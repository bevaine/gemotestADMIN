<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */

$this->title = 'Create Rep Period Lab Gemotest';
$this->params['breadcrumbs'][] = ['label' => 'Rep Period Lab Gemotests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rep-period-lab-gemotest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
