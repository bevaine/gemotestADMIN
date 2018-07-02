<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Operators".
 *
 * @property integer $AID
 * @property string $CACHE_Login
 * @property string $Name
 * @property string $LastName
 * @property string $BirthDate
 * @property string $Orders
 * @property integer $MG
 * @property string $PassportNum
 * @property string $PassportVidan
 * @property string $Palata
 * @property string $IndCity
 * @property string $StreetHouse
 * @property string $PhoneNumber
 * @property string $StrahNumber
 * @property string $PasportNum
 * @property string $PasportDate
 * @property string $DateIns
 * @property string $DateLastUpdate
 * @property string $CACHE_OperatorID
 * @property string $OperatorOffice
 * @property string $OperatorOfficeStatus
 * @property string $Pass
 * @property integer $Active
 * @property integer $CanRegister
 * @property integer $InputOrderRM
 * @property integer $OrderEdit
 * @property integer $MedReg
 * @property integer $PriceID
 * @property integer $ClientMen
 * @property integer $mto
 * @property integer $mto_editor
 * @property string $fio
 */
class Operators extends \yii\db\ActiveRecord
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
        return 'Operators';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CACHE_Login', 'Name', 'LastName', 'Orders', 'PassportNum', 'PassportVidan', 'Palata', 'IndCity', 'StreetHouse', 'PhoneNumber', 'StrahNumber', 'PasportNum', 'PasportDate', 'CACHE_OperatorID', 'OperatorOffice', 'OperatorOfficeStatus', 'Pass'], 'string'],
            [['BirthDate', 'DateIns', 'DateLastUpdate'], 'safe'],
            [['MG', 'Active', 'CanRegister', 'InputOrderRM', 'OrderEdit', 'MedReg', 'PriceID', 'ClientMen', 'mto', 'mto_editor'], 'integer'],
        ];
    }

    /**
     * @return string
     */
    public function getFio()
    {
        $m = explode(' ', $this->Name);
        return !empty($m[0]) && !empty($m[0])
            ? $this->LastName . ' ' . substr($m[0],0,2) . '.' . substr($m[1],0,2) . '.'
            : null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'CACHE_Login' => 'Cache  Login',
            'Name' => 'Name',
            'LastName' => 'Last Name',
            'BirthDate' => 'Birth Date',
            'Orders' => 'Orders',
            'MG' => 'Mg',
            'PassportNum' => 'Passport Num',
            'PassportVidan' => 'Passport Vidan',
            'Palata' => 'Palata',
            'IndCity' => 'Ind City',
            'StreetHouse' => 'Street House',
            'PhoneNumber' => 'Phone Number',
            'StrahNumber' => 'Strah Number',
            'PasportNum' => 'Pasport Num',
            'PasportDate' => 'Pasport Date',
            'DateIns' => 'Date Ins',
            'DateLastUpdate' => 'Date Last Update',
            'CACHE_OperatorID' => 'Cache  Operator ID',
            'OperatorOffice' => 'Operator Office',
            'OperatorOfficeStatus' => 'Operator Office Status',
            'Pass' => 'Pass',
            'Active' => 'Active',
            'CanRegister' => 'Can Register',
            'InputOrderRM' => 'Input Order Rm',
            'OrderEdit' => 'Order Edit',
            'MedReg' => 'Med Reg',
            'PriceID' => 'Price ID',
            'ClientMen' => 'Client Men',
            'mto' => 'Mto',
            'mto_editor' => 'Mto Editor',
        ];
    }
}
