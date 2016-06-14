<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<? if (!empty($pays)) { ?>

    <table cellspacing="0" class="payment_table">
        <thead>
            <tr>
                <th><?=_e('accaunt/transaction_5')?></th>
                <th><?=_e('accaunt/transaction_6')?></th>
                <th><?=_e('accaunt/transaction_7')?></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($pays as $item) { ?>
                <tr>
                    <td style='text-align:center;'><?= date_formate_view($item->date) ?></td>
                    <td style='padding-left:10px;padding-right:10px;width:auto;'><?= $item->description ?><br/><?=(($item->other) ? $item->other."<br/>" : "")?><span style="font-size:11px;color:#858585;">transaction_id:<?= $item->transaction_id ?> order_id:<?= $item->order_id ?>  // <?=base64_decode($item->title); ?></span></td>
                    <td style='text-align:right;width:80px;padding-right:15px;'>$ <?= price_format_double($item->amount) ?></td>
                </tr>
            <? } ?>
        </tbody>
    </table>

    <?= $pages; ?>
<? } else echo _e("У вас нет транзакций."); ?>
