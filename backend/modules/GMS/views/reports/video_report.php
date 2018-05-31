<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use common\models\GmsPlaylist;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\components\helpers\FunctionsHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideoHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет по воспроизведенным видео';
$this->params['breadcrumbs'][] = $this->title;

$url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],

    [
        'headerOptions' => array('style' => 'width: 160px;'),
        'value' => function ($model) {
            return !empty($model["start_at"]) ? date("Y-m-d H:i:s T", $model["start_at"]) : null;
        },
        'label' => 'Начало',
        'attribute' => 'start_at',
        'group'=> false,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'headerOptions' => array('style' => 'width: 160px;'),
        'value' => function ($model) {
            return !empty($model["last_at"]) ? date("Y-m-d H:i:s T", $model["last_at"]) : null;
        },
        'label' => 'Конец',
        'attribute' => 'last_at',
        'group'=> false,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'headerOptions' => array('style' => 'width: 100px;'),
        'value' => function ($model) {
            if (empty($model["start_at"]) || empty($model["last_at"])) {
                return null;
            }
            $duration = $model["last_at"] - $model["start_at"];
            return !empty($duration) && $duration > 0 ? date("H:i:s", mktime(null,null, $duration)) : null;
        },
        'label' => 'Продолжит.',
        'group'=> true,
        'attribute' => 'duration',
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
        'group'=> true,
        'attribute' => 'video_name',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'headerOptions' => array('style' => 'width: 100px;'),
        'attribute' => 'type',
        'filter' => \common\models\GmsPlaylist::getPlayListType(),
        'value' => function ($model) {
            /** @var $model \common\models\GmsPlaylistOut */
            return !empty($model["type_pls"]) ? GmsPlaylist::getPlayListType($model["type_pls"]) : null;
        },
        'format' => 'html',
        'group'=> true,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'attribute' => 'pls_name',
        'width'=>'120px',
        'label' => 'Плейлист',
        'value' => function($model) {
            if (!empty($model["pls_id"]) && !empty($model["pls_name"])) {
                return Html::a(
                    $model['pls_name'],
                    Url::to(["/GMS/playlist-out/view?id=" . $model['pls_id']]),
                    [
                        'title' => $model['pls_name'],
                        'target' => '_blank'
                    ]
                );
            } else return null;
        },
        'format' => 'raw',
        'group'=> true,
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'filter' =>  \common\models\GmsRegions::getRegionList(),
        'label' => 'Регион',
        'value' => function ($model) {
            return !empty($model["region_name"]) ? $model["region_name"] : null;
        },
        'group'=> true,
        'attribute' => 'region_id',
        'pageSummaryOptions'=>['class'=>'text-right']
    ],
    [
        'label' => 'Отделение',
        'filter' => Select2::widget([
            'name' => 'GmsVideoHistorySearch[sender_id]',
            'value' => $searchModel["sender_id"],
            'options' => ['placeholder' => 'Наименование отделения'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'multiple' => false,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return { search:params.term}; }'),
                    'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                    'cache' => true
                ],
                'initSelection' => new JsExpression(FunctionsHelper::AjaxInitScript($url)),
            ],
        ]),
        'value' => function ($model) {
            /** @var \common\models\GmsVideoHistorySearch $model  */
            return !empty($model["sender_name"]) ? $model["sender_name"] : null;
        },
        'group'=> true,
        'attribute' => 'sender_id',
        'format' => 'raw'
    ],
//    [
//        'label' => 'Отделение',
//        'value' => function ($model) {
//            return !empty($model["sender_name"]) ? $model["sender_name"] : null;
//        },
//        'group'=> true,
//        'attribute' => 'sender_name',
//        'pageSummaryOptions'=>['class'=>'text-right']
//    ],
    [
        'attribute' => 'device_name',
        'label' => 'Устройство',
        'width'=>'120px',
        'value' => function($model) {
            if (!empty($model["dev_id"]) && !empty($model["dev_name"])) {
                return Html::a(
                    $model['dev_name'],
                    Url::to(["/GMS/gms-devices/view?id=".$model['dev_id']]),
                    [
                        'title' => $model['dev_name'],
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
        'options' => ['style' => 'font-size:12px;'],
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
