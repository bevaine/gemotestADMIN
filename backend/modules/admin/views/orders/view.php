<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Logins;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExport */

$this->title = $model->AID;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-to-export-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->AID], [
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
            [
                'attribute' => 'TypeUserIns',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if (array_key_exists($model->TypeUserIns, \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model->TypeUserIns];
                    } else return $model->TypeUserIns;
                }
            ],
            [
                'attribute' => 'KeyUserIns',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if ($modelLogins = Logins::getUserByKey($model->TypeUserIns, $model->KeyUserIns)) {
                        return $modelLogins->Name;
                    } else return false;
                }
            ],
            [
                'attribute' => 'TypeUserReg',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if (array_key_exists($model->TypeUserReg, \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model->TypeUserReg];
                    } else return $model->TypeUserReg;
                }
            ],
            [
                'attribute' => 'KeyUserReg',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if ($modelLogins = Logins::getUserByKey($model->TypeUserReg, $model->KeyUserReg)) {
                        return $modelLogins->Name;
                    } else return false;
                }
            ],
            [
                'attribute' => 'TypeUserDel',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if (array_key_exists($model->TypeUserDel, \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model->TypeUserDel];
                    } else return $model->TypeUserDel;
                }
            ],
            [
                'attribute' => 'KeyUserDel',
                'value' => function ($model) {
                    /** @var \common\models\OrdersToExport $model  */
                    if ($modelLogins = Logins::getUserByKey($model->TypeUserDel, $model->KeyUserDel)) {
                        return $modelLogins->Name;
                    } else return false;
                }
            ],
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
