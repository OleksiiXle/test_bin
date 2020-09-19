<?php

namespace app\widgets\binar;

use yii\base\Widget;

class BinarWidget extends Widget
{
    public $binar_id;
    public $params;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = $this->getView();
        BinarAssets::register($view);

        return $this->render('binar',
            [
                'binar_id' => $this->binar_id,
                'params' => $this->params,
            ]);
    }
}
