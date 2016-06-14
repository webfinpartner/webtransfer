<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>

<?}?>

   <? $this->load->view('user/accaunt/messages'); ?>

<style>
    .searchSelect{
        width: 255px !important;
        margin-top: 9px;
        position: fixed;
        top: 215px !important;
    }
    .popup {
        top: 100px !important;
    }
	.payment_table {
	font-size:12px !important;
	}
	.payment_table p{
	font-size:12px !important;
	}
</style>

<script>
       function _loadCombobox(selector, data){
                $(selector+' option').remove();
                $(selector).append( new Option('-','-') );
                $.each(data, function(key, val){
                       $(selector).append( new Option(val,key) );
                    });
   }
</script>
<br/><br/>

<style>


/* new-page-card */

.wt-min-height-area{
	min-height: 1010px;
}

.new-wt-card img {
    display: block;
    margin: 0 auto;
    z-index: 1;
    position: relative;
}

.obmen-top-area img {
    float: left;
    margin-left: 7px;
    margin-top: 10px;
}

.obmen-bottom-area img {
    float: left;
    margin-left: 6px;
}

.obmen-left-area img {
    position: absolute;
    left: 17px;
    margin-top: 221px;
    width: 203px;
}

.obm-new {
    width: 200px !important;
    margin-left: 20px !important;
    margin-top: 15px !important;
}

.obmen-right-area img{
	position: absolute;
	right: 1px;
    margin-top: 212px;
}


.wt-card-container {
    position: relative;
    clear: both;
    overflow: hidden;
    margin-bottom: 30px;
}

.wt-min-height-area h1{
	margin-top: 40px;
	margin-bottom: 40px;
}


.wt-min-height-area .many {
    width: 599px;
    margin: 0 auto;
    background: #fafbfd;
    padding: 37px 30px;
    color: #75a1c6;
    line-height: 44px;
    font-size: 27px;
}

.wt-min-height-area .many2 {
    width: 599px;
    margin: 0 auto;
    background: #FF6800;
    padding: 26px 28px;
    color: #fff;
    line-height: 38px;
    font-size: 27px;
}

h2.big-h2 {
    text-align: center;
    font-size: 19px;
    margin-top: -17px;
    color: #000;
}



.opas08:hover{
	opacity: 0.8;
	-webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    -o-transition: all 0.2s ease-out;
    -ms-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
}

.opas06:hover{
	opacity: 0.6 !important;
	-webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    -o-transition: all 0.2s ease-out;
    -ms-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
}


.wt-icons img {
    width: 57% !important;
    margin-top: 30px;
    margin-bottom: 9px;
}

.my-wt-card{
	margin-top: 60px;
}


.new-wt-icons img {
    width: 100% !important;
    margin-top: 46px !important;
    margin-bottom: -12px !important;
}




.new-wt-card {
    display: block;
    margin: 0 auto;
    z-index: 0;
    *position: relative;
}

.new-bottom-wt {
    margin-top: -35px;
}

.new-card-style button {
    display: inline-block;
    margin-left: 226px;
}

.new-card-style img {
    display: block;
    margin: 0 auto;
    z-index: 1;
    width: 267px;
    position: relative;
    margin-bottom: -50px;
}
</style>

<center>
<a href="#" id="bank_card_real_trigger" class="bank_card_real_trigger but blueB" style="padding:7px; width:50%;" onclick="return false">
 <?=_e('new_218')?>
</a><br/>

<style>
a#exchangers_trigger:hover {
    background: #F75A1F !important;
}
a#bank_card_real_trigger:hover {
    background: #3390EE !important;
}
</style>

<a href="<?=site_url('account/exchanges-list')?>" id="exchangers_trigger" class="but blueB" style="padding:7px; width:50%; cursor: pointer;background: none repeat scroll 0% 0% #3390EE;">
 <?=_e('Пополнить через обменный пункт')?>
</a><!--<br/>
<a href="<?=site_url('account/exchanges-list')?>">
<img src="/images/exwtlogo.png" style="height:30px;">
<img src="/images/cam24.png" style="height:30px;">
<img src="/images/trustex.jpg" style="height:30px;">
<img src="/images/wmc.png" style="height:30px;">
<img src="/images/logo-m.png" style="height:30px;">
</a>-->
<script>
$("#bank_card_real_trigger").hover(
	function(){
    $("#exchangers_trigger").css("background", "#F75A1F")},
	function(){
	$("#exchangers_trigger").css("background", "#3390EE");
	$("#bank_card_real_trigger").css("background", "#F75A1F");
    });
$("#exchangers_trigger").hover(
	function(){
		$("#bank_card_real_trigger").css("background", "#3390EE"); },
	function(){
		$("#bank_card_real_trigger").css("background", "#F75A1F");
		$("#exchangers_trigger").css("background", "#3390EE");
    });
