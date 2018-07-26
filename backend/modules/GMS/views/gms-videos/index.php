<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mihaildev\ckeditor\Assets;
use kartik\date\DatePicker;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Видео библиотека';
$this->params['breadcrumbs'][] = $this->title;

$urlAjaxCheckPlaylist = \yii\helpers\Url::to(['/GMS/playlist-out/ajax-check-playlist']);
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

    <div class="modal bootstrap-dialog type-warning fade size-normal in" id="modal-dialog" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="bootstrap-dialog-header">
                        <div class="bootstrap-dialog-close-button" style="display: none;">
                            <button class="close" aria-label="close">×</button>
                        </div>
                        <div class="bootstrap-dialog-title" id="w1_title">Подтверждение</div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="bootstrap-dialog-body">
                        <div class="bootstrap-dialog-message" id="bootstrap-dialog-message">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="bootstrap-dialog-footer">
                        <div class="bootstrap-dialog-footer-buttons" id="bootstrap-dialog-footer-buttons"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="center">
            <video
                    id="my-player"
                    class="video-js"
                    controls="controls"
                    preload="auto"
                    poster="/img/logo.jpg"
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
                            'onclick' => "playVideo('".htmlspecialchars($model['name'], ENT_QUOTES)."', '{$model['file']}')"
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'width'=>'196px',
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var $model \common\models\GmsDevices */
                    return !empty($model->created_at)
                        ? date("Y-m-d H:i:s T", $model->created_at)
                        : null;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_from',
                    'attribute2' => 'created_at_to',
                    'options' => [
                        'placeholder' => 'от',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'до',
                        'style'=>['width' => '98px']
                    ],
                    'separator' => '-',
                    'readonly' => false,
                    'type' => \kartik\date\DatePicker::TYPE_RANGE,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'format' => 'html', // datetime
            ],
            'name',
            [
                'attribute' => 'type',
                'width'=>'150px',
                'filter' => \common\models\GmsVideos::getTypeVideo(),
                'value' => function ($model) {
                    if (!is_null($model->type)) {
                        return \common\models\GmsVideos::getTypeVideo($model->type);
                    } else return null;
                }
            ],
            [
                'attribute' => 'size',
                'headerOptions' => array('style' => 'width: 50px;'),
                'label' => 'Разрешение',
                'value' => function($model) {
                    /** @var \common\models\GmsVideos $model */
                    return !empty($model->width && $model->height)
                        ? $model->width.'x'.$model->height
                        : null;
                },
                'format' => 'html',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
                'buttons' => [
                    'delete' => function($url, $model){
                        return Html::a('<span onclick="checkPlayList('.$model->id.')" class="glyphicon glyphicon-trash"></span>', "#");
                    }
                ]
            ]
        ],
    ]); ?>
</div>

<?php
$js1 = <<< JS

    function deleteItem(video_key) {
        $.post("../../GMS/gms-videos/delete?id=" + video_key, { id: video_key});
    }
    
    /**
    * 
    * @param str
    */
    function decodeHtml(str)
    {
        const map =
        {
            '&amp;': '&',
            '&lt;': '<',
            '&gt;': '>',
            '&quot;': '"',
            '&#039;': "'"
        };
        return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
    }
    
    function checkPlayList(video_key) {
        $.ajax({
            type : 'GET',
            url: '{$urlAjaxCheckVideo}',
            data: {
                video_key: video_key,
            },
            success: function (res) {
                if (res !== 'null' && res.state !== undefined) {
                    let message = '';
                    let html_button = '';
                    let style = '';
                    let button_cancel = '<button class="btn btn-default" data-dismiss="modal">';
                    button_cancel += '<span class="glyphicon glyphicon-ban-circle"></span> Отмена';
                    button_cancel += '</button>';
                    if (res.state === 0) {
                        style = 'danger';
                        message = res.message;
                    } else {
                        style = 'warning';
                        message = "Вы уверены, что хотите удалить этот элемент?";
                        html_button += '<button class="btn btn-' + style + '" id="delete-item"';
                        html_button += ' onClick="deleteItem(' + video_key + ')">';
                        html_button += '<span class="glyphicon glyphicon-ok"></span> Ok';
                        html_button += '</button>';
                    }                        
                    $('#bootstrap-dialog-message').html(message);  
                    $('#bootstrap-dialog-footer-buttons').html(button_cancel + html_button); 
                    $('#modal-dialog')
                        .removeClass()
                        .toggleClass('modal bootstrap-dialog type-' + style + ' fade size-normal in')
                        .modal('show');
                }
            }
        });
    }
    
    /**
    * 
    * @param name
    * @param file
    */
    function playVideo(name, file) 
    {
        name = decodeHtml(name);
        $('.video-js').prop('controls',true);
        const player = videojs('my-player');
        const modalPlayer = player.createModal(name);
        const modalHtml = $('#deactivate-user');
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