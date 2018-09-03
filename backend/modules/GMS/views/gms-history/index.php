<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\GmsHistory;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'История';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-history-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'headerOptions' => array('style' => 'width: 196px; text-align: center;'),
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsDevices */
                    return !empty($model->created_at) ? date("d-m-Y H:i:s", $model->created_at) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_from',
                    'attribute2' => 'created_at_to',
                    'options' => [
                        'placeholder' => 'От',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'До',
                        'style'=>['width' => '98px']
                    ],
                    'separator' => 'По',
                    'readonly' => false,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            [
                'attribute' => 'pls_name',
                'label' => 'Плейлист',
                'value' => function($model) {
                    if (!empty($model["pls_id"]) && !empty($model["pls_name"])) {
                        return Html::a(
                            $model['pls_name'],
                            Url::to(["/GMS/playlist-out/view?id=" . $model['pls_id']]),
                            [
                                'title' => $model['pls_name'],
                                'target' => '_blank'
                            ]
                        );
                    } else return null;
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'device_id',
                'value' => function($model) {
                    /** @var \common\models\GmsHistorySearch $model  */
                    return Html::a(
                        $model->device ? $model->device->name : null,
                        Url::to(["/GMS/gms-devices/view?id=".$model->device_id]),
                        [
                            'title' => $model->device ? $model->device->name : null,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'status',
                'filter' => GmsHistory::getStatusTypeArray(),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsHistory */
                    return  !empty($model->status) ? GmsHistory::getStatusTypeArray($model->status)['txt'] : null;
                },
                'contentOptions' => function ($model) {
                    /** @var $model \common\models\GmsHistory */
                    return  ['style'=>'color:' . GmsHistory::getStatusTypeArray($model->status)['style']];
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
