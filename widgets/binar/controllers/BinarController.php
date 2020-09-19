<?php

namespace app\widgets\binar\controllers;

use app\models\Binar;
use yii\web\Controller;

class BinarController extends Controller
{
    public $result = [
        'status' => false,
        'data' => [],
    ];


    public function actionGetDefaultTree()
    {
        $root = Binar::findOne(['parent_id' => 0]);
        $this->result = [
            'status' => true,
            'data' => (!empty($root)) ? $root->nodeInfo : [],
        ];

        return $this->asJson($this->result);
    }

    public function actionGetChildren()
    {
        $_post = \yii::$app->request->post();
        if (isset($_post['id'])) {
            $children = Binar::find()
                ->where(['parent_id' => $_post['id']])
                ->orderBy('position')
                ->all();
            $this->result['status'] = true;
            foreach ($children as $child) {
                $this->result['data'][] = $child->nodeInfo;
            }
        }

        return $this->asJson($this->result);
    }

    public function actionGetBinarInfo($id = 0)
    {
        $binar = Binar::findOne($id);
        if (isset($binar)){
            return $this->renderAjax('@app/widgets/binar/views/_binarInfo', [
                'binar' => $binar,
            ]);
        } else {
            return 'Not found';
        }
    }

    public function actionModalOpenUpdate($id, $binar_id)
    {
        $binar = Binar::findOne($id);
        if (isset($binar)){
            return $this->renderAjax('@app/widgets/binar/views/_binarUpdate', [
                'binar_id' => $binar_id,
                'binar' => $binar,
            ]);
        } else {
            return 'Not found';
        }
    }

    public function actionAddChild()
    {
        $r=2;
        if ($data = \Yii::$app->request->post('Binar')){
            $binar = Binar::findOne($data['id']);
            if (!empty($binar)){
                if ($binar->appendChild($data)){
                    $this->result = $binar->result;
                } else {
                    $this->result['data'] = $binar->result['data'];
                }
            }
        }
        return $this->asJson($this->result);
    }

    public function actionBinarDelete()
    {
        $_post = \Yii::$app->request->post();
        $this->result = Binar::deleteWithChildren($_post['id']);
        return $this->asJson($this->result);
    }



}