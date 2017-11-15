<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MedReturnOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="med-return-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'is_virtual') ?>

    <?php // echo $form->field($model, 'kkm') ?>

    <?php // echo $form->field($model, 'z_num') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'pay_type_original') ?>

    <?php // echo $form->field($model, 'is_freepay') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
