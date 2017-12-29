<?php
namespace app\modules\wiki\controllers;

use app\modules\wiki\models\Wiki;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\wiki\Module;

/**
 * WikiController implements the CRUD actions for Wiki model.
 *
 * @author Pascal Mueller, AS infotrack AG
 * @link http://www.asinfotrack.ch
 * @license MIT
 */
class ContentController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs'=>[
				'class'=>VerbFilter::className(),
				'actions'=>[
					'delete'=>['post'],
				],
			],
		];
	}

	/**
	 * Admin action to manage wiki pages
	 *
	 * @return string
	 */
	public function actionAdmin()
	{
		$searchModelClassName = Module::getInstance()->searchModelClass;
		$searchModel = new $searchModelClassName();
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		return $this->render(Module::getInstance()->viewMap['admin'], [
			'dataProvider'=>$dataProvider,
			'searchModel'=>$searchModel,
		]);
	}

	/**
	 * Redirection to the index wiki article
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$articleId = Module::getInstance()->indexArticleId;
		return $this->redirect(['view', 'id'=>$articleId]);
	}

	/**
	 * Displays a single Wiki model.
	 *
	 * @param string $id the id of the article to show
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		if ($model === null) {
			return $this->redirect(['create', 'id'=>$id]);
		}

		return $this->render(Module::getInstance()->viewMap['view'], [
			'model'=>$model,
		]);
	}

	/**
	 * Creates a new Wiki model. If creation is successful, the browser will be
	 * redirected to the 'view' page of the created article.
	 *
	 * @param string $id the id of the article to create
	 * @return mixed
	 */
	public function actionCreate($id)
	{
		//redirect to valid id if the current one is not invalid
		if (!Module::getInstance()->isValidArticleId($id)) {
			$validId = Module::getInstance()->createArticleId($id);
			return $this->redirect(['create', 'id'=>$validId]);
		}

		//get the model class name
		$modelClassName = Module::getInstance()->modelClass;

		//if the id already exists, go to update action
		$model = $modelClassName::findOne($id);
		if ($model !== null) {
			return $this->redirect(['update', 'id'=>$model->id]);
		}

		//otherwise create it
		$model = new $modelClassName();
		$loaded = $model->load(Yii::$app->request->post());
		if (!$loaded) $model->id = strtolower($id);

		if ($loaded && $model->save()) {
			return $this->redirect(['view', 'id'=>$model->id]);
		} else {
			return $this->render(Module::getInstance()->viewMap['create'], [
				'model'=>$model,
			]);
		}
	}

	/**
	 * Updates an existing Wiki model. If the update is successful, the browser
	 * will be redirected to the 'view' page of the updated article
	 *
	 * @param string $id the id of the article to update
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if ($model === null) return $this->redirect(['create', 'id'=>$id]);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id'=>$model->id]);
		} else {
			return $this->render(Module::getInstance()->viewMap['update'], [
				'model'=>$model,
			]);
		}
	}

	/**
	 * Deletes an existing Wiki model. If the deletion is successful, the browser
	 * will be redirected to the 'index' page.
	 *
	 * @param string $id id of the article to be deleted
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(['admin']);
	}

	/**
	 * Finds the Wiki model based on its primary key value. If the model is not found,
	 * a 404 HTTP exception will be thrown.
	 *
	 * @param string $id id of the article to find
	 * @return Wiki the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$modelClassName = Module::getInstance()->modelClass;

		if (($model = $modelClassName::findOne($id)) !== null) {
			return $model;
		} else {
			return null;
		}
	}

}
