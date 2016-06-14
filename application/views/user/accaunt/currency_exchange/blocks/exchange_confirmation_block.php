<div id="popup_exchange" class="popup_window_exchange" style="position: absolute; margin-top: -100px;">
    <div class="close"></div>
    <h2><?= _e('currency_exchange/exchange_confirmation_block/order')?> </h2>

    <?php printf(_e('currency_exchange/exchange_confirmation_block/text'), site_url('page/about/exchange_terms'))?>

    <div class="clear"></div>
    <form method="POST" action="">
        <div style="width: 50%; margin: 10px auto; text-align: left;" class="confirm_order_text_1"><b><?= _e('currency_exchange/exchange_confirmation_block/get_from')?></b><br/></div>
        <div class="select_payment_syctem_container_sell select_payment_syctem_container" style="width: 60%; margin: 10px auto; display: inline-block;"></div>
        
        <div class="payment_container"></div>
        <div class="payment_container_visa"></div>
        
        <div style="margin-top: 20px;"> 
            <label>
                <input type="checkbox" value="1" name="correct"  /> 
                <?= _e('currency_exchange/exchange_confirmation_block/confirm_correct')?>
            </label>
        </div>
        
        <div style="margin-top: 10px; display: none;"> 
            <label>
                <input type="checkbox" value="1" name="sms" /> 
                <?= _e('currency_exchange/exchange_confirmation_block/sms')?>
            </label>
        </div>
        
        <button class="button" type="button" id="exchange_confirm_buntton" ><?= _e('currency_exchange/exchange_confirmation_block/but_confirm')?></button>
        <button class="button" type="button" id="exchange_confirm_buntton_visa" style="display:none"><?= _e('currency_exchange/exchange_confirmation_block/but_confirm')?></button>

        <div class="result" style="margin-top: 15px; font-weight: bold; font-size: 16px;"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
        <input type="hidden" name="exchange_id" value="" />
    </form>
</div>