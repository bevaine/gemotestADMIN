<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $dataArr array */

$this->title = $dataArr['group_name'];
$this->params['breadcrumbs'][] = ['label' => 'Группы устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gms-group-devices-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'group_id' => $dataArr['group_id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'group_id' => $dataArr['group_id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <div class="form-group">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Группа устройств</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        echo FancytreeWidget::widget([
                            'id' => 'devices_group',
                            'options' =>[
                                'disabled' => false,
                                'source' => !empty($dataArr['json']) ? json_decode($dataArr['json']) : [],
                                'extensions' => ['dnd'],
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
</div>
