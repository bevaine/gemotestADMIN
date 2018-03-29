<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use sjaakp\timeline\Timeline;
use edofre\fullcalendar\Fullcalendar;
use edofre\fullcalendar\models\Event;
use yii\web\JsExpression;
use common\models\GmsRegions;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;
use common\models\GmsPlaylistOut;

\backend\assets\GmsAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsVideoHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История воспроизведения';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .center {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>


<div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
    <div class="center">
        <video
                id="my-player"
                class="video-js"
                preload="auto"
                poster="../../img/logo.jpg"
                width="783"
                controls
                data-setup='{ "inactivityTimeout": 0 }'>
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a
                web browser that
                <a href="http://videojs.com/html5-video-support/" target="_blank">
                    supports HTML5 video
                </a>
            </p>
        </video>
    </div>
</div>

<div class="gms-video-history-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'responsive'=>true,
        'hover'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'headerOptions' => array('style' => 'width: 30px; text-align: center;'),
                'value' => function ($model) {
                    return !empty($model["vh_id"]) ? $model["vh_id"] : null;
                },
                'attribute' => 'id'
            ],
             [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Видео',
                'value' => function($model) {
                    if (empty($model['video_name'])
                        || empty($model['thumbnail'])
                        || empty($model['file'])) {
                        return null;
                    }

                    return Html::a(
                        Html::img($model["thumbnail"]),
                        null,
                        [
                            'style' => [
                                'cursor' => 'pointer'
                            ],
                            'title' => $model['video_name'],
                            'target' => '_blank',
                            'onclick' => "playVideo('{$model['video_name']}', '{$model['file']}')"
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'width'=>'196px',
                'attribute' => 'date_at',
                'value' => function($model) {
                    $html = '';
                    if (!empty($model['start_at'])) {
                        $html .= 'с '.$model['start_at'];
                    } else {
                        $html .= "по <span class='not-set'>(не задано)</span>";
                    }
                    $html .= "<br>";
                    if (!empty($model['last_at'])) {
                        $html .= 'по '.$model['last_at'];
                    } else {
                        $html .= "по <span class='not-set'>(не задано)</span>";
                    }
                    return $html;
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'options' => [
                        'placeholder' => 'от',
                        'style'=>['width' => '98px']
                    ],
                    'options2' => [
                        'placeholder' => 'до',
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
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'value' => function ($model) {
                    return !empty($model["region_name"]) ? $model["region_name"] : null;
                },
                'attribute' => 'region_id'
            ],
            [
                'value' => function ($model) {
                    return !empty($model["sender_name"]) ? $model["sender_name"] : null;
                },
                'attribute' => 'sender_name'
            ],
            [
                'attribute' => 'device_id',
                'width'=>'120px',
                'value' => function($model) {
                    return Html::a(
                        $model['device_id'],
                        Url::to(["/GMS/gms-devices/view?id=".$model['dev_id']]),
                        [
                            'title' => $model['device_id'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            //'created_at:datetime',
            //'last_at:datetime',
            [
                'attribute' => 'pls_name',
                'width'=>'120px',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['pls_name'],
                        Url::to(["/GMS/playlist-out/view?id=".$model['pls_id']]),
                        [
                            'title' => $model['pls_name'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'headerOptions' => array('style' => 'width: 200px;'),
                'label' => 'Тип видео',
                'value' => function($model) {
                    /** @var $model \common\models\GmsVideoHistory */
                    if (!$findModel = GmsPlaylistOut::findOne($model['pls_id'])) return null;
                    if ($data = $findModel->getVideoData($model['video_key'])) {
                        if (empty($data->type)) return null;
                        if ($data->type == 1) {
                            return 'Стандартный';
                        } elseif ($data->type == 2) {
                            return 'Коммерческий';
                        }
                    }
                    return null;
                },
                'format' => 'html',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'GMS/gms-video-history/view',
                            'id' => $model['vh_id']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    },
                ],
                'template' => '{view}'
            ]
        ],
    ]);
    ?>
</div>

<?php
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);
$urlAjaxDevice = \yii\helpers\Url::to(['/GMS/gms-devices/ajax-device-list']);
$urlAjaxVideoHistory = \yii\helpers\Url::to(['/GMS/gms-video-history/ajax-video-list']);

$js1 = <<< JS

    Timeline_ajax_url = "../../timeline/simile-ajax-api.js";
    Timeline_urlPrefix = '../../timeline/';
    Timeline_parameters = 'bundle=true';
    let resizeTimerID = null;
    
    $(function()
    {
        getTimeLine();
        
        $("#gmsvideohistorysearch-region_id").change(function() {
            setSender(
                $(this).val()
            );
            setDevice(
                $(this).val(), 
                $('#gmsvideohistorysearch-sender_id').val()
            );
            getTimeLine(
                $('#gmsvideohistorysearch-region_id').val(),
                $('#gmsvideohistorysearch-sender_id').val(),
                $('#gmsvideohistorysearch-device_id').val(),
            )
        });
        
        $("#gmsvideohistorysearch-sender_id").change(function() {
            setDevice(
                $('#gmsvideohistorysearch-region_id').val(), 
                $(this).val()
            );
            getTimeLine(
                $('#gmsvideohistorysearch-region_id').val(),
                $('#gmsvideohistorysearch-sender_id').val(),
                $('#gmsvideohistorysearch-device_id').val(),
            )
        });
        
        $(document).ready(function(){  
            setSender (
                $('#gmsvideohistorysearch-region_id').val()
            );
            setDevice (
                $('#gmsvideohistorysearch-region_id').val(), 
                $('#gmsvideohistorysearch-sender_id').val()
            );
            getTimeLine(
                $('#gmsvideohistorysearch-region_id').val(),
                $('#gmsvideohistorysearch-sender_id').val(),
                $('#gmsvideohistorysearch-device_id').val(),
            )
        });
    });
    
    function getTimeLine(region_id = null, sender_id = null, device_id = null) 
    {
        if (region_id === null) {
            return false;
        }
        
        const eventSource = new Timeline.DefaultEventSource();
            
        const zones = [
            {   start:    "2018-03-15 23:40:00 +03:00",
                end:      "2018-03-20 23:40:00 +03:00",
                magnify:  10,
                unit:     Timeline.DateTime.WEEK
            },
            {   start:    "Fri Nov 22 1963 09:00:00 GMT-0600",
                end:      "Sun Nov 24 1963 00:00:00 GMT-0600",
                magnify:  5,
                unit:     Timeline.DateTime.HOUR
            },
            {   start:    "Fri Nov 22 1963 11:00:00 GMT-0600",
                end:      "Sat Nov 23 1963 00:00:00 GMT-0600",
                magnify:  5,
                unit:     Timeline.DateTime.MINUTE,
                multiple: 10
            },
            {   start:    "Fri Nov 22 1963 12:00:00 GMT-0600",
                end:      "Fri Nov 22 1963 14:00:00 GMT-0600",
                magnify:  3,
                unit:     Timeline.DateTime.MINUTE,
                multiple: 5
            }
        ];
        const zones2 = [
            {   start:    "Fri Nov 22 1963 00:00:00 GMT-0600",
                end:      "Mon Nov 25 1963 00:00:00 GMT-0600",
                magnify:  10,
                unit:     Timeline.DateTime.WEEK
            },
            {   start:    "Fri Nov 22 1963 09:00:00 GMT-0600",
                end:      "Sun Nov 24 1963 00:00:00 GMT-0600",
                magnify:  5,
                unit:     Timeline.DateTime.DAY
            },
            {   start:    "Fri Nov 22 1963 11:00:00 GMT-0600",
                end:      "Sat Nov 23 1963 00:00:00 GMT-0600",
                magnify:  5,
                unit:     Timeline.DateTime.MINUTE,
                multiple: 60
            },
            {   start:    "Fri Nov 22 1963 12:00:00 GMT-0600",
                end:      "Fri Nov 22 1963 14:00:00 GMT-0600",
                magnify:  3,
                unit:     Timeline.DateTime.MINUTE,
                multiple: 15
            }
        ];
        
        const theme = Timeline.ClassicTheme.create();
        theme.event.bubble.width = 250;
        
        const date = "Fri Nov 22 1963 13:00:00 GMT-0600";
        const bandInfos = [
            Timeline.createHotZoneBandInfo({
                width:          "80%", 
                intervalUnit:   Timeline.DateTime.WEEK, 
                intervalPixels: 220,
                zones:          zones,
                eventSource:    eventSource,
                date:           date,
                timeZone:       -6,
                theme:          theme
            }),
            Timeline.createHotZoneBandInfo({
                width:          "20%", 
                intervalUnit:   Timeline.DateTime.MONTH, 
                intervalPixels: 200,
                zones:          zones2, 
                eventSource:    eventSource,
                date:           date, 
                timeZone:       -6,
                overview:       true,
                theme:          theme
            })
        ];
        bandInfos[1].syncWith = 0;
        bandInfos[1].highlight = true;
        
        for (let i = 0; i < bandInfos.length; i++) {
            bandInfos[i].decorators = [
                new Timeline.SpanHighlightDecorator({
                    startDate:  "Fri Nov 22 1963 12:30:00 GMT-0600",
                    endDate:    "Fri Nov 22 1963 13:00:00 GMT-0600",
                    color:      "#FFC080", // set color explicitly
                    opacity:    50,
                    startLabel: "shot",
                    endLabel:   "t.o.d.",
                    theme:      theme
                }),
                new Timeline.PointHighlightDecorator({
                    date:       "Fri Nov 22 1963 14:38:00 GMT-0600",
                    opacity:    50,
                    theme:      theme
                    // use the color from the css file
                }),
                new Timeline.PointHighlightDecorator({
                    date:       "Sun Nov 24 1963 13:00:00 GMT-0600",
                    opacity:    50,
                    theme:      theme
                    // use the color from the css file
                })
            ];
        }
        
        tl = Timeline.create(document.getElementById("tl"), bandInfos, Timeline.HORIZONTAL);        
        let url = '{$urlAjaxVideoHistory}'; 
        url += '?GmsVideoHistorySearch[region_id]=' + region_id;
        url += '&GmsVideoHistorySearch[sender_id]=' + sender_id;
        url += '&GmsVideoHistorySearch[device_id]=' + device_id;

        tl = Timeline.create(document.getElementById("tl"), bandInfos, Timeline.HORIZONTAL);
        tl.loadJSON(url, function(json, url) {
            console.log(json);
            eventSource.loadJSON(json, url);
        });
        
        setupFilterHighlightControls(document.getElementById("controls"), tl, [0,1], theme);

    }
    
    function onResize() {
        if (resizeTimerID === null) {
            resizeTimerID = window.setTimeout(function() {
                resizeTimerID = null;
                tl.layout();
            }, 500);
        }
    }
    
    /**
    * @param region
    */
    function setSender(region) 
    {
        const senderSelect = $('.sender_id select');
        const senderDisable = senderSelect.prop('disabled'); 
        senderSelect.attr('disabled', true); 
        
        $(".sender_id select option").each(function() {
            $(this).remove();
        }); 
        senderSelect.append("<option value=''>---</option>");
        
        $.ajax({
            url: '{$urlAjaxSender}',
            data: {region: region},
            success: function (res) {
                let optionsAsString = "";
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    const results = res.results; 
                    for (let i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                senderSelect.append( optionsAsString );
            }
        });
        senderSelect.attr('disabled', senderDisable);
    }
    
    /**
    * 
    * @param region
    * @param sender
    */
    function setDevice(region = null, sender = null) 
    {
        const deviceSelect = $('.device_id select');
        const deviceDisable = deviceSelect.prop('disabled');
        deviceSelect.attr('disabled', true); 
        
        $(".device_id select option").each(function() {
            $(this).remove();
        }); 
        deviceSelect.append("<option value=''>---</option>");                

        $.ajax({
            url: '{$urlAjaxDevice}',
            data: {
                region: region,
                sender: sender
            },
            success: function (res) {
                let optionsAsString = "";
                if (res !== null && res.results !== undefined && res.results.length > 0) {
                    const results = res.results; 
                    for (let i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                deviceSelect.append(optionsAsString);
            }
        });
        deviceSelect.attr('disabled', deviceDisable);
    }
JS;

$js2 = <<< JS
    function playVideo(name, file) {
        $('.video-js').prop('controls',true);
        const player = videojs('my-player');
        const modalPlayer = player.createModal(name);
        const modalHtml = $('#deactivate-user');
        player.src(file);
        player.ready(function() {
            player.play(); 
        });
        player.addChild(modalPlayer);
        modalPlayer.addClass('vjs-my-fancy-modal');
        $('.vjs-my-fancy-modal').css('height', '93%');
        modalPlayer.on('modalclose', function() {
            modalHtml.modal('hide');
            player.pause();
        });
        modalHtml.on('hidden.bs.modal', function () {
            modalPlayer.close();
            player.pause();
        });
        modalHtml.modal('show');
        modalPlayer.open();
    }
JS;

//$this->registerJsFile('../../timeline/timeline-api.js?bundle=true', ['position' => yii\web\View::POS_HEAD]);
//$this->registerJsFile('../../timeline/scripts/examples.js', ['position' => yii\web\View::POS_HEAD]);
$this->registerJs($js2, yii\web\View::POS_HEAD);
?>
