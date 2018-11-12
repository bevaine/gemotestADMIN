<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rep-period-lab-gemotest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date_start') ?>

    <?= $form->field($model, 'date_end') ?>

    <?= $form->field($model, 'sender_id') ?>

    <?= $form->field($model, 'login') ?>

    <?php // echo $form->field($model, 'pass') ?>

    <?php // echo $form->field($model, 'date_active') ?>

    <?php // echo $form->field($model, 'reward') ?>

    <?php // echo $form->field($model, 'test_period') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
