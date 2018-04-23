<?php

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsGroupDevices;
use common\models\GmsGroupDevicesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;
use yii\log\Logger;

/**
 * GmsGroupDevicesController implements the CRUD actions for GmsGroupDevices model.
 */
class GmsGroupDevicesController extends Controller
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
     * Lists all GmsGroupDevices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsGroupDevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $group_id
     * @return string|\yii\web\Response
     */
    public function actionView($group_id)
    {
        if (!$findModel = $this->findModel($group_id)) {
            return $this->redirect(['index']);
        }

        if ($dataArr = $this->addJson($findModel)) {
            return $this->render('view', [
                'dataArr' => $dataArr,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Creates a new GmsGroupDevices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsGroupDevices();
        $rowInsert = [];
        if ($model->load(Yii::$app->request->post())) {
            $json = json_decode($model->group_json);
            $max = (int)GmsGroupDevices::find()->max('group_id');
            $max += 1;
            foreach ($json[0]->children as $children) {
                $rowInsert[] = [
                    'group_name' => $json[0]->title,
                    'group_id' => $max,
                    'device_id' => $children->key,
                    'parent_key' => $children->data->parent_key,
                ];
            }
            if (!empty($rowInsert)) {
                try {
                    Yii::$app->db->createCommand()->batchInsert(
                        GmsGroupDevices::tableName(),
                        array_keys($rowInsert[0]),
                        $rowInsert
                    )->execute();
                } catch (Exception $e) {
                    Yii::getLogger()->log([
                        'GmsGroupDevices->batchInsert'=>$e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
                return $this->redirect(['view', 'group_id' => $max]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $group_id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($group_id)
    {
        $model = new GmsGroupDevices();
        $rowInsert = [];
        if ($model->load(Yii::$app->request->post())) {
            $json = json_decode($model->group_json);
            if (!self::deleteGroup($group_id)) {
                return $this->redirect(['update', 'group_id' => $group_id]);
            }
            foreach ($json[0]->children as $children) {
                $rowInsert[] = [
                    'group_name' => $json[0]->title,
                    'group_id' => $group_id,
                    'device_id' => $children->key,
                    'parent_key' => $children->data->parent_key,
                ];
            }
            if (!empty($rowInsert)) {
                try {
                    Yii::$app->db->createCommand()->batchInsert(
                        GmsGroupDevices::tableName(),
                        array_keys($rowInsert[0]),
                        $rowInsert
                    )->execute();
                } catch (Exception $e) {
                    Yii::getLogger()->log([
                        'GmsGroupDevices->batchInsert'=>$e->getMessage()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
                return $this->redirect(['view', 'group_id' => $group_id]);
            }
        }

        if (!$findModel = $this->findModel($group_id)) {
            return $this->redirect(['index']);
        }

        if ($dataArr = $this->addJson($findModel)) {
            return $this->render('update', [
                'dataArr' => $dataArr,
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * @param $findModel
     * @return array
     */
    public function addJson($findModel)
    {
        $group_id = '';
        $group_name = '';
        $row_json = [];

        /** @var GmsGroupDevices $model */
        foreach ($findModel as $model) {
            $group_name = $model->group_name;
            $group_id = $model->group_id;
            $row_json[] = [
                'title' => $model->device->name,
                'folder' => false,
                'key' => (string)$model->device_id,
                'data' => [
                    'parent_key' => $model->parent_key
                ]
            ];
        }
        $json = [
            'title' => $group_name,
            'folder' => true,
            'key' => 'group',
            'children' => $row_json,
            'expanded' => true
        ];
        return [
            'group_id' => $group_id,
            'group_name' => $group_name,
            'json' => json_encode(array($json), JSON_UNESCAPED_UNICODE)
        ];
    }

    /**
     * @param $group_id
     * @return bool
     */
    static function deleteGroup($group_id)
    {
        if (!empty($group_id))
            GmsGroupDevices::deleteAll(['group_id' => $group_id]);

        return true;
    }

    /**
     * @param $group_id
     * @return \yii\web\Response
     */
    public function actionDelete($group_id)
    {
        $this->deleteGroup($group_id);

        return $this->redirect(['index']);
    }

    /**
     * @param $group_id
     * @return static[]
     * @throws NotFoundHttpException
     */
    protected function findModel($group_id)
    {
        if (($model = GmsGroupDevices::findAll(['group_id' => $group_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
