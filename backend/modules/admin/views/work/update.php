<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\t23 */

$this->title = 'Update T23: ' . $model->q1;
$this->params['breadcrumbs'][] = ['label' => 'T23s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->q1, 'url' => ['view', 'id' => $model->q1]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="t23-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
