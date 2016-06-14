<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h2><?=sprintf(_e('currency_exchange/mail/order_error_close/title'), isset($order->original_order_id)?$order->original_order_id:$order->id)?></h2>
        <br/>
        <h3><?= _e('currency_exchange/mail/order_error_close/details')?> </h3>
        <table>
            <tr>
                <td><?= _e('currency_exchange/mail/order_error_close/i_give')?>: </td>
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
                     <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?>
                                <?//=_e('currency_id_'.$val->currency_id)?>
                     <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                     </br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td><?= _e('currency_exchange/mail/order_error_close/getting')?>: </td>
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
                     <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?>
                                <?//=_e('currency_id_'.$val->currency_id)?>
                     <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                     </br>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr><td><?= _e('currency_exchange/mail/order_error_close/create_date')?></td><td><?=date('d.m.y',strtotime($order->seller_set_up_date)) ?></td></tr>
            <tr>
                <td><?= _e('currency_exchange/mail/order_error_close/fee')?></td>
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

            <?php /* if(!empty($buyer_user) && $curent_user_id != $buyer_user->id_user):?>
                <tr><td><?= _e('currency_exchange/mail/order_error_close/contr')?></td><td><?=$buyer_user->name?> <?=$buyer_user->sername?></td></tr>
            <?php elseif(!empty($buyer_user) && $curent_user_id == $buyer_user->id_user):?>
                <tr><td><?= _e('currency_exchange/mail/order_error_close/contr')?></td><td><?=$seller_user->name?> <?=$seller_user->sername?></td></tr>
            <?php endif; */?>
            <?php if(!empty($buyer_user)):?>
                <tr><td><?= _e('currency_exchange/mail/order_processing/contr')?></td><td><?=$buyer_user->name?> <?=$buyer_user->sername?></td></tr>
            <?php endif; ?>
        </table>
        <br/><br/>
        <a href="<?= site_url('account/currency_exchange/my_sell_list'); ?>#<?=isset($order->original_order_id)?'oid='.$order->id:'id='.$order->id ?>"><?= _e('currency_exchange/mail/order_error_close/see_order_status')?></a>
        <br/><br/>
        <?= _e('currency_exchange/mail/order_error_close/signature')?>
    </body>
</html>