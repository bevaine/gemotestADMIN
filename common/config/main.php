<?php
return [
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru',
    'name' => 'GemoFix:Админка',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter'  => [
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone'        => 'Europe/Moscow',
            'dateFormat'      => 'dd.MM.Y',
            'timeFormat'      => 'HH:mm:ss',
            'datetimeFormat'  => 'yyyy-MM-dd HH:mm:ss'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        'i18n' => [
            'translations' => [
                'app'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'\budyaga\yii2-users\messages'
                ],
                '*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'\budyaga\yii2-users\messages',
                    'fileMap'=>[
                        'common'=>'users.php',
                        'backend'=>'users.php',
                        'frontend'=>'users.php',
                    ],
                    //'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation']
                ],
            ],
        ],
    ],
];
