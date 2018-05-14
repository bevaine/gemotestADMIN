<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Patients".
 *
 * @property int $AID
 * @property string $Name
 * @property string $LastName
 * @property string $BirthDate
 * @property string $BeremenDate
 * @property string $Orders
 * @property int $MG
 * @property string $PassportNum
 * @property string $PassportVidan
 * @property string $Palata
 * @property string $IndCity
 * @property string $StreetHouse
 * @property string $PhoneNumber
 * @property string $StrahNumber
 * @property int $KontragentAID
 * @property string $KontragentName
 * @property string $KontragentKey
 * @property string $PasportNum
 * @property string $PasportDate
 * @property string $DateIns
 * @property string $DateLastUpdate
 * @property string $CACHE_PatID
 * @property string $CACHE_PatDetailID
 * @property string $LLCcode
 * @property string $metro
 * @property int $FromCache
 * @property string $worlplace
 * @property string $workotdel
 * @property string $MobileNumber
 * @property int $MobileNotify
 * @property string $email
 * @property string $dt_begin_pregnancy
 * @property string $dt_end_pregnancy
 * @property int $anonym
 * @property string $region
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $housing
 * @property string $apartment
 * @property int $dependent
 * @property string $organization
 * @property string $profession
 * @property string $position
 * @property string $snils
 * @property string $benefit_name
 * @property string $benefit_number
 * @property string $benefit_series
 * @property string $benefit_date
 * @property string $benefit_issued
 * @property string $benefit_code
 * @property string $benefit_disability
 * @property int $blood_group
 * @property string $rh
 * @property string $drug_intolerance
 * @property string $chronic_illness
 * @property string $additional
 * @property int $not_address
 * @property int $person_id
 * @property string $create_app
 * @property int $anonymous_id
 * @property int $sync_with_lc_status 0 - готов к синхронизации или 
 * @property string $sync_with_lc_date
 */
class Patients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Patients';
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
            [['Name', 'LastName', 'Orders', 'PassportNum', 'PassportVidan', 'Palata', 'IndCity', 'StreetHouse', 'PhoneNumber', 'StrahNumber', 'KontragentName', 'KontragentKey', 'PasportNum', 'PasportDate', 'CACHE_PatID', 'CACHE_PatDetailID', 'LLCcode', 'metro', 'worlplace', 'workotdel', 'MobileNumber', 'MobileNotify', 'email', 'region', 'city', 'street', 'house', 'housing', 'apartment', 'organization', 'profession', 'position', 'snils', 'benefit_name', 'benefit_number', 'benefit_series', 'benefit_date', 'benefit_issued', 'benefit_code', 'benefit_disability', 'rh', 'drug_intolerance', 'chronic_illness', 'additional', 'create_app', 'sync_with_lc_status'], 'string'],
            [['BirthDate', 'BeremenDate', 'DateIns', 'DateLastUpdate', 'dt_begin_pregnancy', 'dt_end_pregnancy', 'sync_with_lc_date'], 'safe'],
            [['MG', 'KontragentAID', 'FromCache', 'anonym', 'dependent', 'blood_group', 'not_address', 'person_id', 'anonymous_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'Name' => 'Name',
            'LastName' => 'Last Name',
            'BirthDate' => 'Birth Date',
            'BeremenDate' => 'Beremen Date',
            'Orders' => 'Orders',
            'MG' => 'Mg',
            'PassportNum' => 'Passport Num',
            'PassportVidan' => 'Passport Vidan',
            'Palata' => 'Palata',
            'IndCity' => 'Ind City',
            'StreetHouse' => 'Street House',
            'PhoneNumber' => 'Phone Number',
            'StrahNumber' => 'Strah Number',
            'KontragentAID' => 'Kontragent Aid',
            'KontragentName' => 'Kontragent Name',
            'KontragentKey' => 'Kontragent Key',
            'PasportNum' => 'Pasport Num',
            'PasportDate' => 'Pasport Date',
            'DateIns' => 'Date Ins',
            'DateLastUpdate' => 'Date Last Update',
            'CACHE_PatID' => 'Cache  Pat ID',
            'CACHE_PatDetailID' => 'Cache  Pat Detail ID',
            'LLCcode' => 'Llccode',
            'metro' => 'Metro',
            'FromCache' => 'From Cache',
            'worlplace' => 'Worlplace',
            'workotdel' => 'Workotdel',
            'MobileNumber' => 'Mobile Number',
            'MobileNotify' => 'Mobile Notify',
            'email' => 'Email',
            'dt_begin_pregnancy' => 'Dt Begin Pregnancy',
            'dt_end_pregnancy' => 'Dt End Pregnancy',
            'anonym' => 'Anonym',
            'region' => 'Region',
            'city' => 'City',
            'street' => 'Street',
            'house' => 'House',
            'housing' => 'Housing',
            'apartment' => 'Apartment',
            'dependent' => 'Dependent',
            'organization' => 'Organization',
            'profession' => 'Profession',
            'position' => 'Position',
            'snils' => 'Snils',
            'benefit_name' => 'Benefit Name',
            'benefit_number' => 'Benefit Number',
            'benefit_series' => 'Benefit Series',
            'benefit_date' => 'Benefit Date',
            'benefit_issued' => 'Benefit Issued',
            'benefit_code' => 'Benefit Code',
            'benefit_disability' => 'Benefit Disability',
            'blood_group' => 'Blood Group',
            'rh' => 'Rh',
            'drug_intolerance' => 'Drug Intolerance',
            'chronic_illness' => 'Chronic Illness',
            'additional' => 'Additional',
            'not_address' => 'Not Address',
            'person_id' => 'Person ID',
            'create_app' => 'Create App',
            'anonymous_id' => 'Anonymous ID',
            'sync_with_lc_status' => 'Sync With Lc Status',
            'sync_with_lc_date' => 'Sync With Lc Date',
        ];
    }
}
