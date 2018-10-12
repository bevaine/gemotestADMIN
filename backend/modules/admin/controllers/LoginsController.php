<?php

namespace app\modules\admin\controllers;

use common\models\DirectorFlo;
use common\models\ErpGroupsRelations;
use common\models\ErpUsergroups;
use common\models\NAdUseraccounts;
use common\models\NAdUsers;
use common\models\NAuthItem;
use common\models\Permissions;
use PHPUnit\Exception;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\web\Controller;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\models\Logins;
use common\models\Doctors;
use common\models\LoginsSearch;
use common\models\AddUserForm;
use common\components\helpers\ActiveSyncHelper;
use common\models\PermissionsSearch;
use common\models\DirectorFloSender;
use yii\db\Transaction;
use common\models\HrPublicEmployee;

/**
 * LoginsController implements the CRUD actions for Logins model.
 */
class LoginsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Logins models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LoginsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $ad
     * @return \yii\web\Response
     */
    public function actionDelete($ad)
    {
        /** @var NAdUsers $model */
        if ($model = NAdUsers::findOne($ad)) {
            $model->delete();
            if ($adUsersLogins = $model->adUserAccounts) {
                $adUsersLogins->delete();
            }
            $message = 'Пользователь: <b>' . $model->AD_name. '</b> был успешно удален!';
            Yii::$app->session->setFlash('success', $message);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param string $department
     * @return string
     */
    public function actionRoles($department = '')
    {
        $arrRows = [];
        $rowInsert = [];

        if ($department == 7) $department = 0;

        if (!$model = ErpGroupsRelations::findOne([
            'department' => $department
        ])) {
            $model = new ErpGroupsRelations();
        }

        if (!empty(Yii::$app->request->post())) {
            print_r(Yii::$app->request->post());
            exit;
        }


        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if ($model->action == 'assign'
                && !empty($model->list_permission)
                && is_array($model->list_permission))
            {
                foreach ($model->list_permission as $permission) {
                    $rowInsert[] = [$department, $permission];
                    $arrRows[] = $permission;
                }
                try {
                    Permissions::deleteAll([
                        'AND',
                        ['department' => $department],
                        ['in', 'permission', $arrRows]
                    ]);
                    Yii::$app->db->createCommand()->batchInsert(
                        Permissions::tableName(),
                        ['department', 'permission'],
                        $rowInsert
                    )->execute();

                } catch (Exception $e) {
                    Yii::getLogger()->log([
                        'addPermissions->batchInsert'=>$e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }

            } elseif ($model->action == 'revoke'
                && !empty($model->permission)
                && is_array($model->permission))
            {
                foreach ($model->permission as $permission) {
                    $arrRows[] = $permission;
                }
                try {
                    Permissions::deleteAll([
                        'AND',
                        ['department' => $department],
                        ['in', 'permission', $arrRows]
                    ]);
                } catch (Exception $e) {
                    Yii::getLogger()->log([
                        'addPermissions->batchInsert'=>$e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
            }
        } else {
            //print_r($model->errors);
        }

        return $this->render('roles', [
            'department' => $department
        ]);
    }

    /**
     * @param $id
     * @param string $ad
     * @return string
     */
    public function actionView($id, $ad = '')
    {
        if (!$model = $this->findModel($id, $ad))
            return $this->render('index');

        $post = Yii::$app->request->post();
        if (isset($post['block-account'])) {

            $status = $post['block-account'];
            if ($status == 'block') {
                $model->DateEnd = date("Y-m-d G:i:s.000", time());
            } elseif ($status == 'active') {
                $model->DateEnd = NULL;
            }
            if (!$model->save()) {
                Yii::getLogger()->log([
                    'model->DateEnd'=>$model->errors
                ], Logger::LEVEL_ERROR, 'binary');
            }
        } elseif (isset($post['block-register'])) {

            $status = $post['block-register'];
            if ($status == 'block') {
                $model->block_register = date("Y-m-d G:i:s.000", time());
            } elseif ($status == 'active') {
                $model->block_register = NULL;
            }

            if (!$model->save()) {
                Yii::getLogger()->log([
                    'model->block_register'=>$model->errors
                ], Logger::LEVEL_ERROR, 'binary');
            }
        } elseif (isset($post['active-gs'])) {

            $status = $post['active-gs'];
            $findModel = NAdUsers::findAll([
                'gs_id' => $id
            ]);
            if ($findModel)
            {
                foreach ($findModel as $modelAdUser) {
                    /** @var NAdUsers $model */
                    if ($status == 'active') {
                        $modelAdUser->auth_ldap_only = 1;
                    } elseif ($status == 'block') {
                        $modelAdUser->auth_ldap_only = 0;
                    }
                    if (!$modelAdUser->save()) {
                        Yii::getLogger()->log([
                            'modelAdUser->auth_ldap_only'=>$modelAdUser->errors
                        ], Logger::LEVEL_ERROR, 'binary');
                    } else {
                        Yii::getLogger()->log([
                            'modelAdUser->auth_ldap_only'=>$modelAdUser->auth_ldap_only
                        ], Logger::LEVEL_WARNING, 'binary');
                    }
                }
            }
        } elseif (isset($post['reset-pass-gd']))
        {
            if ($model->UserType == 9) {
                $model->EmailPassword = $model->Pass;
                self::resetPassword($model);
                if (isset($model->directorFlo)) {
                    $findModel = DirectorFlo::findOne($model->directorFlo->id);
                    $findModel->password = $model->Pass;
                    $findModel->save();
                }
            }
        }

        return $this->render('view', [
            'model' => $model,
            'ad' => $ad
        ]);
    }

    /**
     * @param string $param
     * @return string
     */
    public function actionCreate($param = 'user')
    {
        $model = new AddUserForm();
        $activeSyncHelper = new ActiveSyncHelper();

        switch ($param) {
            case 'user':
                $model->scenario = 'addUser';
                break;
            case 'gd':
                $model->scenario = 'addUserGD';
                break;
            case 'doc':
                $model->scenario = 'addUserDoc';
                break;
            case 'franch':
                $model->scenario = 'addUserFranch';
                break;
        }

        if ($model->load(Yii::$app->request->post()))
        {
            if ($param == 'user') {
                $activeSyncHelper->type = 7;
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->tableName = 'Operators';
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->lastName = trim($model->lastName);
                $activeSyncHelper->firstName = trim($model->firstName);
                $activeSyncHelper->middleName = trim($model->middleName);
                $activeSyncHelper->department = $model->department;
                $activeSyncHelper->operatorofficestatus = trim($model->operatorofficestatus);
            } elseif ($param == 'franch') {
                $activeSyncHelper->type = 8;
                $activeSyncHelper->typeLO = 'FLO';
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->cacheId = $model->key;
                $activeSyncHelper->lastName = trim($model->lastName);
                $activeSyncHelper->firstName = trim($model->firstName);
                $activeSyncHelper->middleName = trim($model->middleName);
                $activeSyncHelper->operatorofficestatus = trim($model->operatorofficestatus);
            } elseif ($param == 'gd') {
                $activeSyncHelper->type = 9;
                $activeSyncHelper->department = 9;
                $activeSyncHelper->typeLO = 'FLO';
                $activeSyncHelper->tableName = 'DirectorFlo';
                $activeSyncHelper->operatorofficestatus = 'Генеральный директор';
                $activeSyncHelper->changeGD = $model->changeGD;
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->emailGD = $model->email;
                $activeSyncHelper->phone = $model->phone;
                $activeSyncHelper->lastName = trim($model->lastName);
                $activeSyncHelper->firstName = trim($model->firstName);
                $activeSyncHelper->middleName = trim($model->middleName);
            } elseif ($param == 'doc') {
                $activeSyncHelper->type = 5;
                $activeSyncHelper->department = 8;
                $activeSyncHelper->tableName = 'Doctors';
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->key = $model->docId;
                $activeSyncHelper->cacheId = $model->docId;
                $activeSyncHelper->specId = $model->specId;
            }

            $activeSyncHelper->fullName = trim($activeSyncHelper->lastName)
                . " " . trim($activeSyncHelper->firstName);

            if (!empty($activeSyncHelper->middleName))
                $activeSyncHelper->fullName .= " " . trim($activeSyncHelper->middleName);

            if (!is_null(Yii::$app->request->post('radioAccountsList')) &&
                !is_null(Yii::$app->request->post('hiddenEmailList')))
            {
                if (Yii::$app->request->post('radioAccountsList') != 'new')
                {
                    $activeSyncHelper->accountName = Yii::$app->request->post('radioAccountsList');

                    $arrEmails = Yii::$app->request->post('hiddenEmailList');

                    if (!empty($activeSyncHelper->accountName)
                        && array_key_exists($activeSyncHelper->accountName, $arrEmails)) {
                        $activeSyncHelper->emailAD = $arrEmails[$activeSyncHelper->accountName];
                    }
                    if (!is_null(Yii::$app->request->post('checkResetPassword'))) {
                        $activeSyncHelper->resetPassword = true;
                    }
                }
            }

            if (!is_null(Yii::$app->request->post('radioAIDList'))) {
                if (Yii::$app->request->post('radioAIDList') == 'new') {
                    $activeSyncHelper->createNewGS = true;
                } else {
                    $activeSyncHelper->aid = strval(Yii::$app->request->post('radioAIDList'));
                }
            }

            //todo добавление УЗ
            $message = '';
            $newUserData = $activeSyncHelper->checkAccount();

            if ($newUserData) {

                Yii::getLogger()->log(
                    'УСПЕШНО БЫЛА ДОБАВЛЕНА УЗ ДЛЯ '.$activeSyncHelper->fullName,
                    Logger::LEVEL_INFO,
                    'ADD_SKYNET_USER');

                $style = '';
                !empty($activeSyncHelper->idAD) ? $auth = 'Active Directory' : $auth = 'GemoSystem';
                $message .= '<p>Aвторизации через '.$auth.'</p>';

                $url = \yii\helpers\Url::toRoute([
                    './logins/view',
                    'id' => $activeSyncHelper->aid,
                    'ad' => $activeSyncHelper->idAD
                ]);
                $url = Html::a($activeSyncHelper->fullName, $url, [
                    'title' => $activeSyncHelper->fullName,
                    'target' => '_blank'
                ]);
                if ($activeSyncHelper->state == 'new') {

                    if ($activeSyncHelper->type == 8) {
                        $urlKey = \yii\helpers\Url::toRoute([
                            './logins/index',
                            'LoginsSearch[Key]' => $activeSyncHelper->key
                        ]);
                        $urlKey = Html::a($activeSyncHelper->key, $urlKey, [
                            'title' => $activeSyncHelper->key,
                            'target' => '_blank'
                        ]);

                        $message .= '<p>Отделение №<b>'.$urlKey.'</b>';
                        if ($modelLogin = Logins::findOne($activeSyncHelper->aid)) {
                            $emailArr = [];
                            if (isset($modelLogin->directorInfo->fullName)) {
                                $message .= ', директор '.$modelLogin->directorInfo->fullName;
                            }
                            if (isset($modelLogin->Email)) {
                                $emailArr[] =  $modelLogin->Email;
                            }
                            if (isset($modelLogin->directorInfo->email)) {
                                $emailArr[] =  $modelLogin->directorInfo->email;
                            }

                            if (isset($emailArr) && is_array($emailArr)) {
                                $email = implode(';', $emailArr);
                                $message .= ' (почта <b>'.Html::mailto($email).'</b>)';
                            }
                        }
                        $message .= '</p><br>';
                    }

                    !empty($activeSyncHelper->idAD) ? $style = 'info' : $style = 'success';
                    $message .= '<p>Успешно добавлена УЗ для <b>'.$url.'</b></p>';

                } elseif ($activeSyncHelper->state == 'old') {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$url.'</b> уже есть учетная запись!</p>';
                }
                $message .= '<p>Данные для входа в ';
                $message .= Html::a('GemoSystem (https://office.gemotest.ru):', 'https://office.gemotest.ru', [
                    'title' => 'https://office.gemotest.ru',
                    'target' => '_blank'
                ]);
                $message .= '</p>';
                $message .= '<br>Логин: <b>' . $activeSyncHelper->login.'</b>';
                $message .= '<br>Пароль: <b>' . $activeSyncHelper->password.'</b>';
                Yii::$app->session->setFlash($style, $message);
            } else {
                $style = 'error';
                Yii::getLogger()->log([
                    'ВОЗНИКЛА ОШИБКА ПРИ ДОБАВЛЕНИИ УЗ ДЛЯ '.$activeSyncHelper->fullName
                ], Logger::LEVEL_ERROR, 'binary');

                $message = '<p>Не удалось создать УЗ для <b>'.$activeSyncHelper->fullName.'</b> авторизации в GemoSystem</p>';
            }

            if (isset($activeSyncHelper->message["success"])
                && is_array($activeSyncHelper->message["success"])) {
                $successMsg = implode("<br>", $activeSyncHelper->message["success"]);
                $message = "<div style='color: #0a0a0a' class=\"well well-sm\">{$successMsg}</div>" . $message;
            }

            Yii::$app->session->setFlash($style, $message);
        }

        return $this->render('create', [
            'model' => $model,
            'action' => $param
        ]);
    }

    /**
     * Updates an existing Logins model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @param $ad
     */
    public function actionUpdate($id, $ad = '')
    {
        $model = $this->findModel($id, $ad);

        if ($modelDirector = $model->directorInfo) {
            if ($modelDirector->load(Yii::$app->request->post())) {

                if ($modelDirector->getOldAttribute('password')
                    != $modelDirector['password']){
                    self::resetPassword($model);
                }

                /** @var $transaction Transaction */
                $connection = 'GemoTestDB';
                $db = Yii::$app->$connection;
                $transaction = $db->beginTransaction();

                try {
                    if (!$modelDirector->save()) {
                        Yii::getLogger()->log([
                            '$model->directorInfo->save' => $modelDirector->errors
                        ], Logger::LEVEL_ERROR, 'binary');
                    }

                    $directorID = $modelDirector->id;
                    $db->createCommand()->delete(
                        DirectorFloSender::tableName(),
                        ['director_id' => $directorID]
                    )->execute();

                    if (isset(Yii::$app->request->post()['sendersKeys'])
                        && is_array(Yii::$app->request->post()['sendersKeys'])
                    ) {
                        $rowInsert = [];
                        $sendersKeys = Yii::$app->request->post()['sendersKeys'];

                        foreach ($sendersKeys as $key) {
                            $rowInsert[] = [$directorID, $key];
                        }
                        $db->createCommand()->batchInsert(
                            DirectorFloSender::tableName(),
                            ['director_id', 'sender_key'],
                            $rowInsert
                        )->execute();
                    }
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::getLogger()->log([
                        'DirectorFloSender->batchInsert' => $e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if ($adUsers = $model->adUsers)
            {
                if (!$adUsers->load(Yii::$app->request->post()) || !$adUsers->save())
                {
                    Yii::getLogger()->log([
                        '!$adUsers->save()' => $adUsers->errors
                    ], Logger::LEVEL_ERROR, 'binary');
                }

                if ($adUserAccountsOne = $model->adUsers->adUserAccounts)
                {
                    if ($adUserAccountsOne->load(Yii::$app->request->post()))
                    {
                        if ($adUserAccountsOne->getOldAttribute('ad_pass')
                            != $adUserAccountsOne['ad_pass'])
                        {
                            $status = 'error';
                            $message = 'Не удалось сбросить пароль для УЗ <b>'.$adUsers->AD_login.'</b>, возможно AD запись удалена!';

                            if ($adPass = ActiveSyncHelper::resetPasswordAD(
                                $adUsers->AD_login,
                                $adUserAccountsOne['ad_pass'])
                            ) {
                                $status = 'success';
                                $message = 'Успешно был сброшен пароль для УЗ <b>'.$adUsers->AD_login.'</b>';
                                $adUserAccountsOne->ad_pass = $adPass;

                                if (!$adUserAccountsOne->save()) {
                                    Yii::getLogger()->log([
                                        '$adUserAccountsOne->save()' => $adUserAccountsOne->errors
                                    ], Logger::LEVEL_ERROR, 'binary');
                                }
                            }
                            Yii::$app->session->setFlash($status, $message);
                        }
                    }
                }
            }

            return $this->redirect([
                'view',
                'id' => $model->aid,
                'ad' => $ad
            ]);
        }
        return $this->render('update', [
            'model' => $model,
            'ad' => $ad
        ]);
    }

    /**
     * @param $model Logins
     */
    private static function resetPassword($model)
    {
        $style = 'error';
        $message = 'Не удалось сбросить пароль для почты <b>'.$model->Email.'</b>';
        if (isset($model->Login)
            && isset($model->EmailPassword)
            && isset($model->Email)
        ) {
            if (ActiveSyncHelper::createResetPasswordGD(
                $model->Login,
                $model->EmailPassword)
            ) {
                $style = 'success';
                $message = 'Успешно был сброшен пароль на "'.$model->EmailPassword.'" для почты <b>'.$model->Email.'</b>';
            }
        }
        Yii::$app->session->setFlash($style, $message);
    }

    /**
     * Finds the Logins model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @param string $idAD
     * @return Logins the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $idAD = null)
    {
        if (($model = Logins::findOne(['aid' => $id])) !== null) {
            $model->idAD = $idAD;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $type
     * @param null $gd_id
     * @param null $doc_id
     * @param null $last_name
     * @param null $first_name
     * @param null $middle_name
     * @return array|string
     */
    public function actionAjaxForActive(
        $type = null,
        $gd_id = null,
        $doc_id = null,
        $last_name = null,
        $first_name = null,
        $middle_name = null
    ) {

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        //todo проверяем существует ли УЗ
        $arrAccountAD = [];
        $activeSyncHelper = new ActiveSyncHelper();

        if ($type == 'user') {
            $activeSyncHelper->type = 7;
        } elseif ($type == 'franch') {
            $activeSyncHelper->type = 8;
        }

        if (!empty($doc_id)) {
            $doctorModel = Doctors::findOne([
                'CACHE_DocID' => $doc_id,
                'Is_Cons' => '4'
            ]);

            if (!$doctorModel) return 'null';

            $activeSyncHelper->lastName = $doctorModel->LastName;
            $expName = explode(" ", $doctorModel->Name);
            $activeSyncHelper->firstName = $expName[0];
            if (!empty($expName[1])) $activeSyncHelper->middleName = $expName[1];
        } else {
            $activeSyncHelper->lastName = $last_name;
            $activeSyncHelper->firstName = $first_name;
            $activeSyncHelper->middleName = $middle_name;
        }

        if (!empty(trim($activeSyncHelper->lastName))) {
            $activeSyncHelper->fullName = trim($activeSyncHelper->lastName);
        } else return 'null';

        if (!empty(trim($activeSyncHelper->firstName))) {
            $activeSyncHelper->fullName .= " " . trim($activeSyncHelper->firstName);
        }

        if (!empty(trim($activeSyncHelper->middleName))) {
            $activeSyncHelper->fullName .= " " . trim($activeSyncHelper->middleName);
        }

        //todo проверяем существует ли пользователь с ФИО в AD
        if ($arr = $activeSyncHelper->checkUserNameAd()) {
            $arrAccountAD['ad'] = $arr;
        }

        //todo проверяем существует ли пользователь с ФИО в GS
        if ($objectUsersLogins = $activeSyncHelper->checkLoginAccountAll()) {
            $arrAccountAD['gs'] = ArrayHelper::toArray($objectUsersLogins);
        }

        //todo проверяем существует ли пользователь с ФИО в GD
        if (!empty($gd_id)) {
            if ($checkGD = DirectorFloSender::checkGD($gd_id)) {
                $arrAccountAD = array_merge($arrAccountAD, $checkGD);
            }
        }

        if (!$arrAccountAD || !is_array($arrAccountAD)) {
            return 'null';
        } else {
            return $arrAccountAD;
        }
    }

    static function getFullName() {

    }

    /**
     * @param null $search
     * @param null $id
     * @return array|string
     */
    public function actionAjaxUserDataList($search = null, $id = null)
    {
        $out = ['more' => false];

        if (!is_null($search)) {
            $search = mb_strtolower($search, 'UTF-8');
            $data = Logins::find()->select(['aid, [Name]'])
                ->where('lower(Name) LIKE \'%' . $search . '%\'')
                ->andWhere('UserType in (7,5,8)')
                ->limit(20)
                ->all();
            /** @var Logins $userData */
            foreach ($data as $userData) {
                $out['results'][] = ['id' => $userData->aid, 'text' => $userData->Name.' '];
            }
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Logins::findOne($id)->Name];
        }

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @param null $search
     * @param null $id
     * @return array|string
     */
    public function actionAjaxZaborList($search = null, $id = null)
    {
        $out = ['more' => false];

        if (!is_null($search)) {
            $search = mb_strtolower($search, 'UTF-8');
            $data = HrPublicEmployee::find()
                ->select(['last_name', 'first_name', 'middle_name', 'guid', 'personnel_number'])
                ->distinct(true)
                ->where('lower(last_name + \' \' + first_name + \' \' + middle_name) LIKE \'%' . $search . '%\'')
                ->andWhere(['is not', 'guid', null])
                ->andWhere(['!=', 'guid', ''])
                ->andWhere(['fired_date' => ''])
                //->andWhere(['!=', 'hiring_date', ''])
                ->andWhere(['not in', 'type_contract', ['3']])
                ->orderBy(['last_name' =>'acs'])
                ->limit(20)
                ->all();
            /** @var HrPublicEmployee $userData */
            foreach ($data as $userData) {
                $out['results'][] = ['id' => $userData->guid, 'text' => self::addName($userData)];
            }
        } elseif (isset($id)) {
            if ($findModel = HrPublicEmployee::findOne(['guid' => $id])) {
                $out['results'] = ['id' => $id, 'text' => self::addName($findModel)];
            }
        }

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @param $model HrPublicEmployee
     * @return string
     */
    public static function addName($model) {
        if (!$model) return '';
        $name = "";
        $name .= isset($model->last_name) ? ' '.$model->last_name : '';
        $name .= isset($model->first_name) ? ' '.$model->first_name : '';
        $name .= isset($model->middle_name) ? ' '.$model->middle_name : '';
        $name .= isset($model->personnel_number) ? ' (персон. № '.$model->personnel_number.')' : '';
        return $name;
    }

    /**
     * @param bool $department
     * @return null
     */
    public function actionAjaxListName($department = false)
    {
        $out['erp_groups'] = null;
        $out['erp_nurse'] = false;

        if (!is_null($department)) {

            if ($data = Permissions::find()->where(['department' => $department])->all()) {
                /** @var Permissions $userData */
                foreach ($data as $userData) {
                    $out['permission'][] = [
                        'id' => $userData->permission,
                        'text' => $userData->name->description
                    ];
                }
            }

            /** @var ErpGroupsRelations $data */
            if ($data = ErpGroupsRelations::findOne(['department' => $department])) {
                $out['erp_groups'] = $data->group;
                $out['erp_nurse'] = !empty($data->nurse) ? true : false;
                $out['mis_access'] = json_encode($data->mis_access);
            }
        }

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @param bool $department
     * @return null
     */
    public function actionAjaxPermissions($department = false)
    {
        if (!is_null($department)) {
            $data = Permissions::find()
                ->where(['department'=>$department])
                ->all();
            if ($data) {
                /** @var Permissions $userData */
                foreach ($data as $userData) {
                    $out['result'][$userData->permission] = $userData->name->description;
                }
            }
        }
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @param bool $type
     * @return array|string
     */
    public function actionAjaxStructureType($type = false)
    {
        $out = [];
        $activeSync = new ActiveSyncHelper();
        $conf = $activeSync->getConf();

        if (!empty($conf['structure'][$type])){
            $out = $conf['structure'][$type];
        }
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @param null $type
     * @return mixed
     */
    public function actionAjaxListTable($type = null)
    {
        $out = [];
        $activeSync = new ActiveSyncHelper();
        $conf = $activeSync->getConf();

        if (!empty($conf['structure'][$type]))
        {
            $modules = $conf['structure'][$type];
            foreach ($modules as $nameModule => $module)
            {
                $tableFields = [];
                foreach ($module as $tableClass)
                {
                    if ($tblField = ActiveSyncHelper::getTableFields($tableClass)) {
                        $tableFields[ActiveSyncHelper::parseClassPath($tableClass)] = $tblField;
                    }
                }
                $out['result'][$nameModule] = $tableFields;
            }
        }
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }
}