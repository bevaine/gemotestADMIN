<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */

$this->title = 'Редактирование: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Franchazies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->AID]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="franchazy-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
