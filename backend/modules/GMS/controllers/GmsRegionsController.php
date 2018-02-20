<?php

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsRegions;
use common\models\GmsRegionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * GmsRegionsController implements the CRUD actions for GmsRegions model.
 */
class GmsRegionsController extends Controller
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
     * Lists all GmsRegions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsRegionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GmsRegions model.
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
     * Creates a new GmsRegions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsRegions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GmsRegions model.
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
     * Deletes an existing GmsRegions model.
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
     * Finds the GmsRegions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsRegions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsRegions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $search
     * @param null $id
     */
    public function actionAjaxRegionsList($search = null, $id = null)
    {
        $out = ['more' => false];

        if (!is_null($search)) {
            $search = mb_strtolower($search, 'UTF-8');
            $data = GmsRegions::find()
                ->where('lower(region_name) LIKE \'%' . $search . '%\'')
                ->limit(20)
                ->all();
            /** @var GmsRegions $userData */
            foreach ($data as $userData) {
                $out['results'][] = ['id' => $userData->id, 'text' => $userData->region_name];
            }
        } elseif (isset($id)) {
            $out['results'] = ['id' => $id, 'text' => GmsRegions::findOne(['id' =>$id])->region_name];
        }

        echo Json::encode($out);
    }
}
