<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshiftSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nworkshift-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_aid') ?>

    <?= $form->field($model, 'sender_key') ?>

    <?= $form->field($model, 'kkm') ?>

    <?= $form->field($model, 'z_num') ?>

    <?php // echo $form->field($model, 'open_date') ?>

    <?php // echo $form->field($model, 'close_date') ?>

    <?php // echo $form->field($model, 'not_zero_sum_start') ?>

    <?php // echo $form->field($model, 'not_zero_sum_end') ?>

    <?php // echo $form->field($model, 'amount_cash_register') ?>

    <?php // echo $form->field($model, 'sender_key_close') ?>

    <?php // echo $form->field($model, 'error_check_count') ?>

    <?php // echo $form->field($model, 'error_check_total_cash') ?>

    <?php // echo $form->field($model, 'error_check_total_card') ?>

    <?php // echo $form->field($model, 'error_check_return_count') ?>

    <?php // echo $form->field($model, 'error_check_return_total_cash') ?>

    <?php // echo $form->field($model, 'error_check_return_total_card') ?>

    <?php // echo $form->field($model, 'file_name') ?>

    <?php // echo $form->field($model, 'code_1c') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
