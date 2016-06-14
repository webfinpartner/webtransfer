<div id="real_last_user_confirm_block" class="popup_window_exchange" style="width: 450px;padding: 15px;left: 30%;">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/real_last_user_confirm_block/confirm')?></h2>
    
    <div class="content_container" style="text-align: left; width: 363px; margin: 0 auto 15px;">
        <div><b><?= _e('currency_exchange/real_last_user_confirm_block/contr')?></b> <span class="content_container_fio"></span></div>
        <div><b><?= _e('currency_exchange/real_last_user_confirm_block/method')?></b> <span class="content_container_method"></span></div>
        <div><b><?= _e('currency_exchange/real_last_user_confirm_block/summ')?></b> <span class="content_container_summ"></span></div>
    </div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
        
        <label>
            <input type="checkbox" name="last_user_confirm_checkbox" value="1" />
            <?= _e('currency_exchange/real_last_user_confirm_block/i_get')?>
        </label>
    
        
        <div class="result" style="margin-top: 20px;"></div>
        <div class="clear"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <input type="hidden" value="1" name="real_last_confirm">
        <input type="hidden" value="" name="confirm_id">
        <button class="button" type="button" id="confirmation_block" onclick="send_last_confirmation_form($(this).parent())" >
            <?= _e('currency_exchange/real_last_user_confirm_block/but_confirm')?>
        </button>
    </form>
</div>