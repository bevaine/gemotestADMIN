<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "n_AuthItem".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 *
 * @property NAuthAssignment[] $nAuthAssignments
 * @property NAuthItemChild[] $nAuthItemChildren
 * @property NAuthItemChild[] $nAuthItemChildren0
 * @property NAuthItem[] $children
 * @property NAuthItem[] $parents
 */
class NAuthItem extends \yii\db\ActiveRecord
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
        return 'n_AuthItem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'description', 'bizrule', 'data'], 'string'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNAuthAssignments()
    {
        return $this->hasMany(NAuthAssignment::className(), ['itemname' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNAuthItemChildren()
    {
        return $this->hasMany(NAuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNAuthItemChildren0()
    {
        return $this->hasMany(NAuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(NAuthItem::className(), ['name' => 'child'])->viaTable('n_AuthItemChild', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(NAuthItem::className(), ['name' => 'parent'])->viaTable('n_AuthItemChild', ['child' => 'name']);
    }

    /**
     * @param bool $id
     * @return array|mixed
     */
    public static function getListName($id = false)
    {
        $modules = [];
        $arrCategory = [0 => 'Операции', 1 => 'Задания', 2 => 'Роли'];

        /** @var $model NAuthItem */
        foreach (self::find()->all() as $model) {
            $modules[$arrCategory[$model->type]][$model->name] = $model->description;
        }
        return $id !== false && isset($modules[$id]) ? ArrayHelper::getValue($modules, $id) : $modules;
    }
}
