<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gms Devices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-devices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Gms Devices', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sender_id',
            'host_name',
            'device_id',
            'created_at',
            'updated_at',
            'playlist',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
