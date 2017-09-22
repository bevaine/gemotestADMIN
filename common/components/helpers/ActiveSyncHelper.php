<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.08.2017
 * Time: 12:14
 */

namespace common\components\helpers;

use common\models\AddUserForm;
use common\models\ErpNurses;
use common\models\ErpUsers;
use common\models\Logins;
use common\models\LpASs;
use common\models\NAdUseraccounts;
use common\models\NAdUsers;
use common\models\NAuthASsignment;
use common\models\NNurse;
use common\models\Operators;
use Yii;
use Exception;

/**
 * Class ActiveSyncHelper
 * @package common\components\helpers
 */

class ActiveSyncHelper
{
    CONST TYPE_LO = "SLO";
    CONST POWER_SHELL_PATH = "C:\Windows\System32\WindowsPowerShell\\v1.0\powershell.exe -Command";

    public $lastName;
    public $firstName;
    public $middleName;
    public $fullName;
    public $accountName;
    public $emailAD;
    public $passwordAD;
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

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function checkLoginAccount()
    {
        return $loginSearch = Logins::find()
            ->andFilterWhere(['like', 'Name', $this->fullName])
            ->andFilterWhere(['UserType' => $this->department])
            ->one();
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function checkFranchazyAccount()
    {
        return $loginSearch = Logins::find()
            ->andFilterWhere(['Key' => $this->key])
            ->andFilterWhere(['UserType' => 8])
            ->one();
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
            'aid' => $loginSearch->aid,
            'login' => $loginSearch->Login,
            'password' => $loginSearch->Pass,
            'state' => 'old'
        ];
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
        ) return false;

        //todo проверяем существует ли запись в Operators
        $objectOperators = Operators::find()->where([
            'Name' => $this->firstName." ".$this->middleName,
            'LastName' => $this->lastName
        ])->one();

        //todo если нет, то добавление в Operators
        if (!$objectOperators) {
            $cacheId = Operators::find()
                ->select('CACHE_OperatorID')
                ->orderBy('AID DESC')
                ->one();

            $this->cacheId = strval($cacheId->CACHE_OperatorID) + 1;
            $this->cachePass = Yii::$app->getSecurity()->generateRandomString(8);
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

            if (!$objectOperators->save()) {
                Yii::getLogger()->log(['$objectOperators->save()'=>$objectOperators->errors], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log(['$objectOperators->save()'=>$objectOperators], 1, 'binary');
            }
        }

        $loginId = $objectOperators->AID;
        $this->aid = 3000000 + $loginId;
        if ($this->department == 5) $this->login = 'medr'.$loginId;
        else $this->login = 'reg'.$loginId;
        return true;
    }

