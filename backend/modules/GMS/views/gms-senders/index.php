<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsSendersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отделения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-senders-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sender_key',
            'sender_name',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsSenders */
                    return \common\models\GmsRegions::findOne($model->region_id)->region_name;
                },
                'attribute' => 'region_id'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
