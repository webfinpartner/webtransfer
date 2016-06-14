<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup" id="qiwi_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Пополнение «QIWI-Кошелёк»')?></h6>
        </div>
        <fieldset>
            <form action="https://w.qiwi.com/order/external/create.action" class="form"  name="qiwi" id="qiwi_form">
                <input  type="hidden" value="247773"  name="from" />
                <input  type="hidden" value="<?=_e('new_261')?> <?=$GLOBALS["WHITELABEL_NAME"] ?>"  name="comm" />
                <input  type="hidden" value="<?= site_url('account/transactions') ?>"  name="successUrl" />
                <input  type="hidden" value="0"  name="txn_id" />
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>


                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('Телефон:')?></label>
                    <div class="formRight">
                        <input type="text" value="+7<?= preg_replace('/[\(\) -]/sui', '', $phone) ?>" name="to" />
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('Сумма:')?></label>
                    <div class="formRight">
                        <input  type="text"  name="summ" id="qiwi_summa" class="maskMoneyCustom" value="<?= (!empty($_SESSION['invest_summa'])) ? $_SESSION['invest_summ'] : "" ?>"/>
                    </div>

                </div>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="qiwi_send" name="submit" value=""><?=_e('Оформить')?></button>

            </form></fieldset>
        <script>$("#qiwi_form").validationEngine('attach')</script>

    </div>
</div>
<script>
    $('#qiwi_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("qiwi_form", 'input[name="summ"]'))
            return false;

        var user_id = $('#qiwi_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#qiwi_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#qiwi_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $.post(site_url + '/account/pay', {
            summa: $('#qiwi_form input[name="summ"]').val(),
            metod: 'qiwi',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            data.id = $.trim(data.id);
            $('#qiwi_form input[name="txn_id"]').val(data.id);
            $('#qiwi_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>