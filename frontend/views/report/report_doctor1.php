<?php
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\OrdersToExportSearch;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гемотест : просмотр "Отчёт по перевесам"';
$this->params['breadcrumbs'][] = ['label' => 'Отчёты', 'url' => ['/reports/default']];
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' => 'Врач',
        'value' => 'logins.Name'
    ],
    [
        'label' => 'Номер заказа',
        'value' => 'order_num'
    ],
    [
        'label' => 'Дата заказа',
        'value' => 'DateReg'
    ],
    [
        'label' => 'Отделение',
        'value' => 'OrderKontragentID'
    ],
    [
        'label' => 'Стоимость заказа',
        'value' => 'OrderAllCost'
    ],
    //['class' => 'yii\grid\ActionColumn'],
];

$this->registerCssFile('/css/token-input.css');
$this->registerJsFile('/js/jquery.tokeninput.js');
?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

<div class="user-requests-form">

    <?php $form = ActiveForm::begin(['method' => 'GET']); ?>

    <div class="row">

        <div class="col-lg-4">
            <div class="form-group doctor">
                <label>Врач:</label><br>
                <?= Html::input('text', 'OrdersToExportSearch[keys]', '', ['id' => 'demo-input', 'style' => 'width:100px']) ?>
                <div id="searchForm">
                    <script type="text/javascript">
                        (function($){
                            $(document).ready(function() {
                                $("#demo-input").tokenInput("ajax-doctor-list", {
                                    hintText: "Введите код или ФИО врача",
                                    noResultsText: "Врач не найден!",
                                    searchingText: "Выполняется поиск.."
                                });
                            });
                        })(jQuery);
                    </script>
                </div>
            </div>

            <div class="form-group date">
                <label>Дата заказов: от</label><br>
                <?php
                echo DatePicker::widget([
                    'name' => 'date_from',
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'dateFormat' => 'dd.MM.yyyy',
                ]);
                echo " - ";
                echo DatePicker::widget([
                    'name' => 'date_to',
                    'model' => $searchModel,
                    'attribute' => 'date_to',
                    'dateFormat' => 'dd.MM.yyyy',
                ])
                ?>
            </div>

            <div class="form-group date">
                <?php echo Html::submitButton('Сформировать', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="customer-reports-index">
        <p>
            <?php
            //print_r($dataProvider);
            //exit;
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
            ]);
            ?>
        </p>
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'striped' => false,
            'columns' => [
                ['class'=>'kartik\grid\SerialColumn'],
                [
                    'width'=>'150px',
                    'header' => 'Врач',
                    'value' => 'logins.Name',
                    'format' => 'html',
                    'group'=> true,
                    'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                        return [
                            'mergeColumns'=>[[1,4]], // columns to merge in summary
                            'content'=>[             // content to show in each summary cell
                                4=>'Итого:',
                                5=>GridView::F_SUM,
                            ],
                            'contentFormats'=>[      // content reformatting for each summary cell
                                5=>[ 'format' => 'number', 'decimals' => 2, 'thousandSep' => ' '],
                            ],
                            'contentOptions'=>[      // content html attributes for each summary cell
                                1=>['style'=>'text-align:right'],
                                5=>['style'=>'text-align:right'],
                            ],
                            // html attributes for group summary row
                            'options'=>[
//                            'class'=>'danger',
                                'style'=>'font-weight:bold; background-color: #f9f9f9;'
                            ]
                        ];
                    },
                ],
                [
                    'header' => 'Номер заказа',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'attribute'=>'order_num',
                    'width'=>'250px',
                    'pageSummaryOptions'=>['class'=>'text-right']
                ],
                [
                    'header' => 'Дата заказа',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'attribute'=>'DateReg',
                    'width'=>'100px',
                    'pageSummaryOptions'=>['class'=>'text-right']
                ],
                [
                    'header' => 'Отделение',
                    'vAlign'=>'middle',
                    'group'=> true,
                    'attribute'=>'OrderKontragentID',
                    'width'=>'100px',
                    'pageSummaryOptions'=>['class'=>'text-right'],
                ],
                [
                    'header' => 'Стоимость',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'attribute'=> 'OrderAllCost',
                    'width'=>'170px',
                    'pageSummaryOptions'=>['class'=>'text-right'],
                ],
             ]
        ]);
    ?>
    </div>
</div>