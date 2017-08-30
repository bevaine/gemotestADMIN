<?php

namespace app\modules\admin\controllers;

use Yii;
use common\models\AddUserForm;
use common\models\Logins;
use common\models\LoginsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\components\helpers\ActiveSyncHelper;

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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AddUserForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AddUserForm();
        $model->scenario = 'addUser';
        if ($model->load(Yii::$app->request->post()))
        {
            $activeSyncHelper = new ActiveSyncHelper();
            $activeSyncHelper->nurse = $model->nurse;
            $activeSyncHelper->department = $model->department;
            $activeSyncHelper->lastName = $model->lastName;
            $activeSyncHelper->firstName = $model->firstName;
            $activeSyncHelper->middleName = $model->middleName;
            $activeSyncHelper->fullName = $activeSyncHelper->lastName . " " . $activeSyncHelper->firstName . " " . $activeSyncHelper->middleName;
            $activeSyncHelper->operatorofficestatus = $model->operatorofficestatus;
            //todo если УЗ AD существует
            $newUserData = $activeSyncHelper->checkAccount();
            if (!$newUserData) {
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
                    //todo если находим то сбрасываем пароль в AD
                    $newPasswordAd = $activeSyncHelper->resetPasswordAD($activeSyncHelper->accountName);
                    if ($newPasswordAd && !empty($activeSyncHelper->accountName)) {
                        $activeSyncHelper->passwordAD = $newPasswordAd;
                        $newUserData['login'] = $activeSyncHelper->accountName;
                        $newUserData['password'] = $activeSyncHelper->passwordAD;
                        $newUserData['state'] = 'new';
                    }
                } else {
                    $addNewUser = $activeSyncHelper->addUserAD();
                    if (!$addNewUser) {
                        $message = '<p>Не удалось создать УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
                        Yii::$app->session->setFlash('error', $message);
                    }
                }
                $newUserData = $activeSyncHelper->addNewUser();
            }
            if ($newUserData) {
                if ($newUserData['state'] == 'new') {
                    $style = 'success';
                    $message = '<p>Успешно добавлена УЗ для <b>'.$activeSyncHelper->fullName.'</b> в GemoSystem</p>';
                } else {
                    $style = 'warning';
                    $message = '<p>У пользователя <b>'.$activeSyncHelper->fullName.'</b> уже есть УЗ в GemoSystem</p>';
                }
                $message .= '<p>Данные для входа в GomoSystem:<p>';
                $message .= '<br>Логин: '.$newUserData['login'];
                $message .= '<br>Пароль: '.$newUserData['password'];
                Yii::$app->session->setFlash($style, $message);
            }
        }
        return $this->render('create', [
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'aid' => $model->aid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Logins model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logins model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Logins the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logins::findOne(['aid' => $id])) !== null) {
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

        $checkAccount = $activeSyncHelper->checkAccount();
        if ($checkAccount) {echo 'null'; exit;};

        if (!in_array($activeSyncHelper->department, [4, 5]))
        {
            //todo проверяем существует ли пользователь с ФИО в AD
            $arrAccountAD = $activeSyncHelper->checkUserNameAd();

            if (!$arrAccountAD || !is_array($arrAccountAD)) {echo 'null'; exit;};

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
        else echo 'null';
    }
}
