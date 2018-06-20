<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\web\JsExpression;
use wbraganca\fancytree\FancytreeWidget;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */
/* @var $action string
/* @var $form yii\widgets\ActiveForm */

$layout = <<< HTML
    {input1}
    {separator}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$css = <<<CSS
    td.alignRight {
        text-align: right 
    }
    td.alignCenter {
        text-align: center 
    }
    td:hover.reg {
        background: #20b426 
    } 
    td.com {
        color: #df8505 
    }
    .reg {
        color: #fff;
        background: #00a65a;
        text-shadow: 0 0 black;
    }
    .vjs-big-play-button {
        display: none;
    }
    .video-info {
        padding-top: 10px;
        font-style: italic;
    }
    .video-info-normal {
        padding-top: 10px;
        font-style: normal;
    }
    /* fallback */
    @font-face {
        font-family: 'Material Icons';
        font-style: normal;
        font-weight: 400;
        /*src: url("../../fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2") format('woff2');*/
    }
    .material-icons {
        font-family: 'Material Icons',serif;
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }
    table {
      border-collapse: collapse;
    }  
CSS;
$this->registerCss($css);

$html = <<<HTML
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Стандартный шаблон плейлиста</h3>
                </div>
                <div class="box-body">
HTML;
$html .= FancytreeWidget::widget([
    'id' => 'template_region',
    'options' => [
        'disabled' => true,
        'source' => [
            [
                'title' => 'уточните параметры для отображения',
                'folder' => false,
            ]
        ],
        'extensions' => ['dnd'],
        'click' => new JsExpression('function(node, data) {
            if (data.node.isFolder()) {
                let id = getFormVal("pls_std_id");
                let go_to_url = "/GMS/playlist/view?id=" + id;
                window.open(go_to_url, "_blank");
                data.node.expanded = false;
            } else return false;
        }'),
        'dblclick' => new JsExpression('function(node, data) {
            if (!data.node.isFolder()) {
                const playlistNode = $("#treetable")
                    .fancytree("getTree")
                    .getNodeByKey("playlist"),
                    addChild = [];
                addChild.push(data.node);
                playlistNode.addNode(addChild, "child");
                sortNode();
                sumDuration();
            } 
        }'),
        'dnd' => [
            'preventVoidMoves' => true,
            'preventRecursiveMoves' => true,
            'autoExpandMS' => 400,
            'dragStart' => new JsExpression('function(node, data) {
                                if (!data.tree.options.disabled) {
                                    return true;
                                    return !node.isFolder();
                                } else return false;
                            }'),
            'dragEnter' => new JsExpression('function(node, data) {
                                return true;
                            }'),
            'dragDrop' => new JsExpression('function(node, data) {
                                return false;
                            }'),
        ],
    ]
]);
$html .= <<<HTML
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Коммерческий шаблон плейлиста</h3>
                </div>
                <div class="box-body">
HTML;
$html .= FancytreeWidget::widget([
    'id' => 'template_commercial',
    'options' => [
        'disabled' => true,
        'source' => [
            [
                'title' => 'уточните параметры для отображения',
                'folder' => false,
            ]
        ],
        'extensions' => ['dnd'],
        'click' => new JsExpression('function(node, data) {
            if (data.node.isFolder()) {
                let id = getFormVal("pls_com_id");
                let go_to_url = "/GMS/playlist/view?id=" + id;
                window.open(go_to_url, "_blank");
                data.node.expanded = false;
            } else return false;
        }'),
        'dblclick' => new JsExpression('function(node, data) {
            if (!data.node.isFolder()) {
                const playlistNode = $("#treetable")
                    .fancytree("getTree")
                    .getNodeByKey("playlist"),
                    addChild = [];
                data.node.moveTo(playlistNode, "child");
                sortNode();
                sumDuration();
            }
        }'),
        'dnd' => [
            'preventVoidMoves' => true,
            'preventRecursiveMoves' => true,
            'autoExpandMS' => 400,
            'dragStart' => new JsExpression('function(node, data) {
                                if (!data.tree.options.disabled) {
                                    return !node.isFolder();
                                } else return false;
                            }'),
            'dragEnter' => new JsExpression('function(node, data) {
                                return true;
                            }'),
            'dragDrop' => new JsExpression('function(node, data) {
                                return false;
                            }'),
            'dragEnd' => new JsExpression('function(node, data) {
                            }'),
        ],
    ]
]);
$html .= <<<HTML
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Окончательный плейлист</h3>
                </div>
                <div class="box-body">
                    <table id="treetable">
                        <colgroup>
                            <col width="50px">
                            <col width="500px">
                            <col width="150px">
                            <col width="100px">
                            <col width="70px">
                            <col width="70px">
                            <col width="30px">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Плейлист</th>
                            <th>Тип ролика</th>
                            <th>Продолжител.</th>
                            <th>Кол-во показ.</th>
                            <th>Общая продолж.</th>
                            <th></th>
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
                                <td style="text-align: center;"></td>
                            </tr>
                        </tbody>
                        <thead>
                            <tr>
                                <th style="padding-left: 5px;font-size: smaller">Итого:</th>
                                <th style="font-size: smaller" colspan="2">
                                    <span class="day-summ" id="day-summ"></span>
                                </th>
                                <th colspan="2">Стандартное время:</th>
                                <th colspan="1">
                                    <div class="duration-std" id="duration-std"></div>
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="2">Коммерческое время:</th>
                                <th colspan="1">
                                    <div class="duration-com" id="duration-com"></div>
                                </th>
                                <th></th>                            
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="2">Общее время:</th>
                                <th colspan="1">
                                    <div class="duration-summ" id="duration-summ"></div>
                                </th>
                                <th></th>                            
                            </tr>
                            <tr>
                                <th colspan="7">
                                    <div style="padding-left: 5px" class="error_span" id="error_span"></div>
                                </th> 
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
?>

