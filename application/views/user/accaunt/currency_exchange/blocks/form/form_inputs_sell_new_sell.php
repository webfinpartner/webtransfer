<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
<div class="homeobmentable new_sell_fee_container" >
    
    <div class="lefthomecol step_2" >
        <div class="lefthomecolvn">   
<!--            <div class="widgethometitle">
                <div class="widt">Отдаю</div>
            </div>-->
            <div class="widt" style="padding: 1px;"></div>
            <!--<div class="onehline leftgo " id="napobmen-16" style=""></div>-->
            <div class="onehline leftgo " id="napobmen-16" style="display: none;">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    <?php echo _e('currency_exchange/form_inputs_sell/summ'); ?>
                </div>
                <input class="form_input" id="sell_amount" value="1<?//= $sell_sum_amount ?>" type="text"/>
                <!--<input name="sell_sum_amount" value="<?//= $sell_sum_amount ?>" type="hidden" tabindex="1"/>-->
                <input name="sell_sum_amount" value="1" type="hidden" tabindex="1"/>

                <div class="clear"></div>
            </div>
            <div class="buy_fee_and_ammount" style="display: none;">
                <div class="onehline leftgo " style="display: none; border-top:1px solid #eee;">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <span id="buy_amount_error" style="color: red;"><?= _e('Выберите платежную систему') ?></span>
<!--                    <div class="onehline_lable" >
                         <span class="phone_format" onclick="$('.popup_window_helper').show('slow'); return false;" style="color: blue;">(?)</span>
                    </div>-->
                    <!--<input class="form_input" id="buy_amount_fee" value="<?= $sell_sum_fee ?>" type="text" readonly=""  tabindex="-1"/>-->
                    <div class="clear"></div>
                </div>
                <div class="onehline leftgo " id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        <?php echo _e('currency_exchange/form_inputs_sell/fee'); ?>

                         <span class="phone_format" onclick="return show_window_helper_visa();" style="color: blue;"> (?) </span>
                    </div>
                    <!--<input class="form_input" id="buy_amount_fee" value="<?= $sell_sum_fee ?>" type="text" readonly=""  tabindex="-1"/>-->
                    <div id="buy_amount_fee" class="sell_ammount_div_text" style="padding-left:5px; float: left;"></div>
                    <div class="clear"></div>
                </div>
                <div class="onehline leftgo last" id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                         <?php echo _e('currency_exchange/form_inputs_sell/all'); ?>
                    </div>
                    <!--<input class="form_input" id="buy_amount_total" value="<?= $sell_sum_total ?>" type="text"  readonly="" tabindex="-1"/>-->
                    <div id="buy_amount_total" class="sell_ammount_div_text"></div>
                    <div class="clear"></div>
                </div>            
            </div>
        </div>
        <div class="buttons_next_prev" style="display: none; margin-top: 30px">
            <?php $this->load->view($form_folder.'form_steps_button_new_sell.php'); ?>
        </div>
        
    </div>
    <div class="righthomecol step_1" >
        <div class="righthomecolvn" > 
            <img class='loading-gif' style="display: none; margin: 5px auto;" src="/images/loading.gif"/>
            <div class="widt" style="padding: 1px;"></div>
            <div class="onehline leftgo last" id="napobmen-16" style="display: none;">
                <div class="onehlug"></div>
                <div class="onehldopug"></div>
                <div class="onehline_lable" >
                    <?php echo _e('currency_exchange/form_inputs_sell/summ'); ?>
                </div>
                <input class="form_input" id="buy_amount_down" value="1<?//= $buy_amount_down ?>" type="text"/>
                <!--<input name="buy_amount_down" value="<?//= $buy_amount_down ?>" type="hidden" tabindex="2"/>-->
                <input name="buy_amount_down" value="1" type="hidden" tabindex="2"/>
                <div class="clear"></div>
            </div>                
            <div class="sell_fee_and_ammount" style="display: block;">
                <div class="onehline leftgo " style="display: block; border-top:1px solid #eee;">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <span id="sell_amount_error" style="color: red;"><?= _e('Выберите платежную систему') ?></span>
                    <div class="clear"></div>
                </div>
                <div class="onehline leftgo " id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>                    
                    <div class="onehline_lable" >
                        <?php echo _e('currency_exchange/form_inputs_sell/fee'); ?>

                        <span class="phone_format" onclick="return show_window_helper_visa();" style="color: blue"> (?) </span> 
                    </div>
                    <!--<input class="form_input" id="sell_amount_fee" value="<?= $sell_sum_fee ?>" type="text" readonly=""  tabindex="-1"/>-->
                    <div id="sell_amount_fee" class="sell_ammount_div_text"></div>
                    <div class="clear"></div>
                </div>
                <div class="onehline leftgo last" id="napobmen-16">
                    <div class="onehlug"></div>
                    <div class="onehldopug"></div>
                    <div class="onehline_lable" >
                        <?php echo _e('currency_exchange/form_inputs_sell/all'); ?>
                    </div>
                    <!--<input class="form_input" id="sell_amount_total" value="<?= $sell_sum_total ?>" type="text"  readonly="" tabindex="-1"/>-->
                    <div id="sell_amount_total" class="sell_ammount_div_text"></div>
                    <div class="clear"></div>
                </div>            
            </div>
        </div>
        <div style="margin-top: 30px">
            <?php $this->load->view($form_folder.'form_steps_button_new_sell.php'); ?>
        </div>
    </div>        
    <div class="clear"></div>        
</div>