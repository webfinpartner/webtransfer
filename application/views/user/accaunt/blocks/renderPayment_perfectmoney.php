<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="perfectmoney_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Пополнение через')?> Perfectmoney</h6>
        </div>
        <fieldset>
            <form action="https://perfectmoney.is/api/step1.asp" method="POST" class="form" id="perfectmoney_form">
                <input type="hidden" name="PAYEE_ACCOUNT" value="U8258953">
                <input type="hidden" name="PAYEE_NAME" value="<?=$GLOBALS["WHITELABEL_NAME"] ?> Finance">
                <input type="hidden" name="PAYMENT_ID" value="order_id">
                <input type="hidden" name="PAYMENT_UNITS" value="USD">
                <input type="hidden" name="STATUS_URL" value="<?= site_url('ask/perfectmoneyCallback')?>">
                <input type="hidden" name="PAYMENT_URL" value="<?= site_url('account/transactions')?>">
                <input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
                <input type="hidden" name="NOPAYMENT_URL" value="<?= site_url('account/transactions')?>">
                <input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
                <input type="hidden" name="SUGGESTED_MEMO" value="<?=_e('new_260')?> <?= $name; ?> <?= $f_name; ?> (<?= $id_user; ?>) <?= $email; ?>">
                <input type="hidden" name="CLIENTID" value="<?= $id_user; ?>">
                <input type="hidden" name="CLIENTEMAIL" value="<?= $email; ?>">
                <input type="hidden" name="BAGGAGE_FIELDS" value="CLIENTID CLIENTEMAIL">
                <input type="hidden" name="PAYMENT_METHOD" value="Pay Now!">
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>TRUE]  ); ?>

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="PAYMENT_AMOUNT" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
<!--            <div class="formRow">
                    <label style="margin-top:20px;">Описание:</label>
                    <div class="formRight">
                        <input type="text" name="SUGGESTED_MEMO"    value="Пополнение счета от <?= $name; ?> <?= $f_name; ?> (<?= $id_user; ?>) <?= $email; ?>"/>
                    </div>

                </div>-->

                <input  type="submit" style="display:none" class="send" />
<p class="center" style="font-size:11px;"><?=_e('Комиссия при пополнении Webtransfer VISA').' - '.  config_item('card_payin_comission')[318]?>%<br/>
<?=_e('Лимиты на пополнение: 2 шт / день - макс $2,500')?></p>
                <button class="button" onclick="return false;"  id="perfectmoney_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.perfectmoney_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#perfectmoney_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#perfectmoney_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("perfectmoney_form", '[name="PAYMENT_AMOUNT"]'))
            return false;

        var user_id = $('#perfectmoney_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#perfectmoney_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#perfectmoney_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#perfectmoney_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#perfectmoney_form [name="PAYMENT_AMOUNT"]').val(),
            description: $('#perfectmoney_form [name="SUGGESTED_MEMO"]').val(),
            metod: 'perfectmoney',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#perfectmoney_form input[name="PAYMENT_ID"]').val(data.id);
            $('#perfectmoney_form input[name="PAYMENT_AMOUNT"]').val(data.change_summ);

            $('#perfectmoney_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>

