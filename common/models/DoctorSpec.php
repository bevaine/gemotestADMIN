<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
 * @property SprDoctorSpec $spec
 * @property SprFilials $filials
 * @property array $senders
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
            'Name' => 'Имя',
            'LastName' => 'Фамилия',
            'SpetialisationID' => 'Специализация',
            'Active' => 'Активность',
            'GroupID' => 'Группа',
            'Fkey' => 'Отделение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpec()
    {
        return $this->hasOne(SprDoctorSpec::className(), ['aid' => 'SpetialisationID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilials()
    {
        return $this->hasOne(SprFilials::className(), ['Fkey' => 'Fkey']);
    }

    /**
     * @return array
     */
    public function getSenders()
    {
        $finModel = self::find()->alias('ds')
            ->joinWith(['filials f'], true, 'RIGHT JOIN')
            ->select(['f.Fkey'])
            ->where([
                'ds.Name' => $this->Name,
                'ds.LastName' => $this->LastName,
                'ds.SpetialisationID' => $this->SpetialisationID,
                'ds.GroupID' => $this->GroupID
            ])->asArray()->all();

        return ArrayHelper::getColumn($finModel, 'Fkey');
    }
}
