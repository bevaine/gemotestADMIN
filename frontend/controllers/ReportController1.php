<?php


namespace frontend\controllers;

use common\models\OrdersToExportSearch;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use PHPExcelAddon;
use PHPExcel_IOFactory;

class ReportController1 extends Controller
{
    // Отчёт по перевесам
    /**
     * @param $keys
     * @param $date_from
     * @param $date_to
     * @return string
     * @throws NotFoundHttpException
     */
    function actionDoctorReport($keys, $date_from, $date_to)
    {
        if (!Yii::$app->user->can('Report.DoctorsOrders')) {
            throw new NotFoundHttpException('В доступе отказано');
        }
//        $this->render('doctor_report_index');

        $searchModel = new OrdersToExportSearch();

        if (!is_null($date_from) && !is_null($date_to)) {
            $searchModel->date_from = date('Y-m-d', strtotime($date_from));
            $searchModel->date_to = date('Y-m-d', strtotime($date_from));
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report_doctor1', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionViewDoctorGemotest()
    {
        if (!Yii::$app->user->can('Report.DoctorsOrders')) {
            throw new NotFoundHttpException('В доступе отказано');
        }

        $keys = Yii::$app->request->queryParams['keys'];
        $date_from = Yii::$app->request->queryParams['date_from'];
        $date_to = Yii::$app->request->queryParams['date_to'];

        if (!empty($keys)) {
            $exp = explode(',', $keys);
            array_walk($exp, create_function('&$v,$k', '$v = Yii::app()->db->quoteValue($v);'));
            $keys = implode(",", $exp);

            $sql = "select
                        l.[Name],
                        o.order_num,
                        o.datereg,
                        o.OrderKontragentID,
                        o.orderallcost
                    from 
                        OrdersToExport o 
                    left Join 
                        Logins l on o.OrderDoctorID=l.[Key]
                    where 
                      o.OrderDoctorID in ($keys) 
                      and o.[Status] = '2' 
                      and l.UserType = '4'";

            if (!empty($date_from)) {
                $sql .= "and o.datereg >= " . Yii::$app->db->quoteValue($date_from);
            }
            if (!empty($date_to)) {
                $sql .= "and o.datereg <= " . Yii::$app->db->quoteValue($date_to);
            }
            $sql .= "order by l.[Name], o.datereg asc";

            $doctors = Yii::$app->GemoTestDB
                ->createCommand($sql)
                ->queryAll();

            $i = 0;
            $sum = 0;
            if (!Yii::$app->request->isAjax) {
                if ($doctors) {
                    foreach ($doctors as $key => $value) {
                        $doctors[$key]['i'] = ++$i;
                        $sum += $doctors[$key]['orderallcost'];
                    }
                    $filename = 'rep_doctor_gemotest.xlsx';
                    $doc_convert = PHPExcelAddon::convert($_SERVER['DOCUMENT_ROOT'] . '/protected/views/report/template-doc/' . $filename,
                        array(
                            'data' => $doctors,
                            'total' => $sum,
                        ));
                    $objWriter = PHPExcel_IOFactory::createWriter($doc_convert, 'Excel2007');
                    header("Last-Modified: " . gmdate("D, d M Y H(idea)(worry)") . " GMT");
                    header("Cache-Control: no-store, no-cache, must-revalidate");
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Отчет_По_Перевесам.xlsx"');
                    $objWriter->save('php://output');
                }
            } else {
                if ($doctors) {
                    $body = '';
                    foreach ($doctors as $doctor) {
                        $body .= "<tr>";
                        $body .= "<td>" . ++$i . "</td>";
                        $body .= "<td>" . $doctor['Name'] . "</td>";
                        $body .= "<td><a href='/inputOrder/inputMain_test.php?oid=".$doctor['order_num']."'>".$doctor['order_num']."</a></td>";
                        $body .= "<td>" . date('Y-m-d H:i:s', strtotime($doctor['datereg'])) . "</td>";
                        $body .= "<td>" . $doctor['OrderKontragentID'] . "</td>";
                        $body .= "<td>" . $doctor['orderallcost'] . "</td>";
                        $body .= "</tr>";
                        $sum += $doctor['orderallcost'];
                    }
                    $body .= "<tr><td  colspan='5'><b>ИТОГО:</b></td><td>".$sum."</td></tr>";
                    $table = "<table border='1' class='med_services_report' width='100%'>";
                    $table .= "<tr class='head'><td>№</td><td>Врач</td><td>Номер заказа</td><td>Дата регистрации</td><td>Отправитель</td><td>Стоимость заказа</td></tr>";
                    $table .= $body;
                    $table .= "</table>";
                } else {
                    $table = "<table width='100%'>
                            <thead></thead>
                            <tfoot></tfoot>
                            <tbody><tr><td colspan=3 style='text-align:center'>
                            <font style='font-family:Arial;font-size:12px;'>&nbsp;<br>
                            <img src='../img/find2.png' width='32px' height='32px' style='vertical-align: middle; border:0px;'><br>
                            данные не определены: измените условия поиска и повторите попытку...</font><br>&nbsp;</td></tr></tbody></table>";
                }
                echo $table;
            }
        }
    }

    /**
     * @param null $q
     */
    public function actionAjaxDoctorList($q = null)
    {
        if (!Yii::$app->user->can('Report.DoctorsOrders')) {
            throw new NotFoundHttpException('В доступе отказано');
        }

        if (!empty($q)) {
            $sql = "select TOP 20 [key], [Name] FROM Logins WHERE UserType = '4' AND ([key] LIKE '$q' OR [Name] LIKE '%$q%')";
            $doctors = Yii::$app->GemoTestDB
                ->createCommand($sql)
                ->queryAll();
        } else {
            $doctors = Yii::$app->GemoTestDB
                ->createCommand("select TOP 20 [key], [Name] FROM Logins WHERE UserType = '4'")
                ->queryAll();
        }

        $out = [];
        foreach ($doctors as $doctor) {
            $out[] = ['id' => $doctor['key'], 'name' => $doctor['Name']];
        }
        echo json_encode($out);
    }
}