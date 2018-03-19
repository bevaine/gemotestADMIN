<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mihaildev\ckeditor\Assets;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideoHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История воспроизведения';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .center {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
    <div class="center">
        <video
                id="my-player"
                class="video-js"
                controls
                preload="auto"
                poster="../../img/logo.jpg"
                width="783"
                data-setup='{}'>
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a
                web browser that
                <a href="http://videojs.com/html5-video-support/" target="_blank">
                    supports HTML5 video
                </a>
            </p>
        </video>
    </div>
</div>

<div class="gms-video-history-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'responsive'=>true,
        'hover'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'headerOptions' => array('style' => 'width: 30px; text-align: center;'),
                'value' => function ($model) {
                    return !empty($model["vh_id"]) ? $model["vh_id"] : null;
                },
                'attribute' => 'id'
            ],
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Видео',
                'value' => function($model) {
                    if (empty($model['video_name'])
                        || empty($model['thumbnail'])
                        || empty($model['file'])) {
                        return null;
                    }

                    return Html::a(
                        Html::img($model["thumbnail"]),
                        null,
                        [
                            'style' => [
                                'cursor' => 'pointer'
                            ],
                            'title' => $model['video_name'],
                            'target' => '_blank',
                            'onclick' => "
                                var player = videojs('my-player');
                                var modalPlayer = player.createModal('{$model['video_name']}');
                                var modalHtml = $('#deactivate-user');
                                player.src('{$model["file"]}');
                                player.ready(function() {
                                    player.play(); 
                                });
                                player.addChild(modalPlayer);
                                modalPlayer.addClass('vjs-my-fancy-modal');
                                modalPlayer.on('modalclose', function() {
                                    modalHtml.modal('hide');
                                    player.pause();
                                });
                                modalHtml.on('hidden.bs.modal', function () {
                                    modalPlayer.close();
                                    player.pause();
                                });
                                modalHtml.modal('show');
                                modalPlayer.open();"

                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'value' => function ($model) {
                    return !empty($model["region_name"]) ? $model["region_name"] : null;
                },
                'attribute' => 'region_id'
            ],
            [
                'value' => function ($model) {
                    return !empty($model["sender_name"]) ? $model["sender_name"] : null;
                },
                'attribute' => 'sender_name'
            ],
            [
                'attribute' => 'device_id',
                'width'=>'120px',
                'value' => function($model) {
                    return Html::a(
                        $model['device_id'],
                        Url::to(["/GMS/gms-devices/view?id=".$model['dev_id']]),
                        [
                            'title' => $model['device_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'width'=>'196px',
                'attribute' => 'date_at',
                'value' => function($model) {
                    $html = '';
                    if (!empty($model['created_at'])) {
                        $html .= 'с '.$model['created_at'];
                    } else {
                        $html .= "по <span class='not-set'>(не задано)</span>";
                    }
                    $html .= "<br>";
                    if (!empty($model['last_at'])) {
                        $html .= 'по '.$model['last_at'];
                    } else {
                        $html .= "по <span class='not-set'>(не задано)</span>";
                    }
                    return $html;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'options' => [
                        'placeholder' => 'от',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'до',
                        'style'=>['width' => '98px']
                    ],
                    'separator' => 'По',
                    'readonly' => false,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            //'created_at:datetime',
            //'last_at:datetime',
            [
                'attribute' => 'pls_name',
                'width'=>'120px',
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
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);
?>
