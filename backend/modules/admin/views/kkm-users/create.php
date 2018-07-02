<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */

$this->title = 'Create Nkkm Users';
$this->params['breadcrumbs'][] = ['label' => 'Nkkm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nkkm-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
