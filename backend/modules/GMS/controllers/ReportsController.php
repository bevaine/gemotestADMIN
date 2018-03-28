<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 28.03.2018
 * Time: 14:48
 */

namespace app\modules\GMS\controllers;

use Yii;
use common\models\GmsVideoHistory;
use common\models\GmsVideoHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReportsController extends Controller
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
    public function actionVideo()
    {
        $searchModel = new GmsVideoHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('video_report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}