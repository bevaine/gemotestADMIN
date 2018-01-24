<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Franchazies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchazy-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->AID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'AID',
            'Active',
            'Login',
            'Pass',
            'Name',
            'IsOperator',
            'Email:email',
            'IsAdmin',
            'Key',
            'BlankText',
            'BlankName',
            'Logo',
            'LogoText',
            'LogoText2',
            'LogoType',
            'LogoWidth',
            'TextPaddingLeft',
            'OpenExcel',
            'EngVersion',
            'InputOrder',
            'PriceID',
            'CanRegister',
            'InputOrderRM',
            'OpenActive',
            'ReestrUslug',
            'LCN',
            'Li_cOrg',
        ],
    ]) ?>

</div>
