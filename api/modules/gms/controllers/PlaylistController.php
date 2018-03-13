<?php

namespace api\modules\gms\controllers;

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

/**
 * @property GmsDevices $modelDevice
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class PlaylistController extends ActiveController
{
    public $modelDevice;

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
     * @param null $dev
     * @param null $pls
     * @return ResponseObject
     * @throws ForbiddenHttpException
     */
    public function actionView($dev = null, $pls = null)
    {
        $out['state'] = 0;
        $response = new ResponseObject();

        if (!$modelDevices = GmsDevices::findOne(['device' => $dev])) {
            $modelDevices = new GmsDevices();
            $modelDevices->scenario = 'addDevice';
            $modelDevices->device = $dev;
            $modelDevices->auth_status = 0;
            $modelDevices->created_at = time();
        } else {
            $modelDevices->scenario = 'editDevice';
        }

        $modelDevices->last_active_at = time();

        //todo проверка на авторизацию устройства
        if (!empty($modelDevices->auth_status)) {

            if ($modelDevices->playListOutModel) {

                //todo если плейлист назначен в ручную
                if ($modelDevices->current_pls_id != $pls) {
                    $out['state'] = 1;
                    $out['pls'] = [
                        'id' => $modelDevices->playListOutModel->id,
                        'files' => $modelDevices->playListOutModel->getVideos(),
                        'm3u' => Json::decode($modelDevices->playListOutModel->jsonPlaylist)
                    ];
                }
            } else {

                //todo поиск подходящего по параметрам плейлиста
                $this->modelDevice = $modelDevices;
                $plsID = $this->getCurrentPlaylist();

                /** @var $plsID GmsPlaylistOut */
                //todo проверка на соотвествие плейлиста на устройстве и подобранного автоматически
                if ($plsID && $plsID->id != $pls) {
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

        $response->addData($out);
        return $response;
    }

    /**
     * @param bool $day
     * @return yii\db\ActiveRecord
     */
    private function getPlaylist($day = false)
    {
        $currentDate = GmsPlaylistOut::getDateWithoutTime(time());
        $currentTime = GmsPlaylistOut::getTimeDate(time());

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


