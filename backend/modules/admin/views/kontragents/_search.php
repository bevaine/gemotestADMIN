<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KontragentsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kontragents-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'AID') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Key') ?>

    <?= $form->field($model, 'ShortName') ?>

    <?= $form->field($model, 'LoginsAID') ?>

    <?php // echo $form->field($model, 'BlankText') ?>

    <?php // echo $form->field($model, 'BlankName') ?>

    <?php // echo $form->field($model, 'isDelete') ?>

    <?php // echo $form->field($model, 'PayType') ?>

    <?php // echo $form->field($model, 'Blanks') ?>

    <?php // echo $form->field($model, 'Type') ?>

    <?php // echo $form->field($model, 'rmGroup') ?>

    <?php // echo $form->field($model, 'inoe') ?>

    <?php // echo $form->field($model, 'cito') ?>

    <?php // echo $form->field($model, 'goscontract') ?>

    <?php // echo $form->field($model, 'Li_cOrg') ?>

    <?php // echo $form->field($model, 'LCN') ?>

    <?php // echo $form->field($model, 'ReestrUslug') ?>

    <?php // echo $form->field($model, 'RegionID') ?>

    <?php // echo $form->field($model, 'dt_off_discount') ?>

    <?php // echo $form->field($model, 'flNoDiscCard') ?>

    <?php // echo $form->field($model, 'dt_off_auto_discount') ?>

    <?php // echo $form->field($model, 'dt_off_discount_card') ?>

    <?php // echo $form->field($model, 'hide_price') ?>

    <?php // echo $form->field($model, 'lab') ?>

    <?php // echo $form->field($model, 'code_1c') ?>

    <?php // echo $form->field($model, 'contract_number') ?>

    <?php // echo $form->field($model, 'contract_name') ?>

    <?php // echo $form->field($model, 'contractor_name') ?>

    <?php // echo $form->field($model, 'contract_date') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <?php // echo $form->field($model, 'price_supplier') ?>

    <?php // echo $form->field($model, 'sampling_of_biomaterial') ?>

    <?php // echo $form->field($model, 'use_ext_num') ?>

    <?php // echo $form->field($model, 'payment') ?>

    <?php // echo $form->field($model, 'ext_num_mask') ?>

    <?php // echo $form->field($model, 'salt') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
