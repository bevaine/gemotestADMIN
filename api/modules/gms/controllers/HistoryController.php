<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 13.03.2018
 * Time: 17:11
 */

namespace api\modules\gms\controllers;

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

        if (!empty(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();

            if (empty($post["pls_id"]))
                return ['state' => 0];

            if (!$findModel = GmsPlaylistOut::findOne($post["pls_id"]))
                return ['state' => 0];

            $arrJsonKodi = ArrayHelper::toArray(json_decode($findModel->jsonKodi));
            $arr_pos_all = ArrayHelper::getColumn($arrJsonKodi["children"], 'pos_in_all');

            $arr_pos_list = ArrayHelper::getColumn($arrJsonKodi["children"], 'pos_in_list');
            $arr_merge_list = array_combine($arr_pos_list, $arr_pos_all);

            foreach ($post["inf"] as $pos_in_list => $time_start_end) {

                if (!array_key_exists($pos_in_list, $arr_merge_list))
                    continue;

                $current_pos_all = $arr_merge_list[$pos_in_list];

                $videoHistoryModel = GmsVideoHistory::findOne([
                    'pos_pls' => $current_pos_all,
                    'pls_guid' => $post["pls_guid"]
                ]);

                if (!$videoHistoryModel) {
                    $videoHistoryModel = new GmsVideoHistory();
                }

                $videoHistoryModel->pls_pos = $current_pos_all;
                $videoHistoryModel->pls_guid = $post["pls_guid"];
                $videoHistoryModel->device_id = $post["device_id"];
                $videoHistoryModel->pls_id = $post["pls_id"];
                $videoHistoryModel->created_at = $time_start_end['start'];
                $videoHistoryModel->last_at = $time_start_end['end'];

                if ($videoHistoryModel->save()) {
                    return ['state' => 1];
                } else {
                    Yii::getLogger()->log($videoHistoryModel->errors, Logger::LEVEL_ERROR, 'binary');
                    return ['state' => 0];
                }
            }
        }

        return ['state' => 0];
    }
}