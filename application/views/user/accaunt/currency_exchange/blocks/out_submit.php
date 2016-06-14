<div id="popup_debit" class="popup_window_exchange">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/out_submit/order')?></h2>
    <?
    if(strpos($_SERVER['REQUEST_URI'], 'iframe') !== false) {
    	$lang = _e('lang');
    	$url = 'http://wtest12.cannedstyle.com/'. $lang.'/page/exchange_terms';
    	printf(_e('currency_exchange/out_submit/text'), $url);
    } else {
    	printf(_e('currency_exchange/out_submit/text'), site_url('page/about/exchange_terms'));
    }
    ?>
    
    <button class="button" type="submit" onclick="confirm_out_submit_click()" id="#confirm_out_submit" name="submit3" ><?=_e('new_90')?></button>
    <center>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    </center>
</div>