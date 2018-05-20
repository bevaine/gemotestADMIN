<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);

return [
    'timeZone' => 'Europe/Moscow',
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'gms' => [
            'basePath' => '@app/modules/gms',
            'class' => 'api\modules\gms\Module'
        ]
    ],
    'components' => [        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'db'=>[
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except'=>['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
                    'prefix'=>function () {
                        $url = Yii::$app->request->isConsoleRequest
                            ? implode('::', [Yii::$app->controller->id, Yii::$app->controller->action->id])
                            : Yii::$app->request->getUrl();

                        return sprintf('[%s][%s]', Yii::$app->id, $url);
                    },
                    'logVars'=>[],
                    'logTable'=>'{{%system_log}}'
                ]
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'baseUrl' => 'http://itr-local.admin',
            'rules' => [
                'POST /gms/playlist' => 'gms/playlist/view',
                //'GET /gms/playlist' => 'gms/playlist/view',
                'POST /gms/history' => 'gms/history/ajax-history-post',
                'POST /gms/video-history' => 'gms/history/ajax-video-history-post',
            ],
        ]
    ],
    'params' => $params,
];



