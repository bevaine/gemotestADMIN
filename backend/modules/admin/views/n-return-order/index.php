<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            'date',
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
