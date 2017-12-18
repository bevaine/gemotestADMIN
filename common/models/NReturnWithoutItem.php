<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_ReturnWithoutItem".
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
class NReturnWithoutItem extends \yii\db\ActiveRecord
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
        return 'n_ReturnWithoutItem';
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
            'parent_id' => 'Родитель ID',
            'order_num' => '№ заказа',
            'total' => 'Сумма',
            'date' => 'Дата',
            'pay_type' => 'Тип оплаты',
            'kkm' => 'ККМ',
            'z_num' => 'Z-отчет',
            'comment' => 'Комментарий',
            'path_file' => 'Путь к файлу',
            'base' => 'Тип возврата',
            'user_aid' => 'Сотрудник',
            'code_1c' => 'Код 1С',
        ];
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getBaseArray($id = null) {
        $arr =  ['claim' => 'Претензия', 'complex' => 'Комплекс'];
        return is_null($id) ? $arr : $arr[$id];
    }
}
