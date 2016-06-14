<?php 
$select_pay_sys_sell = unserialize($order->sell_payment_data);
$select_pay_sys_buy = unserialize($order->buy_payment_data);

$select_sell_pay_sys_det = array();
$select_buy_pay_sys_det = array();

foreach($select_pay_sys_sell as $sps)
{
    $select_sell_pay_sys_det[$sps->payment_system_id] = $sps; 
}

foreach($select_pay_sys_buy as $sps)
{
    $select_buy_pay_sys_det[$sps->payment_system_id] = $sps; 
}

$c1 = 0;
foreach ($order->payment_systems as $pay_sys){
    if(!isset($payment_systems_id_arr[$pay_sys->payment_system]))
    {
        $payment_systems_id_arr[$pay_sys->payment_system] = Currency_exchange_model::get_ps($pay_sys->payment_system);
    }
    if( $pay_sys->type != $order->type ) continue;
    if(isset($order->payed_system) && $pay_sys->payment_system != $order->payed_system && $order->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS) continue;
    $c1++;
}

$c2 = 0;
foreach ($order->payment_systems as $pay_sys){
    if( $pay_sys->type == $order->type ) continue;
    if(isset($order->sell_system) && $pay_sys->payment_system != $order->sell_system && $order->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS) continue;
    $c2++;
}
?>
<?php // $select_pay_sys = unserialize($order->sell_payment_data); ?>
<div class="timer_confirm" style="text-align: left;/*display: none*/">
    <div class="table_cell table_cell_text"><?= _e('currency_exchange/for_search_table_dop/order')?> № <?php echo isset($order->original_order_id)?$order->original_order_id:$order->id; ?></div>
    <?php if(($order->status == Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED || $order->status == Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR) && !empty($order->reject_note)): ?>
        <div class="table_row" style="text-align: left; color: red; font-weight: bold; padding-left: 33px;">
            <!-- <?=_e('Причина отклонения');?>:<?//= !empty($order->reject_note->text_to_user)?$order->reject_note->text_to_user:$order->reject_note->text;?> -->
            <?=_e('Причина отклонения');?>:<?=$order->reject_note->text_to_user;?>
        </div>
    <?php elseif(($order->status == Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED || $order->status == Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR) && !empty($order->reject_note_arhiv)): ?>
        <div class="table_row" style="text-align: left; color: red; font-weight: bold; padding-left: 33px;">
            <?=_e('Причина отклонения');?>:<?//= $order->reject_note_arhiv->text_to_user?>
            <?//= !empty($order->reject_note_arhiv->text_to_user)?$order->reject_note_arhiv->text_to_user:$order->reject_note_arhiv->text;?>
            <?=$order->reject_note_arhiv->text_to_user?>
        </div>
    <?php endif; ?>
    <?php if( !in_array($order->status, [81,82,83, 85])  ):?>
    <div class="table_cell " >
        <?php 
        if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $user_id )
        {
//            $str2 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername).' <br/><b>'._e('Реквизиты для перевода').'</b>';
            if(isset($table_dop_data_sell))
            {
                $str2 = sprintf(_e($table_dop_data_sell), $curent_user_data->name_sername).' <br/>&nbsp;';
            }
            else
            {
                $str2 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername).' <br/><b>'._e('Реквизиты для перевода').'</b>';
            }
