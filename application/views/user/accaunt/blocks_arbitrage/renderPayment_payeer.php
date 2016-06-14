<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="payeer_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
                <form method="GET" action="//payeer.com/merchant/" class="form" id="payeer_form">
                <input type="hidden" name="m_shop" value="24650396">
                <input type="hidden" name="m_orderid" value="order_id">
                <input type="hidden" name="m_curr" value="USD">
<!--                <input type="hidden" name="other" value="<?="Пополнение счета от $name $f_name ($id_user) $email";?>">-->
                <input type="hidden" name="m_desc" value="<?=base64_encode(_e('new_260') . " $name $f_name ($id_user) $email")?>">
                <input type="hidden" name="m_sign" value="sign">

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="m_amount" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
            <? make(Content::BLOCK_ARBITRAGE_PART, "offert");?>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="payeer_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.payeer_trigger').click(function() {
        resetWindowArbitrage();
        $('p.error').hide();
        get_payment_permission(function(){
           $("#payeer_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#payeer_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("payeer_form", '[name="m_amount"]'))
            return false;

        $('#payeer_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/arbitrage/arbitrage_invest_pay', {
            summa: $('#payeer_form [name="m_amount"]').val(),
            description: $('#payeer_form [name="m_desc"]').val(),
            metod: 'payeer',
            agree: $('#payeer_form [name="agree"]').prop('checked')
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#payeer_form input[name="m_orderid"]').val(data.id);
            $('#payeer_form input[name="m_sign"]').val(data.sign);

            $('#payeer_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>