    /**
     * @return mixed
     */
    public function addCheckLogins()
    {
        /**
         * @var Logins $objectUsersLogins
         */
        $state = '';
        if (empty($this->fullName)) return false;

        //todo проверяем существует ли запись в Logins
        $objectUsersLogins = Logins::find()->where([
            'like', 'Name', $this->fullName
        ])->one();

        if ($objectUsersLogins)
        {
            //todo разблокируем учетную запись
            $state = 'old';
            $this->unblockAccount($objectUsersLogins->aid);
        } else
        {
            //todo добавляем новую запись в Logins
            if (empty($this->aid)
                || empty($this->login)
                || empty($this->cacheId)
                || empty($this->cachePass)
            )

            $state = 'new';
            $logo = 'logos/LogoGemotest.gif';
            $logoText = '107031 Москва, Рождественский бульвар д.21, ст.2^*^тел. (495) 532-13-13, 8(800) 550-13-13^*^www.gemotest.ru';

            $objectUsersLogins = new Logins();
            $objectUsersLogins->aid = $this->aid;
            $objectUsersLogins->Login = $this->login;
            $objectUsersLogins->Pass = $this->cachePass;
            $objectUsersLogins->Name = $this->cacheId . '.' . $this->operatorofficestatus . ':' . $this->fullName;
            $objectUsersLogins->Email = $this->emailAD;
            $objectUsersLogins->Key = strval($this->cacheId);
            $objectUsersLogins->UserType = $this->department == 5 ? 5 : 7;
            $objectUsersLogins->CACHE_Login = $this->accountName;
            $objectUsersLogins->last_update_password = date("Y-m-d G:i:s:000");
            $objectUsersLogins->Logo = $logo;
            $objectUsersLogins->LogoText = $logoText;
            $objectUsersLogins->tbl = 'Operators';
            $objectUsersLogins->IsAdmin = 0;
            $objectUsersLogins->IsOperator = 1;
            $objectUsersLogins->LogoText2 = '';
            $objectUsersLogins->LogoType = '';
            $objectUsersLogins->LogoWidth = '';
            $objectUsersLogins->TextPaddingLeft = '';
            $objectUsersLogins->OpenExcel = 0;
            $objectUsersLogins->EngVersion = 0;
            $objectUsersLogins->IsDoctor = 2;
            $objectUsersLogins->InputOrder = 1;
            $objectUsersLogins->PriceID = 0;
            $objectUsersLogins->CanRegister = 1;
            $objectUsersLogins->InputOrderRM = 1;
            $objectUsersLogins->OrderEdit = 0;
            $objectUsersLogins->goscontract = 0;
            $objectUsersLogins->FizType = 0;
            $objectUsersLogins->mto_editor = 0;

            if (!$objectUsersLogins->save()) {
                Yii::getLogger()->log(['$objectUsersLogins->save()' => $objectUsersLogins->errors], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log(['$objectUsersLogins->save()' => $objectUsersLogins], 1, 'binary');
            }
        }

        return [
            'aid' => $objectUsersLogins->aid,
            'login' => $objectUsersLogins->Login,
            'password' => $objectUsersLogins->Pass,
            'state' => $state
        ];
    }

    /**
     * @return bool
     */
    public function addNewAdUserAccount()
    {
        if (empty($this->accountName) || empty($this->passwordAD)) return false;

        $accountLab = "lab\\".$this->accountName;
        $objectUserAccountsAD = NAdUseraccounts::findOne(['ad_login' => $accountLab]);
        if ($objectUserAccountsAD) {
            $objectUserAccountsAD->ad_pass = $this->passwordAD;
            if (!$objectUserAccountsAD->save()) {
                Yii::getLogger()->log(['$objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors], 1, 'binary');
            } else {
                return true;
            }
        }

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->middleName)
            || empty($this->cacheId)
        ) return false;

        $objectUserAccountsAD = new NAdUseraccounts();
        $objectUserAccountsAD->last_name = $this->lastName;
        $objectUserAccountsAD->first_name = $this->firstName;
        $objectUserAccountsAD->middle_name = $this->middleName;
        $objectUserAccountsAD->gs_type = self::TYPE_LO;
        $objectUserAccountsAD->gs_id = strval($this->cacheId);
        $objectUserAccountsAD->org_name = '';
        $objectUserAccountsAD->ad_login = $accountLab;
        $objectUserAccountsAD->ad_pass = $this->passwordAD;

        if (!$objectUserAccountsAD->save()) {
            Yii::getLogger()->log(['$objectUserAccountsAD->save()'=>$objectUserAccountsAD->errors], 1, 'binary');
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function addLpassUsers()
    {
        $objectLpASs = new LpASs();
        $objectLpASs->ukey = strval($this->cacheId);
        $objectLpASs->utype = strval(7);
        $objectLpASs->login = $this->login;
        $objectLpASs->pass = $this->cachePass;
        $objectLpASs->dateins = date("Y-m-d G:i:s:000");
        $objectLpASs->iukey = strval(0);
        $objectLpASs->iutype = strval(0);
        $objectLpASs->active = strval(1);

        if (!$objectLpASs->save()) {
            Yii::getLogger()->log(['ActiveSyncController:objectLpASs'=>$objectLpASs->errors], 1, 'binary');
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function addNewAdUsers()
    {
        if (!empty($this->accountName)) {
            $objectUserAD = NAdUsers::findOne(['AD_login' => $this->accountName]);
            if ($objectUserAD) return true;
        } else return false;

        if (empty($this->lastName)
            || empty($this->firstName)
            || empty($this->middleName)
            || empty($this->fullName)
            || empty($this->operatorofficestatus)
            || empty($this->aid)
            || empty($this->cacheId)
        ) return false;

        $objectUserAD = new NAdUsers();
        $objectUserAD->last_name = $this->lastName;
        $objectUserAD->first_name = $this->firstName;
        $objectUserAD->middle_name = $this->middleName;
        $objectUserAD->AD_name = $this->fullName;
        $objectUserAD->AD_position = $this->operatorofficestatus;
        $objectUserAD->AD_email = $this->emailAD;
        $objectUserAD->gs_id = $this->aid;
        $objectUserAD->gs_key = strval($this->cacheId);
        $objectUserAD->gs_usertype = $this->type;
        $objectUserAD->AD_login = $this->accountName;
        $objectUserAD->allow_gs = 1;
        $objectUserAD->active = 1;
        $objectUserAD->AD_active = 1;
        $objectUserAD->auth_ldap_only = ($this->department == 2) ? 0 : 1;

        if (!$objectUserAD->save()) {
            Yii::getLogger()->log(['$objectUserAD->save()'=>$objectUserAD->errors], 1, 'binary');
            return false;
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
         * @var Logins $loginsObject
         */
        if ($this->type == 8 && !empty($this->key)) {

            //todo если франчайзи (только проверяем запись в Logins)
            $findUserLogin = $this->checkFranchazyAccount();
            if (!$findUserLogin) {
                $message = '<p>У данного контрагента нет УЗ, невозможно добавить <b>' . $this->fullName . '</b></p>';
                Yii::$app->session->setFlash('error', $message);
                return false;
            }

            $this->emailAD = $findUserLogin->Email;
            $this->aid = $findUserLogin->aid;
            return $this->createAdUserAcc();

        } elseif (in_array($this->department, [4, 5])) {

            //todo если Отдел клиентской информационной поддержки/Мед регистратор
            if ($this->addCheckOperators()) {
                if ($returnLogins = $this->addCheckLogins() && $this->addLpassUsers()) {
                    return $returnLogins;
                }
            }

        } elseif (in_array($this->department, [0, 1, 2, 3, 6, 7, 8])) {

            //todo если Администр./Врач консул./Собств. лаб. отд./Ген. директор (проверяем/создаем в Logins и adUsers)

            if ($this->addCheckOperators() && $loginsObject = $this->addCheckLogins()) {

                if ($loginsObject->adUsersOne && !empty($loginsObject->adUsersOne->AD_login)) {
                    $this->accountName = $loginsObject->adUsersOne->AD_login;
                }
                return $this->createAdUserAcc();
            }
        }
        return false;
    }

    public function createAdUserAcc()
    {
        if (!empty($this->accountName)) {

            //todo если находим то сбрасываем пароль в AD
            $newPasswordAd = $this->resetPasswordAD($this->accountName);
            if ($newPasswordAd) {
                $message = '<p>Изменен пароль УЗ для <b>' . $this->fullName . '</b> в AD </p>';
                Yii::$app->session->setFlash('warning', $message);
            }
        } else {

            //todo если нет УЗ в AD - создаем
            $addNewUser = $this->addUserAD();
            if (!$addNewUser) {
                $message = '<p>Не удалось создать УЗ для <b>' . $this->fullName . '</b> в AD</p>';
                Yii::$app->session->setFlash('error', $message);
                return false;
            }
        }

        if ($this->addNewAdUserAccount() && $this->addNewAdUsers()) {

            if (empty($this->aid)
                || empty($this->accountName)
                || empty($this->passwordAD)
            ) return false;

            return [
                'aid' => $this->aid,
                'login' => $this->accountName,
                'password' => $this->passwordAD,
                'state' => 'new'
            ];
        } else {
            $message = '<p>Не удалось создать/изменить УЗ для <b>' . $this->fullName . '</b> в таблице AD</p>';
            Yii::$app->session->setFlash('error', $message);
            return false;
        }
    }

    /**
     * @return mixed
     *
     */
    public function addNewUser()
    {
        $loginId = "";
        $cacheId = "";
        $cachePass = "";
        $this->fullName = $this->lastName . " " . $this->firstName . " " . $this->middleName;

        //todo проверяем существует ли запись в Operators
        $findUsersOperators = Operators::find()->where([
            'Name' => $this->firstName." ".$this->middleName,
            'LastName' => $this->lastName
        ])->one();

        if (!$findUsersOperators) {
            $cacheId = Operators::find()
                ->select('CACHE_OperatorID')
                ->orderBy('AID DESC')
                ->one();
            $cacheId = $cacheId->CACHE_OperatorID + 1;
            $cachePass = Yii::$app->getSecurity()->generateRandomString(8);
            $objectOperators = new Operators();
            $objectOperators->CACHE_Login = $this->accountName;
            $objectOperators->Name = $this->firstName." ".$this->middleName;
            $objectOperators->LastName = $this->lastName;
            $objectOperators->DateIns = date("Y-m-d G:i:s:000");
            $objectOperators->CACHE_OperatorID = strval($cacheId);
            $objectOperators->OperatorOfficeStatus = $this->operatorofficestatus;
            $objectOperators->Pass = $cachePass;
            $objectOperators->Active = 1;
            $objectOperators->CanRegister = 1;
            $objectOperators->InputOrderRM = 1;
            $objectOperators->mto_editor = 0;

            if (!$objectOperators->save()) {
                Yii::getLogger()->log(['ActiveSyncController:objectOperators'=>$objectOperators->errors], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log(['ActiveSyncController:objectOperators'=>$objectOperators], 1, 'binary');
            }

            $loginId = $objectOperators->AID;
        }

        //todo проверяем существует ли запись в Logins
        $findUsersLogins = Logins::find()->where([
            'like', 'Name', $this->fullName
        ])->one();

        if ($findUsersLogins)
        {
            $this->unblockAccount($findUsersLogins->aid);

        } else {
            $this->aid = 3000000 + $loginId;

            if ($this->department == 5) $this->login = 'medr'.$loginId;
            else $this->login = 'reg'.$loginId;

            $objectUsersLogins = new Logins();
            $objectUsersLogins->aid = $this->aid;
            $objectUsersLogins->Login = $this->login;
            $objectUsersLogins->Pass = $cachePass;
            $objectUsersLogins->Name = $cacheId.'.'.$this->operatorofficestatus.':'.$this->fullName;
            $objectUsersLogins->IsOperator = 1;
            $objectUsersLogins->Email = $this->emailAD;
            $objectUsersLogins->IsAdmin = 0;
            $objectUsersLogins->Key = strval($cacheId);
            $objectUsersLogins->Logo = 'logos/LogoGemotest.gif';
            $objectUsersLogins->LogoText = '107031 Москва, Рождественский бульвар д.21, ст.2^*^тел. (495) 532-13-13, 8(800) 550-13-13^*^www.gemotest.ru';
            $objectUsersLogins->LogoText2 = '';
            $objectUsersLogins->LogoType = '';
            $objectUsersLogins->LogoWidth = '';
            $objectUsersLogins->TextPaddingLeft = '';
            $objectUsersLogins->OpenExcel = 0;
            $objectUsersLogins->EngVersion = 0;
            $objectUsersLogins->tbl = 'Operators';
            $objectUsersLogins->UserType = $this->department == 5 ? 5 : 7;
            $objectUsersLogins->IsDoctor = 2;
            $objectUsersLogins->InputOrder = 1;
            $objectUsersLogins->PriceID = 0;
            $objectUsersLogins->CanRegister = 1;
            $objectUsersLogins->CACHE_Login = $this->accountName;
            $objectUsersLogins->InputOrderRM = 1;
            $objectUsersLogins->OrderEdit = 0;
            $objectUsersLogins->goscontract = 0;
            $objectUsersLogins->FizType = 0;
            $objectUsersLogins->mto_editor = 0;
            $objectUsersLogins->last_update_password = date("Y-m-d G:i:s:000");

            if (!$objectUsersLogins->save()) {
                Yii::getLogger()->log(['ActiveSyncController:objectUsersLogins'=>$objectUsersLogins->errors], 1, 'binary');
                return false;
            } else {
                Yii::getLogger()->log(['ActiveSyncController:objectUsersLogins'=>$objectUsersLogins], 1, 'binary');
            }
        }

        if (in_array($this->department, [0,1,2,3,6,8])) {
            $objectUserAD = new NAdUsers();
            $objectUserAD->last_name = $this->lastName;
            $objectUserAD->first_name = $this->firstName;
            $objectUserAD->middle_name = $this->middleName;
            $objectUserAD->AD_name = $this->fullName;
            $objectUserAD->AD_position = $this->operatorofficestatus;
            $objectUserAD->AD_email = $this->emailAD;
            $objectUserAD->gs_id = $this->aid;
            $objectUserAD->gs_key = strval($cacheId);
            $objectUserAD->gs_usertype = 7;
            $objectUserAD->allow_gs = 1;
            $objectUserAD->active = 1;
            $objectUserAD->AD_login = $this->accountName;
            $objectUserAD->AD_active = 1;
            $objectUserAD->auth_ldap_only = ($this->department == 2) ? 0 : 1;

            if (!$objectUserAD->save()) {
                Yii::getLogger()->log(['ActiveSyncController:objectUserAD'=>$objectUserAD->errors], 1, 'binary');
                return false;
            }
            $objectUserAccountsAD = new NAdUseraccounts();
            $objectUserAccountsAD->last_name = $this->lastName;
            $objectUserAccountsAD->first_name = $this->firstName;
            $objectUserAccountsAD->middle_name = $this->middleName;
            $objectUserAccountsAD->gs_type = self::TYPE_LO;
            $objectUserAccountsAD->gs_id = strval($cacheId);
            $objectUserAccountsAD->org_name = '';
            $objectUserAccountsAD->ad_login = "lab\\".$this->accountName;
            $objectUserAccountsAD->ad_pass = $this->passwordAD;

            if (!$objectUserAccountsAD->save()) {
                Yii::getLogger()->log(['ActiveSyncController:objectUserAccountsAD'=>$objectUserAccountsAD->errors], 1, 'binary');
                return false;
            }
        } else {
            $objectLpASs = new LpASs();
            $objectLpASs->ukey = strval($cacheId);
            $objectLpASs->utype = strval(7);
            $objectLpASs->login = $this->login;
            $objectLpASs->pass = $cachePass;
            $objectLpASs->dateins = date("Y-m-d G:i:s:000");
            $objectLpASs->iukey = strval(0);
            $objectLpASs->iutype = strval(0);
            $objectLpASs->active = strval(1);

            if (!$objectLpASs->save()) {
                Yii::getLogger()->log(['ActiveSyncController:objectLpASs'=>$objectLpASs->errors], 1, 'binary');
                return false;
            }
        }



        if (in_array($this->department, [4,5])) {
            if (empty($this->login) || empty($cachePass)) return false;
            return [
                'aid' => $this->aid,
                'login' =>$this->login,
                'password' => $cachePass,
                'state' => 'new'
            ];
        } else {
            if (empty($this->accountName) || empty($this->passwordAD)) return false;
            return [
                'aid' => $this->aid,
                'login' => "lab\\".$this->accountName,
                'password' => $this->passwordAD,
                'state' => 'new'
            ];
        }
    }

    /**
     * @param $aid
     * @param $department
     * @param $nurse
     * @return bool
     */
    public static function addPermissions($aid, $department, $nurse)
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
        $searchLogin = Logins::findOne(['aid' => $aid]);
        if (!$searchLogin) return false;

        $searchLogin->MedReg = ($department == 5) ? 1 : 0;
        $searchLogin->mto = in_array($department, [2,3,5,6]) ? 1 : 0;
        $searchLogin->clientmen = in_array($department, [3,4,6]) ? 1 : 0;
        $searchLogin->show_preanalytic = in_array($department, [4,5]) ? 1 : 0;

        $searchLogin->operators->MedReg = ($department == 5) ? 1 : 0;
        $searchLogin->operators->mto = in_array($department, [2,3,5,6]) ? 1 : 0;
        $searchLogin->operators->OrderEdit = in_array($department, [3,4,6]) ? 1 : 0;
        $searchLogin->operators->ClientMen = in_array($department, [3,4,6]) ? 1 : 0;

        $fullName = $searchLogin->adUsersOne->last_name." ".$searchLogin->adUsersOne->first_name." ".$searchLogin->adUsersOne->middle_name;

        if (!in_array($department, [1,2,3,5,6])) {
            if (in_array($nurse,[1,2])) {
                $objectNurse = new NNurse();
                $objectNurse->first_name = $searchLogin->adUsersOne->first_name;
                $objectNurse->last_name = $searchLogin->adUsersOne->last_name;
                $objectNurse->middle_name = $searchLogin->adUsersOne->middle_name;
                $objectNurse->active = 1;
                $objectNurse->save();
            }
            if ($nurse == 2) {
                $objectErpUsers = new ErpUsers();
                $objectErpUsers->group_id = 11;
                $objectErpUsers->name = $fullName;
                $objectErpUsers->login = $searchLogin->operators->CACHE_Login;
                $objectErpUsers->password = 'd9b1d7db4cd6e70935368a1efb10e377';
                $objectErpUsers->status = 1;
                $objectErpUsers->skynet_login = $searchLogin->Login;
                $objectErpUsers->save();

                $nurseId = ErpUsers::find()
                    ->select('id')
                    ->where(['group_id' => 11])
                    ->orderBy('id DESC')
                    ->one();
                $objectErpNurses = new ErpNurses();
                $objectErpNurses->user_id = $nurseId;
                $objectErpNurses->nurse_email = '';
                $objectErpNurses->nurse_phone = '';
                $objectErpNurses->nurse_key = $aid;
            }
        }

        if (!$searchLogin->save()) {
            Yii::getLogger()->log(['ActiveSyncController:'=>$searchLogin->errors], 1, 'binary');
            return false;
        }

        //todo сбрасываем права для данного пользователя
        $permissions = [
            '0' => ['mis','workshift.allow','MisManager','Operator','Registrar','Report.Workshift.Kkm','SkynetEstimationOrder'],
            '1' => ['admin', 'Administrator.Callcenter.index', 'mis', 'MisManager', 'Operator', 'Registrar', 'SkynetEstimationOrder'],
            '2' => ['Operator','Registrar'],
            '3' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],
            '4' => ['admin','ClientManager','finance_manager','management_all_offices','Operator','Registrar','Report.Inoe','ReportOrders.Contingents','SkynetEstimationOrder'],
            '5' => ['Operator','ClientManager','MedRegistrar','Report.Inoe','PreanalyticaManager'],
            '6' => ['admin','Administrator.Callcenter.index','bonuses_view','cancelBm_view','ClientManager','discount_all_rights','kurs_view','mis','MisManager','Operator','Registrar','Report.MsZabor','Report.PollPatients','Report.Rep41','ReportOrders.Detail','ReportOrders.SummaryMonth','ReportPrices.Archive','ReportPrices.ByDate','ReportPrices.Detail','ReportPrices.History','SkynetEstimationOrder'],
            '7' => [],
            '8' => ['admin','Administrator.Callcenter.index','MisManager','mis','Operator','Registrar','SkynetEstimationOrder']
        ];

        //todo удаляем все роли у пользователя
        $searchAssignment = NAuthASsignment::deleteAll(['userid' => $aid]);
        if (!$searchAssignment) {
            Yii::getLogger()->log(['ActiveSyncController:searchAssignment'=>'Не удалось удалить права!'], 1, 'binary');
            return false;
        }

        //todo присвоение прав пользователю
        if (!array_key_exists($department, $permissions)) return false;

        if (count($permissions[$department]) > 0) {
            $rowInsert = [];
            foreach ($permissions[$department] as $permission) {
                $rowInsert[] = [$permission, $aid, 'N;'];
            }

            try {
                Yii::$app->db->createCommand()->batchInsert(
                    NAuthASsignment::tableName(),
                    ['itemname', 'userid', 'data'],
                    $rowInsert)->execute();
            } catch (Exception $e) {
                Yii::getLogger()->log(['ActiveSyncController:batchInsert'=>$e->getMessage()], 1, 'binary');
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
        //todo проверяем существует ли подобный логин в AD
        $this->accountName = $this->translit($this->firstName.".".$this->lastName);
        if ($this->checkUserAccountAd())
        {
            //todo если есть, то добавляю отчество в присвоеный логин в AD
            $this->accountName = $this->translit($this->firstName.".".
                substr($this->middleName,0,1).".".$this->lastName);
        }
        //todo создаем нового пользователя в AD
        $arrAccountAD = $this->addNewUserAd();

        if (!$arrAccountAD) return false;
        else {
            $this->accountName = $arrAccountAD['SamAccountName'];
            $this->emailAD = $arrAccountAD['UserPrincipalName'];
            $this->passwordAD = $arrAccountAD['AccountPassword'];
        }
        return true;
    }

    /**
     * @param $accountName
     * @return mixed
     */
    public function resetPasswordAD($accountName)
    {
        //todo сбрасываем пароль для учетной записи
        $newPassword = self::generatePasswordAD();
        $ADcheckUser = " Set-ADAccountPassword -Identity \"".$accountName."\"";
        $ADcheckUser .= " -NewPassword";
        $ADcheckUser .= " (ConvertTo-SecureString -string \"".$newPassword."\" -AsPlainText -force)";
        try {
            $output = shell_exec(addslashes(self::POWER_SHELL_PATH . $ADcheckUser));
        } catch (Exception $e) {
            return false;
        }
        return $output === NULL ? $newPassword : false;
    }

    public function addNewUserAd()
    {
        $email = $this->accountName."@lab.gemotest.ru";
        $fullName = iconv("UTF-8","cp1251", $this->fullName);
        $password = self::generatePasswordAD();

        $ADgroup = "OU=".self::TYPE_LO." Users,OU=SSO,OU=gUsers,DC=lab,DC=gemotest,DC=ru";
        $ADnewUser = " New-ADUser";
        $ADnewUser .= " -Name \"".$fullName."\"";
        $ADnewUser .= " -DisplayName \"".$fullName."\"";
        $ADnewUser .= " -SamAccountName \"".$this->accountName."\"";
        $ADnewUser .= " -UserPrincipalName \"".$email."\"";
        $ADnewUser .= " -GivenName \"".iconv("UTF-8","cp1251", $this->firstName)."\"";
        $ADnewUser .= " -Surname \"".iconv("UTF-8","cp1251", $this->lastName);
        $ADnewUser .= " (".iconv("UTF-8","cp1251",$this->operatorofficestatus).")\"";
        $ADnewUser .= " -Path \"".$ADgroup."\"";
        $ADnewUser .= " -Enabled \$true";
        $ADnewUser .= " -AccountPassword";
        $ADnewUser .= " (ConvertTo-SecureString -string \"".$password."\" -AsPlainText -force)";

        try {
            $output = shell_exec(addslashes(self::POWER_SHELL_PATH . $ADnewUser));
        } catch (Exception $e) {
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
            return false;
        }

        if ($output === NULL) {
            return [
                'SamAccountName' => $this->accountName,
                'UserPrincipalName' => $email,
                'AccountPassword' => $password
            ];
        } else {
            return false;
        }
    }

    public function connectLDAP() {
        // используется ldap-привязка
        $ldaprdn  = 'dymchenko.adm@lab.gemotest.ru';     // ldap rdn или dn
        $ldappass = '2Hszfaussw';  // ассоциированный пароль

        // соединение с сервером
        $ldapconn = ldap_connect("192.168.108.3")
        or die("Не могу соединиться с сервером LDAP.");

        if ($ldapconn) {

            // привязка к ldap-серверу
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

            $dn = "DC=lab,DC=gemotest,DC=ru";
            $sr = ldap_search($ldapconn, $dn, "CH=*");
            print_r($sr);

            // проверка привязки
            if ($ldapbind) {
                echo "LDAP-привязка успешна...";
            } else {
                echo "LDAP-привязка не удалась...";
            }

        }
    }
    /**
     * @return array|bool
     */
    public function checkUserNameAd()
    {
        //todo проверяем на полное совпадение имени пользователя
        $ADcheckUser = " Get-ADUser -filter {Name -like \"*" . iconv("UTF-8", "cp1251", $this->fullName) . "*\"}";
        try {
            exec(addslashes(self::POWER_SHELL_PATH . $ADcheckUser), $output);
        } catch (Exception $e) {
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
            return false;
        }
        if (!empty($output)) {
            return $this->parseAccountSearch($output);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function checkUserAccountAd()
    {
        //todo проверяем на совпадение УЗ AD
        $ADcheckUser = " Get-ADUser -filter {SamAccountName -like \"".$this->accountName."\"}";
        try {
            $output = shell_exec(addslashes(self::POWER_SHELL_PATH . $ADcheckUser));
        } catch (Exception $e) {
            Yii::getLogger()->log(['ActiveSyncController'=>$e->getMessage()], 1, 'binary');
            return false;
        }
        if ($output === NULL) return false;
        else return true;
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
            $findUsersLogins->save();
            Yii::getLogger()->log(['ActiveSyncController'=>'У данного пользователя учетная запись разблокирована!'], 1, 'binary');
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
     * @param $array
     * @return array|bool
     */
    public function parseAccountSearch($array) {
        $arrReturn = [];
        $arrOut = [];
        foreach ($array as $str)
        {
            $expStr = explode(":", $str);
            if (count($expStr) > 1) {
                $arrReturn[trim($expStr[0])][] = trim($expStr[1]);
            }
        }

        if (!empty($arrReturn) && is_array($arrReturn)) {
            foreach ($arrReturn as $keyName => $arrValue) {
                if (is_array($arrValue)) {
                    foreach ($arrValue as $key => $value)
                        $arrOut[$key][$keyName] = $value;
                }
            }
        }

        if (!empty($arrOut)) return $arrOut;
        else return false;
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