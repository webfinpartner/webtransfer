<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
#form_type {
  display: block;
  text-align: right;
  margin-top: -44px;
  margin-bottom: 30px;
}
#form_type select {
    border: 1px solid #999999;
    padding: 0px 5px;
    margin-left: 3px;
    color: #999999;
    width: 300px;
    height: 30px;
    text-align: center;
}
</style>


<? $this->load->view('user/accaunt/card/blocks/tools') ?>



<div id="form_type">
        <form method="POST">
            <select name="card" onchange="this.form.submit()">
                     <?php if (!empty($wtcards)) foreach( $wtcards as $wtcard ){ ?>
                <option value="<?=$wtcard->id?>"<? if($wtcard->id==$wtcard_selected || $wtcard->id == $_REQUEST['selected'] ) echo ' selected="selected"'?>><?=Card_model::display_card_name($wtcard)?></option>
                     <?php } ?>                
            </select>
        </form>
    </div>
    
    
    
<style>
.sts-life {
    text-align: left;
    color: #000;
    clear: both;
    line-height: 19px;
    width: 100% !important;
    font-size: 14px;
}

.hov-stl-s{
	
	float: right;
}

.wth-s{
	width: 270px !important;
}

.color-blue-a-wt{
	color: #FF5100;
}

.transactions-table img {
    max-width: 100px;
    background-size: contain;
    float: left;
}

.transactions-table .images {
    max-width: 100px;
}

.payment_table th {
    padding-left: 8px;
}

.transactions-table td.images{
	width: 100px !important;
}

table.payment_table.transactions-table {
    position: relative;
}
.transactions-table td.images {
    position: absolute;
    top: 0;
    border: 0;
}


p.info-wt-acc{
	font-weight: normal !important;
	font-size: 12px !important;
	line-height: 20px !important;
}
.pay_nets_area {
    width: 305px !important;
}
td.pay_nets_area {
    padding-top: 0;
    position: absolute;
    width: 204px !important;
    padding-left: 15px !important;
    border: 0 !important;
    margin-top: -21px !important;
}
td.pay_nets_area li {
    list-style: none;
}

.transactions-table img {
    max-width: 200px;
    background-size: cover;
    float: left;
    margin-left: -6px !important;
    margin-top: 3px !important;
    width: 200px !important;
}

td.tr-ar1 {
    font-size: 12px;
    padding-left: 0;
}

.transactions-table  td.payment_limits.wth-s {
    border: 0;
}

td.tr-ar1{
	font-size: 12px;
}

p.pp1-wt {
    font-size: 12px !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

table.statics-table-wt {
    margin-top: 4px;
    margin-bottom: 18px;
}

table.statics-table-wt2 {
    margin-top: 4px;
    margin-bottom: 9px;
    width: 100% !important;
}

.hov-stl-s{
	color: #858585;
}


.trs-rt-table li{
	list-style: none;
}

.trs-rt-table li a{
	list-style: none;
}

.clkr {
    border-color: #eee !important;
    background: #eee !important;
    color: #eee !important;
    opacity: 0.2;
    margin-bottom: 18px;
}
.brty-butt {
    background: #007AFF;
    border: 0!important;
    color: #fff;
    padding: 7px 18px;
    margin-top: 9px;
    margin-bottom: 19px;
    border-radius: 4px;
    font-size: 15px;
    clear: both;
    float: right;
    position: relative;
    cursor: pointer;
    right: auto;
    margin-top: 10px;
}

.brty-butt:hover{
	background: #FF5100;
}

.trr-area {
    float: right;
}

.strt-inp {
    height: 17px;
    padding: 0 4px 1px;
    border: 1px solid #E4E4E4;
}


div#ui-datepicker-div {
    margin-top: -114px !important;
}
.ui-widget-header {
    border: 0;
    background: #FF5100;
    color: #ffffff;
    font-weight: bold;
}


.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
    border: 1px solid #cccccc;
    background: #f6f6f6 url("/images/ui-bg_glass_100_f6f6f6_1x400.png") 50% 50% repeat-x;
    font-weight: bold;
    color: #2C8EF1;
}

