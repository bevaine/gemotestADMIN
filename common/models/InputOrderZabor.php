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
 * @property BranchStaff $branchStaff
 */

class InputOrderZabor extends \yii\db\ActiveRecord
{
    public $last_name;
    public $first_name;
    public $middle_name;

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
            'aid' => 'AID',
            'OrderID' => '№ заказа',
            'IsslCode' => 'Код исследования',
            'MSZabor' => 'GUID Сотрудника',
            'DateIns' => 'Дата добавления',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchStaff()
    {
        return $this->hasOne(BranchStaff::className(), ['CAST([guid] AS varchar(100))' => 'MSZabor']);
    }
}