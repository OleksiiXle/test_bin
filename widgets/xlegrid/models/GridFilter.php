<?php

namespace app\widgets\xlegrid\models;

use yii\base\Model;

class GridFilter extends Model
{
    public $checkedIds = [];
    public $checkedIdsJSON = '{}';
    public $showOnlyChecked;
    public $queryModel;

    private $_filterContent;

    public function getQuery($params = null)
    {
        $query = ($this->queryModel)::find();
       //    $e = $query->createCommand()->getSql();

        return $query;
    }

    public function getFilterContent(){
        $this->_filterContent = '';
/*
        if (!empty($this->first_name)) {
            $this->_filterContent .= ' Ім"я *' . $this->first_name . '*;' ;
        }
*/

        return $this->_filterContent;
    }

    public function getDataForUpload()
    {
        return [
            'attribute' => [
                'label' => 'Attribute label',
                'content' => 'value'
            ],
            'callBack' => [
                'label' => 'Call back label',
                'content' => function($model)
                {
                    return ($model->id == 1) ? 'id = 1' : 'id != 1';
                }
            ],
        ];
    }

}