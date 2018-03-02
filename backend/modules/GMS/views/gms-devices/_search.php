<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GmsDevicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gms-devices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sender_id') ?>

    <?= $form->field($model, 'host_name') ?>

    <?= $form->field($model, 'device') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'last_active_at') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'auth_status') ?>

    <?php // echo $form->field($model, 'current_pls_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
