<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NPaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="npay-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'order_data') ?>

    <?= $form->field($model, 'base_doc_id') ?>

    <?php // echo $form->field($model, 'base_doc_type') ?>

    <?php // echo $form->field($model, 'base_doc_date') ?>

    <?php // echo $form->field($model, 'patient_id') ?>

    <?php // echo $form->field($model, 'patient_fio') ?>

    <?php // echo $form->field($model, 'patient_phone') ?>

    <?php // echo $form->field($model, 'patient_birthday') ?>

    <?php // echo $form->field($model, 'login_id') ?>

    <?php // echo $form->field($model, 'login_key') ?>

    <?php // echo $form->field($model, 'login_type') ?>

    <?php // echo $form->field($model, 'login_fio') ?>

    <?php // echo $form->field($model, 'sender_id') ?>

    <?php // echo $form->field($model, 'sender_name') ?>

    <?php // echo $form->field($model, 'pay_type') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'discount_card') ?>

    <?php // echo $form->field($model, 'discount_id') ?>

    <?php // echo $form->field($model, 'discount_name') ?>

    <?php // echo $form->field($model, 'discount_percent') ?>

    <?php // echo $form->field($model, 'bonus') ?>

    <?php // echo $form->field($model, 'discount_total') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'cito_factor') ?>

    <?php // echo $form->field($model, 'bonus_balance') ?>

    <?php // echo $form->field($model, 'printlist') ?>

    <?php // echo $form->field($model, 'free_pay') ?>

    <?php // echo $form->field($model, 'app_version') ?>

    <?php // echo $form->field($model, 'kkm') ?>

    <?php // echo $form->field($model, 'z_num') ?>

    <?php // echo $form->field($model, 'pay_type_original') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
