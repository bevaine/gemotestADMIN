<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsVideos */

$this->title = 'Добавить видео в библиотеку';
$this->params['breadcrumbs'][] = ['label' => 'Библиотека видео', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-videos-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
