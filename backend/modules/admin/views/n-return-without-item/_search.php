<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnWithoutItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nreturn-without-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'kkm') ?>

    <?php // echo $form->field($model, 'z_num') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'path_file') ?>

    <?php // echo $form->field($model, 'base') ?>

    <?php // echo $form->field($model, 'user_aid') ?>

    <?php // echo $form->field($model, 'code_1c') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
