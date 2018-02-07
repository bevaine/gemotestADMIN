<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "erp_usergroups".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $permission
 */
class ErpUsergroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp_usergroups';
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
            [['parent_id'], 'required'],
            [['parent_id'], 'integer'],
            [['name', 'permission'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'permission' => 'Permission',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getErpGroupsList()
    {
        $arr = self::find()
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();
        return ArrayHelper::map($arr,'id','name');
    }
}
