<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gms_devices".
 *
 * @property integer $id
 * @property string $sender_id
 * @property string $name
 * @property string $device
 * @property string $timezone
 * @property string $created_at
 * @property string $last_active_at
 * @property integer $region_id
 * @property integer $auth_status
 * @property integer $current_pls_id
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsPlaylistOut $playListOutModel
 * @property array $treeDevices
 *
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
            self::SCENARIO_EDIT_DEVICE => ['name', 'device', 'created_at', 'last_active_at', 'auth_status', 'sender_id', 'region_id', 'current_pls_id', 'timezone'],
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
            [['name', 'region_id', 'device', 'timezone'], 'required', 'on' => 'editDevice'],
            [['sender_id', 'region_id', 'auth_status', 'current_pls_id'], 'integer'],
            [['name', 'created_at', 'last_active_at', 'device', 'timezone'], 'string', 'max' => 255],
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
            'name' => 'Название',
            'device' => 'Устройство',
            'created_at' => 'Создан',
            'last_active_at' => 'Активность',
            'region_id' => 'Регион',
            'auth_status' => 'Авторизация',
            'current_pls_id' => 'Плейлист',
            'sender_name' => 'Отделение',
            'current_pls_name' => 'Плейлист',
            'timezone' => 'Временная зона'
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

    /**
     * @return array
     */
    static function getTreeDevices()
    {
        $out = [];
        $out2 = [];
        $findModel = self::find()->all();

        /** @var GmsDevices $model */
        foreach ($findModel as $model) {
            if (empty($model->name || empty($model->region_id))) continue;
            $out[$model->region_id][$model->sender_id][$model->id] = $model->name;
        }

        foreach ($out as $key => $region) {
            $findRegion = GmsRegions::findOne($key);
            if (!$findRegion) continue;
            $out = [
                'title' => $findRegion->region_name,
                'key' => (string)$key,
                'folder' => true,
                'expanded' => true
            ];
            if (is_array($region)) {
                foreach ($region as $key1 => $sender) {
                    $children_device = [];
                    if (is_array($sender)) {
                        foreach ($sender as $key2 => $device) {
                            $children_device[] = [
                                'title' => $device,
                                'key' => (string)$key2,
                                'folder' => false,
                                'expanded' => true,
                                'data' => [
                                    'parent_key' => !empty($key1) ? (string)$key.".".$key1 : (string)$key
                                ]
                            ];
                        }
                    }
                    if (!empty($key1)) {
                        $findSender = GmsSenders::findOne($key1);
                        $children_sender = [
                            'title' => $findSender->sender_name,
                            'key' => (string)$key.".".$key1,
                            'folder' => true,
                            'expanded' => true
                        ];
                        $children_sender['children'] = $children_device;
                        $out['children'][] = $children_sender;
                    } else {
                        $out['children'] = $children_device;
                    }
                }
            }
            $out2[] = $out;
        }
        return $out2;
    }

    /**
     * @inheritdoc
     */
    public static function getDeviceList()
    {
        $arr = self::find()
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }
}