<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="wpay_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('new_262')?></h6>
        </div>
        <fieldset>
            <form action="https://wpay.net/merchantapi" method="post" class="form" id="wpay_form">
                <input type="hidden" name="wp_description" value="<?=_e('new_260') . " $name $f_name ($id_user) $email";?>">
                <input type="hidden" name="wp_currency" value="USD">
                <input type="hidden" name="wp_merchant_id" value="<?=getWpayMerchantID()?>">
                <input type="hidden" name="wp_order_id" value="order_id">
                <input type="hidden" name="wp_user_email" value="<?=$email?>">

    <!--                <input type="hidden" name="wp_ia_u" value="http://example.com/interaction">
                <input type="hidden" name="wp_pnd_u" value="http://example.com/pending">-->
                <input type="hidden" name="wp_suc_u" value="<?= site_url('account/transactions')?>">
                <input type="hidden" name="wp_fal_u" value="<?= site_url('account/payment')?>">
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_264')?>:</label>
                    <div class="formRight">
                        <input type="text" name="wp_amount" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('new_263')?>$1.</div>
                </div>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="wpay_send" name="submit" value=""><?=_e('new_269')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('new_268')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.wpay_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#wpay_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#wpay_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("wpay_form", '[name="wp_amount"]'))
            return false;

        var user_id = $('#wpay_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#wpay_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#wpay_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#wpay_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#wpay_form [name="wp_amount"]').val(),
            description: $('#wpay_form [name="wp_description"]').val(),
            metod: 'wpay',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#wpay_form input[name="wp_order_id"]').val(data.id);

            $('#wpay_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>
