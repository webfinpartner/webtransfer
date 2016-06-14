<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .maskMoneyCustom.error{
        border: 1px solid red !important;
        box-shadow: 0 0px 6px red  !important;
    }
</style>
<div class="popup lava_payment" id="p2p_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
                <div class="formRow payment2id">
                    <label style="margin-top:16px;"><?=_e('Счет')?></label>
                    <div class="formRight">
                        <select  name="pament2id" class="form_select" style="margin: 10px 0; width: 90%">
                            <option value="6">Webtransfer DEBIT</option>
                            <option value="7">Webtransfer VISA</option>
                        </select>
                    </div>
                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('Сумма')?></label>
                    <div class="formRight">
                        <input type="text" name="m_amount" class="maskMoneyCustom" value=""/>
                    </div>
                </div>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="p2p_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
        </fieldset>
    </div>
</div>
<script>
    function p2p_trigger_click() {
        $('p.error').hide();
        $("#p2p_payment").fadeIn();
    };

    $('#p2p_send').click(function() {
        $('input.error').removeClass('error');


        var pa = $('#p2p_payment [name="pament2id"] .select3-single-selected-item').data('item-id');
        var amount =  $('input[name="m_amount"]').val();

        if( amount == '' || amount == 0 )
        {
            $('.maskMoneyCustom').addClass('error');
            return false;
        }

//        $('.loading-gif').show();

        var currency = 'wt';
        switch( pa )
        {
            case 2: currency = 'wt_heart'; break;//usd-h
            case 4: currency = 'wt_c_creds'; break;
            case 5: currency = 'wt'; break;//usd1
            case 6: currency = 'wt_debit_usd'; break;//usd1
            case 7: currency = 'wt_visa_usd'; break;//usd1
            default: currency = 'wt_debit_usd';
        }

        window.open("<?=site_url('account/currency_exchange/sell_search')?>?action=add_money&currency="+currency+'&summa='+amount);
        //location.replace("<?=site_url('account/currency_exchange/sell_search')?>?action=add_money&currency="+currency+'&summa='+amount);

        $('#p2p_payment').fadeOut();

        return false;
    });
</script>

