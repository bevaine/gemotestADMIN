<?php

namespace app\modules\GMS\controllers;

use common\components\helpers\FunctionsHelper;
use yii\helpers\Html;
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
use yii\helpers\Url;

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
     * @param null $param
     * @return string|\yii\web\Response
     */
    public function actionCreate($param = null)
    {
        $model = new GmsPlaylistOut();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'action' => $param
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

        if ($model->load(Yii::$app->request->post())) {
            $model->update_at = time();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
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
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $out = [];
        if (empty(Yii::$app->request->queryParams)) {
            return 'null';
        }

        $post = Yii::$app->request->queryParams;
        if (!empty($post['GmsPlaylistOut']['pls_id'])) {
            $pls_id = $post['GmsPlaylistOut']['pls_id'];
            $model = GmsPlaylistOut::findOne($pls_id);
        } else {
            $model = new GmsPlaylistOut();
        }

        if ($model->load($post)) {

            $model->date_start = strtotime($model->date_start);
            $model->date_end = strtotime($model->date_end);

            $model->time_start = FunctionsHelper::getTimeDate(strtotime($model->time_start));
            $model->time_end = FunctionsHelper::getTimeDate(strtotime($model->time_end));
            $out = $model->checkPlaylist();
        }

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
        $std_time = 0;
        $com_time = 0;
        $minimal_std = 60;
        $pos_in_all = -1;
        $all_time = '';
        $arr_commerce = [];
        $arr_standart = [];
        $pls_commerce = '';
        $pls_standart = '';

        if (empty(Yii::$app->request->post()))
            return false;

        $post = Yii::$app->request->post();

        if (!empty($post['all_time'])) $all_time = $post['all_time'];
        if (!empty($post['arr_commerce'])) $arr_commerce = $post['arr_commerce'];
        if (!empty($post['arr_standart'])) $arr_standart = $post['arr_standart'];
        if (!empty($post['pls_commerce'])) $pls_commerce = $post['pls_commerce'];
        if (!empty($post['pls_standart'])) $pls_standart = $post['pls_standart'];

        if (empty($arr_standart)) {
            return [
                'state' => 0,
                'message' => 'Ошибка формирования плейлиста дневного эфира! Нет добавленых стандартных видео!'
            ];
        }

        if (empty($all_time)) {
            return [
                'state' => 0,
                'message' => 'Ошибка формирования плейлиста дневного эфира! Пустое значение времени дневного эфира!'
            ];
        }

        if (empty($pls_standart)) {
            return [
                'state' => 0,
                'message' => 'Ошибка формирования плейлиста дневного эфира! Не удалось получить id стандартного плейлиста!'
            ];
        }

        if (empty($arr_commerce)) {
            return $this->getStandartPls($arr_standart, $pls_standart);
        }

        foreach ($arr_commerce as $input) {
            $com_time += $input['duration'] * $input['views'];
        }

        $play_standart = ($all_time - $com_time)
            / array_sum(ArrayHelper::getColumn(
                $arr_commerce,
                'views')
            );

        $play_standart = ceil($play_standart);

        if ($play_standart >= $minimal_std) {

            $findCommerceModel = GmsPlaylist::findOne($pls_commerce);

            foreach ($arr_commerce as $commerce) {

                if ($dataCommerce = $findCommerceModel->getVideoData($commerce['key'])) {
                    $dataCommerce = ArrayHelper::toArray($dataCommerce);
                } else
                    continue;

                $arr = [];
                for ($n = 1; $n < $commerce['views'] + 1; $n++) {
                    $pos_in_all += $n;
                    $arr[] = [
                        'title' => $dataCommerce['title'],
                        'type' => 2,
                        'key' => $commerce['key'],
                        'file' => $dataCommerce['file'],
                        'duration' => $dataCommerce['duration'],
                        'frame_rate' => $dataCommerce['frame_rate'],
                        'nb_frames' => $dataCommerce['nb_frames'],
                        'start' => 0,
                        'end' => (int)$commerce['duration'],
                        'pos_in_all' => $pos_in_all
                    ];
                }

                if (empty($f)) $f = $arr;
                else $f = array_merge($f, $arr);
            }

            if (!empty($f)) shuffle($f);
            $findStandartModel = GmsPlaylist::findOne($pls_standart);

            while(list(, $time) = each($arr_standart))
            {
                if (current($f) === false
                    && current($arr_standart) == false) {
                    break;
                }

                $pos_in_all += 1;

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
                        'end' => (int)$time['duration'],
                        'pos_in_all' => $pos_in_all,
                    ];

                    if (!empty($val)) {
                        $s[] = $val;
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
                                'end' => (int)$time['duration'],
                                'pos_in_all' => $pos_in_all,
                            ];

                            if (!empty($val)) {
                                $s[] = $val;
                            }
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
                                    'end' => (int)$time['duration'],
                                    'pos_in_all' => $pos_in_all,
                                ];
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
                                'end' => (int)$a,
                                'pos_in_all' => $pos_in_all,
                            ];
                            if (!empty($val)) {
                                $s[] = $val;
                            }
                        }
                    }
                }

                $std_time = $this->getStandartTime($s);
                if (($std_time + $com_time) > $all_time) {
                    array_pop($s);
                    break;
                }

                if (current($f) !== false
                    && current($arr_standart) == false) {
                    reset($arr_standart);
                }
            }

            if (!empty($s)) {
                foreach ($s as $key => $val) {
                    $s[$key]["pos_in_list"] = $key;
                }
                $std_time = $this->getStandartTime($s);
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

    /**
     * @param $arr
     * @return int
     */
    public function getStandartTime($arr)
    {
        $sum_std = 0;
        reset($arr);

        foreach ($arr as $item) {
            if (array_key_exists('type', $item)
                && $item['type'] == 1) {
                $sum_std += $item['end'] - $item['start'];
            }
        }
        return $sum_std;
    }

    /**
     * @param $arr_standart
     * @param $pls_standart
     * @return array
     */
    public function getStandartPls($arr_standart, $pls_standart)
    {
        $pos_in_all = -1;

        $std_time = array_sum(
            ArrayHelper::getColumn(
                $arr_standart,
                'duration'
            )
        );
        $findStandartModel = GmsPlaylist::findOne($pls_standart);

        foreach ($arr_standart as $time) {

            $pos_in_all += 1;

            if ($dataStandart = $findStandartModel->getVideoData($time['key'])) {
                $dataStandart = ArrayHelper::toArray($dataStandart);
            } else
                continue;

            $s[] = [
                'type' => 1,
                'key' => $time['key'],
                'title' => $dataStandart['title'],
                'duration' => $dataStandart['duration'],
                'frame_rate' => $dataStandart['frame_rate'],
                'nb_frames' => $dataStandart['nb_frames'],
                'file' => $dataStandart['file'],
                'start' => 0,
                'end' => (int)$time['duration'],
                'pos_in_all' => $pos_in_all,
                'pos_in_list' => $pos_in_all,
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
     * @param $video_key
     * @return array|string
     */
    public function actionAjaxCheckVideo($video_key)
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        $keys = [];
        if ($findModel = GmsPlaylistOut::find()->all()) {
            /** @var GmsPlaylistOut $model */
            foreach ($findModel as $model) {
                $jsonPlaylist = ArrayHelper::toArray(json_decode($model->jsonPlaylist));
                $arrChildren = $jsonPlaylist["children"];
                $arrKeys = ArrayHelper::getColumn($arrChildren, 'key');
                $arr_keys = array_keys($arrKeys, $video_key);
                if (!empty($arr_keys)) {
                    $keys[] = $model->id;
                }
            }
        }

        if (!empty($keys)) {
            $url = [];
            foreach ($keys as $key) {
                if ($findPls = GmsPlaylistOut::findOne($key)) {
                    $url[] = Html::a($findPls->name, Url::to(["playlist-out/view", 'id' => $findPls->id]), ['target'=>'_blank']);
                }
            }
            if (!empty($url)) {
                $str_html = "Данное видео удалить не возможно";
                $str_html .= "<br>т.к., оно добавлено в плейлисты - ".implode(",", $url);
                return [
                    'state' => 0,
                    'message' => $str_html
                ];
            }
        }
        return [
            'state' => 1,
        ];
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
     * @param null $playlist_key
     * @return array|string
     */
    public function actionAjaxDeletePlaylist($playlist_key = null)
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;

        if (!$model = GmsPlaylist::findOne($playlist_key))
            return 'null';

        $findModel = GmsPlaylistOut::find();
        if (isset($model->region)) {
            $findModel->andWhere([
                'region_id' => $model->region,
                'sender_id' => $model->sender_id
            ]);
        } elseif (isset($model->group_id)) {
            $findModel->andWhere([
                'group_id' => $model->group_id
            ]);
        } elseif (isset($model->device_id)) {
            $findModel->andWhere([
                'device_id' => $model->device_id
            ]);
        } else return 'null';

        $findModel= $findModel->one();

        if ($findModel) {
            $jsonModel = ArrayHelper::toArray(json_decode($findModel->jsonPlaylist));
            $ddd = ArrayHelper::getColumn($jsonModel['children'], 'data');
            $ddd = ArrayHelper::getColumn($ddd, 'type');
            if (in_array($model->type, $ddd)) return ['id' => $findModel->id, 'name' => $findModel->name];
        }
        return 'null';
    }

}
