<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\models\GmsPlaylist;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\components\helpers\FunctionsHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsPlaylistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $action string */

$this->title = 'Шаблоны плейлистов';
$this->params['breadcrumbs'][] = $this->title;

switch ($action) {
    case 'group':
        $tab = 'tab_2';
        break;
    case 'device':
        $tab = 'tab_3';
        break;
    default:
        $tab = 'tab_1';
        break;
}
$url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);
?>

<div class="gms-playlist-index">

    <div class="nav-tabs-custom">

        <ul class="nav nav-tabs">
            <li class="<?= ($action == 'region' || is_null($action)) ? "active" : '' ?>">
                <a href="<?php echo Url::to(["playlist/index/region"]) ?>">
                    <span>Привязка к региону/отделению</span>
                </a>
            </li>
            <li class="<?= ($action == 'group') ? "active" : '' ?>">
                <a href="<?php echo Url::to(["playlist/index/group"]) ?>">
                    <span>Привязка к группе устройств</span>
                </a>
            </li>
            <li class="<?= ($action == 'device') ? "active" : '' ?>">
                <a href="<?php echo Url::to(["playlist/index/device"]) ?>">
                    <span>Привязка к устройству</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <p>
                    <?= Html::a('Создать', ['create#'.$tab], ['class' => 'btn btn-success']) ?>
                </p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'filter' =>  \common\models\GmsRegions::getRegionList(),
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsPlaylist */
                                return $model['region_name'];
                            },
                            'attribute' => 'region_id',
                            'group' => true,
                            'visible' => $action == 'region' || is_null($action) ? true : false
                        ],
                        [
                            'filter' => Select2::widget([
                                'name' => 'GmsPlaylistSearch[sender_id]',
                                'value' => $searchModel->sender_id,
                                'options' => ['placeholder' => 'Наименование отделения'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 2,
                                    'multiple' => false,
                                    'ajax' => [
                                        'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return { search:params.term}; }'),
                                        'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                                        'cache' => true
                                    ],
                                    'initSelection' => new JsExpression(FunctionsHelper::AjaxInitScript($url)),
                                ],
                            ]),
                            'value' => function ($model) {
                                return !empty($model['sender_name'])
                                    ? $model['sender_name']
                                    : Html::tag('span', '(действует во всём регионе)', ['class' => 'not-set']);
                            },
                            'group' => true,
                            'attribute' => 'sender_id',
                            'visible' => $action == 'region' || is_null($action) ? true : false,
                            'format' => 'raw'
                        ],
                        [
                            'filter' =>  \common\models\GmsGroupDevices::getGroupList(),
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsPlaylist */
                                return $model['group_name'];

                            },
                            'group' => true,
                            'attribute' => 'group_id',
                            'visible' => $action == 'group' ? true : false
                        ],
                        [
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsPlaylist */
                                return $model['device_name'];

                            },
                            'group' => true,
                            'attribute' => 'device_name',
                            'visible' => $action == 'device' ? true : false
                        ],
                        [
                            'filter' => \common\models\GmsPlaylist::getPlayListType(),
                            'attribute' => 'type',
                            'value' => function ($model) {
                                /** @var $model \common\models\GmsPlaylist */
                                return \common\models\GmsPlaylist::getPlayListType($model['type']);
                            },
                        ],
                        [
                            'value' => function ($model)  {
                                /** @var $model \common\models\GmsPlaylist */
                                $arr = [];
                                $style = '';
                                $icon = '';
                                if ($model['type'] == 1) {
                                    $style = 'primary';
                                    $icon = '/img/gemotest.jpg';
                                } elseif ($model['type'] == 2) {
                                    $style = 'success';
                                    $icon = '/img/dollar.png';
                                }
                                $html = Html::tag('span', Html::img($icon), ['style' => 'padding-right:5px']);
                                $html .= Html::tag(
                                    'span',$model['name'],
                                    ['class' => 'label label-'.$style,]
                                );
                                $arr[] = $html;

                                if (count($arr) > 0) {
                                    return implode('<br>', $arr);
                                } else return null;
                            },
                            'format' => 'raw',
                            'attribute' => 'playlist',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    /** @var \common\models\LoginsSearch $model */
                                    $customurl = Url::toRoute(['playlist/view', 'id' => $model['id']]);
                                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                        ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                                },
                                'update' => function ($url, $model) {
                                    /** @var \common\models\LoginsSearch $model */
                                    $customurl = Url::toRoute(['playlist/update', 'id' => $model['id']]);
                                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                                        ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                                },
                                'delete' => function ($url, $model) {
                                    /** @var \common\models\LoginsSearch $model */
                                    $customurl = Url::toRoute(['playlist/delete', 'id' => $model['id']]);
                                    return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                                        ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
