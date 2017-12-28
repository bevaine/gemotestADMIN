<?php

namespace app\modules\admin\controllers;

use Yii;
use common\models\NWorkshift;
use common\models\NWorkshiftSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkshiftController implements the CRUD actions for NWorkshift model.
 */
class WorkshiftController extends Controller
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
     * Lists all NWorkshift models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NWorkshiftSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NWorkshift model.
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
     * @param $id
     * @return mixed
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);

        if ($model->pays) {
            $arrPays = ArrayHelper::toArray($model->pays);
            $arrPays = ArrayHelper::getColumn($arrPays, 'total');
            $summPays = array_sum($arrPays);
            $countPays = count($arrPays);

            $arrReturnPays = ArrayHelper::toArray($model->returnPays);
            $arrReturnPays = ArrayHelper::getColumn($arrReturnPays, 'total');
            $summReturnPays = array_sum($arrReturnPays);

            $countPays = count($arrPays);

            //$summPays = ArrayHelper::getColumn($summPays, 'total');
            print_r($summPays);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new NWorkshift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NWorkshift();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing NWorkshift model.
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
     * Deletes an existing NWorkshift model.
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
     * Finds the NWorkshift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NWorkshift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NWorkshift::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
