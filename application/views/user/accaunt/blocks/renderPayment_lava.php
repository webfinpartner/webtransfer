<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!-- Lava Pay Pop Ups -->


<div class="popup lava_payment" id="lava_payment">
    <div class="close" onclick=""></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
		<form  method="post" id="lavapay_form" action="https://www.Lavapay.com/ru/checkout/" class="form">
		<input type="hidden" name="LavaReceiver" value="LP640270414"/>
		<input type="hidden" name="LavaCurrency" value="USD"/>
		<input type="hidden" name="LavaItem1Type" value="Digital"/>

		<input type="hidden" name="LavaItem1Custom1Title" value="order_id">
		<input type="hidden" name="LavaItem1Custom1Value" value="">
		<input type="hidden" name="LavaItem1Custom2Title" value="id_user">
		<input type="hidden" name="LavaPayDirect" value="LAVAP">
		<input type="hidden" name="LavaItem1Custom2Value" value="<?= $id_user; ?>">
		<input type="hidden" name="LavaUrlSuccess" value="<?= site_url('account/transactions')?>">
		<input type="hidden" name="LavaUrlFail" value="<?= site_url('account/payment')?>">
		<input type="hidden" name="LavaUrlCallback" value="<?= site_url('ask/lavapaycallback')?>">
		<input type="hidden" name="LavaBuyerEmail" value="<?= $email; ?>">
                <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>

                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" class="maskMoneyCustom" name="LavaItem1Price" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data17')?></label>
                    <div class="formRight">
                        <input type="text" name="LavaItem1Name" value="<?=_e('new_260')?> <?= $name; ?> <?= $f_name; ?> (<?= $id_user; ?>) <?= $email; ?>"/>
                    </div>

                </div>

                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="lavapay_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form></fieldset>
    </div>
</div>
<script>
    $('.lava_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
            $("#lava_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#lavapay_send').click(function() {
                $('p.error').hide();
                if(!cahckSumm("lavapay_form", '[name="amount"]'))
                    return false;

                var user_id = $('#lavapay_form [name="pament2id"] .select3-single-selected-item').data('item-id');
                var all = 'false';
                if($('#lavapay_form .payment2id [name="to_all"]').prop('checked')){
                    user_id = $('#lavapay_form .payment2id [name="id_user"]').val();
                    all = 'true';
                }

                $('#lavapay_form div.error').hide();
                $('.loading-gif').show();
                $.post(site_url + '/account/pay', {
                    summa: $('#lavapay_form [name="LavaItem1Price"]').val(),
                    description: $('#lavapay_form [name="LavaItem1Name"]').val(),
                    metod: 'lava',
                    user_id: user_id,
                    all: all
                },
                function(data) {
                    if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
                    $('.loading-gif').hide();
                    $('#lavapay_form input[name="LavaItem1Custom1Value"]').val(data.id);

                    $('#lavapay_form .send').trigger('click');
                }, 'json');
                return false;
            });
</script>