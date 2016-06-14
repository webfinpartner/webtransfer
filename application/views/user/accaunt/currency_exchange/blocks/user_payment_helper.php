<script>
    function show_window_helper_visa(){
        var wt_visa_usd = $('input[name="sell_payment_systems[wt_visa_usd]"]').prop('checked');
        var name = '.popup_window_helper.popup_window_exchange';
        
        if( wt_visa_usd ) name = '.popup_window_helper.popup_window_exchange_visa';
        
        $(name).show('slow');
        
        return false;
    }
</script>
<div class="popup_window_helper popup_window_exchange" style="left: 35%;  width: 25%;">
    <div class="close" onclick="$(this).parent().hide('slow');"></div>
    <h2><?= _e('currency_exchange/user_payment_helper/title')?></h2>
    <span><?= _e('currency_exchange/user_payment_helper/text')?></span>
</div>

<div class="popup_window_helper popup_window_exchange_visa" style="left: 35%;  width: 25%;">
    <div class="close" onclick="$(this).parent().hide('slow');"></div>
    <h2><?= _e('currency_exchange/user_payment_helper/title')?></h2>
    <span><?= _e('currency_exchange/user_payment_helper_visa/text')?></span>
</div>