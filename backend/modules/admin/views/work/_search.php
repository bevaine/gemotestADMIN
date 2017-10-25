<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\t23Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="t23-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'q1') ?>

    <?= $form->field($model, 'q2') ?>

    <?= $form->field($model, 'q3') ?>

    <?= $form->field($model, 'q4') ?>

    <?= $form->field($model, 'q5') ?>

    <?php // echo $form->field($model, 'q6') ?>

    <?php // echo $form->field($model, 'q7') ?>

    <?php // echo $form->field($model, 'q8') ?>

    <?php // echo $form->field($model, 'q9') ?>

    <?php // echo $form->field($model, 'q10') ?>

    <?php // echo $form->field($model, 'q11') ?>

    <?php // echo $form->field($model, 'q12') ?>

    <?php // echo $form->field($model, 'q13') ?>

    <?php // echo $form->field($model, 'q14') ?>

    <?php // echo $form->field($model, 'q15') ?>

    <?php // echo $form->field($model, 'q16') ?>

    <?php // echo $form->field($model, 'q17') ?>

    <?php // echo $form->field($model, 'q18') ?>

    <?php // echo $form->field($model, 'q19') ?>

    <?php // echo $form->field($model, 'q20') ?>

    <?php // echo $form->field($model, 'q21') ?>

    <?php // echo $form->field($model, 'q22') ?>

    <?php // echo $form->field($model, 'q23') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
