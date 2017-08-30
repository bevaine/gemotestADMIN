<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NCashBalanceInLOFlow */

$this->title = 'Create Ncash Balance In Loflow';
$this->params['breadcrumbs'][] = ['label' => 'Ncash Balance In Loflows', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ncash-balance-in-loflow-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
