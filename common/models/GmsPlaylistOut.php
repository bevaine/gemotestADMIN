<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\log\Logger;

/**
 * This is the model class for table "gms_playlist_out".
 *
 * @property integer $id
 * @property string $name
 * @property string $jsonPlaylist
 * @property integer $region_id
 * @property integer $device_id
 * @property integer $sender_id
 * @property integer $date_play
 * @property integer $timeStart
 * @property integer $timeEnd
 * @property integer $dateStart
 * @property integer $dateEnd
 * @property integer $active
 * @property integer $created_at
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsDevices $deviceModel
 *
 */

class GmsPlaylistOut extends \yii\db\ActiveRecord
{
    CONST SCENARIO_DEFAULT_PLAYLIST = 'default';
    CONST SCENARIO_FIND_PLAYLIST = 'findPlaylistOut';

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT_PLAYLIST => ['region_id', 'sender_id', 'device_id', 'isMonday', 'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday','name', 'jsonPlaylist', 'dateStart', 'dateEnd','timeStart', 'timeEnd'],
            self::SCENARIO_FIND_PLAYLIST => ['id', 'region_id', 'sender_id', 'device_id', 'isMonday', 'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday','name', 'jsonPlaylist', 'dateStart', 'dateEnd','timeStart', 'timeEnd'],
        ];
    }

    CONST WEEK = [
        "isMonday" => "Понедельник",
        "isTuesday" => "Вторник",
        "isWednesday" => "Среда",
        "isThursday" => "Четверг",
        "isFriday" => "Пятница",
        "isSaturday" => "Суббота",
        "isSunday" => "Воскресенье"
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
            [['region_id', 'jsonPlaylist', 'dateStart', 'dateEnd', 'timeStart', 'timeEnd'], 'required'],
            [['region_id', 'sender_id', 'device_id', 'isMonday', 'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday'], 'integer'],
            [['name', 'jsonPlaylist'], 'string'],
            [['dateStart', 'dateEnd','timeStart', 'timeEnd'], 'filter', 'filter' => function ($value) {
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
            $this->active = 1;
        }

        $this->dateStart = self::getDateWithoutTime($this->dateStart);
        $this->dateEnd = self::getDateWithoutTime($this->dateEnd);

        $this->timeStart = self::getTimeDate($this->timeStart);
        $this->timeEnd = self::getTimeDate($this->timeEnd);

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
            'dateStart' => 'Дата старта',
            'dateEnd' => 'Дата окончания',
            'timeStart' => 'Время старта',
            'timeEnd' => 'Время окончания',
            'jsonPlaylist' => 'Плейлист',
            'active' => 'Статус',
            'created_at' => 'Дата создания'
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
        if (empty($this->region_id)) return false;
        if (empty($this->sender_id)) $this->sender_id = null;
        if (empty($this->device_id)) $this->device_id = null;

        $findModel = self::find()
            ->where([
                'region_id' => $this->region_id,
                'sender_id' => $this->sender_id,
                'device_id' => $this->device_id])
            ->andFilterWhere(['!=', 'id', $this->id])
            ->all();
        //print_r($findModel);

        Yii::getLogger()->log(['$findModel' => ArrayHelper::toArray($findModel)], Logger::LEVEL_ERROR, 'binary');

        if ($findModel) {

            $arrDaysModel = [];

            foreach (self::WEEK as $day => $name) {
                if (!empty($this->$day)) $arrDaysModel[$day] = $name;
            }

            Yii::getLogger()->log(["this" => $this], Logger::LEVEL_ERROR, 'binary');


            foreach ($findModel as $model) {
                Yii::getLogger()->log(['$model' => $model], Logger::LEVEL_ERROR, 'binary');
                /** @var $model GmsPlaylistOut */
                $dateCross = ($this->dateStart <= $model->dateEnd  && $this->dateEnd >= $model->dateStart);

                //todo проверяем пересекается ли даты

                if ($dateCross) {

                    //todo проверяем пересекается ли дни недели
                    $out = [
                        "id" => $model->id,
                        "name" => $model->name,
                        "date" => [
                            "start" => date("d-m-Y", $model->dateStart),
                            "end" => date("d-m-Y", $model->dateEnd),
                        ],
                    ];

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
                        $timeCross = ($this->timeStart <= $model->timeEnd && $this->timeEnd >= $model->timeStart);
                        if ($timeCross) {
                            $out['time'] = [
                                "start" => date("H:i", $model->timeStart),
                                "end" => date("H:i", $model->timeEnd)
                            ];
                        }
                    }

                    if (empty($out["time"])) return false;
                    else return $out;
                }
            }
        }
        return false;
    }

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

    public static function getTimeDate($datetime) {
        return mktime(
            date("H", $datetime),
            date("i", $datetime),
            date("s", $datetime),
            date("m", 0),
            date("d", 0),
            date("Y", 0)
        );
    }
}
