<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($note)) $note = '';
?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?> </h1>

<br/>


   <br/>
   <center>
<iframe id='ab983ef7' name='ab983ef7' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=17&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a6f1a566&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=17&amp;cb={random}&amp;n=a6f1a566&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </center>
   <br/>
    <?}?>

<?if($need_doc ){
    echo _e('new_223')."support@webtransfer.com<br/>";
    return;
}
if(50000 == $limit ){
    echo _e("Вывод заблокирован на 2 недели в связи со сменой номера телефона. Пожалуйста, дождитесь окончания срока блокировки.");
    return;
}
if($little_incame ){
    echo _e("У вас пока недостаточно заработанных средств, чтобы оформить заявку на вывод. Ваш минимальный заработок должен быть не менее -")." $limit. "._e("Сейчас он составляет")." $incame.";
    return;
 }
//if(!is_test_user('new_send_money') ){
 //   echo _e("Раздел закрыт для обновления с 15-08-2015 17:20 до 15-08-2015 18:30 по времени сайта.");
 //   return;
// }
 ?>
<style>
    .table{
        margin-bottom: 32px;
    }
 .popup {
    top: 50px!important;
    left: 50%;
    margin-left: -200px;
    padding: 25px;
    position: fixed;
    width: 400px;
}
    #popup_alfa {
    width: 500px !important;
    top: 5px!important;
    /* left: calc(50% - 350px); */
    margin: 0;
    padding: 22px;
    margin-left: -250px !important;
    position: fixed;
}
    .rower{
        height: auto;
        width: 721px;
        min-height: 15px;
        padding: 9px;
        padding-right: 0;
        font-size: 16px;
        overflow: auto;
    }
    .bg {
        background-color:#eee;
    }
    .bold {
        font-weight:bold;
    }
    .td1 {
        padding-left:10px;
        width:70%;
        float:left;
    }
    .td2 {
        text-align:right;
        float:right;
    }

    .payment_table th {
        text-align:center;
    }
    #buttons{
        display: block;
        margin: 10px 0 20px;
        overflow: auto;
    }
    #buttons .button1{
        float: right;

    }
    .formRow{
        overflow: auto;

    }
    .formRow .formRight{
        padding-top: 9px;
    }
    .balancer:after {
        padding: 0 10px;
    }
	form#send_form_user select#user_id {  margin-top: -35px;
}




form#send_form_user input#to_all {
    margin-right: 5px;
}



form#send_form_user .formRow .formRight select {
 width: calc(100% - 12px);
    height: 14px;
    padding: 2px 6px 6px;
    background: none repeat scroll 0% 0% #FFF;
    border: 1px solid #DDD;
    font-family: Arial,Helvetica,sans-serif;
    box-shadow: 0px 0px 0px 2px #F4F4F4;
    color: #656565;
        height: 34px;
    text-align: center;
}

div#sendmoney .popup {
    margin-top: -38px;
}


#sendmoney .formRow.sendmoney>div {  line-height: 20px;
}


#sendmoney input#input_id_user {  margin-bottom: -39px; margin-top: 5px;
}


#sendmoney .formRow.sendmoney>div {  line-height: 22px;
}


#sendmoney form#send_form_user select#user_id {   margin-top: -38px;margin-top: -22px;}



#sendmoney input#input_id_user {margin-top: 4px !important;  margin-bottom: 0px;  overflow: hidden;  height: 34px;  z-index: 999999; position: absolute;}

 form#send_form_user select#user_id {
  margin-top: -35px;
}




#sendmoney form#send_form_user input#to_all {
 margin-right: 5px;
}



#sendmoney form#send_form_user .formRow .formRight select {
 width: calc(100% - 12px);
    height: 14px;
    padding: 2px 6px 6px;
    background: none repeat scroll 0% 0% #FFF;
    border: 1px solid #DDD;
    font-family: Arial,Helvetica,sans-serif;
    box-shadow: 0px 0px 0px 2px #F4F4F4;
    color: #656565;
        height: 34px;
    text-align: center;
}

#sendmoney div#sendmoney .popup {
    margin-top: -38px;
}


#sendmoney .formRow.sendmoney>div {
 line-height: 20px;
}


#sendmoney input#input_id_user {
 margin-bottom: -39px;
 margin-top: 5px;
}


#sendmoney .formRow.sendmoney>div {
 line-height: 22px;
}


#sendmoney form#send_form_user select#user_id {
    margin-top: 6px;
    position: relative;
    width: 258px;
    margin-bottom: 8px;
}



