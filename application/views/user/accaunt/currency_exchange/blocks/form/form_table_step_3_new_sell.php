<!--<table class="table_results add_more_orders table_search_orders" cellspacing="0" width="100%">
    <tr class="table_row hbody" style="display: none;">
        <td class="table_cell" >
            
        </td>
    </tr>
</table>-->


<table class="table_results add_more_orders table_search_orders" cellspacing="0" width="100%">
    <tr class="table_row hbody" >
        <td class="table_cell" >
            
            <div class="table_cell col2" ><?=_e('currency_exchange/table_search/fee')?></div>
            <div class="table_cell col2" style="padding-left: -34px;">
                <?php if($no_wt == 0 && !$visa_fee ): ?>
                    <?=$fee?>                    
                    <?=Currency_exchange_model::show_payment_system_code(['currency_id' => $fee_ps->currency_id]); ?>
                <?php elseif($no_wt == 0 && $visa_fee ): ?>    
                    <?=$fee?> USD VISA                
                <?php elseif($no_wt == 1 && !$visa_fee ): ?>
                    <?=$percentage?> %
                <?php elseif($no_wt == 1 && $visa_fee ): ?>    
                    <?=$fee?> USD VISA
                <?php endif; ?>
            </div>
            <div class="clear"></div>
            <div class="timer_confirm" style="text-align: left;/*display: none*/">
                <div class="table_cell " >
                    <div class="table_search_payment_system" style="border: 1px solid #cccccc; margin: 7px; border-radius: 8px; padding: 7px">
                        <div class="show_payment_system step_3_ps_show_sell">
                            <div style="height: 28px;">
                               <?php// echo _e('currency_exchange/for_search_table_dop/sell'); ?>
                                <?=sprintf(_e('%s отдаёт'), $curent_user_data->name_sername)?>
                            </div> 
                            <?php $c = 0; ?>
                            <?php foreach ($sell_payment_systems as $key => $val): ?>
                                <div style="height: 52px;">
                                    <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems[$key]->public_path_to_icon ?>;"></div>
                                    <div style="  height: 40px;  padding: 2px 0;  line-height: 40px;">
                                    <?//=_e('currency_name_'.$payment_systems[$key]->machine_name)?>   
                                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($key)->humen_name?>">
                                    <?=Currency_exchange_model::get_ps($key)->humen_name;?>      
                                    </div>
                                     : <?= _e('currency_exchange/for_search_table_dop/summ')?> 
                                        <?//=_e('currency_id_'.$payment_systems[$key]->currency_id)?> 
                                        <?=Currency_exchange_model::show_payment_system_code(['select_curency_sell' => $select_curency_sell, 'ps'=>$payment_systems[$key]]); ?>
                                        <?/*=Currency_exchange_model::show_payment_system_code(['currency_id' => $payment_systems[$key]->currency_id]); */?>  
                                      - <?=$input_summa_sell_payment_systems[$key]?>
                                    </div>
                                </div>
                            <?php $c++; ?>
                            <?php if(count($sell_payment_systems) > 1 && count($sell_payment_systems) != $c ): ?>
                                <div style="color: red; padding-left: 52px;">
                                    <?=_e('или'); ?>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="show_payment_system step_3_ps_show_buy" >   
                            <div style="height: 28px;">
                                <?php// echo _e('currency_exchange/for_search_table_dop/buy'); ?>
                                <?=sprintf(_e('%s получает'), $curent_user_data->name_sername)?>
                            </div>
                            <?php $c = 0; ?>
                            <?php foreach ($buy_payment_systems as $key => $val): ?>
                                <div style="height: 52px;">
                                    <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems[$key]->public_path_to_icon ?>;"></div>
                                    <div style="  height: 40px;  padding: 2px 0;  line-height: 40px;">
                                    <?//=_e('currency_name_'.$payment_systems[$key]->machine_name)?>    
                                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($key)->humen_name?>">
                                    <?=Currency_exchange_model::get_ps($key)->humen_name;?> 
                                    </div>
                                     : <?= _e('currency_exchange/for_search_table_dop/summ')?> 
                                    <?//=_e('currency_id_'.$payment_systems[$key]->currency_id)?>
                                    <?=Currency_exchange_model::show_payment_system_code(['select_curency_sell' => $select_curency_buy, 'ps'=>$payment_systems[$key]]);  ?> 
                                    <?/*=Currency_exchange_model::show_payment_system_code(['currency_id' => $payment_systems[$key]->currency_id]); */?>
                                    - <?=$input_summa_buy_payment_systems[$key]?>
                                    </div>
                                </div>
                            <?php $c++; ?>
                            <?php if(count($buy_payment_systems) > 1 && count($buy_payment_systems) != $c ): ?>
                                <div style="padding-left: 52px; color: red;">
                                    <?=_e('или'); ?>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="clear"></div>    
                    </div>

                </div>
                    <input type="hidden" name="curent_date" value="<?php echo time() ?>">
                    <input type="hidden" name="input_timer_confirm" value="<?php echo $order->buyer_confirmed ?>">
                    <div class="table_cell col2">
                    </div>
                </div>
            </div>    
        </td>
    </tr>
</table>
<div style="margin-top: 30px;">
    <button class="button previous_button" type="button" name="submit1" style="display: inline;"><?php echo _e('Назад'); ?></button>
    <button class="button" type="button" id="out_submit" name="submit2" style="display: inline;"><?php echo _e('currency_exchange/sell_wt/issue'); ?></button>
</div>
