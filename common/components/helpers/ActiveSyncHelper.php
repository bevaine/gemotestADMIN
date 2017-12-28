<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 12:14
 */

namespace common\components\helpers;

use common\models\DirectorFlo;
use common\models\DirectorFloSender;
use common\models\medUserCounterparty;
use common\models\NSprDoctorConsultant;
use common\models\Permissions;
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
 * @property string  $aid
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
 */

class ActiveSyncHelper
{
    CONST LDAP_PORT     =  636;
    CONST LDAP_SERVER   = '192.168.108.3';
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

    /**
     * ActiveSyncHelper constructor.
     */
    public function __construct()
    {
       $this->orgName = self::ORG_NAME;
       $this->resetPassword = false;
       $this->createNewGS = false;
    }

    /**
     * @return mixed
     */
    public function checkAccount()
    {
        /**
         * @var Logins $findUserLogin
         */
        //todo генеруруем/задаем пароль для входа в GS
        $this->setCachePass();

        if ($this->type == 5) {
            //todo если доктор-консультант

            //todo присваиваем значения переменным
            if (!$this->setDoctorObjectParams()) return false;

            //todo добавляем/сбрасываем пароль для УЗ AD
            if (!$this->createAdUserAcc()) return false;

            //todo проверяем/добавляем запись в Logins
            if (!$this->addCheckLogins()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addUserAdTables()) return false;

            //todo проверяем/добавляем запись в таблицу lpASs
            if (!$this->addLpassUsers()) return false;

            //todo проверяем/добавляем запись в таблицу n_spr_DoctorConsultant
            if (!$this->addDoctorConsultant()) return false;

            //todo добавление ролей для пользователя
            if (!$this->addPermissions()) return false;

        } elseif ($this->type == 7) {

            //todo роли с авторизацией в AD
            if (in_array($this->department, [0, 1, 2, 3, 6, 7, 8])) {

                //todo добавляем/сбрасываем пароль для УЗ AD
                if (!$this->createAdUserAcc()) return false;
            }

            //todo проверяем/добавляем запись в Operators
            if (!$this->addCheckOperators()) return false;

            //todo проверяем/добавляем запись в Logins
            if (!$this->addCheckLogins()) return false;

            if (in_array($this->department, [0, 1, 2, 3, 6, 7, 8])) {

                //todo проверяем/добавляем запись в таблицы AD
                if (!$this->addUserAdTables()) return false;

             //todo роли без авторизации в AD
            } elseif (in_array($this->department, [4, 5])) {

                //todo проверяем/добавляем запись в lpASs
                if (!$this->addLpassUsers()) return false;
            }
            //todo добавление пользователя в другие таблицы в соотвествии с ролью
            if (!$this->addDepartmentRules()) return false;

            //todo добавление ролей для пользователя
            if (!$this->addPermissions()) return false;

            //todo добавление пользователя для выбора ЛО
            if (!$this->addCheckCounterparty()) return false;

        } elseif ($this->type == 8) {
            //todo если франчайзи (только проверяем запись в Logins)

            //todo добавляем/сбрасываем пароль для УЗ AD
            if (!$this->createAdUserAcc()) return false;

            //todo проверяем есть ли УЗ у франчайзи
            if (!$this->checkFranchazyAccount()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addUserAdTables()) return false;

            //todo добавление ролей для пользователя
            if (!$this->addPermissions()) return false;

        } elseif ($this->type == 9) {

            //todo добавляем/сбрасываем пароль для УЗ AD
            if (!$this->createAdUserAcc()) return false;

            //todo генерируем Login
            if (!$this->generateLogin()) return false;

            //todo проверяем/создаем есть ли УЗ в DirectorFlo
            if (!$this->addCheckDirectorFlo()) return false;

            //todo если на отделении есть назначенный директор, и выбрали переназначить директора
            if (!$this->addCheckDirectorFloSender()) return false;

            //todo проверяем/добавляем запись в Logins
            if (!$this->addCheckLogins()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addUserAdTables()) return false;

            //todo добавление ролей для пользователя
            if (!$this->addPermissions()) return false;

            //todo добавление пользователя для выбора ЛО
            if (!$this->addCheckCounterparty()) return false;
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
    public function addCheckOperators()
    {
        /**
         * @var Operators $findUsersOperators
         * @var Operators $cacheId
         */
        if (empty($this->lastName)
            || empty($this->firstName)
        ) {
            Yii::getLogger()->log([
                'addCheckOperators'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        //todo проверяем существует ли запись в Operators
        $objectOperators = $this->checkOperatorAccount();
        if (!$objectOperators || $this->createNewGS)
        {
            $this->setLastOperatorCacheId();
            $name = $this->firstName;
            if (!empty($this->middleName))
                $name .= " ".$this->middleName;

            //todo если нет, то добавление в Operators
            $objectOperators = new Operators();
            $objectOperators->CACHE_Login = $this->accountName;
            $objectOperators->Name = $name;
            $objectOperators->LastName = $this->lastName;
            $objectOperators->DateIns = date("Y-m-d G:i:s:000");
            $objectOperators->CACHE_OperatorID = strval($this->cacheId);
            $objectOperators->OperatorOfficeStatus = $this->operatorofficestatus;
            $objectOperators->Pass = $this->cachePass;
            $objectOperators->Active = 1;
            $objectOperators->CanRegister = 1;
            $objectOperators->InputOrderRM = 1;
            $objectOperators->mto_editor = 0;

            $objectOperators->MedReg = ($this->department == 5) ? 1 : 0;
            $objectOperators->mto = in_array($this->department, [2,3,5,6]) ? 1 : 0;
            $objectOperators->OrderEdit = in_array($this->department, [3,4,6]) ? 1 : 0;
            $objectOperators->ClientMen = in_array($this->department, [3,4,6]) ? 1 : 0;

            if ($objectOperators->save()) {
                Yii::getLogger()->log([
                    'objectOperators->save()'=>$objectOperators
                ], Logger::LEVEL_WARNING, 'binary');
            } else {
                Yii::getLogger()->log([
                    'objectOperators->save()'=>$objectOperators->errors
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
        }
        return $this->generateLogin($objectOperators);
    }

    /**
     * @param $model Operators/
     * @return bool
     */
    private function generateLogin($model = null)
    {
        if ($this->type == 7) {

            if (!$model
                || !isset($model->AID)
                || !isset($model->CACHE_OperatorID)
                || !isset($this->department)
            ) return false;

            $loginId = $model->AID;
            $this->aid = 3000000 + $loginId;
            $this->cacheId = $model->CACHE_OperatorID;

            if ($this->department == 5) {
                $this->loginGS = 'medr' . $loginId;
            } else {
                $this->loginGS = 'reg' . $loginId;
            }

        } elseif ($this->type == 9) {

            if (empty($this->key)) return false;

            $cacheId = Logins::find()->where([
                'tbl' => 'DirectorFlo'
            ])->max('[key]');

            if (!$cacheId) return false;

            $this->cacheId =  strval($cacheId) + 1;
            $this->loginGS = $this->key."-gd-".strval(rand(100,999));
        }
        return true;
    }

    /**
     * @param null $login
     * @param null $password
     * @return bool
     */
    public static function createResetPasswordGD($login = null, $password = null)
    {
        if (is_null($login)
            || is_null($password))
            return false;

        $script = "sudo ./changePasswordSkynet.sh '".$login."' '".$password."'";

        try {
            $connection = ssh2_connect(self::POSTFIX_SERVER, self::POSTFIX_PORT);
            if (!$connect = ssh2_auth_password(
                $connection,
                self::POSTFIX_LOGIN,
                self::POSTFIX_PASSW)
            ) return false;

            ssh2_shell($connection, 'xterm');
            if (!ssh2_exec($connection, $script)) return false;

        } catch (Exception $e) {
            Yii::getLogger()->log([
                'resetPasswordGD'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function addCheckLogins()
    {
        if (empty($this->fullName)
            || empty($this->cacheId)
        ) {
            Yii::getLogger()->log([
                'addCheckLogins1'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        //todo проверяем существует ли запись в Logins
        /** @var  $objectUsersLogins Logins*/
        $objectUsersLogins = $this->checkLoginAccount();
        if (!$objectUsersLogins || $this->createNewGS)
        {
            //todo добавляем новую запись в Logins
            if (empty($this->aid)
                || empty($this->loginGS)
                || empty($this->cachePass)
            ) {
                Yii::getLogger()->log([
                    'addCheckLogins2'=>[
                        'Одно из обязательных полей пустое!',
                        ArrayHelper::toArray($this)
                    ]
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }

            $objectUsersLogins = new Logins();

            $this->state = 'new';
            $email = $this->emailAD;

            $loginName = $this->cacheId;
            if (!empty($this->operatorofficestatus)) {
                $loginName .= '.' . $this->operatorofficestatus;
            }
            $loginName .= ": " . $this->fullName;

            if ($this->type == 9) {

                $objectUsersLogins->block_register = date("Y-m-d G:i:s.000", time());
                $loginName = $this->fullName;
                $email = $this->loginGS."@gemosystem.ru";

                if (ActiveSyncHelper::createResetPasswordGD(
                    $this->loginGS,
                    $this->cachePass)
                ) {
                    Yii::getLogger()->log(
                        'Успешно была добавлена почта '.$email,
                        Logger::LEVEL_WARNING,
                        'binary'
                    );
                }
            }

            $objectUsersLogins->aid = $this->aid;
            $objectUsersLogins->Login = $this->loginGS;
            $objectUsersLogins->Pass = $this->cachePass;
            $objectUsersLogins->Name = $loginName;
            $objectUsersLogins->Email = $email;
            $objectUsersLogins->Key = strval($this->cacheId);
            $objectUsersLogins->UserType = $this->type;
            $objectUsersLogins->CACHE_Login = $this->accountName;
            $objectUsersLogins->last_update_password = date("Y-m-d G:i:s:000");
            $objectUsersLogins->Logo = self::LOGO_IMG ;
            $objectUsersLogins->LogoText = self::LOGO_TEXT;
            $objectUsersLogins->tbl = $this->tableName;
            $objectUsersLogins->IsAdmin = 0;
            $objectUsersLogins->IsOperator = 1;
            $objectUsersLogins->LogoText2 = '';
            $objectUsersLogins->LogoType = '';
            $objectUsersLogins->LogoWidth = '';
            $objectUsersLogins->TextPaddingLeft = '';
            $objectUsersLogins->OpenExcel = 0;
            $objectUsersLogins->EngVersion = 0;
            $objectUsersLogins->InputOrder = 1;
            $objectUsersLogins->PriceID = 0;
            $objectUsersLogins->CanRegister = 1;
            $objectUsersLogins->goscontract = 0;
            $objectUsersLogins->FizType = 0;
            $objectUsersLogins->mto_editor = 0;

            $objectUsersLogins->IsDoctor = ($this->department == 8) ? 0 : 2;
            $objectUsersLogins->InputOrderRM = ($this->department == 8) ? 0 : 1;
            $objectUsersLogins->OrderEdit = ($this->department == 8) ? 1 : 0;
            $objectUsersLogins->MedReg = ($this->department == 5) ? 1 : 0;
            $objectUsersLogins->mto = in_array($this->department, [2,3,5,6]) ? 1 : 0;
            $objectUsersLogins->clientmen = in_array($this->department, [3,4,6]) ? 1 : 0;
            $objectUsersLogins->show_preanalytic = in_array($this->department, [4,5]) ? 1 : 0;

            if ($objectUsersLogins->save()) {
                $this->state = 'new';
                Yii::getLogger()->log([
                    'objectUsersLogins->save()' => $objectUsersLogins
                ], Logger::LEVEL_WARNING, 'binary');
            } else {
                Yii::getLogger()->log([
                    'objectUsersLogins->save()' => $objectUsersLogins->errors
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }

        } else {
            $this->state = 'old';

            //todo разблокируем учетную запись
            $this->unblockAccount($objectUsersLogins->aid);
        }

        $this->aid = $objectUsersLogins->aid;
        $this->loginGS = $objectUsersLogins->Login;
        $this->passwordGS = $objectUsersLogins->Pass;
        return $objectUsersLogins;
    }

    /**
     * @return mixed
     */
    public function addNewAdUserAccount()
    {
        if (empty($this->accountName) || empty($this->passwordAD)) {
            Yii::getLogger()->log([
                'addNewAdUserAccount1'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $this->loginAD = "lab\\".$this->accountName;
        $objectUserAccountsAD = NAdUseraccounts::findAdUserAccount($this->loginAD);

        if ($objectUserAccountsAD) {
            Yii::getLogger()->log([
                'addNewAdUserAccount=>objectUserAccountsAD'=>'В NAdUseraccounts уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');

            if ($this->resetPassword) {
                $objectUserAccountsAD->ad_pass = $this->passwordAD;
            }

            if ($objectUserAccountsAD->save()) {
                Yii::getLogger()->log([
                    'objectUserAccountsAD->save()'=>$objectUserAccountsAD
                ], Logger::LEVEL_WARNING, 'binary');
            } else {
                Yii::getLogger()->log([
                    'objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
            return $objectUserAccountsAD;
        }

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->cacheId)
        ) {
            Yii::getLogger()->log([
                'addNewAdUserAccount2'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $objectUserAccountsAD = new NAdUseraccounts();
        $objectUserAccountsAD->last_name = $this->lastName;
        $objectUserAccountsAD->first_name = $this->firstName;
        $objectUserAccountsAD->middle_name = $this->middleName;
        $objectUserAccountsAD->gs_type = $this->typeLO;
        $objectUserAccountsAD->gs_id = strval($this->cacheId);
        $objectUserAccountsAD->gs_position = $this->operatorofficestatus;
        $objectUserAccountsAD->org_name = $this->orgName;
        $objectUserAccountsAD->ad_login = $this->loginAD;
        $objectUserAccountsAD->ad_pass = $this->passwordAD;

        if ($objectUserAccountsAD->save()) {
            Yii::getLogger()->log([
                'objectUserAccountsAD->save()'=>$objectUserAccountsAD
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return $objectUserAccountsAD;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function addNewAdUsers()
    {
        if (empty($this->accountName)) return false;

        if ($findNAdUsers = NAdUsers::findAdAccount($this->accountName)) {

            if (!empty($this->aid)) $findNAdUsers->gs_id = $this->aid;
            if (!empty($this->cacheId)) $findNAdUsers->gs_key = $this->cacheId;
            if (!empty($this->type)) $findNAdUsers->gs_usertype = $this->type;

            if(!$findNAdUsers->save()) {
                Yii::getLogger()->log([
                    'objectUserAD->save()' => $findNAdUsers->errors
                ], Logger::LEVEL_WARNING, 'binary');
            }

            $this->state = 'old';
            $this->idAD = $findNAdUsers->ID;
            Yii::getLogger()->log([
                'addNewAdUsers'=>'В NAdUsers уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->fullName)
            || empty($this->aid)
            || empty($this->cacheId)
        ) {
            Yii::getLogger()->log([
                'addNewAdUsers'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        /** @var $transaction Transaction */
        $transaction = NAdUsers::getDb()->beginTransaction();
        try {
            $objectUserAD = new NAdUsers();
            $objectUserAD->last_name = $this->lastName;
            $objectUserAD->first_name = $this->firstName;
            $objectUserAD->middle_name = $this->middleName;
            $objectUserAD->AD_name = $this->fullName;
            $objectUserAD->AD_position = $this->operatorofficestatus;
            $objectUserAD->AD_email = $this->emailAD;
            $objectUserAD->gs_email = $this->emailAD;
            $objectUserAD->gs_id = $this->aid;
            $objectUserAD->gs_key = strval($this->cacheId);
            $objectUserAD->gs_usertype = $this->type;
            $objectUserAD->AD_login = $this->accountName;
            $objectUserAD->allow_gs = 1;
            $objectUserAD->active = 1;
            $objectUserAD->AD_active = 1;
            $objectUserAD->auth_ldap_only = ($this->department == 2) ? 0 : 1;

            if ($objectUserAD->save()) {
                $transaction->commit();
                $this->state = 'new';
                $this->idAD = $transaction->db->getLastInsertID();
                $this->loginAD = $this->accountName;
                Yii::getLogger()->log([
                    'objectUserAD->save()' => $objectUserAD
                ], Logger::LEVEL_WARNING, 'binary');
            } else {
                $transaction->rollBack();
                Yii::getLogger()->log([
                    'objectUserAD->save()'=>$objectUserAD->errors
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return bool
     */
    public function addDoctorConsultant()
    {
        if (empty($this->cacheId)
            || empty($this->firstName)
            || empty($this->lastName)
            || empty($this->loginGS)
        ) {
            Yii::getLogger()->log([
                'addDoctorConsultant'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        if (NSprDoctorConsultant::findOne(['id' => strval($this->cacheId)])) {
            Yii::getLogger()->log([
                'addDoctorConsultant'=>'В NSprDoctorConsultant уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        $objectDoctorConsultant = new NSprDoctorConsultant();
        $objectDoctorConsultant->id = strval($this->cacheId);
        $objectDoctorConsultant->name = $this->firstName;
        $objectDoctorConsultant->surname = $this->lastName;
        $objectDoctorConsultant->patronymic = $this->middleName;
        $objectDoctorConsultant->active = strval(1);
        $objectDoctorConsultant->post_id = strval(3);
        $objectDoctorConsultant->post_name = '3.Врач';
        $objectDoctorConsultant->login = $this->loginGS;

        if ($objectDoctorConsultant->save()) {
            Yii::getLogger()->log([
                'objectDoctorConsultant->save()' => $objectDoctorConsultant
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectDoctorConsultant->save()'=>$objectDoctorConsultant->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
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
            Yii::getLogger()->log([
                'objectDirectorFloSender->save()' => $objectDirectorFloSender
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectDirectorFloSender->save()'=>$objectDirectorFloSender->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function addCheckDirectorFlo()
    {
        if (empty($this->firstName)
            || empty($this->lastName)
            || empty($this->phone)
            || empty($this->loginGS)
            || empty($this->cachePass)
        ) {
            Yii::getLogger()->log([
                'addCheckDirectorFlo' => [
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $objectDirectorFlo = DirectorFlo::findOne([
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
        ]);

        if (!$objectDirectorFlo) {

            /** @var $transaction Transaction */
            $transaction = DirectorFlo::getDb()->beginTransaction();
            try {
                $objectDirectorFlo = new DirectorFlo();
                $objectDirectorFlo->first_name = $this->firstName;
                $objectDirectorFlo->middle_name = $this->middleName;
                $objectDirectorFlo->last_name = $this->lastName;
                $objectDirectorFlo->phoneNumber = $this->phone;
                $objectDirectorFlo->email = $this->emailGD;
                $objectDirectorFlo->passReplaced = 0;
                $objectDirectorFlo->login = $this->loginGS;
                $objectDirectorFlo->password = $this->cachePass;

                if ($objectDirectorFlo->save()) {
                    $transaction->commit();
                    $this->state = 'new';
                    $this->directorID = $transaction->db->getLastInsertID();
                    Yii::getLogger()->log([
                        'objectDirectorFlo->save()' => $objectDirectorFlo
                    ], Logger::LEVEL_WARNING, 'binary');
                } else {
                    $transaction->rollBack();
                    Yii::getLogger()->log([
                        'objectDirectorFlo->save()' => $objectDirectorFlo->errors
                    ], Logger::LEVEL_ERROR, 'binary');
                    return false;
                }

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }

        } else {
            $this->state = 'old';
        }

        Yii::getLogger()->log([
            'addCheckDirectorFlo->state' => $this->state
        ], Logger::LEVEL_WARNING, 'binary');

        $this->directorID = $objectDirectorFlo->id;
        $this->aid = 8000000 + strval($this->directorID);
        return true;
    }

    /**
     * @return bool
     */
    public function addLpassUsers()
    {
        if (empty($this->cacheId)
            || empty($this->type)
            || empty($this->loginGS)
            || empty($this->cachePass)
        ) {
            Yii::getLogger()->log([
                'addLpassUsers'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        if (LpASs::findOne([
            'ukey' => strval($this->cacheId),
            'utype' => $this->type
            ]))
        {
            Yii::getLogger()->log([
                'addLpassUsers'=>'В LpASs уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        $objectLpASs = new LpASs();
        $objectLpASs->ukey = strval($this->cacheId);
        $objectLpASs->utype = strval($this->type);
        $objectLpASs->login = $this->loginGS;
        $objectLpASs->pass = $this->cachePass;
        $objectLpASs->dateins = date("Y-m-d G:i:s:000");
        $objectLpASs->iukey = strval(0);
        $objectLpASs->iutype = strval(0);
        $objectLpASs->active = strval(1);

        if ($objectLpASs->save()) {
            Yii::getLogger()->log([
                'objectLpASs->save()' => $objectLpASs
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectLpASs->save()'=>$objectLpASs->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function addCheckNNurse()
    {
        if (empty($this->firstName)
            || empty($this->lastName)
        ) {
            Yii::getLogger()->log([
                'addCheckNNurse'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        if (NNurse::findOne([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName
        ])) {
            Yii::getLogger()->log([
                'addCheckNNurse'=>'В NNurse уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        $objectNurse = new NNurse();
        $objectNurse->first_name = $this->firstName;
        $objectNurse->last_name = $this->lastName;
        $objectNurse->middle_name = $this->middleName;
        $objectNurse->active = 1;
        $objectNurse->save();

        if ($objectNurse->save()) {
            Yii::getLogger()->log([
                'objectNurse->save()' => $objectNurse
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectNurse->save()'=>$objectNurse->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function addCheckErpUsers()
    {
        if (empty($this->loginGS)
            || empty($this->loginAD)
            || empty($this->fullName)
        ) {
            Yii::getLogger()->log([
                'addCheckErpUsers'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        if (ErpUsers::findOne(['login' => $this->loginAD,])){
            Yii::getLogger()->log([
                'addCheckErpUsers'=>'В ErpUsers уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        $objectErpUsers = new ErpUsers();
        $objectErpUsers->group_id = 11;
        $objectErpUsers->name = $this->fullName;
        $objectErpUsers->login = $this->loginAD;
        $objectErpUsers->skynet_login = $this->loginGS;
        $objectErpUsers->password = 'd9b1d7db4cd6e70935368a1efb10e377';
        $objectErpUsers->status = 1;
        $objectErpUsers->save();

        if ($objectErpUsers->save()) {
            Yii::getLogger()->log([
                'objectErpUsers->save()' => $objectErpUsers
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectErpUsers->save()'=>$objectErpUsers->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function addCheckCounterparty()
    {
        if (empty($this->aid)
        ) {
            Yii::getLogger()->log([
                'addCheckCounterparty'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        if (medUserCounterparty::findOne(['user_id' => $this->aid])){
            Yii::getLogger()->log([
                'addCheckCounterparty'=>'В medUserCounterparty уже есть запись!'
            ], Logger::LEVEL_WARNING, 'binary');
            return true;
        }

        $this->type == 9 ? $counterparty_id = $this->key : $counterparty_id = 1;
        $objectUserCounterparty = new medUserCounterparty();
        $objectUserCounterparty->user_id = $this->aid;
        $objectUserCounterparty->counterparty_id = $counterparty_id;

        if ($objectUserCounterparty->save()) {
            Yii::getLogger()->log([
                'objectUserCounterparty->save()' => $objectUserCounterparty
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectUserCounterparty->save()'=>$objectUserCounterparty->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function addCheckErpNurses()
    {
        if (empty($this->loginGS)
            || empty($this->loginAD)
            || empty($this->fullName)
        ) {
            Yii::getLogger()->log([
                'addCheckErpNurses'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $nurseId = ErpUsers::find()
            ->select('id')
            ->where(['group_id' => 11])
            ->orderBy('id DESC')
            ->one();

        $objectErpNurses = new ErpNurses();
        $objectErpNurses->user_id = $nurseId;
        $objectErpNurses->nurse_email = $this->emailAD;
        $objectErpNurses->nurse_phone = '';
        $objectErpNurses->nurse_key = $this->key;

        if ($objectErpNurses->save()) {
            Yii::getLogger()->log([
                'objectErpNurses->save()' => $objectErpNurses
            ], Logger::LEVEL_WARNING, 'binary');
        } else {
            Yii::getLogger()->log([
                'objectErpNurses->save()'=>$objectErpNurses->errors
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function setDoctorObjectParams() {

        if (empty($this->key) || empty($this->specId)) {
            Yii::getLogger()->log([
                'setDoctorObjectParams'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
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
        if (count($expName) == 2) $this->middleName = $expName[1];
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
    private function addDepartmentRules()
    {
        //todo если call-центр, клиент-меджер, выездная медсестра
        if (in_array($this->department, [0, 1, 6])) {
            //todo добавляем в модуль выездного обслуживания
            if (!$this->addCheckErpUsers()) return false;
        }

        //todo выездная медсетра
        if (!in_array($this->department, [0])
            && $this->nurse == 1) {
            if (!$this->addCheckErpNurses()) return false;
        }
        return true;
    }

    /**
     * @return bool
     *
     */
    public function addUserAdTables()
    {
        //todo проверяем запись в Logins->adUsers
        /** @var $addNewAdUsers boolean */
        $addNewAdUsers = $this->addNewAdUsers();

        //todo проверяем запись в Logins->adUserAccounts
        /** @var $addNewAdUserAccount NAdUseraccounts */
        $addNewAdUserAccount = $this->addNewAdUserAccount();

        //todo проверяем записи в таблицах AD
        if (!$addNewAdUsers || !$addNewAdUserAccount) {
            $message = '<p>Не удалось создать/изменить УЗ для <b>' . $this->fullName . '</b> в таблице AD</p>';
            Yii::$app->session->setFlash('error', $message);
            return false;
        }

        $this->loginAD = $addNewAdUserAccount->ad_login;
        $this->passwordAD = $addNewAdUserAccount->ad_pass;

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
                $newPasswordAd = $this->resetPasswordAD($this->accountName);
                if ($newPasswordAd) {
                    Yii::getLogger()->log([
                        'resetPasswordAD' => 'Пароль успешно изменен для ' . $this->accountName
                    ], Logger::LEVEL_WARNING, 'binary');

                    $this->passwordAD = $newPasswordAd;
                    $message = '<p>Для пользователя <b>' . $this->fullName . '</b> был изменен пароль для входа в Windows!</p>';
                    $message .= '<p>Данные для входа в Windows:<p>';
                    $message .= '<br>Логин: <b>' . $this->accountName . '</b>';
                    $message .= '<br>Пароль: <b>' . $this->passwordAD . '</b>';
                    Yii::$app->session->setFlash('warning', $message);
                }
            }
        } else {
            //todo если нет УЗ в AD - создаем
            if (!$this->addUserAD()) {
                $message = '<p>Не удалось создать УЗ для <b>' . $this->fullName . '</b> в AD</p>';
                Yii::$app->session->setFlash('error', $message);
                return false;
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
            Yii::getLogger()->log([
                'ActiveSyncController=>addPermissions'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $connection = 'GemoTestDB';
        $db = Yii::$app->$connection;
        /** @var $transaction Transaction */
        $transaction = $db->beginTransaction();

        try {
            //todo удаляем все роли у пользователя
            $db->createCommand()->delete(
                NAuthASsignment::tableName(),
                ['userid' => $this->aid]
            )->execute();

            //todo присвоение прав пользователю
            $findPermissions = Permissions::findAll([
                'department' => $this->department
            ]);

            if ($findPermissions) {
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
            Yii::getLogger()->log([
                'DirectorFloSender->batchInsert' => $e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function addUserAD()
    {
        //todo проверяем существует и подобный логин в AD
        if (empty($this->lastName
            || empty($this->firstName))
            || empty($this->fullName)
        ){
            Yii::getLogger()->log([
                'addUserAD'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }

        $this->cnName = $this->fullName;
        $this->displayName = $this->fullName;

        if ($this->type == 8 && !empty($this->key)) {
            $this->cnName = $this->fullName.' '.$this->key;
            $this->accountName = $this->key;
            $this->accountName .= ".".substr($this->translit($this->firstName),0,1);
            $this->accountName .= ".".$this->translit($this->lastName);
        } else {
            $this->accountName .= $this->translit($this->firstName . "." . $this->lastName);
        }

        if (!empty($this->operatorofficestatus)) {
            $this->displayName = $this->fullName . " (" . $this->operatorofficestatus . ")";
        }

        if ($this->checkUserAccountAd())
        {
            //todo если есть, то добавляю отчество в присвоеный логин в AD
            $this->accountName = $this->translit($this->firstName);
            if (!empty($this->middleName)) {
                $this->accountName .= "." . substr($this->translit($this->middleName), 0, 1);
            } else $this->accountName .= "1";
            $this->accountName .= ".".$this->translit($this->lastName);

            if ($this->typeLO != 'FLO' && !empty($this->operatorofficestatus)) {
                $this->cnName = $this->fullName." (".$this->operatorofficestatus.")";
            }
        }

        //todo создаем нового пользователя в AD
        $arrAccountAD = $this->addNewUserAd();

        if (!$arrAccountAD) {
            Yii::getLogger()->log([
                'addUserAD=>arrAccountAD'=>'Не удалось добавить учетную запись в AD'
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        else {
            Yii::getLogger()->log([
                'addUserAD=>arrAccountAD'=>$arrAccountAD
            ], Logger::LEVEL_WARNING, 'binary');
            $this->accountName = $arrAccountAD['SamAccountName'];
            $this->emailAD = $arrAccountAD['UserPrincipalName'];
            $this->passwordAD = $arrAccountAD['AccountPassword'];
        }
        return true;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function checkLoginAccount()
    {
        return $loginSearch = Logins::find()
            ->andFilterWhere([
                'OR',
                ['like', 'Name', $this->fullName],
                ['aid' => $this->aid]
            ])
            ->andFilterWhere(['UserType' => $this->type])
            ->all();
    }

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
                Yii::getLogger()->log([
                    'addFromDonor->batchInsert' => $e->getMessage()
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }

        }
        return true;
    }

    /**
     * @return boolean
     */
    public function checkFranchazyAccount()
    {
        if (empty($this->key)
        ) {
            Yii::getLogger()->log([
                'checkFranchazyAccount'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_WARNING, 'binary');
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
     * @return mixed
     */
    public function checkOperatorAccount()
    {
        if (empty($this->firstName) ||
            empty($this->lastName)
        ) {
            Yii::getLogger()->log([
                'checkOperatorAccount'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_WARNING, 'binary');
            return false;
        }

        $name = $this->firstName;
        if (!empty($this->middleName))
            $name .= " ".$this->middleName;

        /** @var  $objectOperators Operators */
        $objectOperators = Operators::find()->where([
            'Name' => $name,
            'LastName' => $this->lastName
        ])->one();

        if (empty($objectOperators->CACHE_OperatorID))
            return false;

        $findLogin = Logins::findOne([
            'Key' => $objectOperators->CACHE_OperatorID,
            'UserType' => $this->type
        ]);

        if (!$findLogin) return false;
        else return $objectOperators;
    }

    /**
     * @return mixed
     */
    public function addFranchazyUser()
    {
        if (empty($this->fullName) ||
            empty($this->key)
        ) {
            Yii::getLogger()->log([
                'addFranchazyUser'=>[
                    'Одно из обязательных полей пустое!',
                    ArrayHelper::toArray($this)
                ]
            ], Logger::LEVEL_WARNING, 'binary');
            return false;
        }

        /** @var Logins $loginSearch */
        $loginSearch = Logins::find()
            ->andFilterWhere(['like', 'Name', $this->fullName])
            ->andFilterWhere(['Key' => $this->key])
            ->andFilterWhere(['UserType' => 8])
            ->one();

        if (!$loginSearch) return false;

        return [
            $this->aid => $loginSearch->aid,
            $this->loginGS => $loginSearch->Login,
            $this->passwordGS => $loginSearch->Pass,
            $this->state => 'old'
        ];
    }

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

    private function setCachePass()
    {
        $this->cachePass = Yii::$app->getSecurity()->generateRandomString(8);
    }

    /**
     * @param $accountName
     * @return bool|string
     */
    public function resetPasswordAD($accountName)
    {
        $newPassword = self::generatePasswordAD();
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
            Yii::getLogger()->log([
                'ActiveSyncController'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
    }

    /**
     * @return array|bool
     */
    public function addNewUserAd()
    {
        $password = self::generatePasswordAD();
        $email = $this->accountName."@gemotest.ru";
        $name = $this->firstName;
        if (!empty($this->middleName))
            $name .= " ".$this->middleName;

        $ldaprecord = [
            "CN" => $this->cnName,
            "name" => $this->cnName,
            "sn" => $this->lastName, //фамилия
            "givenname" => $name, //имя отчество
            "sAMAccountName" => $this->accountName, //логин
            "userPrincipalName" => $email, //емаил
            "mail" => $email, //емаил
            "objectClass" => "user",
            "displayname" => $this->displayName, //ФИО
            "unicodepwd" => iconv("UTF-8", "UTF-16LE", '"' . $password . '"'),
            "userAccountControl" => "544" //доступ
        ];

        Yii::getLogger()->log([
            'addNewUserAd->$ldaprecord'=>$ldaprecord
        ], Logger::LEVEL_WARNING, 'binary');

        try {

            $ADgroup = "CN=".$ldaprecord["CN"].",OU=".$this->typeLO." Users,OU=SSO,OU=gUsers,DC=lab,DC=gemotest,DC=ru";
            $ldapconn = ldap_connect(self::LDAP_URL, self::LDAP_PORT);
            if (!$ldapconn) return false;

            ldap_set_option($ldapconn, LDAP_OPT_DEBUG_LEVEL, 7);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);
            if (!$ldapbind) return false;

            $output = ldap_add($ldapconn, $ADgroup, $ldaprecord);
            ldap_close($ldapconn);

            if ($output) {
                Yii::getLogger()->log([
                    'addNewUserAd'=>'В AD успешно добавлена УЗ '.$this->accountName
                ], Logger::LEVEL_WARNING, 'binary');
                return [
                    'SamAccountName' => $this->accountName,
                    'UserPrincipalName' => $email,
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
            Yii::getLogger()->log([
                'ActiveSyncController'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
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
        $justthese = array("displayname", "samaccountname", "userprincipalname", 'cn', 'name');

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

            if (!$info || $info['count'] == 0)  {
                Yii::getLogger()->log([
                    'checkUserNameAd'=>$this->fullName.' '.'не найден в AD'
                ], Logger::LEVEL_WARNING, 'binary');
                return false;
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

            Yii::getLogger()->log([
                'checkUserNameAd=>arrAccounts'=>$arrAccounts
            ], Logger::LEVEL_WARNING, 'binary');

        } catch (Exception $e) {
            Yii::getLogger()->log([
                'checkUserNameAd'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
        return empty($arrAccounts) ? false : $arrAccounts;
    }

    /**
     * @return bool
     */
    public function checkUserAccountAd()
    {
        //todo проверяем на совпадение УЗ AD
        $ADcheckUser = "(samaccountname=*".$this->accountName."*)";
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

            //todo проверяем существует ли запись по лигину в AD
            if (!$info || $info['count'] == 0) {
                Yii::getLogger()->log([
                    'checkUserAccountAd'=>$this->accountName.' '.'не найдена в AD!'
                ], Logger::LEVEL_WARNING, 'binary');
                return false;
            } else {
                Yii::getLogger()->log([
                    'checkUserAccountAd'=>$this->accountName.' '.'найдена в AD!'
                ], Logger::LEVEL_WARNING, 'binary');
                return true;
            }

        } catch (Exception $e) {
            Yii::getLogger()->log([
                'checkUserAccountAd'=>$e->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
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