#sendmoney input#input_id_user
{
 margin-top: 4px;
 margin-bottom: 0px;
 overflow: hidden;
 height: 34px;
 z-index: 999999;
 position: absolute;
}
.c2cIconSet {
    padding-top: 50px;
    padding-left: 27px !important;
}


select#card_user_id {
    margin-top: -37px;
}


#sendmoney input#input_id_user {
    margin-top: 4px !important;
    margin-bottom: 8px! important;
    overflow: hidden !important;
    height: 34px !important;
    z-index: 999999 !important;
    position: relative !important;
}


select#card_user_id {
    position: relative;
    margin-top: 2px !important;
    margin-bottom: 5px !important;
}


a#recepient_user_cards_btn {
    display: block;
}


#usersdiv .formRight br {
    display: none;
}
</style>
<script type="text/javascript">
        var RecaptchaOptions = {
                lang : 'ru', // Unavailable while writing this code (just for audio challenge)
                theme : 'white' // Make sure there is no trailing ',' at the end of the RecaptchaOptions dictionary
        };
</script>
<table cellspacing="0" class="payment_table">
    <tbody>

        <tr>
            <th class="payment_partner"></th>
            <th class="payments_period2" style="text-aign:center;width:280px !important;"><?=_e('send_money/data1')?></th>
            <th class="payments_limitations2" style="text-aign:center"><?=_e('send_money/data2')?></th>
            <th class="payments_description"><?=_e('send_money/data3')?></th>
        </tr>
		<? //if ($id_user == '90831296') { ?>
<? if(!empty($wtcards)){ ?>
        <tr>
            <td class="images">
                <img src="/images/card-2.jpg" width="75">
            </td>
            <td class="payment_limits">
                <br/><p style="text-align:left;margin-left:10px;"><?=_e('send_money/data4')?> <?=_e('через карту напрямую')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><?=_e('send_money/data5')?> - $1<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center">
                <a href="#" id="sendmoney_card_trigger" class="but bluebut sendmoney_card_trigger" style="margin-right:10px;" onclick="return false"><?=_e('send_money/data6')?></a>
            </td>
        </tr>
<? } ?>
<? //} ?>