.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
    border: #FF5100 !important;
    background: #FF5100;
    color: #fff;
}

img.ui-datepicker-trigger {
    position: absolute;
    width: 11px;
    margin-top: 5px;
    margin-left: -14px;
}

.ui-datepicker-next.ui-state-hover{
	border-color: #FF5100 !important;
	background: 0 !important;
}

.ui-datepicker-prev.ui-state-hover{
	border-color: #FF5100 !important;
	background: 0 !important;
}

td.tr-ar1 select {
    width: 122px;
    margin-right: 9px;
}

.bll-ss-p{
	cursor: pointer;
}

table.payment_table.transactions-table {
    padding-bottom: 20px;
}

ul.trs-rt-table {
    position: absolute;
    width: 406px;
    margin-left: 295px;
    margin-top: -22px;
}


ul.trs-rt-table li {
    float: right;
    margin-left: 27px;
}

ul.quick-links-area li a{
	padding-left: 10px;
}

ul.quick-links-area li a:before {
    position: absolute;
    content: '>';
    margin-left: -10px;
    font-size: 9px;
    font-weight: bold;
}


button.brty-butt.nht-butt {
    margin-top: -51px;
    margin-right: 126px;
}
</style>    


 <script>
$(document).ready(function() {
    $(".datepickern").datepicker({
            <? if (_e('lang') == 'ru' ){ ?>
            closeText: 'Закрыть',
            prevText: 'Назад',
            nextText: 'Далее',
            monthNamesShort: ["Янв", "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сент", "Окт", "Нояб", "Дек"],
            monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            <? } else { ?>
            closeText: 'Close',
            prevText: 'Prev',
            nextText: 'Next',                    
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            monthNames: ["January","February","March","April","May","June","July","August","September","October","November","December"],
            dayNamesMin: ["Su","Mo","Tu","We","Th","Fr","Sa"],
            <? } ?>        
           showOn: "button",
      buttonImage: "images/icon-cal.png",
      buttonImageOnly: true,
        }).click(function() { $(this).datepicker('show'); });
 });

  jQuery(function($){
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: 'Назад',
		nextText: 'Далее',
                monthNamesShort: ["Янв", "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сент", "Окт", "Нояб", "Дек"],
                monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                //dateFormat: 'dd.mm.yy',
                dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	
});
    $.datepicker.setDefaults($.datepicker.regional['<?=_e('lang')?>']);
  </script>
  
  


<table cellspacing="0" class="payment_table transactions-table">
		<h2 class="sts-life"><? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo 'Webtransfer VISA Virtual'; else echo 'Webtransfer VISA Black'?> XXXXXXXXXXXX<?=substr($card->pan,-4)?></h2>
<tbody>
<tr>
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
</td>
<td class="payment_limits wth-s" style="padding:10px;width:160px;">
<?=$card->name_on_card?><br/>
4665 **** **** <?=substr($card->pan,-4)?> EXP: <?=$card->details['cardDetail']['expiryDate']?><br/>
TYPE: <?=$card->details['cardDetail']['cardType']?> (<?=$card->details['kyc']?>)<br/>
STATUS: <?=$card->details['cardDetail']['cardStatus']?><br/>
BALANCE: $ <?=  price_format_view($card->last_balance, false)?><br/>
<?if ($card->details['kyc']=='LEVEL_1' && 1==0){?>LIMIT: $2,500 (<a href="https://webtransfercard.com/ru/personal/profile?forcewt=true" target="_blank"><?=_e('убрать лимит')?></a>)<? } ?>


</td>








