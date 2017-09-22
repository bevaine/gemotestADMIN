<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Kontragents */

$this->title = 'Create Kontragents';
$this->params['breadcrumbs'][] = ['label' => 'Kontragents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kontragents-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
