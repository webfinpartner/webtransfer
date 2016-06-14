<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup" id="mc_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="mc_form" class="w400" action="https://www.liqpay.com/api/pay" accept-charset="utf-8">

                <input type="hidden" name="public_key" value="i2194089800" />
                <input type="hidden" name="currency" value="USD" />
                <input type="hidden" name="type" value="buy" />
                <input type="hidden" name="language" value="ru" />
                <input type="hidden" name="result_url"    value="<?= site_url('account/transactions')?>"/>
                <input type="hidden" name="server_url"       value="<?= site_url('ask/liq_pay')?>"/>
                <input type="hidden" name="order_id"    	value=""/>
                <input type="hidden" name="signature" value=""/>
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="amount" id="mc_amount" class="maskMoneyCustom" value="<?= (!empty($_SESSION['invest_summa'])) ? $_SESSION['invest_summa'] : "" ?>"/>
                    </div>

                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>

                </div>

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data17')?></label>
                    <div class="formRight">
                        <input type="text" name="description"    value="<?=_e('new_260')?> <?= $name; ?> <?= $f_name; ?> (<?= $id_user; ?>) <?= $email; ?>"/>
                        <input  type="submit" style="display:none" class="send" />
                    </div>
                </div>
                <center>
                    <button class="button" onclick="return false;" id="mc_send" name="submit"><?=_e('block/data11')?></button>
                    <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                    <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
                </center>
            </form>
        </fieldset>
    </div>
</div>
<script>
    $('.mc_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
            $("#mc_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#mc_send').click(function() {
        $('p.error').hide();
        if(!cahckSumm("mc_form", '[name="amount"]'))
            return false;

        var user_id = $('#mc_form [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#mc_form .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#mc_form .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#mc_form div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: $('#mc_form [name="amount"]').val(),
            description: $('#mc_form [name="description"]').val(),
            metod: 'mc',
            result_url: $('#mc_form [name="result_url"]').val(),
            server_url: $('#mc_form [name="server_url"]').val(),
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            $('.loading-gif').hide();
            $('#mc_form input[name="order_id"]').val(data.id);
            $('#mc_form input[name="signature"]').val(data.sign);

            $('#mc_form .send').trigger('click');
        }, 'json');
        return false;
    });
</script>