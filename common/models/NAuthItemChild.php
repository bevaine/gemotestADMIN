<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_AuthItemChild".
 *
 * @property string $parent
 * @property string $child
 *
 * @property NAuthItem $parent0
 * @property NAuthItem $child0
 */
class NAuthItemChild extends \yii\db\ActiveRecord
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
        return 'n_AuthItemChild';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string'],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => NAuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => NAuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(NAuthItem::className(), ['name' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(NAuthItem::className(), ['name' => 'child']);
    }
}
