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
     * Displays a single Logins model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id, $ad = null)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $ad),
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
                $activeSyncHelper->key = $model->key;
                $activeSyncHelper->type = $model->type;
                $activeSyncHelper->nurse = $model->nurse;
                $activeSyncHelper->lastName = $model->lastName;
                $activeSyncHelper->firstName = $model->firstName;
                $activeSyncHelper->middleName = $model->middleName;
                $activeSyncHelper->department = $model->department;
                $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            } elseif ($param == 'franch') {
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
                !is_null(Yii::$app->request->post('hiddenEmailList'))
            ) {
                $activeSyncHelper->accountName = Yii::$app->request->post('radioAccountsList');
                $arrEmails = Yii::$app->request->post('hiddenEmailList');
                if (!empty($activeSyncHelper->accountName)
                    && array_key_exists($activeSyncHelper->accountName, $arrEmails)
                ) {
                    $activeSyncHelper->emailAD = $arrEmails[$activeSyncHelper->accountName];
                }
            }

            //todo добавление УЗ
            $newUserData = $activeSyncHelper->checkAccount();
            if ($newUserData) {
                if ($newUserData['state'] == 'new') {
                    $style = 'success';
                    $message = '<p>Успешно добавлена УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
                } else {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$activeSyncHelper->fullName.'</b> уже есть УЗ для авторизации через AD</p>';
                }
                if (!empty($newUserData['login'] && $newUserData['password'])) {
                    $message .= '<p>Данные для входа в GomoSystem:<p>';
                    $message .= '<br>Логин: ' . $newUserData['login'];
                    $message .= '<br>Пароль: ' . $newUserData['password'];
                }
                Yii::$app->session->setFlash($style, $message);
            } else {
                $message = '<p>Не удалось создать УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
                Yii::$app->session->setFlash('error', $message);
            }

        }
        print_r($model->errors);

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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $adUsersLogins = $model->adUsers;
            if ($adUsersLogins) {
                if ($adUsersLogins->load(Yii::$app->request->post())) {
                    $adUsersLogins->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->aid]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $action
     * @return string
     */
    public function actionBlockAccount($id, $action)
    {
        $model = $this->findModel($id);

        if ($model) {
            if ($action == 'block') {
                $model->DateEnd = date("Y-m-d G:i:s:000", time());
            } elseif ($action == 'active') {
                $model->DateEnd = NULL;
            }
            if (!$model->save()) {
                Yii::getLogger()->log(['$model->DateEnd'=>$model->errors], 1, 'binary');
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     * @param $action
     * @return string
     */
    public function actionBlockRegister($id, $action)
    {
        $model = $this->findModel($id);

        if ($model) {
            if ($action == 'block') {
                $model->block_register = date("Y-m-d G:i:s:000", time());
            } elseif ($action == 'active') {
                $model->block_register = NULL;
            }
            if (!$model->save()) {
                Yii::getLogger()->log(['$model->block_register'=>$model->errors], 1, 'binary');
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
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
     * @param $department
     * @param $last_name
     * @param $first_name
     * @param $middle_name
     */
    public function actionAjaxForActive($department, $last_name, $first_name, $middle_name)
    {
        //todo проверяем существует ли УЗ
        $return = [];
        $activeSyncHelper = new ActiveSyncHelper();
        $activeSyncHelper->department = $department;
        $activeSyncHelper->lastName = $last_name;
        $activeSyncHelper->firstName = $first_name;
        $activeSyncHelper->middleName = $middle_name;
        $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;

        //$checkAccount = $activeSyncHelper->checkAccount();
        //if ($checkAccount) exit('null');

        if (!in_array($activeSyncHelper->department, [4, 5]))
        {
            //todo проверяем существует ли пользователь с ФИО в AD
            $arrAccountAD = $activeSyncHelper->checkUserNameAd();

            if (!$arrAccountAD || !is_array($arrAccountAD)) exit('null');

            foreach ($arrAccountAD as $arrAccount) {
                if (!array_key_exists('SamAccountName', $arrAccount) ||
                    !array_key_exists('UserPrincipalName', $arrAccount)) continue;
                $return[] = [
                    'account' => $arrAccount['SamAccountName'],
                    'email' => $arrAccount['UserPrincipalName'],
                ];
            }
        }
        if (!empty($return)) echo Json::encode($return);
        else exit('null');
    }
}