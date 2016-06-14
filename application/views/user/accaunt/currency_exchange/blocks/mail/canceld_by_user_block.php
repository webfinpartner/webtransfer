<!DOCTYPE html>
<html>
    <head></head>
    <body>
         <?=sprintf(_e('Контрагент не выполнил свои обязательства по заявке № %s. Ваша заявка переведена в статус Выставлена и видна в поиске.'), isset($order->original_order_id)?$order->original_order_id:$order->id)?>
        
        <?= _e('Вы можете обжаловать это решение, написав письмо по адресу: p2p-security@webtransfer.com')?>
        
        <?= _e('currency_exchange/mail/delete/signature')?>
    </body>
</html>