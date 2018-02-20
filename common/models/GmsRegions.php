<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gms_regions".
 *
 * @property integer $id
 * @property string $region_name
 */
class GmsRegions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_name'], 'required'],
            [['region_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_name' => 'Название',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getRegionList()
    {
        $arr = self::find()
            ->orderBy(['region_name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','region_name');
    }
}
