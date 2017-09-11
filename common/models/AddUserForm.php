<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 11:13
 */

namespace common\models;

use yii\base\Model;


class AddUserForm extends Model
{
    public $department;
    public $nurse;
    public $lastName;
    public $firstName;
    public $middleName;
    public $operatorofficestatus;
    public $radioAccountsList;

    CONST SCENARIO_ADD = 'addUser';
    CONST SCENARIO_FIND = 'findUser';

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['department', 'nurse', 'lastName', 'firstName', 'middleName', 'operatorofficestatus'],
            self::SCENARIO_FIND => ['lastName', 'firstName', 'middleName'],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['department', 'nurse', 'lastName', 'firstName', 'middleName', 'operatorofficestatus'], 'required', 'on' => 'addUser'],
            [['lastName', 'firstName', 'middleName'], 'required', 'on' => 'findUser'],
            ['department', 'in', 'range' => array_keys(self::getDepartments())],
            ['nurse', 'in', 'range' => array_keys(self::getNurses())],
            [['lastName', 'firsName', 'middleName'], 'string'],
            [['department', 'nurse'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'department' => 'Тип департамента',
            'nurse' => 'Доступ к модулю биомат.',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'operatorofficestatus' => 'Должность'
        ];
    }

    /**
     * @return array
     */
    public static function getDepartments()
    {
        return [
            7 => 'Без прав',
            0 => 'CЛО',
            1 => 'Контакт центр',
            2 => 'Продажи\\Региональный менеджер',
            3 => 'Развитие\\ДУОЛО\\Фин. сопровождение договоров\\Бухгалтерия\\',
            4 => 'ОКИП',
            5 => 'Мед регистратор',
            6 => 'Клиент-менеджер',
            8 => 'Врач-консультант'
        ];
    }

    /**
     * @return array
     */
    public static function getNurses()
    {
        return [
            0 => 'Нет',
            1 => 'Да',
            2 => 'Выездная МС'
        ];
    }
}