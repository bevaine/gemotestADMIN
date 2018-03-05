<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = 'Плейлист ID: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Плейлисты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="gms-playlist-out-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
