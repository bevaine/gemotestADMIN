<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-to-export-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AID') ?>

    <?= $form->field($model, 'OrderIDForCACHE') ?>

    <?= $form->field($model, 'ExtOrderId') ?>

    <?= $form->field($model, 'PatID') ?>

    <?= $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'DateIns') ?>

    <?php // echo $form->field($model, 'LastUpdate') ?>

    <?php // echo $form->field($model, 'DateReg') ?>

    <?php // echo $form->field($model, 'TypeUserIns') ?>

    <?php // echo $form->field($model, 'KeyUserIns') ?>

    <?php // echo $form->field($model, 'TypeUserReg') ?>

    <?php // echo $form->field($model, 'KeyUserReg') ?>

    <?php // echo $form->field($model, 'TypeUserDel') ?>

    <?php // echo $form->field($model, 'KeyUserDel') ?>

    <?php // echo $form->field($model, 'DateDel') ?>

    <?php // echo $form->field($model, 'CacheOrderID') ?>

    <?php // echo $form->field($model, 'OrderCost') ?>

    <?php // echo $form->field($model, 'OrderDiscountID') ?>

    <?php // echo $form->field($model, 'OrderDiscount') ?>

    <?php // echo $form->field($model, 'OrderAllCost') ?>

    <?php // echo $form->field($model, 'PercentOfComplite') ?>

    <?php // echo $form->field($model, 'BeremDays') ?>

    <?php // echo $form->field($model, 'UpdateCounts') ?>

    <?php // echo $form->field($model, 'OrderKontragentID') ?>

    <?php // echo $form->field($model, 'OrderDoctorID') ?>

    <?php // echo $form->field($model, 'OrderDoctorKID') ?>

    <?php // echo $form->field($model, 'OrderDoctorUZIID') ?>

    <?php // echo $form->field($model, 'OrderDoctorType') ?>

    <?php // echo $form->field($model, 'DayOfCycle') ?>

    <?php // echo $form->field($model, 'UniversalOrderID') ?>

    <?php // echo $form->field($model, 'IsExported') ?>

    <?php // echo $form->field($model, 'DateExport') ?>

    <?php // echo $form->field($model, 'InsUserIP') ?>

    <?php // echo $form->field($model, 'PayType') ?>

    <?php // echo $form->field($model, 'BeforeDeleteStatus') ?>

    <?php // echo $form->field($model, 'FromImport') ?>

    <?php // echo $form->field($model, 'New') ?>

    <?php // echo $form->field($model, 'garantletter') ?>

    <?php // echo $form->field($model, 'cito') ?>

    <?php // echo $form->field($model, 'checkPrintStatus') ?>

    <?php // echo $form->field($model, 'version') ?>

    <?php // echo $form->field($model, 'Prdstavitel') ?>

    <?php // echo $form->field($model, 'notifyQueued') ?>

    <?php // echo $form->field($model, 'isEditing') ?>

    <?php // echo $form->field($model, 'isComplite') ?>

    <?php // echo $form->field($model, 'CompliteDate') ?>

    <?php // echo $form->field($model, 'External') ?>

    <?php // echo $form->field($model, 'req_passport') ?>

    <?php // echo $form->field($model, 'PatMedHistory') ?>

    <?php // echo $form->field($model, 'PatDepartment') ?>

    <?php // echo $form->field($model, 'DateResult') ?>

    <?php // echo $form->field($model, 'order_num') ?>

    <?php // echo $form->field($model, 'percent_complete') ?>

    <?php // echo $form->field($model, 'sms_status') ?>

    <?php // echo $form->field($model, 'file_guarantee_letter') ?>

    <?php // echo $form->field($model, 'saved_status') ?>

    <?php // echo $form->field($model, 'card_issued') ?>

    <?php // echo $form->field($model, 'erp_order_id') ?>

    <?php // echo $form->field($model, 'bonuses') ?>

    <?php // echo $form->field($model, 'discount_card') ?>

    <?php // echo $form->field($model, 'bonus_balance') ?>

    <?php // echo $form->field($model, 'is_print_blank') ?>

    <?php // echo $form->field($model, 'disabled_discount') ?>

    <?php // echo $form->field($model, 'auto_discount_id') ?>

    <?php // echo $form->field($model, 'kd_status') ?>

    <?php // echo $form->field($model, 'payment_type') ?>

    <?php // echo $form->field($model, 'parentOrderId') ?>

    <?php // echo $form->field($model, 'workshift_id') ?>

    <?php // echo $form->field($model, 'create_employee_guid') ?>

    <?php // echo $form->field($model, 'register_employee_guid') ?>

    <?php // echo $form->field($model, 'sync_with_lc_status') ?>

    <?php // echo $form->field($model, 'read_only') ?>

    <?php // echo $form->field($model, 'create_user_id') ?>

    <?php // echo $form->field($model, 'register_user_id') ?>

    <?php // echo $form->field($model, 'sync_with_lc_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
