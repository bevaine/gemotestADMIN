<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NReturnOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возврат ЛИС';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nreturn-order-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'parent_id',
            'parent_type',
            ['class'=>'kartik\grid\SerialColumn'],
            [
                'width'=>'150px',
                'header' => 'Дата',
                'value' => 'date',
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'options' => ['placeholder' => 'Дата начала'],
                    'options2' => ['placeholder' => 'Дата конца'],
                    'separator' => 'По',
                    'readonly' => true,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            //'date',
            'order_num',
            'status',
            'total',
            'user_id',
            'kkm',
            // 'sync_with_lc_status',
            // 'last_update',
            // 'sync_with_lc_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
