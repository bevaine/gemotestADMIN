<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZaborSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="input-order-iskl-issl-mszabor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'aid') ?>

    <?= $form->field($model, 'OrderID') ?>

    <?= $form->field($model, 'IsslCode') ?>

    <?= $form->field($model, 'MSZabor') ?>

    <?= $form->field($model, 'DateIns') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
