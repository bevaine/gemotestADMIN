<?php

namespace app\modules\admin\controllers;

use common\models\NAdUseraccounts;
use common\models\NAdUsers;
use common\models\NAuthItem;
use common\models\Permissions;
use PHPUnit\Exception;
use Yii;
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
        $action = '';
        $arrRows = [];
        $rowInsert = [];
        if ($department == 7) $department = '';

        if (!empty(Yii::$app->request->post()['Permissions'])) {
            $request = Yii::$app->request->post()['Permissions'];

            if (!empty($request['action']) && isset($request['department'])) {
                $action = $request['action'];
                $department = $request['department'];
            }

            if ($action == 'assign'
                && !empty($request['list-permission'])
                && is_array($request['list-permission'])
                && $request['department'] != 7)
            {
                foreach ($request['list-permission'] as $permission) {
                    $rowInsert[] = [$department, $permission];
                    $arrRows[] = $permission;
                }
                try {
                    Permissions::deleteAll([
                        'AND',
                        ['department' => $department],
                        ['in','permission',$arrRows]
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

            } elseif ($action == 'revoke'
                && !empty($request['permission'])
                && is_array($request['permission']))
            {
                foreach ($request['permission'] as $permission) {
                    $arrRows[] = $permission;
                }
                try {
                    Permissions::deleteAll([
                        'AND',
                        ['department' => $department],
                        ['in','permission',$arrRows]
                    ]);
                } catch (Exception $e) {
                    Yii::getLogger()->log([
                        'addPermissions->batchInsert'=>$e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
            }
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
        $model = $this->findModel($id, $ad);

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
            if ($model->adUsers) {
                $modelAdUser = $model->adUsers;
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
        } elseif (isset($post['reset-pass-gd'])) {

            if ($model->UserType == 9) {
                $model->EmailPassword = $model->Pass;
                self::resetPassword($model);
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
                $activeSyncHelper->tableName = 'Doctors';
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->department = 8;
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
                    $activeSyncHelper->aid = Yii::$app->request->post('radioAIDList');
                }
            }

            if (in_array($activeSyncHelper->department, [10])) {
                $activeSyncHelper->department = 0;
                $activeSyncHelper->nurse = 1;
            }

            if (in_array($activeSyncHelper->department, [21, 22]))
                $activeSyncHelper->department = 2;

            if (in_array($activeSyncHelper->department, [31, 32, 33]))
                $activeSyncHelper->department = 3;

            //todo добавление УЗ
            $newUserData = $activeSyncHelper->checkAccount();

            if ($newUserData) {

                Yii::getLogger()->log([
                    'УСПЕШНО БЫЛА ДОБАВЛЕНА УЗ ДЛЯ '.$activeSyncHelper->fullName
                ], Logger::LEVEL_WARNING, 'binary');

                $style = '';
                $message = '';
                !empty($activeSyncHelper->idAD) ? $auth = 'AD' : $auth = 'GemoSystem';

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
                    !empty($activeSyncHelper->idAD) ? $style = 'info' : $style = 'success';
                    $message = '<p>Успешно добавлена УЗ для <b>'.$url.'</b>';
                    if ($activeSyncHelper->type == 8) {
                        $urlKey = \yii\helpers\Url::toRoute([
                            './logins/index',
                            'LoginsSearch[Key]' => $activeSyncHelper->key
                        ]);
                        $urlKey = Html::a($activeSyncHelper->key, $urlKey, [
                            'title' => $activeSyncHelper->key,
                            'target' => '_blank'
                        ]);
                        $message .= ' (отделение '.$urlKey.')';
                    }
                    $message .= ' авторизации через '.$auth.'</p>';
                } elseif ($activeSyncHelper->state == 'old') {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$url.'</b> уже есть УЗ для авторизации через '.$auth.'</p>';
                }
                $message .= '<p>Данные для входа в ';
                $message .= Html::a('GemoSystem (https://office.gemotest.ru)', 'https://office.gemotest.ru', [
                    'title' => 'https://office.gemotest.ru',
                    'target' => '_blank'
                ]);
                $message .= '<p>';
                $message .= '<br>Логин: <b>' . $activeSyncHelper->login.'</b>';
                $message .= '<br>Пароль: <b>' . $activeSyncHelper->password.'</b>';
                Yii::$app->session->setFlash($style, $message);
            } else {
                Yii::getLogger()->log([
                    'ВОЗНИКЛА ОШИБКА ПРИ ДОБАВЛЕНИИ УЗ ДЛЯ '.$activeSyncHelper->fullName
                ], Logger::LEVEL_ERROR, 'binary');

                $message = '<p>Не удалось создать УЗ для <b>'.$activeSyncHelper->fullName.'</b> авторизации в GemoSystem</p>';
                Yii::$app->session->setFlash('error', $message);
            }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            /** @var $transaction Transaction */
            $connection = 'GemoTestDB';
            $db = Yii::$app->$connection;
            $transaction = $db->beginTransaction();
            try {
                if (isset($model->directorInfo)) {
                    $modelDirector = $model->directorInfo;
                    $modelDirector->password = $model->Pass;

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
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::getLogger()->log([
                    'DirectorFloSender->batchInsert' => $e->getMessage()
                ], Logger::LEVEL_ERROR, 'binary');
            }

            if (!empty(trim($model->EmailPassword))) {
                self::resetPassword($model);
            }
            if ($adUsersLogins = $model->adUsers) {
                if ($adUsersLogins->load(Yii::$app->request->post())) {
                    $adUsersLogins->save();
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
     */
    public function actionAjaxForActive(
        $type = null,
        $gd_id = null,
        $doc_id = null,
        $last_name = null,
        $first_name = null,
        $middle_name = null
    ) {
        //todo проверяем существует ли УЗ
        $arrAccountAD = [];
        $activeSyncHelper = new ActiveSyncHelper();

        if ($type == 'user') {
            $activeSyncHelper->type = 7;
        }

        if (!empty($doc_id)) {
            $doctorModel = Doctors::findOne([
                'CACHE_DocID' => $doc_id,
                'Is_Cons' => '4'
            ]);

            if (!$doctorModel) exit('null') ;

            $activeSyncHelper->lastName = $doctorModel->LastName;
            $expName = explode(" ", $doctorModel->Name);
            $activeSyncHelper->firstName = $expName[0];
            if (!empty($expName[1])) $activeSyncHelper->middleName = $expName[1];
        } else {
            $activeSyncHelper->lastName = $last_name;
            $activeSyncHelper->firstName = $first_name;
            $activeSyncHelper->middleName = $middle_name;
        }

        $activeSyncHelper->fullName = $activeSyncHelper->lastName
            . " " . $activeSyncHelper->firstName
            . " " . $activeSyncHelper->middleName;

        //todo проверяем существует ли пользователь с ФИО в AD
        if ($arr = $activeSyncHelper->checkUserNameAd()) {
            $arrAccountAD['ad'] = $arr;
        }

        //todo проверяем существует ли пользователь с ФИО в GS
        if ($objectUsersLogins = $activeSyncHelper->checkLoginAccountAll()) {
            $arrAccountAD['gs'] = ArrayHelper::toArray($objectUsersLogins);
        }

        //todo проверяем существует ли пользователь с ФИО в GS
        if (!empty($gd_id)) {
            if ($checkGD = self::checkGD($gd_id)) {
                $arrAccountAD = array_merge($arrAccountAD, $checkGD);
            }
        }

        if (!$arrAccountAD || !is_array($arrAccountAD)){
            exit('null') ;
        } else {
            echo Json::encode($arrAccountAD);
        }
    }

    /**
     * @param null $key
     * @return null
     */
    public static function checkGD($key = null)
    {
        $out = null;

        if (!is_null($key)) {
            $findModel = DirectorFloSender::findOne([
                'sender_key' => $key
            ]);

            if (isset($findModel->directorFlo)) {
                $fullName = $findModel->directorFlo->last_name
                    ." ".$findModel->directorFlo->first_name
                    ." ".$findModel->directorFlo->middle_name;
                $out['gd'] = $fullName;
            }
        }
        return $out;
    }

    /**
     * @param null $search
     * @param null $id
     */
    public function actionAjaxUserDataList($search = null, $id = null)
    {
        $out = ['more' => false];

        if (!is_null($search)) {
            $search = mb_strtolower($search, 'UTF-8');
            $data = Logins::find()->select(['aid, [Name]'])
                ->where('lower(Name) LIKE \'%' . $search . '%\'')
                ->andWhere('UserType in (7,5)')
                ->limit(20)
                ->all();
            /** @var Logins $userData */
            foreach ($data as $userData) {
                $out['results'][] = ['id' => $userData->aid, 'text' => $userData->Name];
            }
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Logins::findOne($id)->Name];
        }

        echo Json::encode($out);
    }

    /**
     * @param bool $department
     */
    public function actionAjaxListName($department = false)
    {
        $out = null;

        if (!is_null($department)) {
            $data = Permissions::find()
                ->where(['department' => $department])
                ->all();
            if ($data) {
                /** @var Permissions $userData */
                foreach ($data as $userData) {
                    $out['results'][] = ['id' => $userData->permission, 'text' => $userData->name->description];
                }
            }
        }

        echo Json::encode($out);
    }
}