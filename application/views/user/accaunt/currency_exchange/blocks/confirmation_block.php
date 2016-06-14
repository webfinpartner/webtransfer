<div id="popup_confirme" class="popup_window_exchange valid_sendconfirmation">
    <div class="close" onclick="deactivation_button($(this).parent().find('form'))"></div>
    <h2><?= _e('currency_exchange/popup_confirme/confirm')?></h2>
    
    <div class="content_container" style="text-align: left">
        
    </div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
    
        <div class="result" style="margin-top: 15px;"></div>
        
        <div style="width: 50%; margin: 10px auto; text-align: left;"><b><?= _e('currency_exchange/popup_confirme/send_from')?> </b><br/></div>
        <div class="select_payment_syctem_container" style="width: 65%; margin: 10px auto"></div>
        <div class="clear"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <input type="hidden" name="exchange_id" value="" />
        <?= _e('currency_exchange/popup_confirme/select_doc')?> <input id="foto" type="file" name="foto" size="25" >
        <div style="margin-top: 10px;"> 
            <label><input type="checkbox" value="1" name="sms" /><?= _e('currency_exchange/popup_confirme/sms')?></label>
        </div>
        <input type="hidden" name="submit_confirm" value="1" />
        <button class="button" type="button" id="confirmation_block" onclick="validate_and_send_confirmation_form($(this).parent()); return false;" style="width: 366px; background-color: #ccc;" >
            <?= _e('currency_exchange/popup_confirme/but_confirm')?>
        </button>
    </form>
</div>