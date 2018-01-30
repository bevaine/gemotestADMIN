<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MedOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="med-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'patient_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'office_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'discount_name') ?>

    <?php // echo $form->field($model, 'representative') ?>

    <?php // echo $form->field($model, 'workshift_id') ?>

    <?php // echo $form->field($model, 'guarantee_letter') ?>

    <?php // echo $form->field($model, 'guarantee_letter_file_path') ?>

    <?php // echo $form->field($model, 'guarantee_letter_file_name') ?>

    <?php // echo $form->field($model, 'erp_order_id') ?>

    <?php // echo $form->field($model, 'create_employee_guid') ?>

    <?php // echo $form->field($model, 'create_user_id') ?>

    <?php // echo $form->field($model, 'discount_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
