<!DOCTYPE html>
<html>
    <head></head>
    <body>
         <h2><?=sprintf(_e('currency_exchange/mail/delete/title'), isset($order->original_order_id)?$order->original_order_id:$order->id)?></h2>
        <br/>
        <h3><?= _e('currency_exchange/mail/delete/details')?> </h3>
        <table>
            <tr>
                <td><?= _e('currency_exchange/mail/delete/i_give')?>: </td>
                <td>
                    <?php foreach ($order->payment_systems as $val): ?>
                    <?php if( $send_kontragent === false && $val->type != Currency_exchange_model::ORDER_TYPE_BUY_WT )
                          {   
                            continue;
                          }
                          elseif($send_kontragent === true && $val->type != Currency_exchange_model::ORDER_TYPE_SELL_WT )
                          {
                              continue;
                          }
                        ?>
                     <?//=_e('currency_name_'.$val->machine_name)?>
                     <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?>- <?= $val->summa?> 
                     <?//=_e('currency_id_'.$val->currency_id)?>
                     <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                     </br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td><?= _e('currency_exchange/mail/delete/getting')?>: </td>
                <td>
                    <?php foreach ($order->payment_systems as $val): ?>
                    <?php if( $send_kontragent === false && $val->type != Currency_exchange_model::ORDER_TYPE_SELL_WT )
                          {   
                            continue;
                          }
                          elseif($send_kontragent === true && $val->type != Currency_exchange_model::ORDER_TYPE_BUY_WT )
                          {
                              continue;
                          }
                        ?>
                     <?//=_e('currency_name_'.$val->machine_name)?>
                    <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?> 
                     <?//=_e('currency_id_'.$val->currency_id)?>
                     <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                     </br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr><td><?= _e('currency_exchange/mail/delete/create_date')?></td><td><?=date('d.m.y',strtotime($order->seller_set_up_date)) ?></td></tr>
            <tr>
                <td><?= _e('currency_exchange/mail/delete/fee')?></td>
                <td>
                    <?php if(isset($order->get_fee)): ?>
                        <?=$order->get_fee?>
                        <?//=_e('currency_id_'.$order->get_fee_currency_id)?>
                        <?=Currency_exchange_model::show_payment_system_code(['currency_id' => $order->get_fee_currency_id]); ?>
                    <?php else: ?>
                        <?=$order->get_percent?> %
                    <?php endif; ?>
                </td>
            </tr>
            
        </table>        
        <br/><br/>
        <?= _e('currency_exchange/mail/delete/signature')?>
    </body>
</html>