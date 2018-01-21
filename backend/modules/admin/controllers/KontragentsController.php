<?php

namespace app\modules\admin\controllers;

use Yii;
use common\models\Kontragents;
use common\models\KontragentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * KontragentsController implements the CRUD actions for Kontragents model.
 */
class KontragentsController extends Controller
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
     * Lists all Kontragents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KontragentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kontragents model.
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
     * Creates a new Kontragents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kontragents();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->AID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Kontragents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->AID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Kontragents model.
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
     * Finds the Kontragents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kontragents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kontragents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $search
     * @param null $id
     */
    public function actionAjaxKontragentsList($search = null, $id = null)
    {
        $out = ['more' => false];

        if (!is_null($search)) {
            $search = mb_strtolower($search, 'UTF-8');
            $data = Kontragents::find()->select(['[Key], [Name]'])
                ->where('lower(Name) LIKE \'%' . $search . '%\'')
                ->limit(20)
                ->all();
            /** @var Kontragents $userData */
            foreach ($data as $userData) {
                $out['results'][] = ['id' => $userData->Key, 'text' => $userData->Name];
            }
        } elseif (isset($id)) {
            $out['results'] = ['id' => $id, 'text' => Kontragents::findOne(['key' =>$id])->Name];
        }

        echo Json::encode($out);
    }
}
