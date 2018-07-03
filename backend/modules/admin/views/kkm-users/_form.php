<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nkkm-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kkm_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'login')->textInput() ?>

    <?= $form->field($model, 'password')->textInput() ?>

    <?= $form->field($model, 'user_type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
