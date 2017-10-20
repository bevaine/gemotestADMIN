<?php
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\OrdersToExportSearch;
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гемотест : "Отчёт по перевесам"';
$this->params['breadcrumbs'][] = ['label' => 'Отчёты', 'url' => ['/reports/default']];
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' => 'Врач',
        'value' => 'logins.Name'
    ],
    [
        'label' => 'Отделение',
        'value' => 'OrderKontragentID'
    ],
    [
        'label' => 'Дата заказа',
        'value' => 'DateReg'
    ],
    [
        'label' => 'Номер заказа',
        'value' => 'order_num'
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

<div class="user-requests-form">

    <div class="box box-solid box-default">

        <div class="box-header with-border">
            <h3 class="box-title">Параметры поиска</h3>

        </div>

        <div class="box-body">

            <?php $form = ActiveForm::begin(['method' => 'GET']); ?>

            <div class="row">

                <div class="col-lg-4">
                    <div class="form-group doctor">
                        <label>Врач:</label><br>
                        <?= Html::input('text', 'OrdersToExportSearch[keys]', '', ['id' => 'demo-input', 'style' => 'width:100px']) ?>
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
        </div>

    </div>

    <div class="customer-reports-index">
        <p>
            <?php
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                //'encoding' => 'windows-1251',
                'target' => ExportMenu::TARGET_POPUP,
                'showConfirmAlert'=>true,
                'timeout' => 1000,
                'exportConfig' => [
//                    ExportMenu::FORMAT_CSV => true,
//                    ExportMenu::FORMAT_HTML=> true,
//                    ExportMenu::FORMAT_TEXT => true,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_EXCEL => false,
                    ExportMenu::FORMAT_EXCEL_X => false,
                ],
            ]);
            ?>
        </p>
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'striped' => false,
            'export' => false,
            'panel'=>[
                'type'=>'primary',
                'heading'=>'Отчет по перевесам врачей'
            ],
            'columns' => [
                ['class'=>'kartik\grid\SerialColumn'],
                [
                    'width'=>'150px',
                    'header' => 'Врач',
                    'value' => 'logins.Name',
                    'format' => 'html',
                    'group'=> true,
                    'groupFooter'=>function ($model, $key, $index, $widget) {
                        return [
                            'mergeColumns'=>[[1,4]],
                            'content'=>[
                                4=>'Итого:',
                                5=>GridView::F_SUM,
                            ],
                            'contentFormats'=>[
                                5=>[ 'format' => 'number', 'decimals' => 2, 'thousandSep' => ' '],
                            ],
                            'contentOptions'=>[
                                1=>['style'=>'text-align:right'],
                                5=>['style'=>'text-align:right'],
                            ],
                            'options'=>[
                                'class'=>'success',
                                'style'=>'font-weight:bold; background-color: #f9f9f9;'
                            ]
                        ];
                    },
                ],
                [
                    'header' => 'Отделение',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'attribute'=>'OrderKontragentID',
                    'width'=>'100px',
                    'pageSummaryOptions'=>['class'=>'text-right'],
                ],
                [
                    'header' => 'Дата заказа',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'value' => 'DateReg',
                    'format' =>  ['date', ' dd.MM.Y HH:mm:ss'],
                    'width'=>'100px',
                    'pageSummaryOptions'=>['class'=>'text-right']
                ],
                [
                    'header' => 'Номер заказа',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'value' => function($data){
                        return Html::a(
                            $data->order_num,
                            'https://office.gemotest.ru/inputOrder/inputMain_test.php?oid='.$data->order_num,
                            [
                                'title' => $data->order_num,
                                'target' => '_blank'
                            ]
                        );
                    },
                    'format' => 'raw',
                    'attribute'=>'order_num',
                    'width'=>'250px',
                    'pageSummaryOptions'=>['class'=>'text-right']
                ],
                [
                    'header' => 'Стоимость',
                    'vAlign'=>'middle',
                    'group'=> false,
                    'value'=> 'OrderAllCost',
                    'width'=>'170px',
                    'pageSummaryOptions'=>['class'=>'text-right'],
                ],
             ]
        ]);
        ?>
    </div>
</div>