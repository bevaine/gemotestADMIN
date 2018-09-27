<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 12:14
 */

namespace common\components\helpers;

use Codeception\Lib\Generator\Shared\Classname;
use common\models\DirectorFlo;
use common\models\DirectorFloSender;
use common\models\ErpGroupsRelations;
use common\models\MedCounterpartyPos;
use common\models\medUserCounterparty;
use common\models\NSprDoctorConsultant;
use common\models\Permissions;
use common\models\SkynetRoles;
use Symfony\Component\Debug\Tests\Fixtures\ClassAlias;
use Yii;
use Exception;
use common\models\Doctors;
use common\models\SprDoctorSpec;
use common\models\ErpNurses;
use common\models\ErpUsers;
use common\models\Logins;
use common\models\LpASs;
use common\models\NAdUseraccounts;
use common\models\NAdUsers;
use common\models\NAuthASsignment;
use common\models\NNurse;
use common\models\Operators;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\db\Transaction;

/**
 * Class ActiveSyncHelper
 * @package common\components\helpers
 * @property string $lastName
 * @property string  $firstName
 * @property string  $middleName
 * @property string  $fullName
 * @property string  $accountName
 * @property string  $emailAD
 * @property string  $loginAD
 * @property string  $loginGS
 * @property string  $passwordAD
 * @property string  $passwordGS
 * @property string  $pathAD
 * @property string  $department
 * @property string  $cacheId
 * @property string  $cachePass
 * @property string  $type
 * @property string  $operatorofficestatus
 * @property string  $nurse
 * @property integer $aid
 * @property string  $login
 * @property string  $password
 * @property string  $key
 * @property string  $typeLO
 * @property string  $state
 * @property string  $displayName
 * @property string  $cnName
 * @property string  $specId
 * @property string  $idAD
 * @property string  $orgName
 * @property boolean $resetPassword
 * @property string  $emailGD
 * @property string  $phone
 * @property string  $directorID
 * @property mixed   $directorFloSender
 * @property integer $changeGD
 * @property string  $tableName
 * @property boolean $createNewGS
 * @property integer $erpGroup
 * @property integer $message
 * @property integer $nurseId
 * @property string  $dateNow
 * @property string  $loginName
 * @property string  $loginEmail
 * @property object  $conf
 * @property boolean $addAd
 */

class ActiveSyncHelper
{
    CONST LDAP_PORT     =  636;
    CONST LDAP_SERVER   = '192.168.108.3';
    //CONST LDAP_SERVER   = '192.168.108.185';
    CONST LDAP_URL      = "ldaps://sw-dc-03.lab.gemotest.ru";
    CONST LDAP_LOGIN    = 'dymchenko.adm@lab.gemotest.ru';
    CONST LDAP_PASSW    = '2Hszfaussw';
    CONST LDAP_DN       = "DC=lab,DC=gemotest,DC=ru";

    CONST POSTFIX_PORT      = 22;
    CONST POSTFIX_SERVER    = '192.168.151.152';
    CONST POSTFIX_LOGIN     = 'itr';
    CONST POSTFIX_PASSW     = 'Gthtgenmt117!';

    CONST ORG_NAME      = 'ООО "Лаборатория Гемотест"';
    CONST LOGO_TEXT     = '107031 Москва, Рождественский бульвар д.21, ст.2^*^тел. (495) 532-13-13, 8(800) 550-13-13^*^www.gemotest.ru';
    CONST LOGO_IMG      = 'logos/LogoGemotest.gif';

    public $lastName;
    public $firstName;
    public $middleName;
    public $fullName;
    public $accountName;
    public $emailAD;
    public $loginAD;
    public $loginGS;
    public $passwordAD;
    public $passwordGS;
    public $pathAD;
    public $department;
    public $cacheId;
    public $cachePass;
    public $type;
    public $operatorofficestatus;
    public $nurse;
    public $aid;
    public $login;
    public $password;
    public $key;
    public $typeLO;
    public $state;
    public $displayName;
    public $cnName;
    public $specId;
    public $idAD;
    public $orgName;
    public $resetPassword;
    public $emailGD;
    public $phone;
    public $directorID;
    public $changeGD;
    public $createNewGS;
    public $erpGroup;
    public $message;
    public $dateNow;
    public $conf;
    public $loginName;
    public $loginEmail;
    public $nurseId;
    public $addAd;

    /**
     * ActiveSyncHelper constructor.
     */
    public function __construct()
    {
       $this->orgName = self::ORG_NAME;
       $this->resetPassword = false;
       $this->createNewGS = false;
       $this->dateNow = date("Y-m-d G:i:s:000");
    }

    /**
     * ActiveSyncHelper destructor.
     */
    public function __destruct()
    {
        if (!empty($this->message)) {
            Yii::getLogger()->log(
                $this->message,
                Logger::LEVEL_WARNING, 'ADD_SKYNET_USER'
            );
        }
    }

