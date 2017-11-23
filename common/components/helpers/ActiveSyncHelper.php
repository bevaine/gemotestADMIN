<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 12:14
 */

namespace common\components\helpers;

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
 * @property string  $key
 * @property string  $typeLO
 * @property string  $state
 * @property string  $displayName
 * @property string  $cnName
 * @property string  $specId
 */

class ActiveSyncHelper
{
    CONST LDAP_SERVER = '192.168.108.3';
    CONST LDAP_LOGIN = 'dymchenko.adm@lab.gemotest.ru';
    CONST LDAP_PASSW = '2Hszfaussw';
    CONST LDAP_DN = "DC=lab,DC=gemotest,DC=ru";
    CONST LOGO_TEXT = '107031 Москва, Рождественский бульвар д.21, ст.2^*^тел. (495) 532-13-13, 8(800) 550-13-13^*^www.gemotest.ru';
    CONST LOGO_IMG = 'logos/LogoGemotest.gif';

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
    public $key;
    public $typeLO;
    public $state;
    public $displayName;
    public $cnName;
    public $specId;

    /**
    * @return array|null|\yii\db\ActiveRecord
    */
    public function checkLoginAccount()
    {
        return $loginSearch = Logins::find()
            ->andFilterWhere(['like', 'Name', $this->fullName])
            ->andFilterWhere(['UserType' => $this->type])
            ->one();
    }

