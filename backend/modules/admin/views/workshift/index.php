<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NWorkshiftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nworkshifts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nworkshift-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Nworkshift', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_aid',
            'sender_key',
            'kkm',
            'z_num',
            'open_date',
            'close_date',
            'not_zero_sum_start',
            'not_zero_sum_end',
            // 'amount_cash_register',
            // 'sender_key_close',
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
