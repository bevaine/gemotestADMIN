<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LoginsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logins-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'aid') ?>

    <?= $form->field($model, 'Login') ?>

    <?= $form->field($model, 'Pass') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'IsOperator') ?>

    <?php // echo $form->field($model, 'Email') ?>

    <?php // echo $form->field($model, 'IsAdmin') ?>

    <?php // echo $form->field($model, 'Key') ?>

    <?php // echo $form->field($model, 'Logo') ?>

    <?php // echo $form->field($model, 'LogoText') ?>

    <?php // echo $form->field($model, 'LogoText2') ?>

    <?php // echo $form->field($model, 'LogoType') ?>

    <?php // echo $form->field($model, 'LogoWidth') ?>

    <?php // echo $form->field($model, 'TextPaddingLeft') ?>

    <?php // echo $form->field($model, 'OpenExcel') ?>

    <?php // echo $form->field($model, 'EngVersion') ?>

    <?php // echo $form->field($model, 'tbl') ?>

    <?php // echo $form->field($model, 'IsDoctor') ?>

    <?php // echo $form->field($model, 'UserType') ?>

    <?php // echo $form->field($model, 'InputOrder') ?>

    <?php // echo $form->field($model, 'PriceID') ?>

    <?php // echo $form->field($model, 'CanRegister') ?>

    <?php // echo $form->field($model, 'CACHE_Login') ?>

    <?php // echo $form->field($model, 'InputOrderRM') ?>

    <?php // echo $form->field($model, 'OrderEdit') ?>

    <?php // echo $form->field($model, 'MedReg') ?>

    <?php // echo $form->field($model, 'goscontract') ?>

    <?php // echo $form->field($model, 'FizType') ?>

    <?php // echo $form->field($model, 'clientmen') ?>

    <?php // echo $form->field($model, 'mto') ?>

    <?php // echo $form->field($model, 'mto_editor') ?>

    <?php // echo $form->field($model, 'LastLogin') ?>

    <?php // echo $form->field($model, 'DateBeg') ?>

    <?php // echo $form->field($model, 'date_end') ?>

    <?php // echo $form->field($model, 'block_register') ?>

    <?php // echo $form->field($model, 'last_update_password') ?>

    <?php // echo $form->field($model, 'show_preanalytic') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'parentAid') ?>

    <?php // echo $form->field($model, 'GarantLetter') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
