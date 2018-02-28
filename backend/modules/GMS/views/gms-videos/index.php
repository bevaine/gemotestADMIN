<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mihaildev\ckeditor\Assets;

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
                            'onclick' => "
                                var player = videojs('my-player');
                                var modalPlayer = player.createModal('{$model['name']}');
                                var modalHtml = $('#deactivate-user');
                                player.src('{$model['file']}');
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
            'name',
            'created_at:datetime',
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

JS;
$this->registerJs($js1);
?>