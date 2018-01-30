<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MedOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="med-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'patient_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'office_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'discount_name')->textInput() ?>

    <?= $form->field($model, 'representative')->textInput() ?>

    <?= $form->field($model, 'workshift_id')->textInput() ?>

    <?= $form->field($model, 'guarantee_letter')->textInput() ?>

    <?= $form->field($model, 'guarantee_letter_file_path')->textInput() ?>

    <?= $form->field($model, 'guarantee_letter_file_name')->textInput() ?>

    <?= $form->field($model, 'erp_order_id')->textInput() ?>

    <?= $form->field($model, 'create_employee_guid')->textInput() ?>

    <?= $form->field($model, 'create_user_id')->textInput() ?>

    <?= $form->field($model, 'discount_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
