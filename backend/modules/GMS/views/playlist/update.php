<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */

$this->title = 'Update Gms Playlist: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Gms Playlists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-playlist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
