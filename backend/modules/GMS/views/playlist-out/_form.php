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

$week = [
    "isMonday" => "Понедельник",
    "isTuesday" => "Вторник",
    "isWednesday" => "Среда",
    "isThursday" => "Четверг",
    "isFriday" => "Пятница",
    "isSaturday" => "Суббота",
    "isSunday" => "Воскресенье"
];
?>
<div class="gms-playlist-out-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Выбирите регион (отделение) для отображения доступных шаблонов</h3>
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
                                        <?php
                                        $playListKey = 1;
                                        $playListKeyStr = 'playList['.$playListKey.']';

                                        echo FancytreeWidget::widget([
                                            'id' => 'sender_playlist',
                                            'options' =>[
                                                'source' => [
                                                    [
                                                        'title' => 'Новый плейлист',
                                                        'key' => $playListKeyStr,
                                                        'folder' => true,
                                                        'expanded' => true
                                                    ]
                                                ],
                                                'extensions' => ['dnd', 'edit'],
                                                'edit' => [
                                                    'triggerStart' => ["clickActive", "dblclick"],
                                                    'beforeEdit' =>  new JsExpression('function(event, data){
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
                                                    'preventVoidMoves' => true,
                                                    'preventRecursiveMoves' => true,
                                                    'autoExpandMS' => 400,
                                                    'dragStart' => new JsExpression('function(node, data) {
                                                        if (node.isFolder()) return false;
                                                        else return true;
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

$this->registerJs($js1);
?>
