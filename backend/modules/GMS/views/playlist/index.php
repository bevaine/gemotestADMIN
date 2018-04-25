<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsPlaylistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны плейлистов';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="gms-playlist-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                },
                'attribute' => 'region'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                },
                'attribute' => 'sender_name'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->groupDevicesModel) ? $model->groupDevicesModel->group_name : null;

                },
                'attribute' => 'group_id'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->deviceModel) ? $model->deviceModel->name : null;

                },
                'attribute' => 'device_id'
            ],
            [
                'filter' => \common\models\GmsPlaylist::getPlayListType(),
                'attribute' => 'type',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return \common\models\GmsPlaylist::getPlayListType($model->type);
                },
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->created_at) ? date('Y-m-d H:i:s', $model->created_at) : null;
                },
                'attribute' => 'created_at'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->updated_at) ? date('Y-m-d H:i:s', $model->updated_at) : null;
                },
                'attribute' => 'updated_at'
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
