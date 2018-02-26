<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use common\models\GmsVideos;
use common\components\helpers\FunctionsHelper;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    span.fancytree-title {
        font-size: large;
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
        <div class="col-lg-9">
            <div class="form-group region">
                <?= $form->field($model, 'region')->dropDownList(\common\models\GmsRegions::getRegionList(), [
                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="form-group sender_id">
                <?= $form->field($model, 'sender_id')->dropDownList([], [
                        'prompt' => '---', 'disabled' => (!$model->isNewRecord) ? 'disabled' : false
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'type')->dropDownList(\common\models\GmsPlaylist::getPlayListType(), [
                'disabled' => (!$model->isNewRecord) ? 'disabled' : false
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Видео в шаблоне плейлиста</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        $source = [];
                        $playListKey = 1;
                        $playListKeyStr = 'playList['.$playListKey.']';
                        if ($model->isNewRecord) {
                            $source =  [
                                [
                                    'title' => 'Новый шаблон',
                                    'key' => $playListKeyStr,
                                    'folder' => true,
                                    'expanded' => true
                                ]
                            ];
                        } else {
                            if (!empty($model->jsonPlaylist)) {
                                $source = new JsExpression('['.$model->jsonPlaylist.']');
                            }
                        }

                        echo FancytreeWidget::widget([
                            'id' => 'output_list',
                            'options' =>[
                                'extensions' => ['dnd', 'edit'],
                                'source' => $source,
                                'edit' => [
                                    'triggerStart' => ["clickActive", "dblclick"],
                                    'beforeEdit' =>  new JsExpression('function(event, data){
                                        return data.node.isFolder()
                                    }'),
                                    'inputCss' => [
                                        'color' => 'black'
                                    ],
                                    'edit' => new JsExpression('function(event, data){
                                    }'),
                                    'beforeClose' => new JsExpression('function(event, data){
                                    }'),
                                    'save' => new JsExpression('function(event, data){
                                        setTimeout(function(){
                                            $(data.node.span).removeClass("pending");
                                            data.node.setTitle(data.node.title);
                                        }, 2000);
                                        return true;
                                    }'),
                                    'close' => new JsExpression('function(event, data){
                                        if(data.save) {
                                            $(data.node.span).addClass("pending");
                                        }
                                    }'),
                                ],
                                'dnd' => [
                                    'autoExpandMS' => 400,
                                    'minExpandLevel' => 3,
                                    'dragStart' => new JsExpression('function(node, data) {
                                        if (node.isFolder()) return false;
                                        else return true;
                                    }'),
                                    'dragEnter' => new JsExpression('function(node, data) {
                                        return true;
                                    }'),
                                    'dragDrag'=> new JsExpression('function(node, data) {
                                        data.dataTransfer.dropEffect = "move";
                                    }'),
                                    'dragDrop' => new JsExpression('function(node, data) {
                                        if (data.otherNode) {
                                            var playListKey = "'.$playListKeyStr.'";
                                            var playlistNode = data.tree.getNodeByKey(playListKey);
                                            data.otherNode.moveTo(node, data.hitMode);
                                            if (data.otherNode.parent.key !== playListKey 
                                                || data.otherNode.parent.isRoot() === true) {
                                                    data.otherNode.moveTo(playlistNode, "over");
                                            }        
                                        } else if( data.otherNodeData ) {
                                            node.addChild(data.otherNodeData, data.hitMode);
                                        } else {
                                            node.addNode({
                                                title: transfer.getData("text")
                                            }, data.hitMode);
                                        }
                                    }'),
                                ],
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-1 text-center">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-arrow-left"></span></button>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Видеофайлы для добавления</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        echo FancytreeWidget::widget([
                            'id' => 'input_list',
                            'options' =>[
                                'source' => [
                                    [
                                        'title' => 'Все видео',
                                        'folder' => true,
                                        'key' => 'playList[all]',
                                        'expanded' => true,
                                        'children' => GmsVideos::getVideosTree()
                                    ]
                                ],
                                'extensions' => ['dnd'],
                                'dnd' => [
                                    'preventVoidMoves' => true,
                                    'preventRecursiveMoves' => true,
                                    'autoExpandMS' => 400,
                                    'dragStart' => new JsExpression('function(node, data) {
                                        return !node.isFolder()
                                    }'),
                                    'dragEnter' => new JsExpression('function(node, data) {
                                        return true;
                                    }'),
                                    'dragDrop' => new JsExpression('function(node, data) {
                                        if (data.otherNode) {
                                            var playListKey = "playList[all]";
                                            var playlistNode = data.tree.getNodeByKey(playListKey);
                                            data.otherNode.moveTo(node, data.hitMode);
                                            if (data.otherNode.parent.key !== playListKey 
                                                || data.otherNode.parent.isRoot() === true) {
                                                    data.otherNode.moveTo(playlistNode, "over");
                                            }        
                                        } else if (data.otherNodeData) {
                                            node.addChild(data.otherNodeData, data.hitMode);
                                        } else {
                                            node.addNode({
                                                title: transfer.getData("text")
                                            }, data.hitMode);
                                        }
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

    <div class="form-group">
        <?= Html::Button($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $urlDurations = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);

    if ($model->isNewRecord === false && !empty($model->jsonPlaylist)) {
        $varJSON = $model->jsonPlaylist;
        $this->registerJs("removeInputJSON({$varJSON});");
    }

    $js1 = <<< JS
    /**
    * @param jsonPlaylist
    */
    function removeInputJSON (jsonPlaylist = null) 
    {
        if (jsonPlaylist.children !== undefined 
        && jsonPlaylist.children.length > 0) {
            jsonPlaylist.children.forEach(function(children) {            
                
                var childrenKey = children.key;
                var childrenNode = $("#fancyree_input_list")
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
        var playListKey = "playList[{$playListKey}]";
        var rootTitle = parentFolder.title;        
        
        if ($("input").is("#gmsplaylist-jsonplaylist")) {
            $("#gmsplaylist-jsonplaylist").remove();
        }                    
        
        if ($("input").is("#gmsplaylist-name")) {
            $("#gmsplaylist-name").remove();
        }

        arrOut["key"] = playListKey;
        arrOut["title"] = rootTitle;
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
        var parentFolder = $("#fancyree_output_list")
            .fancytree("getTree")
            .getNodeByKey("playList[{$playListKey}]"); 
        
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
        var senderSelect = $('.sender_id select');
        var senderDisable = senderSelect.prop('disabled');
        senderSelect.attr('disabled', true);
        $.ajax({
            url: '{$urlDurations}',
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
                $(".sender_id select").append( optionsAsString );
                senderSelect.attr('disabled', senderDisable);
            }
        });
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

    $(document).ready(function(){  
        setSender($(".region select").val());
    }); 
    
    $(".region select").change(function() {
        setSender($(this).val());  
    });
JS;
    $this->registerJs($js1);
?>