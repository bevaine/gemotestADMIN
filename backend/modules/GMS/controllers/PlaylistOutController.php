<?php

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsPlaylistOut;
use common\models\GmsPlaylistOutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\log\Logger;
use yii\helpers\Json;

/**
 * PlaylistOutController implements the CRUD actions for GmsPlaylistOut model.
 */
class PlaylistOutController extends Controller
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
     * Lists all GmsPlaylistOut models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsPlaylistOutSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GmsPlaylistOut model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (isset(Yii::$app->request->post()['active-playlist'])) {
            $status = Yii::$app->request->post()['active-playlist'];
            if ($status == 'block') {
                $model->active = 0;
            } elseif ($status == 'active') {
                $model->active = 1;
            }
            if (!$model->save()) {
                Yii::getLogger()->log([
                    'model->DateEnd'=>$model->errors
                ], Logger::LEVEL_ERROR, 'binary');
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GmsPlaylistOut model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsPlaylistOut();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GmsPlaylistOut model.
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
     * Deletes an existing GmsPlaylistOut model.
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
     *
     */
    public function actionAjaxTimeCheck()
    {
        $out = [];
        $model = new GmsPlaylistOut();
        $model->scenario = 'findPlaylistOut';

        //if ($model->load(Yii::$app->request->post())) {
        if ($model->load(Yii::$app->request->queryParams)) {

            $model->dateStart = strtotime($model->dateStart);
            $model->dateEnd = strtotime($model->dateEnd);

            $model->timeStart = GmsPlaylistOut::getTimeDate(strtotime($model->timeStart));
            $model->timeEnd = GmsPlaylistOut::getTimeDate(strtotime($model->timeEnd));

            $out = $model->checkPlaylist();
        }
        echo !empty($out) ? Json::encode($out) : 'null';
    }

    /**
     * Finds the GmsPlaylistOut model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsPlaylistOut the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsPlaylistOut::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
