<div id="popup_exchange_wt" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/exchange_confirmation_wt_block/order')?></h2>
    <?php printf(_e('currency_exchange/exchange_confirmation_wt_block/text'), site_url('page/about/exchange_terms'))?>

    <div class="clear"></div>
    <form method="POST" action="">
        <div style="width: 50%; margin: 10px auto; text-align: left;">
            <b><?//= _e('currency_exchange/exchange_confirmation_wt_block/get_from')?></b>
            <b><?=sprintf(_e('%s получит средства через:'), $curent_user_data->name_sername)?></b>
            <br/>
        </div>
        <div class="select_payment_syctem_container_sell select_payment_syctem_container" style="width: 60%; margin: 10px auto; display: inline-block;"></div>
        
        <div class="payment_container"></div>
        <div style="margin-top: 10px; display: none;"> 
            <label>
                <input type="checkbox" value="1" name="sms" /> 
                <?= _e('currency_exchange/exchange_confirmation_wt_block/sms')?>
            </label>
        </div>
        
        <input type="hidden" value="1" name="correct" />
        <button class="button" type="button" id="exchange_confirm_buntton_wt" >
            <?= _e('currency_exchange/exchange_confirmation_wt_block/but_confirm')?>
        </button>

        <div class="result" style="margin-top: 15px; font-weight: bold; font-size: 16px;"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
        <input type="hidden" name="exchange_id" value="" />
    </form>
    <!--<button class="button" type="submit" class="close" style="display: none;" >Ok</button>-->
</div>