<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InputOrderZaborSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Взятие биоматериала';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./input-order-zabor"])
];
?>
<div class="input-order-iskl-issl-mszabor-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'aid',
                'width'=>'150px',
            ],
            [
                'attribute' => 'OrderID',
                'width'=>'150px',
                'value' => function($data){
                    return Html::a(
                        $data->OrderID,
                        'https://office.gemotest.ru/inputOrder/inputMain_test.php?oid='.$data->OrderID,
                        [
                            'title' => $data->OrderID,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'width'=>'196px',
                'attribute' => 'DateIns',
                'value' => 'DateIns',
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
            'MSZabor',
            'IsslCode',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