<?if(!$isUserUS_CA){?>
	   <tr>
            <td class="images">
                <img src="/img/logo13.gif" width="75">
            </td>
            <td class="payment_limits">
                <br/><p style="text-align:left;margin-left:10px;"><?=_e('send_money/data4')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center">
                <a href="#" id="sendmoney_trigger" class="but bluebut sendmoney_trigger" style="margin-right:10px;" onclick="return false"><?=_e('send_money/data6')?></a>
            </td>
        </tr>


        <tr>
            <td class="images">

                    <img alt="ATM" src="/images/mastercard_visa.jpg" width="60">

            </td>
            <td class="payment_limits"><br/>
                <p style="text-align:left;margin-left:10px;"><?=_e('send_money/data7')?></p><br/></td>
            <td class="payment_restrictions">
                <p style="text-align:center"><?=_e('send_money/data8')?> - $1,000  </p>
            </td>
            <td class="payment_description" style="text-aign:center"><a href="#" id="alfa_trigger" style="margin-right:10px;" class="but bluebut alfa_trigger" onclick="return false"><?=_e('send_money/data6')?></a></td>
        </tr>
<?}?>
 <?if($this->lang->lang()=='ru'){?>
        <tr>
            <td class="images"><img title="<?=_e('магазин')?>" alt="<?=_e('магазин')?>" src="/images/wtshop-logo.png" style="height:45px;margin:10px 0px;"></td>
            <td class="payment_limits"><br/><p style="margin-left:10px;"><a href="http://wt-shop.ru" target="_blank">Wt-shop</a> <br/> <?=_e('send_money/data9')?> </p><br/></td>
            <td class="payment_restrictions">
                <p style="text-align:center"><?=_e('send_money/data11')?></p>
            </td>
            <td class="payment_description" style="text-aign:center">
                <a href="http://wt-shop.ru" id=""   target="_blank" class="but bluebut" style="margin-right:10px;"><?=_e('перейти на сайт')?></a>
            </td>
        </tr>
		<tr>
            <td class="images"><img title=""<?=_e('магазин')?>" alt=""<?=_e('магазин')?>" src="/images/wtbuy-logo.png" style="height:45px;margin:10px 0px;"></td>
            <td class="payment_limits"><br/><p style="margin-left:10px;text-align:left;"><a href="http://wtbuy-world.com" target="_blank">Wtbuy-World</a> <br/> <?=_e('send_money/data9')?> </p><br/></td>
            <td class="payment_restrictions">
                <p style="text-align:center"><?=_e('send_money/data11')?></p>
            </td>
            <td class="payment_description" style="text-aign:center">
               <a href="http://wtbuy-world.com" id=""   target="_blank" class="but bluebut" style="margin-right:10px;"><?=_e('перейти на сайт')?></a>
            </td>
        </tr>

        <tr>
            <td class="images">
                <img src="/images/help.png" width="75">
            </td>
            <td class="payment_limits">
               <br/> <p style="text-align:left;margin-left:10px;"><?=_e('send_money/data12')?></p><br/> </td>
            <td class="payment_restrictions">
                <p style="text-align:center"><?=_e('send_money/data11')?></p></td>
            <td class="payment_description" style="text-aign:center">
                <a href="#" id="donation_trigger"  class="but bluebut donation_trigger" style="margin-right:10px;" onclick="return false">
                    <?=_e('send_money/data6')?>
                </a>
            </td>
        </tr>






<tr>
            <td class="images">
                <a href="http://wt-zdorov.com/" target="_blank">
                    <img alt="Ex-Wt" src="/images/wt-zdorov.jpg" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="http://wt-zdorov.com"  target="_blank">WT-Zdorov</a><br/><?=_e('Товары для здоровья')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $5.00<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="http://wt-zdorov.com" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>
<?}?>
 <tr>
            <td class="images">
                <a href="http://skypasser.ru/" target="_blank">
                    <img alt="Skypasser" src="/images/splogo2.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><?=_e('send_money/data13')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><?=_e('send_money/data11')?></p></td>
            <td class="payment_description" style="text-aign:center"><a href="http://skypasser.ru/"  target="_blank" id="" style="margin-right:10px;" class=" but bluebut "><?=_e('перейти на сайт')?></a></td>
        </tr>
		<? if ($id_user == '90831296') { ?>
		        <tr>
            <td class="images">
                <a href="http://webtransfer.ru/" target="_blank">
                    <img alt="Webtransfer" src="/img/logoz.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="http://webtransfer.ru" target="_blank">Webtransfer.ru</a><br/><?=_e('send_money/data14')?> </p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="#" id="wt_trigger" style="margin-right:10px;" class=" but bluebut wt_trigger" onclick="return false"><?=_e('send_money/data6')?></a></td>
        </tr>

				<tr>
            <td class="images">
                <a href="http://camelot24.com/" target="_blank">
                    <img alt="PM TRUST" src="/images/cam24.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="http://camelot24.com/"  target="_blank">CAMELOT24</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="http://camelot24.com/" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>





        <tr>
            <td class="images">
                <a href="http://ex-wt.com/" target="_blank">
                    <img alt="Ex-Wt" src="/images/exwtlogo.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="http://ex-wt.com" target="_blank">EX-WT</a><br/><?=_e('send_money/data14')?> </p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="http://ex-wt.com" target="_blank" id="" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>


        <tr>
            <td class="images">
                <a href="https://money-change.biz/" target="_blank">
                    <img alt="Money Change" src="/images/logo-m.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://money-change.biz"  target="_blank">Money-Change.biz</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://money-change.biz" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>

		        <tr>
            <td class="images">
                <a href="https://wmcentre.kz/" target="_blank">
                    <img alt="Money Change" src="/images/wmc.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://wmcentre.kz"  target="_blank">Wmcentre.kz</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://wmcentre.kz" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>

 <tr>
            <td class="images">
                <a href="https://cash-transfers.com" target="_blank">
                    <img alt="Money Change" src="/images/cashtr.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://cash-transfers.com"  target="_blank">Cash Transfers</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://cash-transfers.com" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>
<tr>
            <td class="images">
                <a href="http://trust-exchange.org/" target="_blank">
                    <img alt="Money Change" src="/images/trustex.jpg" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="http://trust-exchange.org/"  target="_blank">Trust Exchange</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="http://trust-exchange.org/" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>

<tr>
            <td class="images">
                <a href="https://pm-trust.com/" target="_blank">
                    <img alt="PM TRUST" src="/images/pmtrust.jpg" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://pm-trust.com/"  target="_blank">PM TRUST</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://pm-trust.com/" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>


<tr>
            <td class="images">
                <a href="https://onlain-obmen.ru/" target="_blank">
                    <img alt="PM TRUST" src="/images/online-obmen.jpg" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://onlain-obmen.ru/"  target="_blank">Online Obmen</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://onlain-obmen.ru/" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>

<tr>
            <td class="images">
                <a href="https://1wm.kz/" target="_blank">
                    <img alt="PM TRUST" src="/images/1kz.jpg" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><a href="https://1wm.kz/"  target="_blank">1WM.KZ</a><br/><?=_e('Пункт обмена электронных валют')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><p style="text-align:center"><?=_e('send_money/data5')?> - $50<br/>
				  </p></td>
            <td class="payment_description" style="text-aign:center"><a href="https://1wm.kz/" target="_blank" id="wtzd_trigger" style="margin-right:10px;" class=" but bluebut"><?=_e('перейти на сайт')?></a></td>
        </tr>

<!--
		<tr>
            <td class="images">
                <a href="http://webtour.by/">
                    <img alt="Webtour" src="/images/webtour.png" width="85">
                </a>
            </td>
            <td class="payment_limits">
                <br/>
                <p style="text-align:left;margin-left:10px;"><?=_e('send_money/data15')?></p> <br/></td>
            <td class="payment_restrictions"><p style="text-align:center"><?=_e('send_money/data11')?></p></td>
            <td class="payment_description" style="text-aign:center"><a href="#" id="webtour_trigger" style="margin-right:10px;" class=" but bluebut webtour_trigger" onclick="return false"><?=_e('send_money/data6')?></a></td>
        </tr> -->
<?}?>
    </tbody>
