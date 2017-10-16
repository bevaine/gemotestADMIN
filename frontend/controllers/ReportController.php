<?php

namespace frontend\controllers;

use Yii;
use common\models\Logins;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use common\models\OrdersToExportSearch;

class ReportController extends Controller
{
    // Отчёт по перевесам
    /**
     * @throws ForbiddenHttpException
     */
    function actionDoctorReport()
    {
        set_time_limit(0);

        if (!Yii::$app->user->can('Report.DoctorsOrders')) {
            throw new ForbiddenHttpException('В доступе отказано');
        }

        $searchModel = new OrdersToExportSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report_doctor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param null $q
     * @throws ForbiddenHttpException
     */
    public function actionAjaxDoctorList($q = null)
    {
        if (!Yii::$app->user->can('Report.DoctorsOrders')) {
            throw new ForbiddenHttpException('В доступе отказано');
        }

        if (!empty($q)) {
            $searchLogins = Logins::find()
                ->where(['UserType' => '4'])
                ->andfilterWhere([
                    'or',
                    ['like', '[key]', $q],
                    ['like', '[Name]', $q],
                ])->limit(20)->all();

            $out = [];
            if ($searchLogins) {
                /** @var Logins $Logins */
                foreach ($searchLogins as $Logins) {
                    $out[] = ['id' => $Logins->Key, 'name' => $Logins->Name];
                }
            }
            echo json_encode($out);
        }
    }
}