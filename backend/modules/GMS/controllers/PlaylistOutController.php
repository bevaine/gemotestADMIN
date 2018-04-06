<?php

namespace app\modules\GMS\controllers;

use common\models\GmsPlaylist;
use Yii;
use common\models\GmsPlaylistOut;
use common\models\GmsPlaylistOutSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\log\Logger;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

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
                    '$model->save()'=>$model->errors
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

        if ($model->load(Yii::$app->request->queryParams)) {

            $model->date_start = strtotime($model->date_start);
            $model->date_end = strtotime($model->date_end);

            $model->time_start = GmsPlaylistOut::getTimeDate(strtotime($model->time_start));
            $model->time_end = GmsPlaylistOut::getTimeDate(strtotime($model->time_end));

            $out = $model->checkPlaylist();
        }

        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        return !empty($out) ? $out : 'null';
    }

    /**
     * @return array|bool
     */
    public function actionAjaxCheckPlaylist()
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $f = [];
        $s = [];
        $sum = 0;
        $std_time = 0;
        $com_time = 0;
        $minimal_std = 60;

        if ($post = Yii::$app->request->post()) {
            $all_time = $post['all_time'];
            $arr_commerce = $post['arr_commerce'];
            $arr_standart = $post['arr_standart'];
            $pls_commerce = $post['pls_commerce'];
            $pls_standart = $post['pls_standart'];
        } else
            return false;

        if (empty($arr_standart) || empty($all_time)) {
            return [
                'state' => 0,
                'message' => 'Ошибка формирования плейлиста дневного эфира!'
            ];
        }

        if (empty($arr_commerce)) {
            return $this->getStandartPls($arr_standart);
        }

        foreach ($arr_commerce as $input) {
            $sum += $input['duration'] * $input['views'];
        }

        $play_standart = ($all_time - $sum)
            / array_sum(ArrayHelper::getColumn($arr_commerce, 'views'));

        $play_standart = ceil($play_standart);

        if ($play_standart >= $minimal_std) {

            $findCommerceModel = GmsPlaylist::findOne($pls_commerce);

            foreach ($arr_commerce as $commerce) {

                if ($dataCommerce = $findCommerceModel->getVideoData($commerce['key'])) {
                    $dataCommerce = ArrayHelper::toArray($dataCommerce);
                } else
                    continue;

                $arr = array_fill(0, $commerce['views'], [
                    'title' => $dataCommerce['title'],
                    'type' => 2,
                    'key' => $commerce['key'],
                    'file' => $dataCommerce['file'],
                    'duration' => $dataCommerce['duration'],
                    'frame_rate' => $dataCommerce['frame_rate'],
                    'nb_frames' => $dataCommerce['nb_frames'],
                    'start' => 0,
                    'end' => (int)$commerce['duration ']
                ]);
                if (empty($f)) $f = $arr;
                else $f = array_merge($f, $arr);
                if (!empty($f)) shuffle($f);
            }

            $findStandartModel = GmsPlaylist::findOne($pls_standart);

            while(list($key, $time) = each($arr_standart))
            {
                if ($dataStandart = $findStandartModel->getVideoData($time['key'])) {
                    $dataStandart = ArrayHelper::toArray($dataStandart);
                } else
                    continue;

                if ($play_standart >= $time['duration'])
                {
                    $val = each($f)['value'];
                    $s[] = [
                        'type' => 1,
                        'key' => $time['key'],
                        'title' => $dataStandart['title'],
                        'duration' => $dataStandart['duration'],
                        'frame_rate' => $dataStandart['frame_rate'],
                        'nb_frames' => $dataStandart['nb_frames'],
                        'file' => $dataStandart['file'],
                        'start' => 0,
                        'end' => (int)$time['duration']
                    ];

                    $std_time += array_sum(ArrayHelper::getColumn($time, 'duration'));
                    if (!empty($val)) {
                        $s[] = $val;
                        $com_time += array_sum(ArrayHelper::getColumn($val, 'duration'));
                    }

                } else if ($play_standart < $time['duration'])
                {
                    for ($a = 0; ;($a = $a + $play_standart))
                    {
                        $b = $a - $play_standart;
                        if ($a > $time['duration']) {
                            $val = each($f)['value'];
                            $s[] = [
                                'type' => 1,
                                'key' => $time['key'],
                                'title' => $dataStandart['title'],
                                'duration' => $dataStandart['duration'],
                                'frame_rate' => $dataStandart['frame_rate'],
                                'nb_frames' => $dataStandart['nb_frames'],
                                'file' => $dataStandart['file'],
                                'start' => (int)$b,
                                'end' => (int)$time['duration']
                            ];
                            $s[] = $val;
                            $std_time += $time['duration'] - $b;
                            $com_time += $val['end'];
                            break;
                        } elseif ($a > 0)
                        {
                            $val = each($f)['value'];
                            if (empty($val)) {
                                $s[] = [
                                    'type' => 1,
                                    'key' => $time['key'],
                                    'title' => $dataStandart['title'],
                                    'duration' => $dataStandart['duration'],
                                    'frame_rate' => $dataStandart['frame_rate'],
                                    'nb_frames' => $dataStandart['nb_frames'],
                                    'file' => $dataStandart['file'],
                                    'start' => (int)$b,
                                    'end' => (int)$time['duration']
                                ];
                                $std_time += $time['duration'] - $b;
                                break;
                            }
                            $s[] = [
                                'type' => 1,
                                'key' => $time['key'],
                                'title' => $dataStandart['title'],
                                'duration' => $dataStandart['duration'],
                                'frame_rate' => $dataStandart['frame_rate'],
                                'nb_frames' => $dataStandart['nb_frames'],
                                'file' => $dataStandart['file'],
                                'start' => (int)$b,
                                'end' => (int)$a
                            ];
                            $s[] = $val;
                            $std_time += $a - $b;
                            $com_time += $val['end'];
                        }
                    }
                }

                if (!empty($val) && $key == count($arr_standart) - 1) {
                    reset($arr_standart);
                }
            }

            if (!empty($s)) {
                return [
                    'com_time' => $com_time,
                    'std_time' => $std_time,
                    'state' => 1,
                    'info' => $s
                ];
            } else {
                return [
                    'state' => 0,
                    'message' => 'Ошибка формирования плейлиста дневного эфира!'
                ];
            }
        } else {
            if ($all_time > 60) {
                $all_time = date("H:i:s", mktime(null, null, $all_time));
            } else {
                $all_time = $all_time.' сек.';
            }
            $message = 'Слишком короткий интервал бесплатного эфирного время ' . $play_standart . ' сек. (из допущенного ' . $minimal_std . ' сек.)';
            $message .= '<br>Уменьшите интервал и/или кол-во просмотра коммерческого видео, чтобы уложиться в время дневого эфира - '.$all_time;
            return [
                'state' => 0,
                'message' => $message
            ];
        }
    }

    public function getStandartPls($arr_standart)
    {
        $std_time = array_sum(ArrayHelper::getColumn($arr_standart, 'duration'));

        foreach ($arr_standart as $time) {
            $s[] = [
                'file' => $time['file'],
                'key' => $time['key'],
                'start' => 0,
                'end' => (int)$time['duration']
            ];
        }
        if (!empty($s)) {
            return [
                'com_time' => 0,
                'std_time' => $std_time,
                'state' => 1,
                'info' => $s
            ];
        } else {
            return [
                'state' => 0,
                'message' => 'Ошибка формирования плейлиста дневного эфира!'
            ];
        }
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
