<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsVideoHistory */

$this->title = 'Create Gms Video History';
$this->params['breadcrumbs'][] = ['label' => 'Gms Video Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-video-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
