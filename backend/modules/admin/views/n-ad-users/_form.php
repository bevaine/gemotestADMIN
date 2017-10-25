<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NAdUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nad-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'last_name')->textInput() ?>

    <?= $form->field($model, 'first_name')->textInput() ?>

    <?= $form->field($model, 'middle_name')->textInput() ?>

    <?= $form->field($model, 'AD_name')->textInput() ?>

    <?= $form->field($model, 'AD_position')->textInput() ?>

    <?= $form->field($model, 'AD_email')->textInput() ?>

    <?= $form->field($model, 'table_number')->textInput() ?>

    <?= $form->field($model, 'subdivision')->textInput() ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'last_update')->textInput() ?>

    <?= $form->field($model, 'gs_id')->textInput() ?>

    <?= $form->field($model, 'gs_key')->textInput() ?>

    <?= $form->field($model, 'gs_usertype')->textInput() ?>

    <?= $form->field($model, 'gs_email')->textInput() ?>

    <?= $form->field($model, 'allow_gs')->textInput() ?>

    <?= $form->field($model, 'active')->textInput() ?>

    <?= $form->field($model, 'AD_login')->textInput() ?>

    <?= $form->field($model, 'AD_active')->textInput() ?>

    <?= $form->field($model, 'auth_ldap_only')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
