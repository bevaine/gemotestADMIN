<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NWorkshiftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Смены';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./workshift"])
];
?>
<div class="nworkshift-index">

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
                'value' => function($data){
                    return Html::a(
                        $data->id,
                        'https://office.gemotest.ru/administrator/index.php?r=workshift/view&id='.$data->id,
                        [
                            'title' => $data->id,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_aid',
                'width'=>'120px',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['user_aid'],
                        './logins/view?id='.$model['user_aid'],
                        [
                            'title' => $model['user_aid'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'sender_key',
            'sender_key_close',
            'kkm',
            'z_num',
            [
                'width'=>'196px',
                'attribute' => 'open_date',
                'value' => 'open_date',
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'open_date_from',
                    'attribute2' => 'open_date_to',
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
                'width'=>'196px',
                'attribute' => 'close_date',
                'value' => 'close_date',
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'close_date_from',
                    'attribute2' => 'close_date_to',
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
            'not_zero_sum_start',
            'not_zero_sum_end',
            'amount_cash_register',
            // 'error_check_count',
            // 'error_check_total_cash',
            // 'error_check_total_card',
            // 'error_check_return_count',
            // 'error_check_return_total_cash',
            // 'error_check_return_total_card',
            // 'file_name',
            // 'code_1c',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
