<?php
$t = [
    [
        'name' => 'Адміністрування',
        'route' => '',
        'role' => 'menuAdminxMain',
        'access_level' => 2,
        'children' => [
            [
                'name'       => 'Пользователи',
                'route'      => '/adminxx/user',
                'role' => 'menuAdminUsersView',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Правила',
                'route'      => '/adminxx/rule',
                'role' => 'menuAdminAuthItemList',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Разрешения',
                'route'      => '/adminxx/auth-item',
                'role' => 'menuAdminAuthItemList',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Редактор меню',
                'route'      => '/adminxx/menux/menu',
                'role' => 'menuAdminMenuEdit',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Симтемные настройки',
                'route'      => '/adminxx/configs/update',
                'role' => 'menuAdminConfigUpdate',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Посещение сайта',
                'route'      => '/adminxx/check/guest-control',
                'role' => 'menuAdminGuestControl',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Переводы',
                'route'      => '/adminxx/translation/index',
                'role' => 'menuAdminTranslateUpdate',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'PHP-info',
                'route'      => 'adminxx/user/php-info',
                'role' => 'menuAdminxMain',
                'access_level' => 2,
                'children' => [],
            ],
        ]
    ],

    //********************************************************************************************************** КАБИНЕТ
    [
        'name' => 'Кабинет',
        'route' => '',
        'role' => 'menuAll',
        'access_level' => 0,
        'children' => [
            [
                'name'       => 'Зміна паролю',
                'route'      => '/adminxx/user/change-password',
                'role' => 'menuAll',
                'access_level' => 0,
                'children' => [],
            ],
        ]
    ],
    //********************************************************************************************************** ПОСТЫ
    [
        'name' => 'Посты',
        'route' => '',
        'role' => 'menuAll',
        'access_level' => 0,
        'children' => [
            [
                'name'       => 'Список постов',
                'route'      => '/post/post',
                'role' => 'menuAll',
                'access_level' => 0,
                'children' => [],
            ],
        ]
    ],
    [
        'name'       => 'Вход',
        'route'      => '/adminxx/user/login',
        'role' => '',
        'access_level' => 0,
        'children' => [],
    ],
];

return $t;