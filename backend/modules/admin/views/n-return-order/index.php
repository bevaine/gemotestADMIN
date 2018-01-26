<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NReturnOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возвраты ЛИС';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./n-return-order"])
];
?>
<div class="nreturn-order-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'width'=>'80px',
            ],
            [
                'attribute' => 'order_num',
                'width'=>'120px',
                'value' => function($data){
                    return Html::a(
                        $data->order_num,
                        'https://office.gemotest.ru/inputOrder/inputMain_test.php?oid='.$data->order_num,
                        [
                            'title' => $data->order_num,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'width'=>'196px',
                'attribute' => 'date',
                'value' => 'date',

                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'removeButton' => true,
                    'options' => [
                        'placeholder' => 'Дата начала',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'Дата конца',
                        'style'=>['width' => '98px']
                    ],
                    'separator' => 'По',
                    'readonly' => false,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            [
                'attribute' => 'status',
                'width'=>'80px',
            ],
            [
                'attribute' => 'total',
                'width'=>'120px',
            ],
            [
                'attribute' => 'user_id',
                'width'=>'120px',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['user_id'],
                        './logins/view?id='.$model['user_id'],
                        [
                            'title' => $model['user_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'kkm',
            'sync_with_lc_status',
            [
                'width'=>'196px',
                'attribute' => 'sync_with_lc_date',
                'value' => 'sync_with_lc_date',

                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from_1c',
                    'attribute2' => 'date_to_1c',
                    'options' => [
                        'placeholder' => 'Дата начала',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'Дата конца',
                        'style'=>['width' => '98px']
                    ],
                    'separator' => 'По',
                    'readonly' => false,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            'parent_id',
            'parent_type',
            [
                'attribute' => 'agreement_status',
                'width'=>'150px',
                'filter' => \common\models\NReturnOrder::getAgreementArray(),
                'value' => function ($model) {
                    if (!is_null($model['agreement_status'])) {
                        return \common\models\NReturnOrder::getAgreementArray($model['agreement_status']);
                    } else return null;
                }
            ],
            //'last_update',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>