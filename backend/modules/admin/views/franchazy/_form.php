<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="franchazy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Active')->textInput() ?>

    <?= $form->field($model, 'Login')->textInput() ?>

    <?= $form->field($model, 'Pass')->passwordInput() ?>

    <?= $form->field($model, 'Name')->textInput() ?>

    <?= $form->field($model, 'IsOperator')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput() ?>

    <?= $form->field($model, 'IsAdmin')->textInput() ?>

    <?= $form->field($model, 'Key')->textInput() ?>

    <?= $form->field($model, 'BlankText')->textInput() ?>

    <?= $form->field($model, 'BlankName')->textInput() ?>

    <?= $form->field($model, 'Logo')->textInput() ?>

    <?= $form->field($model, 'LogoText')->textInput() ?>

    <?= $form->field($model, 'LogoText2')->textInput() ?>

    <?= $form->field($model, 'LogoType')->textInput() ?>

    <?= $form->field($model, 'LogoWidth')->textInput() ?>

    <?= $form->field($model, 'TextPaddingLeft')->textInput() ?>

    <?= $form->field($model, 'OpenExcel')->textInput() ?>

    <?= $form->field($model, 'EngVersion')->textInput() ?>

    <?= $form->field($model, 'InputOrder')->textInput() ?>

    <?= $form->field($model, 'PriceID')->textInput() ?>

    <?= $form->field($model, 'CanRegister')->textInput() ?>

    <?= $form->field($model, 'InputOrderRM')->textInput() ?>

    <?= $form->field($model, 'OpenActive')->textInput() ?>

    <?= $form->field($model, 'ReestrUslug')->textInput() ?>

    <?= $form->field($model, 'LCN')->textInput() ?>

    <?= $form->field($model, 'Li_cOrg')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
