<!DOCTYPE html>
<html>
    <head></head>
    <body>
         <?=sprintf(_e('Вы не подтвердили отправку средств от контрагента по заявке № %s за что были заблокированы.'), isset($order->original_order_id)?$order->original_order_id:$order->id)?>
        
        <?= _e('Вы можете обжаловать это решение, написав письмо по адресу: p2p-security@webtransfer.com')?>
        <br/>
        <?= _e('currency_exchange/mail/delete/signature')?>
    </body>
</html>