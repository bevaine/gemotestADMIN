<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\GmsPlaylistOut;
use common\models\GmsDevices;
use kartik\time\TimePicker;

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
        'options' => ['style' => 'font-size:12px;'],
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
                    /** @var $model \common\models\GmsPlaylistOut */
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
                'options' => ['style' => 'font-size:12px;'],
                'filter' =>  \common\models\GmsGroupDevices::getGroupList(),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylistOut */
                    return !empty($model->groupDevicesModel) ? $model->groupDevicesModel->group_name : null;
                },
                'attribute' => 'group_id'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->deviceModel) ? $model->deviceModel->name : null;

                },
                'attribute' => 'device_name'
            ],
            [
                'headerOptions' => [
                    'style' => 'width: 105px; text-align: center;'
                ],
                'attribute' => 'date_start_val',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->date_start) ? date('d-m-Y', $model->date_start) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'name' => 'date_start_val',
                    'attribute' => 'date_start_val',
                    'options' => ['style' => 'font-size:12px;'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]),
                'format' => 'html', // datetime
            ],
            [
                'headerOptions' => [
                    'style' => 'width: 105px; text-align: center;'
                ],
                'attribute' => 'date_end_val',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->date_end) ? date('d-m-Y', $model->date_end) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'options' => ['style' => 'font-size:12px;'],
                    'model' => $searchModel,
                    'name' => 'date_end_val',
                    'attribute' => 'date_end_val',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            [
                'filter' => \kartik\time\TimePicker::widget([
                    'model' => $searchModel,
                    'name' => 'time_start',
                    'attribute' => 'time_start',
                    'options' => ['style' => 'width: 60px; text-align: center; font-size:12px;'],
                    'pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                        'minuteStep' => 5,
                        'hourStep' => 1,
                        'showInputs' => false,
                        'defaultTime' => '00:00',
                        'template' => false
                    ],
                    'addonOptions' => [
                        'asButton' => false,
                    ],
                    'addon' => ''
                ]),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylistOut */
                    return !empty($model->time_start) ? date('H:i', $model->time_start) : null;

                },
                'attribute' => 'time_start'
            ],
            [
                'filter' => \kartik\time\TimePicker::widget([
                    'model' => $searchModel,
                    'name' => 'time_end',
                    'attribute' => 'time_end',
                    'options' => ['style' => 'width: 60px; text-align: center; font-size:12px;'],
                    'pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                        'minuteStep' => 5,
                        'hourStep' => 1,
                        'showInputs' => false,
                        'defaultTime' => '23:59',
                        'template' => false
                    ],
                    'addonOptions' => [
                        'asButton' => false,
                    ],
                    'addon' => ''
                ]),
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
                'headerOptions' => array('style' => 'width: 70px; text-align: center;'),
                'contentOptions' => function ($model, $key, $index, $column){
                    return [
                        'style' => 'text-align: center;'
                    ];
                },
                'label' => 'Воспр. на устр.',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\GmsPlaylistOut $model */
                    return $model->getUpdateDev();
                }
            ],
            [
                'label' => 'Дни воспр.',
                'headerOptions' => [
                        'style' => 'width: 100px; text-align: center;'
                ],
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\GmsPlaylistOut $model */
                    return $model->getDaysPlaylist();
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => [
                    'style' => 'width: 60px; text-align: center;'
                ],
            ],
        ],
    ]); ?>
</div>
