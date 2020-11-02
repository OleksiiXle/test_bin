<?php
$t = [
    [
        'name' => 'Адміністрування',
        'route' => '',
        'role' => 'menuAdminxMain',
        'access_level' => 2,
        'children' => [
            [
                'name'       => 'Користувачі',
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
                'name'       => 'Дозвіли, ролі',
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
                'name'       => 'Системні налаштування',
                'route'      => '/adminxx/configs/update',
                'role' => 'menuAdminConfigUpdate',
                'access_level' => 2,
                'children' => []
            ],
            [
                'name'       => 'Відвідування сайту',
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
        'name' => 'Кабінет',
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
    [
        'name'       => 'Вхід',
        'route'      => '/adminxx/user/login',
        'role' => '',
        'access_level' => 0,
        'children' => [],
    ],
];

return $t;