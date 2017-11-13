<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "InputOrderZabor".
 *
 * @property integer $aid
 * @property string $OrderID
 * @property string $IsslCode
 * @property string $MSZabor
 * @property string $DateIns
 */
class InputOrderZabor extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @return array
     */
    public static function PrimaryKey()
    {
        return ['aid'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'InputOrderIsklIsslMSZabor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OrderID', 'IsslCode', 'MSZabor'], 'string'],
            [['DateIns'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'OrderID' => 'Order ID',
            'IsslCode' => 'Issl Code',
            'MSZabor' => 'Mszabor',
            'DateIns' => 'Date Ins',
        ];
    }
}