<td class="pay_nets_area">
<h2 class="sts-life"><?=_e('Быстрый переход')?></h2>
<ul class="quick-links-area">
	<li><a href="<?=site_url('account/payment')?>"><?=_e('Пополнить карту')?></a></li>
        <? if($card->details['cardDetail']['cardStatus']=='ACTIVE') { ?>
	<li><a href="#" onclick="on_menu_select(null, <?=$card->id?>, 'block'); return false;"><?=_e('Заблокировать карту')?></a></li>
        <? } ?>
        <? if($card->details['cardDetail']['cardStatus']=='BLOCKED' || $card->details['cardDetail']['cardStatus']=='SUSPENDED') { ?>
	<li><a href="#" onclick="on_menu_select(null, <?=$card->id?>, 'unblock'); return false;"><?=_e('Разблокировать карту')?></a></li>
        <? } ?>        
	<li><a href="<?=site_url('account/my_invest')?>"><?=_e('Дать заём')?></a></li>
        <li><a href="<?=site_url('account/send_money')?>"><?=_e('Перевести')?></a></li>
        <? if($card->card_type==Card_model::CARD_TYPE_PLASTIC) { ?>
	<li><a href="#" onclick="on_menu_select(null, <?=$card->id?>, 'pin'); return false;"><?=_e('Посмотреть пин код')?></a></li>
        <? } ?>
        <? /*
	<li><a href="#" onclick="on_menu_select(null, <?=$card->id?>, 'info'); return false;"><?=_e('Посмотреть детали карты')?></a></li>
         */ ?>
	
        <?if ($card->details['kyc']=='LEVEL_1'){?>
            <li><a href="#" onclick="on_menu_select(null, <?=$card->id?>, 'upgrade_level'); return false;"><?=_e('Убрать лимит')?></a></li>
        <? } ?>
        
