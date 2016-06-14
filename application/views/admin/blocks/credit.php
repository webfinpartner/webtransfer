<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="form_credit" method="post" action="/opera/credit/save/<?=$item->id?>" class="form" onSubmit="return (false)">


<div class="formRow">
	<label>Сумма кредита:</label>
	<div class="formRight">$ <input type="text" name="summa" id="summa2" class="validate[required] maskPrice" value="<?=$item->summa?>" /></div>
	<div class="clear"></div>
</div>
<div class="formRow">
	<label>Процент:</label>
	<div class="formRight"> %<input type="text" name="percent" id="percent2" class="validate[required]" value="<?=$item->percent?>" /></div>
	<div class="clear"></div>
</div>
<div class="formRow">
	<label>Срок кредита: </label>
	<div class="formRight">дней<input type="text" name="time" class="validate[required,custom[onlyNumberSp]]" id="time2" value="<?=$item->time?>" /> </div>
	<div class="clear"></div>
</div>
<div class="formRow">
	<label>Сумма возврата: </label>
	<div class="formRight"> $ <input type="text" class="maskPrice" name="summ_return" disabled="disabled" value="<?=$item->out_summ?>" /></div>
	<div class="clear"></div>
</div>
<div class="formRow">
	<label>Payment: </label>
	<div class="formRight">
	<? //	$payments     = (array)getPaymentOrger($item->payment);
//		$paymentsType = paymentArray();
//		foreach($paymentsType as $pay)
//		{
//			echo '<input type="checkbox" name="payment[]" '.checkPaymentAdmin($payments,$pay).' />'.$pay.'<br/>';
//		}
//		echo '<input type="text" name="payment[]" class="other_pay_input" value="'.end($payments).'"/>';
	?>
	</div>
	<div class="clear"></div>
</div>
<div class="formRow">
	<label>Статус:</label>
	<div class="formRight"><? $this->load->helper('form'); echo form_dropdown('state', check_status(), $item->state)?></div>
	<div class="clear"></div>
</div>

<div style="margin-top:10px" id="form"></div>
<center id="answer2" style="display:none">Изменения были сохранены</center>
	<center>
		<a class="wButton greenwB ml15 m10" onclick="send_form2(); return (false)" title="" href="#">
			<span>Сохранить</span>
		</a>
	</center>
</form>

</fieldset>
<script>
$(function(){
	$('#form_credit input').keyup(function()
	{

		summa = parseInt($('#form_credit input[name="summa"]').val().replace(/\s/g, ''))
		time = parseInt($('#form_credit input[name="time"]').val())
		percent = parseFloat($('#form_credit input[name="percent"]').val())
		per= percent/100*time;
		out_sum= summa +  summa*per;

		out=Math.round(out_sum)
		if(out==NaN)out="";

		$('#form_credit input[name="summ_return"]').val(out).trigger('mask')
	})
})


function send_form2()
{
	$("#form_credit").ajaxSubmit({
	  url: "/opera/credit/save/<?=$item->id?>",
	  success: function(){$('#answer2').fadeIn(); setTimeout(function(){$("#answer2").fadeOut()},4000);}
	});
}
</script>