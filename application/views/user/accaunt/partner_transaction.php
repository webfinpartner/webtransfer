<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
<?=_e('accaunt/partner_transaction_1')?>
</div>
 <br/>
 <h3><?=_e('accaunt/partner_transaction_2')?></h3>
  <table cellspacing="0" class="payment_table">
	  <thead><tr><th style='text-align:center;'><?=_e('accaunt/partner_transaction_3')?></th><th style='text-align:center;'><?=_e('accaunt/partner_transaction_4')?></th><th><?=_e('accaunt/partner_transaction_5')?></th><th style='text-align:center;'><?=_e('accaunt/partner_transaction_6')?></th></tr></thead>
	  <tbody>
		   <? if(!empty($transactions)){?>
  <?  foreach($transactions as  $item)
  {
	  echo "<tr rel='$item->id_invited'>
		    <td style='text-align:center;'>".date_formate_view($item->date)."</td>
			<td style='text-align:center;'>$item->id</td>
			<td>".transactionsPartnerType($item->type)."</td>
			<td style='text-align:right;padding-right:30px;'>".price_format_double($item->price)."</td>

			</tr>";
  }?>
		  <?}?>
	  </tbody></table>
<br/><br/>
 <h3><?=_e('accaunt/partner_transaction_7')?></h3>


  <table cellspacing="0" class="payment_table">
	  <thead><tr><th style='text-align:center;'><?=_e('accaunt/partner_transaction_8')?></th><th style='text-align:center;'><?=_e('accaunt/partner_transaction_9')?></th><th><?=_e('accaunt/partner_transaction_10')?></th><th style='text-align:center;'><?=_e('accaunt/partner_transaction_11')?></th></tr></thead>
	  <tbody>
		  <? if(!empty($transactions_wait)){?>
  <?  foreach($transactions_wait as  $item)
  {
	  echo "<tr rel='$item->id_invited'>
		    <td style='text-align:center;'>".date_formate_view($item->date)."</td>
			<td style='text-align:center;'>$item->id</td>
			<td>"._e('accaunt/partner_transaction_12')."<br />
			"._e('accaunt/partner_transaction_13')." $item->date_kontract<br />
			"._e('accaunt/partner_transaction_14')." $item->out_time<br />
			"._e('accaunt/partner_transaction_15')." $ ".partnerPay($item->income,$item->time)."</td>
			<td style='text-align:right;padding-right:30px;'>".accaunt_debit_status($item->state)."</td>

			</tr>";
  }?>
		 <?}?>
	  </tbody></table>


 	<div id="popup_invest">
			<div onclick="$('#popup_invest').hide('slow'); " class="close"></div>
			<div class="content"></div>

		</div>
<script>

	$('table.payment_table tr').on('click',function(){
		if($(this).attr('rel') == 0) return
		$.get(site_url + '/account/ajax_user/get_user_data/2/'+$(this).attr('rel')+'/0' ,  function(data){
			type ='2';
			if(type==1)
				wrap = $('#popup_credit');
			else
				wrap = $('#popup_invest');

			wrap.show('slow');
			wrap.find('.content').html(data);
		})
	})



</script>