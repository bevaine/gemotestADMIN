<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "n_ad_Users".
 *
 * @property integer $ID
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $AD_name
 * @property string $AD_position
 * @property string $AD_email
 * @property string $table_number
 * @property string $subdivision
 * @property string $create_date
 * @property string $last_update
 * @property integer $gs_id
 * @property integer $gs_key
 * @property integer $gs_usertype
 * @property string $gs_email
 * @property integer $allow_gs
 * @property integer $active
 * @property string $AD_login
 * @property integer $AD_active
 * @property integer $auth_ldap_only
 * @property Logins $logins
 * @property NAdUserAccounts $adUserAccounts
 * @property t23 $publicEmployee
 */
class NAdUsers extends \yii\db\ActiveRecord
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
        return ['gs_id'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_ad_Users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_name', 'first_name', 'middle_name', 'AD_name', 'AD_position', 'AD_email', 'table_number', 'subdivision', 'gs_email', 'AD_login'], 'string'],
            [['create_date', 'last_update'], 'safe'],
            [['gs_id', 'gs_key', 'gs_usertype', 'allow_gs', 'active', 'AD_active', 'auth_ldap_only'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'last_name' => 'Фамилия AD',
            'first_name' => 'Имя AD',
            'middle_name' => 'Отчество AD',
            'AD_name' => 'Ad Name',
            'AD_position' => 'Должность AD',
            'AD_email' => 'Email',
            'table_number' => 'Table Number',
            'subdivision' => 'Subdivision',
            'create_date' => 'Create Date',
            'last_update' => 'Last Update',
            'gs_id' => 'Gs ID',
            'gs_key' => 'Gs Key',
            'gs_usertype' => 'Gs Usertype',
            'gs_email' => 'Gs Email',
            'allow_gs' => 'Allow Gs',
            'active' => 'Active',
            'AD_login' => 'Ad Login',
            'AD_active' => 'Ad Active',
            'auth_ldap_only' => 'Auth Ldap Only',
        ];
    }

    /**
     * @param bool $id
     * @return array|mixed
     */
    public static function getList($id = false)
    {
        $modules = [];
        foreach (self::find() as $model) {
            $modules[$model->ID] = $model->last_name;
        }
        return $id !== false && isset($modules[$id]) ? ArrayHelper::getValue($modules, $id) : $modules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasOne(Logins::className(), ['Key' => 'gs_key'])->where(['UserType' => '7']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccounts()
    {
        return $this->hasOne(NAdUseraccounts::className(), [
            'gs_id' => 'gs_key',
            'last_name' => 'last_name',
            'first_name' => 'first_name',
            'middle_name' => 'middle_name',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdUserAccountsOne()
    {
        return
            $this->hasOne(NAdUseraccounts::className(), ['gs_id' => 'gs_key'])
            ->OnCondition('\'lab\\\' + [n_ad_Users].[AD_login] = [n_ad_Useraccounts].[ad_login]');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublicEmployee()
    {
        return $this->hasOne(t23::className(), [
            //'gs_id' => 'gs_key',
            'q5' => 'last_name',
            'q3' => 'first_name',
            'q4' => 'middle_name',
        ]);
    }

}
