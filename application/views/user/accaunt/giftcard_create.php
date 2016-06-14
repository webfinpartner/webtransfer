<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<style>

    .payment_table tr td { text-align: center; }
    
</style>  
<script>
    function show_present_form(id){
        $('#present').show();
        $('#to_user_id').val('');
        $('#gift_card_id').val(id);
    }
    
    function activate(){
        return confirm("<?=_e('Вы уверены, что хотите пополнить Webtransfer DEBIT Card?')?>");

    }
    
    function on_present_submit(){
        location.replace('<?=site_url('account/giftcard/present')?>/'+$('#gift_card_id').val()+'/'+$('#to_user_id').val() );
    }
</script>    


<script>

function on_payment_account_change(){
    var payment_account = $('#payment_account').val();
    var payment_account_summ = $('#payment_account option:selected').data('summ');
    var cnt = Math.floor(payment_account_summ/50);
    var amount = '<option value="0">-</option>';
    if (cnt > 0 )
        for (var i = 1; i <= cnt; i++)  amount += '<option value="' + i + '">' + i + '</option>';
    
    $('#amount').html(amount);
    on_summ_change();
}


function on_summ_change(){
    var amount = $('#amount').val();
    if ( amount != '-')
        $('#total_summ').val('$ '+amount*50);
    
}

function onformsubmit(){
    return true;
    
}

</script>




<style>
	.form-1  { 
		text-align: left;
	}

	.form-1 input[type=text]{
		width: 200px;
	}

	.label-credit {
		border:none;
	}
	div#containern {
	    padding-bottom: 45px !important;
		margin-top: 11px;
	}
	.big-h1 {
    text-align: center;
    font-weight: normal;
    margin-top: 20px;
    line-height: 30px;
}
#container {
display:none !important;
}
.fll-nt {
    float: left;
    margin-right: 4px;
    width: 32.9%;
    overflow: hidden;
    margin-bottom: 30px;
}
.nopd {
    margin-right: 0 !important;
}
.fll-nt img {
    width: 37px;
    background-size: contain;
    margin: 0 auto;
    display: block;
}
.fll-nt h5 {
    text-align: center;
    font-weight: normal;
    margin-top: 12px;
    font-size: 14px;
    margin-bottom: 4px;
}
.fll-nt h6 {
    text-align: center;
    max-width: 177px;
    margin: 0 auto;
    color: #CACBCB;
    margin-top: 16px;
    font-size: 13px;
    font-weight: normal;
}
.fll-nts {
    float: left;
    margin-right: 8px;
    width: 26.3%;
    overflow: hidden;
    margin-bottom: 4px;
    background: #F8F8FA;
    padding: 20px;
    margin-top: 40px;
}
.fll-nts img {
    width: 27px;
    background-size: contain;
    margin: 0 auto;
    display: block;
    margin-top: -30px;
    position: absolute;
    margin-left: 79px;
}
.fll-nts h6 {
    color: #979C9E;
    font-size: 14px;
    text-align: center;
    font-weight: normal;
    padding-top: 20px;
    padding-bottom: 20px;
}
</style>
<style type="text/css">
.ch-area {
    float: left;
    margin-right: 0px;
    margin-bottom: 10px;
    margin-left: 28px;
}
.ch-area2 {
    margin-left: 60px !important;
} 

.checkbox-cls {
    position: absolute;
    left: -9999px;
}
.checkbox-cls + label {
    background: url(../../img/checkbox-sprite.png) 0 0 no-repeat;
    padding-left: 20px;
        background-position: 0px 2px;
}
.checkbox-cls:checked + label {
    background-position: 0 -30px;
}


.checkbox-cls + label {
    padding-left: 30px;
}






.checkbox-cls2 {
    position: absolute;
    left: -9999px;
}
.checkbox-cls2 + label {
    background: url(../../img/checkbox-sprite.png) 0 0 no-repeat;
    padding-left: 20px;
        background-position: 0px 2px;
}
.checkbox-cls2:checked + label {
    background-position: 0 -30px;
}


