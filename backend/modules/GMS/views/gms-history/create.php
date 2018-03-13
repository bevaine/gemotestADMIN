<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsHistory */

$this->title = 'Create Gms History';
$this->params['breadcrumbs'][] = ['label' => 'Gms Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
