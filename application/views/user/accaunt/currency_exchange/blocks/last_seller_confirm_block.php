<div id="last_seller_confirm_block" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/last_seller_confirm_block/confirm')?></h2>
    
    <div class="content_container" style="text-align: left; width: 363px; margin: 0 auto 15px;">
        <div><b><?= _e('currency_exchange/last_seller_confirm_block/contr')?></b> <span class="content_container_fio"></span></div>
        <div><b><?= _e('currency_exchange/last_seller_confirm_block/method')?></b> <span class="content_container_method"></span></div>
        <div><b><?= _e('currency_exchange/last_seller_confirm_block/summ')?></b> <span class="content_container_summ"></span></div>
    </div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
        <div style="width: 400px; margin: 0 auto; color: red;">
            <?php echo _e('Если вы не получили средства, нажмите кнопку "Сообщить о проблеме" на предыдущей странице.'); ?>
        </div>
        
        <br/>
        <br/>
        <label>
            <input type="checkbox" name="last_user_confirm_checkbox" value="1" />
            <?= _e('currency_exchange/last_seller_confirm_block/i_get')?>
        </label>
        <input type="hidden" value="1" name="seller_confirm">
        <input type="hidden" value="" name="confirm_id">
        
        
        <button class="button" type="button" id="confirmation_block" onclick="send_confirmation_form_secure($(this).parent())" ><?= _e('currency_exchange/last_seller_confirm_block/but_confirm')?></button>
        <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
    </form>
</div>