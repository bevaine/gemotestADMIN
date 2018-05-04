<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\GmsDevices;
use common\components\helpers\FunctionsHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $action string */

$this->title = 'Устройства';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="gms-devices-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="nav-tabs-custom">

        <ul class="nav nav-tabs">
            <li class="<?= ($action == 'auth') ? "active" : '' ?>">
                <a href="<?php echo Url::to(["gms-devices/index/auth"]) ?>">
                    <span style="padding-right: 5px">Авторизованные</span>
                    <span class="label label-success">
                        <?= GmsDevices::find()->where(['auth_status' => 1])->count() ?>
                    </span>
                </a>
            </li>
            <li class="<?= ($action != 'auth') ? "active" : '' ?>">
                <a href="<?php echo Url::to(["gms-devices/index"]) ?>">
                    <span style="padding-right: 5px">Не авторизованные</span>
                    <span class="label label-warning">
                        <?= GmsDevices::find()->where(['auth_status' => 0])->count() ?>
                    </span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'headerOptions' => array('style' => 'text-align: center;'),
                            'contentOptions' => function ($model, $key, $index, $column){
                                return ['style' => 'text-align: center;'];
                            },
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var \common\models\GmsDevices $model */
                                return $model->getAuthGrid();
                            }
                        ],
                        [
                            'width'=>'196px',
                            'attribute' => 'created_at',
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->created_at)
                                    ? date("Y-m-d H:i:s T", strtotime($model->created_at))
                                    : null;
                            },
                            'filter' => \kartik\date\DatePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'created_at_from',
                                'attribute2' => 'created_at_to',
                                'options' => [
                                    'placeholder' => 'от',
                                    'style'=>['width' => '98px']
                                ],
                                'options2' => [
                                    'placeholder' => 'до',
                                    'style'=>['width' => '98px']
                                ],
                                'separator' => '-',
                                'readonly' => false,
                                'type' => \kartik\date\DatePicker::TYPE_RANGE,
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'autoclose' => true,
                                ]
                            ]),
                            'format' => 'html', // datetime
                        ],
                        'name',
                        'device',
                        [
                            'filter' =>  \common\models\GmsRegions::getRegionList(),
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->regionModel) ? $model->regionModel->region_name : null;
                            },
                            'attribute' => 'region_id'
                        ],
                        [
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->senderModel) ? $model->senderModel->sender_name : null;

                            },
                            'attribute' => 'sender_name'
                        ],
                        [
                            'headerOptions' => ['style' => 'width: 75px;'],
                            'attribute' => 'timezone',
                            'filter' => FunctionsHelper::getTimeZonesList(),
                            'format' => 'text',
                            'value' => function ($model) {
                                /** @var \common\models\GmsDevices $model */
                                return $model->getTimeZoneGrid();
                            }
                        ],
                        [
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->playListOutModel) ? $model->playListOutModel->name : null;

                            },
                            'attribute' => 'current_pls_name'
                        ],
                        [
                            'headerOptions' => array('style' => 'text-align: center;'),
                            'contentOptions' => function ($model, $key, $index, $column) {
                                return ['style' => 'text-align: center;'];
                            },
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var \common\models\GmsDevices $model */
                                return $model->getLastActiveGrid();
                            },
                            'attribute' => 'last_active_at'
                        ],
                        [
                            'label' => 'Статус',
                            'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var \common\models\GmsDevices $model */
                                return $model->getStateGrid();
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
