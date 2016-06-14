<div id="buyer_confirm_receipt" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/buyer_confirm_receipt/confirm')?></h2>
    
    <div class="content_container" style="text-align: left; width: 363px; margin: 0 auto 15px;">
        <div><b><?= _e('currency_exchange/buyer_confirm_receipt/contr')?></b> <span class="content_container_fio"></span></div>
        <div><b><?= _e('currency_exchange/buyer_confirm_receipt/method')?></b> <span class="content_container_method"></span></div>
        <div><b><?= _e('currency_exchange/buyer_confirm_receipt/summ')?></b> <span class="content_container_summ"></span></div>
    </div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
    <!--<button class="button" type="submit" id="confirmation_block" style="width: 366px;" >Подтвердить отправку денег</button>-->
        
        <label>
            <input type="checkbox" name="last_user_confirm_checkbox" value="1" />
            <?= _e('currency_exchange/buyer_confirm_receipt/i_get')?>
        </label>
    
        
        <div class="result" style="margin-top: 20px;"></div>
        <div class="clear"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <input type="hidden" value="1" name="buyer_confirm_receipt">
        <input type="hidden" value="" name="confirm_id">
        <button class="button" type="button" id="confirmation_block" onclick="send_last_confirmation_form($(this).parent())" ><?= _e('currency_exchange/buyer_confirm_receipt/but_confirm')?></button>
    </form>
</div>