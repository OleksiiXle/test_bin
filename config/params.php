<?php

return [
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    'mode' => 'dev',
    //-- for configs component
    'menuType' => 'horizontal',
    'adminEmail' => 'lokoko.xle@ukr.net',
    'userControl' => '1',
    'guestControl' => '1',
    'guestControlDuration' => '3600',
    'permCacheKey' => 'perm',
    'permCacheKeyDuration' => '180',
    'passwordResetTokenExpire' => '3600',
    'userDefaultRole' => 'user',
    'rbacCacheSource' => 'session', //cache
    'signupWithoutEmailConfirm' => '0',
    //----------------------------------------

    /*
     * memory_limit = 64M
     * upload_max_filesize = 5M
     * max_file_uploads = 10
     * post_max_size = 5M
     */
    'pathToFiles' => '/files',

    'image' => [
        'maxSize' => '5000000',
        'availableExtensions' => [
            'jpg',
            'png',
        ]
    ],
    'defaultRoles' => [
        'user',
        'adminUsers',
    ],

    'pathToBackgroundTasksLogs' => '/runtime/logs/backgroundTasks/',
    'pathToBackgroundTasksTmpFiles' => '/runtime/logs/backgroundTasks/tmp/',
    'killBackgroundTaskAfterDone' => false,

];
