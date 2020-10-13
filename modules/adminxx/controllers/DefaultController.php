<?php
namespace app\modules\adminxx\controllers;

use Yii;
use app\components\AccessControl;

class DefaultController extends MainController
{
   /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'index',
                    ],
                    'roles'      => ['menuAdminxMain', ],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $userPermissions = Yii::$app->authManager->userRolesPermissions;
        $buttons = [
            'users' => (isset($userPermissions['adminUsersView']))
                ? [
                    'show' => true,
                    'name' => 'Користувачі',
                    'route' => '/adminxx/user'
                ]
                : [
                    'show' => false,
                ],
            'rules' => (isset($userPermissions['adminAuthItemList']))
                ? [
                    'show' => true,
                    'name' => 'Правила',
                    'route' => '/adminxx/rule'
                ]
                : [
                    'show' => false,
                ],
            'authItems' => (isset($userPermissions['adminAuthItemList']))
                ? [
                    'show' => true,
                    'name' => 'Дозвіли, ролі',
                    'route' => '/adminxx/auth-item'
                ]
                : [
                    'show' => false,
                ],
            'menuEdit' => (isset($userPermissions['adminMenuEdit']))
                ? [
                    'show' => true,
                    'name' => 'Редактор меню',
                    'route' => '/adminxx/menux/menu'
                ]
                : [
                    'show' => false,
                ],
            'configs' => (isset($userPermissions['adminConfigUpdate']))
                ? [
                    'show' => true,
                    'name' => 'Системні налаштування',
                    'route' => '/adminxx/configs/update'
                ]
                : [
                    'show' => false,
                ],
            'guestControl' => (isset($userPermissions['adminGuestControl']))
                ? [
                    'show' => true,
                    'name' => 'Відвідування сайту',
                    'route' => '/adminxx/check/guest-control'
                ]
                : [
                    'show' => false,
                ],
            'PHPinfo' => (isset($userPermissions['menuAdminxMain']))
                ? [
                    'show' => true,
                    'name' => 'PHP-info',
                    'route' => 'adminxx/user/php-info'
                ]
                : [
                    'show' => false,
                ],

        ];
        $r=1;
        return $this->render('index',
            [
                'buttons' => $buttons,
            ]);
    }
}
