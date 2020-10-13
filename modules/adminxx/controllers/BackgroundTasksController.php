<?php

namespace app\modules\adminxx\controllers;

use app\commands\backgroundTasks\models\BackgroundTask;
use app\modules\adminxx\models\filters\BackgroundTaskFilter;
use app\components\conservation\ActiveDataProviderConserve;
use app\components\AccessControl;

/**
 * Class BackgroundTasksController
 * @package app\modules\adminxx\controllers
 */
class BackgroundTasksController extends MainController
{

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'index', 'modal-open-background-task',
                        'modal-open-background-task-delete-confirm', 'background-task-delete',
                        'modal-open-background-task-logs',
                    ],
                    'roles'      => ['adminSystem'],
                ],
            ],
        ];
        return $behaviors;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
      //  $this->layout = '@app/modules/adminxx/views/layouts/adminxx.php';

        $dataProvider = new ActiveDataProviderConserve([
           // 'searchId' => $id,
            'filterModelClass' => BackgroundTaskFilter::class,
            'conserveName' => 'backgroundTasksGrid',
            'pageSize' => 15,
        ]);
        $r=1;
        if (\Yii::$app->request->isPost){
            return $this->redirect('index');
        }
        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionView($id)
    {
        $task = BackgroundTask::findOne($id);
        return $this->render('view', [
            'task' => $task,
        ]);
    }

    /**
     * @param $id
     * @param $mode
     * @return string
     */
    public function actionModalOpenBackgroundTask($id, $mode)
    {
        $task = BackgroundTask::findOne($id);
        $taskResultFileFullName = $task->taskResultFileFullName;
        $resultContent = (file_exists($taskResultFileFullName))
            ? file_get_contents($taskResultFileFullName)
            : '';
        $resultContent = (!empty($resultContent))
            ? str_replace(PHP_EOL, '<br>', $resultContent)
            : 'Results file not found';

        return $this->renderAjax('_form_background_task', [
            'task' => $task,
            'mode' => $mode,
            'resultContent' => $resultContent,
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBackgroundTaskDelete()
    {
        if (\Yii::$app->request->isPost){
            $_post = \Yii::$app->request->post();
            if (isset($_post['id'])){
                $this->result = BackgroundTask::removeTasks($_post['id']);
            }
        }
        return $this->asJson($this->result);
    }

    /**
     * @param $id
     * @param $mode
     * @return string
     */
    public function actionModalOpenBackgroundTaskLogs($mode)
    {

        if ($mode == 'deleteUnnecessaryTasks') {
            $result = BackgroundTask::deleteUnnecessaryTasks();
            $content = ($result['status'])
                ? '<h3>Зайвi завдання успiшно видаленi</h3><br>'
                : '<h3 style="color: red">Зайвi завдання видаленi з помилками</h3><br>';
            $content .= str_replace(PHP_EOL, '<br>', $result['data']);
        } else {
            $logsNames = BackgroundTask::getFullLogsNames();
            switch ($mode) {
                case 'success':
                    $logFile = $logsNames['logSuccessFileFullName'];
                    break;
                case 'error':
                    $logFile = $logsNames['logErrorFileFullName'];
                    break;
            }
            $content = (file_exists($logFile))
                ? file_get_contents($logFile)
                : '';
            $content = (!empty($content))
                ? str_replace(PHP_EOL, '<br>', $content)
                : 'Log file not found';
        }


        return $this->renderAjax('_form_background_tasks_logs', [
            'content' => $content,
        ]);
    }

}