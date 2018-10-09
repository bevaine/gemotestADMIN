<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Франчайзи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchazy-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->AID], [
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
