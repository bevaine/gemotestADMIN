<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "t23".
 *
 * @property string $q1
 * @property string $q2
 * @property string $q3
 * @property string $q4
 * @property string $q5
 * @property string $q6
 * @property string $q7
 * @property string $q8
 * @property string $q9
 * @property string $q10
 * @property string $q11
 * @property string $q12
 * @property string $q13
 * @property string $q14
 * @property string $q15
 * @property string $q16
 * @property string $q17
 * @property string $q18
 * @property string $q19
 * @property string $q20
 * @property string $q21
 * @property string $q22
 * @property string $q23
 */
class T23 extends \yii\db\ActiveRecord
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
    public static function primaryKey()
    {
        return ['q1'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't23';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20', 'q21', 'q22', 'q23'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'q1' => 'Q1',
            'q2' => 'Q2',
            'q3' => 'Q3',
            'q4' => 'Q4',
            'q5' => 'Q5',
            'q6' => 'Q6',
            'q7' => 'Q7',
            'q8' => 'Дата приема',
            'q9' => 'Дата увол.',
            'q10' => 'Q10',
            'q11' => 'Q11',
            'q12' => 'Q12',
            'q13' => 'Q13',
            'q14' => 'Q14',
            'q15' => 'Q15',
            'q16' => 'Q16',
            'q17' => 'Q17',
            'q18' => 'Q18',
            'q19' => 'GUID',
            'q20' => 'Таб. номер',
            'q21' => 'q21',
            'q22' => 'Q22',
            'q23' => 'Q23',
        ];
    }
}
