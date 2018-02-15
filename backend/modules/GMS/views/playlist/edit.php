<?php

use yii\web\JsExpression;
use wbraganca\fancytree\FancytreeWidget;
// Example of data.
$data = [
    ['title' => 'Node 1', 'key' => 1],
    ['title' => 'Folder 2', 'key' => '2', 'folder' => true, 'children' => [
        ['title' => 'Node 2.1', 'key' => '3'],
        ['title' => 'Node 2.2', 'key' => '4']
    ]]
];

/* @var $this yii\web\View */
?>
<h1>Конструктор плейлиста</h1>

    <div class="logins-form">
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Региональные плейлисты</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            echo FancytreeWidget::widget([
                                'options' =>[
                                    'source' => $data,
                                    'extensions' => ['dnd'],
                                    'dnd' => [
                                        'preventVoidMoves' => true,
                                        'preventRecursiveMoves' => true,
                                        'autoExpandMS' => 400,
                                        'dragStart' => new JsExpression('function(node, data) {
                                            return true;
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
            <div class="col-lg-3">
                <div class="form-group">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Комерческие плейлисты</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            echo FancytreeWidget::widget([
                                'options' =>[
                                    'source' => $data,
                                    'extensions' => ['dnd'],
                                    'dnd' => [
                                        'preventVoidMoves' => true,
                                        'preventRecursiveMoves' => true,
                                        'autoExpandMS' => 400,
                                        'dragStart' => new JsExpression('function(node, data) {
                                            return true;
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
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Окончательный плейлист</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            echo FancytreeWidget::widget([
                                'options' =>[
                                    'source' => $data,
                                    'extensions' => ['dnd'],
                                    'dnd' => [
                                        'preventVoidMoves' => true,
                                        'preventRecursiveMoves' => true,
                                        'autoExpandMS' => 400,
                                        'dragStart' => new JsExpression('function(node, data) {
                                            return true;
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

