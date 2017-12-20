<?php

namespace app\modules\admin\controllers;

use common\models\NEncashmentDetail;
use Yii;
use common\models\NEncashment;
use common\models\NEncashmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncashmentController implements the CRUD actions for NEncashment model.
 */

class EncashmentController extends Controller
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
     * Lists all NEncashment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NEncashmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NEncashment model.
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
     * Creates a new NEncashment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NEncashment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing NEncashment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($modelDetail = $model->detail) {
                $modelDetail->total = $model->total;
                $modelDetail->save();
            }
            if ($modelBalance = $model->cashBalanceInLOFlow) {
                $modelBalance->date = $model->date;
                $modelBalance->total = "-".$model->total;
                $modelBalance->operation = 'Инкассация EncashmentID:'.$model->id.' общая сумма инкассации = '.$model->total;
                $modelBalance->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing NEncashment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($modelDetail = $model->detail) {
            $modelDetail->delete();
        }
        if ($modelBalance = $model->cashBalanceInLOFlow) {
            $modelBalance->delete();
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the NEncashment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NEncashment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NEncashment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
