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

$this->registerCss("td.alignRight { text-align: right; }");
?>

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
                                        <h3 class="box-title">Региональный шаблон плейлиста</h3>
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
                                                        return data.tree.options.disabled ? false : true;
                                                    }'),
                                                    'dragEnter' => new JsExpression('function(node, data) {
                                                        return true;
                                                    }'),
                                                    'dragDrop' => new JsExpression('function(node, data) {
                                                        data.otherNode.moveTo(node, data.hitMode);
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
                                                        return data.tree.options.disabled ? false : true;
                                                    }'),
                                                    'dragEnter' => new JsExpression('function(node, data) {
                                                        return true;
                                                    }'),
                                                    'dragDrop' => new JsExpression('function(node, data) {
                                                        data.otherNode.moveTo(node, data.hitMode);
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
                                                <col width="385px">
                                                <col width="100px">
                                                <col width="150px">
                                                <col width="80px">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Плейлист</th>
                                                <th>Прод.</th>
                                                <th>Тип ролика</th>
                                                <th>
                                                    <span style="font-size: smaller">Маскс. Кол-во повт. в сутки</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <!-- Define a row template for all invariant markup: -->
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <select name="sel1" id="">
                                                        <option value="a">A</option>
                                                        <option value="b">B</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            </tbody>
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
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php


$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxPlaylistTemplate = \yii\helpers\Url::to(['/GMS/playlist/ajax-playlist-template']);

$js1 = <<< JS
    $(function(){
        $("#treetable").fancytree({
              extensions: ["table", "dnd", "edit"],
              table: {
                indentation: 20,      // indent 20px per node level
                nodeColumnIdx: 1,     // render the node title into the 2nd column
                checkboxColumnIdx: 0  // render the checkboxes into the 1st column
              },
              source: [
                 {"title" : "Новый плейлист", "key" : "PlayList[0]", "folder" : true, "expanded" : true}  
              ],
              renderColumns: function(event, data) {
                    var node = data.node, tdList = $(node.tr).find(">td");
                    var typePlaylist = '';
                    tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
                    if (node.data.type !== undefined) {
                        if (node.data.type === '1') {
                            typePlaylist = 'Региональный'; 
                        } else typePlaylist = 'Коммерческий';
                        tdList.eq(3).text(typePlaylist);
                    }
                    if (node.data.duration !== undefined) {
                        var time = moment.unix(node.data.duration).utc().format("HH:mm:ss");
                        tdList.eq(2).text(time);
                    }
                    //tdList.eq(4).html("<input type='checkbox' name='like' value='" + node.key + "'>");
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
                    dragDrop : function(node, data) {
                        if (data.otherNode) {
                            var playListKey = "PlayList[0]";
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
                    }
              }
        });
        /* Handle custom checkbox clicks */
        $("#treetable").delegate("input[name=like]", "click", function(e){
              var node = $.ui.fancytree.getNode(e),
              input = $(e.target);
              e.stopPropagation();  // prevent fancytree activate for this row
              if(input.is(":checked")){
                alert("like " + input.val());
              }else{
                alert("dislike " + input.val());
              }
        });
    });

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
                        optionsAsString += "<option value='" + results[i].id + "' " + (results[i].id == '{$model->sender_id}' ? 'selected' : '') + ">" + results[i].name + "</option>";
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
        $.ajax({
            url: '{$urlAjaxPlaylistTemplate}',
            data: {
                region: region,
                sender_id: sender
            },
            success: function (res) {
                //console.log(res);
                res = JSON.parse(res);
                if (res !== null) {
                    if (res.result[1] !== undefined) {
                        regionObject.fancytree("enable");
                        regionTree.reload(res.result[1]);
                    }                  
                    if (res.result[2] !== undefined) {
                        console.log(res.result[2]);
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
$this->registerCssFile('http://wwwendt.de/tech/fancytree/src/skin-win8/ui.fancytree.css');
$this->registerJsFile(
    'http://wwwendt.de/tech/fancytree/src/jquery.fancytree.js',
    ['depends' => [Assets::className()]]);
$this->registerJsFile(
    'http://wwwendt.de/tech/fancytree/src/jquery.fancytree.table.js',
    ['depends' => [Assets::className()]]);
$this->registerJsFile(
    'http://momentjs.com/downloads/moment.js',
    ['depends' => [Assets::className()]]);

$this->registerJs($js1);
?>
