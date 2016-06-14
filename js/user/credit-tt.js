$(function () {

	$("#priceSlider").ionRangeSlider({
		min: 50,                      // минимальное значение
		max: 1000,                     // максимальное значение
		from: 500,                    // предустановленное значение ОТ
		onChange: function () {
			if (window.credit_home == true)change_home(); else change_form()
		},       // предустановленное значение ДО
		type: "single",                 // тип слайдера
		step: 10,                      // шаг слайдера
		prefix: "$ ",               // постфикс значение
		hasGrid: true                   // показать сетку
	});
	$("#timeSlider").ionRangeSlider({
		min: 3,                         // минимальное значение
		max: 30,                        // максимальное значение
		from: 10,                       // предустановленное значение ОТ
		onChange: function () {
			if (window.credit_home == true)change_home(); else change_form()
		},        // предустановленное значение ДО
		type: "single",                 // тип слайдера
		step: 1,                        // шаг слайдера
		postfix: " дней",               // постфикс значение
		hasGrid: true                   // показать сетку
	});

	if (window.credit_home != true) {
		val_t = $.cookie('form_time');
		val_s = $.cookie('form_summ');
		val_percent = $.cookie('form_percent');

		if (val_percent == null)
			val_percent = '0.1'


		$('#garant, #standart').change(change_form)
		$("select[name=percent] [value='" + val_percent + "']").attr("selected", "selected");
		if (val_t != null)$("#timeSlider").ionRangeSlider("update", {from: val_t});
		if (val_s != null)$("#priceSlider").ionRangeSlider("update", {from: val_s});
		$('select[name=percent]').change(change_form)
	}
	else {
		$('#home_credit,#home_credit2').click(function () {
			$.cookie('form_time', $('#timeSlider').val());
			$.cookie('form_summ', $('#priceSlider').val());
			$.cookie('form_percent', $('select[name=percent]').val());

		})
		$('select[name=percent]').change(change_home)
	}
});
function change_home() {
	var time = $('#timeSlider').val();
	var summ = parseInt($('#priceSlider').val());
	var percent = parseFloat($('select[name=percent]').val());
	var percent2 = $('#your_per').attr('name');
	$('.time').text(time + ' Дней')
	$('.summ').text('$ ' + summ_format(summ))

	var out_sum = summ + summ * (percent2 / 100 * time);
	out_sum = summ_format((Math.round((out_sum + 0.00001) * 100) / 100));
	$("#your_per").text("$ " + out_sum)

	out_sum = summ + summ * (percent / 100 * time);
	out_sum = summ_format((Math.round((out_sum + 0.00001) * 100) / 100));
	$("#your_sum").text("$ " + out_sum);

	var next = 1000 * 60 * 60 * 24 * time;
	var now = new Date();
	var nd = new Date(now.getTime() + next);
	$('#next_date').text(nd.toLocaleFormat("%d %m, %y"))

	$('.change_percent').text($('select[name=percent]  :selected').text());

}

function change_form() {
	// time=$('#timeSlider').val();
	// pribil=summ*(percent/100*time);
	var minus = 0,
	garant = 0,
	time = document.getElementById('razn_mks').value,
	summ = parseInt($('#priceSlider').val()),
	percent = parseFloat($('select[name=percent]').val());
	var out_sum = summ * Math.pow((percent / 100 + 1), time);
	var pribil = out_sum - summ;

	$('#your_invest').val("$ " + summ_format((Math.round((summ + 0.00001) * 100) / 100)))						// Столько вы вложили
	$('#your_invest_plus').val("$ " + summ_format((Math.round((pribil + 0.00001) * 100) / 100)))					// Ваша прибыль
	$('#your_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant + "%)")
	out_sum = summ_format((Math.round((out_sum + 0.00001) * 100) / 100));
	$("#summ_to_return").val("$ " + out_sum)											// Вы получите
}