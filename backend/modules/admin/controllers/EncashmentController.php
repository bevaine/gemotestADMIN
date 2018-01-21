<?php

namespace app\modules\admin\controllers;

use common\models\NEncashmentDetail;
use common\models\NWorkshift;
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

        if ($model->load(Yii::$app->request->post())) {
            $summ = $model->total;
            if (isset(Yii::$app->request->post()['arrDetail'])
                && is_array(Yii::$app->request->post()['arrDetail'])
            ) {
                $summ = 0;
                $totalBalance = 0;
                foreach (Yii::$app->request->post()['arrDetail'] as $keyId => $valTotal) {
                    $summ = $summ + strval($valTotal);
                    if ($findModel = NEncashmentDetail::findOne($keyId)) {
                        $findModel->total = $valTotal;
                        if ($findModel->save() && $findModel->target == 'office_summ') {
                            $totalBalance = $findModel->total;
                        }
                    }
                }
            }

            $model->total = $summ;

            if ($model->save()) {
                if ($modelBalance = $model->cashBalanceInLOFlow) {
                    $modelBalance->date = $model->date;
                    if (!empty($totalBalance)) {
                        $modelBalance->total = "-" . $totalBalance;
                    }
                    $modelBalance->operation = 'Инкассация EncashmentID:' . $model->id . ' общая сумма инкассации = ' . $model->total;
                    $modelBalance->save();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NEncashment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreateEncashment($id)
    {
        $model = NWorkshift::findOne($id);
       // if ($model->load(Yii::$app->request->post()) && $model->save()) {

        if ($model) {
            $modelEncashment = new NEncashment();
            $modelEncashment->date = date(
                'Y-m-d H:i:s.000',
                strtotime($model->open_date) + 1 * 60 * 60
            );
            $modelEncashment->sender_key = $model->sender_key;
            $modelEncashment->user_aid = $model->user_aid;

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
            foreach ($modelDetail as $rowDetail) {
                /** @var $rowDetail NEncashmentDetail */
                $rowDetail->delete();
            }
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
