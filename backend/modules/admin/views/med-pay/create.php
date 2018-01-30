<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MedPay */

$this->title = 'Create Med Pay';
$this->params['breadcrumbs'][] = ['label' => 'Med Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-pay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
