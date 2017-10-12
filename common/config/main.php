<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
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
                    'basePath'=>'@common/messages'
                ],
                '*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                    'fileMap'=>[
                        'common'=>'common.php',
                    ],
                    'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation']
                ],
            ],
        ],
    ],
];
