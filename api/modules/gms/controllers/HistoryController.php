<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 13.03.2018
 * Time: 17:11
 */

namespace api\modules\gms\controllers;

use common\models\GmsDevices;
use common\models\GmsPlaylistOut;
use common\models\GmsVideoHistory;
use yii\rest\ActiveController;
use yii;
use common\models\GmsHistory;
use yii\log\Logger;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


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
        $arr_merge_dev = [];

        if (!empty(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();

            if (!isset($post["pls_id"])
                || empty($post["device_id"])
                || empty($post["guid"])
                || empty($post["type_action"]))
                return ['state' => 0];

            if ($post["type_action"] == 'start' &&
                !isset($post["inf"]["pls_pos"]))
                return ['state' => 0];

            if (!$findModel = GmsPlaylistOut::findOne($post["pls_id"]))
                return ['state' => 0];

            if (!$findModelDevice = GmsDevices::findOne([
                'device' => $post["device_id"]]))
                return ['state' => 0];

            $device_key = $findModelDevice->id;

            if (!empty($findModel->update_json)) {
                $arr_merge_dev = ArrayHelper::toArray(
                    json_decode($findModel->update_json)
                );
            }

            $arr_merge_dev[$device_key] = time();
            $findModel->update_json = json_encode($arr_merge_dev);

            if (!$findModel->save()) {
                Yii::getLogger()->log(
                    $findModel->errors,
                    Logger::LEVEL_ERROR, 'binary'
                );
            }

            if ($post["type_action"] == 'stop')
            {
                $pls_pos = GmsVideoHistory::find()
                    ->where(['pls_guid' => $post["guid"]])
                    ->max('pls_pos');

                if (!$videoHistoryModel = GmsVideoHistory::findOne([
                    'pls_pos' => $pls_pos,
                    'pls_guid' => $post["guid"]
                ])) return ['state' => 0];

                $videoHistoryModel->last_at = $post["datetime"];

            } else {
                $pls_pos = $post["inf"]["pls_pos"];
                $videoHistoryModel = new GmsVideoHistory();
                $videoHistoryModel->created_at = round($post["datetime"]);
                $videoHistoryModel->duration = $post["inf"]["duration"];
                $videoHistoryModel->type = $post["inf"]["type"];
                $videoHistoryModel->pls_pos = $pls_pos;
                $videoHistoryModel->pls_guid = $post["guid"];
                $videoHistoryModel->video_key = $post["inf"]["key"];
                $videoHistoryModel->device_id = $device_key;
                $videoHistoryModel->pls_id = $post["pls_id"];
            }

            if ($videoHistoryModel->save()) {
                return ['state' => 1];
            } else {
                Yii::getLogger()->log(
                    $videoHistoryModel->errors,
                    Logger::LEVEL_ERROR, 'binary'
                );
            }
        }
        return ['state' => 0];
    }
}