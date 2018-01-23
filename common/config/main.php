<?php
return [
    'language' => 'ru',
    'name' => 'GemoTest::fix',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter'  => [
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone'        => 'Europe/Moscow',
            'dateFormat'      => 'dd.MM.Y',
            'timeFormat'      => 'HH:mm:ss',
            'datetimeFormat'  => 'yyyy-MM-dd HH:mm:ss'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=work',
            'username' => 'admin',
            'password' => 'itrTest',
            'charset' => 'utf8',
        ],
        'userDB' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=work',
            'username' => 'admin',
            'password' => 'itrTest',
            'charset' => 'utf8',
        ],
        'GemoTestDB' => [
        //'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:Server=sw-sky-cl;Database=OrdersFromCACHE',
            'username' => 'importfromcache',
            'password' => 'import',
            'charset' => 'utf8',
        ],
        'MIS' => [
        //'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:Server=sw-sky-cl;Database=MIS',
            'username' => 'importfromcache',
            'password' => 'import',
            'charset' => 'utf8',
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
            'showScriptName' => false,
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
