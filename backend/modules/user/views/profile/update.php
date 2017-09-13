<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\User*/

$this->title = Yii::t('app', 'Изменить');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Профиль'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-profile-update">

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
