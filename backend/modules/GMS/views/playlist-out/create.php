<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = 'Конструктор плейлиста';
$this->params['breadcrumbs'][] = ['label' => 'Плейлисты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-out-create">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action
    ]) ?>

</div>
