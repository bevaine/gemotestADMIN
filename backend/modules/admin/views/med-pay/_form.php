<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MedPay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="med-pay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'patient_id')->textInput() ?>

    <?= $form->field($model, 'patient_fio')->textInput() ?>

    <?= $form->field($model, 'patient_phone')->textInput() ?>

    <?= $form->field($model, 'patient_birthday')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'user_username')->textInput() ?>

    <?= $form->field($model, 'office_id')->textInput() ?>

    <?= $form->field($model, 'office_name')->textInput() ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'discount_total')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'printlist')->textInput() ?>

    <?= $form->field($model, 'user_fio')->textInput() ?>

    <?= $form->field($model, 'free_pay')->textInput() ?>

    <?= $form->field($model, 'base_doc_type')->textInput() ?>

    <?= $form->field($model, 'base_doc_id')->textInput() ?>

    <?= $form->field($model, 'is_virtual')->textInput() ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'z_num')->textInput() ?>

    <?= $form->field($model, 'pay_type_original')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
