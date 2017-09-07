<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NAdUsers */
/* @var $form ActiveForm */
?>
<div class="createNAdUsers">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'last_name') ?>
        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'middle_name') ?>
        <?= $form->field($model, 'AD_name') ?>
        <?= $form->field($model, 'AD_position') ?>
        <?= $form->field($model, 'AD_email') ?>
        <?= $form->field($model, 'table_number') ?>
        <?= $form->field($model, 'subdivision') ?>
        <?= $form->field($model, 'gs_email') ?>
        <?= $form->field($model, 'AD_login') ?>
        <?= $form->field($model, 'create_date') ?>
        <?= $form->field($model, 'last_update') ?>
        <?= $form->field($model, 'gs_id') ?>
        <?= $form->field($model, 'gs_key') ?>
        <?= $form->field($model, 'gs_usertype') ?>
        <?= $form->field($model, 'allow_gs') ?>
        <?= $form->field($model, 'active') ?>
        <?= $form->field($model, 'AD_active') ?>
        <?= $form->field($model, 'auth_ldap_only') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- createNAdUsers -->
