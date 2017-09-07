<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */

$this->title = 'Create Nworkshift';
$this->params['breadcrumbs'][] = ['label' => 'Nworkshifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nworkshift-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
