<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежи: медицинские';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-pay-index">
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
                'attribute' => 'order_id',
                'width'=>'150px',
                'value' => function($data){
                    return Html::a(
                        $data['order_id'],
                        'https://office.gemotest.ru/mis/registry/order?order_id='.$data['order_id'],
                        [
                            'title' => $data['order_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'patient_id',
            [
                'attribute' => 'user_id',
                'width'=>'120px',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['user_id'],
                        Url::to(["/admin/logins/view?id=".$model['user_id']]),
                        [
                            'title' => $model['user_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'office_id',
            [
                'attribute' => 'pay_type',
                'width'=>'150px',
                'filter' => \common\models\NPay::getPayTypeArray(),
                'value' => function ($model) {
                    if (!is_null($model['pay_type'])) {
                        return \common\models\NPay::getPayTypeArray($model['pay_type']);
                    } else return null;
                }
            ],
            'cost',
            'total',
            'kkm',
            'z_num',
            'base_doc_type',
            'base_doc_id',
            // 'is_virtual',
            // 'printlist',
            // 'user_fio',
            // 'free_pay',
            // 'discount_total',
            // 'office_name',
            // 'user_username',
            // 'patient_fio',
            // 'patient_phone',
            // 'patient_birthday',
            // 'pay_type_original',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
