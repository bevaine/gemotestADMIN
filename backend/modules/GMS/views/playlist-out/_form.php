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

$layout = <<< HTML
    {input1}
    {separator}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

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

    <?php $form = ActiveForm::begin(['id' => 'form']); ?>

    <div class="modal fade" id="check-playlist" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header" id="modal-header"></div>
                <div class="modal-body" id="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>

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
                                <?= $form->field($model, 'device_id')->dropDownList([], ['prompt' => '---']);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group date">
                                <?= Html::label('Период воспроизведения') ?>
                                <?= DatePicker::widget([
                                    'name' => 'GmsPlaylistOut[dateStart]',
                                    'name2' => 'GmsPlaylistOut[dateEnd]',
                                    'value' => date('d-m-Y', $model->isNewRecord ? time() : $model->dateStart),
                                    'value2' => date('d-m-Y', $model->isNewRecord ? time() : $model->dateEnd),
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

                        <div class="col-lg-6">
                            <div class="form-group date">
                                <?= Html::label('Время воспроизведения') ?>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <?php
                                        if (!empty($model->timeStart)) {
                                            $model->timeStart = date('H:i', $model->timeStart);
                                        }
                                        echo TimePicker::widget([
                                            'model' => $model,
                                            'value' => date('H:i', $model->timeStart),
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
                                        <?php
                                        echo TimePicker::widget([
                                            'model' => $model,
                                            'value' => date('H:i', $model->isNewRecord ? time() : $model->timeEnd),
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
                                <p>
                                <?php
                                foreach ($model::WEEK as $key => $value) {
                                    echo "<span style='padding-left: 10px'>".Html::Activecheckbox($model, $key, [
                                        'value' => "1",
                                        'label' => $value,
                                        'data-url' => 'isMonday'
                                    ])."</span>";
                                }
                                ?>
                                </p>
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
        <?= Html::Button($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxDevice = \yii\helpers\Url::to(['/GMS/gms-devices/ajax-device-list']);
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);
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
            source: {$source},
            dblclick: function(event, data) {
                var videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        var htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
                        res = JSON.parse(res);
                        if (res !== null && res.results.file !== undefined) {
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
                sumDuration(node.parent);
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
    * 
    * @param parentFolder
    */
    function addJSON (parentFolder) 
    {
        var arrOut = {};
        var arrChildrenOne = [];
        var playListKey = parentFolder.key;
        var rootTitle = parentFolder.title;        
        
        if ($("input").is("#gmsplaylistout-jsonplaylist")) {
            $("#gmsplaylistout-jsonplaylist").remove();
        }                    
        
        if ($("input").is("#gmsplaylistout-name")) {
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
                console.log(children);
                var arrChildren = {};
                var arrData = {};
                var key = children.key;
                var name = children.title;
                arrData["duration"] = children.data.duration;
                arrData["type"] = children.data.type;
                arrChildren["key"] = key; 
                arrChildren["title"] = name;
                arrChildren["data"] = arrData;
                arrChildrenOne.push(arrChildren); 
            });

            arrOut["children"] = arrChildrenOne;
            var jsonStr = JSON.stringify(arrOut);

            $("<input>").attr({
                type: "hidden",
                id: "gmsplaylistout-jsonplaylist",
                name: "GmsPlaylistOut[jsonPlaylist]",
                value: jsonStr
            }).appendTo("form");
        }
    }
    
    /**
    * 
    * @returns {boolean}
    */
    function checkJSON () 
    {
        var html_body = '';
        var htm_header = 'Ошибка сохранения плейлиста';
        var parentFolder = 
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
    
    /**
    * @param parent
    */
    function sumDuration (parent) 
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
        $('#duration-summ').html(totalStr);
    }
    
    /**
    * @param region
    */
    function setSender(region) 
    {
        var senderSelect = $('.sender_id select');
        var senderDisable = senderSelect.prop('disabled');        
        senderSelect.attr('disabled', true); 
        
        $(".sender_id select option").each(function() {
            $(this).remove();
        }); 
        senderSelect.append("<option value=''>---</option>");
        
        $.ajax({
            url: '{$urlAjaxSender}',
            data: {region: region},
            success: function (res) {
                res = JSON.parse(res);
                var optionsAsString = "";
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    var results = res.results; 
                    for (var i = 0; i < results.length; i++) {
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
        var deviceSelect = $('.device_id select');
        var deviceDisable = deviceSelect.prop('disabled');
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
                var optionsAsString = "";
                res = JSON.parse(res);
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    var results = res.results; 
                    for (var i = 0; i < results.length; i++) {
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
        var emptyList = [{ 
            title : 'уточните параметры для отображения', 
            folder : false 
        }];
        
        var regionObject = $("#fancyree_template_region");
        var regionTree = regionObject.fancytree("getTree");        
        
        var commercialObject = $("#fancyree_template_commercial");
        var commercialTree = commercialObject.fancytree("getTree");
        
        regionTree.reload(emptyList);
        regionObject.fancytree("disable");        
        
        commercialTree.reload(emptyList);
        commercialObject.fancytree("disable");
        
        var outObject = $("#treetable");
        var outTree = outObject.fancytree("getTree");
        outTree.reload(newPlayList);      
    }
    
    function setTreeData (region = null, sender = null) 
    {
        var regionObject = $("#fancyree_template_region");
        var regionTree = regionObject.fancytree("getTree");
        
        var commercialObject = $("#fancyree_template_commercial");
        var commercialTree = commercialObject.fancytree("getTree");
        
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
    
    $(".btn-primary, .btn-success").click(function() { 
        if (checkJSON()) $("#form").submit();
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
    
    $(document).ready(function(){  
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

$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);

$this->registerCssFile('http://wwwendt.de/tech/fancytree/src/skin-win8/ui.fancytree.css');
$this->registerJsFile('http://wwwendt.de/tech/fancytree/src/jquery.fancytree.js', ['depends' => [Assets::className()]]);
$this->registerJsFile('http://wwwendt.de/tech/fancytree/src/jquery.fancytree.table.js', ['depends' => [Assets::className()]]);
$this->registerJsFile('http://momentjs.com/downloads/moment.js', ['depends' => [Assets::className()]]);
$this->registerJs($js1);
?>
