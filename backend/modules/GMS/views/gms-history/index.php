<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-history-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsHistory */
                    return !empty($model->playlistOutModel) ? $model->playlistOutModel->name : null;

                },
                'attribute' => 'pls_name'
            ],
            'device_id',
            'created_at',
            'status',
            // 'log_text:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
