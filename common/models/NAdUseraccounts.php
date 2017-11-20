<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_ad_Useraccounts".
 *
 * @property integer $ID
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $gs_type
 * @property string $gs_id
 * @property string $org_name
 * @property string $gs_position
 * @property string $ad_login
 * @property string $ad_pass
 * @property Logins $logins
 */
class NAdUseraccounts extends \yii\db\ActiveRecord
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
        return ['ID'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_ad_Useraccounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_name', 'first_name', 'middle_name', 'gs_type', 'gs_id', 'org_name', 'gs_position', 'ad_login', 'ad_pass'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'gs_type' => 'Gs Type',
            'gs_id' => 'Gs ID',
            'org_name' => 'Org Name',
            'gs_position' => 'Gs Position',
            'ad_login' => 'Логин AD',
            'ad_pass' => 'Пароль AD',
        ];
    }

    /**
     * @param $account
     * @return static
     */
    static function findAdUserAccount($account)
    {
        return self::findOne(['ad_login' => $account]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasOne(Logins::className(), ['Key' => 'gs_id']);
    }
}
