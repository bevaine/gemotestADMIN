<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DoctorSpec */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doctor-spec-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Name')->textInput() ?>

    <?= $form->field($model, 'LastName')->textInput() ?>

    <?= $form->field($model, 'SpetialisationID')->textInput() ?>

    <?= $form->field($model, 'Active')->textInput() ?>

    <?= $form->field($model, 'GroupID')->textInput() ?>

    <?= $form->field($model, 'Fkey')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
