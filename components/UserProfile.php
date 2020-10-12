<?php

namespace app\components;

use yii\base\Model;

class UserProfile extends Model
{
    const USER_PROFILE_CONSERVE_NAME = 'userProfile';
    const USER_PROFILE_CONSERVE_DURATION = null;

    const POSITION_SORT_OLD = 'sort';
    const POSITION_SORT_V1 = 'sort1';
    const POSITION_SORT_V2 = 'sort2';
    const POSITION_SORT_DEFAULT = 'sort';
    const POSITION_SORT_CONSERVE_NAME = 'positionSort';

    static $itemsList = [
        self::POSITION_SORT_CONSERVE_NAME,
    ];

    public $positionSort;

    public function __construct(array $config = [])
    {
        $userProfile = \Yii::$app->configs->userProfile;
        $this->positionSort = \Yii::$app->configs->positionSort;

        parent::__construct($config);
    }

    static $positionSorts = [
        self::POSITION_SORT_OLD => 'Сортування старе',
        self::POSITION_SORT_V1 => 'Варіант 1',
        self::POSITION_SORT_V2 => 'Варіант 2',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['positionSort',], 'required'],
            [['positionSort',], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'positionSort' =>  'Оберить тип сортування посад',
        ];
    }

    public function save()
    {
        $userProfile[self::POSITION_SORT_CONSERVE_NAME] = $this->positionSort;
        //.....
        //.....
        //.....
        \Yii::$app->configs->userProfile = $userProfile;
        return true;
    }

}
