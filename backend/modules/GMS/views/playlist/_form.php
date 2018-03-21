<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use common\models\GmsVideos;
use common\components\helpers\FunctionsHelper;
use mihaildev\ckeditor\Assets;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    span.fancytree-title {
        font-size: small;
    }
</style>

<div class="gms-playlist-form">

    <?php $form = ActiveForm::begin(['id' => 'form']); ?>

    <div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
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
        <div class="col-xs-7">
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Перетащите видо-ролики для шаблона плейлиста</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group region">
                                <?= $form->field($model, 'region')->dropDownList(\common\models\GmsRegions::getRegionList(), [
                                    'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group sender_id">
                                <?= $form->field($model, 'sender_id')->dropDownList([], [
                                    'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group type">
                                <?= $form->field($model, 'type')->dropDownList(\common\models\GmsPlaylist::getPlayListType(), [
                                    'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Все видео-ролики</h3>
                                    </div>
                                    <div class="box-body">
                                        <table id="treetable1">
                                            <colgroup>
                                                <col width="50px">
                                                <col width="490px">
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

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Шаблон плейлиста</h3>
                                    </div>
                                    <div class="box-body">
                                        <table id="treetable2">
                                            <colgroup>
                                                <col width="50px">
                                                <col width="470px">
                                                <col width="150px">
                                                <col width="100px">
                                                <col width="30px">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Плейлист</th>
                                                <th>Тип ролика</th>
                                                <th>Длит.</th>
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
                                                <th colspan="3"><div class="duration-summ2" id="duration-summ2"></div></th>
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
        </div>

        <div class="col-xs-5">
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
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);

$videof = [
    [
        'title' => 'Все видео',
        'key' => 'playList[all]',
        'folder' => true,
        'expanded' => true,
        'icon' => '../../img/playlist.png',
        'children' => GmsVideos::getVideosTree()
    ]
];

$standartf =  [
    [
        'title' => 'Стандартный',
        'key' => 'playList[1]',
        'folder' => true,
        'expanded' => true,
        'icon' => '../../img/gemotest.jpg'
    ]
];

$commercef =  [
    [
        'title' => 'Коммерческий',
        'key' => 'playList[1]',
        'folder' => true,
        'expanded' => true,
        'icon' => '../../img/dollar.png'
    ]
];

$videof = json_encode($videof);
$standartf = json_encode($standartf);
$commercef = json_encode($commercef);

if (!$model->isNewRecord && !empty($model->jsonPlaylist)) {
    $standartf = new JsExpression('['.$model->jsonPlaylist.']');
}

$js1 = <<< JS
    
    var tree1 = $("#treetable1");
    var tree2 = $("#treetable2"); 
        
    $(function()
    {
        tree1.fancytree({
            extensions: ["table", "dnd"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },                
            source: {$videof},
            dblclick: function(event, data) {
                var playlistNode = tree2
                    .fancytree("getTree")
                    .getNodeByKey('playList[1]');
                var addChild = [];
                addChild.push(data.node);
                playlistNode.addNode(addChild, 'child');
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
                    return !node.isFolder();
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

        tree2.fancytree({
            extensions: ["table", "dnd", "edit"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },                
            source: {$standartf},
            dblclick: function(event, data) {
                var videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        var htm_table = null;
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
                        resetPlayer(htm_table);
                    }
                });
            },
            beforeActivate: function(event, data) {
            },
            renderColumns: function(event, data) {
                var node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");

                var typePlaylist = ''; 
                if ( $('#gmsplaylist-type').val() === '1') {
                    typePlaylist = 'Стандартный';                     
                } else if ( $('#gmsplaylist-type').val() === '2') {
                    typePlaylist = 'Коммерческий';
                }
                if (typePlaylist !== '' && !node.isFolder()) {
                    tdList.eq(2).text(typePlaylist);
                }                
                
                if (node.data.duration !== undefined) {
                    var time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
                    tdList.eq(3).text(time);
                } 
                
                if (!node.isFolder()) {
                    tdList.eq(4).html('<span id="trash-node" style="cursor:pointer;" class="glyphicon glyphicon-trash"></span>');
                }
                sumDuration(node.parent, '#duration-summ2');
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
                        var playlistNode = data.tree.getNodeByKey('playList[1]');
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
        
        tree2.delegate("span[id=trash-node]", "click", function(e){
            var node = $.ui.fancytree.getNode(e);
            var parent = node.parent;
            e.stopPropagation(); 
            node.remove();
            node.render(true);
            sumDuration(parent, '#duration-summ2');
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
    
    
    /**
    * @param jsonPlaylist
    */
    function removeInputJSON (jsonPlaylist = null) 
    {
        if (jsonPlaylist.children !== undefined 
        && jsonPlaylist.children.length > 0) {
            jsonPlaylist.children.forEach(function(children) {            
                
                var childrenKey = children.key;
                var childrenNode = tree2
                    .fancytree("getTree")
                    .getNodeByKey(childrenKey);
                
                if (childrenNode !== null) {
                    childrenNode.remove();
                }
            });
        }
    }
    
    /**
    * 
    * @param parentFolder
    */
    function addJSON (parentFolder) 
    {
        var arrOut = {};
        var arrChildrenOne = [];
        var playListKey = "playList[1]";
        var rootTitle = parentFolder.title;
        var rootIcon = parentFolder.icon;
        
        if ($("input").is("#gmsplaylist-jsonplaylist")) {
            $("#gmsplaylist-jsonplaylist").remove();
        }                    
        
        if ($("input").is("#gmsplaylist-name")) {
            $("#gmsplaylist-name").remove();
        }

        arrOut["key"] = playListKey;
        arrOut["title"] = rootTitle;
        arrOut["icon"] = rootIcon;
        arrOut["folder"] = "true";
        arrOut["expanded"] = "true";

        $("<input>").attr({
            type: "hidden",
            id: "gmsplaylist-name",
            name: "GmsPlaylist[name]",
            value: rootTitle
        }).appendTo("form");
        
        if (parentFolder.children !== null) {
            parentFolder.children.forEach(function(children) {
                console.log(children);
                var arrChildren = {};
                var arrData = {};
                var key = children.key;
                var name = children.title;
                var typePlaylist = $('#gmsplaylist-type').val();
                arrData["duration"] = children.data.duration;
                arrData["file"] = children.data.file;
                arrData["type"] = typePlaylist;
                arrChildren["key"] = key; 
                arrChildren["title"] = name;
                arrChildren["data"] = arrData;
                arrChildrenOne.push(arrChildren); 
            });

            arrOut["children"] = arrChildrenOne;
            var jsonStr = JSON.stringify(arrOut);

            $("<input>").attr({
                type: "hidden",
                id: "gmsplaylist-jsonplaylist",
                name: "GmsPlaylist[jsonPlaylist]",
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
        var parentFolder = tree2
            .fancytree("getTree")
            .getNodeByKey("playList[1]"); 
        
        if (parentFolder !== null && parentFolder.children !== null) {
            addJSON(parentFolder);
            return true;
        } else {
            html_body = 'Необходимо добавить хотя бы одно видео в шаблон плейлиста'; 
            $('#modal-header').html(htm_header);
            $('#modal-body').html(html_body);
            $('#deactivate-user').modal('show');
            return false;
        }
    }
    
    /**
    * 
    * @param region
    * @param sender_id
    * @param type_list
    */
    function checkList 
    (
        region = null, 
        sender_id = null, 
        type_list = null
    ) {
        var html_body = '';
        var htm_header = 'Ошибка сохранения плейлиста';       
        $.ajax({
            url: '/GMS/playlist/ajax-playlist-active',
            data: {
                region: region,
                sender_id: sender_id,
                type_list: type_list
            },
            success: function (res) {
                if (res === 'null') {
                    if (checkJSON()) $("#form").submit();
                } else {
                    res = JSON.parse(res);
                    var html_body = "";
                    var region = res.region;
                    var sender = res.sender;
                    var type = res.type;
                    var name = res.name;
                    var playlist_Id = res.id;
                    
                    if (region !== undefined) {
                        if (sender !== undefined) {
                            html_body += 'В отделении <b>' + sender + '</b> уже есть шаблон прикрепленного <b>"' + type + '"</b> плейлиста';
                        } else {
                            html_body += 'В регионе <b>' + region + '</b> уже есть шаблон прикрепленного <b>"' + type + '"</b> плейлиста';
                        }
                        html_body += ' <b><a target="_blank" href="/GMS/playlist/view?id=' + playlist_Id + '">';
                        html_body += name;
                        html_body += '</a></b>';
                    }

                    $('#modal-header').html(htm_header);
                    $('#modal-body').html(html_body);
                    $('#deactivate-user').modal('show');
                    return false;
                }
            }
        });
    }

    /**
    * @param region
    */
    function setSender(region) 
    {
        console.log(region);
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
    */
    function disableTree(val) 
    {
        var emptyList;
        val === '1' ?  emptyList = {$standartf} : emptyList = {$commercef};
        var regionObject = $("#treetable2");
        var regionTree = regionObject.fancytree("getTree");        
        regionTree.reload(emptyList);
        resetPlayer();
    }
    
    function resetPlayer(htm_table = null) {
        var myPlayer = videojs('my-player');
        if (htm_table === null) {
            htm_table = 'Добавьте ролик в окончательный плейлист. Просмотр видео и информации по двойному клику мыши.';
        }
        myPlayer.reset();
        myPlayer.poster("../../img/logo.jpg");
        myPlayer.width("645");
        
        $('#video-info')
            .addClass('video-info-normal')
            .html(htm_table);        
    }
    
    $(".btn-success").click(function() {
        checkList(
            $('#gmsplaylist-region').val(),
            $('#gmsplaylist-sender_id').val(),
            $('#gmsplaylist-type').val()
        );
    });

    $(".btn-primary").click(function() { 
        if (checkJSON()) $("#form").submit();
    });
    
    $(".region select").change(function() {
        setSender($(this).val());  
    });    
    
    $(".type select").change(function() {
        disableTree($(this).val());
    });
JS;
$this->registerJs($js1);

$js_ready = <<<JS
    $(document).ready(function(){  
        setSender($(".region select").val());
    }); 
JS;
if (!$model->isNewRecord) {
    $this->registerJs($js_ready);
}
?>