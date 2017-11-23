<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 11:13
 */

namespace common\models;

use yii\base\Model;
use common\models\Doctors;
use Yii;

class AddUserForm extends Model
{
    public $key;
    public $type;
    public $department;
    public $nurse;
    public $lastName;
    public $firstName;
    public $middleName;
    public $operatorofficestatus;
    public $radioAccountsList;
    public $name;
    public $login;
    public $blankText;
    public $email;
    public $docId;
    public $specId;

    CONST SCENARIO_ADD_USER = 'addUser';
    CONST SCENARIO_ADD_DOC = 'addUserDoc';
    CONST SCENARIO_ADD_ORG = 'addUserOrg';
    CONST SCENARIO_ADD_FR = 'addUserFranch';

    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_USER => ['type', 'department', 'nurse', 'lastName', 'firstName', 'middleName', 'operatorofficestatus'],
            self::SCENARIO_ADD_DOC => ['docId', 'specId'],
            self::SCENARIO_ADD_ORG => ['name', 'key', 'login', 'blankText', 'email'],
            self::SCENARIO_ADD_FR => ['key', 'lastName', 'firstName', 'middleName', 'operatorofficestatus'],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type', 'lastName', 'firstName', 'middleName', 'operatorofficestatus', 'department', 'nurse'], 'required', 'on' => 'addUser'],
            [['docId', 'specId'], 'required', 'on' => 'addUserDoc'],
            [['name', 'key', 'login', 'blankText', 'email'], 'required', 'on' => 'addUserOrg'],
            [['lastName', 'firstName', 'middleName', 'key'], 'required', 'on' => 'addUserFranch'],
            [['lastName', 'firstName', 'middleName'], 'string'],
            [['department', 'nurse', 'key'], 'integer'],
            ['department', 'in', 'range' => array_keys(self::getDepartments())],
            ['nurse', 'in', 'range' => array_keys(self::getNurses())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'department' => 'Права пользователя',
            'nurse' => 'Доступ к модулю биомат.',
            'lastName' => 'Фамилия',
            'firstName' => 'Имя',
            'middleName' => 'Отчество',
            'operatorofficestatus' => 'Должность',
            'type' => 'Тип пользователя',
            'name' => 'Название организации',
            'key' => 'Ключ контрагента',
            'login' => 'Логин',
            'blankText' => 'Адрес организации',
            'email' => 'Email',
            'docId' => 'Врач',
            'specId' => 'Специализация'
        ];
    }

    /**
     * @return array
     */
    public static function getDepartments()
    {
        return [
            7 => 'Без прав', //вход через AD
            0 => 'Cобственные отделения', //вход через AD
            1 => 'Контакт центр', //вход через AD
            2 => 'Продажи', //вход через AD
            21 => 'Региональный менеджер', //вход через AD
            22 => 'Лабораторный техник', //вход через AD
            3 => 'Развитие', //вход через AD
            31 => 'ДУОЛО', //вход через AD
            32 => 'Фин. сопровождение договоров', //вход через AD
            33 => 'Бухгалтерия', //вход через AD
            4 => 'Отдел клиентской инф. поддержки', //вход через Logins
            5 => 'Мед регистратор', //вход через Logins
            6 => 'Клиент-менеджер', //вход через AD
            //8 => 'Администратор',
            //9 => 'Финансовый менеджер',
        ];
    }

    /**
     * @return array
     */
    public static function getTypesArray() {
        return [
            '7' => 'Собств. лаб. отд.', //вход через AD
            '8' => 'Франчайзи',         //вход через AD
            '5' => 'Врач консул.',      //вход через AD
            '1' => 'Администр.',        //вход через AD
            '9' => 'Ген. директор',     //вход через AD
            '13' => 'Фин. менеджер',    //вход через Logins
            '3' => 'Юр. лица',          //вход через Logins
            '4' => 'Врач иное.',        //вход через Logins
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

    /**
     * Список для select
     */
    public static function getKeysList()
    {
        $modules = [];
        $object = Logins::find()->where(['UserType' => '8'])->orderBy(['Name' => 'desc'])->all();
        foreach ($object as $model) {
            $modules[$model->Key] = $model->Name;
        }
        return $modules;
    }

}