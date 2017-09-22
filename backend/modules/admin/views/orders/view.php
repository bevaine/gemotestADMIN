<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExport */

$this->title = $model->AID;
$this->params['breadcrumbs'][] = ['label' => 'Orders To Exports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-to-export-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->AID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'AID',
            'OrderIDForCACHE',
            'ExtOrderId',
            'PatID',
            'Status',
            'DateIns',
            'LastUpdate',
            'DateReg',
            'TypeUserIns',
            'KeyUserIns',
            'TypeUserReg',
            'KeyUserReg',
            'TypeUserDel',
            'KeyUserDel',
            'DateDel',
            'CacheOrderID',
            'OrderCost',
            'OrderDiscountID',
            'OrderDiscount',
            'OrderAllCost',
            'PercentOfComplite',
            'BeremDays',
            'UpdateCounts',
            'OrderKontragentID',
            'OrderDoctorID',
            'OrderDoctorKID',
            'OrderDoctorUZIID',
            'OrderDoctorType',
            'DayOfCycle',
            'UniversalOrderID',
            'IsExported',
            'DateExport',
            'InsUserIP',
            'PayType',
            'BeforeDeleteStatus',
            'FromImport',
            'New',
            'garantletter',
            'cito',
            'checkPrintStatus',
            'version',
            'Prdstavitel',
            'notifyQueued',
            'isEditing',
            'isComplite',
            'CompliteDate',
            'External',
            'req_passport',
            'PatMedHistory',
            'PatDepartment',
            'DateResult',
            'order_num',
            'percent_complete',
            'sms_status',
            'file_guarantee_letter',
            'saved_status',
            'card_issued',
            'erp_order_id',
            'bonuses',
            'discount_card',
            'bonus_balance',
            'is_print_blank',
            'disabled_discount',
            'auto_discount_id',
            'kd_status',
            'payment_type',
            'parentOrderId',
            'workshift_id',
            'create_employee_guid',
            'register_employee_guid',
            'sync_with_lc_status',
            'read_only',
            'create_user_id',
            'register_user_id',
            'sync_with_lc_date',
        ],
    ]) ?>

</div>