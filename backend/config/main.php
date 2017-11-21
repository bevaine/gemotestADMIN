<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
        'user' => [
            'class' => 'budyaga\users\Module',
            'userPhotoUrl' => 'http://example.com/uploads/user/photo',
            'userPhotoPath' => '@frontend/web/uploads/user/photo'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => 'backend\views'
                ],
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'budyaga\users\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'budyaga\users\components\oauth\VKontakte',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                    'scope' => 'email'
                ],
                'google' => [
                    'class' => 'budyaga\users\components\oauth\Google',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                ],
                'facebook' => [
                    'class' => 'budyaga\users\components\oauth\Facebook',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                ],
                'github' => [
                    'class' => 'budyaga\users\components\oauth\GitHub',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                    'scope' => 'user:email, user'
                ],
                'linkedin' => [
                    'class' => 'budyaga\users\components\oauth\LinkedIn',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                ],
                'live' => [
                    'class' => 'budyaga\users\components\oauth\Live',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                ],
                'yandex' => [
                    'class' => 'budyaga\users\components\oauth\Yandex',
                    'clientId' => 'XXX',
                    'clientSecret' => 'XXX',
                ],
                'twitter' => [
                    'class' => 'budyaga\users\components\oauth\Twitter',
                    'consumerKey' => 'XXX',
                    'consumerSecret' => 'XXX',
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/signup' => '/user/user/signup',
                '/login' => '/site/login',
                '/logout' => '/user/user/logout',
                '/requestPasswordReset' => '/user/user/request-password-reset',
                '/resetPassword' => '/user/user/reset-password',
                '/profile' => '/user/user/profile',
                '/retryConfirmEmail' => '/user/user/retry-confirm-email',
                '/confirmEmail' => '/user/user/confirm-email',
                '/unbind/<id:[\w\-]+>' => '/user/auth/unbind',
                '/oauth/<authclient:[\w\-]+>' => '/user/auth/index',
                'GET /admin/logins/create/<param>' => '/admin/logins/create',
                'POST /admin/logins/create/<param>' => '/admin/logins/create'
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
];
