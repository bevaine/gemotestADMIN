<?php

namespace api\modules\gms\controllers;

use common\components\helpers\FunctionsHelper;
use common\models\GmsDevices;
use common\models\GmsGroupDevices;
use common\models\GmsPlaylistOut;
use yii\web\ForbiddenHttpException;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use DateTime;
use DateTimeZone;
use yii\db\Query;

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
     * @return array|null
     * @throws ForbiddenHttpException
     */
    public function actionView()
    {
        if (empty(Yii::$app->request->post()['dev'])) {
            throw new ForbiddenHttpException('The requested page does not exist.');
        }

        $resp = [];
        $out['state'] = 0;
        $timezone = "Europe/Moscow";
        $dev = Yii::$app->request->post()['dev'];
        $dt = new DateTime('now', new DateTimeZone($timezone));
        $date_db = $dt->format("Y-m-d H:i:s P");

        if (!$modelDevices = GmsDevices::findOne(['device' => $dev])) {
            $modelDevices = new GmsDevices();
            $modelDevices->scenario = 'addDevice';
            $modelDevices->device = $dev;
            $modelDevices->name = $dev;
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
                    'update_at' => $modelDevices->playListOutModel->update_at,
                    'files' => $modelDevices->playListOutModel->getVideos(),
                    'm3u' => Json::decode($modelDevices->playListOutModel->jsonKodi)
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
                        'update_at' => $plsID->update_at,
                        'files' => $plsID->getVideos(),
                        'm3u' => Json::decode($plsID->jsonKodi)
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

        $resp['result'] = $out;
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($resp) ? $resp : null;
    }

    /**
     * @return array|bool|yii\db\ActiveRecord[]
     */
    function getModelPlaylist()
    {
        $deviceId = '';
        $groupId = '';
        $region_id = '';
        $sender_id = '';

        $this->currentDate = GmsPlaylistOut::getDateWithoutTime($this->timeForTimeZone);
        $this->currentTime = GmsPlaylistOut::getTimeDate($this->timeForTimeZone);

        if ($this->modelDevice) {
            $deviceId = $this->modelDevice->id;
            $region_id = $this->modelDevice->region_id;
            $sender_id = $this->modelDevice->sender_id;
            if ($this->modelDevice->groupDevices) {
                $groupId = $this->modelDevice->groupDevices->group_id;
            }
        }

        $where = [
            'and',
            ['<=', 'date_start', $this->currentDate],
            ['>=', 'date_end', $this->currentDate],
            ['<=', 'time_start', $this->currentTime],
            ['>=', 'time_end', $this->currentTime],
            ['=', 'active', 1]
        ];

        if (!empty($deviceId)) {
            $query = GmsPlaylistOut::find()
                ->where(array_merge(
                    $where,
                    array(['=', 'device_id', $deviceId]))
                )
                ->one();
            if ($query) return $query->toArray();
        }

        if (!empty($groupId)) {
            $query = GmsPlaylistOut::find()
                ->where(array_merge(
                    $where,
                    array(['=', 'group_id', $groupId]))
                )
                ->one();
            if ($query) return $query->toArray();
        }

        if (!empty($region_id)) {
            $query = GmsPlaylistOut::find()
                ->where(array_merge(
                    $where,
                    array(['=', 'region_id', $region_id]))
                )
                ->andFilterWhere(['=', 'sender_id', $sender_id])
                ->one();
            if ($query) return $query->toArray();
        }
        return false;
    }

    /**
     * @return array|bool
     */
    private function getPlaylist()
    {
        $findPlaylist = self::getModelPlaylist();
        if (!$findPlaylist) return false;

        $weekKeys = array_combine(
            array_keys(
                array_fill(1, 7, '')
            ), array_keys(GmsPlaylistOut::WEEK)
        );

        $currentDay = date("N", time());
        $currentDayField = $weekKeys[$currentDay];

        $key_set = ArrayHelper::getColumn($findPlaylist, 'id');
        $dev_set = array_combine($key_set, ArrayHelper::getColumn($findPlaylist, 'device_id'));
        $day_set = array_combine($key_set, ArrayHelper::getColumn($findPlaylist, $currentDayField));
        $created_set = array_combine($key_set, ArrayHelper::getColumn($findPlaylist, 'created_at'));

        //todo если установлен день и устройство
        foreach ($key_set as $key) {
            if (!empty($day_set[$key]) && !empty($dev_set[$key])) {
                //Yii::getLogger()->log(['getPlaylist'=>'установлен день и устройство'], 1, 'binary');
                return [
                    "model" => GmsPlaylistOut::findOne($key),
                    "state" => 1
                ];
            }
        }

        //todo если нет дня но есть устройство
        foreach ($key_set as $key) {
            if (empty($day_set[$key]) && !empty($dev_set[$key])) {
                //Yii::getLogger()->log(['getPlaylist'=>'нет дня но есть устройство'], 1, 'binary');
                return [
                    "model" => GmsPlaylistOut::findOne($key),
                    "state" => 2
                ];
            }
        }

        //todo если есть день но нет устройства
        foreach ($key_set as $key) {
           if (!empty($day_set[$key]) && empty($dev_set[$key])) {
               //Yii::getLogger()->log(['getPlaylist'=>'есть день но нет устройства'], 1, 'binary');
               return [
                   "model" => GmsPlaylistOut::findOne($key),
                   "state" => 3
               ];
            }
        }

        //todo если пустой день и нет устройства, возвращаем по дате создания
        $key_min = min($created_set);
        $key = array_search($key_min, $created_set);
        return [
            "model" => GmsPlaylistOut::findOne($key),
            "state" => 4
        ];
    }

    /**
     * @return bool|yii\db\ActiveRecord
     */
    private function getCurrentPlaylist ()
    {
        if ($findPlaylist = self::getPlaylist()) {
            $model = $findPlaylist["model"];
            return $model;
        } else return false;
    }
}