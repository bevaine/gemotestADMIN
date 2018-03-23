<?php

namespace api\modules\gms\controllers;

use common\components\helpers\FunctionsHelper;
use common\models\GmsDevices;
use common\models\GmsPlaylistOut;
use yii\web\ForbiddenHttpException;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use DateTime;
use DateTimeZone;

/**
 * @property GmsDevices $modelDevice
 * @property integer $timeForTimeZone
 * @property integer $currentDate
 * @property integer $currentTime
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class PlaylistController extends ActiveController
{
    public $modelDevice;
    public $timeForTimeZone;
    public $currentDate;
    public $currentTime;

    /**
     * @var string
     */
    public $modelClass = 'common\models\GmsPlaylistOut';

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

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
     * @throws ForbiddenHttpException
     */
    public function actionView()
    {
        if (empty(Yii::$app->request->post()['dev'])) {
            throw new ForbiddenHttpException('The requested page does not exist.');
        }

        $response = [];
        $out['state'] = 0;
        $timezone = "Europe/Moscow";
        $dev = Yii::$app->request->post()['dev'];
        $dt = new DateTime('now', new DateTimeZone($timezone));
        $date_db = $dt->format("Y-m-d H:i:s P");

        if (!$modelDevices = GmsDevices::findOne(['device' => $dev])) {
            $modelDevices = new GmsDevices();
            $modelDevices->scenario = 'addDevice';
            $modelDevices->device = $dev;
            $modelDevices->auth_status = 0;
            $modelDevices->created_at = $date_db;
            $modelDevices->last_active_at = $date_db;
        } else {
            $modelDevices->scenario = 'editDevice';
            if (!empty($modelDevices->timezone)) {
                $timezone = $modelDevices->timezone;
            }
            $this->timeForTimeZone = FunctionsHelper::getTimestampForTimeZone($dt->getTimestamp(), $timezone);
            $last_active_at = new DateTime('now', new DateTimeZone($timezone));
            $modelDevices->last_active_at = $last_active_at->format("Y-m-d H:i:s P");
        }

        //todo проверка на авторизацию устройства
        if (!empty($modelDevices->auth_status)) {

            if ($modelDevices->playListOutModel) {

                //todo если плейлист назначен в ручную
                $out['state'] = 1;
                $out['pls'] = [
                    'id' => $modelDevices->playListOutModel->id,
                    'files' => $modelDevices->playListOutModel->getVideos(),
                    'm3u' => Json::decode($modelDevices->playListOutModel->jsonPlaylist)
                ];
            } else {

                //todo поиск подходящего по параметрам плейлиста
                $this->modelDevice = $modelDevices;

                /** @var $plsID GmsPlaylistOut */
                //todo проверка на соотвествие плейлиста на устройстве и подобранного автоматически
                if ($plsID = $this->getCurrentPlaylist()) {
                    $out['state'] = 1;
                    $out['pls'] = [
                        'id' => $plsID->id,
                        'files' => $plsID->getVideos(),
                        'm3u' => Json::decode($plsID->jsonPlaylist)
                    ];
                }
            }
        }

        if (!$modelDevices->save()) {
            Yii::getLogger()->log([
                'actionView:$modelDevices->save()' => $modelDevices->getErrors()
            ], 1, 'binary');
        }

        if (empty($modelDevices->auth_status)) {
            throw new ForbiddenHttpException('The requested page does not exist.');
        }

        $response['result'] = $out;
        return json_encode($response);
    }

    /**
     * @param bool $day
     * @return bool|GmsPlaylistOut
     */
    private function getPlaylist($day = false)
    {
        $arr_with_dev = '';
        $arr_without_dev = '';

        $this->currentDate = GmsPlaylistOut::getDateWithoutTime($this->timeForTimeZone);
        $this->currentTime = GmsPlaylistOut::getTimeDate($this->timeForTimeZone);

        $findPlaylist = GmsPlaylistOut::find()
            ->andFilterWhere(['region_id' => $this->modelDevice->region_id])
            ->andFilterWhere(['sender_id' => $this->modelDevice->sender_id])
            ->andWhere([
                'OR',
                ['device_id' => $this->modelDevice->id],
                ['is', 'device_id' , null]
            ])
            ->andWhere(['<=', 'date_start', $this->currentDate])
            ->andWhere(['>=', 'date_end', $this->currentDate])
            ->andWhere(['<=', 'time_start', $this->currentTime])
            ->andWhere(['>=', 'time_end', $this->currentTime])
            ->andWhere(['=', 'active', 1]);

        if ($day) {
            $weekKeys = array_combine(
                array_keys(
                    array_fill(1, 7, '')
                ), array_keys(GmsPlaylistOut::WEEK)
            );

            $currentDay = date("N", time());
            $currentDayField = $weekKeys[$currentDay];
            $findPlaylist->andWhere(['=', $currentDayField, 1]);
        }

        foreach ($findPlaylist->each() as $model) {
            /** @var GmsPlaylistOut $model */
            if (!empty($model->device_id)) $arr_with_dev = $model;
            else $arr_without_dev = $model;
        }

        if (!empty($arr_with_dev)) return $arr_with_dev;
        elseif (!empty($arr_without_dev)) return $arr_without_dev;
        else return false;
    }

    /**
     * @return bool|yii\db\ActiveRecord
     */
    private function getCurrentPlaylist ()
    {
        if ($findPlaylist = self::getPlaylist(true)) {
            return $findPlaylist;
        } elseif ($findPlaylist = self::getPlaylist(false)) {
            $weekCross = array_intersect_key(ArrayHelper::toArray($findPlaylist), GmsPlaylistOut::WEEK);
            $weekCross = array_filter($weekCross);
            if (empty($weekCross)) return $findPlaylist;
            else return false;
        } else {
            return false;
        }
    }
}