    /**
     * @return boolean
     */
    public function checkFranchazyAccount()
    {
        if (empty($this->key)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'addCheckOperators: Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        /** @var $loginSearch Logins */
        $loginSearch = Logins::find()
            ->andFilterWhere(['Key' => $this->key])
            ->andFilterWhere(['UserType' => 8])
            ->one();

        if ($loginSearch) {
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
        /** @var  $objectOperators Operators */
        $objectOperators = Operators::find()->where([
            'Name' => $this->firstName." ".$this->middleName,
            'LastName' => $this->lastName
        ])->one();

        if (!empty($objectOperators->CACHE_OperatorID)) {
            $findLogin = Logins::findOne([
                'Key' => $objectOperators->CACHE_OperatorID,
                'UserType' => $this->type
            ]);
            if ($findLogin) return $objectOperators;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function addFranchazyUser()
    {
        /**
         * @var Logins $loginSearch
         */
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

    public function setLoginDoctor()
    {
        $this->aid = 1000000 + strval($this->key);
        $this->loginGS = 'reg'.$this->key;
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
            || empty($this->middleName)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'addCheckOperators: Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        //todo проверяем существует ли запись в Operators
        if (!$objectOperators = $this->checkOperatorAccount()) {

            $this->setCachePass();

            $this->setLastOperatorCacheId();

            //todo если нет, то добавление в Operators
            $objectOperators = new Operators();
            $objectOperators->CACHE_Login = $this->accountName;
            $objectOperators->Name = $this->firstName." ".$this->middleName;
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

            if (!$objectOperators->save()) {
                Yii::getLogger()->log([
                    '$objectOperators->save()'=>$objectOperators->errors
                ], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log([
                    '$objectOperators->save()'=>$objectOperators
                ], 1, 'binary');
            }
        }

        $loginId = $objectOperators->AID;
        $this->aid = 3000000 + $loginId;
        $this->cacheId = $objectOperators->CACHE_OperatorID;
        if ($this->department == 5) $this->loginGS = 'medr'.$loginId;
        else $this->loginGS = 'reg'.$loginId;
        return true;
    }

    private function setLastOperatorCacheId() {
        /** @var  $cacheId Operators */
        $cacheId = Operators::find()
            ->select('CACHE_OperatorID')
            ->orderBy('AID DESC')
            ->one();
        $this->cacheId = strval($cacheId->CACHE_OperatorID) + 1;
    }

    private function setCachePass() {
        $this->cachePass = Yii::$app->getSecurity()->generateRandomString(8);
    }

    /**
     * @return mixed
     */
    public function addCheckLogins()
    {
        if (empty($this->fullName) || empty($this->cacheId)) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'addCheckLogins: Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        //todo проверяем существует ли запись в Logins
        /** @var  $objectUsersLogins Logins*/
        if ($objectUsersLogins = $this->checkLoginAccount()) {
            $this->state = 'old';

            //todo разблокируем учетную запись
            $this->unblockAccount($objectUsersLogins->aid);

        } else {
            //todo добавляем новую запись в Logins
            if (empty($this->aid)
                || empty($this->loginGS)
                || empty($this->cacheId)
                || empty($this->cachePass)
            ) {
                Yii::getLogger()->log([
                    'ActiveSyncController=>addUserAD'=>'addCheckLogins: Одно из обязательных полей пустое!'
                ], 1, 'binary');
                return false;
            }

            $this->state = 'new';
            $loginName = $this->cacheId;
            if (!empty($this->operatorofficestatus)) $loginName .= '.' . $this->operatorofficestatus;
            $loginName .= "." . $this->fullName;

            $objectUsersLogins = new Logins();
            $objectUsersLogins->aid = $this->aid;
            $objectUsersLogins->Login = $this->loginGS;
            $objectUsersLogins->Pass = $this->cachePass;
            $objectUsersLogins->Name = $loginName;
            $objectUsersLogins->Email = $this->emailAD;
            $objectUsersLogins->Key = strval($this->cacheId);
            $objectUsersLogins->UserType = $this->type;
            $objectUsersLogins->CACHE_Login = $this->accountName;
            $objectUsersLogins->last_update_password = date("Y-m-d G:i:s:000");
            $objectUsersLogins->Logo = self::LOGO_IMG ;
            $objectUsersLogins->LogoText = self::LOGO_TEXT;
            $objectUsersLogins->tbl = 'Operators';
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

            if (!$objectUsersLogins->save()) {
                Yii::getLogger()->log([
                    '$objectUsersLogins->save()' => $objectUsersLogins->errors
                ], 1, 'binary');
                return false;
            } else {
                $this->state = 'new';
                Yii::getLogger()->log([
                    '$objectUsersLogins->save()' => $objectUsersLogins
                ], 1, 'binary');
            }
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
                'ActiveSyncController=>addUserAD'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        $this->loginAD = "lab\\".$this->accountName;
        $objectUserAccountsAD = NAdUseraccounts::findAdUserAccount($this->loginAD);

        if ($objectUserAccountsAD) {
            $objectUserAccountsAD->ad_pass = $this->passwordAD;
            if (!$objectUserAccountsAD->save()) {
                Yii::getLogger()->log([
                    '$objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors
                ], 1, 'binary');
                return false;
            }
            return $objectUserAccountsAD;
        }

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->middleName)
            || empty($this->cacheId)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'addNewAdUserAccount: Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        $objectUserAccountsAD = new NAdUseraccounts();
        $objectUserAccountsAD->last_name = $this->lastName;
        $objectUserAccountsAD->first_name = $this->firstName;
        $objectUserAccountsAD->middle_name = $this->middleName;
        $objectUserAccountsAD->gs_type = $this->typeLO;
        $objectUserAccountsAD->gs_id = strval($this->cacheId);
        $objectUserAccountsAD->gs_position = $this->operatorofficestatus;
        $objectUserAccountsAD->org_name = 'ООО "Лаборатория Гемотест"';
        $objectUserAccountsAD->ad_login = $this->loginAD;
        $objectUserAccountsAD->ad_pass = $this->passwordAD;

        if (!$objectUserAccountsAD->save()) {
            Yii::getLogger()->log([
                '$objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors
            ], 1, 'binary');
            return false;
        }
        return $objectUserAccountsAD;
    }

    /**
     * @return mixed
     */
    public function addNewAdUsers()
    {
        if (empty($this->accountName)) return false;

        if (NAdUsers::findAdAccount($this->accountName)) {
            $this->state = 'old';
            return true;
        }

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->middleName)
            || empty($this->fullName)
            || empty($this->aid)
            || empty($this->cacheId)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'addNewAdUsers: Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

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
            $this->loginAD = $this->accountName;
            $this->state = 'new';
            return true;
        } else {
            Yii::getLogger()->log([
                '$objectUserAD->save()'=>$objectUserAD->errors
            ], 1, 'binary');
            return false;
        }
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
                'ActiveSyncController=>addLpassUsers'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        if (LpASs::findOne([
            'ukey' => strval($this->cacheId),
            'utype' => $this->type
            ])) return true;

        $objectLpASs = new LpASs();
        $objectLpASs->ukey = strval($this->cacheId);
        $objectLpASs->utype = strval($this->type);
        $objectLpASs->login = $this->loginGS;
        $objectLpASs->pass = $this->cachePass;
        $objectLpASs->dateins = date("Y-m-d G:i:s:000");
        $objectLpASs->iukey = strval(0);
        $objectLpASs->iutype = strval(0);
        $objectLpASs->active = strval(1);

        if (!$objectLpASs->save()) {
            Yii::getLogger()->log([
                'ActiveSyncController:objectLpASs'=>$objectLpASs->errors
            ], 1, 'binary');
            return false;
        } else {
            Yii::getLogger()->log([
                '$objectLpASs->save()' => $objectLpASs
            ], 1, 'binary');
        }

        return true;
    }

    private function setDoctorObjectParams() {

        if (empty($this->key) || empty($this->specId)) {
            Yii::getLogger()->log([
                'ActiveSyncController=>setDoctorObjectParams'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
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
        if (!empty($expName[1])) $this->middleName = $expName[1];
        $this->fullName = $this->lastName . " " . $this->firstName . " " . $this->middleName;

        if (array_key_exists($this->specId, SprDoctorSpec::getKeysList())) {
            $this->operatorofficestatus = SprDoctorSpec::getKeysList()[$this->specId];
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function checkAccount()
    {
        /**
         * @var Logins $findUserLogin
         */
        //print_r($this);
        //exit;
        if ($this->type == 5) {
            //todo если доктор-консультант

            //todo генеруруем/задаем пароль для входа в GS
            $this->setCachePass();

            //todo присваиваем значения переменным
            if (!$this->setDoctorObjectParams()) return false;

            //todo добавляем/сбрасываем пароль для УЗ AD
            if (!$this->createAdUserAcc()) return false;

            //todo проверяем/добавляем запись в Logins
            if (!$this->addCheckLogins()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addUserAdTables()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addLpassUsers()) return false;

            //todo добавление ролей для пользователя
            if (!$this->addPermissions()) return false;

            if (!empty($this->aid) &&
                !empty($this->loginAD) &&
                !empty($this->passwordAD)
            ){
                return [
                    'aid' => $this->aid,
                    'login' => $this->loginAD,
                    'password' => $this->passwordAD,
                    'state' => $this->state,
                ];
            }
        } elseif ($this->type == 8) {
            //todo если франчайзи (только проверяем запись в Logins)

            //todo добавляем/сбрасываем пароль для УЗ AD
            if (!$this->createAdUserAcc()) return false;

            //todo проверяем есть ли УЗ у франчайзи
            if (!$this->checkFranchazyAccount()) return false;

            //todo проверяем/добавляем запись в таблицы AD
            if (!$this->addUserAdTables()) return false;

            if (!empty($this->aid) &&
                !empty($this->loginAD) &&
                !empty($this->passwordAD)
            ){
                return [
                    'aid' => $this->aid,
                    'login' => $this->loginAD,
                    'password' => $this->passwordAD,
                    'state' => $this->state,
                ];
            }
        } elseif ($this->type == 7) {

            if (in_array($this->department, [21, 22])) $this->department = 2;
            if (in_array($this->department, [31, 32, 33])) $this->department = 3;
            //if ($this->department == 0) $this->nurse = 1;

            if (in_array($this->department, [0, 1, 2, 3, 6, 7, 8])) {
                //todo если Администр./Врач консул./Собств. лаб. отд./Ген. директор (проверяем/создаем в Logins и adUsers)

                //todo добавляем/сбрасываем пароль для УЗ AD
                if (!$this->createAdUserAcc()) return false;

                //todo проверяем/добавляем запись в Operators
                if (!$this->addCheckOperators()) return false;

                //todo проверяем/добавляем запись в Logins
                if (!$this->addCheckLogins()) return false;

                //todo проверяем/добавляем запись в таблицы AD
                if (!$this->addUserAdTables()) return false;

                //todo добавление пользователя в другие таблицы в соотвествии с ролью
                if (!$this->addDepartmentRules()) return false;

                //todo добавление ролей для пользователя
                if (!$this->addPermissions()) return false;

                if (!empty($this->aid) &&
                    !empty($this->loginAD) &&
                    !empty($this->passwordAD)
                ) {
                    return [
                        'aid' => $this->aid,
                        'login' => $this->loginAD,
                        'password' => $this->passwordAD,
                        'state' => $this->state,
                    ];
                }

            } elseif (in_array($this->department, [4, 5])) {
                //todo если Отдел клиентской информационной поддержки/Мед регистратор

                //todo проверяем/добавляем запись в Operators
                if (!$this->addCheckOperators()) return false;

                //todo проверяем/добавляем запись в Logins
                if (!$this->addCheckLogins()) return false;

                //todo проверяем/добавляем запись в lpASs
                if (!$this->addLpassUsers()) return false;

                //todo добавление пользователя в другие таблицы в соотвествии с ролью
                if (!$this->addDepartmentRules()) return false;

                //todo добавление ролей для пользователя
                if (!$this->addPermissions()) return false;

                if (!empty($this->aid) &&
                    !empty($this->loginGS) &&
                    !empty($this->passwordGS)
                ) {
                    return [
                        'aid' => $this->aid,
                        'login' => $this->loginGS,
                        'password' => $this->passwordGS,
                        'state' => $this->state,
                    ];
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function addDepartmentRules()
    {
        if (!in_array($this->department, [1, 2, 3, 5, 6])) {

            if (in_array($this->nurse, [1, 2])) {
                if (!$this->addCheckNNurse()) return false;
            }

            if ($this->nurse == 2) {
                if (!$this->addCheckErpUsers()) return false;
                if (!$this->addCheckErpNurses()) return false;
            }
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
            $newPasswordAd = $this->resetPasswordAD($this->accountName);
            if ($newPasswordAd) {
                $this->passwordAD = $newPasswordAd;
                $message = '<p>Изменен пароль УЗ для <b>' . $this->fullName . '</b> в AD </p>';
                Yii::$app->session->setFlash('warning', $message);
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
    private function addCheckNNurse()
    {
        if (empty($this->firstName)
            || empty($this->lastName)
            || empty($this->middleName)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addCheckNNurse'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        if (NNurse::findOne([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName
        ])) return true;

        $objectNurse = new NNurse();
        $objectNurse->first_name = $this->firstName;
        $objectNurse->last_name = $this->lastName;
        $objectNurse->middle_name = $this->middleName;
        $objectNurse->active = 1;
        $objectNurse->save();

        if (!$objectNurse->save()) {
            Yii::getLogger()->log([
                'ActiveSyncController:objectNurse'=>$objectNurse->errors
            ], 1, 'binary');
            return false;
        } else {
            Yii::getLogger()->log([
                '$objectNurse->save()' => $objectNurse
            ], 1, 'binary');
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
                'ActiveSyncController=>addCheckErpUsers'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        if (ErpUsers::findOne(['login' => $this->loginAD,])) return true;

        $objectErpUsers = new ErpUsers();
        $objectErpUsers->group_id = 11;
        $objectErpUsers->name = $this->fullName;
        $objectErpUsers->login = $this->loginAD;
        $objectErpUsers->skynet_login = $this->loginGS;
        $objectErpUsers->password = 'd9b1d7db4cd6e70935368a1efb10e377';
        $objectErpUsers->status = 1;
        $objectErpUsers->save();

        if (!$objectErpUsers->save()) {
            Yii::getLogger()->log([
                'ActiveSyncController:objectErpUsers'=>$objectErpUsers->errors
            ], 1, 'binary');
            return false;
        } else {
            Yii::getLogger()->log([
                '$objectErpUsers->save()' => $objectErpUsers
            ], 1, 'binary');
        }
        return true;
    }

    public function addCheckErpNurses()
    {
        if (empty($this->loginGS)
            || empty($this->loginAD)
            || empty($this->fullName)
        ) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addCheckErpUsers'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
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

        if (!$objectErpNurses->save()) {
            Yii::getLogger()->log([
                'ActiveSyncController:objectErpNurses'=>$objectErpNurses->errors
            ], 1, 'binary');
            return false;
        } else {
            Yii::getLogger()->log([
                '$objectErpNurses->save()' => $objectErpNurses
            ], 1, 'binary');
        }
        return true;
    }

    /**
     * @return bool
     */
    private function addPermissions()
    {
        /* $nurse
         * 0 - нет,
         * 1 - да,
         * 2 - выездная мс

         * $department
         * 0 - CЛО
         * 1 - Контакт центр
         * 2 - Продажи
         * 3 - Развитие\ДУОЛО\Фин сопровождение логоворов\бухгалтерия\
         * 4 - ОКИП
         * 5 - мед регистратор
         * 6 - клиент менеджер
         * 7 - без прав
        */

        if (empty($this->aid)) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addPermissions'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        $permissions = [
            '7' => [], //todo без прав
            '0' => ['mis','workshift.allow','MisManager','Operator','Registrar','Report.Workshift.Kkm','SkynetEstimationOrder'],//Cобственные отделения'
            '1' => ['admin','Administrator.Callcenter.index','mis','MisManager','Operator','Registrar','SkynetEstimationOrder'],//Контакт центр
            '2' => ['Operator','Registrar'],//Продажи
            '3' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],//Развитие
            '4' => ['admin','ClientManager','finance_manager','management_all_offices','Operator','Registrar','Report.Inoe','ReportOrders.Contingents','SkynetEstimationOrder'],//Отдел клиентской инф. поддержки
            '5' => ['Operator','ClientManager','MedRegistrar','Report.Inoe','PreanalyticaManager'],//Мед регистратор
            '6' => ['admin','Administrator.Callcenter.index','bonuses_view','cancelBm_view','ClientManager','discount_all_rights','kurs_view','mis','MisManager','Operator','Registrar','Report.MsZabor','Report.PollPatients','Report.Rep41','ReportOrders.Detail','ReportOrders.SummaryMonth','ReportPrices.Archive','ReportPrices.ByDate','ReportPrices.Detail','ReportPrices.History','SkynetEstimationOrder'],//Клиент-менеджер
            '8' => ['admin','Administrator.Callcenter.index','mis','MisManager','Operator','Registrar','SkynetEstimationOrder'],//todo доктор-консультант
        ];

        if (!array_key_exists($this->department, $permissions)) return false;

        //todo удаляем все роли у пользователя
        $searchAssignment = NAuthASsignment::deleteAll(['userid' => $this->aid]);
        if (!$searchAssignment) {
            Yii::getLogger()->log(['ActiveSyncController:searchAssignment'=>'Не удалось удалить права!'], 1, 'binary');
            return false;
        }

        //todo присвоение прав пользователю
        if (count($permissions[$this->department]) > 0) {
            $rowInsert = [];
            foreach ($permissions[$this->department] as $permission) {
                $rowInsert[] = [$permission, $this->aid, 'N;'];
            }
            try {
                $connection = 'GemoTestDB';
                $db = Yii::$app->$connection;
                $db->createCommand()->batchInsert(
                    NAuthASsignment::tableName(),
                    ['itemname', 'userid', 'data'],
                    $rowInsert
                )->execute();
            } catch (Exception $e) {
                Yii::getLogger()->log([
                    'ActiveSyncController->batchInsert'=>$e->getMessage()
                ], 1, 'binary');
                return false;
            }
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
            || empty($this->middleName)
            || empty($this->fullName)
        ){
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'Одно из обязательных полей пустое!'
            ], 1, 'binary');
            return false;
        }

        $this->cnName = $this->fullName;
        $this->displayName = $this->fullName;

        if ($this->type == 8)  {
            $this->accountName = $this->key;
            $this->accountName .= ".".substr($this->translit($this->firstName),0,1);
            $this->accountName .= ".".$this->translit($this->lastName);
        } else {
            $this->accountName .= $this->translit($this->firstName . "." . $this->lastName);
        }

        if (!empty($this->operatorofficestatus)) {
            $this->displayName = $this->fullName . " (" . $this->operatorofficestatus . ")";
        }

        if ($this->typeLO == 'FLO' && !empty($this->key)) {
            $this->cnName = $this->fullName.' '.$this->key;
        }

        if ($this->checkUserAccountAd())
        {
            //todo если есть, то добавляю отчество в присвоеный логин в AD
            $this->accountName = $this->translit($this->firstName);
            $this->accountName .= ".".substr($this->translit($this->middleName),0,1);
            $this->accountName .= ".".$this->translit($this->lastName);
            if ($this->typeLO != 'FLO' && !empty($this->operatorofficestatus)) {
                $this->cnName = $this->fullName." (".$this->operatorofficestatus.")";
            }
        }

        //todo создаем нового пользователя в AD
        $arrAccountAD = $this->addNewUserAd();

        if (!$arrAccountAD) {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD'=>'Не удалось добавить учетную запись в AD'
            ], 1, 'binary');
            return false;
        }
        else {
            Yii::getLogger()->log([
                'ActiveSyncController=>addUserAD=>arrAccountAD'=>$arrAccountAD
            ], 1, 'binary');
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
    public function resetPasswordAD($accountName)
    {
        $newPassword = self::generatePasswordAD();
        $newPasswordUTF6LE = iconv("UTF-8", "UTF-16LE", '"' . $newPassword . '"');
        $ADgroup = "DC=lab,DC=gemotest,DC=ru";

        try {

            $ldapconn = ldap_connect('ldaps://sw-dc-05.lab.gemotest.ru', 636);
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
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
            return false;
        }
    }

    /**
     * @return array|bool
     */
    public function addNewUserAd()
    {
        $password = self::generatePasswordAD();
        $email = $this->accountName."@lab.gemotest.ru";

        $ldaprecord = [
            "CN" => $this->cnName,
            "name" => $this->cnName,
            "sn" => $this->lastName, //фамилия
            "givenname" => $this->firstName.' '.$this->middleName, //имя отчество
            "sAMAccountName" => $this->accountName, //логин
            "userPrincipalName" => $email, //емаил
            "mail" => $email, //емаил
            "objectClass" => "user",
            "displayname" => $this->displayName, //ФИО
            "unicodepwd" => iconv("UTF-8", "UTF-16LE", '"' . $password . '"'),
            "userAccountControl" => "544" //доступ
        ];

        Yii::getLogger()->log([
            'ActiveSyncController=>addNewUserAd=>ldaprecord'=>$ldaprecord
        ], 1, 'binary');

        try {

            $ADgroup = "CN=".$ldaprecord["CN"].",OU=".$this->typeLO." Users,OU=SSO,OU=gUsers,DC=lab,DC=gemotest,DC=ru";
            $ldapconn = ldap_connect('ldaps://sw-dc-05.lab.gemotest.ru', 636);
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
                    'ActiveSyncController=>addNewUserAd'=>'В AD успешно добавлена УЗ '.$this->accountName
                ], 1, 'binary');
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
                $message .= ' т.к. данный пользователь с таким именем уже есть, либо не синхронизированы контроллеры домена! Повторите попытку позже!</p>';
                    Yii::$app->session->setFlash('warning', $message);
            }
            if (strpos($e->getMessage(), 'Add: Constraint violation') !== false) {
                $message = '<p>Не удалось создать УЗ в AD для <b> ' . $this->accountName. '</b>';
                $message .= ' т.к. данный пользователь с таким логином уже есть, либо не синхронизированы контроллеры домена! Повторите попытку позже!</p>';
                Yii::$app->session->setFlash('warning', $message);
            }
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
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
        $ADcheckUser = "(displayname=*".$this->fullName."*)";
        $justthese = array("displayname", "samaccountname", "userprincipalname");

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
                    'ActiveSyncController=>checkUserNameAd'=>$this->fullName.' '.'не найден в AD'
                ], 1, 'binary');
                return false;
            }

            for ($i = 0; $i < $info['count']; $i++) {
                $samaccountname = $info[$i]['samaccountname'][0];
                $findModel = NAdUsers::findOne(['AD_login' => $samaccountname]);
                if (!$findModel) {
                    $arrAccounts[] = [
                        'account' => $samaccountname,
                        'name' => $info[$i]['displayname'][0],
                        'email' => $info[$i]['userprincipalname'][0],
                    ];
                    Yii::getLogger()->log([
                        'ActiveSyncController=>checkUserNameAd=>$arrAccounts'=>$arrAccounts
                    ], 1, 'binary');
                } else {
                    //todo если УЗ в AD уже используется в NAdUsers то не можем использовать
                    Yii::getLogger()->log([
                        'ActiveSyncController=>checkUserNameAd'=>$samaccountname.' '.'есть в NAdUsers'
                    ], 1, 'binary');
                }
            }

        } catch (Exception $e) {
            Yii::getLogger()->log([
                'ActiveSyncController'=>$e->getMessage()
            ], 1, 'binary');
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
                    'ActiveSyncController=>checkUserAccountAd'=>$this->accountName.' '.'не найдена в AD!'
                ], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log([
                    'ActiveSyncController=>checkUserAccountAd'=>$this->accountName.' '.'найдена в AD!'
                ], 1, 'binary');
                return true;
            }

        } catch (Exception $e) {
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
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