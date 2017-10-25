<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\t23 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="t23-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'q1')->textInput() ?>

    <?= $form->field($model, 'q2')->textInput() ?>

    <?= $form->field($model, 'q3')->textInput() ?>

    <?= $form->field($model, 'q4')->textInput() ?>

    <?= $form->field($model, 'q5')->textInput() ?>

    <?= $form->field($model, 'q6')->textInput() ?>

    <?= $form->field($model, 'q7')->textInput() ?>

    <?= $form->field($model, 'q8')->textInput() ?>

    <?= $form->field($model, 'q9')->textInput() ?>

    <?= $form->field($model, 'q10')->textInput() ?>

    <?= $form->field($model, 'q11')->textInput() ?>

    <?= $form->field($model, 'q12')->textInput() ?>

    <?= $form->field($model, 'q13')->textInput() ?>

    <?= $form->field($model, 'q14')->textInput() ?>

    <?= $form->field($model, 'q15')->textInput() ?>

    <?= $form->field($model, 'q16')->textInput() ?>

    <?= $form->field($model, 'q17')->textInput() ?>

    <?= $form->field($model, 'q18')->textInput() ?>

    <?= $form->field($model, 'q19')->textInput() ?>

    <?= $form->field($model, 'q20')->textInput() ?>

    <?= $form->field($model, 'q21')->textInput() ?>

    <?= $form->field($model, 'q22')->textInput() ?>

    <?= $form->field($model, 'q23')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
