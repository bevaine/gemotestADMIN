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
use yii\log\Logger;

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
        $videoDir = implode(DIRECTORY_SEPARATOR, [Yii::getAlias('@backend'), 'web', 'upload', 'video', Yii::$app->session->id]). DIRECTORY_SEPARATOR;
        $thumbnailDir = implode(DIRECTORY_SEPARATOR, [Yii::getAlias('@backend'), 'web', 'upload', 'thumbnail', Yii::$app->session->id]). DIRECTORY_SEPARATOR;

        if (!is_dir($videoDir)) {
            FileHelper::createDirectory($videoDir, 0777);
        }
        if (!is_dir($thumbnailDir)) {
            FileHelper::createDirectory($thumbnailDir, 0777);
        }

        if ($imageFile) {

            $thumbnailUrl = '/img/video.png';
            $uid = uniqid(time(), true);

            $fileName = $uid . '.' . $imageFile->extension;
            $thumbnailName = $uid . '.jpg';

            $filePath = $videoDir . $fileName;
            $thumbnailPath = $thumbnailDir . $thumbnailName;

            if ($imageFile->saveAs($filePath)) {
                if ($thumbnail = FunctionsHelper::createMovieThumb($filePath, $thumbnailPath)) {
                    $thumbnailUrl = '/' . implode('/', ['upload', 'thumbnail', Yii::$app->session->id, $thumbnailName]);
                }
                $path = '/' . implode('/', ['upload', 'video', Yii::$app->session->id, $fileName]);

                $model->name = 'Без имени';
                $model->file = $path;
                $model->type = FileHelper::getMimeType($filePath);
                $model->thumbnail = $thumbnailUrl;
                if ($duration = FunctionsHelper::getDurationVideo($filePath)) {
                    $model->time = round($duration);
                }
                $model->created_at = time();

                if (!empty(Yii::$app->request->post())) {
                    $post = Yii::$app->request->post();
                    if (!empty($imageFile->name)) {
                        $nameBase64 = base64_encode($imageFile->name);
                        if (!empty($post["GmsVideos"][$nameBase64]['name'])) {
                            $model->name = $post["GmsVideos"][$nameBase64]['name'];
                        }
                        if (!empty($post["GmsVideos"][$nameBase64]['comment'])) {
                            $model->comment = $post["GmsVideos"][$nameBase64]['comment'];
                        }
                    }
                }

                if ($model->save()) {
                    return Json::encode([
                        'files' => [
                            [
                                'name' => $fileName,
                                'size' => $imageFile->size,
                                'url' => "../.." . $path,
                                'thumbnailUrl' => "../.." . $thumbnailUrl,
                                'deleteUrl' => 'video-delete?id=' . $model->id,
                                'deleteType' => 'POST'
                            ]
                        ]
                    ]);
                } else {
                    Yii::getLogger()->log([
                        '$model->save()' => $model->getErrors()
                    ], Logger::LEVEL_ERROR, 'binary');
                }
            }
        }

        return '';
    }

    /**
     * @param $id
     * @return string
     */
    public function actionVideoDelete($id)
    {
        $output = [];
        $videoDir = implode(DIRECTORY_SEPARATOR, [Yii::getAlias('@backend'), 'web', 'upload', 'video', Yii::$app->session->id]). DIRECTORY_SEPARATOR;
        $thumbnailDir = implode(DIRECTORY_SEPARATOR, [Yii::getAlias('@backend'), 'web', 'upload', 'thumbnail', Yii::$app->session->id]). DIRECTORY_SEPARATOR;

        $modelVideo = GmsVideos::findOne($id);
        if (!$modelVideo) return '';

        if (!empty($modelVideo->file)) {
            $videoFile = $modelVideo->file;
            $videoPath = $videoDir . basename($videoFile);
            if (is_file($videoPath)) {
                unlink($videoPath);
            } else {
                Yii::getLogger()->log('Файл ' . $videoPath . ' - не найден!',
                    Logger::LEVEL_ERROR, 'binary');
            }
        }

        if (!empty($modelVideo->thumbnail)) {
            $thumbnailFile = $modelVideo->thumbnail;
            $thumbnailPath = $thumbnailDir . basename($thumbnailFile);
            if (is_file($thumbnailPath)) {
                unlink($thumbnailPath);
            } else {
                Yii::getLogger()->log('Файл ' . $thumbnailPath . ' - не найден!',
                    Logger::LEVEL_ERROR, 'binary');
            }
        }

        $files = FileHelper::findFiles($videoDir);

        foreach ($files as $file) {

            $path_parts = pathinfo($file);
            $fileName = $path_parts['basename'];
            $thumbnailName =  $path_parts['filename'].'.jpg';
            $videoURL = '/' . implode('/', ['upload', 'video', Yii::$app->session->id, $fileName]);

            $thumbnailURL = '/img/video.png';
            if (is_file($thumbnailDir . $thumbnailName)) {
                $thumbnailURL = '/' . implode('/', ['upload', 'thumbnail', Yii::$app->session->id, $thumbnailName]);
            } else {
                Yii::getLogger()->log('Файл ' . $thumbnailDir . $thumbnailName . ' - не найден!',
                    Logger::LEVEL_ERROR, 'binary');
            }

            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => '../..' . $videoURL,
                'thumbnailUrl' => '../..'. $thumbnailURL,
                'deleteUrl' => 'video-delete?id=' . $modelVideo->id,
                'deleteType' => 'POST',
                'value' => $fileName,
            ];
        }

        $modelVideo->delete();

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
