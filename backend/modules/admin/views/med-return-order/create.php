<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MedReturnOrder */

$this->title = 'Create Med Return Order';
$this->params['breadcrumbs'][] = ['label' => 'Med Return Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
