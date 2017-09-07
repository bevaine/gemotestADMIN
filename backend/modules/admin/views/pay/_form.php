<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NPay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="npay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'order_num')->textInput() ?>

    <?= $form->field($model, 'order_data')->textInput() ?>

    <?= $form->field($model, 'base_doc_id')->textInput() ?>

    <?= $form->field($model, 'base_doc_type')->textInput() ?>

    <?= $form->field($model, 'base_doc_date')->textInput() ?>

    <?= $form->field($model, 'patient_id')->textInput() ?>

    <?= $form->field($model, 'patient_fio')->textInput() ?>

    <?= $form->field($model, 'patient_phone')->textInput() ?>

    <?= $form->field($model, 'patient_birthday')->textInput() ?>

    <?= $form->field($model, 'login_id')->textInput() ?>

    <?= $form->field($model, 'login_key')->textInput() ?>

    <?= $form->field($model, 'login_type')->textInput() ?>

    <?= $form->field($model, 'login_fio')->textInput() ?>

    <?= $form->field($model, 'sender_id')->textInput() ?>

    <?= $form->field($model, 'sender_name')->textInput() ?>

    <?= $form->field($model, 'pay_type')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'discount_card')->textInput() ?>

    <?= $form->field($model, 'discount_id')->textInput() ?>

    <?= $form->field($model, 'discount_name')->textInput() ?>

    <?= $form->field($model, 'discount_percent')->textInput() ?>

    <?= $form->field($model, 'bonus')->textInput() ?>

    <?= $form->field($model, 'discount_total')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'cito_factor')->textInput() ?>

    <?= $form->field($model, 'bonus_balance')->textInput() ?>

    <?= $form->field($model, 'printlist')->textInput() ?>

    <?= $form->field($model, 'free_pay')->textInput() ?>

    <?= $form->field($model, 'app_version')->textInput() ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'z_num')->textInput() ?>

    <?= $form->field($model, 'pay_type_original')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
