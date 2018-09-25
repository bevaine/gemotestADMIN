<?php

namespace app\modules\admin\controllers;

use common\components\helpers\ActiveSyncHelper;
use common\models\AddUserForm;
use common\models\Permissions;
use Yii;
use common\models\SkynetRoles;
use common\models\SkynetRolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\log\Logger;

/**
 * SkynetRolesController implements the CRUD actions for SkynetRoles model.
 */
class SkynetRolesController extends Controller
{
    CONST TYPE_SLO = '7';
    CONST TYPE_FLO = '8';
    CONST TYPE_DOC = '5';
    CONST TYPE_GD = '9';

    /**
     * {@inheritdoc}
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
     * Lists all SkynetRoles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SkynetRolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SkynetRoles model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $post
     * @return array|bool
     */
    public static function parseConf($post)
    {
        $newConf = [];

        if (array_key_exists('info', $post)
            && is_array($post['info'])) {
            foreach ($post['info'] as $key => $val) {
                $newConf['info'][$key] = json_decode($val);
            }
        }

        if (empty($post['structure'])
            || !is_array($post['structure']))
            return false;

        $conf = ActiveSyncHelper::getConf();

        foreach ($conf['structure'][$post['SkynetRoles']['type']] as $moduleName => $tables)
        {
            if (!array_key_exists($moduleName, $post['structure']))
                continue;

            $newConf['structure'][] = $moduleName;
            foreach ($conf['structure'][$post['SkynetRoles']['type']][$moduleName] as $tableConf)
            {
                if (!array_key_exists($tableConf, $conf['tables']))
                    continue;

                $tableBase = $conf['tables'][$tableConf];
                if (array_key_exists(ActiveSyncHelper::parseClassPath($tableConf), $post['tables'])) {
                    $tablePost = $post['tables'][ActiveSyncHelper::parseClassPath($tableConf)];
                    $tableBase = array_replace_recursive($tableBase, $tablePost);
                }
                $newConf['tables'][$tableConf] = $tableBase;
            }
        }
        return !empty($newConf) ? $newConf : false;
    }

        /**
     * Creates a new SkynetRoles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SkynetRoles();

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->type !== self::TYPE_SLO) {
                $model->name = AddUserForm::getTypes($model->type);
            }
            $post = Yii::$app->request->post();
            if ($this->addUpdateRole($model, $post)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param SkynetRoles $model
     * @param $post
     * @return bool|\yii\web\Response
     */
    public function addUpdateRole($model, $post)
    {
        if ($newConf = self::parseConf($post)) {
            $model->tables_json = !empty($newConf['tables']) ? json_encode($newConf['tables']) : '';
            $model->structure_json = !empty($newConf['structure']) ? json_encode($newConf['structure']) : '';
            $model->info_json = !empty($newConf['info']) ? json_encode($newConf['info']) : '';
            if ($model->save()) {
                $model->addPermissions($model->permission);
                return true;
            } else {
                Yii::getLogger()->log(
                    $model->errors,
                    Logger::LEVEL_ERROR,
                    'binary'
                );
            }
        }
        return false;
    }



    /**
     * Updates an existing SkynetRoles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $post = Yii::$app->request->post();
            if ($this->addUpdateRole($model, $post)) {
                return $this->redirect(['view', 'id' => $model->id, 'type' => $model->type]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SkynetRoles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        /** @var SkynetRoles $findModel */
        if ($findModel = $this->findModel($id)) {
            Permissions::deleteAll(['department' => $id]);
            $findModel->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the SkynetRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SkynetRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SkynetRoles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
