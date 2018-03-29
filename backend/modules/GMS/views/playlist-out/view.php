<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Плейсты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

empty($model->active) ? $activePls = 'active' : $activePls = 'block';
?>
<div class="gms-playlist-out-view">

    <?php $form = ActiveForm::begin(['id'=>'form-input','method' => 'post']); ?>

    <p>
        <?= Html::SubmitButton($activePls == 'active' ? 'Разблокировать' : 'Заблокировать', [
            'name' => 'active-playlist',
            'class' => $activePls == 'active' ? 'btn btn-success' : 'btn btn-danger',
            'value' => $activePls
        ]) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="gms-playlist-form">
        <div class="row">
            <div class="col-lg-10">
                <div class="form-group">
                    <div class="box box-solid box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Плейлист: <?= $model->name ?></h3>
                        </div>
                        <div class="box-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                                        },
                                        'attribute' => 'region_id'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                                        },
                                        'attribute' => 'sender_id'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->deviceModel) ? $model->deviceModel->device : null;

                                        },
                                        'attribute' => 'device_id'
                                    ],
                                    'date_start:date',
                                    'date_end:date',
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->time_start) ? date('H:i', $model->time_start) : null;

                                        },
                                        'attribute' => 'time_start'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->time_end) ? date('H:i', $model->time_end) : null;

                                        },
                                        'attribute' => 'time_end'
                                    ],
                                    [
                                        'label' => 'Воспроизводить только',
                                        'value' => $model->getDaysPlaylist(),
                                        'format' => 'raw',
                                    ],
                                    'created_at:datetime',
                                    [
                                        'label' => 'Статус',
                                        'value' => $model->getAuthStatus(),
                                        'format' => 'raw',
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($model->jsonPlaylist)) : ?>
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Видео в плейлисте</h3>
                        </div>
                        <div class="box-body">
                            <table id="treetable1">
                                <colgroup>
                                    <col width="50px">
                                    <col width="520px">
                                    <col width="80px">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Название</th>
                                    <th>Длител.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                                <thead>
                                <tr>
                                    <th style="font-size: smaller" colspan="2">Итого</th>
                                    <th colspan="3"><div class="duration-summ1" id="duration-summ1"></div></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <video
                        id="my-player"
                        class="video-js"
                        controls
                        preload="auto"
                        poster="../../img/logo.jpg"
                        width="660"
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
        <?php endif; ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);

$standartf = new JsExpression('[]');
if (!empty($model->jsonPlaylist)) {
    $standartf = new JsExpression('['.$model->jsonPlaylist.']');
}

$js1 = <<< JS
    
    const tree1 = $("#treetable1");
    const tree2 = $("#treetable2"); 
        
    $(function()
    {
        tree1.fancytree({
            extensions: ["table", "dnd"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },                
            source: {$standartf},
            dblclick: function(event, data) {
                const videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        if (res !== null && res.results.file !== undefined) {
                            const videoPath = res.results.file; 
                            const myPlayer = videojs('my-player');
                            myPlayer.src(videoPath);
                            myPlayer.ready(function() {
                                this.play();
                            });
                        }
                    }
                });
            },
            beforeActivate: function(event, data) {
            },
            renderColumns: function(event, data) {
                const node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
                if (node.data.duration !== undefined) {
                    const time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
                    tdList.eq(2).text(time);
                } 
                sumDuration(node.parent, '#duration-summ1');
            },
            dnd: {
                preventVoidMoves : true,
                preventRecursiveMoves : true,
                autoExpandMS :400,
                dragStart : function(node, data) {
                    return false;
                },
                dragEnter : function(node, data) {
                    return true;
                },
                dragOver : function(node, data) {
                },
                dragDrop : function(node, data) {
                    return false;
                }
            }
        });
    });
    
    /**
    * 
    * @param parent
    * @param span
    */
    function sumDuration (parent, span) 
    {
        let total = 0;
        let totalStr = '';
        if (parent.getChildren() === undefined) return;
        $.each(parent.getChildren(), function() {
            if (this.data.duration !== undefined) {
                total += parseInt(this.data.duration, 10);
            }
        });
        if (total > 0) {
            totalStr = moment.unix(total).utc().format("HH:mm:ss");
        }
        $(span).html(totalStr);
    }
JS;
$this->registerJs($js1);
