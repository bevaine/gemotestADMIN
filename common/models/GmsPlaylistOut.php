<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gms_playlist_out".
 *
 * @property integer $id
 * @property string $name
 * @property string $jsonPlaylist
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
}
