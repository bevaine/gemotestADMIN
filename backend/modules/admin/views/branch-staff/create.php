<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BranchStaff */

$this->title = 'Create Branch Staff';
$this->params['breadcrumbs'][] = ['label' => 'Branch Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-staff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
