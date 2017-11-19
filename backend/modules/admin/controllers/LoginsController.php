<?php

namespace app\modules\admin\controllers;

use Yii;
use common\models\AddOrgForm;
use common\models\AddUserForm;
use common\models\Logins;
use common\models\LoginsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\components\helpers\ActiveSyncHelper;
use common\models\NAdUsers;

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
     * Creates a new AddUserForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($param)
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

        //print_r($activeSyncHelper);

        if ($model->load(Yii::$app->request->post()))
        {
            if ($param == 'user') {
                $activeSyncHelper->typeLO = 'SLO';
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->type = $model->type;
                $activeSyncHelper->nurse = $model->nurse;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
                $activeSyncHelper->department = $model->department;
                $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            } elseif ($param == 'franch') {
                $activeSyncHelper->typeLO = 'FLO';
                $activeSyncHelper->type = 8;
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
            } elseif ($param == 'org') {
                $activeSyncHelper->type = 3;
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
            } elseif ($param == 'doc') {
                $activeSyncHelper->type = 4;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
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

            //todo добавление УЗ
            $newUserData = $activeSyncHelper->checkAccount();

            if ($newUserData) {
                $message = '';
                $style = '';
                if ($activeSyncHelper->state == 'new') {
                    $style = 'success';
                    $message = '<p>Успешно добавлена УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
                } elseif ($activeSyncHelper->state == 'old') {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$activeSyncHelper->fullName.'</b> уже есть УЗ для авторизации через AD</p>';
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
     */
    public function actionAjaxForActive($last_name, $first_name, $middle_name)
    {
        //todo проверяем существует ли УЗ
        $activeSyncHelper = new ActiveSyncHelper();
        $activeSyncHelper->lastName = $last_name;
        $activeSyncHelper->firstName = $first_name;
        $activeSyncHelper->middleName = $middle_name;
        $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;

        //todo проверяем существует ли пользователь с ФИО в AD
        $arrAccountAD = $activeSyncHelper->checkUserNameAd();

        if (!$arrAccountAD || !is_array($arrAccountAD)){
            exit('null') ;
        } else {
            echo Json::encode($arrAccountAD);
        }
    }
}