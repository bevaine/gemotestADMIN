<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_Nurse".
 *
 * @property integer $id
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property integer $active
 */
class NNurse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_Nurse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_name', 'first_name'], 'required'],
            [['last_name', 'first_name', 'middle_name'], 'string'],
            [['active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'active' => 'Active',
        ];
    }
}
