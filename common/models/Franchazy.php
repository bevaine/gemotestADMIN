<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Franchazy".
 *
 * @property integer $AID
 * @property integer $Active
 * @property string $Login
 * @property string $Pass
 * @property string $Name
 * @property integer $IsOperator
 * @property string $Email
 * @property integer $IsAdmin
 * @property string $Key
 * @property string $BlankText
 * @property string $BlankName
 * @property string $Logo
 * @property string $LogoText
 * @property string $LogoText2
 * @property integer $LogoType
 * @property integer $LogoWidth
 * @property integer $TextPaddingLeft
 * @property integer $OpenExcel
 * @property integer $EngVersion
 * @property integer $InputOrder
 * @property integer $PriceID
 * @property integer $CanRegister
 * @property integer $InputOrderRM
 * @property string $OpenActive
 * @property string $ReestrUslug
 * @property string $LCN
 * @property string $Li_cOrg
 */
class Franchazy extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Franchazy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Active', 'IsOperator', 'IsAdmin', 'LogoType', 'LogoWidth', 'TextPaddingLeft', 'OpenExcel', 'EngVersion', 'InputOrder', 'PriceID', 'CanRegister', 'InputOrderRM'], 'integer'],
            [['Login', 'Pass', 'Name', 'Email', 'Key', 'BlankText', 'BlankName', 'Logo', 'LogoText', 'LogoText2', 'ReestrUslug', 'LCN', 'Li_cOrg'], 'string'],
            [['OpenActive'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'Active' => 'Active',
            'Login' => 'Login',
            'Pass' => 'Pass',
            'Name' => 'Name',
            'IsOperator' => 'Is Operator',
            'Email' => 'Email',
            'IsAdmin' => 'Is Admin',
            'Key' => 'Key',
            'BlankText' => 'Blank Text',
            'BlankName' => 'Blank Name',
            'Logo' => 'Logo',
            'LogoText' => 'Logo Text',
            'LogoText2' => 'Logo Text2',
            'LogoType' => 'Logo Type',
            'LogoWidth' => 'Logo Width',
            'TextPaddingLeft' => 'Text Padding Left',
            'OpenExcel' => 'Open Excel',
            'EngVersion' => 'Eng Version',
            'InputOrder' => 'Input Order',
            'PriceID' => 'Price ID',
            'CanRegister' => 'Can Register',
            'InputOrderRM' => 'Input Order Rm',
            'OpenActive' => 'Open Active',
            'ReestrUslug' => 'Reestr Uslug',
            'LCN' => 'Lcn',
            'Li_cOrg' => 'Li C Org',
        ];
    }
}
