<div class="homeobmentable" style="border-top: 1px solid #ebebeb; display: none;">
    <div class="lefthomecol">
        <div class="lefthomecolvn" style="border-bottom:  2px solid #ebebeb;border-left: 2px solid #ebebeb; border-right: 2px solid #ebebeb;"> 
            <div class="widgethometitle">
                <!--<div class="widt">&nbsp</div>-->
                <div class="righthomecolvn">                
                    <div class="onehline leftgo last" id="napobmen-16">
                        <div class="onehlug"></div>
                        <div class="onehldopug"></div>
                        <div class="onehline_lable" >
                            <?= _e('currency_exchange/form_inputs_sell_search/summ_from')?>
                        </div>
                        <!--<input class="form_input" id="sell_amount_down" value="<?= $sell_amount_down ?>" type="text"/>-->
                        <input class="form_input" id="sell_amount_down" value="" placeholder="0" type="text"/>
                        <input name="sell_amount_down" value="<?= $sell_amount_down ?>" type="hidden" tabindex="2"/>
                        <div class="clear"></div>
                    </div>                
                    <div class="onehline leftgo last" id="napobmen-16">
                        <div class="onehlug"></div>
                        <div class="onehldopug"></div>
                        <div class="onehline_lable" >
                           <?= _e('currency_exchange/form_inputs_sell_search/summ_to')?>
                        </div>
                        <!--<input class="form_input" id="sell_amount_up" value="<?= $sell_amount_up ?>" type="text"/>-->
                        <input class="form_input" id="sell_amount_up" value="" placeholder="0" type="text"/>
                        <input name="sell_amount_up" value="<?= $sell_amount_up ?>" type="hidden" tabindex="2"/>
                        <div class="clear"></div>
                    </div>                
                </div>                
            </div>
        </div>        
        <div class="clear"></div>        
    </div>
    <div class="righthomecol">
        <div class="righthomecolvn" style="border-bottom:  2px solid #ebebeb;border-right: 2px solid #ebebeb;">   
            <?php /* ?>
            <div class="widgethometitle">
                <div class="widt"><?= _e('currency_exchange/form_inputs_sell_search/i_get')?></div>
            </div>
            <?php  */ ?>
                <div class="righthomecolvn">                
                    <div class="onehline leftgo last" id="napobmen-16">
                        <div class="onehlug"></div>
                        <div class="onehldopug"></div>
                        <div class="onehline_lable" >
                            <?= _e('currency_exchange/form_inputs_sell_search/summ_from')?>
                        </div>
                        <!--<input class="form_input" id="buy_amount_down" value="<?= $buy_amount_down ?>" type="text"/>-->
                        <input class="form_input" id="buy_amount_down" value="" placeholder="0" type="text"/>
                        <input name="buy_amount_down" value="<?= $buy_amount_down ?>" type="hidden" tabindex="2"/>
                        <div class="clear"></div>
                    </div>                
                    <div class="onehline leftgo last" id="napobmen-16">
                        <div class="onehlug"></div>
                        <div class="onehldopug"></div>
                        <div class="onehline_lable" >
                            <?= _e('currency_exchange/form_inputs_sell_search/summ_to')?>
                        </div>
                        <!--<input class="form_input" id="buy_amount_up" value="<?= $buy_amount_up ?>" type="text"/>-->
                        <input class="form_input" id="buy_amount_up" value="" placeholder="0" type="text"/>
                        <input name="buy_amount_up" value="<?= $buy_amount_up ?>" type="hidden" tabindex="2"/>
                        <div class="clear"></div>
                    </div>                
                </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
