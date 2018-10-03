<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SprFilialsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spr-filials-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AID') ?>

    <?= $form->field($model, 'Fid') ?>

    <?= $form->field($model, 'Fkey') ?>

    <?= $form->field($model, 'Fname') ?>

    <?= $form->field($model, 'Type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
