<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbo.med_ReturnWithoutItem".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $order_num
 * @property string $total
 * @property string $date
 * @property integer $pay_type
 * @property string $kkm
 * @property string $z_num
 * @property string $comment
 * @property string $path_file
 * @property string $base
 * @property integer $user_aid
 * @property string $code_1c
 */
class MedReturnWithoutItem extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('MIS');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dbo.med_ReturnWithoutItem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'pay_type', 'user_aid'], 'integer'],
            [['order_num', 'total', 'pay_type', 'kkm', 'z_num'], 'required'],
            [['order_num', 'kkm', 'z_num', 'comment', 'path_file', 'base', 'code_1c'], 'string'],
            [['total'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родитель',
            'order_num' => '№ заказа',
            'total' => 'Сумма',
            'date' => 'Дата',
            'pay_type' => 'Тип оплаты',
            'kkm' => 'ККМ',
            'z_num' => 'Z-отчет',
            'comment' => 'Комментарий',
            'path_file' => 'Путь к файлу',
            'base' => 'Основание',
            'user_aid' => 'Пользователь',
            'code_1c' => 'Код 1С',
        ];
    }
}
