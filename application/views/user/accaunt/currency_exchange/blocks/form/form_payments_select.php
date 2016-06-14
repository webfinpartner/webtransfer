    <div class="homeobmentable">
        <div class="lefthomecol div_buy_payment_systems" style="float:right;">
            <!--<div class="lefthomecolvn scrol_500">-->
            <div class="lefthomecolvn" >
                <div class="widgethometitle">
                    <!--<div class="widt" style="float:left;"><?//=_e('currency_exchange/form_payments_select/buy'); ?></div>-->
                    <div class="widt shaddow-blink" style="float:left;"><?=sprintf(_e('Вы получаете'), $curent_user_data->name_sername);?></div>
                        <?php if(isset($show_select_all_payment_system) && $show_select_all_payment_system === true): ?>                         
                        <div class="onehrinetext" style="display:none; margin: 16px 0 0 26px; width: 125px;">
                              <?=_e('currency_exchange/form_payments_select/all_orders'); ?>
                        </div>
                        <input type="checkbox" name="all_orders" value="1" style="display:none; margin-top: 28px;"/>
                        <?php endif; ?>
                <div class="clear"></div>
                </div>
                <div class="scrol_500">
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/form/_form_group_and_payments_select.php', array('checkbox_name'=>'buy_payment_systems', 'group_cont' => 'b', 'user_payment_summa_field'=>'buy_user_payment_summa', 'show_only_group_wt' => TRUE)); ?>
                        <a class="onehrine" style="height: 66px;">
                            <div class="clear"></div>
                        </a>
                </div>
            </div>
        </div>
        
        <div class="righthomecol div_sell_payment_systems" style="float:left;">
            <!--<div class="righthomecolvn scrol_500">-->
            <div class="righthomecolvn">
                <div class="widgethometitle2">
                    <!--<div class="widt1"><?//=_e('currency_exchange/form_payments_select/sell'); ?></div>-->
                    <div class="widt1 shaddow-blink"><?=sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername)?></div>

                    <div class="clear"></div>
                </div>
               <? // vre($sell_payment_systems); ?>
                <div class="scrol_500">
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/form/_form_group_and_payments_select.php', array('payment_systems_groups' => $payment_systems_groups, 'buy_payment_systems' => $sell_payment_systems, 'group_cont' => 'b', 'checkbox_name'=>'sell_payment_systems', 'show_payment_data' => FALSE, 'save_user_data'=> false, 'user_payment_summa_field'=>'sell_user_payment_summa', 'show_only_group_wt' => FALSE)); ?>
                    <a class="onehrine" style="height: 66px;">
                        <div class="clear"></div>
                    </a>
                </div>
            </div>
        </div>        
        <div class="clear"></div>
    </div>