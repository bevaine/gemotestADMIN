<?php

namespace api\modules\gms\controllers;

use common\components\helpers\FunctionsHelper;
use common\models\GmsDevices;
use common\models\GmsPlaylistOut;
use yii\web\ForbiddenHttpException;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use api\helpers\ResponseObject;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use DateTime;
use DateTimeZone;
use yii\log\Logger;

/**
 * @property GmsDevices $modelDevice
 * @property integer $timeForTimeZone
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class PlaylistController extends ActiveController
{
    public $modelDevice;
    public $timeForTimeZone;

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
        $dev = Yii::$app->request->post()['dev'];
        $timezone = "Europe/Moscow";
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

            $this->timeForTimeZone = FunctionsHelper::getTimestampForTimeZone(time(), $timezone);
            Yii::getLogger()->log([
                'timeForTimeZone' => date ("Y-m-d H:i:s P", $this->timeForTimeZone)
            ], 1, 'binary');
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
     * @return yii\db\ActiveRecord
     */
    private function getPlaylist($day = false)
    {
        $currentDate = GmsPlaylistOut::getDateWithoutTime($this->timeForTimeZone);
        $currentTime = GmsPlaylistOut::getTimeDate($this->timeForTimeZone);

        Yii::getLogger()->log([
            '$this->timeForTimeZone' => date("Y-m-d H:i:s", $this->timeForTimeZone),
            '$currentDate'=>date("Y-m-d H:i:s", $currentDate),
            '$currentTime'=>date("Y-m-d H:i:s", $currentTime)

        ], Logger::LEVEL_ERROR, 'binary');

        $findPlaylist = GmsPlaylistOut::find()
            ->andFilterWhere(['region_id' => $this->modelDevice->region_id])
            ->andFilterWhere(['sender_id' => $this->modelDevice->sender_id])
            ->andFilterWhere(['device_id' => $this->modelDevice->id])
            ->andWhere(['<=', 'date_start', $currentDate])
            ->andWhere(['>=', 'date_end', $currentDate])
            ->andWhere(['<=', 'time_start', $currentTime])
            ->andWhere(['>=', 'time_end', $currentTime])
            ->andWhere(['=', 'active', 1]);

        if ($day) {
            $weekKeys = array_combine(array_keys(array_fill(1, 7, ''))
                , array_keys(GmsPlaylistOut::WEEK));

            $currentDay = date("N", time());
            $currentDayField = $weekKeys[$currentDay];

            $findPlaylist->andWhere(['=', $currentDayField, 1]);
        }
        return $findPlaylist->one();
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


