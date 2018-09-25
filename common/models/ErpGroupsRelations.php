<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "erp_groups_relations".
 *
 * @property integer $id
 * @property string $department
 * @property string $group
 * @property string $mis_access
 * @property integer $nurse
 * @property ErpUsergroups $name
 * @property string $action
 * @property array $permission
 * @property array $list_permission
 */

class ErpGroupsRelations extends \yii\db\ActiveRecord
{
    public $action;
    public $permission;
    public $list_permission;

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
            [['action', 'department', 'group'], 'string', 'max' => 255],
            [['nurse', 'mis_access'], 'integer'],
            [['permission', 'list_permission'], 'each', 'rule' => ['string']],
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
            'mis_access' => 'mis_access',
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
