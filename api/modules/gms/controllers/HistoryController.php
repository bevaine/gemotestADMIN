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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return json_encode(['state' => 1]);
        } else {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
            return json_encode(['state' => 0]);
        }
    }

    public function actionAjaxVideoHistoryPost()
    {
        $model = new GmsVideoHistory();

        if ($model->load(Yii::$app->request->post())) {
            $findModel = GmsVideoHistory::find()
                ->where([
                    'device_id' => $model->device_id,
                    'pls_id' => $model->pls_id,
                    'video_key' => $model->video_key
                ])->orderBy(['created_at' => 'desc'])
                ->limit(1)->one();
            Yii::getLogger()->log($findModel, Logger::LEVEL_ERROR, 'binary');
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return json_encode(['state' => 1]);
        } else {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
            return json_encode(['state' => 0]);
        }
    }
}