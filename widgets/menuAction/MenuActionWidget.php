<?php

namespace app\widgets\menuAction;

use yii\base\Widget;

class MenuActionWidget extends Widget
{
    public $icon = "glyphicon glyphicon-list";
    public $items = [
        'text' => 'route',
    ];
    public $offset = 0;


    public function run()
    {

        return $this->render('menuAction',
            [
                'icon' => $this->icon,
                'items' => $this->items,
                'offset' => $this->offset,
            ]);
    }

}
