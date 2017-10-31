<?php

namespace common\models;

use Yii;
use common\components\helpers\ActiveSyncHelper;

/**
 * This is the model class for table "Logins".
 *
 * @property string $aid
 * @property string $Login
 * @property string $Pass
 * @property string $Name
 * @property integer $IsOperator
 * @property string $Email
 * @property integer $IsAdmin
 * @property string $Key
 * @property string $Logo
 * @property string $LogoText
 * @property string $LogoText2
 * @property string $LogoType
 * @property string $LogoWidth
 * @property string $TextPaddingLeft
 * @property integer $OpenExcel
 * @property integer $EngVersion
 * @property string $tbl
 * @property integer $IsDoctor
 * @property integer $UserType
 * @property integer $InputOrder
 * @property integer $PriceID
 * @property integer $CanRegister
 * @property string $CACHE_Login
 * @property integer $InputOrderRM
 * @property integer $OrderEdit
 * @property integer $MedReg
 * @property integer $goscontract
 * @property integer $FizType
 * @property integer $clientmen
 * @property integer $mto
 * @property integer $mto_editor
 * @property string $LastLogin
 * @property string $DateBeg
 * @property string $DateEnd
 * @property string $block_register
 * @property string $last_update_password
 * @property integer $show_preanalytic
 * @property string $role
 * @property string $parentAid
 * @property integer $GarantLetter
 * @property Operators $operators
 * @property NAdUsers $adUsersMany
 * @property NAdUsers $adUsersOne
 * @property NAdUserAccounts $adUserAccountsMany
 * @property NAdUserAccounts $adUserAccountsOne
 * @property integer $idAD
 */
class Logins extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $last_name;
    public $first_name;
    public $middle_name;
    public $AD_position;
    public $idAD;

    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'Logins';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'Key', 'UserType'], 'required'],
            [['aid', 'IsOperator', 'IsAdmin', 'OpenExcel', 'EngVersion', 'IsDoctor', 'UserType', 'InputOrder', 'PriceID', 'CanRegister', 'InputOrderRM', 'OrderEdit', 'MedReg', 'goscontract', 'FizType', 'clientmen', 'mto', 'mto_editor', 'show_preanalytic', 'parentAid', 'GarantLetter'], 'integer'],
            [['Login', 'Pass', 'Name', 'Email', 'Key', 'Logo', 'LogoText', 'LogoText2', 'LogoType', 'LogoWidth', 'TextPaddingLeft', 'tbl', 'CACHE_Login', 'role'], 'string'],
            [['LastLogin', 'DateBeg', 'DateEnd', 'block_register', 'last_update_password'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'Login' => 'Логин GS',
            'Pass' => 'Пароль GS',
            'Name' => 'Имя GS',
            'last_name' => 'Фамилия',
            'IsOperator' => 'Is Operator',
            'Email' => 'Email',
            'IsAdmin' => 'Is Admin',
            'Key' => '№ отдел.',
            'Logo' => 'Логотип',
            'LogoText' => 'Текст логотипа №1',
            'LogoText2' => 'Текст логотипа №2',
            'LogoType' => 'Logo Type',
            'LogoWidth' => 'Logo Width',
            'TextPaddingLeft' => 'Text Padding Left',
            'OpenExcel' => 'Open Excel',
            'EngVersion' => 'Eng Version',
            'tbl' => 'Tbl',
            'IsDoctor' => 'Is Doctor',
            'UserType' => 'Тип пользователя',
            'InputOrder' => 'Input Order',
            'PriceID' => 'Price ID',
            'CanRegister' => 'Can Register',
            'CACHE_Login' => 'Cache  Login',
            'InputOrderRM' => 'Input Order Rm',
            'OrderEdit' => 'Order Edit',
            'MedReg' => 'Med Reg',
            'goscontract' => 'Goscontract',
            'FizType' => 'Fiz Type',
            'clientmen' => 'Clientmen',
            'mto' => 'Mto',
            'mto_editor' => 'Mto Editor',
            'LastLogin' => 'Дата посл. входа',
            'DateBeg' => 'Дата регистрации',
            'DateEnd' => 'Дата блокировки',
            'block_register' => 'Дата запрета рег.',
            'last_update_password' => 'Last Update Password',
            'show_preanalytic' => 'Show Preanalytic',
            'role' => 'Role',
            'parentAid' => 'Parent Aid',
            'GarantLetter' => 'Garant Letter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUsersMany()
    {
        return $this->hasMany(NAdUsers::className(), [
            'gs_id' => 'aid',
            'gs_key' => 'Key',
            'gs_usertype' => 'UserType',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccountsMany()
    {
        return $this->adUsersMany ? $this->hasMany(NAdUseraccounts::className(), [])
            ->andOnCondition('\'lab\\\' + [n_ad_Users].[AD_login] = [n_ad_Useraccounts].[ad_login]')
            ->andOnCondition('[n_ad_Useraccounts].[gs_type] = CASE
                    WHEN [Logins].[UserType] = 8 THEN \'FLO\'
                    ELSE \'SLO\' 
                    END')
            : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUsersOne()
    {
        return !empty($this->idAD) ? NAdUsers::find()
            ->where(['[n_ad_Users].[ID]' => $this->idAD])
            : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccountsOne()
    {
        return $this->adUsersOne ? NAdUseraccounts::find()
            ->where(['ad_login' => 'lab\\'.$this->adUsersOne->AD_login])
            : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperators()
    {
        return $this->hasOne(Operators::className(), ['CACHE_OperatorID' => 'Key']);
    }

    /**
     * @return array
     */
    public static function getStatusesArray() {
        return ['1' => 'Активный', '2' => 'Заблокирован'];
    }

    /**
     * @return array
     */
    public static function getTypesArray() {
        return [
            '1' => 'Администр.',        //вход через AD
            '3' => 'Юр. лица',          //вход через Logins
            '4' => 'Врач иное.',        //вход через Logins
            '5' => 'Врач консул.',      //вход через AD
            '7' => 'Собств. лаб. отд.', //вход через AD
            '8' => 'Франчайзи',         //вход через AD
            '9' => 'Ген. директор',     //вход через AD
            '13' => 'Фин. менеджер',    //вход через Logins
        ];
    }

    /**
     * @return array
     */
    public static function getColorTypes() {
        return [
            '1' => 'green',
            '3' => 'red',
            '4' => 'blue',
            '5' => 'grey',
            '7' => 'a5e3ff',
            '8' => 'orange',
            '9' => 'grey',
            '13' => 'grey',
        ];
    }
}