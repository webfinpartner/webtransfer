$(document).ready(function () {

//    $(".maskPhone_new").mask('99999999999? x99',{placeholder:"0079001112233"});
//     $(".maskPhone_new").mask('999999999999')
	//$(".maskKPD").mask('999-999');

	$('.maskMoneyCustom').maskMoney({
		prefix: '',
		allowNegative: false,
		thousands: '',
		decimal: '',
		affixesStay: false,
		precision: 0
	}).trigger('mask')


	$(".maskPrice,  input[name='n_vklad']").maskMoney({thousands: ' ', decimal: ',', precision: 0}).trigger('mask')
	$(".maskPrice2").maskMoney({thousands: ' ', decimal: ',', precision: 2}).trigger('mask')
	$(".maskPrice,  input[name='summa'] input[name='summ']").maskMoney({
		thousands: ' ',
		decimal: ',',
		precision: 0
	}).trigger('mask')
	//$('select').selectBox();
	$('a[rel=fancybox]').fancybox({});

	$(function () {
		$('#tabs li').click(function () {
			$('.tab_content').addClass('hidden');
			$($(this).find('a').attr('href')).removeClass('hidden');

			$('#tabs li').removeClass('active');
			$(this).addClass('active');

			return false;
		});
	});
	$('.loginenter').click(function () {

		$("#login").fadeIn();
	}).css('cursor', 'pointer');

	$('.wt-login').click(function () {
		$("#wt-login-form").fadeIn();
	}).css('cursor', 'pointer');

	$('.investnow').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('.bonusnow').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('.aboutnow').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('#home_credit').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('#qiwi_trigger').click(function () {
		$("#qiwi_payment").fadeIn();
	}).css('cursor', 'pointer');

	$('#w1_trigger').click(function () {
		$("#w1_payment").fadeIn();
	}).css('cursor', 'pointer');

	$('.mc_trigger').click(function () {
		$("#mc_payment").fadeIn();
	}).css('cursor', 'pointer');

	$('#mc_trigger').click(function () {
		$("#mc_payment").fadeIn();
	}).css('cursor', 'pointer');

	$('#send_trigger').click(function () {
		$("#send_friend").fadeIn();
	}).css('cursor', 'pointer');

	$('#bank_trigger').click(function () {
		$("#bank_payment").fadeIn();
	}).css('cursor', 'pointer');

	$('#credit_info1').click(function () {
		$("#credit_info2").fadeIn();
	}).css('cursor', 'pointer');

	$('#home_credit2').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('.creditnow').click(function () {
		$("#social_loginss").fadeIn();
	}).css('cursor', 'pointer');

	$('.phoner').click(function () {
		$("#callback").fadeIn();
	})

	$('.pressalink').click(function () {
		if ($("#pressa").css('display') == 'block') {
			$(this).removeClass('active_presslink')
			$("#pressa").fadeOut();
		} else {
			$(this).addClass('active_presslink')
			$("#pressa").fadeIn();
		}
	})
	$('.tochkilink').click(function () {
		if ($("#tochkividachi").css('display') == 'block')
			$("#tochkividachi").fadeOut();
		else
			$("#tochkividachi").fadeIn();
		$("#tochkividachi li").css({display: 'inline-block', width: '33%', textAlign: 'left'})
	})
	$('.close').click(function () {
		$(".popup").fadeOut();
		$('.parentFormvalidate').css('display', "none")
	})
	$('.vivod13').click(function () {
		$("#tariffs_vivod").fadeOut();
	})
})

Date.prototype.toLocaleFormat = function (format) {
	var f = {
		y: this.getYear() + 1900,
		m: month(this.getMonth() + 1),
		d: this.getDate(),
		H: this.getHours(),
		M: this.getMinutes(),
		S: this.getSeconds()
	}
	for (var k in f)
		format = format.replace('%' + k, f[k] < 10 ? "0" + f[k] : f[k]);
	return format;
};

function month(n) {
	var arr = {
		'1': 'Января',
		'2': 'Февраля',
		'3': 'Марта',
		'4': 'Апреля',
		'5': 'Мая',
		'6': 'Июня',
		'7': 'Июля',
		'8': 'Августа',
		'9': 'Сентября',
		'10': 'Октября',
		'11': 'Ноября',
		'12': 'Декабря'
	}
	return arr[n];
}

function summ_format(c) {
	return c.toString().replace(/(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g, "$1 ")
}
