<?php
return [
    'permissions' => [

        //***************************************************************** Общие роуты
        'menuAll'                          => 'Загальні пункти меню',


        //***************************************************************** Администрирование
        'menuAdminxMain'          => 'Системне адміністрування (меню)',

        'menuAdminUsersView'          => '*** Просмотр списка пользователей (меню)',
        'adminUsersView'              => 'Просмотр списка пользователей',
        'adminUserView'               => 'Просмотр профиля пользователя',
        'adminUserCreate'             => 'Создание пользователя администратором',
        'adminUserUpdate'             => 'Стандартное редактирование пользователя администратором ',
        'adminChangeUserAssignments'  => 'Нестандартное редактирование прав доступа пользователя',
        'adminChangeUserActivity'     => 'Изменение активности пользователя',

        'menuAdminGuestControl'       => '*** Контроль посещения сайта (меню)',
        'adminGuestControl'           => 'Контроль посещения сайта',
        'adminGuestControlDelete'     => 'Очистка БД контроля посещений',

        'menuAdminAuthItemList'       => '*** Просмотр списка ролей, разрешений (меню)',
        'adminAuthItemList'           => 'Просмотр списка ролей, разрешений',
        'adminAuthItemCRUD'           => 'Создание, удаление правила, разрешения',

        'menuAdminMenuEdit'           => '*** Редактор меню (меню)',
        'adminMenuEdit'               => 'Редактор меню',

        'menuAdminConfigUpdate'       => '*** Изменение системных настроек (меню)',
        'adminConfigUpdate'           => 'Изменение системных настроек',


    ],
    'roles' => [
        'adminSuper' => 'Демиург',
        'adminSystem' => 'Системний аднімістратор (Технічний)',
        'adminUsers' => 'Системний аднімістратор (По персоналу)',
        'adminUsersAdvanced' => 'Системний аднімістратор (По персоналу, продвинутий - нестандартнв ролі та дозвіли)',

        'user'        => 'Загальний користувач',
    ],
    'rolesPermissions' => [
        'adminSuper' => [
            'menuAdminxMain',
        ],
        'adminSystem' => [
            'menuAdminxMain',

            'menuAdminUsersView',
            'menuAdminAuthItemList',
            'menuAdminMenuEdit',
            'menuAdminConfigUpdate',
            'menuAdminGuestControl',

            'adminUsersView',
            'adminUserView',
            'adminGuestControl',
            'adminGuestControlDelete',
            'adminAuthItemList',
            'adminAuthItemCRUD',
            'adminMenuEdit',
            'adminConfigUpdate',
        ],
        'adminUsers' => [
            'menuAdminxMain',
            'menuAdminUsersView',
            'menuAdminAuthItemList',
            'menuAdminGuestControl',

            'adminUsersView',
            'adminUserView',
            'adminUserCreate',
            'adminUserUpdate',
            'adminChangeUserActivity',
            'adminGuestControl',
            'adminAuthItemList',
        ],
        'adminUsersAdvanced' => [
            'adminChangeUserAssignments',
        ],
        'user' => [
            'menuAll',
        ],
    ],
    'rolesChildren' => [
        'adminSuper' => [
            'adminSystem',
            'adminUsers',
            'adminUsersAdvanced'
        ],
        'adminSystem' => [
            'user',
        ],
        'adminUsers' => [
            'user',
        ],
        'adminUsersAdvanced' => [
            'adminUsers',
        ],
    ]
];