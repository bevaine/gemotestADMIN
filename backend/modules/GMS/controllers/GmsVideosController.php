<?php

namespace app\modules\GMS\controllers;

use common\components\helpers\FunctionsHelper;
use Yii;
use common\models\GmsVideos;
use common\models\GmsVideosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;
use FFMpeg;
use yii\helpers\Url;

/**
 * GmsVideosController implements the CRUD actions for GmsVideos model.
 */
class GmsVideosController extends Controller
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
     * Lists all GmsVideos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GmsVideosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionVideoUpload()
    {
        $model = new GmsVideos();

        $imageFile = UploadedFile::getInstance($model, 'file');

        $directory = Yii::getAlias('@backend/web/upload/video') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory, 0777);
        }

        if ($imageFile) {
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
            if ($imageFile->saveAs($filePath)) {
                $path = '/upload/video/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
                return Json::encode([
                    'files' => [
                        [
                            'name' => $fileName,
                            'size' => $imageFile->size,
                            'url' => $path,
                            'thumbnailUrl' =>  '/img/video.png',
                            'deleteUrl' => 'video-delete?name=' . $fileName,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }

        return '';
    }

    /**
     * @param $name
     * @return string
     */
    public function actionVideoDelete($name)
    {
        $directory = Yii::getAlias('@backend/web/upload/video') . DIRECTORY_SEPARATOR . Yii::$app->session->id;
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = '/upload/video/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => '/img/video.png',
                'deleteUrl' => 'video-delete?name=' . $fileName,
                'deleteType' => 'POST',
                'value' => $fileName
            ];
        }
        return Json::encode($output);
    }

    /**
     * Displays a single GmsVideos model.
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
     * Creates a new GmsVideos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GmsVideos();

        if ($model->load(Yii::$app->request->post())) {
            if (!empty(Yii::$app->request->post()['GmsVideos']['fileName'])) {
                $post = Yii::$app->request->post();
                $directory = Yii::getAlias('@backend/web') . DIRECTORY_SEPARATOR;
                $file = $directory . $post['GmsVideos']['fileName'];
                if (file_exists($file)) {
                    $model->file = $post['GmsVideos']['fileName'];
                    //$model->type = FileHelper::getMimeType($file);
                    if ($duration = FunctionsHelper::getDurationVideo($file)) {
                        $model->time = round($duration);
                    }
                    if ($thumbnail = FunctionsHelper::createMovieThumb($file, $directory.'thumbnail.jpg')) {

                    }

                    $model->created_at = time() ;
                }
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
     * Updates an existing GmsVideos model.
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
     * Deletes an existing GmsVideos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $findModel = $this->findModel($id);
        if (!empty($findModel->file)) {
            $pathInfo = pathinfo($findModel->file);
            $directory = Yii::getAlias('@backend/web') . $pathInfo['dirname'];
            $fileName = $directory . DIRECTORY_SEPARATOR . $pathInfo['basename'];
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
        $findModel->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the GmsVideos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GmsVideos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GmsVideos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $video
     */
    public function actionAjaxVideoActive($video = null)
    {
        $out = [];
        $table = '';
        if (!is_null($video)) {
            $data = GmsVideos::findOne($video);
            /** @var GmsVideos $userData */
            $tr = '';
            foreach ($data->attributeLabels() as $key=>$label) {
                if (in_array($key, ['type', 'file'])) continue;
                if ($key == 'time')
                    $data->$key = date("H:i:s", mktime(0, 0, $data->$key));
                if ($key == 'created_at')
                    $data->$key = date("d-m-Y H:i:s", $data->$key);
                $tr .= '<tr>';
                $tr .= '<th class="">'.$label.'</th>';
                $tr .= '<td>'.$data->$key.'</td>';
                $tr .= '</tr>';
            }
            if (!empty($tr)) {
                $table .= '<table id="w0" class="table table-striped table-bordered detail-view">';
                $table .=  '<tbody>'.$tr.'</tbody>';
                $table .=  '</table>';
                $out['results']['table'] = $table;
            }
            if (!empty($data->file)) $out['results']['file'] = $data->file;
        }
        echo !empty($out) ? Json::encode($out) : null;
    }
}
