<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "DoctorSpec".
 *
 * @property integer $AID
 * @property string $Name
 * @property string $LastName
 * @property integer $SpetialisationID
 * @property integer $Active
 * @property integer $GroupID
 * @property string $Fkey
 */
class DoctorSpec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DoctorSpec';
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
            [['Name', 'LastName', 'Fkey'], 'string'],
            [['SpetialisationID', 'Active', 'GroupID'], 'integer'],
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
            'SpetialisationID' => 'Spetialisation ID',
            'Active' => 'Active',
            'GroupID' => 'Group ID',
            'Fkey' => 'Fkey',
        ];
    }
}
