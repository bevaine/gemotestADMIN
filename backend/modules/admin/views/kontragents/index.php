<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\KontragentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контрагенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kontragents-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'AID',
            'Name',
            'Key',
            'ShortName',
            'LoginsAID',
            //'BlankText',
            'BlankName',
            //'Blanks',
            'LCN',
            'mapPoint1.address',
            // 'isDelete',
            // 'PayType',
            // 'Type',
            // 'rmGroup',
            // 'inoe',
            // 'cito',
            // 'goscontract',
            // 'Li_cOrg',
            // 'ReestrUslug',
            // 'RegionID',
            // 'dt_off_discount',
            // 'flNoDiscCard',
            // 'dt_off_auto_discount',
            // 'dt_off_discount_card',
            // 'hide_price',
            // 'lab',
            // 'code_1c',
            // 'contract_number',
            // 'contract_name',
            // 'contractor_name',
            // 'contract_date',
            // 'date_update',
            // 'price_supplier',
            // 'sampling_of_biomaterial',
            // 'use_ext_num',
            // 'payment',
            // 'ext_num_mask',
            // 'salt',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
