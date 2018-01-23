<?php

namespace app\modules\admin\controllers;

use common\models\NReturnOrder;
use common\models\NReturnOrderDetail;
use Yii;
use common\models\NReturnWithoutItem;
use common\models\NReturnWithoutItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Transaction;
use yii\log\Logger;
use yii\helpers\Html;

/**
 * NReturnWithoutItemController implements the CRUD actions for NReturnWithoutItem model.
 */
class NReturnWithoutItemController extends Controller
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
     * Lists all NReturnWithoutItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NReturnWithoutItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NReturnWithoutItem model.
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
     * Creates a new NReturnWithoutItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NReturnWithoutItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionCreateParent($id)
    {
        $searchModel = NReturnWithoutItem::findOne($id);

        if ($searchModel && !isset($searchModel->parent_id)) {
            /** @var $transaction Transaction */
            $transaction = NReturnOrder::getDb()->beginTransaction();
            try {
                $model = new NReturnOrder();
                $model->parent_id = $searchModel->order_num;
                $model->parent_type = 1;
                $model->date = $searchModel->date;
                $model->order_num = $searchModel->order_num;
                $model->status = 2;
                $model->total = $searchModel->total;
                $model->user_id = $searchModel->user_aid;
                $model->kkm = $searchModel->kkm;
                $model->sync_with_lc_status = 4;
                $model->sync_with_lc_date = $searchModel->date;

                if ($model->save()) {
                    $transaction->commit();
                    $parent_id = $transaction->db->getLastInsertID();
                    $searchModel->parent_id = $parent_id;

                    $modelDetail = new NReturnOrderDetail();
                    $modelDetail->return_id = $parent_id;
                    $modelDetail->total = $searchModel->total;
                    $modelDetail->price = $searchModel->total;

                    if ($searchModel->save() && $modelDetail->save()) {
                        $urlKey = \yii\helpers\Url::toRoute([
                            '/admin/n-return-order/view',
                            'id' => $searchModel->parent_id
                        ]);
                        $urlKey = Html::a($searchModel->parent_id, $urlKey, [
                            'title' => $searchModel->parent_id,
                            'target' => '_blank'
                        ]);
                        $message = '<p>Для возврата без номенклатуры <b>#'.$searchModel->id.'</b>';
                        $message .= ' был успешно добавлен родитель <b>#'.$urlKey.'</b></p>';
                        Yii::$app->session->setFlash('warning', $message);
                    }
                } else {
                    $transaction->rollBack();
                    Yii::getLogger()->log([
                        'NReturnOrder->save()'=>$model->errors
                    ], Logger::LEVEL_ERROR, 'binary');
                }
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->redirect(['view', 'id' => $searchModel->id]);
    }

    /**
     * Updates an existing NReturnWithoutItem model.
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
     * Deletes an existing NReturnWithoutItem model.
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
     * Finds the NReturnWithoutItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NReturnWithoutItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NReturnWithoutItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
