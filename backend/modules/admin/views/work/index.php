<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\t23Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'T23s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="t23-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create T23', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'q1',
            'q2',
            'q3',
            'q4',
            'q5',
            // 'q6',
            // 'q7',
            // 'q8',
            // 'q9',
            // 'q10',
            // 'q11',
            // 'q12',
            // 'q13',
            // 'q14',
            // 'q15',
            // 'q16',
            // 'q17',
            // 'q18',
            // 'q19',
            // 'q20',
            // 'q21',
            // 'q22',
            // 'q23',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