.checkbox-cls2 + label {
    padding-left: 30px;
    font-size: 14px;
}

.list-unstyled {
    list-style: none;
}
.mgts {
    margin: 0 auto;
    width: 580px!important;
}
.form-control2 {
    display: block;
    height: 30px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #A4A4A4;
    background-color: #F9F9F9;
    margin: 0 !important;
    background-image: none;
    border: 1px solid #F9F9F9;
    border-radius: 0px;
    margin-top: 8px !important;
    margin-bottom: 15px !important;
}


.wizz50n {
    width: 49%;
    float: left;
}

.wizz50n input {
    width: 82%;
}
.wizz52n input {
    width: 77%;
}
.wizz52n {
    width: 49%;
    float: left;
}

.hov-mgts {
    width: 450px !important;
    margin: 0 auto;
}


.mgts{
	position: relative;
}
.sdrrw {
    padding-left: 231px; 
}
.sdsds {
    position: absolute;
    margin-top: 13px;
    margin-left: 12px;
    color: #A8A8A8;
    font-weight: 500;
}

.hov-mgts {
    width: 450px !important;
    margin: 0 auto;
}

.respo {
    width: 96.7%;
    height: 120px;
    background-size: contain;
    clear: both !important;
    border: 1px solid #DCDBDB;
}

.priv {
    clear: both;
    width: 100%;
    position: absolute;
    z-index: 999;
    /* margin-top: 190px; */
    left: 0;
    bottom: 32px;
}



    
    .mgts {
    width: 580px !important;
    	margin: 0 auto;
    	position: relative;
}


.cc-selector input {
    margin: 0px;
    padding: 0px;
    width: 118px;
    height: 114px;
    position: absolute;
        -moz-appearance: none;
    opacity: 0;

}


/* Extras */
a:visited{color:#888}
a{color:#444;text-decoration:none;}
p{margin-bottom:.3em;}

.cc-selector input:checked +.drinkcard-cc {

    border: 2px solid #F24841;
}


.new-ic{
	width: 170px;
	height: 170px;
	background: url(/img/pr-icon1.png) 0 0 no-repeat;
	background-size: contain;
	    margin: 0 auto;
}

.new-ic2{
	width: 170px;
	height: 170px;
	background: url(/img/pr-icon2.png) 0 0 no-repeat;
	background-size: contain;
	    margin: 0 auto;
}


.new-ic3{
	width: 170px;
	height: 170px;
	background: url(/img/pr-icon3.png) 0 0 no-repeat;
	background-size: contain;
	    margin: 0 auto;
}

.p-nic {
    text-align: center;
    padding-top: 87px;
}

.mx-wz {
    max-width: 540px;
    margin: 0 auto;
    line-height: 34px !important;
}

.ul-pr li {
    list-style: none !important;
    clear: both;
    margin-bottom: 23px;
    overflow: hidden;
    margin-left: 44px;
}

.ones {
    font-size: 24px;
    font-weight: bold;
    float: left;
    margin-top: 3px;
}

.ones2 {
    float: left;
    margin-left: 20px;
    width: 576px;
    color: #888;
}

.many {
    width: 599px;
    margin: 0 auto;
    background: #fafbfd;
    padding: 20px 30px;
    color: #75a1c6;
    line-height: 35px;
    font-size: 28px;
}

.w-pr {
    width: 660px;
    margin: 0 auto;
    	overflow: hidden;
	clear: both;
}

.fl-w1 {
    width: 191px;
    float: left;
    padding-left: 29px;
}

.posab-line {
    width: 72px;
    height: 3px;
    color: #4387b6;
    background: #4387b6;
    position: absolute;
    margin-top: -36px;
    margin-left: 126px;
}

.ots-n {
    width: 44px;
    height: 44px;
    border: 2px solid #333;
    color: #333;
    line-height: 40px;
    border-radius: 50px;
    font-size: 30px;
    text-align: center;
}

.ots-n2 {
    font-size: 18px;
    line-height: 21px;
}


.br-txt {
    width: 600px;
    margin: 20px auto;
    color: #888;
}


.love-wt {
    width: 300px;
    text-align: center;
    margin: 62px auto;
    font-size: 20px;
}
.mgts .swiper-container.swiper-container-horizontal  .swiper-slide{
      width: 122.5px !important;
}


select#payment_account_new {
    width: 100%;
}

.pay-transaction {
    margin: 40px auto !important;
}

.hov-mgts .sdrrw-no {
    height: 44px;
    width: 100%;
}


.term-sv .payment_table th {
    background: none repeat scroll 0 0 #F0F0F0;
    color: #999999;
    font-weight: 400;
    height: 42px;
    text-align: center;
    padding-left: 0;
}

.term-sv a {
    color: #fff !important;
    background: #2881E8;
    width: 100px !important;
    display: block;
    margin: 0px auto;
    border-radius: 4px;
}

.term-sv a:hover {
    color: #fff !important;
opacity: 0.8 !important;
}

.term-sv .pod-wt {
    background: #FF4A00;
    margin-bottom: -12px;
}
.term-sv .pod-wt:hover {
    opacity: 0.8 !important;
}

.term-sv .payment_table td {
    background: none repeat scroll 0 0 #FFFFFF;
    border-bottom: 1px solid #EFEFEF;
    padding-bottom: 10px;
    padding-top: 8px;
    padding-left: 0px;
    padding-left: 0px !important;
    text-align: center;
}



.sdrrw-no {
    padding-left: 15px !important;
}

select#payment_account {
    width: 100%;
    padding-left: 10px !important;
}


 input#total_summ {
    border: 0 !important;
    width: 87%;
    float: right;
    height: 41px !important;
    padding: 0px;
    margin-right: -7px !important;
    padding-left: 10px;
    margin-top: 10px !important;
}



