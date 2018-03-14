<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 13.03.2018
 * Time: 17:11
 */

namespace api\modules\gms\controllers;

use yii\rest\ActiveController;
use yii;
use common\models\GmsHistory;
use yii\log\Logger;


class HistoryController extends ActiveController
{
    public $modelClass = 'common\models\GmsHistory';

    public function actionAjaxHistoryPost()
    {
        $model = new GmsHistory();
        $model->load(Yii::$app->request->post());

        if (!$model->save()) {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
        }
    }
}