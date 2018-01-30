<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MedPaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="med-pay-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'patient_id') ?>

    <?= $form->field($model, 'patient_fio') ?>

    <?php // echo $form->field($model, 'patient_phone') ?>

    <?php // echo $form->field($model, 'patient_birthday') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_username') ?>

    <?php // echo $form->field($model, 'office_id') ?>

    <?php // echo $form->field($model, 'office_name') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'discount_total') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'printlist') ?>

    <?php // echo $form->field($model, 'user_fio') ?>

    <?php // echo $form->field($model, 'free_pay') ?>

    <?php // echo $form->field($model, 'base_doc_type') ?>

    <?php // echo $form->field($model, 'base_doc_id') ?>

    <?php // echo $form->field($model, 'is_virtual') ?>

    <?php // echo $form->field($model, 'kkm') ?>

    <?php // echo $form->field($model, 'z_num') ?>

    <?php // echo $form->field($model, 'pay_type_original') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
