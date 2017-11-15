<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NReturnOrder */

$this->title = 'Создать Возврат ЛИС';
$this->params['breadcrumbs'][] = ['label' => 'Возврат ЛИС', 'url' => ['index']];
?>
<div class="nreturn-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