</table>
<br/>
<? /*
<center>
<a href="<?=site_url('account/exchanges-list')?>"  class="but blueB" style="padding:7px 100px;">
 <?=_e('Воспользоваться услугами обменников')?>
</a><br/>
<a href="<?=site_url('account/exchanges-list')?>">
<img src="/images/exwtlogo.png" style="height:30px;">
<img src="/images/cam24.png" style="height:30px;">
<img src="/images/trustex.jpg" style="height:30px;">
<img src="/images/wmc.png" style="height:30px;">
<img src="/images/logo-m.png" style="height:30px;">

<img src="/images/1kz.jpg" style="height:30px;">
<img src="/images/online-obmen.jpg" style="height:30px;">
<img src="/images/pmtrust.jpg" style="height:30px;">
</a>
</center>
*/?>

<!--<div style="text-align:right;margin-bottom:10px;">
<a href="#" id="per-txt" class="but blueB alfa" style="cursor: pointer;" onclick="return false"><?=_e('accaunt/send_money_14')?></a>
</div>-->
<?if(!$isUserUS_CA){?>
<div class="popup" id="popup_alfa" style="width:690px;height: 90vh;box-shadow:none;border-radius:0;/*display:block !important;*/">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" style="margin-top:10px;width:100%;height:100%;box-shadow:none;border-radius:0;">
    <iframe src="https://3ds.payment.ru/P2P_PSBR/card_form.html" frameborder="0" width="95%" height="95%" align="center">
    <?=_e('accaunt/send_money_1')?>
    </iframe>
    </div>
</div>
<?}?>
<div class="popup" id="sendmoney">
    <script>
        var accounts_amount = [];
        accounts_amount[ 'WTUSD1' ] = '<?= price_format_double($accaunt_header['payout_limit_by_bonus'][5], TRUE, TRUE)?>';
        accounts_amount[ 'WTUSD2' ] = '<?= price_format_double($accaunt_header['payout_limit_by_bonus'][2], TRUE, TRUE)?>';
        accounts_amount[ 'C-CREDS' ] = '<?= price_format_double($accaunt_header['payout_limit_by_bonus'][4], TRUE, TRUE)?>';
        accounts_amount[ 'P-CREDS' ] = '<?= price_format_double($accaunt_header['payout_limit_by_bonus'][3], TRUE, TRUE)?>';
        accounts_amount[ 'WTDEBIT' ] = '<?= price_format_double($accaunt_header['payout_limit_by_bonus'][6], TRUE, TRUE)?>';

        $(function(){
           $('#account_type').on('change',function(){
                var account_type_choosen = $('#account_type option:selected' ).val();
                $('#can_payout' ).html( accounts_amount[ account_type_choosen ] );
           });
        });
    </script>
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/send_money_18')?></h6>
        </div>
        <form id="send_form_user" method="POST" action="<?=site_url('account/send_money')?>" accept-charset="utf-8">
            <input type="hidden" name="submit" value="1"/>
            <input type="hidden" name="code" id="form_code" value="1"/>
            <input type="hidden" name="own" id="form_code" value="<?=$own?>"/>
            <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
                <img class='loading-gif' style="margin-top: 130px" src="/images/loading.gif"/>
            </div>
                <fieldset>

                        <div id='usersdiv' class="formRow sendmoney">
                            <label style="margin-top:20px;width:105px;"><?=_e('Получатель')?></label>
                            <script>
                                function toAll(t){
                                    var selct = ('true' == $('[name="card_direct"]').val()) ? '#card_user_id' : '#user_id';
                                    if ($(t).prop('checked')){
                                        $('#input_id_user').show();
                                         if($('#out_send_form_user').data('send-money-card') == 'true')
                                            $('#recepient_user_cards_btn').show();

                                        $('#input_id_user').prop('disabled','');
                                        $(selct).hide();
                                        $(selct).prop('disabled','disabled');
                                    } else{
                                        $('#input_id_user').hide();
                                        $('#recepient_user_cards_btn').hide();
                                        $('#input_id_user').prop('disabled','disabled');
                                        $(selct).show();
                                        $(selct).prop('disabled','');
                                    }
                                }

                            </script>
                            <div class="formRight">
                                <input id="input_id_user" class="form_input" type="text" name="id_user" value="" style="display: none">

                                <a href="#" onclick="find_cards(); return false;" id="recepient_user_cards_btn" style="display: none"><?=_e('Найти карты этого пользователя')?></a><br>
                                <select class="form_select" name="recepient_user_card" id="recepient_user_cards" style="display: none">
                                </select>
                                <img class='cards_loading-gif' style="display: none" style="margin-top: 130px" src="/images/loading.gif"/><br>

                            <? if(!empty($wtcards)){ ?>
                                <select class="form_select" name="id_user" id="card_user_id" style="display: none">
                                    <? renderSelect($userUsersCard, $user_id); ?>
                                </select>
                                <input type="hidden" name="card_direct" value="false"/>
                            <? } ?>
                                <select class="form_select" name="id_user" id="user_id">
                                    <? renderSelect($userUsers, $user_id); ?>
                                </select>


                                <input type="checkbox" name="to_all" id="to_all" value="1" onclick="toAll(this)" style="margin-top:10px;"><span class="grey" id="to_all_text" style="font-size:11px"> - <?=_e('send_money/data16')?></span>
                            </div>
                        </div>
                    <div class="formRow" id="my_cards_block" style="display: none">
                            <label style="padding-top:20px"><?=_e('Карта')?></label>
                            <div class="formRight">
                                <select name="card"  style="padding: 5px;" class="form_select" onchange="get_card_balance( $('#my_cards_block select[name=card] option:selected').val(), 1, '#my_cards_block #balance_loading-gif', '#my_cards_block #card_balance' );">
                                 <?php if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                    <option value="<?=$card->id?>"><?=Card_model::display_card_name($card)?></option>
                                 <?php } ?>
                                </select>
                                <?php if (!empty($wtcards)) { ?>
                                <br><span style="font-size:12px;"><?=_e('Сумма на карте')?>: $<span id='card_balance'><?= $wtcards[0]->last_balance ?></span>
                                    <a href='#' onclick="get_card_balance( $('#my_cards_block select[name=card] option:selected').val(), 0, '#my_cards_block #balance_loading-gif', '#my_cards_block #card_balance' );
                                        return false;"><img src="/images/reload.png"></a>
                                    <p class="center"><img id='balance_loading-gif' style="display: none" src="/images/loading.gif"/></p>

                                </span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="formRow" id="account_types_block">
                            <label style="margin-top: 15px; width: 105px;"><?= _e('Счет') ?>:</label>
                            <div class="formRight">
                                 <select class="form_select" name="account_type" id="account_type">

                                   <? $default_can_payout = $accaunt_header['payout_limit_by_bonus'][4]; ?>
                                   <?if($is_exchenge){
                                       $default_can_payout = $accaunt_header['payout_limit_by_bonus'][2];
                                       ?>
									   <?if($is_exchenge_debit){ ?>
                                     <option value="WTDEBIT">Webtransfer DEBIT</option>
									 <? } ?>
                                     <option value="WTUSD2" selected="selected" style="color:red;"><?= _e('Webtransfer WTUSD&#10084;') ?></option>
                                     <option value="C-CREDS">C-CREDS</option>
                                     <option value="P-CREDS">P-CREDS</option>
                                   <? } else { ?>

                                     <option value="C-CREDS" selected="selected">C-CREDS</option>
                                     <option value="WTUSD2" style="color:red;"><?= _e('Webtransfer WTUSD&#10084;') ?></option>
                                   <? } ?>
                                </select>
                            </div>
                        </div>
                        <div class="formRow">
                            <div class="small" id="limit_money"><?=_e('accaunt/send_money_5')?> <span id="can_payout_qestions" class="phone_format" onclick="$('#popup_limit').show('slow');  return  false;" style="cursor:pointer;">(?)</span>: $<span id="can_payout"><?= price_format_double($default_can_payout)?></span></div>
                            <label style="margin-top:20px;" id="summlabel"><?=_e('send_money/data17')?></label>
                            <div class="formRight">
                                <input class="form_input send_amount" type="text" name="amount" id="send_amount" value="<?=$money_amount?>"/>
                                <?if($is_exchenge){?><input class='sendmoney code_send_protection' type="checkbox" name="money_back" value="return" onclick="hideCodeProtection(this);" style="display:none;margin-top:10px;"><span class="grey sendmoney code_send_protection" style="display:none;font-size:11px"> - <?=_e('Возврат денег')?></span><?}?>
                                <input class='sendmoney code_send_protection code_send_protection_input' type="checkbox" name="protection" value="1" onclick="protectionEnable(this)" style="display:none;margin-top:10px;"><span class="grey sendmoney code_send_protection code_send_protection_input" style="display:none;font-size:11px"> - <?=_e('send_money/data18')?></span>
                            </div>
                            <script>
                                function hideCodeProtection(t){
                                    if ($(t).prop('checked')){
                                        $('.code_send_protection_input').hide();
                                        $('[name="protection"]').prop('disabled','disabled');
                                    } else{
                                        $('.code_send_protection_input').show();
                                        $('[name="protection"]').prop('disabled','');
                                    }
                                }
                                function protectionEnable(t){
                                    if ($(t).prop('checked')){
                                        $('.codeprotection').show();
                                        $('#code').prop('disabled','');
                                    } else{
                                        $('.codeprotection').hide();
                                        $('#code').prop('disabled','disabled');
                                    }
                                }

                            </script>
                            <div class="error formRight" style="display:none; clear: both; color: red; width:60%" id="error_amount"></div>
                        </div>
                        <div class="formRow codeprotection" style="display: none">
                            <label style="margin-top:20px;"><?=_e('send_money/data19')?></label>
                            <div class="formRight">
                                <input class="form_input" type="text" name="codeprotection" value="" maxlength="5" placeholder="<?=_e('send_money/data22')?>"/>
                            </div>
                            <div class="error formRight" style="display:none; clear: both; color: red; width:60%" id="error_codeprotection"></div>
                        </div>
                        <div class="formRow summsumary" style="display: none">
                            <label style="margin-top:20px;"><?=_e('block/data10')?></label>
                            <div class="formRight">
                                <input class="form_input" type="text" name="summsumary" value=""/>
                            </div>
                            <div class="error formRight" style="display:none; clear: both; color: red; width:60%" id="error_codeprotection"></div>
                        </div>
                        <div class="formRow notetext">
                            <label style="margin-top:20px;"><?=_e('send_money/data20')?></label>
                            <div class="formRight">
                                <input class="form_input" type="text" name="note" id="note" value="<?=$note?>"/>
                            </div>
                            <div class="error formRight" style="display:none; clear: both; color: red; width:60%" id="error_note"></div>
                        </div>
                        <?if(!$is_exchenge){?>
                        <div class="formRow comission">
                            <label style="margin-top:20px;"><?=_e('Комиссию<br>платит')?></label>
                            <div class="formRight">
                                <select name="comission_payer">
                                    <option value="sender">Отправитель <?=config_item('account_sendmoney_comission')?>%</option>
                                    <option value="receiver">Получатель <?=config_item('account_sendmoney_comission')?>%</option>
                                    <option value="both">Отправитель <?=config_item('account_sendmoney_comission')/2?>% и Получатель <?=config_item('account_sendmoney_comission')/2?>%</option>
                                </select>
                            </div>
                        </div>
                        <? } ?>
                        <div class="formRow sendmoney" style="display: none">
                            <div style="text-align:center;">
                               <?=_e('send_money/data21')?>
                            </div>
                        </div>
    <!--                    <div class="formRow" style="height: 150px; overflow: hidden">
                            <label style="margin-top:20px;"></label>
                            <div class="formRight">
                                < ?=/*$reCapcha*/?>
                            </div>
                            <div class="clear"></div>
                        </div>-->
                        <button class="button" type="submit"  id="out_send_form_user" name="submit"><?=_e('accaunt/send_money_9')?></button>
                        <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                        <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
                </fieldset>
        </form>
    </div>
</div>
<? $this->load->view( 'user/accaunt/blocks/renderPayoutLimits_window' );?>
<div class="popup_window" id="order_bank_norvik_print" style="padding: 0px; margin-right: -200px; width: 495px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');window.location.reload();" class="close"></div>
    <h2><?=_e('block/data14')?></h2>

    <button class="button" onclick="return false;" id="check_print_norvik" name="submit"><?=_e('block/data13')?></button>
</div>

<div id="popup_limit" class="popup_window" style="z-index:9999">
    <div onclick="$('#popup_limit').hide('slow');" class="close"></div>
    <h2><?=_e('accaunt/send_money_10')?></h2>
   <?=_e('accaunt/send_money_11')?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit"><?=_e('accaunt/send_money_12')?></a>
</div>
 <? //} ?>
<script>
    var security = '<?=$security ?>';

    $('#out_send_form_user').click(function(){
        $(".error").hide();
        if('true' == $('#out_send_form_user').data('bank_norvik')){
            createBankNorvikChack();
            return false;
        }
        if('true' == $('#out_send_form_user').data('send-money') ||
           'true' == $('#out_send_form_user').data('send-money-card')
                ){
            if('' == $('[name="note"]').val()){
                $('#error_note').text('<?=_e('Поле не должно быть пустым')?>');
                $('#error_note').show();
                return false;
            }
        }
        if('' == $('[name="amount"]').val()){
                $('#error_amount').text('<?=_e('Поле не должно быть пустым')?>');
                $('#error_amount').show();
                return false;
            }
        if(security)
        {
            if( $(this).hasClass('submitted') ) return true;


            mn.security_module
                .init()
                .show_window('withdrawal_standart_credit')
                .done(function(res){
                        if( res['res'] != 'success' ) return false;
                        var code = res['code'];

                        $('#form_code').val( code );
                        $('#out_send_form_user').addClass('submitted').click();
                });
            return false;

        } else
            return true;
    });
</script>
<script>
    $('.shop_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('500112');
        $("#sendmoney h6").text("<?=_e('Оплата магазину')?> WT-Shop");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');

    $('.shop2_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('76799865');
        $("#sendmoney h6").text("<?=_e('Оплата магазину')?> WTBuy-World");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');

    $('.wt_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('85497641');
        $("#sendmoney h6").text("<?=_e('Отправить в Обменный Пункт Webtransfer.ru')?>");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');


    $('.donation_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('500111');
        $("#sendmoney h6").text("<?=_e('Пожертвовать деньги')?>");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');

    $('.sendmoney_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('');
        $('#input_id_user').prop('disabled','disabled');
        $('#user_id').prop('disabled','');
        $('.sendmoney').show();
        $('.comission').show();
        $("#sendmoney h6").text("<?=_e('Перевести деньги участнику')?>");
        $('#out_send_form_user').data('send-money', 'true');
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');
<? if(!empty($wtcards)){ ?>
    $('.sendmoney_card_trigger').click(function() {
        resetWindow();

        $('#to_all_text').html('<?=_e('перевод по ID')?>');

        $('#input_id_user').val('');
        $('#input_id_user').prop('disabled','disabled');
        $('#user_id').prop('disabled','disabled');
        $('#user_id').hide();


        $('#my_cards_block').show();
        $('#account_types_block').hide();
        $('#limit_money').hide();
        $('#card_user_id').prop('disabled','');
        $('#card_user_id').show();
        $('[name="card_direct"]').val('true');
        $('#min_sum_info').html(1);
        $('.sendmoney').show();
        $('.comission').hide();
        $('#can_payout_qestions').hide();
        $('.code_send_protection').hide();
        $('#can_payout').html('<span class="balancer">x.xx</span>');
        $("#sendmoney h6").text("<?=_e('Перевести с карты на карту')?>");
        $('#out_send_form_user').data('send-money-card', 'true');
        
        
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');
<?}?>

    $('.skypasser_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('500113');
        $("#sendmoney h6").text("<?=_e('Оплатить в')?> Skypasser");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');

    $('.webtour_trigger').click(function() {
        resetWindow();
        $('#input_id_user').val('500114');
        $("#sendmoney h6").text("<?=_e('Оплатить в')?> Webtour.by");
        $("#sendmoney").fadeIn();
    }).css('cursor', 'pointer');

    $('.alfa_trigger').click(function() {
        $("#popup_alfa").fadeIn();
    }).css('cursor', 'pointer');

    $('#send_form_user').submit(function() {
        var wrongs = 0;
//                error = $(this).find('.error'),
//                vip = <?= json_encode(array_flip(getVipUsers()))?>;
        var s = parseFloat($(this, '.send_amount').val().replace(",", "."));
        if(isNan(s)) return false;

//            if ("undefined" == typeof(vip[$('#user_id').val()]) && "undefined" == typeof(vip[$('#input_id_user').val()])) {
//                error.fadeIn();
//                return false;
//            }
        $('#send_form_user .send_amount').val(s);
        wrongs = checkField( '#user_id',wrongs );
        wrongs = checkField( '#send_form_user .send_amount',wrongs );
//            wrongs = checkField( '#recaptcha_response_field',wrongs );
        if( !wrongs ) return true;
        else return false;
    });
    $("#send_amount").keyup(function(){
        var s = parseInt($(this).val());
        if (!isNaN(s) && 1 < s){
            $('[name="summsumary"]').val(s/10);
        } else {
            $('[name="summsumary"]').val('');
        }
    });
    $('.alfa').click(function() {
        if($('#popup_alfa').is(':visible')) {
                $('#popup_alfa').hide('slow');
                $('#per-txt').html("<?=_e('Перевод с карты на карту')?>")
                $('#send_form_user').show('slow');
        } else {
                $('#popup_alfa').show('slow');
                $('#per-txt').html("<?=_e('Пожертвование денег')?>")
                $('#send_form_user').hide('slow');
        }

         $('.popup_window .close').click(function() {
                 $(this).parent().hide('slow');
         });
       return false;
     });


     function find_cards(){

        $('.cards_loading-gif').show();
        $('#recepient_user_cards').hide();
        var user_id = $('#input_id_user').val();
        $.post(site_url + '/account/ajax_get_user_cards', {
            user_id: user_id
        },
        function(data) {
            $('.cards_loading-gif').hide();

            if ( data.status == 'error' ){
                alert(data.error);
            } else  {
                var options_html = '';
                $.each(data.cards, function(i){
                    options_html+= '<option value="'+this.id+'">'+this.name+'</option>';
                });
                $('#recepient_user_cards').html(options_html);
                $('#recepient_user_cards').show();
            }






        }, 'json');


     }


    function resetWindow(){
        $('#to_all').removeAttr('checked');
        $('#to_all_text').html('<?=_e('send_money/data16')?>');
        $('#out_send_form_user').data('send-money', '');
        $('#out_send_form_user').data('send-money-card', '');
        $("#summlabel").text("<?=_e('send_money/data17');?>");
        $('#can_payout').html("<?= price_format_double($default_can_payout, TRUE, TRUE)?>");

        if ( $('#account_type option[value=WTUSD2]').length  )
            $('#account_type option[value=WTUSD2]').attr('selected','selected');
        else
            $('#account_type option[value=C-CREDS]').attr('selected','selected');

        $('#input_id_user').prop('disabled','');
        $('#input_id_user').hide();
        $('#user_id').prop('disabled','disabled');
        $('#user_id').show();
        $('#card_user_id').prop('disabled','disabled');
        $('#card_user_id').hide();
        $('[name="card_direct"]').val('false');
        $('#min_sum_info').html(50);
        $('#can_payout_qestions').show();
        $('.sendmoney').hide();
        $('.codeprotection').hide();
        $('.summsumary').hide();
        $('.notetext').show();
        $('#limit_money').show();

        $('#my_cards_block').hide();
        $('#account_types_block').show();

        $('#recepient_user_cards_btn').hide();
        $('#recepient_user_cards').hide();



    }

    function checkField( _link, _wrongs, _pattern ){
        var elem = $(_link),
            res = 0;

        if( elem.length == 0 ) return -1;

        if( elem.val() == '' ){
            elem.addClass('wrong');
            res = 1;
        }
        else
            elem.removeClass('wrong');

        return _wrongs + res;
    }
    function createBankNorvikChack(){
        if(!cahckSumm("sendmoney", 'input[name="summsumary"]'))
            return false;
        var s = parseFloat($('input[name="summsumary"]').val());

        var user_id = $('[name="own"]').val();
        var all = 'true';

        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: s,
            metod: 'bank_norvik',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            data.id = $.trim(data.id);
            if (data.id >= 1) {
                $('.loading-gif').hide();
                $('#check_print_norvik').attr('rel', data.id);
                $('#sendmoney').hide();
                $('#order_bank_norvik_print').show('slow');
            } else {
                $('.loading-gif').hide();
                $('p.error').show();
            }
        }, 'json');
    }
    function cahckSumm(f, e){
        var val = $('#'+f+' '+e).val();
        if (isNaN(parseInt(val))){
            $('#'+f+' div.error').show();
            return false;
        }
        if (parseInt(val) < 1){
            $('#'+f+' div.error').show();
            return false;
        }
        if (parseInt(val) != val){
            $('#'+f+' div.error').show();
            return false;
        }
        return true;
    }
    $('#check_print_norvik').click(function() {
        $('#order_bank_norvik_print').hide('slow');
        window.open(site_url + '/account/getcheck/' + $(this).attr('rel'), '_blank');
        $("#secondary-nav a[href*=send_money]").trigger('click');
    });
</script>
<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
</div>

<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>