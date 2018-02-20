<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsPlaylistOutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gms Playlist Outs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-out-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Gms Playlist Out', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'file',
            'device_id',
            'date_play',
            'start_time_play:datetime',
            // 'end_time_play:datetime',
            // 'isMonday',
            // 'isTuesday',
            // 'isWednesday',
            // 'isThursday',
            // 'isFriday',
            // 'isSaturday',
            // 'isSunday',
            // 'timeStart:datetime',
            // 'timeEnd:datetime',
            // 'dateStart',
            // 'dateEnd',
            // 'sender_id',
            // 'region_id',
            // 'jsonPlaylist:ntext',
            // 'created_at',
            // 'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