//            $str1 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $str1 = isset($table_dop_data_buy)?sprintf(_e($table_dop_data_buy), $curent_user_data->name_sername):sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $str1 .= ' <br/>&nbsp;';
            $style_float =  'style="float:right;"';
        }
        else
        {
//            $str2 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
//            $str1 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername).' <br/><b>'._e('Реквизиты для перевода').'</b>';
            $style_float =  '';
            if(isset($table_dop_data_sell))
            {
                $str1 = sprintf(_e($table_dop_data_sell), $curent_user_data->name_sername).' <br/>&nbsp;';
            }
            else
            {
                $str1 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername).' <br/><b>'._e('Реквизиты для перевода').'</b>';
            }
            $str2 = isset($table_dop_data_buy)?sprintf(_e($table_dop_data_buy), $curent_user_data->name_sername):sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $str2 .= ' <br/>&nbsp;';
        } 
        ?>     
        
        <div class="table_search_payment_system " style="border: 1px solid #cccccc; margin: 7px; border-radius: 8px; padding: 7px">
            <div class="show_payment_system" <?=$style_float?>>
                
                <div style="/*/height: 28px;*/"><?php echo $str1; ?></div> 
                <?php $c = 0; $count_ps = count($order->payment_systems);?>
                <?php foreach ($order->payment_systems as $pay_sys): ?>
                <?php if( $pay_sys->type != $order->type ) continue; ?>
                <?php if(isset($order->payed_system) && $pay_sys->payment_system != $order->payed_system && $order->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS) continue; ?>
                <!--<div style="height: 52px;">-->
                <div class="to_table_ps_cont">
                    <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system]->public_path_to_icon ?>;"></div>
                    <?php if($select_buy_pay_sys_det[$pay_sys->payment_system]->payment_data && isset($is_show_payment_data) && $is_show_payment_data === TRUE): ?>
                    <!--<div style="  height: 40px;  padding: 2px 0;  line-height: 19px;">-->
                    <div style="padding: 2px 0;  line-height: 19px; margin-top:4px; ">
                    <?php else: ?>
                    <!--<div style="  height: 40px;  padding: 2px 0;  line-height: 40px;">-->
                    <div style="padding: 2px 0;  line-height: 40px;">
                    <?php endif; ?>
                    <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system]->machine_name)?>
                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>">
                        <?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>
                    </div>:
                    <?= _e('currency_exchange/for_search_table_dop/summ')?> 
                    <?//=_e('currency_id_'.$payment_systems_id_arr[$pay_sys->payment_system]->currency_id)?>
                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys]);?> - 
                    <?=$pay_sys->summa?>
                     <?php if($select_buy_pay_sys_det[$pay_sys->payment_system]->payment_data && isset($is_show_payment_data) && $is_show_payment_data === TRUE): ?>
                        <br/><?//= _e('currency_exchange/for_search_table_dop/ackount')?>
                        <?
                            $ps_data = unserialize($pay_sys->orig_order_data);
                            $ps_buyer_id = end($ps_data->payment_systems)->payment_system;
                            if($ps_buyer_id != 115) 
                                echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system], $select_buy_pay_sys_det[$pay_sys->payment_system]->payment_data, ['order' => $val, 'curent_user' => $user_id ]); ?> 
                            
                     <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php $c++; ?>
                <?php if($c1 > 1 && $c1 != $c && $count_ps > 1 ): ?>
                    <div style="padding-left: 52px; color: red;">
                        <?=_e('или'); ?>
                    </div>
                <?php endif; ?>
                
                <?php endforeach; ?>
            </div>
                
            <div class="show_payment_system" <?=$style_float?>>   
                <div style="/*height: 28px;*/"><?php echo $str2; ?></div>
                <?php $c = 0; $count_ps = count($order->payment_systems);?>
                <?php foreach ($order->payment_systems as $pay_sys): 
                    
                    if(!isset($payment_systems_id_arr[$pay_sys->payment_system]))
                    {
                        $payment_systems_id_arr[$pay_sys->payment_system] = Currency_exchange_model::get_ps($pay_sys->payment_system);
                    }
                    ?>
                <?php if( $pay_sys->type == $order->type ) continue; ?>
                <?php if(isset($order->sell_system) && $pay_sys->payment_system != $order->sell_system && $order->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS) continue; ?>
                <div style="height: 52px;">
                    <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system]->public_path_to_icon ?>;"></div>
                    <div style="  height: 40px;  padding: 2px 0;  line-height: 40px;">
                    <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system]->machine_name)?>
                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>">
                        <?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>
                    </div> : 
                    <?= _e('currency_exchange/for_search_table_dop/summ')?> 
                    <?//=_e('currency_id_'.$payment_systems_id_arr[$pay_sys->payment_system]->currency_id)?>
                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys]);?> -
                    <?=$pay_sys->summa?>
                    </div>
                </div>
                
                
                <!--**************-->
                
                <div style="display: none;" class="div_select_pay_sys_sell">
                    <?php if(isset($order->confirm_type) && $order->confirm_type == Currency_exchange_model::ORDER_TYPE_BUY_WT): ?> 
                    <div class="onehrine" style="height: auto; width: 100%;">
                    <?php else: ?>
                    <div class="onehrine" style="height: auto;">
                    <?php endif; ?>    
                            <?php if(count($select_pay_sys_sell) == 1 && Currency_exchange_model::is_root_curr($pay_sys->payment_system)): ?>
                                <input type="radio" value="<?=$pay_sys->payment_system?>" name="select_payment_systems_sell" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system]->machine_name?>">
                                <input type="hidden" value="<?=$pay_sys->payment_system?>" name="select_payment_systems_sell" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system]->machine_name?>">
                                <input type="hidden" name="sell_only_wt" value="1" />
                            <?php elseif(count($select_pay_sys_sell) == 1 && !Currency_exchange_model::is_root_curr($pay_sys->payment_system)): ?>
                                <input type="radio" value="<?=$pay_sys->payment_system?>" name="select_payment_systems_sell" checked="checked" style="display: none;" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system]->machine_name?>">
                                <input type="hidden" value="<?=$pay_sys->payment_system?>" name="select_payment_systems_sell" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system]->machine_name?>">
                            <?php else:?>
                                <input type="radio" value="<?=$pay_sys->payment_system?>" name="select_payment_systems_sell" data-m-name="<?=$payment_systems_id_arr[$pay_sys->payment_system]->machine_name?>" >
                            <?php endif; ?>

                            <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$pay_sys->payment_system]->public_path_to_icon ?>;float: left;"></div>
                            <div style="float: left; width: 270px; padding: 0;">
                                <?php if( !Currency_exchange_model::is_root_curr($pay_sys->payment_system) ): ?>
                                <div style="height: 25px;  padding: 2px 0;  line-height: 19px; float: left;">
                                <?php else: ?>
                                <div style="height: 25px;  padding: 2px 0;  line-height: 25px; float: left;">
                                <?php endif; ?>
                                    <?//=_e('currency_name_'.$payment_systems_id_arr[$pay_sys->payment_system]->machine_name)?> 
                                     <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>">
                                     <?=Currency_exchange_model::get_ps($pay_sys->payment_system)->humen_name?>   
                                     </div>
                                    : <?= _e('currency_exchange/for_search_table_dop/summ')?> 
                                    <?//=_e('currency_id_'.$payment_systems_id_arr[$pay_sys->payment_system]->currency_id)?>
                                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys]); ?> -
                                    <?=$pay_sys->summa?>
                                </div>

                                <?php $payment_sys_user_data = @$payment_systems[$payment_systems_id_arr[$pay_sys->payment_system]->machine_name]->payment_sys_user_data ?>
                                <div class="div_user_payment_data_save <?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>" style="float: left;">
                                    <?php //if($payment_systems_id_arr[$pay_sys->payment_system]->machine_name != 'wt')  : ?>
                                    <?php if( !Currency_exchange_model::is_root_curr($pay_sys->payment_system) ): ?>
                                        <?php if(!empty($payment_systems_id_arr[$pay_sys->payment_system]->method_get_field_and_ps_data)): ?>
                                            <?php echo Currency_exchange_model::get_field_and_ps_data($payment_systems_id_arr[$pay_sys->payment_system], $payment_sys_user_data); ?>
                                        <?php elseif(!empty($payment_sys_user_data)): ?>

                                <?php /* ?>
                                <div class="div_user_payment_data_save <?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>" style="float: left;">
                                    <?php if( !Currency_exchange_model::is_root_curr($pay_sys->payment_system) )  : ?>
                                        <?php if(!empty($payment_sys_user_data)): */ ?>

                                            <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin: 0 5px 9px 0px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>]" data-payment-system="<?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>" value="<?= @$payment_sys_user_data ?>" type="text" readonly="readonly" />
                                            <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 0px; float: left;" class="but agree save_user_payment_data"><?=_e('Сохранить')?></a>
                                            <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 0px; float: left;" class="but agree change_user_payment_data"><?=_e('Изменить')?></a>
                                        <?php else: ?>
                                            <input style="height: 6px; margin: 0 5px 9px 0px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>]" data-payment-system="<?= $payment_systems_id_arr[$pay_sys->payment_system]->machine_name ?>" value="" type="text" />
                                            <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data"><?=_e('Сохранить')?></a>
                                            <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 0px; float: left;" class="but agree change_user_payment_data"><?=_e('Изменить')?></a>
                                        <?php endif; ?>
                                    <?php endif; ?> 

                                    <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
                                </div>
                                <div class="clear"></div>
                            </div>
                    </div>
                </div>  
                
                <!--**************-->
                <?php $c++; ?>
                <?php if($c2 > 1 && $c2 != $c && $count_ps > 1 ): ?>
                    <div style="padding-left: 52px; color: red;">
                        <?=_e('или'); ?>
                    </div>
                <?php endif; ?>
                
                <?php endforeach; ?>
            </div>
            <div class="clear"></div>    
        </div>
           
    </div>
    <?php endif; ?>
    <input type="hidden" name="curent_date" value="<?php echo time() ?>">
    <input type="hidden" name="input_timer_confirm" value="<?php echo $order->buyer_confirmed ?>">
    <div class="table_cell col2">
    </div>
</div>
