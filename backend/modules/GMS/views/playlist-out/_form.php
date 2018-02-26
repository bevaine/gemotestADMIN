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

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */
/* @var $form yii\widgets\ActiveForm */

$data = [
    ['title' => 'Node 1', 'key' => 1],
    ['title' => 'Folder 2', 'key' => '2', 'folder' => true, 'children' => [
        ['title' => 'Node 2.1', 'key' => '3'],
        ['title' => 'Node 2.2', 'key' => '4']
    ]]
];

$layout = <<< HTML
    {input1}
    {separator}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$tableTree = <<< HTML

HTML;

$week = [
    "isMonday" => "Понедельник",
    "isTuesday" => "Вторник",
    "isWednesday" => "Среда",
    "isThursday" => "Четверг",
    "isFriday" => "Пятница",
    "isSaturday" => "Суббота",
    "isSunday" => "Воскресенье"
];

$this->registerCss("td.alignRight { text-align: right }; td:hover.reg { background : #20b426 }; td.com { color : #df8505 }; ");
?>
<style type="text/css">
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
</style>

<div class="gms-playlist-out-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Выберите регион (отделение) для отображения доступных шаблонов</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group region">
                                <?= $form->field($model, 'region_id')->dropDownList(\common\models\GmsRegions::getRegionList(), [
                                    'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group sender_id">
                                <?= $form->field($model, 'sender_id')->dropDownList([], [
                                    'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
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
                                                            return true;
                                                            //return !node.isFolder();
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
                                                <col width="470px">
                                                <col width="100px">
                                                <col width="150px">
                                                <col width="30px">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Плейлист</th>
                                                <th>Продолжит.</th>
                                                <th>Тип ролика</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;"></td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th style="font-size: smaller" colspan="2">Итого</th>
                                                    <th colspan="3"><div class="duration-summ" id="duration-summ"></div></th>
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

            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Привязка плейлиста и параметры воспроизведения</h3>
                </div>
                <div class="box-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group device_id">
                                <?= $form->field($model, 'device_id')->dropDownList([], [
                                    'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group date">
                                <?= Html::label('Период воспроизведения') ?>
                                <?= DatePicker::widget([
                                    'type' => DatePicker::TYPE_RANGE,
                                    'name' => 'dateStart',
                                    'value' => date('d-m-Y', time()),
                                    'name2' => 'dateEnd',
                                    'value2' => date('d-m-Y', time()),
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

                        <div class="col-lg-6">
                            <div class="form-group date">
                                <?= Html::label('Время воспроизведения') ?>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <?= TimePicker::widget([
                                            'model' => $model,
                                            'attribute' => 'timeStart',
                                            'name' => 'timeStart',
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'secondStep' => 5,
                                            ]
                                        ]);
                                        ?>
                                    </div>

                                    <div class="col-lg-2" align="center">
                                        <i class="glyphicon glyphicon-resize-horizontal"></i>
                                    </div>

                                    <div class="col-lg-5">
                                        <?= TimePicker::widget([
                                            'model' => $model,
                                            'attribute' => 'timeEnd',
                                            'name' => 'timeEnd',
                                            'pluginOptions' => [
                                                'showSeconds' => false,
                                                'showMeridian' => false,
                                                'minuteStep' => 1,
                                                'secondStep' => 5,
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group week">
                                <?= Html::label('Воспроизводить только в') ?>
                                <?= Html::checkboxList('week', null, $week, ['inline'=>false]) ?>
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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);
$urlAjaxPlaylistTemplate = \yii\helpers\Url::to(['/GMS/playlist/ajax-playlist-template']);

$js1 = <<< JS

    var newPlayList = [
        {
            "title" : "Новый плейлист", 
            "key" : "PlayList[1]", 
            "folder" : true, 
            "expanded" : true, 
            "icon" : "../../img/video1.png"
        }
    ];

  
    $(function()
    {
        var tree = $("#treetable");
        
        tree.fancytree({
            extensions: ["table", "dnd", "edit"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },
            source: newPlayList,
            dblclick: function(event, data) {
                var videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        var htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
                        res = JSON.parse(res);
                        if (res.results.file !== undefined) {
                            var videoPath = res.results.file; 
                            var myPlayer = videojs('my-player');
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
                
                if (node.data.type !== undefined) {
                    var icon = '';
                    var typePlaylist = '';
                    if (node.data.type === '1') {
                        icon = 'gemotest.jpg';
                        typePlaylist = 'Стандартный'; 
                    } else if (node.data.type === '2') {
                        icon = 'dollar.png';
                        typePlaylist = 'Коммерческий';
                    }                        
                    if (icon !== '') {
                        var span = $(node.span);
                        span.find("> span.fancytree-icon").css({
                            backgroundImage: "url(../../img/" + icon + ")",
                            backgroundPosition: "0 0"
                        });
                    }
                    if (typePlaylist !== '') {
                        tdList.eq(3).text(typePlaylist);
                    }
                }
                
                if (!node.isFolder()) {
                    tdList.eq(4).html('<span id="trash-node" style="cursor:pointer;" class="glyphicon glyphicon-trash"></span>');
                }
            },
            edit: {
                triggerStart: ["clickActive", "dblclick"],
                beforeEdit : function(event, data){
                    return data.node.isFolder()
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
                dragDrop : function(node, data) {
                    if (data.otherNode) {
                        var sameTree = (data.otherNode.tree === data.tree);
                        var playlistNode = data.tree.getNodeByKey('PlayList[1]');
                        if (!sameTree) {
                            if (data.otherNode.isFolder()) {
                                playlistNode.addNode(data.otherNode.children, 'child');                           
                            } else {
                                var addChild = [];
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
                    } else if (data.otherNodeData) {
                        node.addChild(data.otherNodeData, data.hitMode);
                    } else {
                        node.addNode({
                          title: transfer.getData("text")
                        }, data.hitMode);
                    }
                    node.setExpanded();
                    sumDuration(playlistNode);                
                }
            }
        });
        
        tree.delegate("span[id=trash-node]", "click", function(e){
            var node = $.ui.fancytree.getNode(e);
            var parent = node.parent;
            e.stopPropagation(); 
            node.remove();
            node.render(true);
            sumDuration(parent);
        });
    });
    
    /**
    * @param parent
    */
    function sumDuration (parent) {
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
        $('#duration-summ').html(totalStr);
    }
    
    /**
    * @param region
    */
    function setSender(region) {
        var senderSelect = $('.sender_id select');
        var senderDisable = senderSelect.prop('disabled');        
        senderSelect.attr('disabled', true);
        $.ajax({
            url: '{$urlAjaxSender}',
            data: {region: region},
            success: function (res) {
                res = JSON.parse(res);
                var optionsAsString = "<option value=''>---</option>";
                if (res.results !== undefined && res.results.length > 0) {
                    var results = res.results; 
                    for (var i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += results[i].id == '{$model->sender_id}' ? 'selected' : '';
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                $(".sender_id select option").each(function() {
                    $(this).remove();
                });
                senderSelect.append( optionsAsString );
                senderSelect.attr('disabled', senderDisable);
            }
        });
    }
    
    function setTreeData (region = null, sender = null) {
        var emptyList = [{ 
            title : 'уточните параметры для отображения', 
            folder : false 
        }];
        
        var regionObject = $("#fancyree_template_region");
        var regionTree = regionObject.fancytree("getTree");
        regionTree.reload(emptyList);
        regionObject.fancytree("disable");        
        
        var commercialObject = $("#fancyree_template_commercial");
        var commercialTree = commercialObject.fancytree("getTree");
        commercialTree.reload(emptyList);
        commercialObject.fancytree("disable");
        
        var outObject = $("#treetable");
        var outTree = outObject.fancytree("getTree");
        outTree.reload(newPlayList);
        
        $.ajax({
            url: '{$urlAjaxPlaylistTemplate}',
            data: {
                region: region,
                sender_id: sender
            },
            success: function (res) {
                res = JSON.parse(res);
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
    
    $(".region select").change(function() {
        setSender($(this).val());
        setTreeData (
            $('#gmsplaylistout-region_id').val(),
            $('#gmsplaylistout-sender_id').val()
        );
    });
    
    $(".sender_id select").change(function() {
        setTreeData (
            $('#gmsplaylistout-region_id').val(),
            $('#gmsplaylistout-sender_id').val()
        );
    });
JS;

$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerCssFile('http://wwwendt.de/tech/fancytree/src/skin-win8/ui.fancytree.css');
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);
$this->registerJsFile('http://wwwendt.de/tech/fancytree/src/jquery.fancytree.js', ['depends' => [Assets::className()]]);
$this->registerJsFile('http://wwwendt.de/tech/fancytree/src/jquery.fancytree.table.js', ['depends' => [Assets::className()]]);
$this->registerJsFile('http://momentjs.com/downloads/moment.js', ['depends' => [Assets::className()]]);
$this->registerJs($js1);
?>
