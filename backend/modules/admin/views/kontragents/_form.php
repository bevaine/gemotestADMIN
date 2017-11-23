<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Kontragents */
/* @var $form yii\widgets\ActiveForm */

$action = 'org';

?>

<div class="kontragents-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Name')->textInput() ?>

    <?= $form->field($model, 'Key')->textInput() ?>

    <?= $form->field($model, 'ShortName')->textInput() ?>

    <?= $form->field($model, 'LoginsAID')->textInput() ?>

    <?= $form->field($model, 'BlankText')->textInput() ?>

    <?= $form->field($model, 'BlankName')->textInput() ?>

<!--    --><?//= $form->field($model, 'mapPoint1.address')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.zip_code')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.area')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.city')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.street')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.house')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.housing')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'mapPoint1.region')->textInput() ?>

    <?= $form->field($model, 'isDelete')->textInput() ?>

    <?= $form->field($model, 'PayType')->textInput() ?>

    <?= $form->field($model, 'Blanks')->textarea() ?>

    <?= $form->field($model, 'Type')->textInput() ?>

    <?= $form->field($model, 'rmGroup')->textInput() ?>

    <?= $form->field($model, 'inoe')->textInput() ?>

    <?= $form->field($model, 'cito')->textInput() ?>

    <?= $form->field($model, 'goscontract')->textInput() ?>

    <?= $form->field($model, 'Li_cOrg')->textInput() ?>

    <?= $form->field($model, 'LCN')->textInput() ?>

    <?= $form->field($model, 'ReestrUslug')->textInput() ?>

    <?= $form->field($model, 'RegionID')->textInput() ?>

    <?= $form->field($model, 'dt_off_discount')->textInput() ?>

    <?= $form->field($model, 'flNoDiscCard')->textInput() ?>

    <?= $form->field($model, 'dt_off_auto_discount')->textInput() ?>

    <?= $form->field($model, 'dt_off_discount_card')->textInput() ?>

    <?= $form->field($model, 'hide_price')->textInput() ?>

    <?= $form->field($model, 'lab')->textInput() ?>

    <?= $form->field($model, 'code_1c')->textInput() ?>

    <?= $form->field($model, 'contract_number')->textInput() ?>

    <?= $form->field($model, 'contract_name')->textInput() ?>

    <?= $form->field($model, 'contractor_name')->textInput() ?>

    <?= $form->field($model, 'contract_date')->textInput() ?>

    <?= $form->field($model, 'date_update')->textInput() ?>

    <?= $form->field($model, 'price_supplier')->textInput() ?>

    <?= $form->field($model, 'sampling_of_biomaterial')->textInput() ?>

    <?= $form->field($model, 'use_ext_num')->textInput() ?>

    <?= $form->field($model, 'payment')->textInput() ?>

    <?= $form->field($model, 'ext_num_mask')->textInput() ?>

    <?= $form->field($model, 'salt')->textInput() ?>

    <div class="logins-create">
        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li class="<?= ($action == 'org') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/org"]) ?>">Юр. лица</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="logins-form">

                        <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>

                        <div name="account-hide" id="account-hide"></div>

                        <?php if ($action == 'org') : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <? //$form->field($model, 'name')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <? //$form->field($model, 'key')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <? //$form->field($model, 'login')->textInput() ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <? //$form->field($model, 'email')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <? //$form->field($model, 'blankText')->textarea() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <?php
                            if ($action == 'user' || $action == 'franch' || $action == 'doc') {
                                echo Html::Button('Создать', ['class' => 'btn btn-success']);
                            } else {
                                //echo Html::submitButton('Создать', ['class' => 'btn btn-success']);
                            }
                            ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
