<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "gms_playlist_out".
 *
 * @property integer $id
 * @property string $name
 * @property string $jsonPlaylist
 * @property string $jsonKodi
 * @property integer $region_id
 * @property integer $device_id
 * @property integer $sender_id
 * @property integer $date_play
 * @property integer $time_start
 * @property integer $time_end
 * @property integer $date_start
 * @property integer $date_end
 * @property integer $active
 * @property integer $created_at
 * @property integer $update_at
 * @property integer $is_monday
 * @property integer $is_tuesday
 * @property integer $is_wednesday
 * @property integer $is_thursday
 * @property integer $is_friday
 * @property integer $is_saturday
 * @property integer $is_sunday
 * @property integer $group_id
 * @property integer $pls_id
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsDevices $deviceModel
 * @property GmsGroupDevices $groupDevicesModel
 * @property integer $videoType
 * @property array $videos
 *
 */

class GmsPlaylistOut extends \yii\db\ActiveRecord
{
    public $pls_id;

    CONST WEEK = [
        "is_monday" => "Понедельник",
        "is_tuesday" => "Вторник",
        "is_wednesday" => "Среда",
        "is_thursday" => "Четверг",
        "is_friday" => "Пятница",
        "is_saturday" => "Суббота",
        "is_sunday" => "Воскресенье"
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_playlist_out';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jsonPlaylist', 'jsonKodi', 'date_start', 'date_end', 'time_start', 'time_end'], 'required'],
            [['created_at', 'update_at', 'active', 'region_id', 'sender_id', 'group_id', 'device_id', 'is_monday', 'is_tuesday', 'is_wednesday', 'is_thursday', 'is_friday', 'is_saturday', 'is_sunday', 'pls_id'], 'integer'],
            [['name', 'jsonPlaylist', 'jsonKodi'], 'string'],
            [['date_start', 'date_end', 'time_start', 'time_end'], 'filter', 'filter' => function ($value) {
                if (!preg_match("/^[\d\+]+$/", $value) && !empty($value)) return strtotime($value);
                else return $value;
            }],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
            $this->update_at = time();
            $this->active = 1;
        } else {
            $this->update_at = time();
        }

        $this->date_start = self::getDateWithoutTime($this->date_start);
        $this->date_end = self::getDateWithoutTime($this->date_end);

        $this->time_start = self::getTimeDate($this->time_start);
        $this->time_end = self::getTimeDate($this->time_end);

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'region_id' => 'Регион',
            'sender_id' => 'Отделение',
            'device_id' => 'Устройство',
            'date_start' => 'Дата старта',
            'date_end' => 'Дата окончания',
            'time_start' => 'Время старт',
            'time_end' => 'Время стоп',
            'jsonPlaylist' => 'Плейлист',
            'jsonKodi' => 'Команды Kodi',
            'active' => 'Статус',
            'created_at' => 'Дата создания',
            'update_at' => 'Дата обновления',
            'device_name' => 'Устройство',
            'sender_name' => 'Отделение',
            'date_start_val' => 'Дата старт',
            'date_end_val' => 'Дата стоп',
            'group_id' => 'Номер группы'
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPlayListArray() {

        $arr = self::find()
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionModel()
    {
        return $this->hasOne(GmsRegions::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderModel()
    {
        return $this->hasOne(GmsSenders::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceModel()
    {
        return $this->hasOne(GmsDevices::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupDevicesModel()
    {
        return $this->hasOne(GmsGroupDevices::className(), ['id' => 'group_id']);
    }

    /**
     * @param null $auth
     * @return array|mixed
     */
    static public function getAuthStatusValue($auth = null) {
        if ($auth != 1) $auth = 0;
        return self::getAuthStatusArray()[$auth];
    }

    /**
     * @return array
     */
    static public function getAuthStatusArray() {
        return ['0' => 'Заблокирован', '1' => 'Активный'];
    }

    /**
     * @return string
     */
    public function getAuthStatus() {
        /** @var \common\models\LoginsSearch $model */
        $this->active == 1 ? $class = 'success' : $class = 'danger';
        $html = Html::tag(
            'span',
            Html::encode(self::getAuthStatusValue($this->active)),
            ['class' => 'label label-' . $class]
        );
        return $html;
    }

    /**
     * @return string
     */
    public function getDaysPlaylist() {
        $arr = [];
        foreach (self::WEEK as $key=>$val) {
            if (!empty($this->$key)) {
                $html = Html::tag(
                    'span',
                    Html::encode($val),
                    ['class' => 'label label-primary']
                );
                $arr[] = $html;
            }
        }
        if (count($arr) > 0) {
            return implode('<br>', $arr);
        } else return null;
    }

    /**
     * @return array|bool
     */
    public function checkPlaylist()
    {
        $cross = '';
        if (empty($this->region_id)) $this->region_id = null;
        if (empty($this->sender_id)) $this->sender_id = null;
        if (empty($this->group_id)) $this->group_id = null;
        if (empty($this->device_id)) $this->device_id = null;

        $findModel = GmsPlaylistOut::find();
        if (!empty($this->region_id)) {
            $cross = 'region';
            $findModel->andWhere([
                'region_id' => $this->region_id,
                'sender_id' => $this->sender_id
            ]);
        } elseif (!empty($this->group_id)) {
            $cross = 'group';
            $findModel->andWhere([
                'group_id' => $this->group_id
            ]);
        } elseif (!empty($this->device_id)) {
            $cross = 'device';
            $findModel->andWhere([
                'device_id' => $this->device_id
            ]);
        }

        $findModel->andFilterWhere(['!=', 'id', $this->pls_id]);
        $findModel1 = $findModel->all();

        if ($findModel1) {

            $arrDaysModel = [];

            foreach (self::WEEK as $day => $name) {
                if (!empty($this->$day)) $arrDaysModel[$day] = $name;
            }

            foreach ($findModel1 as $model) {

                /** @var $model GmsPlaylistOut */
                $dateCross = ($this->date_start <= $model->date_end  && $this->date_end >= $model->date_start);

                //todo проверяем пересекается ли даты
                if ($dateCross) {

                    //todo проверяем пересекается ли дни недели
                    $out = [
                        "id" => $model->id,
                        "name" => $model->name,
                        "cross" => $cross,
                        "date" => [
                            "start" => date("d-m-Y", $model->date_start),
                            "end" => date("d-m-Y", $model->date_end),
                        ],
                    ];
                    if ($model->regionModel) {
                        $out["region"] = $model->regionModel->region_name;
                    }
                    if ($model->senderModel) {
                        $out["sender"] = $model->senderModel->sender_name;
                    }
                    if ($model->deviceModel) {
                        $out["device"] = $model->deviceModel->name;
                    }
                    if (!empty($arrDaysModel)) {

                        $weekCross = array_intersect_key(ArrayHelper::toArray($model), $arrDaysModel);

                        if (!is_array($weekCross)) return $out;
                        $weekCross = array_filter($weekCross);

                        if (!empty($weekCross)) {
                            $sumDays = array_sum($weekCross);
                            $returnWeek = array_intersect_key(self::WEEK, $weekCross);
                            $implodeWeek = implode(', ', $returnWeek);
                            if (!empty($sumDays)) $out["week"] = $implodeWeek;
                        }
                    }

                    if (!empty($arrDaysModel) && !empty($out["week"]) || empty($arrDaysModel)) {
                        //todo проверяем пересекается ли время
                        $timeCross = ($this->time_start <= $model->time_end && $this->time_end >= $model->time_start);
                        if ($timeCross) {
                            $out['time'] = [
                                "start" => date("H:i", $model->time_start),
                                "end" => date("H:i", $model->time_end)
                            ];
                        }
                    }

                    if (empty($out["time"])) continue;
                    else return $out;
                }
            }
        }
        return false;
    }

    /**
     * @param $datetime
     * @return false|int
     */
    public static function getDateWithoutTime($datetime) {
        return mktime(
            0,
            0,
            0,
            date("m", $datetime),
            date("d", $datetime),
            date("Y", $datetime)
        );
    }

    /**
     * @param $datetime
     * @return false|int
     */
    public static function getTimeDate($datetime) {
        return mktime(
            date("H", $datetime),
            date("i", $datetime),
            date("s", 0),
            date("m", 0),
            date("d", 0),
            date("Y", 0)
        );
    }

    /**
     * @return array|bool
     */
    public function getVideos()
    {
        if (!empty($this->jsonPlaylist)) {
            $jsonPlaylist = json_decode($this->jsonPlaylist);
            if (empty($jsonPlaylist->children)) return false;
            $arrKeys = ArrayHelper::getColumn($jsonPlaylist->children, 'key');
            if ($findVideos = GmsVideos::find()->where(['in', 'id' , $arrKeys])->all()) {
                $findVideos = ArrayHelper::getColumn($findVideos, 'file');
                return $findVideos;
            }
        }
        return false;
    }

    /**
     * @param $video_key
     * @return bool
     */
    public function getVideoData($video_key)
    {
        /** @var GmsPlaylistOut $findModel */
        if (!empty($this->jsonPlaylist)) {
            $jsonPlaylist = json_decode($this->jsonPlaylist);
            if (empty($jsonPlaylist->children)) return false;
            $arrTypes = ArrayHelper::getColumn($jsonPlaylist->children, 'data');
            $arrKeys = ArrayHelper::getColumn($jsonPlaylist->children, 'key');
            $arr_comb = array_combine($arrKeys , $arrTypes);
            if (array_key_exists($video_key, $arr_comb)) {
                return $arr_comb[$video_key];
            }
        }
        return false;
    }
}