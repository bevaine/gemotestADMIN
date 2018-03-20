<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mihaildev\ckeditor\Assets;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Видео библиотека';
$this->params['breadcrumbs'][] = $this->title;
?>
    <style>
        .center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .custom-video-controls {
            z-index: 2147483647;
        }
    </style>

    <div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="center">
            <video
                    id="my-player"
                    class="video-js"
                    controls="controls"
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

    <div class="gms-videos-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => array('style' => 'width: 50px;'),
            ],
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Видео',
                'value' => function($model) {
                    /** @var \common\models\GmsVideos $model */
                    return Html::a(
                        Html::img($model['thumbnail']),
                        null,
                        [
                            'style' => [
                                'cursor' => 'pointer'
                            ],
                            'title' => $model['name'],
                            'target' => '_blank',
                            'onclick' => "playVideo('{$model['id']}', '{$model['file']}')"
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'name',
            [
                'headerOptions' => array('style' => 'width: 195px; text-align: center;'),
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsVideos */
                    return isset($model->created_at) ? date('d-m-Y', $model->created_at) : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'name' => 'created_at',
                    'attribute' => 'created_at',
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy'
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            'type',
            [
                'attribute' => 'time',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideos */
                    return date("H:i:s" , mktime(0, 0, $model->time));
                },
            ],
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view} {delete}'],
        ],
    ]); ?>
</div>

<?php
$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);
$js1 = <<< JS
    function playVideo(name, file) 
    {
        $('.video-js').prop('controls',true);
        var player = videojs('my-player');
        var modalPlayer = player.createModal(name);
        var modalHtml = $('#deactivate-user');
        player.src(file);
        player.ready(function() {
            player.play(); 
        });
        player.addChild(modalPlayer);
        modalPlayer.addClass('vjs-my-fancy-modal');
        $('.vjs-my-fancy-modal').css('height', '93%');
        modalPlayer.on('modalclose', function() {
            modalHtml.modal('hide');
            player.pause();
        });
        modalHtml.on('hidden.bs.modal', function () {
            modalPlayer.close();
            player.pause();
        });
        modalHtml.modal('show');
        modalPlayer.open();
    }
JS;
$this->registerJs($js1, yii\web\View::POS_HEAD);
?>