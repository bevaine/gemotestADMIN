<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use wbraganca\fancytree\FancytreeWidget;

/* @var $this yii\web\View */
/* @var $model common\models\GmsGroupDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gms-group-devices-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-5">
            <div class="form-group">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Новая группа</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        echo FancytreeWidget::widget([
                            'id' => 'devices_group',
                            'options' =>[
                                'disabled' => false,
                                'source' => [
                                    [
                                        'title' => 'Новая группа',
                                        'key' => 'group',
                                        'folder' => true,
                                    ]
                                ],
                                'extensions' => ['dnd'],
                                'dblclick' => new JsExpression('function(node, data) {
                                    if (!data.node.isFolder()) {
                                        const playlistNode = $("#treetable")
                                            .fancytree("getTree")
                                            .getNodeByKey("playlist"),
                                            addChild = [];
                                        addChild.push(data.node);
                                        playlistNode.addNode(addChild, "child");
                                    }
                                }'),
                                'dnd' => [
                                    'preventVoidMoves' => true,
                                    'preventRecursiveMoves' => true,
                                    'autoExpandMS' => 400,
                                    'dragStart' => new JsExpression('function(node, data) {
                                        return false;
                                    }'),
                                    'dragEnter' => new JsExpression('function(node, data) {
                                        return true;
                                    }'),
                                    'dragDrop' => new JsExpression('function(node, data) {
                                        if (data.otherNode) {                                        
                                            let sameTree = (data.otherNode.tree === data.tree);
                                            const playlistNode = data.tree.getNodeByKey(\'group\');

                                            if (!sameTree) {
                                                if (data.otherNode.isFolder()) {
                                                    $.each(data.otherNode.children, function(index, children1) {
                                                        if (children1.isFolder()) {
                                                            $.each(children1.children, function(index, children2) {
                                                                children2.moveTo(playlistNode, \'child\'); 
                                                            });    
                                                        } else {
                                                            children1.moveTo(playlistNode, \'child\'); 
                                                        }
                                                    });
                                                } else if (data.otherNode.isFolder() === false) {
                                                    data.otherNode.moveTo(playlistNode, \'child\'); 
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
                                    }'),
                                ],
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="form-group">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Иерархия добавленных устройств</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        echo FancytreeWidget::widget([
                            'id' => 'devices_all',
                            'options' =>[
                                'disabled' => false,
                                'source' => \common\models\GmsDevices::getTreeDevices(),
                                'extensions' => ['dnd'],
                                'dblclick' => new JsExpression('function(node, data) {
                                    if (!data.node.isFolder()) {
                                        const playlistNode = $("#treetable")
                                            .fancytree("getTree")
                                            .getNodeByKey("playlist"),
                                            addChild = [];
                                        addChild.push(data.node);
                                        playlistNode.addNode(addChild, "child");
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
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
