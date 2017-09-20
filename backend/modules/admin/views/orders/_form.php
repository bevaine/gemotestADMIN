<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-to-export-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'OrderIDForCACHE')->textInput() ?>

    <?= $form->field($model, 'ExtOrderId')->textInput() ?>

    <?= $form->field($model, 'PatID')->textInput() ?>

    <?= $form->field($model, 'Status')->textInput() ?>

    <?= $form->field($model, 'DateIns')->textInput() ?>

    <?= $form->field($model, 'LastUpdate')->textInput() ?>

    <?= $form->field($model, 'DateReg')->textInput() ?>

    <?= $form->field($model, 'TypeUserIns')->textInput() ?>

    <?= $form->field($model, 'KeyUserIns')->textInput() ?>

    <?= $form->field($model, 'TypeUserReg')->textInput() ?>

    <?= $form->field($model, 'KeyUserReg')->textInput() ?>

    <?= $form->field($model, 'TypeUserDel')->textInput() ?>

    <?= $form->field($model, 'KeyUserDel')->textInput() ?>

    <?= $form->field($model, 'DateDel')->textInput() ?>

    <?= $form->field($model, 'CacheOrderID')->textInput() ?>

    <?= $form->field($model, 'OrderCost')->textInput() ?>

    <?= $form->field($model, 'OrderDiscountID')->textInput() ?>

    <?= $form->field($model, 'OrderDiscount')->textInput() ?>

    <?= $form->field($model, 'OrderAllCost')->textInput() ?>

    <?= $form->field($model, 'PercentOfComplite')->textInput() ?>

    <?= $form->field($model, 'BeremDays')->textInput() ?>

    <?= $form->field($model, 'UpdateCounts')->textInput() ?>

    <?= $form->field($model, 'OrderKontragentID')->textInput() ?>

    <?= $form->field($model, 'OrderDoctorID')->textInput() ?>

    <?= $form->field($model, 'OrderDoctorKID')->textInput() ?>

    <?= $form->field($model, 'OrderDoctorUZIID')->textInput() ?>

    <?= $form->field($model, 'OrderDoctorType')->textInput() ?>

    <?= $form->field($model, 'DayOfCycle')->textInput() ?>

    <?= $form->field($model, 'UniversalOrderID')->textInput() ?>

    <?= $form->field($model, 'IsExported')->textInput() ?>

    <?= $form->field($model, 'DateExport')->textInput() ?>

    <?= $form->field($model, 'InsUserIP')->textInput() ?>

    <?= $form->field($model, 'PayType')->textInput() ?>

    <?= $form->field($model, 'BeforeDeleteStatus')->textInput() ?>

    <?= $form->field($model, 'FromImport')->textInput() ?>

    <?= $form->field($model, 'New')->textInput() ?>

    <?= $form->field($model, 'garantletter')->textInput() ?>

    <?= $form->field($model, 'cito')->textInput() ?>

    <?= $form->field($model, 'checkPrintStatus')->textInput() ?>

    <?= $form->field($model, 'version')->textInput() ?>

    <?= $form->field($model, 'Prdstavitel')->textInput() ?>

    <?= $form->field($model, 'notifyQueued')->textInput() ?>

    <?= $form->field($model, 'isEditing')->textInput() ?>

    <?= $form->field($model, 'isComplite')->textInput() ?>

    <?= $form->field($model, 'CompliteDate')->textInput() ?>

    <?= $form->field($model, 'External')->textInput() ?>

    <?= $form->field($model, 'req_passport')->textInput() ?>

    <?= $form->field($model, 'PatMedHistory')->textInput() ?>

    <?= $form->field($model, 'PatDepartment')->textInput() ?>

    <?= $form->field($model, 'DateResult')->textInput() ?>

    <?= $form->field($model, 'order_num')->textInput() ?>

    <?= $form->field($model, 'percent_complete')->textInput() ?>

    <?= $form->field($model, 'sms_status')->textInput() ?>

    <?= $form->field($model, 'file_guarantee_letter')->textInput() ?>

    <?= $form->field($model, 'saved_status')->textInput() ?>

    <?= $form->field($model, 'card_issued')->textInput() ?>

    <?= $form->field($model, 'erp_order_id')->textInput() ?>

    <?= $form->field($model, 'bonuses')->textInput() ?>

    <?= $form->field($model, 'discount_card')->textInput() ?>

    <?= $form->field($model, 'bonus_balance')->textInput() ?>

    <?= $form->field($model, 'is_print_blank')->textInput() ?>

    <?= $form->field($model, 'disabled_discount')->textInput() ?>

    <?= $form->field($model, 'auto_discount_id')->textInput() ?>

    <?= $form->field($model, 'kd_status')->textInput() ?>

    <?= $form->field($model, 'payment_type')->textInput() ?>

    <?= $form->field($model, 'parentOrderId')->textInput() ?>

    <?= $form->field($model, 'workshift_id')->textInput() ?>

    <?= $form->field($model, 'create_employee_guid')->textInput() ?>

    <?= $form->field($model, 'register_employee_guid')->textInput() ?>

    <?= $form->field($model, 'sync_with_lc_status')->textInput() ?>

    <?= $form->field($model, 'read_only')->textInput() ?>

    <?= $form->field($model, 'create_user_id')->textInput() ?>

    <?= $form->field($model, 'register_user_id')->textInput() ?>

    <?= $form->field($model, 'sync_with_lc_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
