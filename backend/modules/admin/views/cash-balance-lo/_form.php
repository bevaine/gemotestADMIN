<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\NCashBalanceInLOFlow */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ncash-balance-in-loflow-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cashbalance_id')->textInput() ?>

    <?= $form->field($model, 'sender_key')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= Html::label("Дата") ?>

    <?= DateTimePicker::widget([
        'name' => 'NCashBalanceInLOFlow[date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->date)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'operation')->textInput() ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'workshift_id')->textInput() ?>

    <?= $form->field($model, 'operation_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
