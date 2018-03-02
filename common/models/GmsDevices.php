<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_devices".
 *
 * @property integer $id
 * @property string $sender_id
 * @property string $host_name
 * @property string $device
 * @property integer $created_at
 * @property integer $last_active_at
 * @property integer $region_id
 * @property integer $auth_status
 * @property integer $current_pls_id
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsPlaylistOut $playListOutModel
 */

class GmsDevices extends \yii\db\ActiveRecord
{
    CONST SCENARIO_ADD_DEVICE = 'addDevice';
    CONST SCENARIO_EDIT_DEVICE = 'editDevice';

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_DEVICE => ['device', 'created_at', 'last_active_at', 'auth_status'],
            self::SCENARIO_EDIT_DEVICE => ['device', 'created_at', 'last_active_at', 'auth_status', 'sender_id', 'region_id', 'current_pls_id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device'], 'required', 'on' => 'addDevice'],
            [['region_id', 'device'], 'required', 'on' => 'editDevice'],
            [['created_at', 'sender_id', 'last_active_at', 'region_id', 'auth_status', 'current_pls_id'], 'integer'],
            [['host_name', 'device'], 'string', 'max' => 255],
            ['auth_status', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Отделение',
            'host_name' => 'Хост',
            'device' => 'Устройство',
            'created_at' => 'Создан',
            'last_active_at' => 'Активность',
            'region_id' => 'Регион',
            'auth_status' => 'Авторизация',
            'current_pls_id' => 'Плейлист',
            'sender_name' => 'Отделение',
            'current_pls_name' => 'Плейлист',
        ];
    }

    /**
     * @param null $auth
     * @return array|mixed
     */
    public static function getAuthStatus($auth = null) {
        if ($auth == null || $auth != 1) $auth = 0;
        return self::getAuthStatusArray()[$auth];
    }

    /**
     * @return array
     */
    static public function getAuthStatusArray() {
        return ['0' => 'Не авторизирован', '1' => 'Авторизирован'];
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
    public function getPlayListOutModel()
    {
        return $this->hasOne(GmsPlaylistOut::className(), ['id' => 'current_pls_id']);
    }
}
