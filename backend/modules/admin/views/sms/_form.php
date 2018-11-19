<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([ 'pending' => 'Pending', 'sent' => 'Sent', 'delivered' => 'Delivered', 'buried' => 'Buried', 'bounced' => 'Bounced', 'new' => 'New', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'client_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <?= $form->field($model, 'enqueued')->checkbox() ?>

    <?= $form->field($model, 'attempt')->textInput() ?>

    <?= $form->field($model, 'provider_id')->textInput() ?>

    <?= $form->field($model, 'provider_sms_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deliver_sm')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bounce_reason')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'callback')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attempts_get_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
