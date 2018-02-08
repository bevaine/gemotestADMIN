<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "erp_groups_relations".
 *
 * @property integer $id
 * @property string $department
 * @property string $group
 * @property integer $nurse
 * @property ErpUsergroups $name
 */
class ErpGroupsRelations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp_groups_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department'], 'required'],
            [['department', 'group'], 'string', 'max' => 255],
            [['nurse'], 'integer']
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
            'group' => 'Group',
            'nurse' => 'Nurse',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(ErpUsergroups::className(), ['id' => 'group']);
    }

    /**
     * @param $department
     * @return bool
     */
    public static function getNurse($department)
    {
        if ($data = self::findOne(['department' => $department])) {
            if ($data->nurse == 1) return true;
        }
        return false;
    }
}
