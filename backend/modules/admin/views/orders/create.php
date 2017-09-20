<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExport */

$this->title = 'Create Orders To Export';
$this->params['breadcrumbs'][] = ['label' => 'Orders To Exports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-to-export-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
