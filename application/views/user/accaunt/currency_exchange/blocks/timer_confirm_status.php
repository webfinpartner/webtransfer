<?php // vre(Currency_exchange_model::get_order_status($val)); ?>
<?php 
switch (Currency_exchange_model::get_order_status($val))
{
    //Контрагент должен подтвердить отправку денег wt_set = 1
    case 1: 
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
    <input type="hidden" name="input_timer_confirm" value="0">
    <input type="hidden" name="ps_period" value="<?php echo Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php       
    break;

    //Инициатор должен подтвердить получение денег wt_set = 1
    case 2:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_send_money_date)?>">
    <input type="hidden" name="input_timer_confirm_2" value="1">
    <input type="hidden" name="input_timer_confirm" value="1">
    <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 2)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php 
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 1
    // Статус - Контр агент ожидает подтверждения.
    case 3:
    break; 

    // Инициатор должен подтвердить отправку денег wt_set = 1
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 4:
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2
    // Статус - Ожидание ответа контрагента. 
    case 5:
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2   
    case 6:
?>
    <input type="hidden" name="set_up_date" value="<?php  echo strtotime($order->buyer_confirmation_date)?>">
    <input type="hidden" name="input_timer_confirm" value="0">
    <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 7:

    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    case 8:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->seller_send_money_date)?>">
    <input type="hidden" name="input_timer_confirm_2" value="1">
    <input type="hidden" name="input_timer_confirm" value="1">
    <input type="hidden" name="ps_period" value="<?php echo ($order->bank_set == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    case 9:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
    <input type="hidden" name="input_timer_confirm" value="0">
    <input type="hidden" name="ps_period" value="<?php echo Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
    
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание ответа контрагента.
    case 10:
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 11:
    break;

    case 12:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_send_money_date)?>">
    <input type="hidden" name="input_timer_confirm_2" value="1">
    <input type="hidden" name="input_timer_confirm" value="1">
    <!--<input type="hidden" name="ps_period" value="<?//php echo ($order->bank_set == 2)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">-->
    <input type="hidden" name="ps_period" value="<?php echo (Currency_exchange_model::get_ps($order->payed_system)->is_bank == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидаем отправку средств контрагентом.
    case 13:
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    case 14:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_confirmation_date)?>">
    <input type="hidden" name="input_timer_confirm" value="0">
    <input type="hidden" name="ps_period" value="<?php echo Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 15:

    break;

    // Контрагент должен подтвердить получения денег wt_set = 0
    case 16:
?>
    <input type="hidden" name="set_up_date" value="<?php echo strtotime($order->buyer_send_money_date)?>">
    <input type="hidden" name="input_timer_confirm_2" value="1">
    <input type="hidden" name="input_timer_confirm" value="1">
    <input type="hidden" name="ps_period" value="<?php echo (Currency_exchange_model::get_ps($order->sell_system)->is_bank == 1)?Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK:Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT ?>">
<?php
    break;
} 
?>
<input type="hidden" name="curent_date" value="<?php echo time() ?>">
<input type="hidden" name="ps_is_bank" value="<?php // echo time() ?>">