<div class="gms-playlist-out-form">

    <?php $form = ActiveForm::begin(['id' => 'form']); ?>

    <?= Html::hiddenInput('GmsPlaylistOut[pls_id]', $model->id) ?>

    <div class="modal bootstrap-dialog type-warning fade size-normal in" id="modal-dialog" tabindex="-1" role="dialog"
         aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="bootstrap-dialog-header">
                        <div class="bootstrap-dialog-close-button" style="display: none;">
                            <button class="close" aria-label="close">×</button>
                        </div>
                        <div class="bootstrap-dialog-title" id="bootstrap-dialog-title">Подтверждение</div>
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
                        <div class="bootstrap-dialog-footer-buttons" id="bootstrap-dialog-footer-buttons">
                            <button class="btn btn-default" data-dismiss="modal">
                                <span class="glyphicon glyphicon-ban-circle"></span> Отмена
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Привязка плейлиста и параметры воспроизведения</h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group date">
                                <?= Html::label('Период воспроизведения') ?>
                                <?= DatePicker::widget([
                                    'name' => 'GmsPlaylistOut[date_start]',
                                    'name2' => 'GmsPlaylistOut[date_end]',
                                    'value' => date('d-m-Y', $model->isNewRecord ? time() : $model->date_start),
                                    'value2' => date('d-m-Y', $model->isNewRecord ? time() : $model->date_end),
                                    'type' => DatePicker::TYPE_RANGE,
                                    'separator' => '<i class="glyphicon glyphicon-resize-horizontal"></i>',
                                    'layout' => $layout,
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd-mm-yyyy'
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <?= Html::label('Время воспроизведения') ?>
                            <table style="border-width: 1px; border-color: #d2d6de; border-style: ridge; border-radius: 3px 0 0 3px;">
                                <tr>
                                    <td>
                                        <div class="form-control-wrapper">
                                            <?= Html::input(
                                                'text',
                                                'GmsPlaylistOut[time_start]',
                                                date("H:i", $model->isNewRecord ? mktime(7, 0) : $model->time_start),
                                                [
                                                    'id' => 'gmsplaylistout-time_start',
                                                    'class' => 'form-control floating-label',
                                                    'placeholder' => 'начало'
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="padding-left: 7px; padding-right: 15px">
                                            <i class="glyphicon glyphicon-resize-horizontal"></i>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="form-control-wrapper">
                                            <?= Html::input(
                                                'text',
                                                'GmsPlaylistOut[time_end]',
                                                date("H:i", $model->isNewRecord ? mktime(18, 0) : $model->time_end),
                                                [
                                                    'id' => 'gmsplaylistout-time_end',
                                                    'class' => 'form-control floating-label',
                                                    'placeholder' => 'конец'
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group week">
                                <?= Html::label('Воспроизводить только в') ?>
                                <p>
                                    <?php
                                    foreach ($model::WEEK as $key => $value) {
                                        echo "<span style='padding-left: 10px'>" . Html::Activecheckbox($model, $key, [
                                                'value' => "1",
                                                'label' => $value,
                                                'data-url' => 'is_monday'
                                            ]) . "</span>";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="">
                        <a data-toggle="tab" aria-expanded="true" href="#tab_1">Привязка к региону/отделению</a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" aria-expanded="true" href="#tab_2">Привязка к группе устройств</a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" aria-expanded="true" href="#tab_3">Привязка к устройству</a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div id="tab_1" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group region">
                                    <?= $form->field($model, 'region_id')->dropDownList(\common\models\GmsRegions::getRegionList(), [
                                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                    ]); ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group sender_id">
                                    <?= $form->field($model, 'sender_id')->dropDownList([], [
                                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab_2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group group_id">
                                    <?= $form->field($model, 'group_id')->dropDownList(\common\models\GmsGroupDevices::getGroupList(), [
                                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab_3" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group device_id">
                                    <?= $form->field($model, 'device_id')->dropDownList(\common\models\GmsDevices::getDeviceList(), [
                                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $html ?>

                </div>
            </div>

        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Информация о ролике</h3>
                    </div>
                    <div class="box-body">
                        <video
                                id="my-player"
                                class="video-js"
                                controls
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
                        <div class="video-info" id="video-info">
                            Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::Button($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxDevice = \yii\helpers\Url::to(['/GMS/gms-devices/ajax-device-list']);
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);
$urlAjaxTime = \yii\helpers\Url::to(['/GMS/playlist-out/ajax-time-check']);
$urlAjaxPlaylistTemplate = \yii\helpers\Url::to(['/GMS/playlist/ajax-playlist-template']);
$urlAjaxCheckPlaylist = \yii\helpers\Url::to(['/GMS/playlist-out/ajax-check-playlist']);

$isNew = 'false';
$pls_id = 'null';
$source = [];
if ($model->isNewRecord) {
    $isNew = 'true';
    $source = [
        [
            'title' => 'Новый плейлист',
            'key' => 'playlist',
            'folder' => true,
            'expanded' => true,
            'icon' => '/img/video1.png'
        ]
    ];
    $source = json_encode($source);
} else {
    $pls_id = $model->id;
    if (!empty($model->jsonPlaylist)) {
        $source = new JsExpression('[' . $model->jsonPlaylist . ']');
    }
}

$js1 = <<< JS

    const newPlayList = [
            {
                "title" : "Новый плейлист", 
                "key" : "playlist", 
                "folder" : true, 
                "expanded" : true, 
                "icon" : "/img/video1.png"
            }
        ],
        inputVar = $("input"),
        regionSelectConst = $('#gmsplaylistout-region_id'),
        senderSelectConst = $('#gmsplaylistout-sender_id'),
        groupSelectConst = $('#gmsplaylistout-group_id'),
        deviceSelectConst = $('#gmsplaylistout-device_id'),
        timeStartConst = $('#gmsplaylistout-time_start'),
        timeEndConst = $('#gmsplaylistout-time_end'),
        isNew = {$isNew};

    /**
    * 
    * @param htm_table
    */
    function resetPlayer(htm_table = null) {
        const myPlayer = videojs('my-player');
        if (htm_table === null) {
            htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
        }
        myPlayer.reset();
        myPlayer.poster("/img/logo.jpg");
        myPlayer.width("783");
        
        $('#video-info')
            .addClass('video-info-normal')
            .html(htm_table);        
    }
  
    /**
    * 
    */
    $(function()
    {
        const tree = $("#treetable");
        
        setTabs();
        
        $(".btn-primary, .btn-success").click(function() { 
            checkTime();
        });
        
        $(".region select").change(function() {
            disableTree();
            resetPlayer();
            setSender ($(this).val());
            setTreeData (
                $(this).val(), 
                senderSelectConst.val(),
                null,
                null,
            );
        });
        
        $(".sender_id select").change(function() {
            disableTree();
            resetPlayer();
            setTreeData (
                regionSelectConst.val(), 
                $(this).val(),
                null,
                null
            );
        });
        
        $(".group_id select").change(function() {
            disableTree();
            resetPlayer();
            setTreeData (
                null, 
                null, 
                $(this).val(),
                null
            );
        });
        
        $(".device_id select").change(function() {
            disableTree();            
            resetPlayer();
            setTreeData (
                null, 
                null, 
                null,
                $(this).val()
            );
        });
        
        tree.fancytree({
            extensions: ["table", "dnd", "edit"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },
            renderStatusColumns: true,
            source: {$source},
            dblclick: function(event, data) {
            },
            beforeActivate: function(event, data) {
            },
            renderColumns: function(event, data) 
            {
                let time = 0;
                const 
                    node = data.node, 
                    tdList = $(node.tr).find(">td");

                tdList.eq(1).addClass('dblclick');
                
                if (node.data.duration !== undefined) {
                    time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
                }

                if (node.data.type !== undefined) 
                {
                    let icon = '';
                    let html = '';
                    let typePlaylist;
                    let views_str = '';
                    
                    if (node.data.type === '1') {
                        
                        icon = 'gemotest.jpg';
                        typePlaylist = 'Стандартный';
                        
                        tdList.eq(2).text(typePlaylist);
                        if (time !== 0) {
                            node.data.views = 1;
                            node.data.total = node.data.duration;
                            tdList.eq(3).text(time); 
                            tdList.eq(5).text(time);
                        } 
                    } else if (node.data.type === '2') {
                                                
                        icon = 'dollar.png';
                        typePlaylist = 'Коммерческий';
                        
                        const folderNode = $("#fancyree_template_commercial")
                                .fancytree("getTree")
                                .getNodeByKey(node.key);
                        
                        if (folderNode) folderNode.remove();    
                        
                        tdList.eq(2).text(typePlaylist);
                        if (node.data.views !== undefined) {
                            views_str = "value = '" + node.data.views + "'";
                        }
                        
                        html = '<input style="width:50px" type="number" id="count_view[' + node.key + ']" min="0" step="1" ' + views_str + '/>';
                        tdList.eq(4).html(html).addClass('alignCenter');
                        if (node.data.total !== undefined) {
                            const total_str = moment.unix(node.data.total).utc().format("HH:mm:ss");
                            tdList.eq(5).text(total_str); 
                        }
                        
                        tdList.eq(4).find("input").change(function(){
                            node.data.views = $(this).val();
                            if (updateTotal(node)) {
                                const time_views = moment.unix(node.data.total).utc().format("HH:mm:ss");
                                tdList.eq(5).text(time_views);  
                            }
                            sumDuration();
                        });
                        
                        if (time !== 0) {
                            html = '<div class="input-group bootstrap-timepicker timepicker">';
                            html += '<input id="time_com[' + node.key + ']" type="text" class="form-control input-small">';
                            html += '</div>';
                            tdList.eq(3).html(html);
                            tdList.eq(3).find("input").timepicker({
                                minuteStep: 1,
                                template: 'modal',
                                appendWidgetTo: 'body',
                                showSeconds: true,
                                showMeridian: false,
                                defaultTime: time
                            }).on('changeTime.timepicker', function(e) {
                                const date1 = new Date('1976-01-01 ' + time);
                                const date2 = new Date('1976-01-01 ' + e.time.value);
                                const minDate = new Date('1976-01-01 00:00:10');
                                if (date2 > date1 || date2 < minDate) {
                                    $(this).timepicker('setTime', time);
                                } else {
                                    const m1 = moment('1976-01-01T00:00:00');
                                    const splitDate2 = e.time.value.split(':');
                                    const m2 = moment('1976-01-01T00:00:00').set({
                                        'hour': splitDate2[0], 
                                        'minute': splitDate2[1], 
                                        'second' : splitDate2[2]
                                    });
                                    let timestamp = (m2.diff(m1, 'ms'));
                                    timestamp = timestamp / 1000;
                                    node.data.duration = timestamp;
                                    if (updateTotal(node)) {
                                        const time_views = moment.unix(node.data.total).utc().format("HH:mm:ss");
                                        tdList.eq(5).text(time_views);  
                                    }
                                    sumDuration();
                                }
                            });                            
                        }
                    }
                    
                    if (icon !== '') {
                        const span = $(node.span);
                        span.find("> span.fancytree-icon").css({
                            backgroundImage: "url(/img/" + icon + ")",
                            backgroundPosition: "0 0"
                        });
                    }
                }

                if (!node.isFolder()) {
                    tdList.eq(6).html('<span id="trash-node" style="cursor:pointer;" class="glyphicon glyphicon-trash"></span>');
                }
            },
            edit: {
                triggerStart: ["clickActive"],
                beforeEdit : function(event, data){
                    return (data.node.isFolder() && data.node.key !== 'ComFolder'); 
                },
                edit : function(event, data){
                },
                beforeClose : function(event, data){
                },
                save : function(event, data){
                    setTimeout(function(){
                        $(data.node.span).removeClass("pending");
                        data.node.setTitle(data.node.title);
                    }, 2000);
                    return true;
                },
                close : function(event, data){
                    if(data.save) {
                        $(data.node.span).addClass("pending");
                    }
                }
            },
            dnd: {
                preventVoidMoves : true,
                preventRecursiveMoves : true,
                autoExpandMS :400,
                dragStart : function(node, data) {
                    if (node.data.type !== undefined && node.data.type === '2') {
                        return false;
                    }
                    return !node.isFolder();
                },
                dragEnter : function(node, data) {
                    return true;
                },
                dragOver : function(node, data) {
                },
                dragDrop : function(node, data) 
                {
                    if (data.otherNode) {
                        
                        let sameTree = (data.otherNode.tree === data.tree);
                        const playlistNode = data.tree.getNodeByKey('playlist');                    
                        
                        if (!sameTree) {
                            if (data.otherNode.isFolder()) {
                                playlistNode.addNode(data.otherNode.children, 'child');                           
                            } else {
                                const addChild = [];
                                addChild.push(data.otherNode);
                                if (data.otherNode.data.type === '2') {
                                    data.otherNode.moveTo(playlistNode, "child");
                                } else if (data.otherNode.data.type === '1') {
                                    playlistNode.addNode(addChild, 'child');
                                }
                            }
                        } else {
                            data.otherNode.moveTo(node, data.hitMode); 
                            if (!data.otherNode.isChildOf(playlistNode)) {
                                data.otherNode.moveTo(playlistNode, "child");
                            }
                            data.otherNode.render(true);
                        }
                        playlistNode.setExpanded();
                    } else if (data.otherNodeData) {
                        node.addChild(data.otherNodeData, data.hitMode);
                    } else {
                        node.addNode({
                          title: transfer.getData("text")
                        }, data.hitMode);
                    }
                    sortNode();
                    sumDuration();
                    node.setExpanded();
                }
            }
        });
        
        tree.delegate("span[id=trash-node]", "click", function(e)
        {
            const node = $.ui.fancytree.getNode(e),           
                tdList = $(node.tr),
                parent = node.parent;
            
            if (node.data.type === undefined) return;
            
            if (node.data.type  === '2') {
                const commercialObject = $("#fancyree_template_commercial"), 
                    commercialTree = commercialObject.fancytree("getTree"), 
                    folderNode = commercialTree.getNodeByKey("playList[1]");
                
                if (!commercialTree.getNodeByKey(node.key)) {
                    node.moveTo(folderNode, "child");
                } else {
                    node.remove();
                }
                
                folderNode.setExpanded();
                tdList.remove(); 

            } else if (node.data.type  === '1') {
                node.remove();
            }
            node.render(true); 
            e.stopPropagation();  
            resetPlayer();
            sortNode();
            sumDuration();
        });
        
        tree.delegate(".dblclick", "dblclick", function(e)
        {
            const node = $.ui.fancytree.getNode(e);
            if (node.isFolder()) {
                e.stopPropagation();
                return;
            } 
            const videoKey = node.key;
            
            $.ajax({
                url: '{$urlAjaxVideo}',
                data: {video: videoKey},
                success: function (res) {
                    let htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
                    if (res !== null && res.results.file !== undefined) {
                        const videoPath = res.results.file; 
                        const myPlayer = videojs('my-player');
                        myPlayer.src(videoPath);
                        myPlayer.ready(function() {
                            this.play();
                        });
                    }
                    if (res.results.table !== undefined) {
                        htm_table = res.results.table;
                    }
                    $('#video-info')
                        .addClass('video-info-normal')
                        .html(htm_table);
                }
            });
            e.stopPropagation(); 
        });
        
        $("a[href='#tab_1'], a[href='#tab_2'], a[href='#tab_3']").click(function() {
            if (isNew !== false) {
                regionSelectConst.prop('selectedIndex', 0);
                setSender(regionSelectConst.val());
                groupSelectConst.prop('selectedIndex', 0);
                deviceSelectConst.prop('selectedIndex', 0); 
                disableTree();
                resetPlayer();
                sumDuration();
            }
        });
    
        timeEndConst.bootstrapMaterialDatePicker({ 
            date: false, shortTime: false, format: 'HH:mm', lang : 'ru'
        }).on('change', function(e, date) 
        {
            setDayTime();
            sumDuration();
        });

        timeStartConst.bootstrapMaterialDatePicker({
            date: false, shortTime: false, format: 'HH:mm', lang : 'ru'
        }).on('change', function(e, date) 
        {
            timeEndConst.bootstrapMaterialDatePicker('setMinDate', date);
            setDayTime();
            sumDuration();
        });

        setDayTime();
		            
        setSender (regionSelectConst.val());
        
        setTimeout(function(){
            setTreeData (
                regionSelectConst.val(), 
                senderSelectConst.val(),
                groupSelectConst.val(),
                deviceSelectConst.val()
            );

        }, 500);             
        
        setTimeout(function(){
            sumDuration();
        }, 1000); 
        
        sortNode();
    });
    
    /**
    * 
    */
    function sortNode() {
        const 
            treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            d = tree.toDict(true),
            children = d.children[0].children;
            
        let g = [], k = [], comPos = null;
        
        if (children === undefined) {
            return false;
        }
        
        children.forEach(function(node) 
        {
            if (node.data.type === '1') {
                g.push(node);        
            } else if (node.data.type === '2') {
                k.push(node);
            }
        });
        
        if (k.length > 0 && g.length > 0) {
            comPos = g.length;        
            d.children[0].children = $.merge(g, k);
            tree.reload(d);          
        }       

        if (comPos !== null) {
            $( "#treetable tr").eq(comPos + 2).find(">td").css({
                "border-top-style" : "double",
                "border-top-width" : "10px",
            });        
        }
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function setTabs() 
    {
        let liTab1, liTab2, liTab3;
        let customTabs = $('.nav-tabs-custom'),
            navTabs = customTabs.find('.nav-tabs'),
            liTabs = navTabs.find('li');

        if (liTabs.length > 0) {
            liTab1 = liTabs.eq(0);
            liTab2 = liTabs.eq(1);
            liTab3 = liTabs.eq(2);                
        }        
        
        if (isNew === true) {
           $('ul.nav a[href="#tab_1"]').tab('show');
           return true;
        }

        if (String('{$model->region_id}') !== '') {
            $('ul.nav a[href="#tab_1"]').tab('show');
            liTab2
                .addClass('disabled')
                .find('a').attr('data-toggle', '');
            liTab3
                .addClass('disabled')
                .find('a').attr('data-toggle', '');
        }
        
        if (String('{$model->group_id}') !== '') {
            $('ul.nav a[href="#tab_2"]').tab('show');
            liTab1
                .addClass('disabled')
                .find('a').attr('data-toggle', '');            
            liTab3
                .addClass('disabled')
                .find('a').attr('data-toggle', '');
        } 
        
        if (String('{$model->device_id}') !== '') {
            $('ul.nav a[href="#tab_3"]').tab('show');
            liTab1
                .addClass('disabled')
                .find('a').attr('data-toggle', '');            
            liTab2
                .addClass('disabled')
                .find('a').attr('data-toggle', '');
        } 
        return true;
    }
    
    /**
    * 
    * @returns {*}
    */
    function getTimeDay() {
        const datePicker1 = timeStartConst.val(),
            datePicker2 = timeEndConst.val(),
            date1 = new Date('1976-01-01 ' + datePicker1),
            date2 = new Date('1976-01-01 ' + datePicker2);
        if (datePicker2 > datePicker1) {
            const splitDate1 = datePicker1.split(':'),
                splitDate2 = datePicker2.split(':'),            
                m1 = moment('1976-01-01T00:00:00').set({
                    'hour': splitDate1[0], 
                    'minute': splitDate1[1], 
                    'second' : splitDate1[2]
                }),
                m2 = moment('1976-01-01T00:00:00').set({
                    'hour': splitDate2[0], 
                    'minute': splitDate2[1], 
                    'second' : splitDate2[2]
                });
            let timestamp = (m2.diff(m1, 'ms'));
            return timestamp / 1000;
        }
        return false;
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function sumDuration() 
    {
        let i = 0,
            total = 0,
            std_time = 0,
            com_time = 0,
            color = 'green',
            totalStr = '00:00:00';

        const 
            treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            d = tree.toDict(true);
            
        if (d.children[0].children === undefined) {
            resetTimeDuration();
            return false;
        }
        
        d.children[0].children.forEach(function(children) 
        {
            if (children.data.type !== undefined) {
                let tdList = $(children.tr).find(">td");
                if (children.data.type === '1') {
                    tdList.eq(0).text(++i).addClass("alignRight");
                } else if (children.data.type === '2') {
                    tdList.eq(0).append().css({
                        backgroundImage: "url(/img/rand.png)",
                        backgroundPosition: "0 0",
                        backgroundRepeat: "no-repeat"
                    });
                }
            }
        });
            
        let 
            timeDay = getTimeDay(),
            objChildren = getTreeNodeTypes();
        
        if (objChildren === false) {
            resetTimeDuration();
            return false;
        } 
        
        $.ajax({
            type : 'POST',
            url: '{$urlAjaxCheckPlaylist}',
            data: {
                all_time: timeDay,
                pls_commerce: getFormVal('pls_com_id'),
                pls_standart: getFormVal('pls_std_id'),
                arr_commerce: objChildren.arrCommercialChildren,
                arr_standart: objChildren.arrStandartChildren
            },
            success: function (res)
            {
                let 
                    color,
                    total,
                    error = '',
                    std_time = 0,
                    com_time = 0,
                    stdStr = '00:00:00',
                    comStr = '00:00:00',
                    totalStr = '00:00:00';
                
                if (res !== 'null' && res.state !== undefined) {
                    if (res.state === 1) {
                        color = 'green';
                        if (res.std_time !== null) std_time = res.std_time;
                        if (res.com_time !== null) com_time = res.com_time;
                        if (std_time > 0) {
                            stdStr = moment.unix(std_time).utc().format("HH:mm:ss");
                        } 
                        if (com_time > 0) {
                            comStr = moment.unix(com_time).utc().format("HH:mm:ss");
                        } 
                        total = res.std_time + res.com_time;                            
                        if (total > 0) {
                            totalStr = moment.unix(total).utc().format("HH:mm:ss");
                        }                        
                    } else {
                        color = 'red';
                        error = res.message;
                    }
                }
                
                $('#duration-std').html(stdStr).css({
                    color : color
                });
                $('#duration-com').html(comStr).css({
                    color : color
                });
                $('#duration-summ').html(totalStr).css({
                    color : color
                });
            
                $('#error_span').html(error).css({
                    color : color
                });
                
                return color !== 'red';            
            }
        }); 
    }
    
    /**
    * 
    * @returns {string}
    */
    function resetTimeDuration() 
    {
        $('#duration-std').html('00:00:00').css({
            color : 'green'
        });
        $('#duration-com').html('00:00:00').css({
            color : 'green'
        });
        $('#duration-summ').html('00:00:00').css({
            color : 'green'
        });
    
        $('#error_span').html('');
    }
    
    /**
    * 
    * @param unix_timestamp
    * @returns {string}
    */
    function stampToString(unix_timestamp) 
    {
        let date = new Date(Date.UTC(1970, 1, 1, -3, 0, unix_timestamp));
        let hours = date.getHours();
        let minutes = "0" + date.getMinutes();
        let seconds = "0" + date.getSeconds();
        return hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    }
    
    /**
    * 
    * @returns {*}
    */
    function getSum() 
    {
        let total = 0;
        
        const treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            parent = tree.getNodeByKey("playlist");
        
        if (parent === undefined 
            || parent.getChildren() === undefined) 
            return false;
        
        $.each(parent.getChildren(), function() 
        { 
            if (this.data.total !== undefined) {
                total += parseInt(this.data.total, 10);                
            } 
        });
        if (total > 0) return total;
        return false;
    }   
    
    /**
    * 
    * @param node
    * @returns {boolean}
    */
    function updateTotal(node) {
        if (node.data.views === undefined || node.data.views === 0) {
            return false;
        }
        if (node.data.duration === undefined || node.data.duration === 0) {
            return false;
        }
        node.data.total = node.data.views * node.data.duration;
        return true;
    }

    /**
    * @param region
    */
    function setSender(region) 
    {
        const senderSelect = $('.sender_id select');
        const senderDisable = senderSelect.prop('disabled'); 
        senderSelect.attr('disabled', true); 
        
        $(".sender_id select option").each(function() {
            $(this).remove();
        }); 
        senderSelect.append("<option value=''>---</option>");
        
        $.ajax({
            url: '{$urlAjaxSender}',
            data: {region: region},
            success: function (res) {
                let optionsAsString = "";
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    const results = res.results; 
                    for (let i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += results[i].id == '{$model->sender_id}' ? 'selected' : '';
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                senderSelect.append( optionsAsString );
            }
        });
        senderSelect.attr('disabled', senderDisable);
    }
    
    /**
    * 
    * @param region
    * @param sender
    */
    function setDevice(region = null, sender = null) 
    {
        const deviceSelect = $('.device_id select');
        const deviceDisable = deviceSelect.prop('disabled');
        deviceSelect.attr('disabled', true); 
        
        $(".device_id select option").each(function() {
            $(this).remove();
        }); 
        deviceSelect.append("<option value=''>---</option>");                

        $.ajax({
            url: '{$urlAjaxDevice}',
            data: {
                region: region,
                sender: sender
            },
            success: function (res) {
                let optionsAsString = "";
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    const results = res.results; 
                    for (let i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += results[i].id == '{$model->device_id}' ? 'selected' : '';
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                deviceSelect.append(optionsAsString);
            }
        });
        deviceSelect.attr('disabled', deviceDisable);
    }
    
    /**
    * 
    */
    function disableTree() 
    {
        const emptyList = [{ 
            title : 'уточните параметры для отображения', 
            folder : false 
        }];
        
        const regionObject = $("#fancyree_template_region");
        const regionTree = regionObject.fancytree("getTree");        
        
        const commercialObject = $("#fancyree_template_commercial");
        const commercialTree = commercialObject.fancytree("getTree");
        
        regionTree.reload(emptyList);
        regionObject.fancytree("disable");        
        
        commercialTree.reload(emptyList);
        commercialObject.fancytree("disable");
        
        const outObject = $("#treetable");
        const outTree = outObject.fancytree("getTree");
        outTree.reload(newPlayList);      
    }
    
    /**
    * 
    * @param region
    * @param sender
    * @param group
    * @param device
    */
    function setTreeData (
        region = null, 
        sender = null, 
        group = null, 
        device = null) 
    {
        const regionObject = $("#fancyree_template_region");
        const regionTree = regionObject.fancytree("getTree");
        
        const commercialObject = $("#fancyree_template_commercial");
        const commercialTree = commercialObject.fancytree("getTree");
        
        const parentFolder = $("#treetable")
            .fancytree("getTree")
            .getNodeByKey("playlist");

        $.ajax({
            url: '{$urlAjaxPlaylistTemplate}',
            data: {
                region: region,
                sender_id: sender,
                pls_out_id: {$pls_id},
                group_id: group,
                device_id: device
            },
            success: function (res) {
                if (res !== 'null') {
                    const pls_id = [];
                    if (res.result[1] !== undefined) {
                        setFormVal("pls_std_id", res.result[1]['pls']);
                        regionObject.fancytree("enable");
                        regionTree.reload(res.result[1]['inf']);
                    }  
                    if (res.result[2] !== undefined) {
                        setFormVal("pls_com_id", res.result[2]['pls']);
                        commercialObject.fancytree("enable");
                        commercialTree.reload(res.result[2]['inf']);
                    }                        
                }
            }
        });
    }
    
    /**
    * 
    * @param name
    * @param val
    */
    function setFormVal(name, val) 
    {
        if ($("input").is("#" + name)) {
            $("#" + name).remove();
        }
        $("<input>").attr({
            type: "hidden",
            id: name,
            name: name,
            value: val
        }).appendTo("form");
    }
    
    /**
    * 
    * @param name
    * @returns {*|jQuery}
    */
    function getFormVal(name) 
    {
        return $('#' + name).val();
    }
    
    /**
    * 
    * @returns {*}
    */
    function getTreeNodeTypes() 
    {
         let 
            arrOutPls = {},
            returnObj = {},
            arrChildrenOnePls = [],
            arrStandartChildren = [],
            arrCommercialChildren = [];
         
         const treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            d = tree.toDict(true);
            
        if (d.children[0].children === null) 
            return false;
        
        let 
            playListKey = d.children[0].key,
            rootTitle = d.children[0].title;
        
        d.children[0].children.forEach(function(children) 
        {
            let arrChildren = {}, 
                arrChildrenPls = {},
                key = children.key,
                type = children.data.type,
                duration = children.data.duration,
                views = children.data.views,
                total = children.data.total;
            
            if (views === undefined || views < 0) 
                views = 0;
            
            if (duration === undefined || duration < 0) 
                duration = 0;
            
            if (total === undefined || total < 0) 
                total = 0;
            
            if (type === '2' && (
                views === 0 || 
                duration === 0 || 
                total === 0)
            ) return true;
            
            arrChildrenPls.title = children.title;
            arrChildrenPls.key = children.key;
            arrChildrenPls.data = {};
            arrChildrenPls.data.type = children.data.type;
            arrChildrenPls.data.file = children.data.file;
            arrChildrenPls.data.views = children.data.views;
            arrChildrenPls.data.duration = children.data.duration;
            arrChildrenPls.data.frame_rate = children.data.frame_rate;
            arrChildrenPls.data.nb_frames = children.data.nb_frames;
            arrChildrenPls.data.total = children.data.total;
            arrChildrenOnePls.push(arrChildrenPls); 

            arrChildren.key = key; 
            arrChildren.type = type;
            arrChildren.views = views;
            arrChildren.duration = duration;
            
            if (type === '1') {
                arrStandartChildren.push(arrChildren); 
            } else if (type === '2') {
                arrCommercialChildren.push(arrChildren); 
            }
        });
        
        arrOutPls["key"] = playListKey;
        arrOutPls["title"] = rootTitle;
        arrOutPls["folder"] = true;
        arrOutPls["expanded"] = true; 
        arrOutPls["children"] = arrChildrenOnePls;

        returnObj.arrStandartChildren = arrStandartChildren;
        returnObj.arrCommercialChildren = arrCommercialChildren;
        returnObj.arrOutPls = arrOutPls;
        return returnObj; 
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function addJSON() 
    {
        let 
            htm_header = 'Ошибка сохранения плейлиста';
        
        const treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            parentFolder = tree.getNodeByKey("playlist");

        let objChildren = getTreeNodeTypes();
        
        if (objChildren === false) 
            return false;
        
        let 
            timeDay = getTimeDay(), 
            arrOutPls = objChildren.arrOutPls,
            rootTitle = arrOutPls.title,
            playListKey = arrOutPls.key,
            jsonStrPls = JSON.stringify(arrOutPls);        

        if (inputVar.is("#gmsplaylistout-jsonplaylist")) {
            $("#gmsplaylistout-jsonplaylist").remove();
        }
        
        if (inputVar.is("#gmsplaylistout-jsonKodi")) {
            $("#gmsplaylistout-jsonKodi").remove();
        }  
        
        if (inputVar.is("#gmsplaylistout-name")) {
            $("#gmsplaylistout-name").remove();
        }

        $("<input>").attr({
            type: "hidden",
            id: "gmsplaylistout-name",
            name: "GmsPlaylistOut[name]",
            value: rootTitle
        }).appendTo("form");
        
        $("<input>").attr({
            type: "hidden",
            id: "gmsplaylistout-jsonplaylist",
            name: "GmsPlaylistOut[jsonPlaylist]",
            value: jsonStrPls
        }).appendTo("form");

        $.ajax({
            type : 'POST',
            url: '{$urlAjaxCheckPlaylist}',
            data: {
                all_time: timeDay,
                pls_commerce: getFormVal('pls_com_id'),
                pls_standart: getFormVal('pls_std_id'),
                arr_commerce: objChildren.arrCommercialChildren,
                arr_standart: objChildren.arrStandartChildren
            },
            success: function (res) 
            {
                let arrOut = {};
                if (res !== 'null') {
                    if (res.state === 0) {
                        const html_body = res.message; 
                        $('#bootstrap-dialog-title').html(htm_header);  
                        $('#bootstrap-dialog-message').html(html_body);  
                        $('#modal-dialog').modal('show');
                        return false;
                    } else {
                        arrOut["key"] = playListKey;
                        arrOut["title"] = rootTitle;
                        arrOut["folder"] = true;
                        arrOut["expanded"] = false;
                        arrOut["children"] = res.info;
                        const jsonStr = JSON.stringify(arrOut);

                        $("<input>").attr({
                            type: "hidden",
                            id: "gmsplaylistout-jsonkodi",
                            name: "GmsPlaylistOut[jsonKodi]",
                            value: jsonStr
                        }).appendTo("form");
                        
                        $("#form").submit();
                    }
                }
            }
        });
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function checkJSON () 
    {
        let html_body = '';
        
        const htm_header = 'Ошибка сохранения плейлиста',
            treeObject = $("#treetable"), 
            tree = treeObject.fancytree("getTree"),
            parentFolder = tree.getNodeByKey("playlist"),
            timeDay = getTimeDay(),
            timeSum = getSum(),       
            strTimeDay = moment.unix(timeDay).utc().format("HH:mm:ss"),
            strTimeSum = moment.unix(timeSum).utc().format("HH:mm:ss");
        
       
        if (parentFolder === null || parentFolder.children === null) {
            html_body = 'Необходимо добавить хотя бы одно видео в окончательный плейлист'; 
        }

        if (timeDay === false) {
            html_body = 'Некорректно указано время дневного эфира'; 
        } 

        if (timeSum === false) {
            html_body = 'Некорректная итоговая продолжительность роликов';
        }
        
        if (timeSum > timeDay) {
            html_body = 'Превышен лимит в ' + strTimeSum + ' за указанное время ' + strTimeDay;
        }        

        if (timeDay === false ||
             timeSum === false || 
             timeSum > timeDay || 
             parentFolder === null || 
             parentFolder.children === null
         ) {
            $('#bootstrap-dialog-title').html(htm_header);  
            $('#bootstrap-dialog-message').html(html_body);  
            $('#modal-dialog').modal('show');
            return false;
        }
        
        addJSON(); 
    }
    
    /**
    * 
    */
    function checkTime() 
    {
        const m_data = $('#form').serialize();
        let html_body = '';

        const htm_header = 'Ошибка сохранения плейлиста',
            activeTab = $('.nav-tabs .active a')[0].hash;
            
        if (activeTab === '#tab_1' && regionSelectConst.val() === '') {
            html_body += 'Не указано обязательное поле - <b>"Регион прогрывания"</b>'; 
        }
        if (activeTab === '#tab_2' && groupSelectConst.val() === '') {
            html_body += 'Не указано обязательное поле - <b>"Группа устройств"</b>'; 
        }
        if (activeTab === '#tab_3' && deviceSelectConst.val() === '') {
            html_body += 'Не указано обязательное поле - <b>"Устройство"</b>'; 
        }
        if (html_body !== '') {
            $('#bootstrap-dialog-title').html(htm_header);  
            $('#bootstrap-dialog-message').html(html_body);  
            $('#modal-dialog').modal('show');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '{$urlAjaxTime}',
            data: m_data,
            success: function (res){
                let html_body = '';
                const htm_header = 'Ошибка добавления, временное пересечение с другим плейлистом!';
                if (res !== 'null') {
                    if (res.cross !== undefined && res.id !== undefined && res.name !== undefined) {
                        let html = "";
                        if (res.cross === 'region') {
                            html  = 'Регион: <b>' + $('.region select option:selected').text() + '</b>'; 
                            if (res.region !== undefined) {
                                html += '<br>Отделение: <b>' + res.region + '</b>'; 
                            } 
                        } else if (res.cross === 'group') {
                            if (res.group !== undefined) {
                                html += 'Группа устройств: <b>' + res.group + '</b>'; 
                            }   
                        } else if (res.cross === 'device') {
                            if (res.device !== undefined) {
                                html += 'Устройство: <b>' + res.device + '</b>'; 
                            }                              
                        }
                        html_body += 'Для параметров: <p style="margin-left:30px;">' + html + '</span>';
                        html_body += '<p>Уже есть привязанный плейлист: ';
                        html_body += '<b><a target="_blank" href="/GMS/playlist-out/view?id=' + res.id + '">' + res.name + '</a></b>';
                        html_body += '</p>';
                    }       

                    if (res.date !== undefined) {
                        html_body += 'Действует с: <b>' + res.date.start + ' г.</b> по <b>' + res.date.end + ' г.</b>';
                    }
                    if (res.time !== undefined) {
                        html_body += '<br>Время проигрывания с: <b>' + res.time.start + '</b> по <b>' + res.time.end + '</b>';
                    }
                    if (res.week !== undefined) {
                        html_body += '<br>Пересечение по дням: <b>' + res.week + '</b>';
                    }
                    html_body += '<p>Измените параметры и попробуйте ещё!</p>';

                    $('#bootstrap-dialog-title').html(htm_header);  
                    $('#bootstrap-dialog-message').html(html_body);  
                    $('#modal-dialog').modal('show');
                    return false;
                } else {
                    checkJSON();
                }
            }
        });
    }
    
    function setDayTime() {
        let timeDuration;
        if (timeDuration = getTimeDay()) {
            timeDuration = 'Продолжительность рабочего дня: ' + moment.unix(timeDuration).utc().format("HH:mm:ss");
        }
        $('#day-summ').html(timeDuration);        
    }
JS;
$this->registerJs($js1);
?>
