<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="bank_payment_norvik" style="width: 400px; margin-left: -256px;">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data4')?></h6>
        </div>
        <? if(2 <= $paymenyBank){ ?>
        <p>
            <?=_e('block/data5')?>
        </p>
    </div>
</div>
        <?} else {?>
       <form  class="form" >
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('block/data6')?></label>
                <div class="formRight">
                    <input  type="text" class="maskMoneyCustom" name="summa" style="height:23px;" value="<?= (!empty($_SESSION['invest_summa'])) ? $_SESSION['invest_summ'] : "" ?>"/>
                    <div class="error" style="display:none" ><?=_e('block/data7')?></div>
                    <div class="error1" style="display:none" ><?=_e('block/data8')?></div>
                </div>
            </div>
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('block/data9')?></label>
                <div class="formRight">
                    <input  type="text"  style="height:23px;" value="<?=$bank_fee['bank_norvik']?>" disabled="disabled"/>
                </div>
            </div>
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('block/data10')?></label>
                <div class="formRight">
                    <input id="summ_norvik_invose"  type="text"  style="height:23px;" value="" disabled="disabled"/>
                </div>
            </div>
        </form>
    </div>
    <center>
        <button class="button" onclick="return false;" onclick=" return  false;" id="print_check_norvik" type="submit" name="submit" value=""><?=_e('block/data11')?></button>
        <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
        <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
    </center>
</div>
<div class="popup_window" id="order_bank_norvik_print" style="padding: 0px; margin-right: -200px; width: 495px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');window.location.reload();" class="close"></div>
    <h2><?=_e('block/data14')?></h2>

    <button class="button" onclick="return false;" id="check_print_norvik" name="submit"><?=_e('block/data13')?></button>
</div>
        <?}?>

<script>
    $('#bank_norvik_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
            $("#bank_payment_norvik").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#bank_payment_norvik input[name="summa"]').keyup(function(){
        var s = parseInt($('#bank_payment_norvik input[name="summa"]').val());
        if (!isNaN(s) && 1 < s){
            $("#summ_norvik_invose").val(s+<?=$bank_fee['bank_norvik']?>);
        } else {
            $("#summ_norvik_invose").val('');
        }
    });
    $('#print_check_norvik').click(function(){
        $('p.error').hide();
        if(!cahckSumm("bank_payment_norvik", 'input[name="summa"]'))
            return false;
        var s = parseInt($('input[name="summa"]').val());

        if (10000 < s) {
            $('#bank_payment_norvik .error1').show();
            return false;
        }

        var user_id = $('#bank_payment_norvik [name="pament2id"] .select3-single-selected-item').data('item-id');
        var all = 'false';
        if($('#bank_payment_norvik .payment2id [name="to_all"]').prop('checked')){
            user_id = $('#bank_payment_norvik .payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('.loading-gif').show();
        $.post(site_url + '/account/pay_invest_arbitrage', {
            summa: $('#bank_payment_norvik input[name="summa"]').val(),
            metod: 'bank_norvik',
            user_id: user_id,
            all: all
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            data.id = $.trim(data.id);
            if (data.id >= 1) {
                $('.loading-gif').hide();
                $('#check_print_norvik').attr('rel', data.id);
                $('#bank_payment_norvik').hide();
                $('#order_bank_norvik_print').show('slow');
            } else {
                $('.loading-gif').hide();
                $('p.error').show();
            }
        }, 'json');
    });
    $('#check_print_norvik').click(function() {
        $('#order_bank_norvik_print').hide('slow');
        window.open(site_url + '/account/getcheckarbitrage/' + $(this).attr('rel'), '_blank');
        $("#secondary-nav a[href*=payment]").trigger('click');
    });
</script>