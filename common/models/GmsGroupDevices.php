<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gms_group_devices".
 *
 * @property int $id
 * @property string $group_name
 * @property int $device_id
 * @property string $group_json
 * @property string $group_id
 * @property string $parent_key
 * @property GmsDevices $device
 */
class GmsGroupDevices extends \yii\db\ActiveRecord
{
    public $group_json;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_group_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'device_id'], 'required'],
            [['device_id'], 'default', 'value' => null],
            [['device_id', 'group_id'], 'integer'],
            [['group_name', 'group_json', 'parent_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Номер группы',
            'group_name' => 'Название',
            'device_id' => 'Устройство',
            'parent_key' => 'Родитель'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(GmsDevices::className(), ['id' => 'device_id']);
    }

    /**
     * @inheritdoc
     */
    public static function getGroupList()
    {
        $arr = self::find()
            ->orderBy(['group_name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','group_name');
    }
}
