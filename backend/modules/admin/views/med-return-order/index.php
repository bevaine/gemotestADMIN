<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedReturnOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возврат МИС';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-order-index">

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
            'date',
            'order_id',
            'status',
            'total',
            'user_id',
            'is_virtual',
            'kkm',
            'z_num',
            'pay_type',
            // 'pay_type_original',
            // 'is_freepay',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
