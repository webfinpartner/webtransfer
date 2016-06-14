$(function () {

	$("#priceSlider").ionRangeSlider({
		min: 1000,                        // минимальное значение
		max: 30000,                      // максимальное значение
		from: 15000,                       // предустановленное значение ОТ
		onChange: function () {
			if (window.credit_home == true)change_home(); else change_form()
		},       // предустановленное значение ДО
		type: "single",                 // тип слайдера
		step: 500,                       // шаг слайдера
		postfix: " руб.",              // постфикс значение
		hasGrid: true                 // показать сетку
	});
	$("#timeSlider").ionRangeSlider({
		min: 3,                        // минимальное значение
		max: 30,                      // максимальное значение
		from: 10,                       // предустановленное значение ОТ
		onChange: function () {
			if (window.credit_home == true)change_home(); else change_form()
		},                   // предустановленное значение ДО
		type: "single",                 // тип слайдера
		step: 1,                       // шаг слайдера
		postfix: " дней",              // постфикс значение
		hasGrid: true                  // показать сетку
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
	time = $('#timeSlider').val();
	summ = parseInt($('#priceSlider').val());
	percent = parseFloat($('select[name=percent]').val());
	percent2 = $('#your_per').attr('name');
	$('.time').text(time + ' дней')
	$('.summ').text(summ_format(summ) + ' руб.')

	out_sum = summ + summ * (percent2 / 100 * time);
	out_sum = summ_format(Math.round(out_sum));
	$("#your_per").text(out_sum + " руб.")

	out_sum = summ + summ * (percent / 100 * time);
	out_sum = summ_format(Math.round(out_sum));
	$("#your_sum").text(out_sum + " руб.");

	next = 1000 * 60 * 60 * 24 * time;
	now = new Date();
	nd = new Date(now.getTime() + next);
	$('#next_date').text(nd.toLocaleFormat("%d %m, %y"))

	$('.change_percent').text($('select[name=percent]  :selected').text());

}

function change_form() {
	time = $('#timeSlider').val();
	summ = parseInt($('#priceSlider').val());

	percent = parseFloat($('select[name=percent]').val());
	pribil = summ * (percent / 100 * time)
	out_sum = summ + pribil;
	minus = 0
	garant = 0
	if ($('#garant').prop("checked")) {
		if (time <= 10)garant = 50
		else if (time <= 19) garant = 45
		else if (time <= 30) garant = 40
		//else  if(time>28) garant= 15
		minus = pribil * (garant / 100)
		out_sum = out_sum - minus
	}
	else {
		garant = 10
		minus = pribil * (garant / 100)
		out_sum = out_sum - minus
	}
	$('#your_invest').val(summ_format(Math.round(summ)) + " руб.")
	$('#your_invest_plus').val(summ_format(Math.round(pribil)) + " руб.")
	$('#your_invest_minus').val(summ_format(Math.round(minus)) + " руб. (-" + garant + "%)")


	out_sum = summ_format(Math.round(out_sum));
	$("#summ_to_return").val(out_sum + " руб.")
}
