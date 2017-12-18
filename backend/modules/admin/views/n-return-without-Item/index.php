<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NReturnWithoutItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возврат без номенклатуры ЛИС';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./n-return-without-item"])
];
?>
<div class="nreturn-without-item-index">

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
            'total',
            [
                'attribute' => 'pay_type',
                'width'=>'150px',
                'filter' => \common\models\NPay::getPayTypeArray(),
                'value' => function ($model) {
                    return  \common\models\NPay::getPayTypeArray($model['pay_type']);
                }
            ],
            'kkm',
            'z_num',
            'parent_id',
            [
                'attribute' => 'base',
                'width'=>'150px',
                'filter' => \common\models\NReturnWithoutItem::getBaseArray(),
                'value' => function ($model) {
                    return  \common\models\NReturnWithoutItem::getBaseArray($model['base']);
                }
            ],
            //'comment:ntext',
            // 'path_file',
            // 'code_1c',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
