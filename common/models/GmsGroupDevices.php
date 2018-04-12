<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_group_devices".
 *
 * @property int $id
 * @property string $group_name
 * @property int $device_id
 */
class GmsGroupDevices extends \yii\db\ActiveRecord
{
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
            [['device_id'], 'integer'],
            [['group_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => 'Название',
            'device_id' => 'Устройство',
        ];
    }
}
