<?php 
$orde_payment_systems_summ = array();
foreach ($order->payment_systems as $order_pay_sys)
{
    $orde_payment_systems_summ[$order_pay_sys->payment_system] = $order_pay_sys->summa;
}
?>
<?php 
    $select_pay_sys_sell = unserialize($order->sell_payment_data); 
    $select_pay_sys_buy = unserialize($order->buy_payment_data); 
    //var_dump($order->buy_payment_data);
?>
<div class="timer_confirm" style="display: none; text-align: left;">
    <div class="table_cell table_cell_text"><?= _e('currency_exchange/timer_confirm/order')?> № <?php echo $order->original_order_id;?></div>
    <div class="table_cell">
        <?php 
        if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $user_id )
        {
//            $str2 =  _e('currency_exchange/timer_confirm/sell');
            $str2 =  sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername);
//            $str1 = _e('currency_exchange/timer_confirm/buy');
            $str1 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $style_float =  'style="float:right;"';
        }
        else
        {
//            $str1 = _e('currency_exchange/timer_confirm/sell');
            $str1 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername);
//            $str2 = _e('currency_exchange/timer_confirm/buy');
            $str2 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $style_float =  '';
        }
        ?> 
        <div class="table_cell table_cell_text" >
            <span class="timer_1">
            <?= _e('currency_exchange/timer_confirm/timer_1')?>
            </span>
            <span class="timer_2" style="display: none;">
            <?= _e('currency_exchange/timer_confirm/timer_2')?>
            </span>
        </div>
        <?php if($order->type != $order->confirm_type && $order->buyer_user_id  && $order->seller_confirmed): ?>
            <span style="color:red; margin-left: 35px;">
                <?=_e('<b>Важно:</b> если вы не получили средств жмите кнопку "Есть проблема"');?>
            </span>
        <?php endif;?>
        <?php if($val->buyer_user_id && $val->type == $val->confirm_type && !$val->seller_confirmed && $order->buyer_confirmed): ?>
            <span style="color:red; margin-left: 35px;">
                <?=_e('<b>Важно:</b> если вы не получили средств жмите кнопку "Есть проблема"');?>
            </span>
        <?php endif;?>
        <div style="border: 1px solid #cccccc; margin: 7px; border-radius: 8px; padding: 7px; /*text-align: left;*/">
            <div class="show_payment_system 111111" <?=$style_float?>>
                <div style="height: 28px;"><?php echo $str1; ?></div>
                <?php                 
                foreach ($select_pay_sys_buy as $pay_sys): ?>
                <!--<div style="height: 52px;">-->
                <?php if($val->payed_system > 0 && $pay_sys->payment_system_id != $val->payed_system) continue; ?>
                <div>
                    <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->public_path_to_icon ?>;"></div>
                    <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) ): ?>
                        <!--<div style="  height: 40px;  padding: 2px 0;  line-height: 19px;">-->
                        <div style="padding: 2px 0;  line-height: 19px;">
                    <?php else: ?>    
                        <div style="padding: 2px 0;  line-height: 42px;">
                    <?php endif; ?>        
                    <div>        
                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name?>">
                    <?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name;?>
                    </div>
                     : <?= _e('currency_exchange/timer_confirm/summ')?>                      
                     <?=Currency_exchange_model::show_payment_system_code(['order' => $order, 'payment_systems_id' => $pay_sys->payment_system_id]);?> -
                     <?=$orde_payment_systems_summ[$pay_sys->payment_system_id]?></br>
                    <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) && $pay_sys->payment_system_id != 115 ): ?>
                        <?php 
                        $ps_sell_id = $pay_sys->payment_system_id;
                        if($ps_sell_id != 115) 
                            echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system_id], $pay_sys->payment_data, ['order' => $val, 'curent_user' => $user_id ]);
                         ?> 
                     <?php endif; ?>
                    </div>
                    <?php if( $pay_sys->payment_system_id == 115 && !empty( $order->visa_tranfer_money_url ) ): ?>
                    <div>
                        <?php if( isset( $order->visa_tranfer_money_url['url'] ) ): ?>
                             <!--<a href="#" onclick="confirme_select_payment_visa_card($(this).parentsUntil('.table_body').prev()); return false;" class="confirme_select_payment_system table_green_button" style="text-align: center;"><?= _e('Перевести средства') ?></a>-->
                            <?php //<a href="#" onclick="$('#popup_visa_tranfer_money').fadeIn();return false;" ><?= _e('Инструкция') </a> ?>
                        <?php else: ?>
                            <div style="color: blue; width: 285px; float: right;"><?= $order->visa_tranfer_money_url['error'] ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                            
                        <div class="clear"></div>
                    </div>
                </div>
                <div style="display: none;" class="div_select_pay_sys_buy">
                    <!--<div class="onehrine" style="height: 85px; line-height: 52px; width: 100%;">-->
                    <div class="onehrine" style="line-height: 52px; width: 100%; margin-top: 5px;">
                        <!--<label>-->
                        <?php if(count($select_pay_sys_buy) == 1 && Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name))  : ?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_buy" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                            <input type="hidden" name="buy_only_wt" value="1" />
                        <?php elseif(count($select_pay_sys_buy) == 1 && !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name))  : ?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_buy" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                            <input type="hidden" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_buy" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                        <?php else:?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_buy" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>" >
                        <?php endif; ?>
                        <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->public_path_to_icon ?>;"></div>
                        <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) ): ?>
                            <div style="padding: 2px 0;  line-height: 19px;">
                        <?php else: ?>    
                            <div style="padding: 2px 0;  line-height: 42px;">
                        <?php endif; ?>
                            <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)?>                    
                            <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name?>">    
                            <?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name;?>  
                            </div>    
                                : <?= _e('currency_exchange/timer_confirm/summ')?>  
                                <?//=_e('currency_id_'.$payment_systems_id_arr[$pay_sys->payment_system_id]->currency_id)?> 
                                <?=Currency_exchange_model::show_payment_system_code(['order' => $order, 'payment_systems_id' => $pay_sys->payment_system_id]);?>
                                - 
                                <?=$orde_payment_systems_summ[$pay_sys->payment_system_id]?>
                            <?php // if($order->type == Currency_exchange_model::ORDER_TYPE_SELL_WT): ?>    
                                <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) && $pay_sys->payment_system_id != 115 ): ?>
                                    <br/><?//= _e('currency_exchange/timer_confirm/ackount')?> <? //=$pay_sys->payment_data?>
                                    <?php echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system_id], $pay_sys->payment_data, ['order' => $val, 'curent_user' => $user_id ]); ?> 
                                <?php endif; ?>
                            <?php // endif; ?>
                                <!--<div class="clear"></div>-->
                            </div>
                        <!--</label>-->
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>

                </div>
                <?php $c++; ?>
                <?php if(count($select_pay_sys_buy) > 1 && count($select_pay_sys_buy) != $c && $val->payed_system == 0 ): ?>
                    <div style="padding-left: 52px; color: red;">
                        <?=_e('или'); ?>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>    
                
                
            <!--#####-->
            <div class="show_payment_system 222222" <?=$style_float?>>    
                <div style="height: 28px;"><?php echo $str2; ?></div>
            <?php $c = 0; ?>
            <?php foreach ($select_pay_sys_sell as $pay_sys): ?>
            <!--<div style="height: 52px; line-height: 52px;">-->
            <div >
                <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->public_path_to_icon ?>;"></div>
                <!--<div style="  height: 40px;  padding: 2px 0;  line-height: 40px;">-->
                <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) && $pay_sys->payment_data ): ?>
                    <div style="padding: 2px 0;  line-height: 19px;">
                <?php else: ?>    
                    <div style="padding: 2px 0;  line-height: 42px;">
                <?php endif; ?>
                <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)?>
                <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name?>">
                <?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name;?>
                </div>
                 : <?= _e('currency_exchange/timer_confirm/summ')?> 
                <?=Currency_exchange_model::show_payment_system_code(['order' => $order, 'payment_systems_id' => $pay_sys->payment_system_id]);?>
                 - 
                <?=$orde_payment_systems_summ[$pay_sys->payment_system_id]?>
                <?php if(!Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) && $pay_sys->payment_data && $pay_sys->payment_system_id != 115 ): ?>    
                    <br/> <?//= _e('currency_exchange/timer_confirm/ackount')?> 
                        <?php echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system_id], $pay_sys->payment_data, ['order' => $val, 'curent_user' => $user_id ]); ?> 
                        <?//=$pay_sys->payment_data?>
                <?php endif; ?>
                    
                    <?php if( $pay_sys->payment_system_id == 115 && !empty( $order->visa_tranfer_money_url ) ): ?>
                    <div>
                        <?php if( isset( $order->visa_tranfer_money_url['url'] ) ): ?>
                            <a href="<?= $order->visa_tranfer_money_url['url'] ?>" target="_blank" class="confirme_select_payment_system table_green_button" style="text-align: center;"><?= _e('Перевести средства') ?></a>
                            <?php //<a href="#" onclick="$('#popup_visa_tranfer_money').fadeIn();return false;" ><?= _e('Инструкция') </a> ?>
                        <?php else: ?>
                            <div style="color: blue; width: 285px; float: right;"><?= $order->visa_tranfer_money_url['error'] ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div style="display: none;" class="div_select_pay_sys_sell">
                <?php if($order->confirm_type == Currency_exchange_model::ORDER_TYPE_BUY_WT): ?> 
                <div class="onehrine" style="/*height: 85px;*/ line-height: 52px; width: 100%;">
                <?php else: ?>
                <div class="onehrine" style="/*height: 52px;*/ line-height: 52px;">
                <?php endif; ?>    
                    <label>
                        <!--<input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_sell" >-->
                        <?php if(count($select_pay_sys_sell) == 1 && Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)): ?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_sell" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                            <input type="hidden" name="sell_only_wt" value="1" />
                        <?php elseif(count($select_pay_sys_sell) == 1 && !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)): ?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_sell" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                            <input type="hidden" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_sell" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>">
                        <?php else:?>
                            <input type="radio" value="<?=$pay_sys->payment_system_id?>" name="select_payment_systems_sell" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name?>" >
                        <?php endif; ?>

                        <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->public_path_to_icon ?>;"></div>

                        <?php if( !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name) ): ?>
                        <!--<div style="height: 40px;  padding: 2px 0;  line-height: 19px; float: left;">-->
                        <div style="padding: 2px 0;  line-height: 19px; float: left;">

                        <?php else: ?>
                        <!--<div style="height: 40px;  padding: 2px 0;  line-height: 40px; float: left;">-->
                        <div style="padding: 2px 0;  line-height: 40px; float: left;">
                        <?php endif; ?>
                            <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)?>                    
                            <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name?>">
                            <?=Currency_exchange_model::get_ps($pay_sys->payment_system_id)->humen_name;?>                   
                            </div>
                            : <?= _e('currency_exchange/timer_confirm/summ')?> 
                            <?//=_e('currency_id_'.$payment_systems_id_arr[$pay_sys->payment_system_id]->currency_id)?> 
                            <?=Currency_exchange_model::show_payment_system_code(['order' => $order, 'payment_systems_id' => $pay_sys->payment_system_id]);?>
                            - 
                            <?=$orde_payment_systems_summ[$pay_sys->payment_system_id]?>
                            <?php if($order->type == Currency_exchange_model::ORDER_TYPE_SELL_WT && !Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)): ?>    
                            <br/><?= _e('currency_exchange/timer_confirm/ackount')?> <?=$pay_sys->payment_data?>

                            <?php endif; ?>
                        </div>
                    </label>
                    <?php // if($order->type == Currency_exchange_model::ORDER_TYPE_BUY_WT): ?>    
                    <?php if($order->confirm_type == Currency_exchange_model::ORDER_TYPE_BUY_WT): ?>    
                            <?php $payment_sys_user_data = @$payment_systems[$payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name]->payment_sys_user_data ?>
                            <div class="div_user_payment_data_save <?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>" style="margin-left:69px; float: left; margin-top: -18px;">
                                <?php if(!Currency_exchange_model::is_root_curr($payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name)): ?>
                                    <?php if(!empty($payment_sys_user_data)): ?>
                                        <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin: 0 5px 9px 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name ?>]" data-payment-system="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name ?>" value="<?= @$payment_sys_user_data ?>" type="text" readonly="readonly" />
                                        <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data"><?= _e('currency_exchange/_form_group_and_payments_select/save')?></a>
                                        <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?= _e('currency_exchange/_form_group_and_payments_select/change')?></a>
                                    <?php else: ?>
                                        <input style="height: 6px; margin: 0 5px 9px 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name ?>]" data-payment-system="<?= $payment_systems_id_arr[$pay_sys->payment_system_id]->machine_name ?>" value="" type="text" />
                                        <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data"><?= _e('currency_exchange/_form_group_and_payments_select/save')?></a>
                                        <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?= _e('currency_exchange/_form_group_and_payments_select/change')?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
                            </div>
                    <?php endif; ?>  
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <?php $c++; ?>
            <?php if(count($select_pay_sys_sell) > 1 && count($select_pay_sys_sell) != $c ): ?>
                <div style="padding-left: 52px; color: red;">
                    <?=_e('или'); ?>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>    
            </div>    
            <!--#####-->
                
                
            <div class="clear"></div>  
            <span class="destination">
            <?= _e('currency_exchange/timer_confirm/destination')?>
            </span>
        </div>
        <br/>
        <div class="table_cell table_cell_text" >
            <span  class="after_send">
            <?php printf(_e('currency_exchange/timer_confirm/after_send'), $order->original_order_id)?>
            </span>&nbsp;
        </div>
    </div>
    <?php /* if($order->type == Currency_exchange_model::ORDER_TYPE_SELL_WT): ?>    
        <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
    <?php endif; ?>
    <?php if($order->type == Currency_exchange_model::ORDER_TYPE_BUY_WT): ?>    
        <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->seller_confirmation_date)?>">
    <?php endif; */?>
    <?php /* if($order->type == Currency_exchange_model::ORDER_TYPE_SELL_WT): ?>    
        <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
    <?php endif; */?>
    <?php if( $order->old_orders == 1): ?>
        <?php /* if($order->buyer_document_image_path == 'wt' && !$order->seller_confirmed && $val->confirm_type == 0): ?>
            1<<<<<<<<<
            <!--<input type="hidden" name="set_up_date" value="<?php // echo strtotime($order->seller_confirmation_date)?>">-->
            <input type="hidden" name="set_up_date" value="<?php  echo strtotime($order->buyer_confirmation_date)?>">
            <input type="hidden" name="input_timer_confirm" value="0">
            <!--<input type="hidden" name="ps_is_bank" value="<?php // echo $order->sell_payment_data_un[$order->sell_system]->is_bank?>">-->
            <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
        <?php elseif(!$val->buyer_document_image_path && $order->seller_document_image_path == 'wt' && !$order->seller_confirmed && $order->buyer_confirmed && $val->confirm_type == 1): ?>  
             2<<<<<<<<< 
            <!--<input type="hidden" name="set_up_date" value="<?php //  echo strtotime($order->seller_confirmation_date)?>">-->
            <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
            <input type="hidden" name="input_timer_confirm" value="0">
            <input type="hidden" name="ps_period" value="<?php echo Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
        <?php elseif($order->buyer_send_money_date > 0 && $order->initiator == 1 && $order->seller_confirmed == 0): ?>  
             3<<<<<<<<< 
            <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_send_money_date)?>">
            <input type="hidden" name="input_timer_confirm_2" value="1">
            <input type="hidden" name="input_timer_confirm" value="1">
            <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 2)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
        <?php elseif($order->seller_send_money_date > 0 && $order->initiator == 0 && $order->buyer_confirmed == 0): ?> 
             4<<<<<<<<< 
            <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->seller_send_money_date)?>">
            <input type="hidden" name="input_timer_confirm_2" value="1">
            <input type="hidden" name="input_timer_confirm" value="1">
            <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
        <?php else: ?>   
             5<<<<<<<<< 
            <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
            <input type="hidden" name="input_timer_confirm" value="<?php echo (int)((boolean)$order->buyer_user_id || $order->seller_confirmed || $order->seller_document_image_path)?>">
        <?php endif; ?>
        <input type="hidden" name="curent_date" value="<?php echo time() ?>">
         * 
         <? */?>
        <?php $this->load->view('user/accaunt/currency_exchange/blocks/timer_confirm_status_old.php'); ?>
    <?php else: ?>
        <?php $this->load->view('user/accaunt/currency_exchange/blocks/timer_confirm_status.php'); ?>
    <?php endif; ?>
    <input type="hidden" name="ps_is_bank" value="<?php // echo time() ?>">
    
    <div class="table_cell col2">
    </div>
</div>
