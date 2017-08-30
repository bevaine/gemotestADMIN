<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NCashBalanceInLOFlow */

$this->title = 'Update Ncash Balance In Loflow: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ncash Balance In Loflows', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ncash-balance-in-loflow-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
