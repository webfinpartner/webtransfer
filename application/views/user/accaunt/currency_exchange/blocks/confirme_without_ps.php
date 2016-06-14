<div id="popup_confirme_without_ps" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/confirme_without_ps/confirm')?></h2>
    
    <div class="content_container" style="text-align: left;"></div>
    
    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
        <div class="result" style="margin-top: 15px;"></div>
        <div style="width: 50%; margin: 10px auto; text-align: left; display: none;">
            <b><?= _e('currency_exchange/confirme_without_ps/send_from')?></b>
            <br/>
        </div>
        <div class="select_payment_syctem_container" style="width: 60%; margin: 10px auto; display: none;"></div>
        <div class="clear"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <input type="hidden" name="exchange_id" value="" />
        <?= _e('currency_exchange/confirme_without_ps/select_doc')?><input id="foto" type="file" name="foto" size="25" >
        <input type="hidden" name="submit_confirm" value="1" />
        <button class="button" type="submit" id="confirmation_block" style="width: 366px;" >
            <?= _e('currency_exchange/confirme_without_ps/but_confirm')?>Выбрать
        </button>
    </form>
</div>