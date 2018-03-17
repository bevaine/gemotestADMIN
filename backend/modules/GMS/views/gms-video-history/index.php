<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideoHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История воспроизведения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-video-history-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            'last_at',
            'pls_id',
            'device_id',
            'video_key',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
