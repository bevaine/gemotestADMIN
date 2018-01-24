<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FranchazySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="franchazy-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AID') ?>

    <?= $form->field($model, 'Active') ?>

    <?= $form->field($model, 'Login') ?>

    <?= $form->field($model, 'Pass') ?>

    <?= $form->field($model, 'Name') ?>

    <?php // echo $form->field($model, 'IsOperator') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'IsAdmin') ?>

    <?php // echo $form->field($model, 'Key') ?>

    <?php // echo $form->field($model, 'BlankText') ?>

    <?php // echo $form->field($model, 'BlankName') ?>

    <?php // echo $form->field($model, 'Logo') ?>

    <?php // echo $form->field($model, 'LogoText') ?>

    <?php // echo $form->field($model, 'LogoText2') ?>

    <?php // echo $form->field($model, 'LogoType') ?>

    <?php // echo $form->field($model, 'LogoWidth') ?>

    <?php // echo $form->field($model, 'TextPaddingLeft') ?>

    <?php // echo $form->field($model, 'OpenExcel') ?>

    <?php // echo $form->field($model, 'EngVersion') ?>

    <?php // echo $form->field($model, 'InputOrder') ?>

    <?php // echo $form->field($model, 'PriceID') ?>

    <?php // echo $form->field($model, 'CanRegister') ?>

    <?php // echo $form->field($model, 'InputOrderRM') ?>

    <?php // echo $form->field($model, 'OpenActive') ?>

    <?php // echo $form->field($model, 'ReestrUslug') ?>

    <?php // echo $form->field($model, 'LCN') ?>

    <?php // echo $form->field($model, 'Li_cOrg') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
