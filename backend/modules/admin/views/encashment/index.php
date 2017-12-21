<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NEncashmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Инкассации';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./encashment"])
];
?>
<div class="nencashment-index">
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
            'sender_key',
            'total',
            'user_aid',
            'receipt_number',
            // 'receipt_file',
            // 'code_1c',
            // 'status',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>