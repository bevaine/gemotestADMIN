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
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                },
                'attribute' => 'region'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                },
                'attribute' => 'sender_name'
            ],
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
