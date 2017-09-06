<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AddOrgForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создание юридического лица';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-create">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class=""><a href="<?php echo Url::to(["logins/create"]) ?>">Пользователи</a></li>
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Юр. лица</a></li>
            <li class=""><a href="<?php echo Url::to(["logins/create-doc"]) ?>">Врачи</a></li>
            <li class=""><a href="<?php echo Url::to(["logins/create-franch"]) ?>">Франчайзи</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="organization-form">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?= $form->field($model, 'name')->textInput() ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?= $form->field($model, 'key')->textInput() ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?= $form->field($model, 'login')->textInput() ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?= $form->field($model, 'email')->textInput() ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?= $form->field($model, 'blankText')->textarea() ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::Button('Создать',['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>