    /**
     * @return array
     */
    public static function getConf()
    {
        return [
            "tables" => [
                Operators::class => [
                    "CACHE_Login" => "{accountName}",
                    "Name" => "{fullName}",
                    "LastName" => "{lastName}",
                    "DateIns" => "{dateNow}",
                    "CACHE_OperatorID" => "{cacheId}",
                    "OperatorOfficeStatus" => "{operatorofficestatus}",
                    "Pass" => "{cachePass}",
                    "Active" => "{userData}",
                    "CanRegister" => "{userData}",
                    "InputOrderRM" => "{userData}",
                    "mto_editor" => "{userData}",
                    "MedReg" => "{userData}",
                    "mto" => "{userData}",
                    "OrderEdit" => "{userData}",
                    "ClientMen" => "{userData}"
                ],
                Logins::class => [
                    //"Logo" => self::LOGO_IMG,
                    //"LogoText" => self::LOGO_TEXT,
                    "Name" => "{loginName}",
                    "Email" => "{loginEmail}",
                    "aid" => "{aid}",
                    "Login" => "{loginGS}",
                    "Pass" => "{cachePass}",
                    "Key" => "{cacheId}",
                    "UserType" => "{type}",
                    "CACHE_Login" => "{accountName}",
                    "last_update_password" => "{dateNow}",
                    "tbl" => "{tableName}",
                    "LogoText2" => "",
                    "LogoType" => "",
                    "LogoWidth" => "",
                    "TextPaddingLeft" => "",
                    "IsAdmin" => "{userData}",
                    "IsOperator" => "{userData}",
                    "OpenExcel" => "{userData}",
                    "EngVersion" => "{userData}",
                    "InputOrder" => "{userData}",
                    "PriceID" => "{userData}",
                    "CanRegister" => "{userData}",
                    "goscontract" => "{userData}",
                    "FizType" => "{userData}",
                    "mto_editor" => "{userData}",
                    "IsDoctor" => "{userData}",
                    "InputOrderRM" => "{userData}",
                    "OrderEdit" => "{userData}",
                    "MedReg" => "{userData}",
                    "mto" => "{userData}",
                    "clientmen" => "{userData}",
                    "show_preanalytic" => "{userData}"
                ],
                LpASs::class => [
                    "ukey" => "{cacheId}",
                    "utype" => "{type}",
                    "iukey" => "{userData}",
                    "iutype" => "{userData}",
                    "login" => "{loginGS}",
                    "pass" => "{cachePass}",
                    "dateins" => "{dateNow}",
                    "active" => "{userData}"
                ],
                NAdUsers::class => [
                    "last_name" => "{lastName}",
                    "first_name" => "{firstName}",
                    "middle_name" => "{middleName}",
                    "AD_name" => "{fullName}",
                    "AD_position" => "{operatorofficestatus}",
                    "AD_email" => "{emailAD}",
                    "gs_email" => "{emailAD}",
                    "gs_id" => "{aid}",
                    "gs_key" => "{cacheId}",
                    "gs_usertype" => "{type}",
                    "AD_login" => "{accountName}",
                    "allow_gs" => "{userData}",
                    "active" => "{userData}",
                    "AD_active" => "{userData}",
                    "auth_ldap_only" => "{userData}",
                    "create_date" => "{dateNow}",
                    "last_update" => "{dateNow}",
                ],
                ErpUsers::class => [
                    //"group_id" => "{group_id}",
                    "name" => "{fullName}",
                    "login" => "{accountName}",
                    "skynet_login" => "{loginGS}",
                    "password" => "d9b1d7db4cd6e70935368a1efb10e377", //123
                    "status" => "{userData}",
                    "password_dt" => "{dateNow}",
                ],
                NAdUseraccounts::class => [
                    "last_name" => "{lastName}",
                    "first_name" => "{firstName}",
                    "middle_name" => "{middleName}",
                    "gs_type" => "{typeLO}",
                    "gs_id" => "{cacheId}",
                    "gs_position" => "{operatorofficestatus}",
                    "org_name" => "{orgName}",
                    "ad_login" => "{loginAD}",
                    "ad_pass" => "{passwordAD}"
                ],
                ErpNurses::class => [
                    "user_id" => "{nurseId}",
                    "nurse_email" => "{emailAD}",
                    "nurse_phone" => "",
                    "nurse_key" => "{cacheId}",
                    "flag_inr_specialization" => "{userData}"
                ],
                medUserCounterparty::class => [
                    "user_id" => "{aid}",
                    "counterparty_id" => ""
                ],
                MedCounterpartyPos::class => [
                    "pos_key" => "{cacheId}",
                    "counterparty_id" => ""
                ],
                NSprDoctorConsultant::class => [
                    "id" => "{cacheId}",
                    "name" => "{firstName}",
                    "surname" => "{lastName}",
                    "patronymic" => "{middleName}",
                    "active" => "{userData}",
                    "post_id" => "{userData}",
                    "post_name" => "3.Врач",
                    "login" => "{loginGS}"
                ],
                DirectorFlo::class => [
                    "first_name" => "{firstName}",
                    "middle_name" => "{middleName}",
                    "last_name" => "{lastName}",
                    "phoneNumber" => "{phone}",
                    "email" => "{emailGD}",
                    "passReplaced" => "{userData}",
                    "login" => "{loginGS}",
                    "password" => "{cachePass}",
                ]
            ],
            "structure" => [
                7 => [
                    "operator" => [
                        Operators::class
                    ],
                    "main" => [
                        Logins::class,
                        LpASs::class,
                    ],
                    "ad_authorization" => [
                        NAdUsers::class,
                        NAdUseraccounts::class,
                    ],
                    "erp" => [
                        ErpUsers::class,
                    ],
                    "nurse" => [
                        ErpNurses::class,
                    ],
                    "party" => [
                        medUserCounterparty::class,
                        MedCounterpartyPos::class
                    ],
                ],
                5 => [
                    "doctors" => [],
                    "main" => [
                        Logins::class,
                        LpASs::class,
                    ],
                    "ad_authorization" => [
                        NAdUsers::class,
                        NAdUseraccounts::class,
                    ],
                    "doctor_consultant" =>[
                        NSprDoctorConsultant::class
                    ]
                ],
                8 => [
                    "franchazy" => [],
                    "ad_authorization" => [
                        NAdUsers::class,
                        NAdUseraccounts::class,
                    ],
                ],
                9 => [
                    "director" => [
                        DirectorFlo::class
                    ],
                    "main" => [
                        Logins::class,
                        LpASs::class,
                    ],
                    "ad_authorization" => [
                        NAdUsers::class,
                        NAdUseraccounts::class,
                    ],
                    "party" => [
                        medUserCounterparty::class,
                        MedCounterpartyPos::class
                    ],
                ]
            ]
        ];
    }

