<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InputOrderZaborSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Input Order Iskl Issl Mszabors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-order-iskl-issl-mszabor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Input Order Iskl Issl Mszabor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'aid',
            'OrderID',
            'IsslCode',
            'MSZabor',
            'DateIns',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
