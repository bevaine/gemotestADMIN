<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NAdUsers */

$this->title = 'Create Nad Users';
$this->params['breadcrumbs'][] = ['label' => 'Nad Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nad-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
