<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="security_module popup_window" style="z-index:99999;">
        <div onclick="$(this).parent().hide('slow');" class="close"></div>
        <h2><?= _e('Двух этапная авторизация') ?></h2>
        <div id="sms-code" class="formRow padding10-40" style="border-bottom:0; overflow: auto;О">
            <label class="label" style="float: left; margin-top: 4px;"><?= _e('Код подтверждения'); ?></label>
            <div class="formRight" style="width: 64% !important;float: right;">
                <div class="sms-content" style="width: 78%;">
                    <input class="form_input" type="text" value="" style="height: 17px;width: 130px; margin-right: 10px;"/>
                    <a class="but blueB ml10 right" onclick="mn.security_module.get_code(); return false;" href="#" style="margin-top: 4px"><?= _e('Запрос СМС'); ?></a>
                </div>
            </div>
            <div class="res-message"></div>
            
        </div>
        <a class="button narrow" onclick="mn.security_module.confirm_code();">Ok</a>
        <center>
            <img class="loader" style="display: none" src="/images/loading.gif"/>
        <center>
    </div>
  
<? /*
<script>
    $(function(){
         $('#out_send_window_sms .send_code_link').unbind('click');
         $('#out_send_window_sms .send_code_link').off();
         $(document).on('click','#out_send_window_sms .send_code_link', function(){
             var $this = $(this);

             if( $(this).hasClass('closed') ) return false;
             $(this).addClass('closed');

             $('#out_send_window_sms').data('count',1);
             console.log('on click');
             window.purpose = 'save_p2p_payment_data';
    
             d_sendSms(function(){
                 $this.removeClass('closed');
                 window.purpose = 'withdrawal_standart_credit';
             }, '/account/ajax_user/sms_request','#out_send_window_sms');
             return false;
         });

         $(document).on('click','#out_send_window_sms .button', function() {
             //$('#out_send_window_sms .send_code_link').css('display', 'block');
             var wrongs = 0,
             strFun = $('#out_send_window_sms').data('call-back');

             wrongs = d_checkField('#code_sms', wrongs);

             if (wrongs)
                 return false;

             $('#out_send_window_sms').hide('slow');
             //console.log( strFun );
             if( strFun !== undefined ) strFun();
             window.purpose = 'withdrawal_standart_credit';
         });

         $("#out_send_window_sms").keydown(function(e) {//console.log("sms",e.which);
             switch(e.which) {
                 case 13: if($("#out_send_window_sms").is(':visible'))
                             $("#out_send_window_sms .button").trigger("click");// left
                 break;
                 case 27: $('.out_send_window_sms').hide();// left
                 break;

                 default: return; // exit this handler for other keys
             }
             e.preventDefault(); // prevent the default action (scroll / move caret)
         }); 
    });
</script>
 */ ?>