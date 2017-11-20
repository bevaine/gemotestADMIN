<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_AuthAssignment".
 *
 * @property string $itemname
 * @property string $userid
 * @property string $bizrule
 * @property string $data
 */
class NAuthASsignment extends \yii\db\ActiveRecord
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
        return 'n_AuthAssignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemname', 'userid'], 'required'],
            [['itemname', 'userid', 'bizrule', 'data'], 'string'],
            [['itemname'], 'exist', 'skipOnError' => true, 'targetClass' => NAuthItem::className(), 'targetAttribute' => ['itemname' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemname' => 'Itemname',
            'userid' => 'Userid',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
        ];
    }
}
