<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DoctorSpecSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-spec-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AID') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'LastName') ?>

    <?= $form->field($model, 'SpetialisationID') ?>

    <?= $form->field($model, 'Active') ?>

    <?php // echo $form->field($model, 'GroupID') ?>

    <?php // echo $form->field($model, 'Fkey') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
