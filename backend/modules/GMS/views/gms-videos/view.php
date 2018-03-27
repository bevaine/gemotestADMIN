<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mihaildev\ckeditor\Assets;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsVideos */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Библиотека видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$htmlPlayer = <<<HTML
    <video
            id="my-player"
            class="video-js"
            controls
            preload="auto"
            poster="../../img/logo.jpg"
            width="783"
            data-setup='{}'>
            <source src="$model->file" type="$model->type"></source>
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a
            web browser that
            <a href="http://videojs.com/html5-video-support/" target="_blank">
                supports HTML5 video
            </a>
        </p>
    </video>
HTML;

?>
<div class="gms-videos-view">

    <p>
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
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Видео',
                'value' => $htmlPlayer,
                'format' => 'raw',
            ],
            'name',
            'created_at:datetime',
            'type',
            [
                'attribute' => 'time',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideos */
                    return date("H:i:s" , mktime(0, 0, $model->time));
                },
            ],

        ],
    ]) ?>

</div>