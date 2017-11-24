<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BranchStaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branch Staff';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-staff-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Branch Staff', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'first_name',
            'middle_name',
            'last_name',
            'guid',
            'sender_key',
            'prototype',
            'date',
            'personnel_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
