<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

            'id',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsHistory */
                    return !empty($model->playlistOutModel) ? $model->playlistOutModel->name.", ID:".$model->playlistOutModel->id : null;

                },
                'attribute' => 'pls_name'
            ],
            'device_id',
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
            'created_at',
            'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
