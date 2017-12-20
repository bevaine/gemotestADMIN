<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NEncashmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nencashment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sender_key') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'user_aid') ?>

    <?= $form->field($model, 'receipt_number') ?>

    <?php // echo $form->field($model, 'receipt_file') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'code_1c') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
