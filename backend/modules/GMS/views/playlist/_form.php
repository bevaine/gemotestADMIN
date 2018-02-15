<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use common\models\GmsVideos;


/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */
/* @var $form yii\widgets\ActiveForm */

$data = [
    ['title' => 'Новый плейлист', 'key' => '1', 'folder' => true, 'expanded' => false, ]
];

?>
<style type="text/css">
    span.fancytree-title {
        font-size: large;
    }
</style>

<div class="gms-playlist-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'value' => $model->isNewRecord ? 'Новый плейлист' : '']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'type')->dropDownList(\common\models\GmsPlaylist::getPlayListType()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Видео в плейлисте</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        //echo $form->field($model, 'file')->widget(FancytreeWidget::className(), [
                        echo FancytreeWidget::widget([
                            'options' =>[
                                'extensions' => ['dnd', 'edit'],
                                'source' => [
                                    [
                                        'title' => 'Новый плейлист',
                                        'key' => '0',
                                        'folder' => true,
                                        'expanded' => false
                                    ]
                                ],
                                //'clickFolderMode' => 4,
                                'edit' => [
                                    'triggerStart' => ["clickActive", "dblclick"],
                                    'beforeEdit' =>  new JsExpression('function(event, data){
                                    }'),
                                    'edit' => new JsExpression('function(event, data){
                                    }'),
                                    'beforeClose' => new JsExpression('function(event, data){
                                    }'),
                                    'save' => new JsExpression('function(event, data){
                                        console.log("save...", this, data);
                                        setTimeout(function(){
                                            $(data.node.span).removeClass("pending");
                                            data.node.setTitle(data.node.title);
                                        }, 2000);
                                        return true;
                                    }'),
                                    'close' => new JsExpression('function(event, data){
                                        if( data.save ) {
                                            $(data.node.span).addClass("pending");
                                        }
                                    }'),
                                ],
                                'dnd' => [
                                    'autoExpandMS' => 400,
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
                                        console.log(data.tree.getSelectedNodes());                                        
                                        if (data.otherNode) {
                                            if (data.otherNode.parent.key === "0") {
                                                data.otherNode.moveTo(node, data.hitMode);
                                            }
                                        } else if( data.otherNodeData ) {
                                            node.addChild(data.otherNodeData, data.hitMode);
                                        } else {
                                            node.addNode({
                                                title: transfer.getData("text")
                                            }, data.hitMode);
                                        }
                                        //console.log(data.otherNode.parent.key);
                                        //console.log(data.node.parent.children);
                                        node.setExpanded();
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
            <button class="btn btn-success" type="button" name="Permissions[action]" value="assign"><span class="glyphicon glyphicon-arrow-left"></span></button>
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
                            'options' =>[
                                'source' => [
                                    [
                                        'title' => 'Все видео',
                                        'folder' => true,
                                        'expanded' => false,
                                        'children' => GmsVideos::getVideosTree()
                                    ]
                                ],
                                'extensions' => ['dnd'],
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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $js1 = <<< JS

JS;
    //$this->registerJs($js1);
?>