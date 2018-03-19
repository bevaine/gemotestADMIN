<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mihaildev\ckeditor\Assets;

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
                'attribute' => 'id',
                'headerOptions' => array('style' => 'width: 30px; text-align: center;'),
            ],
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Видео',
                'value' => function($model) {
                    /** @var \common\models\GmsVideoHistory $model */
                    if (empty($model->videoModel)) {
                        return null;
                    }
                    return Html::a(
                        Html::img($model->videoModel->thumbnail),
                        null,
                        [
                            'style' => [
                                'cursor' => 'pointer'
                            ],
                            'title' => $model->videoModel->name,
                            'target' => '_blank',
                            'onclick' => "
                                var player = videojs('my-player');
                                var modalPlayer = player.createModal('{$model->videoModel->name}');
                                var modalHtml = $('#deactivate-user');
                                player.src('{$model->videoModel->file}');
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
                    /** @var $model \common\models\GmsDevices */
                    return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                },
                'attribute' => 'region_id'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                },
                'attribute' => 'sender_name'
            ],
            'device_id',
            'created_at:datetime',
            'last_at:datetime',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    return !empty($model->playListOutModel) ? $model->playListOutModel->name : null;

                },
                'attribute' => 'pls_name'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);
?>
