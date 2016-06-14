<div class="left_right_container_payment_buy_text" style="display: none;">
    <div class="left_container_payment_buy_text" style="float: right;">
        <?php if(!isset($no_show_text) || $no_show_text !== TRUE): ?>
        <div class="quick_selected_payment_buy_text" style="display: none;"><?=_e('currency_exchange/form_selected_payment_system/buy'); ?></div>
        <?php endif; ?>
        <div class="homeobmentable quick_selected_payment_buy" style="margin:10px 0;">
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="right_container_payment_buy_text" style="float: left;">
        <div class="quick_selected_payment_sell_text" style="display: none;"><?=_e('currency_exchange/form_selected_payment_system/sell'); ?></div>
        <div class="homeobmentable quick_selected_payment_sell" style="margin:10px 0; ">
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div style="width: 100%; height: 20px;"></div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>