<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?  if(!empty($item)){
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#open_transaction').click(function(){
		$(this).parent().find('#transaction').toggle()
	})
	$(".dateMask").mask("9999 99 99");
	$('#open_platej').click(function(){
		$('.platej').toggle()
	})
});
</script>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($item->type==1)?"Взятый Кредит":"Данный кредит"?></h6>
                         </div>
  <fieldset style="padding: 10px">
<style>
.td_left{display: inline-block; width:115px}
</style>
<span class="td_left">Имя:</span> <?=$user->name?><br/>
<span class="td_left">Фамилия: </span> <?=$user->sername?><br/>
<span class="td_left">Отчество: </span> <?=$user->patronymic?><br/>
<span class="td_left">Телефон:</span>  <?=$user->phone?><br/>
<br/>
<span class="td_left">Контракт:</span>  <?=$item->kontract?><br/>
<span class="td_left">Дата контракта:</span>  <?=  date_formate_view($item->date_kontract)?><br/>
<span class="td_left">Дата возврата:</span>  <?=  date_formate_view($item->out_time) ?><br/>
<span class="td_left">Сумма возврата:</span> $ <?=price_format_double($item->out_summ)?><br/>
<span class="td_left">Срок:</span>  <?=$item->time ?> дней <br/>
<span class="td_left">Сумма:</span>  $ <?=price_format_double($item->summa)?><br/>
<span class="td_left">Процент:</span>  <?=$item->percent?>%<br/>
<span class="td_left">Payment:</span>  <?=creditPayment($item->payment)?><br/>
<span class="td_left">Заработок:</span> $ <?=price_format_double($item->income)?><br/>

<span class="td_left">Загрузка:</span> <a href="/opera/credit/view_kontract/<?=$item->kontract?>"><img class="pdf_image"   src="/images/PDF-Ripper.gif" /></a><br/>

<br /><a href="/opera/credit/view/<?=$item->debit?>">Контрагент</a>

<br /><a href="/opera/users/<?=$item->id_user?>">Профиль пользователя</a>
<br /><a id="open_platej" style="cursor: pointer">Регистрация  платежа</a>
<br /><a id="open_transaction" style="cursor: pointer">История транзакций</a>
<div id="transaction" style="margin: -10px; display: none;  margin-top: 0px">
	<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Транзакции  платежей</h6></div>
	<table  class="display dTable">
		<thead><tr><th>Дата</th><th>Сумма</th><th>Метод  платежа</th><th>Примечание</th><th>Дата  изменения</th><th>Исполнитель</th></tr> </thead><tbody>
		<? if(!empty($transaction)){

			foreach($transaction as  $val){
				echo "<tr><td>".date_formate_view($val->date)."</td> <td>".price_format_double($val->summa)."</td><td>$val->payment</td><td>$val->note</td><td>$val->date_change</td><td>$val->performer</td></tr>";
			}

			}?>
	</tbody>
	</table>

</div>

                </fieldset>
                </div>

<form id="validate" class="form platej" style="display: none" action="<?php base_url()?>opera/invest/<?="add_transaction/$item->id"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Регистрация  платежа</h6>
                    </div>
                    <div class="formRow">
                        <label>Дата  получения:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="date" class="validate[required,custom[date] dateMaskMy" name="date" value=""></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Сумма:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required] maskPrice" id="summa"  name="summa"  value=""></div>
                        <div class="clear"></div>
                    </div>
	                    <div class="formRow">
                        <label>Метод  платежа:<span class="req">*</span></label>
                        <div class="formRight"><select name="payment"><option value="Банковсний перевод">Банковсний перевод</option><option value="Наличные">Наличные</option></select></div>
                        <div class="clear"></div>
                    </div>
                      <div class="formRow">
                        <label>Примечание:</label>
                        <div class="formRight"><input type="text"  class="" id="note"  name="note"  value=""></div>
                        <div class="clear"></div>
                    </div>


  </div>
            </fieldset>


	<center>
		<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false) " title="" href="#"><span>Сохранить</span></a>
	</center>

			</form>

                <?}?>
