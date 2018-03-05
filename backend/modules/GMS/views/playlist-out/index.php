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
            'name',
            'created_at',
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                },
                'attribute' => 'region_id'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                },
                'attribute' => 'sender_id'
            ],
            'device_id',
            'dateStart:datetime',
            'dateEnd:datetime',
            'timeStart:time',
            'timeEnd:time',
            [
                'label' => 'Статус',
                'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\GmsPlaylistOut $model */
                    return $model->getAuthStatus();
                }
            ],
            [
                'label' => 'Дни воспр.',
                'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\GmsPlaylistOut $model */
                    return $model->getDaysPlaylist();
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
