<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */

$this->title = 'Create Gms Playlist';
$this->params['breadcrumbs'][] = ['label' => 'Gms Playlists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
