<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\GmsDevices;

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
            <li class="<?= ($action == 'auth') ? "active" : '' ?>"><a href="<?php echo Url::to(["gms-devices/index/auth"]) ?>">Авторизованные</a></li>
            <li class="<?= ($action != 'auth') ? "active" : '' ?>"><a href="<?php echo Url::to(["gms-devices/index"]) ?>">Не авторизованные</a></li>
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
                                /** @var \common\models\LoginsSearch $model */
                                $value = $model['last_active_at'];
                                $img_name = 'icon-time3.jpg';
                                if (!empty($value)) {
                                    $dt1 = new DateTime('now');
                                    $dt2 = $dt3 = new DateTime($value);
                                    $dt2->add(new DateInterval('P1D')); // +1 день
                                    $dt3->add(new DateInterval('P2D')); // +2 дня
                                    if ($dt2 <= $dt1) {
                                        $img_name = 'icon-time1.jpg';
                                    } elseif ($dt3 <= $dt1) {
                                        $img_name = 'icon-time2.jpg';
                                    }
                                }
                                return Html::img('/img/'.$img_name, [
                                    "alt" => 'Последняя активность была '.$value,
                                    "title" => 'Последняя активность была '.$value
                                ]);
                            }
                        ],
                        [
                            'attribute' => 'id',
                            'headerOptions' => array('style' => 'width: 30px; text-align: center;'),
                        ],
                        [
                            'width'=>'196px',
                            'attribute' => 'created_at',
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->created_at) ? $model->created_at : null;
                            },
                            'filter' => \kartik\date\DatePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'created_at_from',
                                'attribute2' => 'created_at_to',
                                'options' => [
                                    'placeholder' => 'От',
                                    'style'=>['width' => '98px']
                                ],
                                'options2' => [
                                    'placeholder' => 'До',
                                    'style'=>['width' => '98px']
                                ],
                                'separator' => 'По',
                                'readonly' => false,
                                'type' => \kartik\date\DatePicker::TYPE_RANGE,
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd',
                                    'autoclose' => true,
                                ]
                            ]),
                            'format' => 'html', // datetime
                        ],
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
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsDevices */
                                return !empty($model->playListOutModel) ? $model->playListOutModel->name : null;

                            },
                            'attribute' => 'current_pls_name'
                        ],
                        [
                            'label' => 'Статус',
                            'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                /** @var \common\models\LoginsSearch $model */
                                $value = $model['auth_status'];
                                $name = GmsDevices::getAuthStatus($value);
                                $value == 1 ? $class = 'success' : $class = 'danger';
                                $html = Html::tag('span', Html::encode($name), ['class' => 'label label-' . $class]);
                                return $html;
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
