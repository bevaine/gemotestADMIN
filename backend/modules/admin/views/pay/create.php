<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NPay */

$this->title = 'Create Npay';
$this->params['breadcrumbs'][] = ['label' => 'Npays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="npay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
