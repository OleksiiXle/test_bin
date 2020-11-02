<?php
namespace app\modules\adminxx\models\filters;

use app\components\models\Translation;
use app\widgets\xlegrid\models\GridFilter;

class TranslationFilter extends GridFilter
{
    public $queryModel = Translation::class;

    public $id;

    private $_filterContent = null;

    public function getFilterContent()
    {
        if ($this->_filterContent === null) {
            $this->getQuery();
        }

        return $this->_filterContent;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    public function getQuery()
    {
        $tmp = 1;
        $query = Translation::find()->where(['language' => \Yii::$app->language]);
        $this->_filterContent = '';
        if ($this->showOnlyChecked =='1' && !empty($this->checkedIdsJSON)) {
            $checkedIds = json_decode($this->checkedIdsJSON);
            $query->andWhere(['IN', 'id', $checkedIds]);
            $this->_filterContent .= ' * Только отмеченные*;' ;
            return $query;
        }

        if (!$this->validate()) {
            return $query;
        }

     //   $e = $query->createCommand()->getSql();

        return $query;


    }

    public function getDataForUpload()
    {
        return [
            'id' => [
                'label' => 'id',
                'content' => 'value'
            ],
            'app' => [
                'label' => 'app',
                'content' => 'value'
            ],
            'language' => [
                'label' => 'language',
                'content' => 'value'
            ],
            'message' => [
                'label' => 'message',
                'content' => 'value'
            ],
            /*
            'status' => [
                'label' => 'Статус',
                'content' => function($model)
                {
                    return ($model->status == UserM::STATUS_ACTIVE) ? 'active' : 'not active';
                }
            ],
            */
        ];
    }

}