<?php

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsDevices;
use common\models\GmsDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * GmsDevicesController implements the CRUD actions for GmsDevices model.
 */
class GmsDevicesController extends Controller
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
     * @param null $param
     * @return string
     */
    public function actionIndex($param = null)
    {
        $searchModel = new GmsDevicesSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $param
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => $param
        ]);
    }

    /**
     * Displays a single GmsDevices model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GmsDevices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsDevices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GmsDevices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'editDevice';

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::getLogger()->log([
                    '$model->getErrors()' => $model->getErrors()
                ], 1, 'binary');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionActivate($id)
    {
        if ($model = $this->findModel($id)) {
            $model->scenario = 'editDevice';
            $model->auth_status = 1;
            if (!$model->save()) {
                print_r($model->errors);
            } else {
                //print_r($model);
            }

        }

        return $this->redirect(['/GMS/gms-devices/index/auth']);
    }

    public function actionDeactivate($id)
    {
        if ($model = $this->findModel($id)) {
            $model->scenario = 'editDevice';
            $model->auth_status = 0;
            //print_r($model);
            if (!$model->save()) {
                print_r($model->errors);
            } else {

            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing GmsDevices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GmsDevices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsDevices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsDevices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $region
     * @param null $sender
     * @return string
     */
    public function actionAjaxDeviceList($region = null, $sender = null)
    {
        if (empty($sender)) $sender = null;
        if (empty($region)) exit('null');

        $data = GmsDevices::findAll([
            'region_id' => $region,
            'sender_id' => $sender,
        ]);

        /** @var GmsDevices $userData */
        foreach ($data as $userData) {
            $out['results'][] = ['id' => $userData->id, 'name' => $userData->device];
        }
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : null;
    }
}
