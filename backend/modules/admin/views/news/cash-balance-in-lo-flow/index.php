<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NCashBalanceInLOFlowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ncash Balance In Loflows';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ncash-balance-in-loflow-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ncash Balance In Loflow', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cashbalance_id',
            'sender_key',
            'total',
            'date',
            // 'operation',
            // 'balance',
            // 'workshift_id',
            // 'operation_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
