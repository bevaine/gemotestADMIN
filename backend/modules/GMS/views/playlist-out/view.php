<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mihaildev\ckeditor\Assets;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Плейсты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

empty($model->active) ? $activePls = 'active' : $activePls = 'block';
?>
<div class="gms-playlist-out-view">

    <?php $form = ActiveForm::begin(['id'=>'form-input','method' => 'post']); ?>

    <p>
        <?= Html::SubmitButton($activePls == 'active' ? 'Разблокировать' : 'Заблокировать', [
            'name' => 'active-playlist',
            'class' => $activePls == 'active' ? 'btn btn-success' : 'btn btn-danger',
            'value' => $activePls
        ]) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="gms-playlist-form">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="box box-solid box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Плейлист: <?= $model->name ?></h3>
                        </div>
                        <div class="box-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                                        },
                                        'attribute' => 'region_id'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                                        },
                                        'attribute' => 'sender_id'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->deviceModel) ? $model->deviceModel->device : null;

                                        },
                                        'attribute' => 'device_id'
                                    ],
                                    'dateStart:date',
                                    'dateEnd:date',
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->timeStart) ? date('H:i', $model->timeStart) : null;

                                        },
                                        'attribute' => 'timeStart'
                                    ],
                                    [
                                        'value' => function ($model) {
                                            /** @var $model \common\models\GmsPlaylistOut */
                                            return !empty($model->timeEnd) ? date('H:i', $model->timeEnd) : null;

                                        },
                                        'attribute' => 'timeEnd'
                                    ],
                                    [
                                        'label' => 'Воспроизводить только',
                                        'value' => $model->getDaysPlaylist(),
                                        'format' => 'raw',
                                    ],
                                    'created_at:datetime',
                                    [
                                        'label' => 'Статус',
                                        'value' => $model->getAuthStatus(),
                                        'format' => 'raw',
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($model->jsonPlaylist)) : ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Видео в плейлисте</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            echo FancytreeWidget::widget([
                                'options' =>[
                                    'source' => new JsExpression('['.$model->jsonPlaylist.']'),
                                    'extensions' => ['dnd'],
                                    'collapse' => new JsExpression('function(event, data) {
                                         console.log(event, data);
                                    }'),
                                    'loadError' => new JsExpression('function(event, data) {
                                         console.log(event, data);
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
        <?php endif; ?>

    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerCssFile("https://unpkg.com/video.js/dist/video-js.css");
$this->registerJsFile('https://unpkg.com/video.js/dist/video.js', ['depends' => [Assets::className()]]);
?>
