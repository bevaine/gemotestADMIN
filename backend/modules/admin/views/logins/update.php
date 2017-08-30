<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Logins */

$this->title = 'Update Logins: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->aid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="logins-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
