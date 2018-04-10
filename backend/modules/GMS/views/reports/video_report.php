<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use common\models\GmsPlaylistOut;
use kartik\export\ExportMenu;
use common\models\GmsPlaylist;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideoHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет по воспроизведенным видео';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],

    [
        'value' => function ($model) {
            return !empty($model["start_at"]) ? date("Y-m-d H:i:s T", strtotime($model["start_at"])) : null;
        },
        'label' => 'Начало',
        'attribute' => 'start_at',
        'group'=> false,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'value' => function ($model) {
            return !empty($model["last_at"]) ? date("Y-m-d H:i:s T", strtotime($model["last_at"])) : null;
        },
        'label' => 'Конец',
        'attribute' => 'last_at',
        'group'=> false,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'headerOptions' => array('style' => 'width: 200px;'),
        'label' => 'Видео',
        'value' => function($model) {
            if (!empty($model["video_key"])) {
                return Html::a(
                    $model['video_name'],
                    Url::to(["/GMS/gms-videos/view?id=" . $model['video_key']]),
                    [
                        'title' => $model['video_name'],
                        'target' => '_blank'
                    ]
                );
            } else return null;
        },
        'format' => 'html',
        'group'=> false,
        'attribute' => 'video_name',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'headerOptions' => array('style' => 'width: 200px;'),
        'attribute' => 'type',
        'filter' => \common\models\GmsPlaylist::getPlayListType(),
        'value' => function ($model) {
            /** @var $model \common\models\GmsPlaylistOut */
            return !empty($model["type_pls"]) ? GmsPlaylist::getPlayListType($model["type_pls"]) : null;
        },
        'format' => 'html',
        'group'=> false,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'value' => function ($model) {
            return !empty($model["duration"]) ? date("H:i:s", $model["duration"]) : null;
        },
        'label' => 'Продолжит.',
        'group'=> false,
        'attribute' => 'duration',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'attribute' => 'pls_name',
        'width'=>'120px',
        'label' => 'Плейлист',
        'value' => function($model) {
            /** @var \common\models\LoginsSearch $model */
            return Html::a(
                $model['pls_name'],
                Url::to(["/GMS/playlist-out/view?id=".$model['pls_id']]),
                [
                    'title' => $model['pls_name'],
                    'target' => '_blank'
                ]
            );
        },
        'format' => 'raw',
        'group'=> true,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'filter' =>  \common\models\GmsRegions::getRegionList(),
        'value' => function ($model) {
            return !empty($model["region_name"]) ? $model["region_name"] : null;
        },
        'label' => 'Регион',
        'group'=> true,
        'attribute' => 'region_id',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'value' => function ($model) {
            return !empty($model["sender_name"]) ? $model["sender_name"] : null;
        },
        'label' => 'Отделение',
        'group'=> true,
        'attribute' => 'sender_name',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'attribute' => 'device_id',
        'label' => 'Устройство',
        'width'=>'120px',
        'value' => function($model) {
            if (!empty($model["sender_name"])) {
                return Html::a(
                    $model['device_id'],
                    Url::to(["/GMS/gms-devices/view?id=".$model['dev_id']]),
                    [
                        'title' => $model['device_id'],
                        'target' => '_blank'
                    ]
                );
            } else return null;
        },
        'group'=> true,
        'format' => 'raw',
        'pageSummaryOptions'=>['class'=>'text-right'],
    ]
];
?>

<div class="gms-video-history-index">

    <p>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'showPageSummary' => true,
        'striped' => false,
        'export' => false,
        'panel'=>[
            'type'=>'primary',
            'heading'=>'Отчет по воспроизведенным видео'
        ],
        'columns' => $gridColumns,
    ]);
    ?>
</div>
