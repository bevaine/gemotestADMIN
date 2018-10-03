<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sprFilials".
 *
 * @property integer $AID
 * @property integer $Fid
 * @property string $Fkey
 * @property string $Fname
 * @property integer $Type
 */
class SprFilials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sprFilials';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Fid', 'Type'], 'integer'],
            [['Fkey', 'Fname'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'Fid' => 'Fid',
            'Fkey' => 'Код',
            'Fname' => 'Наименование',
            'Type' => 'Тип',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getFilialsList()
    {
            $arr = self::find()
                ->select(['id' => 'Fkey' , 'name' => 'Fname'])
                ->orderBy(['Fname' => 'asc'])
                ->asArray()
                ->all();
            return ArrayHelper::map($arr,'id','name');
    }
}
