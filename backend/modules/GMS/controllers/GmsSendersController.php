<?php

namespace app\modules\GMS\controllers;

use common\models\GmsRegions;
use common\models\Kontragents;
use Yii;
use common\models\GmsSenders;
use common\models\GmsSendersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * GmsSendersController implements the CRUD actions for GmsSenders model.
 */
class GmsSendersController extends Controller
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
     * Lists all GmsSenders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsSendersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GmsSenders model.
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
     * Creates a new GmsSenders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsSenders();

        if ($model->load(Yii::$app->request->post())) {
            if (isset($model->kontragents)) {
                $model->sender_name = $model->kontragents->Name;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GmsSenders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GmsSenders model.
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
     * Finds the GmsSenders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsSenders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsSenders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $region
     */
    public function actionAjaxSendersList($region = null)
    {
        if (empty($region)) exit('null');
        $data = GmsSenders::findAll(['region_id' => $region]);

        /** @var GmsSenders $userData */
        foreach ($data as $userData) {
            $out['results'][] = ['id' => $userData->id, 'name' => $userData->sender_name];
        }
        echo !empty($out) ? Json::encode($out) : 'null';
    }

    /**
     * @param null $sender
     */
    public function actionAjaxGetRegion($sender = null)
    {
        $out = ['more' => false];

        $region_id = null;
        if ($dataSender = GmsSenders::findOne(['sender_key' => $sender])) {
            $region_id = $dataSender->region_id;
        }

        foreach (GmsRegions::find()->all() as $userData) {
            /** @var $userData GmsRegions */
            $out['selected'] = $region_id;
            $out['results'][] = [
                'id' => $userData->id,
                'name' => $userData->region_name
            ];
        }

        echo Json::encode($out);
    }
}