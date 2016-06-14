<?php if($order->buyer_document_image_path == 'wt' && !$order->seller_confirmed && $val->confirm_type == 0): ?>
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
<input type="hidden" name="ps_is_bank" value="<?php // echo time() ?>">