</script>
</center>


<table cellspacing="0" class="payment_table">
    <thead>
        <tr>
         <th class="payment_partner"><?=_e('new_206')?></th>
            <th class="payments_period"><?=_e('new_207')?></th>
            <th class="payments_limitations"><?=_e('new_208')?></th>
            <th class="payments_description"><?=_e('new_209')?></th>
        </tr>
    </thead>
    <tbody>

		           <tr>
            <td class="images"><img alt="WTCard" src="/images/card-2.jpg" width="75"></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer DEBIT  Card <br/>- моментально. В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/><?=_e('Level 1')?> - $100<br/><?=_e('Level 2')?> - $1,000<br/></p></td>
            <td class="payment_description">


                <a href="#" id="wtcard_trigger" class="wtcard_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>


            </td>
        </tr>
        <tr>
            <td class="images"><img alt="CardBank" src="/images/bankico.png" width="75"></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Сard через банк.<br>В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 10,000<br/></p></td>
            <td class="payment_description">
                <a href="#" id="bank_card_trigger" class="bank_card_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>




        <tr>
            <td class="images" style="vertical-align: middle; text-align: center; font-size: 26px; font-family: 'Courier New', Courier, monospace; font-weight: 600;">
                <span>I<span style="color:red;margin-left: 6px;">❤</span><br/>P2P</span>
            </td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 1,000<br/></p></td>
            <td class="payment_description">
                <a href="#" id="p2p_trigger" class="p2p_trigger but bluebut" onclick="p2p_trigger_click();return false;"><?=_e('new_213')?></a>
            </td>
        </tr>
        <tr>
            <td class="images"><img alt="RealCardBank" src="/images/worldpay.jpg" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="bank_card_real_trigger" class="bank_card_real_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>

        <tr>
            <td class="images"><a href="https://okpay.com" target="_blank"><img alt="ATM" src="/images/payways/okpay.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p> </td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$3,000<br/></p></td>
            <td class="payment_description">
                <a href="#" id="okpay_trigger" class="okpay_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>


        <tr>
            <td class="images"><a href="https://qiwi.com/" target="_blank"><img alt="Qiwi" src="/images/payways/qiwi.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 200<br/></p></td>
            <td class="payment_description">
                <a href="#" data-force-country="RU" data-force-service="Qiwi"  class="bank_card_real_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>

   <tr>
            <td class="images"><a href="https://payeer.com/" target="_blank"><img alt="Payeer" src="/images/payways/payeer.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="payeer_trigger" class="payeer_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>



        <tr>
            <td class="images"><a href="https://perfectmoney.is/" target="_blank"><img alt="PerfectMoney" src="/images/payways/perfect.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="perfectmoney_trigger" class="perfectmoney_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>

        <tr>
            <td class="images"><a href="https://nixmoney.com/" target="_blank"><img alt="Nixmoney" src="/images/nixmoney.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="nixmoney_trigger" class="nixmoney_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>

        <? if ($id_user == '500150') { ?>
        <tr>
            <td class="images"><a href="https://qiwi.com/" target="_blank"><img alt="Qiwi" src="/images/qiwi.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Webtransfer VISA Card <br> электронными деньгами - моментально <br> В отдельных случаях сроки могут составлять более продолжительное время.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" data-force-country="RU" data-force-service="Qiwi"  class="bank_card_real_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>
		<tr>
            <td class="images"><a href="http://barclays.com/" target="_blank"><img title="<?=_e('new_210')?>" alt="<?=_e('new_210')?>" src="/images/bank1.png" style="width:75px;margin:10px 0px;"></a></td>
            <td class="payment_limits"><p><?=_e('Пополнение Арбитражного счета - срок зачисления от 1-го до 2-х рабочих дней.')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212r')?><br/> $ 100,000 (<?=_e('Арбитраж')?>) </p></td>
            <td class="payment_description">
                <a href="#" id="bank_trigger" class="but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>





	<tr><td colspan=4>
<div style="width:50%;float:left;margin:10px 5px;">
<center>
<a href="#" id="w1_trigger2" class="payeer_trigger but blueB" style="padding:7px; width:90%" onclick="return false">
 <?=_e('new_218')?>
</a><br/>
<?if('ru' == $this->lang->lang()){?>
<style>
.pay_img {
width:50px;
}
</style>
<img src="/images/payways/qiwi.png" class="pay_img">
<img src="/images/payways/sber.png" class="pay_img">
<img src="/images/payways/yamoney.gif" class="pay_img">
<img src="/images/payways/alfa.png" class="pay_img">
<img src="/images/payways/w1.png" class="pay_img" style="margin-right:-16px">
<?}?>
</center>
</div>
<div style="width:47%;float:right;margin:10px 5px;">
<center>
<a href="<?=site_url('account/exchanges-list')?>"  class="but blueB" style="padding:7px;width:90%;">
 <?=_e('Пополнить через обменный пункт')?>
</a><br/>
<a href="<?=site_url('account/exchanges-list')?>">
<img src="/images/exwtlogo.png" style="height:30px;">
<img src="/images/cam24.png" style="height:30px;">
<img src="/images/trustex.jpg" style="height:30px;">
<img src="/images/wmc.png" style="height:30px;">
<img src="/images/logo-m.png" style="height:30px;">
</a>

</center>
</div>

</td></tr>
  <? } ?>
    </tbody>
