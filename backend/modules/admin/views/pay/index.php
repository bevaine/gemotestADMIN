<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежи';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./pay"])
];
?>
<div class="npay-index">
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
                'width'=>'196px',
                'attribute' => 'date',
                'value' => 'date',
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
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
                'attribute' => 'order_num',
                'width'=>'150px',
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
                'attribute' => 'order_data',
                'value' => 'order_data',
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'order_data_from',
                    'attribute2' => 'order_data_to',
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
            'kkm',
            'z_num',
            [
                'attribute' => 'login_id',
                'width'=>'120px',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['login_id'],
                        Url::to(["/admin/logins/view?id=".$model['login_id']]),
                        [
                            'title' => $model['login_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'login_key',
            [
                'attribute' => 'login_type',
                'width'=>'150px',
                'filter' => \common\models\Logins::getTypesArray(),
                'value' => function ($model) {
                    return  \common\models\Logins::getTypesArray($model['login_type']);
                }
            ],
            [
                'attribute' => 'pay_type',
                'width'=>'150px',
                'filter' => \common\models\NPay::getPayTypeArray(),
                'value' => function ($model) {
                    return  \common\models\NPay::getPayTypeArray($model['pay_type']);
                }
            ],
            'cost',
            'total',
            'sender_id',
            //'sender_name',
            // 'login_fio',
            // 'patient_id',
            // 'patient_fio',
            // 'base_doc_id',
            // 'base_doc_type',
            // 'base_doc_date',
            // 'patient_phone',
            // 'patient_birthday',
            // 'discount_card',
            // 'discount_id',
            // 'discount_name',
            // 'discount_percent',
            // 'bonus',
            // 'discount_total',
            // 'cito_factor',
            // 'bonus_balance',
            // 'printlist',
            // 'free_pay',
            // 'app_version',
            // 'pay_type_original',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
