<?php

return [
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    'mode' => 'dev',
    'supportEmail' => 'robot@devreadwrite.com',
    'user.passwordResetTokenExpire' => '3600',

    //  'menuType' => 'horizontal',
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

    'pathToFiles' => '/files',
    'max_execution_time' => '60',
    /*
     * memory_limit = 64M
     * upload_max_filesize = 5M
     * max_file_uploads = 10
     * post_max_size = 5M
     */

    'image' => [
        'maxSize' => '5000000',
        'availableExtensions' => [
            'jpg',
            'png',
        ]
    ],





    'pathToBackgroundTasksLogs' => '/runtime/logs/backgroundTasks/',
    'pathToBackgroundTasksTmpFiles' => '/runtime/logs/backgroundTasks/tmp/',
    'killBackgroundTaskAfterDone' => false,

];
