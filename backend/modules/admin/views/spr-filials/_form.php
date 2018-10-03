<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SprFilials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spr-filials-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Fkey')->textInput() ?>

    <?= $form->field($model, 'Fname')->textInput() ?>

    <?= $form->field($model, 'Type')
        ->dropDownList(\common\models\Logins::getTypesArray(), ['prompt' => '---']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
