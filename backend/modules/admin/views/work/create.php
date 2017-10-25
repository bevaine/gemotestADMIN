<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\t23 */

$this->title = 'Create T23';
$this->params['breadcrumbs'][] = ['label' => 'T23s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="t23-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
