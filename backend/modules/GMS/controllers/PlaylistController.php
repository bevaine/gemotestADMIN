<?php

namespace app\modules\GMS\controllers;

use common\models\GmsPlaylistOut;
use Yii;
use common\models\GmsPlaylist;
use common\models\GmsPlaylistSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * PlaylistController implements the CRUD actions for GmsPlaylist model.
 */
class PlaylistController extends Controller
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
     * Lists all GmsPlaylist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsPlaylistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GmsPlaylist model.
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
     * Creates a new GmsPlaylist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsPlaylist();

        if ($model->load(Yii::$app->request->post())) {
            //$model->created_at = time();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GmsPlaylist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            //$model->updated_at = time();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GmsPlaylist model.
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
     * @return string
     */
    public function actionEdit()
    {
        return $this->render('edit');
    }

    /**
     * Finds the GmsPlaylist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsPlaylist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsPlaylist::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $region
     * @param null $sender_id
     * @param null $type_list
     * @return array|null
     */
    public function actionAjaxPlaylistActive(
        $region = null,
        $sender_id = null,
        $type_list = null
    ) {

        if (empty($sender_id)) $sender_id = null;
        if (empty($region)) exit('null');

        $findModel = GmsPlaylist::findOne([
            'region' => $region,
            'sender_id' => $sender_id,
            'type' => $type_list
        ]);

        if ($findModel) {
            $out = $findModel->toArray();
            if (isset($findModel->regionModel)) {
                $out['region'] = $findModel->regionModel->region_name;
            }
            if (isset($findModel->senderModel)) {
                $out['sender'] = $findModel->senderModel->sender_name;
            }
            if (isset($findModel->type)) {
                $out['type'] =  \common\models\GmsPlaylist::getPlayListType($findModel->type);
            }
        }

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : null;
    }

    /**
     * @param $children
     * @return array|bool
     */
    public function parseJSON($children)
    {
        if (!empty($children)) {
            $key = ArrayHelper::getColumn($children, 'key');
            return array_filter(
                array_combine($key, $children),
                function($v) {return $v['data']['type'] == 2;}
            );
        }
        return false;
    }

    /**
     * @param null $region
     * @param null $sender_id
     * @param null $pls_out_id
     * @return null
     */
    public function actionAjaxPlaylistTemplate(
        $region = null,
        $sender_id = null,
        $pls_out_id = null
    ) {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $out = [];
        $diffArr = '';
        $plsDataJSON = false;

        if (empty($sender_id)) $sender_id = null;
        if (empty($region)) return 'null';

        $findModel = GmsPlaylist::findAll([
            'region' => $region,
            'sender_id' => $sender_id,
        ]);

        foreach ($findModel as $model) {
            /** @var $model GmsPlaylist */
            if (!isset($model->type)) continue;
            $out["result"][$model->type][] = ArrayHelper::toArray(json_decode($model->jsonPlaylist));
        }

        if (!empty($pls_out_id) && !empty($out["result"][2][0])) {

            $children = $out["result"][2][0]['children'];
            $comDataJSON = $this->parseJSON($children);

            if ($findPlsModel = GmsPlaylistOut::findOne($pls_out_id)) {
                $jsonModel = ArrayHelper::toArray(json_decode($findPlsModel->jsonPlaylist));
                $plsDataJSON = $this->parseJSON($jsonModel['children']);
            }

            if (empty($plsDataJSON)) return $out;

            if ($comDataJSON && $plsDataJSON) {
                $diffArr = array_diff_key($comDataJSON, $plsDataJSON);
            }

            unset($out["result"][2][0]['children']);
            if (!empty($diffArr)) {
                $out["result"][2][0]['children'] = array_values($diffArr);
            }
        }

        return !empty($out) ? $out : null;
    }
}
