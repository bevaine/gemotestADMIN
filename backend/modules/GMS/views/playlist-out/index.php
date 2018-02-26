<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsPlaylistOutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Действующие плейлисты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-out-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'region_id',
            'sender_id',
            'device_id',
            'dateStart:datetime',
            'dateEnd:datetime',
            'timeStart:time',
            'timeEnd:time',
            'active',
            'created_at',
            //'file',
            // 'end_time_play:datetime',
            // 'isMonday',
            // 'isTuesday',
            // 'isWednesday',
            // 'isThursday',
            // 'isFriday',
            // 'isSaturday',
            // 'isSunday',
            // 'jsonPlaylist:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
