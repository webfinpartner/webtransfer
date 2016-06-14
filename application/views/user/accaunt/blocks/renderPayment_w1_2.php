<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup" id="w1_payment2">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('new_266')?></h6>
        </div>
        <fieldset>

            <form method="post" id="w1_form" action="https://www.walletone.com/checkout/default.aspx" accept-charset="UTF-8">
                <input type="hidden" name="WMI_MERCHANT_ID"    value="179842778302"/>
                <input type="hidden" name="WMI_SUCCESS_URL"    value="<?= site_url('account/money')?>"/>
                <input type="hidden" name="WMI_FAIL_URL"       value="<?= site_url('account/no_money')?>"/>
                <input type="hidden" name="WMI_CURRENCY_ID"    value="643"/>
                <input type="hidden" name="WMI_CUSTOMER_FIRSTNAME"    value="<?php echo $name; ?>"/>
                <input type="hidden" name="WMI_CUSTOMER_LASTNAME"   	value="<?php echo $f_name; ?>"/>
                <input type="hidden" name="WMI_CUSTOMER_EMAIL "    	value="<?php echo $email; ?>"/>
                <input type="hidden" name="WMI_PAYMENT_NO"    	value=""/>
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_270')?>:</label>
                    <div class="formRight">
                        <input type="text" value="<?php echo $email; ?>" name="WMI_RECIPIENT_LOGIN" />
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_264')?>:</label>
                    <div class="formRight">
                        <input type="text" name="WMI_PAYMENT_AMOUNT" class="maskMoneyCustom" value="<?= (!empty($_SESSION['invest_summa'])) ? $_SESSION['invest_summa'] : "" ?>"/>
                    </div>

                </div>
                <input  type="submit" style="display:none" class="send" />
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_265')?>:</label>
                    <div class="formRight">
                        <input type="text" name="WMI_DESCRIPTION"    value="<?=_e('new_262')?> <?= $name; ?> <?= $f_name; ?> (<?= $id_user; ?>) <?= $email; ?>"/>
                    </div>

                </div>
                <button class="button" onclick="return false;"  id="w1_send" name="submit" value=""><?=_e('new_269')?></button>

            </form>
        </fieldset>
    </div>
</div>

<script>
    $('.w1_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#w1_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#w1_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("qiwi_form", 'input[name="summ"]'))
            return false;

        var user_id = $('#w1_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#w1_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#w1_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $.post(site_url + '/account/pay', {
            summa: $('#w1_form [name="WMI_PAYMENT_AMOUNT"]').val(),
            metod: 'w1',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            data.id = $.trim(data.id);
            $('#w1_form input[name="WMI_PAYMENT_NO"]').val(data.id);
            $('#w1_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>