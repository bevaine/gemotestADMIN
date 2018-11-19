<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SmsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'tz') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'enqueued')->checkbox() ?>

    <?php // echo $form->field($model, 'attempt') ?>

    <?php // echo $form->field($model, 'provider_id') ?>

    <?php // echo $form->field($model, 'provider_sms_id') ?>

    <?php // echo $form->field($model, 'deliver_sm') ?>

    <?php // echo $form->field($model, 'bounce_reason') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'callback') ?>

    <?php // echo $form->field($model, 'attempts_get_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
