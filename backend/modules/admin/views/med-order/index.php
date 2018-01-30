<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Med Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Med Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'date',
            'patient_id',
            'user_id',
            'office_id',
            'status',
            'discount',
            'discount_type',
            'discount_name',
            'workshift_id',
            'erp_order_id',
            'create_employee_guid',
            'create_user_id',
            // 'representative',
            // 'guarantee_letter',
            // 'guarantee_letter_file_path',
            // 'guarantee_letter_file_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
