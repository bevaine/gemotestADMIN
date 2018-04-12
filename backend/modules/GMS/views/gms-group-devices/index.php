<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsGroupDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гуппы устройств';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-group-devices-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'group_name',
            'device_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
