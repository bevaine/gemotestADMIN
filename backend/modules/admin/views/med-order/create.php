<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MedOrder */

$this->title = 'Create Med Order';
$this->params['breadcrumbs'][] = ['label' => 'Med Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
