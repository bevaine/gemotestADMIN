<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrdersToExportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders To Exports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-to-export-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'order_num',
            //'AID',
            //'OrderIDForCACHE',
            //'ExtOrderId',
            'PatID',
            'Status',
            // 'DateIns',
            // 'LastUpdate',
            // 'DateReg',
            // 'TypeUserIns',
             'KeyUserIns',
            // 'TypeUserReg',
             'KeyUserReg',
            // 'TypeUserDel',
             'KeyUserDel',
            // 'DateDel',
            // 'CacheOrderID',
            // 'OrderCost',
            // 'OrderDiscountID',
            // 'OrderDiscount',
            // 'OrderAllCost',
            // 'PercentOfComplite',
             'BeremDays',
            // 'UpdateCounts',
            // 'OrderKontragentID',
             'OrderDoctorID',
            // 'OrderDoctorKID',
            // 'OrderDoctorUZIID',
            // 'OrderDoctorType',
            // 'DayOfCycle',
            // 'UniversalOrderID',
            // 'IsExported',
             'DateExport',
            // 'InsUserIP',
            // 'PayType',
            // 'BeforeDeleteStatus',
            // 'FromImport',
            // 'New',
            // 'garantletter',
            // 'cito',
            // 'checkPrintStatus',
            // 'version',
            // 'Prdstavitel',
            // 'notifyQueued',
            // 'isEditing',
            // 'isComplite',
            // 'CompliteDate',
            // 'External',
            // 'req_passport',
            // 'PatMedHistory',
            // 'PatDepartment',
            // 'DateResult',

            // 'percent_complete',
            // 'sms_status',
            // 'file_guarantee_letter',
            // 'saved_status',
            // 'card_issued',
            // 'erp_order_id',
            // 'bonuses',
            // 'discount_card',
            // 'bonus_balance',
            // 'is_print_blank',
            // 'disabled_discount',
            // 'auto_discount_id',
            // 'kd_status',
             'payment_type',
            // 'parentOrderId',
             'workshift_id',
            // 'create_employee_guid',
            // 'register_employee_guid',
             'sync_with_lc_status',
            // 'read_only',
             'create_user_id',
             'register_user_id',
             'sync_with_lc_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