select#payment_account {
    width: 100%;
    height: 44px;
}


select#amount {
    width: 100%;
    height: 46px;
}

.term-sv {
    margin-bottom: 35px !important;
}

.term-sv a:first-child {
    background: #FF6800;
}

.term-sv a:first-child {
    background: #FF6800;
    margin-bottom: -14px;
}

.payment_table {
    width: 700px;
    margin-left: 1px;
}
.term-sv .payment_table th {
    min-width: 100px;
}
</style>
</div>
<div id="containern" class="content" style="padding-bottom:0px !important;">
                <h1 class="big-h1"><?=_e('Webtransfer GIFT Card')?> </h1>
                <br>
                <br>
                    <div class="many" style="text-align:center">
                   <?=_e('Webtransfer GIFT Card - прекрасный подарок на НОВЫЙ ГОД вашим близким и бизнес-партнерам!')?>
                    </div>
<br>
<center>
<img src="/images/wt-gift.jpg">
</center>
                    <div class="br-txt" style="text-align:justify;">
                    <?=_e('<b>Подарочная карта Webtransfer GIFT Сard - прекрасная возможность пополнить Webtransfer DEBIT Card по номиналу.</b> <br/><br/>Webtransfer GIFT Card выпускается номиналом $50. Вы можете приобрести любое количество подарочных карт используя вашу Webtransfer VISA Card. <br/><br/>Для покупки Webtransfer GIFT Card выберите в форме заказа (см. ниже) вашу Webtransfer VISA Card, укажите нужное количество подарочных карт и нажмите кнопку "ЗАКАЗАТЬ". Купленные карты мгновенно появятся на данной странице.<br/>Для пополнения Webtransfer DEBIT Card с вашей подарочной карты, Вам необходимо нажать кнопку "ПОПОЛНИТЬ" и средства моментально поступят на ваш счет Webtransfer DEBIT. Кроме того, Webtransfer GIFT Card можно подарить любому другому участнику Webtransfer - для этого достаточно нажать кнопку "ПОДАРИТЬ" и указать его ID.')?>
                    <br>
                    <br>
                    <?=_e('Количество новогодних Webtransfer GIFT Card  ограниченно количеством дней в 2015 г., комиссия за выпуск подарочных карт не взимается.')?>
					
					
    </div>

                </div>
          

  <? if (time() <= 1451584800) { ?>
   
<div id="containern" class="content">
<h1 class="big-h1" style="
    text-align: center;
    font-weight: normal;
    margin-top: 30px;
    line-height: 30px;
    margin-bottom: 40px;
"><?=_e('Заказать Webtransfer GIFT Card')?></h1>
<div class="mgts">
<div class="hov-mgts">
 <form class="form" id="create_form" method="POST"  accept-charset="utf-8" onsubmit="onformsubmit()">
   <input type="hidden" name="card_type" value="<?=$card->card_type?>">
<ul class=" list-unstyled" style="
    margin-bottom: 70px;
">
<li class="nosv blk">
<label for="payment_account"><?=_e('Выберите карту')?></label>
                            <select name="payment_account" id="payment_account" class="form-control form-control2 sdrrw" onchange="on_payment_account_change()">
                                <option value="0">-</option>
                            <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-summ="<?=$card->last_balance?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
                            <? } ?>                            
                            </select>
