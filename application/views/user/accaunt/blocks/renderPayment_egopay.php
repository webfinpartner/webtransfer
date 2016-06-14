<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!-- Egopay Pop Ups -->

<div class="popup lava_payment" id="egopay_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
            <form action="https://www.egopay.com/payments/pay/form" method="post" class="form" id="egopay_form">
                <input type="hidden" value="<?=getEgopayStore()?>" name="store_id" />
                <input type="hidden" value="verify" name="verify" />
                <input type="hidden" value="USD" name="currency" />
                <input type="hidden" value="<?=_e('new_260') . "$name $f_name ($id_user) $email";?>" name="description" />
                <input type="hidden" value="order_id" name="cf_1" />
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>

		<div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="amount" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="egopay_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.egopay_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#egopay_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#egopay_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("egopay_form", '[name="amount"]'))
            return false;

        var user_id = $('#egopay_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#egopay_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#egopay_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        if ($('#egopay_form [name="amount"]').val() < 1){
            $('#egopay_form div.error').show();
            return false;
        }
        $('#egopay_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#egopay_form [name="amount"]').val(),
            description: $('#egopay_form [name="description"]').val(),
            metod: 'egopay',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#egopay_form input[name="cf_1"]').val(data.id);
            $('#egopay_form input[name="verify"]').val(data.sign);

            $('#egopay_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>