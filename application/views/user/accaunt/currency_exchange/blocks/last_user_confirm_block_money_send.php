<div id="last_user_confirm_block_money_send" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/last_user_confirm_block_money_send/confirm')?></h2>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
    
        <div class="content_container_contragent" style="text-align: left;  width: 363px; margin: 15px auto;">
            <div><?= _e('currency_exchange/last_user_confirm_block_money_send/contr_wait')?> </div>
            <div><?= _e('currency_exchange/last_user_confirm_block_money_send/ackount')?></div>
        </div>

        <div class="select_file_confirm_block">
            <?= _e('Документ подтверждения оплаты')?> 
            <input id="foto" type="file" name="foto" size="25" data-m-name="" >
            <br/>
            <br/>
            <span class="warning_about_file_size">(<?=_e('Размер файла не должен превышать 2Mb и быть в формате .jpg, .png или .gif ');?>)</span>
            <div class="confirm_docs_caution"><?=_e('currency_exchange/confirm_docs_caution');?></div>
        </div>
    
        <div class="result" style="margin-top: 15px;"></div>
        <div class="clear"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <input type="hidden" value="1" name="confirm_money_send">
        <input type="hidden" value="" name="confirm_id">
        <button class="button" type="button" id="confirmation_block" onclick="send_confirmation_form_money_send($(this).parent())" >
            <?= _e('currency_exchange/last_user_confirm_block_money_send/but_confirm')?>
        </button>
    </form>
</div>