</li>
<li class="nosv wizz50n">
<label for="amount"><?=_e('Выберите количество')?></label>
                            <select id="amount" name="amount" class="form-control form-control2" onchange="on_summ_change()">
                                <option value="-">-</option>
                            </select>
</li>
<li class="nosv wizz52n">
<label for="summ-sv"><?=_e('Сумма')?></label>
 <input class="form-control form-control2 form_input" id="total_summ" type="text" value="" disabled="disabled"/>
</li>

</ul></div></div>
<br/>
<button type="submit" class="button" style=""><?=_e('Заказать')?></button></form></div>

<? } ?>

<div id="containern" class="content term-sv">
<h1 class="big-h1" style="
    text-align: center;
    font-weight: normal;
    margin-top: 30px;
    line-height: 30px;
    margin-bottom: 40px;
"><?=_e('Мои Webtransfer GIFT Card')?></h1><br/>
<table class="payment_table">
    <thead>
    <tr>
        <th><?=_e('Дата')?></th>
        <th><?=_e('Код')?></th>
        <th><?=_e("Номинал")?></th>
        <th><?=_e("Статус")?></th>
        <th></th>
    </tr>
    </thead>

        <tbody>
            <? if (empty($giftcards)) { ?>
            <tr><td colspan="5"><?=_e('У вас нет ни одной карты')?></td></tr>
            <? } ?>
            
            <? foreach( $giftcards as $gcard){ ?>
                
            <tr>
                <td><?=date('d-m-Y',strtotime($gcard->date_buy))?></td>
                <td><?=$gcard->id?></td>
                <td>$ <?=$gcard->nominal?></td>
                <td><?=$gcard->status_text?> <?=$gcard->to_user_id?></td>
                <td><?if( $gcard->status == 0){?>
                    <a href="#" onclick="show_present_form(<?=$gcard->id?>); return false;"><?=_e('Подарить')?></a><br>
                    <a href="<?=site_url('account/giftcard/activate').'/'.$gcard->id?>" onclick="return activate();"><?=_e('Пополнить')?></a></td>
                <? } ?>
            </tr>
            
            <? } ?>
        </tbody>
</table>
</div>

</div>  </div>

</div>



  

<div class="popup_window" id="present">
    <div class="close"></div>
    <h2><?=_e('Подарить пользователю')?></h2>
    <input type="text" id="to_user_id" style="padding:5px;">
    <input type="hidden" id="gift_card_id">
    <a href="#"  onclick="on_present_submit(); return false;" class="but cancel bluebut"><?=_e('Подарить')?></a>
</div>

