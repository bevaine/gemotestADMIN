<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 13.03.2018
 * Time: 17:11
 */

namespace api\modules\gms\controllers;

use common\models\GmsVideoHistory;
use yii\rest\ActiveController;
use yii;
use common\models\GmsHistory;
use yii\log\Logger;
use yii\filters\VerbFilter;


class HistoryController extends ActiveController
{
    public $modelClass = 'common\models\GmsHistory';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'], //delete this string to may GET
                ],
            ],
        ];
    }

    public function actionAjaxHistoryPost()
    {
        $model = new GmsHistory();

        //todo если плейлист не изменился или нет подходящего плейлиста то историю не сохраняем
        if ($model->save() && $model->load(Yii::$app->request->post())) {
            return json_encode(['state' => 1]);
        } else {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
            return json_encode(['state' => 0]);
        }
    }

    public function actionAjaxVideoHistoryPost()
    {
        $model = new GmsVideoHistory();

        if ($model->save() && $model->load(Yii::$app->request->post())) {
            print_r($model->load(Yii::$app->request->post()));
            return json_encode(['state' => 1]);
        } else {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
            return json_encode(['state' => 0]);
        }
    }
}