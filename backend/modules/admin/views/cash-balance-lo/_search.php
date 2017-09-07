<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NCashBalanceInLOFlowSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ncash-balance-in-loflow-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cashbalance_id') ?>

    <?= $form->field($model, 'sender_key') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'operation') ?>

    <?php // echo $form->field($model, 'balance') ?>

    <?php // echo $form->field($model, 'workshift_id') ?>

    <?php // echo $form->field($model, 'operation_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
