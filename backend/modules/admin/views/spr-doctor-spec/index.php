<?php

use yii\helpers\Html;
use common\models\SprFilials;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\AddUserForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

$this->title = 'Прикрепить врача к отдлению: запись на прием';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-doctor-spec-index">
    <div class="spr-doctor-spec-form">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <?php
                    echo Html::dropDownList('filials', null, SprFilials::getFilialsList());
                    ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <? //$form->field($model, 'specId')->dropDownlist(\common\models\SprDoctorSpec::getKeysList(), ['prompt' => '---', 'disabled' => false]); ?>
                </div>
            </div>
        </div>
    </div>
</div>