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
                        <div class="box-body">
                            <table id="devices_group">
                                <colgroup>
                                    <col width="50px">
                                    <col width="600px">
                                    <col width="30px">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Название</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center;"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
                                        const playlistNode = $("#devices_group")
                                            .fancytree("getTree")
                                            .getNodeByKey("group"),
                                            addChild = [];
                                        data.node.moveTo(playlistNode, "child");
                                        playlistNode.setExpanded();
                                    }
                                }'),
                                'dnd' => [
                                    'preventVoidMoves' => true,
                                    'preventRecursiveMoves' => true,
                                    'autoExpandMS' => 400,
                                    'dragStart' => new JsExpression('function(node, data) {
                                        return !node.isFolder();
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

<?php
$standartf = [
    [
        'title' => 'Новая группа',
        'key' => 'group',
        'folder' => true,
    ]
];
$standartf = json_encode($standartf);

$js1 = <<< JS
    
    const tree1 = $("#devices_group");
        
    $(function()
    {
        tree1.fancytree({
            extensions: ["table", "dnd", "edit"],
            table: {
                indentation: 20,
                nodeColumnIdx: 1,
                checkboxColumnIdx: 0
            },                
            source: {$standartf},
            dblclick: function(event, data) {

            },
            beforeActivate: function(event, data) {
            },
            renderColumns: function(event, data) {
                const node = data.node, tdList = $(node.tr).find(">td");
                tdList.eq(0).text(node.getIndexHier()).addClass("alignRight");
                if (!node.isFolder()) {
                    tdList.eq(2).html('<span id="trash-node" style="cursor:pointer;" class="glyphicon glyphicon-trash"></span>');
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
                    return false;
                },
                dragEnter : function(node, data) {
                    return true;
                },
                dragOver : function(node, data) {
                },
                dragDrop : function(node, data) {
                    console.log(data);
                    if (data.otherNode) 
                    {                                        
                        let sameTree = (data.otherNode.tree === data.tree);
                        const playlistNode = data.tree.getNodeByKey('group');

                        if (!sameTree) {
                            if (data.otherNode.isFolder()) {
                                $.each(data.otherNode.children, function(index, children1) {
                                    if (children1.isFolder()) {
                                        $.each(children1.children, function(index, children2) {
                                            children2.moveTo(playlistNode, 'child'); 
                                        });    
                                    } else {
                                        children1.moveTo(playlistNode, 'child'); 
                                    }
                                });
                            } else if (data.otherNode.isFolder() === false) {
                                data.otherNode.moveTo(playlistNode, 'child'); 
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
        
        tree1.delegate("span[id=trash-node]", "click", function(e){
            const node = $.ui.fancytree.getNode(e), tdList = $(node.tr);
            const tree2 = $("#fancyree_devices_all").fancytree("getTree");
            const playlistNode = tree2.getNodeByKey(node.data.key_parent);
            node.moveTo(playlistNode, "child");
            playlistNode.setExpanded();
            tdList.remove();
            e.stopPropagation();
            node.render(true);
        });
    });
JS;

$this->registerJs($js1);