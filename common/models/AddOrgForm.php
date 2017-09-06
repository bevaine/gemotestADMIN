<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 31.08.2017
 * Time: 14:59
 */

namespace common\models;

use yii\base\Model;

class AddOrgForm extends Model
{
    public $name;
    public $key;
    public $login;
    public $blankText;
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'key', 'login', 'blankText', 'email'], 'required'],
            [['email'], 'email'],
            [['name', 'key', 'login', 'blankText'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название организации',
            'key' => 'Ключ контрагента',
            'login' => 'Логин',
            'blankText' => 'Адрес организации',
            'email' => 'Email'
        ];
    }
}