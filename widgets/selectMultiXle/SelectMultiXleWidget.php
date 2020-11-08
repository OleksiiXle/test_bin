<?php
namespace app\widgets\selectMultiXle;

use yii\base\Widget;

class SelectMultiXleWidget extends Widget
{
    public $selectId;
    public $itemsArray;
    public $modelName;
    public $textAreaAttribute;

    public function init()
    {
        parent::init();
        if (empty($this->selectId)) {
            $this->selectId = 'xleMultiSelect_' . $this->getId();
        }
    }

    public function run()
    {
        $textAreaAttributeId = '';
        $textAreaAttributeName = '';

        $itemsArrayJSON = json_encode($this->itemsArray);
        $view = $this->getView();
        SelectMultiXleAssets::register($view);
        $view->registerJs("jQuery('#$this->selectId')
            .selectMultiXle('$this->selectId', $itemsArrayJSON, '$textAreaAttributeId');");

        return $this->render('selectMultiXle',
            [
                'selectId' => $this->selectId,
                'itemsArray' => $this->itemsArray,
                'textAreaId' => $textAreaAttributeId,
            ]);
    }

}
