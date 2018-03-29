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

    /**
     * @return array
     */
    public function actionAjaxHistoryPost()
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $model = new GmsHistory();

        //todo если плейлист не изменился или нет подходящего плейлиста то историю не сохраняем
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['state' => 1];
        } else {
            Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
            return ['state' => 0];
        }
    }

    /**
     * @return array
     */
    public function actionAjaxVideoHistoryPost()
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $model = new GmsVideoHistory();
        if ($model->load(Yii::$app->request->post())) {

            $model->last_at = $model->created_at;
            /** @var GmsVideoHistory $findModel */
            $findModel = GmsVideoHistory::find()
                ->where([
                    'device_id' => $model->device_id,
                    'pls_id' => $model->pls_id,
                ])->limit(1)->orderBy(['created_at' => SORT_DESC])->one();

            if ($findModel
                && $findModel->video_key == $model->video_key
                && $findModel->load(Yii::$app->request->post())
            ) {
                $findModel->last_at = $model->created_at;
                $findModel->created_at = $findModel->oldAttributes['created_at'];
                if ($findModel->save()) {
                    return ['state' => 1];
                } else {
                    Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
                    return ['state' => 0];
                }
            }

            if ($model->save()) {
                return ['state' => 1];
            } else {
                Yii::getLogger()->log($model->errors, Logger::LEVEL_ERROR, 'binary');
                return ['state' => 0];
            }

        }
        return ['state' => 0];
    }
}