<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Kontragents */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kontragents-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'AID',
            'Name',
            'Key',
            'ShortName',
            'LoginsAID',
            'BlankText',
            'BlankName',
            'mapPoint1.address',
            'mapPoint1.zip_code',
            'mapPoint1.area',
            'mapPoint1.city',
            'mapPoint1.street',
            'mapPoint1.house',
            'mapPoint1.housing',
            'mapPoint1.region',
            'isDelete',
            'PayType',
            'Blanks',
            'Type',
            'rmGroup',
            'inoe',
            'cito',
            'goscontract',
            'Li_cOrg',
            'LCN',
            'ReestrUslug',
            'RegionID',
            'dt_off_discount',
            'flNoDiscCard',
            'dt_off_auto_discount',
            'dt_off_discount_card',
            'hide_price',
            'lab',
            'code_1c',
            'contract_number',
            'contract_name',
            'contractor_name',
            'contract_date',
            'date_update',
            'price_supplier',
            'sampling_of_biomaterial',
            'use_ext_num',
            'payment',
            'ext_num_mask',
            'salt',
        ],
    ]) ?>

</div>
