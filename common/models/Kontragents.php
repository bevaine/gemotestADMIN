<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Kontragents".
 *
 * @property integer $AID
 * @property string $Name
 * @property string $Key
 * @property string $ShortName
 * @property integer $LoginsAID
 * @property string $BlankText
 * @property string $BlankName
 * @property integer $isDelete
 * @property integer $PayType
 * @property string $Blanks
 * @property integer $Type
 * @property integer $rmGroup
 * @property integer $inoe
 * @property integer $cito
 * @property integer $goscontract
 * @property string $Li_cOrg
 * @property string $LCN
 * @property string $ReestrUslug
 * @property string $RegionID
 * @property string $dt_off_discount
 * @property integer $flNoDiscCard
 * @property string $dt_off_auto_discount
 * @property string $dt_off_discount_card
 * @property integer $hide_price
 * @property integer $lab
 * @property string $code_1c
 * @property string $contract_number
 * @property string $contract_name
 * @property string $contractor_name
 * @property string $contract_date
 * @property string $date_update
 * @property integer $price_supplier
 * @property integer $sampling_of_biomaterial
 * @property integer $use_ext_num
 * @property string $payment
 * @property string $ext_num_mask
 * @property string $salt
 * @property MapPoint1 $MapPoint1
 */
class Kontragents extends \yii\db\ActiveRecord
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
        return 'Kontragents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Key', 'ShortName', 'BlankText', 'BlankName', 'Blanks', 'Li_cOrg', 'LCN', 'ReestrUslug', 'RegionID', 'code_1c', 'contract_number', 'contract_name', 'contractor_name', 'payment', 'ext_num_mask', 'salt'], 'string'],
            [['LoginsAID', 'isDelete', 'PayType', 'Type', 'rmGroup', 'inoe', 'cito', 'goscontract', 'flNoDiscCard', 'hide_price', 'lab', 'price_supplier', 'sampling_of_biomaterial', 'use_ext_num'], 'integer'],
            [['dt_off_discount', 'dt_off_auto_discount', 'dt_off_discount_card', 'contract_date', 'date_update'], 'safe'],
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
            'Key' => 'Key',
            'ShortName' => 'Short Name',
            'LoginsAID' => 'Logins Aid',
            'BlankText' => 'Blank Text',
            'BlankName' => 'Blank Name',
            'isDelete' => 'Is Delete',
            'PayType' => 'Pay Type',
            'Blanks' => 'Blanks',
            'Type' => 'Type',
            'rmGroup' => 'Rm Group',
            'inoe' => 'Inoe',
            'cito' => 'Cito',
            'goscontract' => 'Goscontract',
            'Li_cOrg' => 'Li C Org',
            'LCN' => 'Lcn',
            'ReestrUslug' => 'Reestr Uslug',
            'RegionID' => 'Region ID',
            'dt_off_discount' => 'Dt Off Discount',
            'flNoDiscCard' => 'Fl No Disc Card',
            'dt_off_auto_discount' => 'Dt Off Auto Discount',
            'dt_off_discount_card' => 'Dt Off Discount Card',
            'hide_price' => 'Hide Price',
            'lab' => 'Lab',
            'code_1c' => 'Code 1c',
            'contract_number' => 'Contract Number',
            'contract_name' => 'Contract Name',
            'contractor_name' => 'Contractor Name',
            'contract_date' => 'Contract Date',
            'date_update' => 'Date Update',
            'price_supplier' => 'Price Supplier',
            'sampling_of_biomaterial' => 'Sampling Of Biomaterial',
            'use_ext_num' => 'Use Ext Num',
            'payment' => 'Payment',
            'ext_num_mask' => 'Ext Num Mask',
            'salt' => 'Salt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapPoint1()
    {
        return $this->hasOne(MapPoint1::className(), ['sender_id' => 'Key']);
    }
}
