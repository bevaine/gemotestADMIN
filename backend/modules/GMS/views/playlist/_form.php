<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\models\GmsVideos;
use yii\helpers\Url;

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

    <div class="modal bootstrap-dialog type-warning fade size-normal in" id="modal-dialog" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
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
        <div class="col-xs-7">
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="">
                        <a data-toggle="" href="#tab_1">Привязка к региону/отделению</a>
                    </li>
                    <li class="">
                        <a data-toggle="" href="#tab_2">Привязка к группе устройств</a>
                    </li>
                    <li class="">
                        <a data-toggle="" href="#tab_3">Привязка к устройству</a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div id="tab_1" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group region">
                                    <?= $form->field($model, 'region')->dropDownList(\common\models\GmsRegions::getRegionList(), [
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
                                                <th style="font-size: smaller" colspan="3">Итого</th>
                                                <th colspan="2"><div class="duration-summ2" id="duration-summ2"></div></th>
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
                        <div class="video-info" id="video-info">
                            Добавьте ролик в шаблон плейлиста. Просмотр видео и информации по двойному клику мыши.
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
$isNew = 'false';
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxVideo = \yii\helpers\Url::to(['/GMS/gms-videos/ajax-video-active']);

$videof = [
    [
        'title' => 'Все видео',
        'key' => 'playList[all]',
        'folder' => true,
        'expanded' => true,
        'icon' => '/img/playlist.png',
        'children' => GmsVideos::getVideosTree()
    ]
];

$standartf =  [
    [
        'title' => 'Стандартный',
        'key' => 'playList[1]',
        'folder' => true,
        'expanded' => true,
        'icon' => '/img/gemotest.jpg'
    ]
];

$commercef =  [
    [
        'title' => 'Коммерческий',
        'key' => 'playList[1]',
        'folder' => true,
        'expanded' => true,
        'icon' => '/img/dollar.png'
    ]
];

$videof = json_encode($videof);
$standartf = json_encode($standartf);
$commercef = json_encode($commercef);

if ($model->isNewRecord) {
    $isNew = 'true';
} else {
    if (!empty($model->jsonPlaylist)) {
        $standartf = new JsExpression('['.$model->jsonPlaylist.']');
    }
}

$js1 = <<< JS
    
    const tree1 = $("#treetable1"),
        tree2 = $("#treetable2"),
        regionSelectConst = $('#gmsplaylist-region'),
        typeSelectConst = $('#gmsplaylist-type'),
        senderSelectConst = $('#gmsplaylist-sender_id'),
        deviceSelectConst = $('#gmsplaylist-device_id'),
        groupSelectConst = $('#gmsplaylist-group_id'),
        input = $("input"),
        isNew = {$isNew}; 
        
    function setNewTabs() {
        if (isNew !== false) {
            regionSelectConst.prop('selectedIndex', 0);
            setSender(regionSelectConst.val());
            groupSelectConst.prop('selectedIndex', 0);
            deviceSelectConst.prop('selectedIndex', 0);               
        }        
    }
    
    $(function()
    {  
        setTabs();
        
        if (isNew !== false) {
            setSender(regionSelectConst.val());
        }
        
        $("a[href='#tab_1'], a[href='#tab_2'], a[href='#tab_3']").click(function() {
            setNewTabs();
        });
           
        let strGET = window.location.hash;
        $('ul.nav a[href="' + strGET + '"]').tab('show');
        setNewTabs();
        
        $(".btn-success").click(function() {
             checkList(
                 regionSelectConst.val(),
                 senderSelectConst.val(),
                 typeSelectConst.val(),
                 groupSelectConst.val(),
                 deviceSelectConst.val()
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
                
        tree1.fancytree({
            extensions: ["table", "dnd"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },                
            source: {$videof},
            dblclick: function(event, data) {
                if (data.node.isFolder()) {
                    return false;
                }
                if (typeSelectConst.val() === '2') {
                    const findKey = tree2
                        .fancytree("getTree")
                        .getNodeByKey(data.node.key);
                    if (findKey !== null) return false;
                }
                const playlistNode = tree2
                    .fancytree("getTree")
                    .getNodeByKey('playList[1]'),
                    addChild = [];
                addChild.push(data.node);
                playlistNode.addNode(addChild, 'child');                        
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
                const videoKey = data.node.key;
                $.ajax({
                    url: '{$urlAjaxVideo}',
                    data: {video: videoKey},
                    success: function (res) {
                        let htm_table = null;
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
                        resetPlayer(htm_table);
                    }
                });
            },
            beforeActivate: function(event, data) {
            },
            renderColumns: function(event, data) {
                const node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
                
                let typePlaylist = ''; 
                if (typeSelectConst.val() === '1') {
                    typePlaylist = 'Стандартный';                     
                } else if (typeSelectConst.val() === '2') {
                    typePlaylist = 'Коммерческий';
                }
                
                if (typePlaylist !== '' && node.isFolder() === false) {
                    tdList.eq(2).text(typePlaylist);
                }                
                
                if (node.data.duration !== undefined) {
                    const time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
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
                        if (typeSelectConst.val() === '2') {
                            const findKey = tree2
                                .fancytree("getTree")
                                .getNodeByKey(data.otherNode.key);
                            if (findKey !== null) return false;
                        }                        
                        let sameTree = (data.otherNode.tree === data.tree);
                        const playlistNode = data.tree.getNodeByKey('playList[1]');
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
            const node = $.ui.fancytree.getNode(e);
            const parent = node.parent;
            e.stopPropagation(); 
            node.remove();
            node.render(true);
            sumDuration(parent, '#duration-summ2');
            resetPlayer();
        });
    });
    
    /**
    * 
    */
    function setTabs() {
        let liTab1 = '',
            liTab2 = '',
            liTab3 = '',
            pineTab1 = '',
            pineTab2 = '',
            pineTab3 = '';

        const customTabs = $('.nav-tabs-custom'),
            navTabs = customTabs.find('.nav-tabs'),
            contentTabs = customTabs.find('.tab-content'),
            liTabs = navTabs.find('li'),
            pineTabs = contentTabs.find('.tab-pane');
        
        if (liTabs.length > 0) {
            liTab1 = liTabs.eq(0);
            liTab2 = liTabs.eq(1);
            liTab3 = liTabs.eq(2);                
        }
        
        if (pineTabs.length > 0) {
            pineTab1 = pineTabs.eq(0);
            pineTab2 = pineTabs.eq(1);
            pineTab3 = pineTabs.eq(2);
        }

        if (isNew !== false) {
            if (liTab1 !== '' && pineTab1 !== '') {
                liTab1.addClass('active');
                liTab1.find('a').attr('data-toggle', 'tab');
                pineTab1.removeClass('tab-pane fade').addClass('tab-pane fade in active');
            }
            if (liTab2 !== '') {
                liTab2.find('a').attr('data-toggle', 'tab');
                
            }
            if (liTab3 !== '') {
                liTab3.find('a').attr('data-toggle', 'tab');
            }
        } else {
            let data_tab1 = "",
                data_tab2 = "",
                data_tab3 = "",
                class_tab1 = "disabled",
                class_tab2 = "disabled",
                class_tab3 = "disabled",
                class_active1 = 'tab-pane fade',
                class_active2 = 'tab-pane fade',
                class_active3 = 'tab-pane fade';

            if (liTab1 !== '' && pineTab1 !== '' && '{$model->region}' !== '') {
                data_tab1 = 'tab';
                class_tab1 = 'active';
                class_active1 = 'tab-pane fade in active';
            }
            
            if (liTab2 !== '' && pineTab2 !== '' && '{$model->group_id}' !== '') {
                data_tab2 = 'tab';
                class_tab2 = 'active';
                class_active2 = 'tab-pane fade in active';
            }
            
            if (liTab3 !== '' && pineTab3 !== '' && '{$model->device_id}' !== '') {
                data_tab3 = 'tab';
                class_tab3 = 'active';
                class_active3 = 'tab-pane fade in active';
            }

            liTab1.addClass(class_tab1);
            liTab1.find('a').attr('data-toggle', data_tab1);
            pineTab1.removeClass('tab-pane fade').addClass(class_active1);
            
            liTab2.addClass(class_tab2);
            liTab2.find('a').attr('data-toggle', data_tab2);
            pineTab2.removeClass('tab-pane fade').addClass(class_active2);
            
            liTab3.addClass(class_tab3);
            liTab3.find('a').attr('data-toggle', data_tab3);
            pineTab3.removeClass('tab-pane fade').addClass(class_active3);
        }
    }
    
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
    
    
    /**
    * @param jsonPlaylist
    */
    function removeInputJSON (jsonPlaylist = null) 
    {
        if (jsonPlaylist.children !== undefined 
        && jsonPlaylist.children.length > 0) {
            jsonPlaylist.children.forEach(function(children) {            
                
                const childrenKey = children.key;
                const childrenNode = tree2
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
        const arrOut = {};
        const arrChildrenOne = [];
        const playListKey = "playList[1]";
        const rootTitle = parentFolder.title;
        const rootIcon = parentFolder.icon;
        
        if (input.is("#gmsplaylist-jsonplaylist")) {
            $("#gmsplaylist-jsonplaylist").remove();
        }                    
        
        if (input.is("#gmsplaylist-name")) {
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
                const arrChildren = {};
                const arrData = {};
                const key = children.key;
                const name = children.title;
                const typePlaylist = typeSelectConst.val();
                arrData["duration"] = children.data.duration;
                arrData["frame_rate"] = children.data.frame_rate;
                arrData["nb_frames"] = children.data.nb_frames;
                arrData["file"] = children.data.file;
                arrData["type"] = typePlaylist;
                arrChildren["key"] = key; 
                arrChildren["title"] = name;
                arrChildren["data"] = arrData;
                arrChildrenOne.push(arrChildren); 
            });

            arrOut["children"] = arrChildrenOne;
            const jsonStr = JSON.stringify(arrOut);

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
        let html_body = '';
        const htm_header = 'Ошибка сохранения плейлиста',
            parentFolder = tree2
            .fancytree("getTree")
            .getNodeByKey("playList[1]"); 
        
        if (parentFolder !== null && parentFolder.children !== null) {
            addJSON(parentFolder);
            return true;
        } else {
            html_body = 'Необходимо добавить хотя бы одно видео в шаблон плейлиста'; 
            
            $('#bootstrap-dialog-title').html(htm_header);  
            $('#bootstrap-dialog-message').html(html_body);  
            $('#modal-dialog').modal('show');
            return false;
        }
    }
    
    /**
    * 
    * @param region
    * @param sender_id
    * @param type_list
    * @param group_id
    * @param device_id
    */
    function checkList 
    (
        region = null, 
        sender_id = null, 
        type_list = null,
        group_id = null,
        device_id = null
    ) {
        let html_body = '';
        const htm_header = 'Ошибка сохранения плейлиста',
            activeTab = $('.nav-tabs .active a')[0].hash;
            
        if (activeTab === '#tab_1' && region === '') {
            html_body += 'Не указано обязательное поле - <b>"Регион прогрывания"</b>'; 
        }
        if (activeTab === '#tab_2' && group_id === '') {
            html_body += 'Не указано обязательное поле - <b>"Группа устройств"</b>'; 
        }
        if (activeTab === '#tab_3' && device_id === '') {
            html_body += 'Не указано обязательное поле - <b>"Устройство"</b>'; 
        }
        if (html_body !== '') {
            $('#bootstrap-dialog-title').html(htm_header);  
            $('#bootstrap-dialog-message').html(html_body);  
            $('#modal-dialog').modal('show');
            return false;
        }
        $.ajax({
            url: '/GMS/playlist/ajax-playlist-active',
            data: {
                region: region,
                sender_id: sender_id,
                type_list: type_list,
                group_id: group_id,
                device_id: device_id
            },
            success: function (res) {
                if (res === 'null') {
                    if (checkJSON()) $("#form").submit();
                } else {
                    let html_body = "";
                    const region = res.region,
                        sender = res.sender,
                        type = res.type,
                        name = res.name,
                        group = res.group,
                        device = res.device,
                        playlist_Id = res.id;
                    
                    if (region !== null) {
                        html_body += 'Для региона <b>' + region + '</b>';
                        if (sender !== null) {
                            html_body += ' и отделения <b>' + sender + '</b>';
                        } 
                    } else if (group !== null) {
                        html_body += 'Для группы устройств <b>' + group + '</b>';     
                    } else if (device !== null) {
                        html_body += 'Для устройства <b>' + device + '</b>'; 
                    }
                    
                    if (html_body !== '') {
                        html_body += ' уже есть шаблон с типом <b>"' + type + '"</b> и названием ';
                        html_body += ' <b><a target="_blank" href="/GMS/playlist/view?id=' + playlist_Id + '">';
                        html_body += '"' + name + '"';
                        html_body += '</a></b>';
                    }    

                    $('#bootstrap-dialog-title').html(htm_header);  
                    $('#bootstrap-dialog-message').html(html_body);  
                    $('#modal-dialog').modal('show');
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
    */
    function disableTree(val) 
    {
        let emptyList;
        val === '1' ?  emptyList = {$standartf} : emptyList = {$commercef};
        const regionObject = $("#treetable2");
        const regionTree = regionObject.fancytree("getTree");        
        regionTree.reload(emptyList);
        resetPlayer();
    }
    
    function resetPlayer(htm_table = null) {
        const myPlayer = videojs('my-player');
        if (htm_table === null) {
            htm_table = 'Добавьте ролик в шаблон плейлиста. Просмотр видео и информации по двойному клику мыши.';
        }
        myPlayer.reset();
        myPlayer.poster("/img/logo.jpg");
        myPlayer.width("645");
        
        $('#video-info')
            .addClass('video-info-normal')
            .html(htm_table);        
    }
JS;
$this->registerJs($js1);
?>