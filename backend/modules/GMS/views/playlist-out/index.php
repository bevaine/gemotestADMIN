<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

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

            [
                'headerOptions' => array('style' => 'width: 30px; text-align: center;'),
                'attribute' => 'id'
            ],
            'created_at:date',
            'update_at:date',
            'name',
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
                'attribute' => 'sender_name'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->deviceModel) ? $model->deviceModel->device : null;

                },
                'attribute' => 'device_name'
            ],
            [
                'headerOptions' => array('style' => 'width: 195px; text-align: center;'),
                'attribute' => 'date_start_val',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->date_start) ? date('d-m-Y', $model->date_start) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                        'model' => $searchModel,
                        'name' => 'date_start_val',
                        'attribute' => 'date_start_val',
                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]),
                'format' => 'html', // datetime
            ],
            [
                'headerOptions' => array('style' => 'width: 195px; text-align: center;'),
                'attribute' => 'date_end_val',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->date_end) ? date('d-m-Y', $model->date_end) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'name' => 'date_end_val',
                    'attribute' => 'date_end_val',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            //'date_start:date',
            //'date_end:date',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylistOut */
                    return !empty($model->time_start) ? date('H:i', $model->time_start) : null;

                },
                'attribute' => 'time_start'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylistOut */
                    return !empty($model->time_end) ? date('H:i', $model->time_end) : null;

                },
                'attribute' => 'time_end'
            ],
            [
                'label' => 'Статус',
                'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                'filter' => \common\models\GmsPlaylistOut::getAuthStatusArray(),
                'format' => 'raw',
                'attribute' => 'active',
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
