<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nworkshift-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_aid')->textInput() ?>

    <?= $form->field($model, 'sender_key')->textInput() ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'z_num')->textInput() ?>

    <?= $form->field($model, 'open_date')->textInput() ?>

    <?= $form->field($model, 'close_date')->textInput() ?>

    <?= $form->field($model, 'not_zero_sum_start')->textInput() ?>

    <?= $form->field($model, 'not_zero_sum_end')->textInput() ?>

    <?= $form->field($model, 'amount_cash_register')->textInput() ?>

    <?= $form->field($model, 'sender_key_close')->textInput() ?>

    <?= $form->field($model, 'error_check_count')->textInput() ?>

    <?= $form->field($model, 'error_check_total_cash')->textInput() ?>

    <?= $form->field($model, 'error_check_total_card')->textInput() ?>

    <?= $form->field($model, 'error_check_return_count')->textInput() ?>

    <?= $form->field($model, 'error_check_return_total_cash')->textInput() ?>

    <?= $form->field($model, 'error_check_return_total_card')->textInput() ?>

    <?= $form->field($model, 'file_name')->textInput() ?>

    <?= $form->field($model, 'code_1c')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
