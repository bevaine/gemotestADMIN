<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\NAdUsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nad-users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'middle_name') ?>

    <?= $form->field($model, 'AD_name') ?>

    <?php // echo $form->field($model, 'AD_position') ?>

    <?php // echo $form->field($model, 'AD_email') ?>

    <?php // echo $form->field($model, 'table_number') ?>

    <?php // echo $form->field($model, 'subdivision') ?>

    <?php // echo $form->field($model, 'create_date') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <?php // echo $form->field($model, 'gs_id') ?>

    <?php // echo $form->field($model, 'gs_key') ?>

    <?php // echo $form->field($model, 'gs_usertype') ?>

    <?php // echo $form->field($model, 'gs_email') ?>

    <?php // echo $form->field($model, 'allow_gs') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'AD_login') ?>

    <?php // echo $form->field($model, 'AD_active') ?>

    <?php // echo $form->field($model, 'auth_ldap_only') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
