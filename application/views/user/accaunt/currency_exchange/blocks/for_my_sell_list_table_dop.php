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
foreach ($order->buy_payment_data_un as $pay_sys)
{
    if($order->payed_system && $order->payed_system != $pay_sys->payment_system_id) continue;
    $c1++;
}
?>
<div class="timer_confirm for_my_sell_list_table_dop" style="text-align: left;">
    <div class="table_cell table_cell_text"><?= _e('currency_exchange/for_my_sell_list_table_dop/order')?> № <?php echo $order->original_order_id;?></div>
    <div class="table_cell " >
        <?php 
        if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $user_id )
        {
//            $str2 = _e('currency_exchange/for_my_sell_list_table_dop/sell');
            $str2 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername);
//            $str1 = _e('currency_exchange/for_my_sell_list_table_dop/buy');
            $str1 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $style_float =  'style="float:right;"';
            $is_seller = TRUE;
        }
        else
        {
//            $str1 = _e('currency_exchange/for_my_sell_list_table_dop/sell');
            $str1 = sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername);
//            $str2 = _e('currency_exchange/for_my_sell_list_table_dop/buy');
            $str2 = sprintf(_e('Вы получаете'), $curent_user_data->name_sername);
            $style_float =  '';
            $is_seller = FAlSE;
        } 
        ?>     
        
        <div class="table_search_payment_system" style="border: 1px solid #cccccc; margin: 7px; border-radius: 8px; padding: 7px">
            <div class="show_payment_system" <?=$style_float?>>
                <div style="height: 28px;"><?php echo $str1; ?></div>
                <?php $c = 0; $count_ps = count($order->buy_payment_data_un);?>
                <?php foreach ($order->buy_payment_data_un as $pay_sys): ?>
                <?php if($order->payed_system && $order->payed_system != $pay_sys->payment_system_id) continue; ?>
                <!--<div style="height: 52px;">-->
                <div>
                    <div class="onehrineico sprite_payment_systems" style="<?= $pay_sys->public_path_to_icon ?>;"></div>
                    <?php if($pay_sys->payment_data && $is_show_payment_data === TRUE): ?>
                    <div style="padding: 2px 0;  line-height: 19px;">
                    <?php else: ?>
                    <div style="  padding: 2px 0;  line-height: 40px;">
                    <?php endif; ?>
                    
                    <?//=_e('currency_name_'.$pay_sys->machine_name)?>
                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->machine_name)->humen_name?>">    
                    <?=Currency_exchange_model::get_ps($pay_sys->machine_name)->humen_name;?>
                    </div>    
                    : 
                    <?= _e('currency_exchange/for_my_sell_list_table_dop/summ')?> 
                    <?//=_e('currency_id_'.$pay_sys->currency_id)?> 
                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys]); ?>
                    - 
                    <?=$pay_sys->summa?>
                    
                     <?php if($pay_sys->payment_data && $is_show_payment_data === TRUE && $pay_sys->payment_system_id != 115 ): ?>
                        <br/><?//= _e('currency_exchange/for_my_sell_list_table_dop/ackount')?> <?//=$pay_sys->payment_data; ?>
                        <?php echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system_id], $pay_sys->payment_data, ['order' => $val, 'curent_user' => $user_id ]); ?> 
                     <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php $c++; ?>
                <?php // if(count($order->buy_payment_data_un) > 1 && count($order->buy_payment_data_un) != $c ): ?>
                <?php if($c1 > 1 && $c1 != $c ): ?>
                    <div style=" padding-left: 52px; color: red;">
                        <?=_e('или'); ?>
                    </div>
                <?php // if(count($order->buy_payment_data_un) > 1 && count($order->buy_payment_data_un) != $c && $count_ps > 1 ): ?>
                <?php // if(count($order->buy_payment_data_un) > 1 && count($order->buy_payment_data_un) != $c && $count_ps > 1 ): ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
                
            <div class="show_payment_system" <?=$style_float?>>   
                <div style="height: 28px;"><?php echo $str2; ?></div>
                <?php $c = 0; $count_ps = count($order->sell_payment_data_un);?>
                <?php foreach ($order->sell_payment_data_un as $pay_sys): ?>
                <!--<div style="height: 52px;">-->
                <div>
                    <div class="onehrineico sprite_payment_systems" style="<?= $pay_sys->public_path_to_icon ?>;"></div>
                    <?php if($pay_sys->payment_data && $is_show_payment_data === TRUE): ?>
                    <div style="padding: 2px 0;  line-height: 19px;">
                    <?php else: ?>
                    <div style="padding: 2px 0;  line-height: 40px;">
                    <?php endif; ?>
                    <?//=_e('currency_name_'.$pay_sys->machine_name)?>
                    <div class="show_name_ps_in_table" title="<?=Currency_exchange_model::get_ps($pay_sys->machine_name)->humen_name?>">    
                    <?=Currency_exchange_model::get_ps($pay_sys->machine_name)->humen_name?> 
                    </div>
                        : 
                    <?= _e('currency_exchange/for_my_sell_list_table_dop/summ')?> 
                    <?//=_e('currency_id_'.$pay_sys->currency_id)?> 
                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys]); ?> - 
                    <?=$pay_sys->summa?>
                     <?php if($pay_sys->payment_data && $is_show_payment_data === TRUE): ?>
                     <br/><?//= _e('currency_exchange/for_my_sell_list_table_dop/ackount')?> 
                         <?php if(  $order->initiator == 1 && $order->wt_set == Currency_exchange_model::WT_SET_BUY && $order->buyer_user_id>0 && ($order->status==8 || $order->status==9)) echo '*********'; else echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$pay_sys->payment_system_id], $pay_sys->payment_data, ['order' => $val, 'curent_user' => $user_id ]); ?>
                     <?php endif; ?>
                     
                     <?php if( $pay_sys->payment_system_id == 115 && !empty( $order->visa_tranfer_money_url ) ): ?>
                    <div>
                        <?php if( isset( $order->visa_tranfer_money_url['url'] ) ): ?>
                            <a href="#" onclick="confirme_select_payment_visa_card($(this).parent().parent());; return false;" target="_blank" class="confirme_select_payment_system table_green_button" style="text-align: center;"><?= _e('Перевести средства') ?></a>
                            <?php //<a href="#" onclick="$('#popup_visa_tranfer_money').fadeIn();return false;" ><?= _e('Инструкция') </a> ?>
                        <?php else: ?>
                            <div style="color: blue; width: 285px; float: right;"><?= $order->visa_tranfer_money_url['error'] ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                     
                         <div class="clear"></div>    
                    </div>
                </div>
                <?php $c++; ?>
                <?php if(count($order->sell_payment_data_un) > 1 && count($order->sell_payment_data_un) != $c && $count_ps > 1 ): ?>
                    <div style="padding-left: 52px; color: red;">
                        <?=_e('или'); ?>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="clear"></div>    
        </div>
    </div>
    <!--<div class="table_cell col2"></div>-->
    <div class="clear"></div>
</div>
