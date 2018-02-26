<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_senders".
 *
 * @property integer $id
 * @property string $sender_key
 * @property string $sender_name
 * @property string $region_id
 * @property Kontragents $kontragents
 * @property GmsRegions $regions
 */
class GmsSenders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_senders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_key', 'sender_name', 'region_id'], 'required'],
            [['sender_key', 'sender_name', 'region_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_key' => 'Код отделения',
            'sender_name' => 'Отделение',
            'region_id' => 'Регион',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKontragents()
    {
        return $this->hasOne(Kontragents::className(), ['Key' => 'sender_key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegions()
    {
        return $this->hasOne(GmsRegions::className(), ['id' => 'region_id']);
    }
}
