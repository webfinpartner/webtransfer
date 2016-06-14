<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h2><?=sprintf(_e('currency_exchange/mail/order_confirm_close/title'), isset($order->original_order_id)?$order->original_order_id:$order->id)?></h2>
        <br/>
        <h3><?= _e('currency_exchange/mail/order_confirm_close/details')?> </h3>
        <table>
            <?php if($order->initiator == 0): ?>
                <tr>
                    <td><?= _e('currency_exchange/mail/order_confirm_close/i_give')?> </td>
                    <td>
                        <?php // vre($curent_user_id); ?>
                        <?php // pre($order); ?>
                        <?php foreach ($order->payment_systems as $val): ?>
                        <?php if( $val->type != Currency_exchange_model::ORDER_TYPE_SELL_WT ) continue;    ?>
                         <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?> 
                                <?//=_e('currency_id_'.$val->currency_id)?>
                         <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                         <br/>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <td><?= _e('currency_exchange/mail/order_confirm_close/getting')?> </td>
                    <td>
                        <?php foreach ($order->payment_systems as $val): ?>
                         <?php if( $val->type != Currency_exchange_model::ORDER_TYPE_BUY_WT )  continue; ?>
                         <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?> 
                                 <?//=_e('currency_id_'.$val->currency_id)?>
                         <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                         <br/>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php else: ?>
            <tr>
                <td><?= _e('currency_exchange/mail/order_confirm_close/i_give')?> </td>
                <td>
                    <?php foreach ($order->payment_systems as $val): ?>
                    <?php if( $val->type != Currency_exchange_model::ORDER_TYPE_BUY_WT ) continue; ?>
                        <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?> 
                            <?//=_e('currency_id_'.$val->currency_id)?>
                        <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                        <br/>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td><?= _e('currency_exchange/mail/order_confirm_close/getting')?> </td>
                <td>
                    <?php foreach ($order->payment_systems as $val): ?>
                     <?php if( $val->type != Currency_exchange_model::ORDER_TYPE_SELL_WT )  continue; ?>
                        <?=Currency_exchange_model::get_ps($val->machine_name)->humen_name?> - <?= $val->summa?> 
                             <?//=_e('currency_id_'.$val->currency_id)?>
                        <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $val]); ?>
                        <br/>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endif ?>
            <tr><td><?= _e('currency_exchange/mail/order_confirm_close/create_date')?></td><td><?=date('d.m.y',strtotime($order->seller_set_up_date)) ?></td></tr>
            <tr>
                <td><?= _e('currency_exchange/mail/order_confirm_close/fee')?></td>
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
                <tr><td><?= _e('currency_exchange/mail/order_confirm_close/contr')?></td><td><?=$buyer_user->name?> <?=$buyer_user->sername?></td></tr>
            <?php elseif(!empty($buyer_user) && $curent_user_id == $buyer_user->id_user):?>    
                <tr><td><?= _e('currency_exchange/mail/order_confirm_close/contr')?></td><td><?=$seller_user->name?> <?=$seller_user->sername?></td></tr>
            <?php endif; */?>
            <?php if(!empty($buyer_user)):?>
                <tr><td><?= _e('currency_exchange/mail/order_processing/contr')?></td><td><?=$buyer_user->name?> <?=$buyer_user->sername?></td></tr>
            <?php endif; ?>
        </table>        
        
        <?= _e('currency_exchange/mail/order_confirm_close/signature')?>
    </body>
</html>