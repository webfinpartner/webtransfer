<div id="popup_select_payment_system" class="popup_window_exchange valid_sendconfirmation" style="position: absolute; margin-top: -100px;">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/select_payment_system_block/select_ps')?></h2>
    
    <div class="content_container" style="text-align: left; display: none;"></div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
        <div class="result" style="margin-top: 15px;"></div>
        
        <div style="display: none; width: 50%; margin: 10px auto; text-align: left;">
            <b><?= _e('currency_exchange/select_payment_system_block/get_from')?></b><br/>
        </div>
        <div class="select_payment_syctem_container_sell select_payment_syctem_container" style="display: none; width: 65%; margin: 10px auto; padding-left: 10px;"></div>
        <div class="clear"></div>
        
        <div style="width: 50%; margin: 10px auto; text-align: left;">
            <b><?= _e('currency_exchange/select_payment_system_block/send_from')?></b><br/>
        </div>
        <div class="select_payment_syctem_container_buy select_payment_syctem_container" style="width: 65%; margin: 10px auto"></div>
        <div class="clear"></div>
        
        <input type="hidden" name="exchange_id" value="" />
        <div class="foto_container select_file_confirm_block"> 
            <?= _e('Документ подтверждения оплаты')?> 
            <input id="foto" type="file" name="foto" size="25" >
            <br/>
            <br/>
            <span class="warning_about_file_size">(<?=_e('Размер файла не должен превышать 2Mb и быть в формате .jpg, .png или .gif ');?>)</span>
            <div class="confirm_docs_caution"><?=_e('currency_exchange/confirm_docs_caution');?></div>
        </div>
        <div style="margin-top: 10px; display: none;"> 
            <label>
                <input type="checkbox" value="1" name="sms" /> 
                 <?= _e('currency_exchange/select_payment_system_block/sms')?> 
            </label>
        </div>
        <input type="hidden" name="select_payment_system_submit_confirm" value="1" />
        <!--<button class="button" type="button" id="confirmation_block" onclick="validate_and_send_confirmation_form($(this).parent()); return false;" style="width: 366px; background-color: #ccc;" >-->
        <button class="button" type="button" id="confirmation_block" onclick="validate_and_send_confirmation_form_secure($(this).parent(), this); return false;" style="width: 366px; background-color: #ccc;" >
            <?= _e('currency_exchange/select_payment_system_block/but_confirm')?>
        </button>
        <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
        <center class="select_ps" style="color: red; font-size: 15px; display: none;"><?= _e('Пожалуйста, выберите метод, которым вы произвели оплату, и повторите попытку'); ?></center>
    </form>
</div>