<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sprDoctorSpec".
 *
 * @property integer $aid
 * @property string $specName
 */
class SprDoctorSpec extends \yii\db\ActiveRecord
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
        return 'sprDoctorSpec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['specName'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'specName' => 'Spec Name',
        ];
    }

    /**
     * Список для select
     */
    public static function getKeysList()
    {
        $modules = [];
        foreach (self::find()->orderBy(['specName' => 'asc'])->all() as $model) {
            $modules[$model->aid] = $model->specName;
        }
        return $modules;
    }
}
