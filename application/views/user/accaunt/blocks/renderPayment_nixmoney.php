<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="nixmoney_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Пополнение через')?> Nixmoney</h6>
        </div>
        <fieldset>
		
			<? if ($id_user == '500111') { ?>				
		<br/><center>
		<?=_e('По техническим причинам пополнение через NIXMONEY временно недоступно.')?> <br/><Br/>
		<?=_e('Приносим свои извинения')?><br/><br/>
		<a href="http://www.ex-wt.com" target="_blank"><?=_e('Воспользоваться услугами обменника EX-WT')?></a>
		</center>
		<br/><br/>
	<? } ?>		
			

            <form action="https://www.nixmoney.com/merchant.jsp" method="post" class="form" id="nixmoney_form">
                <input type="hidden" name="PAYEE_ACCOUNT" value="<?=getNixMonayID()?>"/>
                <input type="hidden" name="PAYEE_NAME" value="<?=$GLOBALS["WHITELABEL_NAME"] ?> Finance"/>
                <input type="hidden" name="SUGGESTED_MEMO" value="<?=_e('new_260') . " $name $f_name ($id_user) $email";?>"/>
                <input type="hidden" name="PAYMENT_ID" value="order_id"/>
                <input type="hidden" name="BAGGAGE_FIELDS" value="PAYMENT_ID PAYEE_ACCOUNT"/>
                <input type="hidden" name="PAYMENT_UNITS" value="USD"/>
                <input type="hidden" name="PAYMENT_URL" value="<?= site_url('account/transactions')?>">
		<input type="hidden" name="NOPAYMENT_URL" value="<?= site_url('account/payment')?>">
		<input type="hidden" name="STATUS_URL" value="<?= site_url('ask/nixmoneyCallback')?>">
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>TRUE]  ); ?>

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="PAYMENT_AMOUNT" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                <input  type="submit" style="display:none" class="send" />
<p class="center" style="font-size:11px;"><?=_e('Комиссия при пополнении Webtransfer VISA').' - '.  config_item('card_payin_comission')?>%<br/>
<?=_e('Лимиты на пополнение: 2 шт / день - макс $2,500')?></p>

                <button class="button" onclick="return false;"  id="nixmoney_send" name="submit" value=""><?=_e('block/data11')?></button>
				
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.nixmoney_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#nixmoney_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#nixmoney_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("nixmoney_form", '[name="PAYMENT_AMOUNT"]'))
            return false;

        var user_id = $('#nixmoney_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#nixmoney_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#nixmoney_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#nixmoney_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#nixmoney_form [name="PAYMENT_AMOUNT"]').val(),
            metod: 'nixmoney',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#nixmoney_form input[name="PAYMENT_ID"]').val(data.id);
            $('#nixmoney_form input[name="PAYMENT_AMOUNT"]').val(data.change_summ);

            $('#nixmoney_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>