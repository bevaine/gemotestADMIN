<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOutSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gms-playlist-out-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'file') ?>

    <?= $form->field($model, 'device_id') ?>

    <?= $form->field($model, 'date_play') ?>

    <?= $form->field($model, 'start_time_play') ?>

    <?php // echo $form->field($model, 'end_time_play') ?>

    <?php // echo $form->field($model, 'isMonday') ?>

    <?php // echo $form->field($model, 'isTuesday') ?>

    <?php // echo $form->field($model, 'isWednesday') ?>

    <?php // echo $form->field($model, 'isThursday') ?>

    <?php // echo $form->field($model, 'isFriday') ?>

    <?php // echo $form->field($model, 'isSaturday') ?>

    <?php // echo $form->field($model, 'isSunday') ?>

    <?php // echo $form->field($model, 'timeStart') ?>

    <?php // echo $form->field($model, 'timeEnd') ?>

    <?php // echo $form->field($model, 'dateStart') ?>

    <?php // echo $form->field($model, 'dateEnd') ?>

    <?php // echo $form->field($model, 'sender_id') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'jsonPlaylist') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