    /**
     * @param $tablesClass
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function setTable($tablesClass)
    {
        switch ($tablesClass) {

            case Operators::class:
                /** @var $model Operators */
                $model = $this->checkOperatorAccount();
                if ($model && !$this->createNewGS) {
                    if (!$this->loadAndSave($model)) return false;
                    isset($this->cacheId) ?: $this->cacheId = $model->CACHE_OperatorID;
                }
                if (!$model || $this->createNewGS) {
                    $this->setLastOperatorCacheId();
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу : " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                $this->aid = 3000000 + strval($model->AID);
                return true;
                break;

            case Logins::class:
                /** @var $model Logins */
                $this->setLoginName();
                $this->setLoginEmail();
                if ($this->type == 7) {
                    $this->generateLoginSlo();
                }
                $model = $this->checkLoginAccountOne();
                if ($model && !$this->createNewGS) {
                    $this->state = "old";
                    if (!$this->loadAndSave($model)) return false;
                    $this->unblockAccount($model->aid);
                }
                if (!$model || $this->createNewGS) {
                    /** @var Logins $model */
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($this->type == 9) {
                        $model->block_register = $this->dateNow;
                    }
                    $model->Logo = self::LOGO_IMG;
                    $model->LogoText = self::LOGO_TEXT;
                    if ($model->save()) {
                        $this->state = "new";
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                $this->loginGS = $model->Login;
                $this->passwordGS = $model->Pass;
                return true;
                break;

            case NSprDoctorConsultant::class:
                /** @var NSprDoctorConsultant $model */
                $model = $this->checkDoctorConsultant();
                if ($model && !$this->createNewGS) {
                    if (!$this->loadAndSave($model)) return false;
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case LpASs::class:
                /** @var $model LpASs */
                $model = $this->checkLpASs();
                if ($model && !$this->createNewGS) {
                    if (!$this->loadAndSave($model)) return false;
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case NAdUsers::class:
                /** @var $model NAdUsers */
                $model = $this->checkNAdUsers();
                if ($model && !$this->createNewGS) {
                    $model->gs_id = $this->aid;
                    $model->gs_key = $this->cacheId;
                    $model->gs_usertype = $this->type;
                    if (!$this->loadAndSave($model)) return false;
                    $this->state = 'old';
                    $this->idAD = $model->ID;
                }
                if (!$model || $this->createNewGS) {
                    /** @var $transaction Transaction */
                    $transaction = NAdUsers::getDb()->beginTransaction();
                    try {
                        if (!$model = $this->constructModel($tablesClass)) {
                            return false;
                        }
                        if ($model->save()) {
                            $transaction->commit();
                            $this->state = 'new';
                            $this->idAD = $transaction->db->getLastInsertID();
                            $this->loginAD = $this->accountName;
                            $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                        } else {
                            $transaction->rollBack();
                            $this->message["error"][] = [
                                __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                                $model->errors
                            ];
                            return false;
                        }
                    } catch(\Exception $e) {
                        $transaction->rollBack();
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $e->getMessage()
                        ];
                        return false;
                    } catch(\Throwable $e) {
                        $transaction->rollBack();
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $e->getMessage()
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case NAdUseraccounts::class:
                /** @var $model NAdUseraccounts */
                $this->loginAD = "lab\\".$this->accountName;
                $model = $this->checkNAdUserAccounts();
                if ($model && !$this->createNewGS) {
                    if ($this->resetPassword) {
                        $model->ad_pass = $this->passwordAD;
                    }
                    if (!$this->loadAndSave($model)) return false;
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                        $this->loginAD = $model->ad_login;
                        $this->passwordAD = $model->ad_pass;
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case ErpUsers::class:
                /** @var $model ErpUsers */
                $model = $this->checkErpUsers();
                if ($model && !$this->createNewGS) {
                    if (!$this->loadAndSave($model)) return false;
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                        $this->erpGroup = $model->group_id;
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case ErpNurses::class:
                /** @var $model ErpNurses */
                $this->setLastNurseId();
                $model = $this->checkErpNurse();
                if ($model && !$this->createNewGS) {
                    if (!$this->loadAndSave($model)) return false;
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                return true;
                break;

            case medUserCounterparty::class:
                $counterparty_arr = [];
                if ($this->type == 9) {
                    $counterparty_arr[] = $this->key;
                } else {
                    $infoConf = ArrayHelper::toArray(json_decode($this->conf->info_json));
                    if (array_key_exists('counterparty_id', $infoConf)
                        && is_array($infoConf['counterparty_id'])) {
                        $counterparty_arr = $infoConf['counterparty_id'];
                    }
                }

                foreach ($counterparty_arr as $counterparty_id)
                {
                    if ($this->checkMedUserCounterparty($counterparty_id))
                        continue;

                    $model = new medUserCounterparty();
                    $model->user_id = $this->aid;
                    $model->counterparty_id = $counterparty_id;

                    if ($model->save()) {
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                    }
                }
                return true;
                break;

            case DirectorFlo::class:
                /** @var $model DirectorFlo */
                $this->generateLoginDirector();
                $model = $this->checkDirectorFlo();
                if ($model && !$this->createNewGS) {
                    $this->state = 'old';
                }
                if (!$model || $this->createNewGS) {
                    if (!$model = $this->constructModel($tablesClass)) {
                        return false;
                    }
                    if ($model->save()) {
                        $this->state = 'new';
                        $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $model::tableName();
                    } else {
                        $this->message["error"][] = [
                            __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $model::tableName(),
                            $model->errors
                        ];
                        return false;
                    }
                }
                $this->directorID = $model->id;
                $this->aid = 8000000 + strval($this->directorID);
                if (!$this->addCheckDirectorFloSender()) return false;
                return true;
                break;
        }
        return true;
    }

    /**
     * @param $tableClass
     * @param bool $userData
     * @return array|bool
     */
    public static function getTableFields($tableClass, $userData = false)
    {
        $attr = [];
        $fields = [];
        $conf = self::getConf();

        if (class_exists($tableClass)) {
            /** @var Model $newClass */
            $newClass = new $tableClass;
            $attr = $newClass->attributeLabels();
        }
        $tableData = $conf['tables'][$tableClass];

        foreach ($tableData as $field => $value)
        {
            $type = self::checkVar($tableClass, $field);
            $title = array_key_exists($field, $attr) ? $attr[$field] : $field;
            preg_match("^\{(.*?)\}^", $value, $field_out);
            if (empty($field_out[1])) continue;
            if (($userData && $field_out[1] == 'userData')
                || !$userData && $field_out[1] != 'userData')
                continue;

            $fields[] = [
                'value' => $field_out[1],
                'title' => $title,
                'name' => $field,
                'type' => $type
            ];
        }
        return !empty($fields) ? $fields : false;
    }

    /**
     * @param $className
     * @return array|bool
     */
    public function checkObjectVars($className)
    {
        $errors = [];
        if ($out = self::getTableFields($className,  true)) {
            foreach ($out as $field) {
                if (!isset($this->{$field['value']})) {
                    $errors[] = $field;
                }
            }
        }
        return !empty($errors) ? $errors : false;
    }

    /**
     * @return bool
     */
    public function setLoginAndCacheId()
    {
        if (isset($this->aid)) {
            /** @var Logins $loginModel */
            $loginModel = Logins::find()
                ->filterWhere(['aid' => $this->aid])
                ->one();
            if ($loginModel) {
                $this->cacheId = $loginModel->Key;
                $this->loginGS = $loginModel->Login;
                return true;
            }
        }
        return false;
    }

    /**
     *
     */
    public function setLastNurseId() {
        $this->nurseId = ErpUsers::find()
            ->where(['group_id' => $this->erpGroup])
            ->max('id');
    }

    /**
     * @return SkynetRoles
     */
    public function getUserConf()
    {
        if ($this->type === 7) {
            return SkynetRoles::findOne($this->department);
        } else {
            return SkynetRoles::findOne(['type' => $this->type]);
        }
    }

    /**
     * @param $model
     * @param $field
     * @return array|bool
     */
    public static function checkVar($model, $field)
    {
        /** @var Model $modelClass */
        $modelClass = new $model;
        $rules = $modelClass->rules();
        foreach ($rules as $rule) {
            if (isset($rule[0])) {
                if (in_array('string', $rule)
                    && in_array($field, $rule[0])){
                    return 'string';
                } elseif (in_array('integer', $rule)
                    && in_array($field, $rule[0])){
                    return 'integer';
                }
            }
        }
        return false;
    }

    /**
     * @param $tablesClass
     * @return array|bool
     */
    public function loadUserData($tablesClass)
    {
        $outArr = [];
        if (empty($this->conf->tables_json))
            return false;

        $userTable = ArrayHelper::toArray(
            json_decode($this->conf->tables_json)
        );
        if (!array_key_exists($tablesClass, $userTable)) {
            return false;
        }
        foreach ($userTable[$tablesClass] as $fieldName => $fieldVal)
        {
            preg_match("^\{(.*?)\}^", $fieldVal, $outField);
            if (!isset($outField[1])){
                $outArr[self::parseClassPath($tablesClass)][$fieldName] = $fieldVal;
            }
        }
        return !empty($outArr) ? $outArr : false;
    }


    public static function parseClassPath($name)
    {
        $array = explode('\\', $name);
        return $array[count($array) - 1];
    }

    /**
     * @param $tablesClass
     * @return bool|Model
     */
    public function constructModel($tablesClass)
    {
        if (empty($this->conf->tables_json))
            return false;

        $userTable = ArrayHelper::toArray(
            json_decode($this->conf->tables_json)
        );

        $objectVars = self::checkObjectVars($tablesClass);
        if ($objectVars !== false) {
            $this->message["error"][] = [
                "Не заполнены обязательные поля для добавления в " . self::parseClassPath($tablesClass) . ": ",
                $objectVars
            ];
            return false;
        }

        /** @var Model $newModel */
        $newModel = new $tablesClass;
        foreach ($userTable[$tablesClass] as $fieldName => $fieldVal)
        {
            preg_match("^\{(.*?)\}^", $fieldVal, $outField);
            if (isset($outField[1]) && $outField[1] !== "userData")
            {
                $type = self::checkVar($tablesClass, $fieldName);
                if (isset($this->{$outField[1]})) {
                    $newModel->{$fieldName} = (!$type || $type== 'string')
                        ? strval($this->{$outField[1]})
                        : (int)$this->{$outField[1]};
                }
            } else {
                $newModel->{$fieldName} = $fieldVal;
            }
        }
        return $newModel;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function checkLoginAccountAll()
    {
        return Logins::find()
            ->andFilterWhere([
                'OR',
                ['like', 'Name', $this->fullName],
                ['aid' => $this->aid]
            ])
            ->andFilterWhere(['UserType' => $this->type])
            ->all();
    }

    /**
     * @return mixed
     */
    public function checkAccount()
    {
        /**
         * @var Logins $findUserLogin
         */
        $this->addAd = false;
        $baseConf = self::getConf();

        if ($this->conf = self::getUserConf())
        {
            //todo получение конфигурации пользователя
            $structureConf = json_decode($this->conf->structure_json);

            if (in_array('ad_authorization', $structureConf)) {
                $this->addAd = true;
            }

            //todo генеруруем/задаем пароль для входа в GS
            $this->setCachePass();

            //todo добавляем/сбрасываем пароль для УЗ AD
            if ($this->type !== 5) {
                if (!$this->createAdUserAcc()) return false;
            }

            foreach ($structureConf as $structure)
            {
                switch ($structure) {
                    case 'doctors':
                        if (!$this->setDoctorObjectParams()) return false;
                        if (!$this->createAdUserAcc()) return false;
                        break;
                    case 'franchazy':
                        if (!$this->checkFranchazyAccount()) return false;
                        break;
                }

                $tablesClass = $baseConf['structure'][$this->type][$structure];
                if (!empty($tablesClass)) {
                    foreach ($tablesClass as $table)
                    {
                        if (!self::setTable($table))
                        {
                            $this->message['success'][] = '<p>Не удалось создать/изменить УЗ для <b>' . $this->fullName . '</b> в таблице AD</p>';
                            return false;
                        }
                    }
                }
            }
        } else {
            $urlRules = \yii\helpers\Url::to(['/admin/skynet-roles/create']);
            $this->message['success'][] = "<h4>Rules Info:</h4><p>Не определен набор правил для данного вида пользователей - <b><a style='color: #0a0a0a' href='{$urlRules}'>Добавить</a></b></p>";
            return false;
        }

        //todo добавление ролей для пользователя
        if ($this->type !== 8) {
            if (!$this->addPermissions()) return false;
        }

        return $this->setLoginPassword();
    }

    /**
     * @return bool
     */
    public function setLoginPassword()
    {
        if (empty($this->aid)
            || empty($this->fullName))
            return false;

        if (!empty($this->loginAD)
            && !empty($this->passwordAD)) {
            $this->login = $this->loginAD;
            $this->password = $this->passwordAD;
        } elseif (!empty($this->loginGS)
            && !empty($this->passwordGS)) {
            $this->login = $this->loginGS;
            $this->password = $this->passwordGS;
        } else return false;

        return true;
    }

    /**
     * @return bool
     */
    public function generateLoginSlo()
    {
        if ($this->department == 5) {
            $this->loginGS = 'medr' . $this->aid;
        } else {
            $this->loginGS = 'reg' . $this->aid;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function generateLoginDirector()
    {
        if (empty($this->key))
            return false;

        $cacheId = Logins::find()->where([
            'tbl' => 'DirectorFlo'
        ])->max('[key]');
        if (!$cacheId) return false;

        $this->cacheId =  strval($cacheId) + 1;
        $this->loginGS = $this->key."-gd-".strval(rand(100,999));
        return true;
    }

    /**
     *
     */
    public function setLoginName()
    {
        $this->loginName = $this->cacheId;
        if (!empty($this->operatorofficestatus)) {
            $this->loginName .= '.' . $this->operatorofficestatus;
        }
        $this->loginName .= ": " . $this->fullName;
        if ($this->type == 9) {
            $this->loginName = $this->fullName;
        }
    }

    /**
     *
     */
    public function setLoginEmail()
    {
        $this->loginEmail = !empty($this->emailAD) ? $this->emailAD : '';
        if ($this->type == 9) {
            $this->loginEmail = $this->loginGS."@gemosystem.ru";
        }
    }

    /**
     * @return mixed
     */
    public function addCheckDirectorFloSender()
    {
        if ($this->changeGD == 1) {
            $objectDirectorFloSender = DirectorFloSender::findOne([
                'sender_key' => $this->key
            ]);

            if ($objectDirectorFloSender) {
                if ($objectDirectorFloSender->director_id != $this->directorID) {
                    $objectDirectorFloSender->director_id = $this->directorID;
                } else return true;
            }
        } else {
            $objectDirectorFloSender = new DirectorFloSender();
            $objectDirectorFloSender->sender_key = $this->key;
            $objectDirectorFloSender->director_id = $this->directorID;
        }

        if ($objectDirectorFloSender->save()) {
            $this->message["info"][] = __FUNCTION__ . ": Добавлены данные в таблицу: " . $objectDirectorFloSender::tableName();
        } else {
            $this->message["error"][] = [
                __FUNCTION__ . ": Ошибка при добавлении данных в таблицу: " . $objectDirectorFloSender::tableName(),
                $objectDirectorFloSender->errors
            ];
            return false;
        }
        return true;
    }

    /**
     * @return ActiveRecord|boolean
     */
    public function checkDirectorFlo()
    {
        if (empty($this->firstName) || empty($this->lastName)) {
            $this->message['error'][] = 'addCheckDirectorFlo : Одно из обязательных полей пустое!';
            return false;
        }

        return DirectorFlo::findOne([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
        ]);
    }

    /**
     * @return bool
     */
    private function setDoctorObjectParams() {

        if (empty($this->key) || empty($this->specId))
        {
            $this->message['error'][] = 'setDoctorObjectParams : Одно из обязательных полей пустое!';
            return false;
        }

        $doctorModel = Doctors::findOne([
            'CACHE_DocID' => $this->key,
            'Is_Cons' => '4'
        ]);

        if (!$doctorModel) return false;

        $loginId = $doctorModel->AID;
        $expName = explode(" ", $doctorModel->Name);

        $this->aid = 1000000 + $loginId;
        $this->loginGS = 'v'.$doctorModel->CACHE_DocID;
        $this->lastName = $doctorModel->LastName;
        $this->firstName = $expName[0];
        if (count($expName) > 2) $this->middleName = $expName[1];
        $this->fullName = $this->lastName . " " . $this->firstName;
        if (!empty($this->middleName))
            $this->fullName .= " " . $this->middleName;

        if (array_key_exists($this->specId, SprDoctorSpec::getKeysList())) {
            $this->operatorofficestatus = SprDoctorSpec::getKeysList()[$this->specId];
        }
        return true;
    }

    /**
     * @return bool
     */
    public function createAdUserAcc()
    {
        if (!empty($this->accountName)) {

            //todo если находим то сбрасываем пароль в AD
            $this->passwordAD = '******';

            if ($this->resetPassword) {
                $newPasswordAd = self::resetPasswordAD($this->accountName);
                if ($newPasswordAd) {
                    $this->message['info'][] = 'resetPasswordAD : Пароль успешно изменен для ' . $this->accountName;
                    $this->passwordAD = $newPasswordAd;
                    $message = '<p>Для пользователя <b>' . $this->fullName . '</b> был изменен пароль для входа в Windows!</p>';
                    $message .= '<p>Данные для входа в Windows:<p>';
                    $message .= '<br>Логин: <b>' . $this->accountName . '</b>';
                    $message .= '<br>Пароль: <b>' . $this->passwordAD . '</b>';
                    $this->message['success'][] = $message;
                }
            }
        } else {
            //todo генерируем логин/email
            $this->generateAdLogin($this->addAd);

            //todo если нет УЗ в AD - создаем
            if ($this->addAd) {
                if (!$this->addUserAD()) {
                    $message = '<p>Не удалось создать УЗ для <b>' . $this->fullName . '</b> в AD</p>';
                    $this->message['success'][] = $message;
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function addPermissions()
    {
        if (empty($this->aid)) {
            $this->message['error'][] = 'ActiveSyncController=>addPermissions : Одно из обязательных полей пустое!';
            return false;
        }

        $connection = 'GemoTestDB';
        $db = Yii::$app->$connection;
        /** @var $transaction Transaction */
        $transaction = $db->beginTransaction();

        try {
            //todo присвоение прав пользователю
            $findPermissions = Permissions::findAll([
                'department' => $this->department
            ]);

            if ($findPermissions) {
                //todo удаляем все роли у пользователя
                $db->createCommand()->delete(
                    NAuthASsignment::tableName(),
                    ['userid' => $this->aid]
                )->execute();

                $rowInsert = [];
                foreach ($findPermissions as $permission) {
                    $rowInsert[] = [
                        $permission->permission,
                        $this->aid,
                        'N;'
                    ];
                }
                $db->createCommand()->batchInsert(
                    NAuthASsignment::tableName(),
                    ['itemname', 'userid', 'data'],
                    $rowInsert
                )->execute();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->message['error'][] = 'NAuthASsignment->batchInsert : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @param bool $checkAd
     * @return bool
     */
    public function generateAdLogin($checkAd = false)
    {
        $this->cnName = $this->fullName;

        if ($this->type == 8 && !empty($this->key)) {
            $this->cnName = $this->fullName . ' ' . $this->key;
            $account_name_v1 = $this->key;
            $account_name_v1 .= "." . substr($this->translit($this->firstName),0,1);
            $account_name_v1 .= "." . $this->translit($this->lastName);
        } else {
            $account_name_v1 = $this->translit($this->firstName . "." . $this->lastName);
        }
        $arr_logins[] = self::cutAccountName($account_name_v1);

        if (!empty($this->middleName)) {
            $account_name_v2 = $this->translit($this->firstName);
            $account_name_v2 .= "." . substr($this->translit($this->middleName), 0, 1);
            $account_name_v2 .= "." . $this->translit($this->lastName);
            $arr_logins[] = self::cutAccountName($account_name_v2);
        }

        for ($i = 1; $i < 10; $i++) {
            $account_name_v3 = substr($this->translit($this->firstName), 0, 1);
            $account_name_v3 .= "." . $this->translit($this->lastName);
            $arr_logins[] = self::cutAccountName($account_name_v3) . $i;;
        }

        if ($checkAd) {
            foreach ($arr_logins as $login)
            {
                if ($this->checkUserAccountAd($login))
                {
                    if ($this->typeLO != 'FLO' && !empty($this->operatorofficestatus)) {
                        $this->cnName = $this->fullName . " (".$this->operatorofficestatus.")";
                    }
                } else {
                    $this->accountName = $login;
                    break;
                }
            }
        } else {
            $this->accountName = $arr_logins[0];
        }

        $this->displayName = $this->fullName;
        if (!empty($this->operatorofficestatus)) {
            $this->displayName = $this->fullName . " (" . $this->operatorofficestatus . ")";
        }

        $this->emailAD = $this->accountName."@gemotest.ru";
        return true;
    }

    /**
     * @return bool
     */
    public function addUserAD()
    {
        //todo создаем нового пользователя в AD
        $arrAccountAD = $this->addNewUserAd();

        if (!$arrAccountAD) {
            $this->message['error'][] = 'addUserAD: Не удалось добавить учетную запись в AD';
            return false;
        }
        else {
            if (!$infGroup = self::addGroupLdap()) {
                return false;
            }
            $this->message['info'][] = 'addUserAD: Успешно добавлена учетная запись в AD!';
            $this->accountName = $arrAccountAD['SamAccountName'];
            $this->emailAD = $arrAccountAD['UserPrincipalName'];
            $this->passwordAD = $arrAccountAD['AccountPassword'];
        }
        return true;
    }

    /**
     * @param $accountName
     * @return bool|string
     */
    static function cutAccountName ($accountName)
    {
        if (strlen($accountName) > 19) {
            return substr($accountName, 0, 19);
        } else return $accountName;
    }

    /**
     * @param $fromAid
     * @param $toAid
     * @return array|bool
     */
    static function addFromDonor($fromAid, $toAid)
    {
        $rowInsertOut = [];

        //todo удаляем все роли у пользователя
        $rowInsert = NAuthASsignment::find()->where([
            'userid' => $fromAid
        ])->asArray()->all();

        if (is_array($rowInsert) && count($rowInsert) > 0) {

            foreach ($rowInsert as $row) {
                $row['userid'] = $toAid;
                $rowInsertOut[] = $row;
            }

            /** @var $transaction Transaction */
            $connection = 'GemoTestDB';
            $db = Yii::$app->$connection;
            $transaction = $db->beginTransaction();

            try {
                //todo удаляем все роли у пользователя
                $db->createCommand()->delete(
                    NAuthASsignment::tableName(),
                    ['userid' => $toAid]
                )->execute();

                $db->createCommand()->batchInsert(
                    NAuthASsignment::tableName(),
                    ['itemname', 'userid', 'bizrule', 'data'],
                    $rowInsertOut
                )->execute();

                $transaction->commit();
                $rowRules = ArrayHelper::getColumn($rowInsert, 'itemname');
                return $rowRules;

            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::getLogger()->log(
                    'addFromDonor->batchInsert : ' . $e->getMessage(),
                    Logger::LEVEL_ERROR,
                    'ADD_SKYNET_USER');
                return false;
            }

        }
        return true;
    }

    /**
     * @param ActiveRecord $object
     * @return bool|ActiveRecord
     */
    public function loadAndSave($object)
    {
        if (!$object || !class_exists($object->className())) return false;

        $this->message["info"][] = __FUNCTION__ . ": В " . self::parseClassPath($object::tableName()) . " уже есть запись!";

        if (!$loadData = $this->loadUserData($object->className())) {
            $this->message["info"][] = __FUNCTION__ . ": В " . self::parseClassPath($object::tableName()) . " нет полей для обновления!";
            return true;
        }

        if ($object->load($loadData) && $object->save()) {
            $this->message["info"][] = __FUNCTION__ . ": В " . self::parseClassPath($object::tableName()) . " успешно обновлены данные в соотвествии с правами!";
            return $object;
        } else {
            $this->message["error"][] = __FUNCTION__ . ": Возникли ошибки при обновлении данных в " . self::parseClassPath($object::tableName()) . "!";
            return false;
        }
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function checkOperatorAccount()
    {
        /** @var  $objectOperators Operators */
        if ($this->setLoginAndCacheId()) {
            return Operators::find()
                ->andFilterWhere(['CACHE_OperatorID' => $this->cacheId])
                ->one();
        }
        return Operators::find()
            ->andFilterWhere(['like', 'Name', $this->fullName])
            ->andFilterWhere(['LastName' => $this->lastName])
            ->one();
    }

    /**
     * @return array|bool|null|ActiveRecord
     */
    public function checkDoctorConsultant()
    {
        return NSprDoctorConsultant::find()
            ->where(['id' => strval($this->cacheId)])
            ->one();
    }

    /**
     * @return array|bool|null|ActiveRecord
     */
    public function checkLoginAccountOne()
    {
        if (isset($this->aid)) {
            return Logins::find()
                ->andFilterWhere(['aid' => $this->aid])
                ->andFilterWhere(['UserType' => $this->type])
                ->one();
        } elseif ($this->cacheId) {
            return Logins::find()
                ->andFilterWhere(['Key' => $this->cacheId])
                ->andFilterWhere(['UserType' => $this->type])
                ->one();
        } else {
            return Logins::find()
                ->andFilterWhere(['like', 'Name', $this->fullName])
                ->andFilterWhere(['UserType' => $this->type])
                ->one();
        }
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function checkLpASs()
    {
        return LpASs::find()
            ->andFilterWhere(['ukey' => strval($this->cacheId)])
            ->andFilterWhere(['utype' => $this->type])
            ->one();
    }

    /**
     * @return array|bool|null|ActiveRecord
     */
    public function checkNAdUsers()
    {
        return NAdUsers::find()
            ->andFilterWhere(['AD_login' => $this->accountName])
            ->one();
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function checkNAdUserAccounts()
    {
        return NAdUseraccounts::find()
            ->andFilterWhere(['ad_login' => $this->loginAD])
            ->one();
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function checkErpUsers()
    {
        return ErpUsers::find()
            ->where(['login' => $this->accountName])
            ->one();
    }

    /**
     * @return array|null|ActiveRecord
     */
    public function checkErpNurse()
    {
        return ErpNurses::find()
            ->where(['nurse_key' => $this->cacheId])
            ->one();
    }

    /**
     * @param $counterparty_id
     * @return array|null|ActiveRecord
     */
    public function checkMedUserCounterparty($counterparty_id)
    {
        return medUserCounterparty::find()
            ->filterWhere(['user_id' => $this->aid])
            ->andFilterWhere(['counterparty_id' => $counterparty_id])
            ->one();
    }

    /**
     * @return boolean
     */
    public function checkFranchazyAccount()
    {
        if (empty($this->key)
        ) {
            $this->message['error'][] = 'checkFranchazyAccount : Одно из обязательных полей пустое!';
            return false;
        }

        /** @var $loginSearch Logins */
        $loginSearch = Logins::find()
            ->andFilterWhere(['Key' => $this->key])
            ->andFilterWhere(['UserType' => 8])
            ->one();

        if ($loginSearch) {
            if ($loginSearch->franchazy) {
                $this->orgName = $loginSearch->franchazy->BlankName;
            }
            $this->emailAD = $loginSearch->Email;
            $this->aid = $loginSearch->aid;
            return true;
        } else {
            $message = '<p>У данного контрагента нет УЗ, невозможно добавить <b>' . $this->fullName . '</b></p>';
            Yii::$app->session->setFlash('error', $message);
            return false;
        }
    }

    /**
     *
     */
    private function setLastOperatorCacheId()
    {
        /** @var  $cacheId Operators */
        $cacheId = Operators::find()
            ->select('CACHE_OperatorID')
            ->orderBy('AID DESC')
            ->one();
        if (!empty($cacheId->CACHE_OperatorID)) {
            $this->cacheId = strval($cacheId->CACHE_OperatorID) + 1;
        }
    }

    /**
     *
     */
    private function setCachePass()
    {
        $this->cachePass = Yii::$app->getSecurity()->generateRandomString(8);
    }

    /**
     * @param null $login
     * @param null $password
     * @return array|bool
     */
    public static function createResetPasswordGD($login = null, $password = null)
    {
        if (is_null($login) || is_null($password)) {
            return [
                "status" => 0,
                "msg" => "createResetPasswordGD : пустые логин или пароль"
            ];
        }

        $script = "sudo ./changePasswordSkynet.sh '".$login."' '".$password."'";

        try {
            $connection = ssh2_connect(self::POSTFIX_SERVER, self::POSTFIX_PORT);
            if (!$connect = ssh2_auth_password(
                $connection,
                self::POSTFIX_LOGIN,
                self::POSTFIX_PASSW)
            ) {
                return [
                    "status" => 0,
                    "msg" => "createResetPasswordGD : Не удалось авторизироваться на сервере " . self::POSTFIX_SERVER
                ];
            }

            ssh2_shell($connection, 'xterm');
            if (!ssh2_exec($connection, $script)) return false;

        } catch (Exception $e) {
            return [
                "status" => 0,
                "msg" => "createResetPasswordGD : " . $e->getMessage()
            ];
        }
        return [
            "status" => 0,
            "msg" => ''
        ];
    }

    /**
     * @param $accountName
     * @param null $password
     * @return bool|null|string
     */
    public static function resetPasswordAD($accountName, $password = null)
    {
       is_null($password) ? $newPassword = self::generatePasswordAD() : $newPassword = $password;

        $newPasswordUTF6LE = iconv("UTF-8", "UTF-16LE", '"' . $newPassword . '"');
        $ADgroup = "DC=lab,DC=gemotest,DC=ru";

        try {

            $ldapconn = ldap_connect(self::LDAP_URL, self::LDAP_PORT);
            if (!$ldapconn) return false;

            ldap_set_option($ldapconn, LDAP_OPT_DEBUG_LEVEL, 7);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);
            if (!$ldapbind) return false;

            $searchResults = ldap_search($ldapconn, $ADgroup, 'sAMAccountName='.$accountName);

            if ($searchResults === false || !is_resource($searchResults)) return false;

            $entry = ldap_first_entry($ldapconn, $searchResults);
            if (!is_resource($entry)) return false;

            $userDn = ldap_get_dn($ldapconn, $entry);
            ldap_modify($ldapconn, $userDn, ['unicodePwd' => $newPasswordUTF6LE]);
            ldap_close($ldapconn);

            return $newPassword;

        } catch (Exception $e) {

            Yii::getLogger()->log(
                'ActiveSyncController : ' . $e->getMessage(),
                Logger::LEVEL_ERROR,
                'ADD_SKYNET_USER');
            return false;
        }
    }

    /**
     * @return array|bool
     */
    public function addNewUserAd()
    {
        $password = self::generatePasswordAD();
        $name = $this->firstName;
        if (!empty($this->middleName))
            $name .= " ".$this->middleName;

        $ldaprecord = [
            "CN" => $this->cnName,
            "name" => $this->cnName,
            "sn" => $this->lastName, //фамилия
            "givenname" => $name, //имя отчество
            "sAMAccountName" => $this->accountName, //логин
            "userPrincipalName" => $this->emailAD, //емаил
            "mail" => $this->emailAD, //емаил
            "objectClass" => "user",
            "displayname" => $this->displayName, //ФИО
            "unicodepwd" => iconv("UTF-8", "UTF-16LE", '"' . $password . '"'),
            "userAccountControl" => "544" //доступ
        ];

        $this->message['info'][] = ['addNewUserAd->$ldaprecord' => $ldaprecord];

        try {

            $ADgroup = "CN=".$ldaprecord["CN"].",OU=SSO ".$this->typeLO." Users,OU=Departments,OU=Gemotest,DC=lab,DC=gemotest,DC=ru";

            $ldapconn = ldap_connect(self::LDAP_URL, self::LDAP_PORT);
            if (!$ldapconn) return false;

            ldap_set_option($ldapconn, LDAP_OPT_DEBUG_LEVEL, 7);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);
            if (!$ldapbind) return false;

            $output = ldap_add($ldapconn, $ADgroup, $ldaprecord);
            ldap_close($ldapconn);

            if ($output)
            {
                $this->message['info'][] = 'addNewUserAd: В AD успешно добавлена УЗ '.$this->accountName;
                return [
                    'SamAccountName' => $this->accountName,
                    'UserPrincipalName' => $this->emailAD,
                    'AccountPassword' => $password
                ];
            } else {
                return false;
            }

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Add: Already exists') !== false) {
                $message = '<p>Не удалось создать УЗ в AD для <b> ' . $this->cnName. '</b>';
                $message .= ' т.к. данный пользователь с таким <b>именем</b> уже есть, либо не синхронизированы контроллеры домена! Повторите попытку позже!</p>';
                    Yii::$app->session->setFlash('warning', $message);
            }
            if (strpos($e->getMessage(), 'Add: Constraint violation') !== false) {
                $message = '<p>Не удалось создать УЗ в AD для <b> ' . $this->accountName. '</b>';
                $message .= ' т.к. данный пользователь с таким <b>логином</b> уже есть, либо не синхронизированы контроллеры домена! Повторите попытку позже!</p>';
                Yii::$app->session->setFlash('warning', $message);
            }

            $this->message['error'][] = 'ActiveSyncController : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    function addGroupLdap()
    {
        $dnPath = "OU=Departments,OU=Gemotest";
        $dnName = "SSO {$this->typeLO} Users";

        $groupPath = "OU=SSO {$this->typeLO} Users,OU=Departments,OU=Gemotest";//рабочий путь
        $groupName = "SSO {$this->typeLO} Users"; //рабочая группа

//        $groupPath = "OU=ALL,OU=Departments,OU=Gemotest"; //тестовый путь
//        $groupName = "svc-bitrix-import-exclude"; //тестовая группа

        $dnMember = "CN={$this->cnName},OU={$dnName},{$dnPath},".self::LDAP_DN; //контенер
        $groupMember = "CN={$groupName},{$groupPath},".self::LDAP_DN;; //группа

        try {
            $ldapconn = ldap_connect(self::LDAP_SERVER);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            if (!$ldapconn) return false;

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);

            if (!$ldapbind) return false;

            $userdata['member'] = $dnMember;

            $result = ldap_modify($ldapconn, $groupMember, $userdata);

            if (!$result) {
                $txt = "<h4>AD Info:</h4><p>Возникли ошибки при добавлении пользователя: <b>{$this->cnName}</b>";
                $txt .= "<br> в группу <b>\"{$groupName}\"</b> и/или контейнер <b>\"{$dnName}\"</b> </p>";
                $this->message['success'][] = $txt;
                $this->message['error'][] = [$dnMember, $groupMember];
            } else {
                $txt = "<h4>AD Info:</h4><p>Пользователь: <b>{$this->cnName}</b> успешно добавлен";
                $txt .= "<br> в группу <b>\"{$groupName}\"</b> и контейнер <b>\"{$dnName}\"</b> </p>";
                $this->message['success'][] = $txt;
                $this->message['info'][] = $txt;
            }
            return $result;

        } catch (Exception $e) 
        {
            $txt = "<h4>AD Info:</h4><p>Возникли ошибки при добавлении пользователя: <b>{$this->cnName}</b>";
            $txt .= "<br> в группу <b>\"{$groupName}\"</b> и/или контейнер <b>\"{$dnName}\"</b> </p>";
            $txt .= "<br>msg: " . $e->getMessage();
            $this->message['success'][] = $txt;
            $this->message['error'][] = [$dnMember, $groupMember];
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function checkUserNameAd()
    {
        //todo проверяем на полное совпадение имени пользователя
        $arrAccounts = [];
        $ADcheckUser = "(name=*".$this->fullName."*)";
        $justthese = array("displayname", "samaccountname", "userprincipalname", 'cn', 'name', 'uniqueMember');

        try {
            $ldapconn = ldap_connect(self::LDAP_SERVER);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            if (!$ldapconn) return false;

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);

            if (!$ldapbind) return false;

            $sr = ldap_search($ldapconn, self::LDAP_DN, $ADcheckUser, $justthese);

            $info = ldap_get_entries($ldapconn, $sr);
            ldap_close($ldapconn);

            if (!$info || $info['count'] == 0)  
            {
                $this->message['info'][] = 'checkUserNameAd : ' . $this->fullName.' '.'не найден в AD';
                return false;
            } else {
                $this->message['info'][] = ['$info' => $info];
            }

            for ($i = 0; $i < $info['count']; $i++) {
                $samaccountname = $info[$i]['samaccountname'][0];
                $findModel = NAdUsers::findOne(['AD_login' => $samaccountname]);
                $findModel ? $active = 1 : $active = 0;
                $arrAccounts[] = [
                    'account' => $samaccountname,
                    'name' => $info[$i]['displayname'][0],
                    'email' => $info[$i]['userprincipalname'][0],
                    'active' => $active
                ];
            }

            $this->message['info'][] = ['checkUserNameAd=>arrAccounts' => $arrAccounts];

        } catch (Exception $e)
        {
            $this->message['error'][] = 'checkUserNameAd : ' . $e->getMessage();
            return false;
        }
        return empty($arrAccounts) ? false : $arrAccounts;
    }

    /**
     * @return bool
     */
    public function checkUserAccountAd ($accountName)
    {
        //todo проверяем на совпадение УЗ AD
        $ADcheckUser = "(samaccountname=*".$accountName."*)";
        $justthese = array("samaccountname");

        try {
            $ldapconn = ldap_connect(self::LDAP_SERVER);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            if (!$ldapconn) return false;

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);

            if (!$ldapbind) return false;

            $sr = ldap_search($ldapconn, self::LDAP_DN, $ADcheckUser, $justthese);
            $info = ldap_get_entries($ldapconn, $sr);
            ldap_close($ldapconn);

            //todo проверяем существует ли запись по логину в AD
            if (!$info || $info['count'] == 0)
            {
                $this->message['info'][] = 'checkUserAccountAd : ' . $accountName . ' не найдена в AD!';
                return false;
            } else {
                $this->message['info'][] = 'checkUserAccountAd : ' . $accountName . ' найдена в AD!';
                return true;
            }

        } catch (Exception $e) {
            $this->message['error'][] = 'checkUserAccountAd : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * @param $aid
     * @return bool
     */
    public function unblockAccount($aid)
    {
        $findUsersLogins = Logins::findOne(['aid' => $aid]);
        if (!$findUsersLogins) return false;

        if (time() > strtotime($findUsersLogins->DateEnd)
            || time() > strtotime($findUsersLogins->block_register)) {
            $findUsersLogins->DateEnd = NULL;
            $findUsersLogins->block_register = NULL;
            if ($findUsersLogins->save()) {
                $message = '<p>У данного пользователя учетная запись разблокирована!</p>';
                Yii::$app->session->setFlash('warning', $message);
            } else return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public static function generatePasswordAD()
    {
        return str_shuffle(Yii::$app->getSecurity()->generateRandomString(8).rand(1, 9));
    }

    /**
     * @param string
     * @return string
     */
    public function translit($st)
    {
        $st = mb_strtolower($st, "utf-8");
        $st = str_replace([
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',
            'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',
            'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'
        ], [
            '_', '_', '.', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_',
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',
            'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h',
            'j', 'i', 'e', '_', 'zh', 'ts', 'ch', 'sh', 'shch',
            '', 'yu', 'ya'
        ], $st);
        $st = preg_replace("/[^a-z0-9_.]/", "", $st);
        $st = trim($st, '_');

        $prev_st = '';
        do {
            $prev_st = $st;
            $st = preg_replace("/_[a-z0-9]_/", "_", $st);
        } while ($st != $prev_st);

        $st = preg_replace("/_{2,}/", "_", $st);
        return $st;
    }


}