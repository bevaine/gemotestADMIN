<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "permissions".
 *
 * @property integer $id
 * @property string $department
 * @property string $permission
 * @property NAuthItem $name
 */
class Permissions extends \yii\db\ActiveRecord
{
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
        return 'permissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department'], 'required'],
            [['department', 'permission'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department' => 'Department',
            'permission' => 'Permission',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(NAuthItem::className(), ['name' => 'permission']);
    }
}