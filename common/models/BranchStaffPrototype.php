<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "branch_staff_prototype".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 * @property string $guid
 * @property string $special_id
 */
class BranchStaffPrototype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch_staff_prototype';
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
            [['title', 'guid', 'special_id'], 'string'],
            [['parent_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'parent_id' => 'Parent ID',
            'guid' => 'Guid',
            'special_id' => 'Special ID',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getPrototypeList()
    {
        $arr = self::find()
            ->select(['id', 'special_id', 'name' => 'title'])
            ->where(['guid' => null])
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }
}
