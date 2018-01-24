<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */

$this->title = 'Create Franchazy';
$this->params['breadcrumbs'][] = ['label' => 'Franchazies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchazy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
