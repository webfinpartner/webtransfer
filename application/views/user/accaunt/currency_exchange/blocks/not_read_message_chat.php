<?php if(!isset($my_sell_list) && isset($order->original_order_id) && 
            (
             isset($order_count_chat[$order->id]) && $order_count_chat[$order->id] > 0 ||
             isset($order_count_chat[$order->buyer_order_id]) && $order_count_chat[$order->buyer_order_id] > 0
            )
        ): 

    $count = 0;
    $count += isset($order_count_chat[$order->id])?$order_count_chat[$order->id]:0;
    $count += isset($order_count_chat[$order->buyer_order_id])?$order_count_chat[$order->buyer_order_id]:0;
?>
    <img class="currency_exchange_bell_for_chat"  src="/images/currency_exchange/bell.png" />
<?php endif; ?>
    
<?php if(isset($my_sell_list) && $my_sell_list === true && isset($order->original_order_id) && 
            (
             isset($order_chat_conf[$order->id]) && $order_chat_conf[$order->id] > 0 ||
             isset($order_chat_conf[$order->buyer_order_id]) && $order_chat_conf[$order->buyer_order_id] > 0
            )
        ): 

    $count = 0;
    $count += isset($order_chat_conf[$order->id])?$order_chat_conf[$order->id]:0;
    $count += isset($order_chat_conf[$order->buyer_order_id])?$order_chat_conf[$order->buyer_order_id]:0;
?>
    <img class="currency_exchange_bell_for_chat"  src="/images/currency_exchange/bell.png" />
<?php endif; ?>

    
