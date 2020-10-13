<?php

namespace app\controllers;

use Yii;
use app\components\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\adminxx\models\form\Login;

class SiteController extends Controller
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
                        'index', 'error'
                    ],
                    'roles'      => [
                        '@', '?'
                    ],
                ],
                [
                    'allow'      => true,
                    'actions'    => [
                        'index',
                    ],
                    'roles'      => ['menuAdminxMain', ],
                ],
                [
                    'allow' => true,
                    'actions' => ['login' ],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'actions' => ['logout'],
                    'roles' => ['@'],
                ],

            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],

        ];

        return $behaviors;
    }


    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogin()
    {
        //  $this->layout = '@app/views/layouts/commonLayout.php';
        $model = new Login();
        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $query = 'SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY,", ""))';
            Yii::$app->db->createCommand($query)->execute();

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        return $this->redirect('/site/index');
    }



    public function actionError()
    {
       // $this->layout = '@app/views/layouts/commonLayout.php';

        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error',
                [
                    'exception' => $exception,
                     'message' => $exception->getMessage(),
                    ]);
        }
    }

}
