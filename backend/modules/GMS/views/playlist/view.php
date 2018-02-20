<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use yii\helpers\Json;
use common\models\GmsVideos;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны плейлистов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                },
                'attribute' => 'region'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                },
                'attribute' => 'sender_id'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return \common\models\GmsPlaylist::getPlayListType($model->type);
                },
                'attribute' => 'type'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->created_at) ? date('Y-m-d H:i:s', $model->created_at) : null;
                },
                'attribute' => 'created_at'
            ],
            [
                'value' => function ($model) {
                    /** @var $model \common\models\GmsPlaylist */
                    return isset($model->updated_at) ? date('Y-m-d H:i:s', $model->updated_at) : null;
                },
                'attribute' => 'updated_at'
            ],
        ],
    ]) ?>
    <div class="gms-playlist-form">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Видео в шаблоне плейлиста</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            echo FancytreeWidget::widget([
                                'options' =>[
                                    'source' => new JsExpression('['.$model->jsonPlaylist.']'),
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

</div>
