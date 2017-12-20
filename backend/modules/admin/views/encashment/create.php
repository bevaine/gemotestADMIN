<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NEncashment */

$this->title = 'Create Nencashment';
$this->params['breadcrumbs'][] = ['label' => 'Nencashments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nencashment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
