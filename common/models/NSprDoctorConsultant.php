<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_spr_DoctorConsultant".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property integer $active
 * @property integer $post_id
 * @property string $post_name
 * @property string $login
 */
class NSprDoctorConsultant extends \yii\db\ActiveRecord
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
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_spr_DoctorConsultant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'active', 'post_id'], 'integer'],
            [['name', 'surname', 'patronymic', 'post_name', 'login'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'active' => 'Active',
            'post_id' => 'Post ID',
            'post_name' => 'Post Name',
            'login' => 'Login',
        ];
    }
}
