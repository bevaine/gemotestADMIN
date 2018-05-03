<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\GmsPlaylistOut;

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
            [
                'headerOptions' => array('style' => 'text-align: center;'),
                'contentOptions' => function ($model, $key, $index, $column){
                    return ['style' => 'text-align: center;'];
                },
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\GmsPlaylistOut $model */
                    $arr_dev = [];
                    $html_dev = '';
                    $img_name = 'stop.png';
                    $value = $model['update_json'];
                    if (!empty($value)) {
                        $update_json = json_decode($value);
                        Yii::getLogger()->log([
                          '$update_json'=>$update_json
                        ], 1, 'binary');
                        $max_datetime = max(ArrayHelper::toArray($update_json));
                        if ($play_time = GmsPlaylistOut::checkTime($max_datetime)) {
                            $img_name =  'play.jpg';
                        }
                        foreach ($update_json as $key_dev => $val_dev) {
                            if (GmsPlaylistOut::checkTime($val_dev)) {
                                $arr_dev[] = Html::tag(
                                    'span',
                                    $key_dev,
                                    ['class' => 'label label-success']
                                );
                            }
                        }
                        if (!empty($arr_dev)) {
                            $html_dev = implode('<br>', $arr_dev);
                        }
                    }
                    return Html::img('/img/'.$img_name).$html_dev;
                }
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
