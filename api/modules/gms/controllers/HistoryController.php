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

            Yii::getLogger()->log([
                '$post'=>$post
            ], Logger::LEVEL_WARNING, 'binary');

            if (!isset($post["pls_id"])
                || empty($post["pls_guid"])
                || empty($post["device_id"]))
                return ['state' => 0];

            if (!$findModel = GmsPlaylistOut::findOne($post["pls_id"]))
                return ['state' => 0];

            if (!$findModelDevice = GmsDevices::findOne([
                'device' => $post["device_id"]
            ])) return ['state' => 0];

            $device_key = $findModelDevice->id;

            if (!empty($findModel->update_json)) {
                $arr_merge_dev = ArrayHelper::toArray(
                    json_decode($findModel->update_json)
                );
            }

            $arr_merge_dev[$device_key] = time();
            $findModel->update_json = json_encode($arr_merge_dev);
            $findModel->save();

            $arrJsonKodi = ArrayHelper::toArray(json_decode($findModel->jsonKodi));
            $arr_pos_all = ArrayHelper::getColumn($arrJsonKodi["children"], 'pos_in_all');
            $arr_pos_list = ArrayHelper::getColumn($arrJsonKodi["children"], 'pos_in_list');

            if (empty($arr_pos_list) || empty($arr_pos_all))
                return ['state' => 0];

            $arr_merge_list = array_combine($arr_pos_list, $arr_pos_all);

            foreach ($post["inf"] as $pos_in_list => $time_start_end) {

                if (!array_key_exists($pos_in_list, $arr_merge_list)) {
                    continue;
                }

                $current_pos_all = $arr_merge_list[$pos_in_list];

                $videoHistoryModel = GmsVideoHistory::findOne([
                    'pls_pos' => $current_pos_all,
                    'pls_guid' => $post["pls_guid"]
                ]);

                if (!$videoHistoryModel) {
                    $videoHistoryModel = new GmsVideoHistory();
                    $videoHistoryModel->created_at = $time_start_end['start'];
                }

                $videoHistoryModel->duration = $time_start_end["duration"];
                $videoHistoryModel->type = $time_start_end["type"];
                $videoHistoryModel->pls_pos = $current_pos_all;
                $videoHistoryModel->pls_guid = $post["pls_guid"];
                $videoHistoryModel->video_key = $time_start_end["key"];
                $videoHistoryModel->device_id = $device_key;
                $videoHistoryModel->pls_id = $post["pls_id"];
                $videoHistoryModel->last_at = $time_start_end['end'];

                if (!$videoHistoryModel->save()) {
                    Yii::getLogger()->log(
                        $videoHistoryModel->errors,
                        Logger::LEVEL_ERROR, 'binary'
                    );
                    return ['state' => 0];
                }
            }
        }

        return ['state' => 1];
    }
}