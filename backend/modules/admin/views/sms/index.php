<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'CompliteDate',
                'value' => function($model) {
                    return isset($model->order) ? $model->order->CompliteDate : null;
                }
            ],
            'created_at',
            'updated_at',
            'phone',
            'orderNum',
            [
                'attribute' => 'status',
                'filter' => \common\models\Sms::getStatusArray(),
                'value' => function ($model) {
                    if (!is_null($model['status'])) {
                        return \common\models\Sms::getStatusArray($model['status']);
                    } else return null;
                }
            ],
            'message',
            //'client_id',
            //'tz',
            //'priority',
            //'enqueued:boolean',
            //'attempt',
            //'provider_id',
            //'provider_sms_id',
            //'deliver_sm:ntext',
            //'bounce_reason',
            //'callback',
            //'attempts_get_status',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>
</div>
