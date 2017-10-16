<?php
/**
 * Created by PhpStorm.
 * User: m.shubin
 * Date: 08.06.2015
 * Time: 12:58
 */

// Add CSS
$this->registerCssFile('/css/main.css');
$this->registerCssFile('/css/doctor_report.css');
$this->registerCssFile('/css/token-input.css');

// Add JS
//$this->registerJsFile('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
$this->registerJsFile('/js/jquery.tokeninput.js');
$this->registerJsFile('/js/jquery.redirect.js');

$this->title = 'Гемотест : просмотр \'Отчёт по перевесам\'';

?>
<style>
	table{
		background: #ffffff;
	}
	select {
		width: 230px;
		font-family: Arial;
		font-size: 12px;
		border: 1px solid #acacac;
	}
	input {
		width: 150px;
		font-family: Arial;
		font-size: 12px;
		border: 1px solid #acacac;
	}
</style>

<script type="text/javascript">

</script>

<table border='0' width='100%' align="center">
	<tr>
		<td style="background:#ededed;"></td>
		<td style="width:1024px;padding:0px;">
			<table cellpadding='8' cellspacing='0' border='0' width='1024px' align="center" bgcolor="#FFFFFF">
				<tr>
					<td class="mainHeadTbl_TdWithSearch" colspan="3" id="head_filter">
                        <table cellpadding='0' cellspacing='0' border='0' width='1024px' align="center" bgcolor="#FFFFFF">
                            <tr>
                                <td>
                                    <table cellpadding='0' cellspacing='0' border='0' width='100%' align="center" bgcolor="#FFFFFF">
                                        <tr>
                                            <td width="100px"><span class="title">Врач</span></td>
                                            <td>
                                                <input type="text" id="demo-input" name="blah"/><br>
                                                <div id="searchForm"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding='0' cellspacing='0' border='0' width='100%' align="center" bgcolor="#FFFFFF">
                                        <tr>
                                            <td width="100px"><span class="title">Дата с</span></td>
                                            <td width="250px"><input type="date" id='filter_date_from' class='filter' placeholder="Дата с"/></td>
                                        </tr>
                                        <tr>
                                            <td><span class="title">Дата по</span></td>
                                            <td><input type="date" id='filter_date_to' class='filter' placeholder="Дата по"/></td>
                                            <td >
                                                <div class="flipthis-wrapper">
                                                    <a href='#' id='btn_action_get_report' class='btn-small bg-green underline' style='width:120px;'>Сформировать</a>
                                                </div>
                                                <a href="#" id="btn_action_get_report_xls"><img src="../img/excel_20.png" border="0"></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
				<tr>
					<td class="mainHeadTbl_TdWithBlc" colspan="4">
						<div  id='box'>
							<blockquote id="blc" class="blockquote_blc">ожидаю действий...</blockquote>
						</div>
					</td>
				</tr>
				<tr>
					<td align="left" nowrap>
					</td>
					<td align="left" nowrap style="font-family:Arial;font-size:20px;padding:15px;padding-left:3px;color:#4f4f4f;" width="90%" nowrap>
                        Отчёт по перевесам
					</td>

					<td align="right" style="padding-right:3px;vertical-align:middle" >
					</td>
					<td align="left" width="33px">
					</td>
				</tr>
				<tr>
					<td id="report_container" colspan=3 valign="top">
						<p style='font-family:Arial;font-size:12px;text-align: center;'><img src='../img/find2.png' width='32px' height='32px' style='vertical-align: middle; border:0px;'><br>
							данные не определены: измените условия поиска и повторите попытку...</p>
					</td>
				</tr>
				<tr>
					<td valign="top" style="padding:5px;padding-left:100px; vertical-align:top;white-space: nowrap; height:93px; background:url(/img/gl25.gif) right top repeat-x;" wrap colspan="4"></td>
				</tr>
				<tr>
					<td colspan=4 height="1px;">
						<?php //include('../templates/footer.php'); ?>
					</td>
				</tr>
			</table>
		</td>
		<td style="background:#ededed"></td>
	</tr>
</table>
<div style="display: none">
	<span id="lng_all">Все</span>
	<span id="lng_status_new">Новые</span>
	<span id="lng_status_finished">Исполненые</span>

	<span id="lng_status_new_one">Новый</span>
	<span id="lng_status_finished_one">Исполненый</span>

	<span id="lng_med_report_table_head_patient">Пациент</span>
	<span id="lng_med_report_table_head_fullname">ФИО</span>
	<span id="lng_med_report_table_head_birthdate">Дата рождения</span>
	<span id="lng_med_report_table_head_telephone">Телефон</span>
	<span id="lng_med_report_table_head_register">Пользователь, осуществивший запись</span>
	<span id="lng_med_report_table_head_parthner">Наименование ЛО</span>
	<span id="lng_med_report_table_head_order_date">Дата обследования</span>
	<span id="lng_med_report_table_head_doctor">Врач</span>
	<span id="lng_med_report_table_head_service">Услуга</span>
	<span id="lng_med_report_table_head_status">Статус</span>
	<span id="lng_med_report_table_head_price">Цена</span>
	<span id="lng_med_report_table_head_discount">Скидка</span>
	<span id="lng_med_report_table_head_cost">Итого</span>
	<span id="lng_med_report_table_head_cost_uppercase">ИТОГО</span>
</div>

<?php
$js = <<< JS
    $("#demo-input").tokenInput("report/ajax-doctor-list", {
        hintText: "Введите код или ФИО врача",
        noResultsText: "Врач не найден!",
        searchingText: "Выполняется поиск.."
    });

    $("#btn_action_get_report").click(function () {
        actionReport();
    });
    
    $("#btn_action_get_report_xls").click(function () {
        actionReport('file');
    });

    function actionReport(operation) {

        var keys = $("#searchForm").siblings("input[type=text]").val();
        var date_from = $("#filter_date_from").val();
        var date_to = $("#filter_date_to").val();

        $.ajax({
            type: 'GET',
            url: '/index.php?r=report/ViewDoctorGemotest',
            data: {
                keys: keys,
                date_from: date_from,
                date_to: date_to
            },
            beforeSend: function (){
                blockedRefreshButton = true;
            },
            success: function (data){
                if (data !== '0' && data !== ''){
                    $('td#report_container').html(data);
                    $('#blc').html("данные обновлены");
                } else {
                    this.error();
                    blockedRefreshButton = false;
                }
            },
            error: function (){
                var html = "<table width='100%'>" +
                    "<thead></thead>" +
                    "<tfoot></tfoot>" +
                    "<tbody><tr><td colspan=3 style='text-align:center'>" +
                    "<font style='font-family:Arial;font-size:12px;'>&nbsp;<br>" +
                    "<img src='../img/find2.png' width='32px' height='32px' style='vertical-align: middle; border:0px;'><br>" +
                    "данные не определены: измените условия поиска и повторите попытку...</font><br>&nbsp;</td></tr></tbody></table>";

                $('td#report_container').html(html);
                $('#blc').html("данные не определены");
                blockedRefreshButton = false;
            }
        });

        if (operation === 'file') {
            $.redirect('/index.php?r=report/ViewDoctorGemotest', {
                'keys': keys,
                'date_from': date_from,
                'date_to': date_to
            },
            "POST", "_blank");
        }
    }
JS;
$this->registerJs($js);