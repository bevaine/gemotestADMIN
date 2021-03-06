<?php

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsVideoHistory;
use common\models\GmsVideoHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GmsVideoHistoryController implements the CRUD actions for GmsVideoHistory model.
 */
class GmsVideoHistoryController extends Controller
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
     * Lists all GmsVideoHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsVideoHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GmsVideoHistory model.
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
     * Creates a new GmsVideoHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsVideoHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GmsVideoHistory model.
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
     * Deletes an existing GmsVideoHistory model.
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
     * Finds the GmsVideoHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsVideoHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsVideoHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *
     */
    public function actionAjaxVideoList()
    {
        $searchModel = new GmsVideoHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        echo $this->createJson($dataProvider->getModels());
    }

    /**
     * @param $model
     * @return array
     */
    public function createJson($model)
    {
        $node = [];
        if ($model && is_array($model)) {
            foreach ($model as $field) {
                $node[] = [
                    'start' => $field['start_at'],
                    'end' => $field['last_at'],
                    'title' => $field['name'],
                    'description' => $field['comment'],
                    'image' => $field['thumbnail'],
                    'link' => $field['file'],
                ];
            }
        }
        $arrJson = [
            'dateTimeFormat' => 'iso8601',
            'wikiURL' => 'https://corptv.gemotest.ru/wiki/content/admin',
            'wikiSection' => 'Просмотр истории показанных видео-роликов',
            'events' => $node
        ];
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return $arrJson;
    }
}
