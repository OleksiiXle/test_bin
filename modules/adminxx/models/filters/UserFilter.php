<?php
namespace app\modules\adminxx\models\filters;

use app\modules\adminxx\models\UserData;
use app\modules\adminxx\models\UserM;
use yii\base\Model;

class UserFilter extends Model
{
    public $checkedIds = [];
    public $chekedAll = false;

    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $username;
    public $emails;

    public $role;
    public $permission;
    private $_roleDict;

    /**
     * @return mixed
     */
    public function getRoleDict()
    {
        $roles = \Yii::$app->authManager->getRoles();
        $this->_roleDict['0'] = 'Не визначено';
        foreach ($roles as $role){
            $this->_roleDict[$role->name] = $role->name;
        }

        return $this->_roleDict;
    }
    public $permissionDict;
    public $additionalTitle = '';

  //  public $showStatusAll;
    public $showStatusActive;
    public $showStatusInactive;

    private $_filterContent;




    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['first_name', 'middle_name', 'last_name', 'role', 'username', 'emails'], 'string', 'max' => 50],
            [['first_name', 'middle_name', 'last_name'],  'match', 'pattern' => UserM::USER_NAME_PATTERN,
                'message' => \Yii::t('app', UserM::USER_NAME_ERROR_MESSAGE)],
            [['username'],  'match', 'pattern' => UserM::USER_PASSWORD_PATTERN,
                'message' => \Yii::t('app', UserM::USER_PASSWORD_ERROR_MESSAGE)],
            [['id', ], 'integer'],
            [['first_name', 'middle_name', 'last_name', 'role'], 'string', 'max' => 50],
            [[ 'showStatusActive', 'showStatusInactive'], 'boolean'],
            ['emails', 'email'],



        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логін',
            'first_name' => 'Імя',
            'middle_name' => 'По батькові',
            'last_name' => 'Прізвище',
            'phone' => 'Телефон',
            'auth_key' => 'Ключ авторізації',
            'password' => 'Пароль',
            'password_hash' => 'Пароль',
            'oldPassword' => 'Старий пароль',
            'retypePassword' => 'Підтвердждення паролю',
            'password_reset_token' => 'Токен збросу паролю',
            'emails' => 'Email',
            'status' => 'Status',
            'created_at_str' => 'Створений',
            'updated_at_str' => 'Змінений',
            'time_login_str' => 'Увійшов',
            'time_logout_str' => 'Вийшов',
            'role' => 'Роль користувача',
            'showStatusAll' => 'Всі',
            'showStatusActive' => 'Активні',
            'showStatusInactive' => 'Не активні',
        ];
    }


    public function getQuery($params = null)
    {
        $tmp = 1;
        $query = UserM::find()
            ->joinWith(['userDatas'])
          //  ->joinWith(['userDatas.personal']);
        ;

        if (!$this->validate()) {
            return $query;
        }

        if (!empty($this->role)) {
            $query ->innerJoin('auth_assignment aa', 'user.id=aa.user_id')
                ->innerJoin('auth_item ai', 'aa.item_name=ai.name')
                ->where(['ai.type' => 1])
            ;
        }


        if (!empty($this->emails)) {
            $query->andWhere(['LIKE', 'user.emails', $this->emails]);
        }

        if (!empty($this->username)) {
            $query->andWhere(['user.username' => $this->username]);
        }

        if (!empty($this->role)) {
            $query->andWhere(['aa.item_name' => $this->role]);
        }


        if (!empty($this->first_name)) {
            $query->andWhere(['like', 'user_data.first_name', $this->first_name]);
        }

        if (!empty($this->middle_name)) {
            $query->andWhere(['like', 'user_data.middle_name', $this->middle_name]);
        }

        if (!empty($this->last_name)) {
            $query->andWhere(['like', 'user_data.last_name', $this->last_name]);
        }

        if ($this->showStatusActive =='1'){
            $query->andWhere(['user.status' => UserM::STATUS_ACTIVE]);
        }

        if ($this->showStatusInactive =='1'){
            $query->andWhere(['user.status' => UserM::STATUS_INACTIVE]);
        }
           $e = $query->createCommand()->getSql();

        return $query;


    }

    public function getFilterContent(){
        $this->_filterContent = '';

        if (!empty($this->first_name)) {
            $this->_filterContent .= ' Ім"я *' . $this->first_name . '*;' ;
        }

        if (!empty($this->middle_name)) {
            $this->_filterContent .= ' По-батькові *' . $this->middle_name . '*;' ;
        }

        if (!empty($this->last_name)) {
            $this->_filterContent .= ' Прізвище *' . $this->last_name . '*;' ;
        }

        if (!empty($this->username)) {
            $this->_filterContent .= ' Логін *' . $this->username . '*;' ;
        }

        if (!empty($this->emails)) {
            $this->_filterContent .= ' Email *' . $this->emails . '*;' ;
        }

        if (!empty($this->role)) {
            $this->_filterContent .= ' Роль *' . $this->roleDict[$this->role] . '*;' ;
        }

        if ($this->showStatusActive =='1'){
            $this->_filterContent .= ' * Тількі активні*;' ;
        }

        if ($this->showStatusInactive =='1'){
            $this->_filterContent .= ' * Тількі неактивні*;' ;
        }

        return $this->_filterContent;
    }
}