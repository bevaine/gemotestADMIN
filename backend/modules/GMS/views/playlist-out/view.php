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
            <div class="col-lg-12">
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
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Видео в плейлисте</h3>
                        </div>
                        <div class="box-body">
                            <table id="treetable1">
                                <colgroup>
                                    <col width="50px">
                                    <col width="400px">
                                    <col width="120px">
                                    <col width="70px">
                                    <col width="70px">
                                    <col width="80px">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Старт</th>
                                    <th>Стоп</th>
                                    <th>Продолж.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                                <thead>
                                <tr>
                                    <th style="font-size: medium">Итого:</th>
                                    <th style="font-size: medium" colspan="1">
                                        <span class="day-summ" id="day-summ"></span>
                                    </th>
                                    <th style="text-align: right;" colspan="3">
                                        <span style="padding: 4px" class="error_span" id="error_span"></span>
                                    </th>
                                    <th>
                                        <div class="duration-summ" id="duration-summ"></div>
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <video
                        id="my-player"
                        class="video-js"
                        controls
                        preload="auto"
                        poster="/img/logo.jpg"
                        width="800"
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
if (!empty($model->jsonKodi)) {
    $d = \yii\helpers\ArrayHelper::toArray(
        json_decode($model->jsonKodi)
    );
    $d['icon'] = '/img/video1.png';
    foreach ($d['children'] as $key => $ww) {
        $d['children'][$key]['data'] = [
            'type' =>(string)$ww['type'],
            'file' => $ww['file'],
            'start' => $ww['start'],
            'end' => $ww['end'],
            'title' => $ww['title']
        ];
        $d['children'][$key]['icon'] =
            $ww['type'] == 1 ? '/img/gemotest.jpg' : '/img/dollar.png';
    }
    $standartf = new JsExpression(json_encode(array($d)));
}

$js1 = <<< JS
    
    const tree1 = $("#treetable1");
        
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
                let typePlaylist = ''; 
                if (node.data.type === '1') {
                    typePlaylist = 'Стандартный';
                } else if (node.data.type === '2') {
                    typePlaylist = 'Коммерческий';
                }
                tdList.eq(2).text(typePlaylist);
                if (node.data.start !== undefined) {
                    const time_start = moment.unix(node.data.start).utc().format("HH:mm:ss");
                    tdList.eq(3).text(time_start);
                }
                if (node.data.end !== undefined) {
                    const time_end = moment.unix(node.data.end).utc().format("HH:mm:ss");
                    tdList.eq(4).text(time_end);
                }
                let time = getTime(node);
                if (time !== false) {
                    time = moment.unix(time).utc().format("HH:mm:ss");
                    tdList.eq(5).text(time);
                }
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
        
        sumDuration();
    });
    
    /**
    * 
    * @param node
    * @returns {*}
    */
    function getTime(node) 
    {
        if (node.data.start !== undefined && node.data.end !== undefined){
            return parseInt(node.data.end, 10) - parseInt(node.data.start, 10);
        } else return false; 
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function sumDuration() 
    {
        let i = 0;
        let total = 0;
        let tdList = '';
        let totalStr = '00:00:00';
        
        const 
            treeObject = $("#treetable1"), 
            tree = treeObject.fancytree("getTree"),
            parent = tree.getNodeByKey("playlist");
        
        if (parent !== undefined && parent.getChildren() !== null) 
        { 
            $.each(parent.getChildren(), function() 
            {
                tdList = $(this.tr).find(">td");
                tdList.eq(0).text(++i).addClass("alignRight");
                let time = getTime(this);
                if (time !== false) {
                    total += time; 
                }
            });
            
            if (total > 0) {
                totalStr = moment.unix(total).utc().format("HH:mm:ss");
                $('#duration-summ').html(totalStr).css({
                    color : "black"
                });
            }
        }
    }
JS;
$this->registerJs($js1);
