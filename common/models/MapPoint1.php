<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_Point_1".
 *
 * @property integer $id
 * @property string $address
 * @property string $coordinates
 * @property string $sender_id
 * @property string $code_1c
 * @property string $zip_code
 * @property string $area
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $housing
 * @property string $region
 * @property string $phone
 * @property integer $active
 */
class MapPoint1 extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_Point_1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'coordinates', 'sender_id', 'code_1c', 'zip_code', 'area', 'city', 'street', 'house', 'housing', 'region', 'phone'], 'string'],
            [['active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'coordinates' => 'Coordinates',
            'sender_id' => 'Sender ID',
            'code_1c' => 'Code 1c',
            'zip_code' => 'Zip Code',
            'area' => 'Area',
            'city' => 'City',
            'street' => 'Street',
            'house' => 'House',
            'housing' => 'Housing',
            'region' => 'Region',
            'phone' => 'Phone',
            'active' => 'Active',
        ];
    }
}