</table>
<br/>

<br/><br/>


<? //$this->load->view('user/accaunt/blocks/renderPayment_qiwi', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_w1', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_lava', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_egopay', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_lavaliq', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_lavaqiwi', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? //$this->load->view('user/accaunt/blocks/renderPayment_w1_2', compact($name, $f_name, $email, $partners, $id_user) ); ?>

<? $this->load->view('user/accaunt/blocks/renderPayment_p2p', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank', compact($bank_fee, $paymenyBank, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_norvik', compact($bank_fee, $paymenyBank, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_raiffeisen', compact($bank_fee, $paymenyBank, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_okpay', compact($name, $f_name, $email, $partners, $id_user) ); ?>

<? $this->load->view('user/accaunt/blocks/renderPayment_payeer', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_perfectmoney', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_mc', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_wpay', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_nixmoney', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_wtcard', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_card', compact($name, $f_name, $email, $partners, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_card_real', compact($name, $f_name, $email, $partners, $id_user) ); ?>

<script>
        function toAll(t){
            if ($(t).prop('checked')){
                $('[name="id_user"]').show();
                $('[name="pament2id"]').hide();
                $('[name="pament2id"]').prop('disabled','disabled');
                $('[name="id_user"]').prop('disabled','');
            } else{
                $('[name="id_user"]').hide();
                $('[name="id_user"]').prop('disabled','disabled');
                $('[name="pament2id"]').show();
                $('[name="pament2id"]').prop('disabled','');
            }
        }
        function checkId(el){
            $(el).parent().find('.payment2id-loading-gif').show();
            $.get(site_url + '/login/checkUserId/'+$(el).val(), function(a){
                $(el).parent().find('.payment2id-loading-gif').hide();
                if(!a.isExist) alert('<?=_e('Такого пользователя не существует!')?>');
            }, 'json');
        }
//        function showMessage( message ) {
//            $('#popup_window .content').html('<p class="center">' + message + '</p>');
//            $('#popup_window').show('slow');
//        }

        function get_payment_permission( call_back ){
            call_back();
            return;
//            $.post(site_url + '/account/ajax_user/get_max_add_funds',function( responce ){
//                var html = '';
//                if (responce === '') {
//                    showMessage('Неверный ответ от сервера.');
//                    return;
//                }
//                try {
//                    html = JSON.parse(responce);
//                } catch (e) {
//                    showMessage('Неверный ответ от сервера.');
//                    return;
//                }
//
//                if (html['error']){
//                    showMessage(html['error']);
//                } else if (html['success']) {
//                    call_back();
//                }
//
//            });
        }

        function cahckSumm(f, e){
            var val = $('#'+f+' '+e).val();
            if (isNaN(parseInt(val))){
                $('#'+f+' div.error').show();
                return false;
            }
            if (parseFloat(val) < 1){
                $('#'+f+' div.error').show();
                return false;
            }
            if (parseFloat(val) != val){
                $('#'+f+' div.error').show();
                return false;
            }
            return true;
        }

//    $("[name=pament2id]").select2();
//    $("[name=pament2id]").combobox();
//    $("[name=pament2id]").select3({dropdownCssClass: 'searchSelect', triggerChange: true});
     $(document).ready(function() {
        $('[name=pament2id]').on('change', function(e){
            if ( e.value == 'card_create_virtual')
                window.open("<?=site_url('account/card/virtual')?>",'_blank');
            else if ( e.value == 'card_create_plastic')
                window.open("<?=$create_card_url?>",'_blank');
        });
    });


    function on_payment_account_change(select_object){
        var selected_val = $(select_object).find('option:selected').val();
        if ( selected_val == 'card_create_virtual')
            window.open("<?=site_url('account/card/virtual')?>",'_blank');
        else if ( selected_val == 'card_create_plastic')
            window.open("<?=$create_card_url?>",'_blank');

    }



</script>
