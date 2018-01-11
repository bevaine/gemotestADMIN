<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedReturnWithoutItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возврат МИС (без номенклатуры)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-without-item-index">
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
            'order_num',
            'total',
            'date',
            'pay_type',
            'kkm',
            'z_num',
            // 'comment:ntext',
            // 'path_file',
            // 'base',
            // 'user_aid',
            // 'code_1c',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
