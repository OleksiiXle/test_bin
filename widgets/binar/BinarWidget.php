<?php

namespace app\widgets\binar;

use yii\base\Widget;

/**
 * Class BinarWidget
 * @package app\widgets\binar
 */
class BinarWidget extends Widget
{
    /**
     * @var
     */
    public $binar_id;
    /**
     * @var
     */
    public $params;

    /**
     * @return string
     */
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
