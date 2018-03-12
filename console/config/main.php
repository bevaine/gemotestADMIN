<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => 'http://itr-local.admin'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=work',
            'username' => 'admin',
            'password' => 'itrTest',
            'charset' => 'utf8',
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
    ],
    'params' => $params,
];
