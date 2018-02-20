<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Playlist Outs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-out-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'file',
            'device_id',
            'date_play',
            'start_time_play:datetime',
            'end_time_play:datetime',
            'isMonday',
            'isTuesday',
            'isWednesday',
            'isThursday',
            'isFriday',
            'isSaturday',
            'isSunday',
            'timeStart:datetime',
            'timeEnd:datetime',
            'dateStart',
            'dateEnd',
            'sender_id',
            'region_id',
            'jsonPlaylist:ntext',
            'created_at',
            'active',
        ],
    ]) ?>

</div>