</ul>
</td>
</tr>
<style>td.payment_description button{background:#007AFF;border:0!important;color:#fff;padding:7px 18px;margin-top:9px;margin-bottom:19px;border-radius:4px;font-size:15px;position:absolute;right:280px;margin-top:-55px;}td.payment_description button:hover{background:#FF5100;}.summ-div{right:286px;position:absolute;margin-top:10px;}div#notice_box{position:relative;position:fixed;left:50%;margin-left:-247px;top:14%!important;}</style>
</tbody>
</table>




<table cellspacing="0" class="statics-table-wt">
	  <hr class="clkr">
	
<tbody>
<tr>
	<!--
<td class="tr-ar1" style="width:160px;">
Minimum Payment Due:<br>
Last Statement:<br>
Last Payment:<br>
</td>
-->
<form method="GET" id="filter_form">
<input type="hidden" name="card" value="<?=$card->id?>">
<td class="tr-ar1" style="width:400px;">
<div class="trr-div">
<?=_e('Показать транзакции за')?>:
<select name="days" style="width:160px;" onchange="this.form.submit()">
<? foreach( $days as $day){ ?>
<option <? if ($day==$days_selected) echo 'selected="selected"'; ?> value="<?=$day?>"><?=sprintf(_e('Последние %d дней'),$day)?></option>
<? }?>
</select>
<ul class="trs-rt-table">
<li><a class="bll-ss-p"><?=_e('Поиск')?></a></li>
<!--li><a href="#" onclick="exportPDF(); return false;"><?=_e('Скачать')?></a></li-->

</ul>

</div>
</td>

</tr>

</tbody>
</table>
    
    <script>
	  $(document).ready(function(){
    $('.bll-ss-p').click(function(){
        $('.blcks-ss').slideToggle( "" );
    });
});
    function resetFilter(){
        $('input[type=text]').val('');
        $('#filter_form').submit();
        
    }
  </script>
  
  <? $search_block='none'; 
    foreach($input_filter as $f) 
        if (!empty($f)){ 
            $search_block='block';
            break;
        }
   ?>
  
    <div class="blcks-ss" style="display: <?=$search_block?>;">
    <table cellspacing="0" class="statics-table-wt2">
	      <hr class="clkr">
	<thead>
		<tr>

<td class="tr-ar1 form-area-tt" style="width:360px;">
 <label><?=_e('по дате')?></label>
  <label style="margin-left: 10px;"><?=_e('с')?>:<input class="strt-inp datepickern" style="width: 70px; margin-left:5px;" name="date_from" type="text" value="<?=$input_filter['date_from']?>"/></label>
  <label style="margin-left: 10px;"><?=_e('по')?>:<input class="strt-inp datepickern" style="width: 70px; margin-left:5px;" name="date_to" type="text" value="<?=$input_filter['date_to']?>"/></label>
</td>

<td class="tr-ar1 form-area-tt" style="width:360px;">
	<div class="trr-area">
 <label><?=_e('по сумме')?></label>
  <label style="margin-left: 10px;"><?=_e('мин')?>:<input class="strt-inp" style="width: 70px; margin-left:5px;" name="amount_min" type="text" value="<?=$input_filter['amount_min']?>"/></label>
  <label style="margin-left: 10px;"><?=_e('макс')?>:<input class="strt-inp" style="width: 70px; margin-left:5px;" name="amount_max" type="text" value="<?=$input_filter['amount_max']?>"/></label>
	</div>
</td>

</tr>
	</thead>
    </table>
    
<table cellspacing="0" class="statics-table-wt2">	
<tbody>

<tr class="clearfix">
        <button class="brty-butt" type="button" onclick="resetFilter();"><?=_e('Сбросить')?></button>
	<button class="brty-butt nht-butt" type="submit" value="search"><?=_e('Найти')?></button>
        
</tr>

</tbody>
</table>
    </div>
</form>


<?
if($errors || empty($transactions)) {
    echo "<div class='message'>"._e($errors)."</div>";
    ?>

<?
}else{
?>
    <table class="payment_table">
    <thead>
    <tr>
        <th><?=_e('accaunt/transaction_5')?></th>
        <th><?=_e('accaunt/transaction_6')?></th>
        <th><?=_e("Дебит")?></th>
        <th><?=_e("Кредит")?></th>
        <th><?=_e('accaunt/transaction_8')?></th>
    </tr>
    </thead>

        <tbody>


    <?
    foreach($transactions as $transaction){
        $sign = ("DEBIT" == $transaction['crdrIndicator']) ? "-" : "+";
        ?>
        <tr>
            <td style='text-align:center;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;width:120px;'><?= date_formate_view($transaction['tranDate']) ?></td>
            <td style='padding-left:10px;padding-right:10px;width:350px;border-right:1px dotted #ccc; border-bottom:1px dotted #ccc;'>
			<? if(!empty($transaction['description'] )) echo str_replace('-',' ',$transaction['description']) ?> 
			<? if(!empty($transaction['comment'] )) echo  str_replace('-','',$transaction['comment']) ?>
			<br/><span style="font-size:11px;color:#858585;"><?= $transaction['transactionId'] ?> // <?= $transaction['txnType'] ?> (<?= $sign.price_format_double($transaction['transactionAmount']/100)?> <?=$transaction['transactionCurrency']?>)</span></td>
            	 <td style='text-align:right;width:150px;padding-right:15px;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;'><?if("DEBIT" == $transaction['crdrIndicator']){ echo "$ ".price_format_double($transaction['billAmount']/100); }?></td>
				 <td style='text-align:right;width:150px;padding-right:15px;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;'><?if("CREDIT" == $transaction['crdrIndicator']){ echo "$ ".price_format_double($transaction['billAmount']/100); }?></td>
		
            <td style='text-align:center;padding-left:10px;padding-right:10px;'><?= $transaction['status'] ?></td>
        </tr>
    <?
    }

?>
        </tbody>
</table>
<?=$pages?>
<!--<pre>-->
<?//print_r($transactions);?>
<?}?>

<script>
function exportPDF(){

    $('#export_content').html('<?=_e('Генерация документа...')?><br><br><img class="loading-gif" src="/images/loading.gif"/>');
    $('#export_window').show();
     $.post(site_url +  "/account/card-transactions", 
        { card: <?=$card->id?>,'export': true, days: <?=$days_selected?>,date_from: '<?=$input_filter['date_from']?>', date_to: '<?=$input_filter['date_to']?>', amount_min: '<?=$input_filter['amount_min']?>', amount_max: '<?=$input_filter['amount_max']?>'},  
            function(data){ 
                    $('#export_content').html( data);
                    
             }, "html");               
          
    

}
</script>

<div class="popup_window" id="export_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>

    <div id="export_content"></div>
    
</div>
