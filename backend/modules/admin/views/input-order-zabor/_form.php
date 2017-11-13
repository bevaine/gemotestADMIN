<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="input-order-iskl-issl-mszabor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'OrderID')->textInput() ?>

    <?= $form->field($model, 'IsslCode')->textInput() ?>

    <?= $form->field($model, 'MSZabor')->textInput() ?>

    <?= $form->field($model, 'DateIns')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
