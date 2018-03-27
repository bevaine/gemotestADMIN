<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\GmsVideoHistory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'История показа видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$videoFile = $model->videoModel->file;
$videoType = $model->videoModel->type;

$htmlPlayer = <<<HTML
<video
        id="my-player"
        class="video-js"
        controls
        preload="auto"
        poster="../../img/logo.jpg"
        width="783"
        data-setup='{}'>
    <source src="$videoFile" type="$videoType"></source>
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

<div class="gms-video-history-view">

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
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Название видео',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    return !empty($model->videoModel->name) ? $model->videoModel->name : null;
                },
                'format' => 'html',
            ],
            'created_at:datetime',
            'last_at:datetime',
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Название плейлиста',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    return !empty($model->playListOutModel->name) ? $model->playListOutModel->name : null;
                },
                'format' => 'html',
            ],
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Номер устройства',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    return !empty($model->deviceModel->device) ? $model->deviceModel->device : null;
                },
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
