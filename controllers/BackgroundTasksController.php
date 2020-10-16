<?php
namespace app\controllers;

use yii\web\Controller;
use \app\components\AccessControl;
use app\commands\backgroundTasks\models\BackgroundTask;

/**
 * Class BackgroundTasksController
 * @package app\controllers
 */
class BackgroundTasksController extends Controller
{
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
                        'start-task', 'check-task'
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
     * @param string $model (post)
     * @param string $arguments (post json)
     * @return \yii\web\Response
     */
    public function actionStartTask()
    {
       $result = [
            'taskId' => 0,
            'status' => BackgroundTask::TASK_STATUS_NOT_FOUND,
            'progress' => 0,
            'temporaryResult' => [],
            'result' => '',
        ];
        $_post = \Yii::$app->request->post();
        if (isset($_post['model']) && isset($_post['arguments'])) {
            $arguments = json_decode($_post['arguments'], true);
            $model = $_post['model'];
         //   $arguments['id'] = (int)$arguments['id']; //постом приходит стринг а нам надо интеджер
            $taskIsAlreadyRunning = BackgroundTask::taskIsRunning($model, $arguments);
            if ($taskIsAlreadyRunning) {
                $result = [
                    'taskId' => 0,
                    'status' => BackgroundTask::TASK_STATUS_ERROR,
                    'progress' => 0,
                    'temporaryResult' => [],
                    'result' => "Task for " . $model . ' and arguments=' . json_encode($arguments) . ' now is already running',
                ];
            } else {
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
                    $task->startRun();
                    $result = BackgroundTask::checkTask($task->id);
                }
            }
        }

        return $this->asJson($result);
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
            $result = BackgroundTask::checkTask($taskId);
        } else {
            $result = [
                'status' => BackgroundTask::TASK_STATUS_ERROR,
                'result' => "Wrong taskId",
                '_post' => $_post
            ];
       }

        return $this->asJson($result);
    }
}