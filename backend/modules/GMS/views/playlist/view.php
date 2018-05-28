<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\JsExpression;
use yii\helpers\Url;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */
/* @var $action string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны плейлистов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-view">

    <div class="nav-tabs-custom">

    <ul class="nav nav-tabs">
        <li class="<?= ($action == 'auth') ? "active" : '' ?>">
            <a href="<?php echo Url::to(["gms-devices/index/auth"]) ?>">
                <span>Стандартный</span>
            </a>
        </li>
        <li class="<?= ($action != 'auth') ? "active" : '' ?>">
            <a href="<?php echo Url::to(["gms-devices/index"]) ?>">
                <span>Коммерческий</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
    <div class="tab-pane active" id="tab_1">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
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
                'attribute' => 'sender_id'
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
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return \common\models\GmsPlaylist::getPlayListType($model->type);
                },
                'attribute' => 'type'
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
        ],
    ]) ?>
    <div class="gms-playlist-form">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Видео в шаблоне плейлиста</h3>
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
                        poster="/img/logo.jpg"
                        width="645"
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
    </div>
</div>
<?php
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);

$standartf = new JsExpression('[]');
if (!empty($model->jsonPlaylist)) {
    $standartf = new JsExpression('['.$model->jsonPlaylist.']');
}

$js1 = <<< JS
    
    const tree1 = $("#treetable1"),
        tree2 = $("#treetable2"); 
        
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
                if (data.node.isFolder() === true) {
                    return false;
                }
                const videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        if (res !== null && res.results.file !== undefined) {
                            var videoPath = res.results.file; 
                            var myPlayer = videojs('my-player');
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
                var node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
                if (node.data.duration !== undefined) {
                    var time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
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
        var total = 0;
        var totalStr = '';
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
