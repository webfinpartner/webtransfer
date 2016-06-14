<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div>Original</div>
<form method="POST" action="/opera/currency_exchange/save_original_order"> 
    <input type="hidden" id="submited" name="back_url" value="<?= uri_string()  ?>"/>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td>id</td>                        

                <td>seller user id</td>                                              

                <td>buyer user id</td>

                <td>seller amount</td>
                <td>bonus</td>
                <td>seller fee</td>
                <td>status</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>

        <? if( empty( $orders['original'] ) ): ?>
            <tr><td colspan="8"><span style="text-align: center;">There is no order</span></td></tr>
        <? else: ?>
        <? $ao = $orders['original']; ?>
            <tr>
                    <td><?= $ao->id ?></td>                                
                    <td><?= $ao->seller_user_id ?></td>

                    <td><input type="text" value="<?= $ao->buyer_user_id?>" name="original_order[<?= $ao->id ?>][buyer_user_id]" onchange="save_form(<?= $ao->id ?>);"/></td>

                    <td><?= $ao->seller_amount ?></td>
                    <td><?= $ao->bonus ?></td>
                    <td><?= $ao->seller_fee ?></td>
                    <td>                                
                        <select style="width: 100px;" class="status" name="archive_orders[<?= $ao->id ?>][status]" onchange="save_form(<?= $ao->id ?>);">                    
                            <?= renderSelect(getCurExchStatuses(),$ao->status) ?>
                        </select>
                    </td>
                    <td>              
                        <input type="hidden" id="save_<?= $ao->id ?>" name="original_order[<?= $ao->id ?>][save]" value="0">
                        <button class="wButton greenwB" title="" name="submited" type="submit" style="line-height: 14px;">
                            <span style="padding:0;">Сохранить</span>
                        </button>
                    </td>                        
            </tr> 
        <? endif; ?>

        </tbody>            
    </table>
</form>
<div>Archive</div>
<form method="POST" action="/opera/currency_exchange/save_archive_order"> 
    <input type="hidden" id="submited" name="back_url" value="<?= uri_string()  ?>"/>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <td>id</td>                        
                <td>orig id</td>
                <td>initiator</td>
                <td>seller user id</td>                                              
                <td>buyer user id</td>
                <td>buyer order id</td>
                <td>buyer date</td>
                <td>seller amount</td>
                <td>Bonus</td>
                <td>seller fee</td>
                <td>seller document</td>
                <td>buyer document</td>
                <td>status</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
        <? foreach( $orders['archive'] as $ao): ?>
            <tr>
                    <td>
                        <a href="/opera/currency_exchange/order_arhiv/<?= $ao->id ?>" target="_blank"><?= $ao->id ?></a>
                    </td>
                    <td><?= $ao->original_order_id ?></td>
                    <td><?= $ao->initiator ?></td>
                    <td><?= $ao->seller_user_id ?></td>

                    <td><?= $ao->buyer_user_id ?></td>
                    <td><?= $ao->buyer_order_id ?></td>
                    <td><?= $ao->buyer_confirmation_date ?></td>
                    <td><?= $ao->seller_amount ?></td>
                    <td><?= $ao->bonus ?></td>
                    <td><?= $ao->seller_fee ?></td>
                    <td>
                        <? if(!isset($ao->seller_document_image) ):?>
                            -
                        <? else:?>
                            <a href="/opera/currency_exchange/show_any_document/<?= $ao->seller_document_image ?>">Open</a>
                        <? endif;?>
                    </td>
                    <td>                                    
                        <? if(!isset($ao->buyer_document_image) ):?>
                            -
                        <? else:?>
                            <a href="/opera/currency_exchange/show_any_document/<?= $ao->buyer_document_image ?>">Open</a>
                        <? endif;?>
                    </td>
                    <td>                                
                        <select style="width: 100px;" class="status" name="archive_orders[<?= $ao->id ?>][status]" onchange="save_form(<?= $ao->id ?>);">                    
                            <?= renderSelect(getCurExchStatuses(),$ao->status) ?>
                        </select>
                    </td>
                    <td>              
                        <input type="hidden" id="save_<?= $ao->id ?>" name="archive_orders[<?= $ao->id ?>][save]" value="0">
                        <button class="wButton greenwB" title="" name="submited" type="submit" style="line-height: 14px;">
                            <span style="padding:0;">Сохранить</span>
                        </button>
                    </td>                        
            </tr>
            <? endforeach; ?>
        </tbody>            
    </table>
</form>
