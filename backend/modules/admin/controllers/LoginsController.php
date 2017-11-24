<?php

namespace app\modules\admin\controllers;

use common\models\SprDoctorSpec;
use Yii;
use common\models\AddOrgForm;
use common\models\AddUserForm;
use common\models\Logins;
use common\models\LoginsSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\components\helpers\ActiveSyncHelper;
use common\models\NAdUsers;
use common\models\Doctors;
use yii\helpers\Html;

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
     * @param $id
     * @param string $ad
     * @param string $action
     * @param string $status
     * @return string
     */
    public function actionView($id, $ad = '', $action = '', $status = '')
    {
        $model = $this->findModel($id, $ad);

        switch ($action) {
            case 'block-account':
                if ($status == 'block') {
                    $model->DateEnd = date("Y-m-d G:i:s:000", time());
                } elseif ($status == 'active') {
                    $model->DateEnd = NULL;
                }
                if (!$model->save()) {
                    Yii::getLogger()->log(['$model->DateEnd'=>$model->errors], 1, 'binary');
                }
                break;
            case 'block-register':
                if ($status == 'block') {
                    $model->block_register = date("Y-m-d G:i:s:000", time());
                } elseif ($status == 'active') {
                    $model->block_register = NULL;
                }
                if (!$model->save()) {
                    Yii::getLogger()->log(['$model->block_register'=>$model->errors], 1, 'binary');
                }
                break;
            case 'active-gs':
                if ($model->adUsers) {
                    $modelAdUser = $model->adUsers;
                    if ($status == 'block') {
                        $modelAdUser->auth_ldap_only = 1;
                    } elseif ($status == 'active') {
                        $modelAdUser->auth_ldap_only = 0;
                    }
                    if ($modelAdUser->save()) {
                        Yii::getLogger()->log(['$modelAdUser->auth_ldap_only'=>$modelAdUser->errors], 1, 'binary');
                    }
                    $modelAdUser->save();
                }
                break;
        }

        return $this->render('view', [
            'model' => $model,
            'ad' => $ad
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateOrg ()
    {
        $model = new AddUserForm();
        $model->scenario = 'addUserOrg';

        if ($model->load(Yii::$app->request->post())) {

        }
        return $this->render('createOrg', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateDoc ()
    {
        $model = new AddUserForm();
        $model->scenario = 'addUserDoc';

        if ($model->load(Yii::$app->request->post())) {
            $activeSyncHelper = new ActiveSyncHelper();
            $activeSyncHelper->docId = $model->docId;
            $activeSyncHelper->specId = $model->specId;
        }

        return $this->render('createDoc', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateFranch ()
    {
        $model = new AddUserForm();
        $model->scenario = 'addUserFranch';

        if ($model->load(Yii::$app->request->post())) {
            $activeSyncHelper = new ActiveSyncHelper();
            $activeSyncHelper->key = $model->key;
            $activeSyncHelper->type = 8;
            $activeSyncHelper->nurse = $model->nurse;
            $activeSyncHelper->lastName = $model->lastName;
            $activeSyncHelper->firstName = $model->firstName;
            $activeSyncHelper->middleName = $model->middleName;
            $activeSyncHelper->department = $model->department;
            $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;
        }

        return $this->render('createFranch', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionCreateNAdUsers()
    {
        $model = new NAdUsers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                Yii::getLogger()->log(['$model'=>$model->errors], 1, 'binary');
            }
        } else {
            Yii::getLogger()->log(['$model'=>$model->errors], 1, 'binary');
        }

        return $this->render('createNAdUsers', [
            'model' => $model,
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
            case 'org':
                $model->scenario = 'addUserOrg';
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
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->nurse = $model->nurse;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
                $activeSyncHelper->department = $model->department;
                $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            } elseif ($param == 'franch') {
                $activeSyncHelper->type = 8;
                $activeSyncHelper->typeLO = 'FLO';
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->cacheId = $model->key;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
                $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            } elseif ($param == 'org') {
                $activeSyncHelper->type = 3;
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
            } elseif ($param == 'doc') {
                $activeSyncHelper->type = 5;
                $activeSyncHelper->department = 8;
                $activeSyncHelper->key = $model->docId;
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->cacheId = $model->docId;
                $activeSyncHelper->specId = $model->specId;
            }

            $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;

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
                }
            }

            if (in_array($activeSyncHelper->department, [21, 22])) $activeSyncHelper->department = 2;
            if (in_array($activeSyncHelper->department, [31, 32, 33])) $activeSyncHelper->department = 3;
            //if ($this->department == 0) $this->nurse = 1;

            //todo добавление УЗ
            $newUserData = $activeSyncHelper->checkAccount();

            if ($newUserData) {
                $message = '';
                $style = '';
                $url = \yii\helpers\Url::toRoute([
                    './logins/view',
                    'id' => $newUserData['aid'],
                    'ad' => $newUserData['adID']
                ]);
                $url = Html::a($activeSyncHelper->fullName, $url, ['title' => $activeSyncHelper->fullName, 'target' => '_blank']);

                if ($activeSyncHelper->state == 'new') {
                    $style = 'success';
                    $message = '<p>Успешно добавлена УЗ для <b>'.$url.'</b> в GemoSystem</p>';
                } elseif ($activeSyncHelper->state == 'old') {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$url.'</b> уже есть УЗ для авторизации через AD</p>';
                }
                if (!empty($newUserData['login'] && $newUserData['password'])) {
                    $message .= '<p>Данные для входа в GemoSystem:<p>';
                    $message .= '<br>Логин: ' . $newUserData['login'];
                    $message .= '<br>Пароль: ' . $newUserData['password'];
                }
                Yii::$app->session->setFlash($style, $message);
            } else {
                $message = '<p>Не удалось создать УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
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
     * @param $last_name
     * @param $first_name
     * @param $middle_name
     * @param $doc_id
     */
    public function actionAjaxForActive($doc_id = null, $last_name = null, $first_name = null, $middle_name = null)
    {
        //todo проверяем существует ли УЗ
        $activeSyncHelper = new ActiveSyncHelper();

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

        $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;

        //todo проверяем существует ли пользователь с ФИО в AD
        $arrAccountAD = $activeSyncHelper->checkUserNameAd();
//
//        if (is_array($arrAccountAD) && count($arrAccountAD) > 1) {
//            $arrAccounts = ArrayHelper::getColumn($arrAccountAD, 'account');
//        }

        if (!$arrAccountAD || !is_array($arrAccountAD)){
            exit('null') ;
        } else {
            echo Json::encode($arrAccountAD);
        }
    }
}