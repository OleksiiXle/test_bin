<?php
namespace app\controllers;

use app\helpers\Functions;
use yii\helpers\FileHelper;
use yii\web\Controller;
use \app\components\AccessControl;
use app\commands\backgroundTasks\models\BackgroundTask;

/**
 * Class BackgroundTasksController
 * @package app\controllers
 */
class BackgroundTasksController extends Controller
{
    public $result = [
        'taskId' => 0,
        'status' => BackgroundTask::TASK_STATUS_NOT_FOUND,
        'progress' => 0,
        'temporaryResult' => [],
        'result' => '',
    ];

    /**
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'start-task', 'check-task', 'test-background-task', 'upload-result', 'kill-task'
                    ],
                    'roles'      => [
                        '@',
                    ],
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * Создание и запуск новой фоновой задачи
     * @param boolean $checkForAlreadyRunning (get)
     * @param string $model (post)
     * @param string $arguments (post json)
     * @return \yii\web\Response
     */
    public function actionStartTask($checkForAlreadyRunning = false)
    {
        $_post = \Yii::$app->request->post();
        if (isset($_post['model']) && isset($_post['arguments'])) {
            if (!is_array($_post['arguments'])) {
                $arguments = json_decode($_post['arguments'], true);
            } else {
                $arguments = $_post['arguments'];
            }
            $model = $_post['model'];
            if ($checkForAlreadyRunning != 'false') {
                $taskIsAlreadyRunning = BackgroundTask::taskIsRunning($model, $arguments);
                if ($taskIsAlreadyRunning) {
                    $this->result = [
                        'taskId' => 0,
                        'status' => BackgroundTask::TASK_STATUS_ERROR,
                        'progress' => 0,
                        'temporaryResult' => [],
                        'result' => "Task for " . $model . ' and arguments=' . json_encode($arguments) . ' now is already running',
                    ];

                    return $this->asJson($this->result);
                }
            }

            $task = BackgroundTask::newTask($model, $arguments);
            if ($task->hasErrors()) {
                $this->result = [
                    'taskId' => 0,
                    'status' => BackgroundTask::TASK_STATUS_ERROR,
                    'progress' => 0,
                    'temporaryResult' => [],
                    'result' => "Task for " . $model . ' and arguments=' . json_encode($arguments) . ' save error',
                ];
            } else {
                $task->startRun();
                $this->result = BackgroundTask::checkTask($task->id);
            }
        }

        return $this->asJson($this->result);
    }

    /**
     * Проверка состояния выполнения тестовой задачи и получение очередной порции временного результата
     * @param integer $taskId (post)
     * @return \yii\web\Response
     * Array
     * (
     *  [taskId] => 198
     *  [status] => process
     *  [progress] => 32
     *  [temporaryResult] => Array
     *   (
     *      [0] => running job for id=7778 progres=0% step 1 ...
     *      [1] => running job for id=7778 progres=4% step 2 ...
     *      [2] => running job for id=7778 progres=8% step 3 ...
     *      [3] => running job for id=7778 progres=12% step 4 ...
     *   )
     * )
     */
    public function actionCheckTask()
    {
        $_post = \Yii::$app->request->post();
        if (isset($_post['taskId'])) {
            $taskId = $_post['taskId'];
            $this->result = BackgroundTask::checkTask($taskId);
        }

        return $this->asJson($this->result );
    }

    public function actionKillTask()
    {
        $_post = \Yii::$app->request->post();
        if (isset($_post['taskId'])) {
            $taskId = $_post['taskId'];
            $task = BackgroundTask::findOne($taskId);
            if (!empty($task)) {
                $this->result = [
                    'taskId' => $task->id,
                    'status' => $task->status,
                    'progress' => 0,
                    'temporaryResult' => [],
                ];
                if ($task->remove(true)) {
                    $this->result['result'] = 'removed';
                } else {
                    $this->result['result'] = $task->showErrors();
                }
            }
        }

        return $this->asJson($this->result );
    }

    public function actionUploadResult($taskId, $killTask, $killFile, $fileNameColumn)
    {
        $task = BackgroundTask::findOne($taskId);
        if (empty($task)) {
            return 'task not found!!!';
        }
        $fileToUpload = $task->{$fileNameColumn};
        if ($killTask && !$task->remove(false)) {
            return 'task not removed!!!';
        }
        $options['mimeType'] = FileHelper::getMimeTypeByExtension($fileToUpload);
        $attachmentName = basename($fileToUpload);
        \Yii::$app->response->sendFile($fileToUpload, $attachmentName, $options);
        if ($killFile){
            unlink($fileToUpload);
        }
    }

    public function actionTestBackgroundTask()
    {
        $tmp = 1;
        $result = [
            'taskId' => 0,
            'status' => BackgroundTask::TASK_STATUS_NOT_FOUND,
            'progress' => 0,
            'temporaryResult' => [],
            'result' => '',
        ];
        $_post = \Yii::$app->request->post();
        if (isset($_post['model']) && isset($_post['arguments'])) {
            if (!is_array($_post['arguments'])) {
                $arguments = json_decode($_post['arguments'], true);
            } else {
                $arguments = $_post['arguments'];
            }
            $model = $_post['model'];

            $task = BackgroundTask::newTask($model, $arguments);
            if ($task->hasErrors()) {
                $result = [
                    'taskId' => 0,
                    'status' => BackgroundTask::TASK_STATUS_ERROR,
                    'progress' => 0,
                    'temporaryResult' => [],
                    'result' => "Task for " . $model . ' and arguments=' . json_encode($arguments) . ' save error',
                ];
            } else {
                $result = BackgroundTask::runTask($task->id);
            }
        }

        return $this->asJson($result);
    }
}