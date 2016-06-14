$(function() {
	$(document).on('change', '#select-sum', onChange).on('keyup', '#select-sum', onChange);
	$(document).on('change', '#select-period', onChange).on('keyup', '#select-period', onChange);
	$(document).on('change', '#select-percent', onChange).on('keyup', '#select-percent', onChange);
	$(document).on('change', '#garant, #standart,#bonus, #direct, #partner_fund', onChange);

    if( $('.message.arbitration').length != 0 ){
        var arbitr_timer = setInterval(function(){
            var val = $('#arbitration-timer').attr('data-time'),
                var_ar = val.split(':'),
                h = parseInt( var_ar[0] ),
                m = parseInt( var_ar[1] ),
                s = parseInt( var_ar[2] );
            s--;
            if( s <= 0 ){
                s = 59; m--;
            }
            if( m <= 0 ){
                m = 59; h--;
            }
            if( h <= 0 ){
                clearInterval(arbitr_timer);
                $('.message.arbitration').hide();
            }
            h += '';m += '';s += '';
            if( h.length == 1 ) h = 0+h;
            if( m.length == 1 ) m = 0+m;
            if( s.length == 1 ) s = 0+s;
            val = h+':'+ m +':'+ s;

            $('#arbitration-timer').attr('data-time', val);
            $('#arbitration-timer').html( val );
        },1000);

    }

    if (window.credit_home != true) {
        var val_t = $.cookie('form_time'),
                        val_s = $.cookie('form_summ'),
                        val_percent = $.cookie('form_percent');

        if (val_percent == null)
                val_percent = '0.1'

        $("#select-percent [value='" + val_percent + "']").attr("selected", "selected");
        if (val_t != null)
                $("#select-period [value='" + val_t + "']").attr("selected", "selected");
        if (val_s != null)
                $("#select-sum [value='" + val_s + "']").attr("selected", "selected");
    }
    else
    {
        $('#home_credit,#home_credit2').click(function() {
                $.cookie('form_time', $('#select-period').val());
                $.cookie('form_summ', $('#select-sum').val());
                $.cookie('form_percent', $('#select-percent').val());
        });
    }

    onChange();
});

function onChange() {
    if (window.credit_home == true)
        change_home();
    else
        change_form();
}

function get_fee(){
        var time = $('#select-period').val();
    var summ = parseInt($('#select-sum').val());
    var percent = parseFloat($('#select-percent').val());
    var pa = $('#payment_account option:selected').val();
    var is_ccreds_fee = $('#is_ccreds_fee').prop("checked");

    var pribil = summ * (percent / 100 * time)

    var out_sum = summ + pribil;
    var minus = 0
    var garant = 0
    if ($('#garant').prop("checked"))
    {
		if(pa == '3'){
			garant = 20;
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus;
		} else {
			if (time <= 10)
				garant = 50;
			else if (time <= 20)
				garant = 45;
			else if (time <= 30)
				garant = 40;
			//else  if(time>28) garant= 15
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus
		}
    }
    else
    {
        garant = 10
        minus = pribil * (garant / 100)
        out_sum = out_sum - minus
    }

    return minus;
    
}

function change_form() {
    var time = $('#select-period').val();
    var summ = parseInt($('#select-sum').val());
    var percent = parseFloat($('#select-percent').val());
    var pa = $('#payment_account option:selected').val();
    var ccreds_perc = $('#ccreds_perc').val();
    var is_ccreds_fee = $('#is_ccreds_fee').prop("checked");
    var is_usd6creds_fee = $('#is_usd6creds_fee').prop("checked");
    var is_usd2_creds_fee = $('#is_usd2_creds_fee').prop("checked");

    var pribil = summ * (percent / 100 * time)

    var out_sum = summ + pribil;
    var minus = 0
    var garant = 0
    if ($('#garant').prop("checked"))
    {
		if(pa == '3'){
			garant = 20;
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus;
		} else {
			if (time <= 10)
				garant = 50;
			else if (time <= 20)
				garant = 45;
			else if (time <= 30)
				garant = 40;
			//else  if(time>28) garant= 15
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus
		}
    }
    else
    {
        garant = 10
        minus = pribil * (garant / 100)
        out_sum = out_sum - minus
    }


    $('#ohearth').hide();
    $('#your_invest').val("$ " + summ_format((Math.round((summ + 0.00001) * 100) / 100)))
    $('#your_invest_plus').val("$ " + summ_format((Math.round((pribil + 0.00001) * 100) / 100)))
    if ( is_ccreds_fee ){
        out_sum = out_sum + minus;
        minus = minus*ccreds_perc;
        $('#your_invest_minus').val("$ 0");
        $('#your_ccreds_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant*ccreds_perc + "%)")
        $('#your_ccreds_invest_minus').show();
    } else if(is_usd6creds_fee){
        $('#your_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant + "%)")        
        $('#your_ccreds_invest_minus').val('')
        $('#your_ccreds_invest_minus').hide();
    } else if(is_usd2_creds_fee){
        $('#your_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant + "%)")        
        out_sum += minus;
        $('#your_ccreds_invest_minus').val('')
        $('#your_ccreds_invest_minus').hide();
        $('#ohearth').show();
        
    } else {
        $('#your_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant + "%)")        
        $('#your_ccreds_invest_minus').val('')
        $('#your_ccreds_invest_minus').hide();
    }
    
    if (is_ccreds_fee )
        $('#your_ccreds_invest_minus').show();
    
   
    if( typeof out_sum_garant != 'undefined'){
        if( out_sum <= out_sum_garant ) $('#img_garant').show();
        else $('#img_garant').hide();
    }

    out_sum = summ_format((Math.round((out_sum + 0.00001) * 100) / 100));

    if( $("#summ_to_return").length !=0 ){
        if($("#form_invest").length)
            $("#summ_to_return").val("$ " + out_sum);
        else $("#summ_to_return").val("$ " + (summ + pribil));
    }
}

function change_home() {
    var time = $('#select-period').val();
    var summ = parseInt($('#select-sum').val());
    var percent = parseFloat($('#select-percent').val());
    var percent2 = $('#your_per').attr('name');
    $('.time').text(time + ' дней')
    $('.summ').text('$ ' + summ_format(summ))

    var out_sum = summ + summ * (percent2 / 100 * time);
    out_sum = summ_format(Math.round((out_sum + 0.00001) * 100) / 100);
    $("#your_per").text("$ " + out_sum)

    out_sum = summ + summ * (percent / 100 * time);
    out_sum = summ_format(Math.round((out_sum + 0.00001) * 100) / 100);
    $("#your_sum").text("$ " + out_sum);

    var next = 1000 * 60 * 60 * 24 * time;
    var now = new Date();
    var nd = new Date(now.getTime() + next);
    $('#next_date').text(nd.toLocaleFormat("%d %m, %y"))

    $('.change_percent').text($('#select-percent  :selected').text());

}

