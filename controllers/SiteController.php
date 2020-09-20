<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Binar;

class SiteController extends Controller
{
    /**
     * Creating a test binar with 5 levels and displaying a form to manage it
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        Binar::deleteAll();
        Binar::makeTestBinars(0);

        return $this->render('binar');
    }
}
