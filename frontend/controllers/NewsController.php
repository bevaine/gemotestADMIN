<?php

namespace frontend\controllers;

use common\models\Logins;
use common\models\Patients;
use Yii;
use common\models\NewsBlog;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for NewsBlog model.
 */
class NewsController extends Controller
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
     * Lists all NewsBlog models.
     * @return mixed
     */
    public function actionIndex()
    {
        self::parseCSV("./files/export.csv");
    }


    public static function parseCSV($file_name)
    {
        $row = 1;
        if (($handle = fopen($file_name, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $date = trim($data[0]);
                $sum = trim($data[1]);
                $fio = iconv('Windows-1251', 'UTF-8', trim($data[2]));
                $email = trim($data[3]);
                $phone = trim($data[4]);
                $city = iconv('Windows-1251', 'UTF-8',trim($data[5]));
                $older = iconv('Windows-1251', 'UTF-8',trim($data[6]));
                /** @var Patients $findModel */
                $findModel = Patients::find()
                    ->where(['email' => $email])
                    ->one();

                //print_r($findModel);
                if ($findModel) {
                    echo $findModel->LastName." ".$findModel->Name." -> ".$fio;
                    //break;
                }


//
//                $num = count($data);
//                //echo "<p> $num полей в строке $row: <br /></p>\n";
//                $row++;
//                for ($c=0; $c < $num; $c++) {
//                    $result = iconv('Windows-1251', 'UTF-8', $data[$c]);
//                    echo $result . "<br />\n";
//                }
            }
            fclose($handle);
        }
    }

    /**
     * Displays a single NewsBlog model.
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
     * Creates a new NewsBlog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NewsBlog();

        if ($model->load(Yii::$app->request->post())) {

            if (!$model->validate()) { //todo валидация данных

                Yii::$app->session->setFlash('alert', [
                    'options'=>['class'=>'alert-danger'],
                    'body'=>Yii::t('backend', 'Не удалось добавить новость!')
                ]);
            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing NewsBlog model.
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
     * Deletes an existing NewsBlog model.
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
     * Finds the NewsBlog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NewsBlog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NewsBlog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
