<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="okpay_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Пополнение через')?> Okpay</h6>
        </div>
        <fieldset>
		<? if ($id_user == '500111') { ?>
		<br/><center>
		<?=_e('По техническим причинам пополнение через OKPAY временно недоступно.')?> <br/><Br/>
		<?=_e('Приносим свои извинения')?><br/><br/>
		<a href="http://www.ex-wt.com" target="_blank"><?=_e('Воспользоваться услугами обменника EX-WT')?></a>
		</center>
		<br/><br/>
			<? } ?>
            <form action="https://www.okpay.com/process.html" method="post" class="form" id="okpay_form">
                <input type="hidden" name="ok_receiver" value="<?=getOkpayWalletID()?>"/>
                <input type="hidden" name="ok_item_1_name" value="<?=_e('new_260') . " $name $f_name ($id_user) $email";?>"/>
                <input type="hidden" name="ok_invoice" value="order_id"/>
                <input type="hidden" name="ok_currency" value="USD"/>
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>TRUE]  ); ?>
                
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="ok_item_1_price" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                <input  type="submit" style="display:none" class="send" />
<p class="center" style="font-size:11px;"><?=_e('Комиссия при пополнении Webtransfer VISA').' - '.  config_item('card_payin_comission')[319]?>%<br/>
<?=_e('Лимиты на пополнение: 2 шт / день - макс $2,500')?></p>
                <button class="button" onclick="return false;"  id="okpay_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
		
        </fieldset>
    </div>
</div>
<script>
    $('.okpay_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#okpay_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#okpay_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("okpay_form", '[name="ok_item_1_price"]'))
            return false;

        var user_id = $('#okpay_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#okpay_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#okpay_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#okpay_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#okpay_form [name="ok_item_1_price"]').val(),
            description: $('#okpay_form [name="ok_item_1_custom_1_title"]').val(),
            metod: 'okpay',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#okpay_form input[name="ok_invoice"]').val(data.id);
            $('#okpay_form input[name="ok_item_1_price"]').val(data.change_summ);

            $('#okpay_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>