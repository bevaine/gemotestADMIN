<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Doctors;
use kartik\select2;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\form\ActiveForm;
use yii\web\JsExpression;
use wbraganca\fancytree\FancytreeWidget;
use mihaildev\ckeditor\Assets;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */
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
        background-color: #00a65a;
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
        src: url(../../fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2');
    }
    .material-icons {
        font-family: 'Material Icons';
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
CSS;
$this->registerCss($css);

?>
<style type="text/css">

</style>

<div class="gms-playlist-out-form">

    <?php $form = ActiveForm::begin(['id' => 'form']); ?>

    <?= Html::hiddenInput('GmsPlaylistOut[id]', $model->id) ?>

    <div class="modal fade" id="check-playlist" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="modal-body">
                    <div class="box box-solid box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="box-title"></h3>
                        </div>
                        <div class="box-body" id="box-body"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
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
                                                date("H:i", $model->time_start),
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
                                                date("H:i", $model->time_end),
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
                                        echo "<span style='padding-left: 10px'>".Html::Activecheckbox($model, $key, [
                                                'value' => "1",
                                                'label' => $value,
                                                'data-url' => 'is_monday'
                                            ])."</span>";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Выберите регион (отделение) для отображения доступных шаблонов</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group region">
                                <?= $form->field($model, 'region_id')->dropDownList(\common\models\GmsRegions::getRegionList(), ['prompt' => '---']); ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group sender_id">
                                <?= $form->field($model, 'sender_id')->dropDownList([], ['prompt' => '---']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Стандартный шаблон плейлиста</h3>
                                    </div>
                                    <div class="box-body">
                                        <?php
                                        echo FancytreeWidget::widget([
                                            'id' => 'template_region',
                                            'options' =>[
                                                'disabled' => true,
                                                'source' => [
                                                    [
                                                        'title' => 'уточните параметры для отображения',
                                                        'folder' => false,
                                                    ]
                                                ],
                                                'extensions' => ['dnd'],
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
                                        ?>
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
                                        <?php
                                        echo FancytreeWidget::widget([
                                            'id' => 'template_commercial',
                                            'options' =>[
                                                'disabled' => true,
                                                'source' => [
                                                    [
                                                        'title' => 'уточните параметры для отображения',
                                                        'folder' => false,
                                                    ]
                                                ],
                                                'extensions' => ['dnd'],
                                                'dnd' => [
                                                    'preventVoidMoves' => true,
                                                    'preventRecursiveMoves' => true,
                                                    'autoExpandMS' => 400,
                                                    'dragStart' => new JsExpression('function(node, data) {
                                                        if (!data.tree.options.disabled) {
                                                            //return true;
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
                                        ?>
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
                                                    <th style="font-size: smaller" colspan="5">Итого</th>
                                                    <th colspan="6"><div class="duration-summ" id="duration-summ"></div></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Привязка устройства</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group device_id">
                                <?= $form->field($model, 'device_id')->dropDownList([], ['prompt' => '---']);
                                ?>
                            </div>
                        </div>
                    </div>
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
                        <div class="video-info" id="video-info">
                            Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <?php
//    edofre\fullcalendar\Fullcalendar::widget([
//        'options'       => [
//            'id'       => 'calendar',
//            'language' => 'ru',
//        ],
//        'clientOptions' => [
//            'weekNumbers' => true,
//            'selectable'  => true,
//            'defaultView' => 'agendaWeek',
//            'eventResize' => new JsExpression("
//                function(event, delta, revertFunc, jsEvent, ui, view) {
//                    console.log(event);
//                }
//            "),
//
//        ],
//        'events' => Url::to(['calendar/events', 'id' => 1]),
//    ]);
    ?>

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

$source = [];
$playListKey = 1;
$playListKeyStr = 'playList['.$playListKey.']';

if ($model->isNewRecord) {
    $source =  [
        [
            'title' => 'Новый плейлист',
            'key' => $playListKeyStr,
            'folder' => true,
            'expanded' => true,
            'icon' => '../../img/video1.png'
        ]
    ];
    $source = json_encode($source);
} else {
    if (!empty($model->jsonPlaylist)) {
        $source = new JsExpression('['.$model->jsonPlaylist.']');
    }
}

$js1 = <<< JS

    const newPlayList = [
        {
            "title" : "Новый плейлист", 
            "key" : "PlayList[1]", 
            "folder" : true, 
            "expanded" : true, 
            "icon" : "../../img/video1.png"
        }
    ];

    function resetPlayer(htm_table = null) {
        const myPlayer = videojs('my-player');
        if (htm_table === null) {
            htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
        }
        myPlayer.reset();
        myPlayer.poster("../../img/logo.jpg");
        myPlayer.width("783");
        
        $('#video-info')
            .addClass('video-info-normal')
            .html(htm_table);        
    }
  
    $(function()
    {
        const tree = $("#treetable");
        
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
                const node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
            
                tdList.eq(1).addClass('dblclick');
                
                if (node.data.duration !== undefined) {
                    time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
                    //tdList.eq(2).text(time);
                }

                if (node.data.type !== undefined) 
                {
                    let icon = '';
                    let html = '';
                    let typePlaylist;
                    
                    if (node.data.type === '1') {
                        icon = 'gemotest.jpg';
                        typePlaylist = 'Стандартный';
                        tdList.eq(2).text(typePlaylist);
                        
                        if (time !== 0) {
                            node.data.total = node.data.duration;
                            tdList.eq(3).text(time); 
                            tdList.eq(5).text(time);
                        } 
                    } else if (node.data.type === '2') {
                        icon = 'dollar.png';
                        typePlaylist = 'Коммерческий';
                        tdList.eq(2).text(typePlaylist);
                        
                        html = '<input style="width:50px" type="number" id="count_view[' + node.key + ']" min="0" step="1"/>';
                        tdList.eq(4).html(html).addClass('alignCenter');
                        tdList.eq(4).find("input").change(function(){
                            node.data.views = $(this).val();
                            if (updateTotal(node)) {
                                const time_views = moment.unix(node.data.total).utc().format("HH:mm:ss");
                                tdList.eq(5).text(time_views);  
                            }
                            sumDuration(node.parent);
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
                                    sumDuration(node.parent);
                                }
                            });                            
                        }
                    }
                    if (icon !== '') {
                        const span = $(node.span);
                        span.find("> span.fancytree-icon").css({
                            backgroundImage: "url(../../img/" + icon + ")",
                            backgroundPosition: "0 0"
                        });
                    }
                }

                if (!node.isFolder()) {
                    tdList.eq(6).html('<span id="trash-node" style="cursor:pointer;" class="glyphicon glyphicon-trash"></span>');
                }
                sumDuration(node.parent);
            },
            edit: {
                triggerStart: ["clickActive"],
                beforeEdit : function(event, data){
                    return !!(data.node.isFolder() && data.node.key !== 'ComFolder'); 
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
                        const playlistNode = data.tree.getNodeByKey('PlayList[1]');                    

                        if (data.otherNode.data.type === '2') {
                            let folderNode = data.tree.getNodeByKey('ComFolder');  
                            if (folderNode === null) {
                                const newFolderNode = [
                                    {
                                        "title" : "Коммерческие ролики", 
                                        "key" : "ComFolder", 
                                        "folder" : true, 
                                        "expanded" : true, 
                                        "icon" : "../../img/dollar.png"
                                    }
                                ];
                                playlistNode.addNode(newFolderNode, 'child');
                            }
                            folderNode = data.tree.getNodeByKey('ComFolder');
                            data.otherNode.moveTo(folderNode, "child");
                            return;
                        }   
                        
                        if (!sameTree) {
                            if (data.otherNode.isFolder()) {
                                playlistNode.addNode(data.otherNode.children, 'child');                           
                            } else {
                                const addChild = [];
                                addChild.push(data.otherNode);
                                playlistNode.addNode(addChild, 'child');
                            }
                            
                        } else {
                            data.otherNode.moveTo(node, data.hitMode); 
                            if (!data.otherNode.isChildOf(playlistNode)) {
                                data.otherNode.moveTo(playlistNode, "child");
                            }
                            data.otherNode.render(true);
                        }
                        playlistNode.setExpanded();
                        sumDuration(node.parent);
                    } else if (data.otherNodeData) {
                        node.addChild(data.otherNodeData, data.hitMode);
                    } else {
                        node.addNode({
                          title: transfer.getData("text")
                        }, data.hitMode);
                    }
                    node.setExpanded();
                }
            }
        });
        
        tree.delegate("span[id=trash-node]", "click", function(e)
        {
            const node = $.ui.fancytree.getNode(e),           
                tdList = $(node.tr),
                parent = node.parent;

            if (parent.key === 'ComFolder') {
                const commercialObject = $("#fancyree_template_commercial"), 
                    commercialTree = commercialObject.fancytree("getTree"), 
                    folderNode = commercialTree.getNodeByKey("playList[1]");
                
                node.moveTo(folderNode, "child");
                folderNode.setExpanded();
                tdList.remove();
                if (parent.countChildren() === 0) {
                    parent.remove();
                    parent.render(true); 
                }
            } else {
                node.remove();
            }
            node.render(true); 
            e.stopPropagation();  
            sumDuration(parent);
            resetPlayer();
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
    });
    
    function updateTotal(node) {
        if (node.data.views === undefined || node.data.views === 0) {
            return false;
        }
        if (node.data.duration === undefined || node.data.duration === 0) {
            return false;
        }
        node.data.total = node.data.views * node.data.duration;
    }
    
    /**
    * 
    * @param parentFolder
    */
    function addJSON (parentFolder) 
    {
        const arrOut = {};
        const arrChildrenOne = [];
        const playListKey = parentFolder.key;
        const rootTitle = parentFolder.title;
        const inputVar = $("input");
        
        if (inputVar.is("#gmsplaylistout-jsonplaylist")) {
            $("#gmsplaylistout-jsonplaylist").remove();
        }                    
        
        if (inputVar.is("#gmsplaylistout-name")) {
            $("#gmsplaylistout-name").remove();
        }

        arrOut["key"] = playListKey;
        arrOut["title"] = rootTitle;
        arrOut["folder"] = "true";
        arrOut["expanded"] = "true";

        $("<input>").attr({
            type: "hidden",
            id: "gmsplaylistout-name",
            name: "GmsPlaylistOut[name]",
            value: rootTitle
        }).appendTo("form");
        
        if (parentFolder.children !== null) {
            parentFolder.children.forEach(function(children) {
                //console.log(children);
                const arrChildren = {};
                const arrData = {};
                const key = children.key;
                const name = children.title;
                arrData["file"] = children.data.file;
                arrData["duration"] = children.data.duration;
                arrData["type"] = children.data.type;
                arrChildren["key"] = key; 
                arrChildren["title"] = name;
                arrChildren["data"] = arrData;
                arrChildrenOne.push(arrChildren); 
            });

            arrOut["children"] = arrChildrenOne;
            const jsonStr = JSON.stringify(arrOut);

            $("<input>").attr({
                type: "hidden",
                id: "gmsplaylistout-jsonplaylist",
                name: "GmsPlaylistOut[jsonPlaylist]",
                value: jsonStr
            }).appendTo("form");
        }
    }

    /**
    * @param parent
    */
    function sumDuration (parent) 
    {
        let total = 0;
        let totalStr = '';
        if (parent.getChildren() === undefined) return;
        $.each(parent.getChildren(), function() {
            let views = 1;
            if (this.data.duration === undefined) return;            
            if (this.data.views !== undefined 
                && this.data.views !== 0) {
                views = this.data.views;
            }
            total += parseInt(this.data.duration, 10) * views;
        });
        if (total > 0) {
            totalStr = moment.unix(total).utc().format("HH:mm:ss");
        }
        $('#duration-summ').html(totalStr);
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
    
    function setTreeData (region = null, sender = null) 
    {
        const regionObject = $("#fancyree_template_region");
        const regionTree = regionObject.fancytree("getTree");
        
        const commercialObject = $("#fancyree_template_commercial");
        const commercialTree = commercialObject.fancytree("getTree");
        
        $.ajax({
            url: '{$urlAjaxPlaylistTemplate}',
            data: {
                region: region,
                sender_id: sender
            },
            success: function (res) {
                if (res !== null) {
                    if (res.result[1] !== undefined) {
                        regionObject.fancytree("enable");
                        regionTree.reload(res.result[1]);
                    }                  
                    if (res.result[2] !== undefined) {
                        commercialObject.fancytree("enable");
                        commercialTree.reload(res.result[2]);
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
        const htm_header = 'Ошибка сохранения плейлиста';
        const parentFolder = 
            $("#treetable")
            .fancytree("getTree")
            .rootNode.children[0];
        
        if (parentFolder !== null && parentFolder.children !== null) {
            addJSON(parentFolder);
            return true;
        } else {
            html_body = 'Необходимо добавить хотя бы одно видео в окончательный плейлист'; 
            $('#modal-header').html(htm_header);
            $('#modal-body').html(html_body);
            $('#check-playlist').modal('show');
            return false;
        }
    }
    
    function checkTime () {
        const m_data = $('#form').serialize();
        $.ajax({
            type: 'GET',
            url: '{$urlAjaxTime}',
            data: m_data,
            success: function (res){
                let html_body = '';
                const htm_header = 'Ошибка добавления, временное пересечение с другим плейлистом!';
                if (res !== null) {
                    if (res.id !== undefined && res.name !== undefined) {
                        let html  = 'Регион: <b>' + $('.region select option:selected').text() + '</b>'; 
                        if ($('.sender_id select option:selected').text() !== '---') {
                            html += '<br>Отделение: <b>' + $('.sender_id select option:selected').text() + '</b>'; 
                        } 
                        if ($('.device_id select option:selected').text() !== '---') {
                            html += '<br>Устройство: <b>' +  + $('.device_id select option:selected').text() + '</b>'; 
                        } 
                        //html += 'Действует с' + res.date_start + ' по '.res.date_end;
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

                    $('#box-title').html(htm_header);
                    $('#box-body').html(html_body);
                    $('#check-playlist').modal('show');
                } else {
                    if (checkJSON()) $("#form").submit();
                }
            }
        });
    }
    
    $(".btn-primary, .btn-success").click(function() { 
        checkTime();
    });
    
    $(".region select").change(function() {
        disableTree();
        setSender ($(this).val());
        setDevice ($(this).val(), $('#gmsplaylistout-sender_id').val());
        setTreeData ($(this).val(), $('#gmsplaylistout-sender_id').val());
    });
    
    $(".sender_id select").change(function() {
        disableTree();
        setDevice ($('#gmsplaylistout-region_id').val(), $(this).val());
        setTreeData ($('#gmsplaylistout-region_id').val(), $(this).val());
    });
    
    $(document).ready(function()
    {  
        $('#gmsplaylistout-time_end').bootstrapMaterialDatePicker({ 
            date: false, shortTime: false, format: 'HH:mm', lang : 'ru'
        });
        
        $('#gmsplaylistout-time_start').bootstrapMaterialDatePicker({
            date: false, shortTime: false, format: 'HH:mm', lang : 'ru'
        }).on('change', function(e, date) {
            $('#gmsplaylistout-time_end').bootstrapMaterialDatePicker('setMinDate', date);
        });

        //$.material.init();
			
        setSender ($('#gmsplaylistout-region_id').val());
        setTimeout(function(){
            setDevice ($('#gmsplaylistout-region_id').val()
                , $('#gmsplaylistout-sender_id').val());
        }, 1000);
        setTimeout(function(){
            setTreeData ($('#gmsplaylistout-region_id').val()
                ,$('#gmsplaylistout-sender_id').val());
        }, 1000);        
    }); 
JS;
$this->registerJs($js1);
?>
