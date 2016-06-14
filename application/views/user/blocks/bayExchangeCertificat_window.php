<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="popup_window bayExchangeCertificat">
<!--    <div class="close" style="z-index: 2" onclick="$('.bayExchangeCertificat').hide();"></div>-->
    <div style="position: relative; height: 100%; width: 100%; z-index: 1">
        <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
            <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
        </div>
        <h2><?=_e('blocks/bayExchangeCertificat_window_1')?><span id="id_certificat"></span>?</h2>
        <p><?=_e('blocks/bayExchangeCertificat_window_2')?>
        <br/>
        <table>
            <tr><td><?=_e('Счет')?></td><td style="text-align:right;padding-right:10px;"><span id="bonus"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_3')?></td><td style="text-align:right;padding-right:10px;">$ <span id="out_summ"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_4')?></td><td style="text-align:right;padding-right:10px;">$ <span id="gross_profit"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_5')?></td><td style="text-align:right;padding-right:10px;"> $ <span id="conrtibutions"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_6')?></td><td style="text-align:right;padding-right:10px;"> $ <span id="summ_income"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_7')?></td><td style="text-align:right;padding-right:10px;"> $ <span id="summ"></span></td></tr>
            <tr><td><?=_e('blocks/bayExchangeCertificat_window_8')?></td><td style="text-align:right;padding-right:10px;">$ <span id="profit"></span></td></tr>
        </table>
        </p>
        <br/>

        <? if (  $accaunt_header['net_own_funds_by_bonus'][5] >= 50 && $accaunt_header['net_own_funds_by_bonus'][2] >= 50  && 1==0 ) { ?>
        <br><br><?=_e('Выберите счет для получения займа: ') ?><br>
              <select id="payment_account" name="payment_account" style="width: 200px"><?
              if ( $accaunt_header['net_own_funds_by_bonus'][5] >= 50 )
                  echo "<option value='5'>WTUSD1 - $ {$accaunt_header['net_own_funds_by_bonus'][5]}</option>";
              if ( $accaunt_header['net_own_funds_by_bonus'][2] >= 50 )
                  echo "<option value='2'>WTUSD&#10084; - $ {$accaunt_header['net_own_funds_by_bonus'][2]}</option>";
              ?></select>
          <? } else { echo '<input type="hidden" id="payment_account" value="-1">';} ?>

        <button class="button" style='display: inline-block;' id="send_bayExchangeCertificat"><?=_e('blocks/bayExchangeCertificat_window_9')?></button>
        <button class="button" style='display: inline-block;' onclick="$('.bayExchangeCertificat').hide();"><?=_e('blocks/bayExchangeCertificat_window_10')?></button>
    </div>
    <script>
        $(document).keydown(function(e) {
            switch(e.which) {
                case 13: if($(".bayExchangeCertificat").is(':visible'))
                            $("#send_bayExchangeCertificat").trigger("click");// left
                break;
                case 27: $('.bayExchangeCertificat').hide();// left
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });

        $(document).on('click', '#send_bayExchangeCertificat', function(e){
            
            <? if ( get_current_user_id() == 92156962 || 1==1) { ?>
            mn.security_module
                .init()
                .show_window('withdrawal_standart_credit')
                .done(function(res){
                    if( res['res'] != 'success' ) return false;
                    var code = res['code'];
                    $('.close').hide();
                     $('.loading-div').show();
                    window.location = site_url + "/account/exchange/bay/"+$("#send_bayExchangeCertificat").data("id") + '/norm/' + $('#payment_account').val() + '/'+code+'/'+mn.security_module.purpose;
                    console.log("submit submit");
            });
            
            <? } else { ?>
            window.location = site_url + "/account/exchange/bay/"+$("#send_bayExchangeCertificat").data("id") + '/norm/' + $('#payment_account').val();
            $('.close').hide();
            $('.loading-div').show();
            <? } ?>
        });
    </script>
</div>