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

    <?php // echo $form->field($model, 'is_monday') ?>

    <?php // echo $form->field($model, 'is_tuesday') ?>

    <?php // echo $form->field($model, 'is_wednesday') ?>

    <?php // echo $form->field($model, 'is_thursday') ?>

    <?php // echo $form->field($model, 'is_friday') ?>

    <?php // echo $form->field($model, 'is_saturday') ?>

    <?php // echo $form->field($model, 'is_sunday') ?>

    <?php // echo $form->field($model, 'time_start') ?>

    <?php // echo $form->field($model, 'time_end') ?>

    <?php // echo $form->field($model, 'date_start') ?>

    <?php // echo $form->field($model, 'date_end') ?>

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
