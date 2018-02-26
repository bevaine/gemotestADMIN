<?php

namespace backend\modules\admin;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return array
     */
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['admin/kontragents'],
                        'roles' => ['GemotestAdmin', 'GMSaccess'],
                        'actions' => ['ajax-kontragents-list']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['GemotestAdmin'],
                    ],
                ],
            ],
        ];
    }
}
