<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lpASs".
 *
 * @property integer $aid
 * @property string $ukey
 * @property string $utype
 * @property string $login
 * @property string $pass
 * @property string $dateins
 * @property string $iukey
 * @property string $iutype
 * @property integer $active
 */
class LpASs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lpASs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ukey', 'utype', 'login', 'pass', 'iukey', 'iutype'], 'string'],
            [['dateins'], 'safe'],
            [['active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'ukey' => 'Ukey',
            'utype' => 'Utype',
            'login' => 'Login',
            'pass' => 'Pass',
            'dateins' => 'Dateins',
            'iukey' => 'Iukey',
            'iutype' => 'Iutype',
            'active' => 'Active',
        ];
    }
}
