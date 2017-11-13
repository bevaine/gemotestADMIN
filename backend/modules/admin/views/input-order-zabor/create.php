<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */

$this->title = 'Create Input Order Iskl Issl Mszabor';
$this->params['breadcrumbs'][] = ['label' => 'Input Order Iskl Issl Mszabors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-order-iskl-issl-mszabor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
