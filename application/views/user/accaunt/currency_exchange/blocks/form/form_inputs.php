<div class="homeobmentable" style="border-top: 1px solid #ebebeb;">
    <div class="lefthomecol">
        <div class="lefthomecolvn">                                
            <div class="onehline leftgo " id="napobmen-16">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    Сумма
                </div>
                <input class="form_input" id="sell_amount" value="<?= $sell_sum_amount ?>" type="text"/>
                <input name="sell_sum_amount" value="<?= $sell_sum_amount ?>" type="hidden" tabindex="1"/>

                <div class="clear"></div>
            </div>
            <?php if(!isset($search)): ?>
            <div class="onehline leftgo " id="napobmen-16">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    Комиссия
                </div>
                <input class="form_input" id="sell_amount_fee" value="<?= $sell_sum_fee ?>" type="text" readonly=""  tabindex="-1"/>
                <div class="clear"></div>
            </div>
            <div class="onehline leftgo last" id="napobmen-16">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    Всего
                </div>
                <input class="form_input" id="sell_amount_total" value="<?= $sell_sum_total ?>" type="text"  readonly="" tabindex="-1"/>
                <div class="clear"></div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <div class="righthomecol">
        <div class="righthomecolvn">                
            <div class="onehline leftgo last" id="napobmen-16">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    Сумма От
                </div>
                <input class="form_input" id="buy_amount_down" value="<?= $buy_amount_down ?>" type="text"/>
                <input name="buy_amount_down" value="<?= $buy_amount_down ?>" type="hidden" tabindex="2"/>
                <div class="clear"></div>
            </div>                
        </div>
        <div class="onehrine">                
            <div class="onehline leftgo last" id="napobmen-16">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    Сумма До
                </div>
                <input class="form_input" id="buy_amount_up" name="buy_amount_up" value="<?= $buy_amount_up ?>" type="text" tabindex="3"/>
                <input name="buy_amount_up" value="<?= $buy_amount_up ?>" type="hidden"/>
                <div class="clear"></div>
            </div>                
        </div>            
    </div>        
    <div class="clear"></div>        
</div>