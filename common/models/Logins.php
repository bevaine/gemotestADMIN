<?php

namespace common\models;

use Yii;
use common\components\helpers\ActiveSyncHelper;
use yii\log\Logger;

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
 * @property NAdUsers $adUsers
 * @property NAdUserAccounts $adUserAccountsMany
 * @property NAdUserAccounts $adUserAccounts
 * @property DirectorFlo $directorFlo
 * @property DirectorFlo $directorInfo
 * @property DirectorFloSender $directorInfoSender
 * @property integer $idAD
 * @property integer $aid_donor
 * @property Franchazy $franchazy
 * @property LpASs $lpASs
 * @property string EmailPassword
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
    public $aid_donor;
    public $EmailPassword;

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
            [['aid_donor', 'aid', 'IsOperator', 'IsAdmin', 'OpenExcel', 'EngVersion', 'IsDoctor', 'UserType', 'InputOrder', 'PriceID', 'CanRegister', 'InputOrderRM', 'OrderEdit', 'MedReg', 'goscontract', 'FizType', 'clientmen', 'mto', 'mto_editor', 'show_preanalytic', 'parentAid', 'GarantLetter'], 'integer'],
            [['Login', 'Pass', 'Name', 'Email', 'EmailPassword', 'Key', 'Logo', 'LogoText', 'LogoText2', 'LogoType', 'LogoWidth', 'TextPaddingLeft', 'tbl', 'CACHE_Login', 'role'], 'string'],
            [['LastLogin', 'DateBeg', 'DateEnd', 'last_update_password'], 'safe'],
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
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!empty($this->aid_donor) && !empty($this->aid)) {
            if ($itemname = ActiveSyncHelper::addFromDonor($this->aid_donor, $this->aid)) {
                $nameDonor = self::findOne(['aid' => $this->aid_donor]);
                $txtName = implode('<br>', $itemname);
                $message = '<p>Были успешно применены роли как у пользователя <b>' . $nameDonor->Name . '</b>:</p>';
                $message .= '<p>' . $txtName . '</p>';
                Yii::$app->session->setFlash('warning', $message);
            } else {
                $message = '<p>Не удалось применить роли!</p>';
                Yii::$app->session->setFlash('error', $message);
                return false;
            }
        }
        if ($modelLpASs = $this->lpASs) {
            if($this->Login !== $this->oldAttributes['Login']) {
                $modelLpASs->login = $this->Login;
            }
            if($this->Pass !== $this->oldAttributes['Pass']) {
                $modelLpASs->pass = $this->Pass;
            }
            if (!$modelLpASs->save()) {
                Yii::getLogger()->log([
                    'modelLpASs->save()'=>$modelLpASs->errors
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorFlo()
    {
        return $this->hasOne(DirectorFlo::className(), [])
            ->andOnCondition('[Logins].[login] = [DirectorFlo].[login]');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorInfo()
    {
        return $this->hasOne(DirectorFlo::className(), ['login' => 'Login']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorInfoSender()
    {
        return $this->hasMany(DirectorFloSender::className(), [
            'director_id' => 'id'
        ])->via('directorInfo');
    }

        /**
         * @return array
         */
    public function getSendersList()
    {
        $modules = [];
        if ($this->directorInfoSender) {
            /** @var DirectorFloSender $model */
            foreach ($this->directorInfoSender as $model) {
                $modules[$model->sender_key] = $model->sender_key;
            }
        }
        return $modules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorFloSender()
    {
        return $this->hasOne(DirectorFloSender::className(), [])
            ->andOnCondition('[DirectorFloSender].[director_id] = [DirectorFlo].[id]');
    }

    /**
     * @param $userType
     * @param $userKey
     * @return static
     */
    public static function getUserByKey($userType, $userKey)
    {
        return self::findOne([
            'UserType' => $userType,
            'Key' => $userKey
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUsersMany()
    {
        return $this->hasMany(NAdUsers::className(), [
            'gs_id' => 'aid',
            'gs_usertype' => 'UserType',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUsers()
    {
        if (!empty($this->idAD)) {
            return NAdUsers::find()->where(['[n_ad_Users].[ID]' => $this->idAD]);
        }

        return $this->hasOne(NAdUsers::className(), [
            'gs_id' => 'aid',
            'gs_usertype' => 'UserType'
        ]);
    }

    /**
     * @param $AdLogin
     * @return static
     */
    public function getCheckAdLogin($AdLogin)
    {
        return self::findOne(['AD_login' => $AdLogin]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccountsMany()
    {
        return $this->adUsersMany ? $this->hasMany(NAdUseraccounts::className(), [])
            ->andOnCondition('\'lab\\\' + [n_ad_Users].[AD_login] = [n_ad_Useraccounts].[ad_login]') : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccounts()
    {
        return $this->adUsers ? NAdUseraccounts::find()
            ->where(['ad_login' => 'lab\\'.$this->adUsers->AD_login])
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
     * @return \yii\db\ActiveQuery
     */
    public function getFranchazy()
    {
        return $this->hasOne(Franchazy::className(), ['Key' => 'Key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLpASs()
    {
        return $this->hasOne(LpASs::className(), [
            'ukey' => 'Key',
            'utype' => 'UserType'
        ]);
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