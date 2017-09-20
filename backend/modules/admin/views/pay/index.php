<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Npays';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="npay-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Npay', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'date',
            'order_num',
            'order_data',
            'kkm',
            'z_num',
            'patient_id',
            'patient_fio',
            'pay_type',
            'total',
            'sender_id',
            'sender_name',
            // 'cost',
            // 'base_doc_id',
            // 'base_doc_type',
            // 'base_doc_date',
            // 'patient_phone',
            // 'patient_birthday',
            // 'login_id',
            // 'login_key',
            // 'login_type',
            // 'login_fio',
            // 'discount_card',
            // 'discount_id',
            // 'discount_name',
            // 'discount_percent',
            // 'bonus',
            // 'discount_total',
            // 'cito_factor',
            // 'bonus_balance',
            // 'printlist',
            // 'free_pay',
            // 'app_version',
            // 'pay_type_original',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
