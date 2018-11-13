<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 11:13
 */

namespace common\models;

use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AddUserForm
 * @package common\models
 * @property string  $key
 * @property string  $type
 * @property string  $department
 * @property string  $nurse
 * @property string  $lastName
 * @property string  $firstName
 * @property string  $middleName
 * @property string  $operatorofficestatus
 * @property string  $radioAccountsList
 * @property string  $name
 * @property string  $login
 * @property string  $blankText
 * @property string  $email
 * @property string  $docId
 * @property string  $specId
 * @property string  $phone
 * @property string  $changeGD
 * @property string  $guid
 */

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
    public $phone;
    public $changeGD;
    public $guid;


    CONST SCENARIO_ADD_USER = 'addUser';
    CONST SCENARIO_ADD_DOC = 'addUserDoc';
    CONST SCENARIO_ADD_GD = 'addUserGD';
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
            self::SCENARIO_ADD_USER => ['type', 'department', 'nurse', 'lastName', 'firstName', 'middleName', 'operatorofficestatus', 'guid'],
            self::SCENARIO_ADD_DOC => ['docId', 'specId'],
            self::SCENARIO_ADD_GD => ['name', 'key', 'phone', 'email', 'lastName', 'firstName', 'middleName','changeGD'],
            self::SCENARIO_ADD_FR => ['key', 'lastName', 'firstName', 'middleName', 'operatorofficestatus'],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type', 'lastName', 'firstName', 'operatorofficestatus', 'department', 'nurse', 'guid'], 'required', 'on' => 'addUser'],
            [['docId', 'specId'], 'required', 'on' => 'addUserDoc'],
            [['name', 'key', 'phone', 'lastName', 'firstName'], 'required', 'on' => 'addUserGD'],
            [['lastName', 'firstName', 'key'], 'required', 'on' => 'addUserFranch'],
            [['lastName', 'firstName', 'email'], 'string'],
            [['department', 'nurse', 'key','changeGD'], 'integer'],
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
            'specId' => 'Специализация',
            'phone' => 'Телефон',
            'guid' => 'GUID из 1С',
        ];
    }

    public static function getTypes($type = null)
    {
        $arr = [
            7 => 'Собственные лабораторные отделения',
            8 => 'Франчайзи',
            5 => 'Доктор-консультант',
            9 => 'Генеральный директор'
        ];
        return !is_null($type) && array_key_exists($type, $arr) ? $arr[$type] : $arr;
    }

    public static function getTypeList()
    {
        $options = [];
        $values = [];
        $types = self::getTypes();

        $object = SkynetRoles::find()
            ->where(['not in', 'type', [7]])
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        $abjectArr = ArrayHelper::getColumn($object, 'type');

        foreach ($types as $typeKey => $typeName)
        {
            $disable = false;
            $style = 'color:#02723f';
            $txt = $typeName;
            if (in_array($typeKey, $abjectArr)) {
                $disable = true;
                $txt .= ' - уже есть';
                $style =  'color:#ec1c24;font-weight:bold';
            }
            $values[$typeKey] = $txt;
            $options[$typeKey] = [
                'disabled' => $disable,
                'label' => $txt,
                'style' => $style
            ];
        }

        if (!empty($options) && !empty($values)) {
            //krsort($options);
            //krsort($values);
            return [
                'arrOptions' =>
                    ['options' => $options],
                'arrValues' =>
                    $values
            ];
        } else return null;
    }

    /**
     * @param int $type
     * @return array
     */
    public static function getDepartments($type = 7)
    {
        $arr = SkynetRoles::find()
            ->where(['type' => $type])
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }

    /**
     * @return array
     */
    public static function getMainDepartments()
    {
        return [
            8 => 'Работник франчайзи', //вход через AD
            9 => 'Генеральный директор', //вход через AD
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
     * @return array
     */
    public static function getKeysList()
    {
        $modules = [];
        $object = Logins::find()
            ->where(['UserType' => '8'])
            ->orderBy(['Name' => 'desc'])
            ->all();

        /** @var Logins $model */
        foreach ($object as $model) {
            $modules[$model->Key] = $model->Name;
        }
        return $modules;
    }
}