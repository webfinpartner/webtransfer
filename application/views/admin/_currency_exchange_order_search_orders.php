<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div>User info</div>
    <div style="margin-bottom:10px;">
        <div>
            <div style="width:150px; float:left;">ФИО</div>
            <div style="width:400px;"><?= $search_orders['user']['full_name'] ?></div>
        </div>
        <table class="table table-striped table-bordered table-hover" style="width: 28%;">
            <tr>
                <td></td>
                <td>USD-H</td>
                <td>C-CREDS</td>
                <td>USD1</td>
                <td>Debit</td>
            </tr>
            <tr>
                <td>Доступно</td>
                <td><?= $search_orders['user']['scores']['payout_limit_by_bonus'][2] ?></td>
                <td><?= $search_orders['user']['scores']['payout_limit_by_bonus'][4] ?></td>
                <td><?= $search_orders['user']['scores']['payout_limit_by_bonus'][5] ?></td>
                <td><?= $search_orders['user']['scores']['payout_limit_by_bonus'][6] ?></td>
            </tr>
            <tr>
                <td>На обмен</td>
                <td><?= $search_orders['user']['scores']['total_processing_p2p_by_bonus'][2] ?></td>
                <td><?= $search_orders['user']['scores']['total_processing_p2p_by_bonus'][4] ?></td>
                <td><?= $search_orders['user']['scores']['total_processing_p2p_by_bonus'][5] ?></td>
                <td><?= $search_orders['user']['scores']['total_processing_p2p_by_bonus'][6] ?></td>
            </tr>
            <tr>
                <td>Сумма сведённых</td>
                <td><?= $search_orders['user']['scores']['net_processing_p2p_by_bonus'][2] ?></td>
                <td><?= $search_orders['user']['scores']['net_processing_p2p_by_bonus'][4] ?></td>
                <td><?= $search_orders['user']['scores']['net_processing_p2p_by_bonus'][5] ?></td>
                <td><?= $search_orders['user']['scores']['net_processing_p2p_by_bonus'][6] ?></td>
            </tr>
        </table>
    </div>

    <div>Original</div>
    <form method="POST" action="/opera/currency_exchange/save_original_order">
        <input type="hidden" id="submited" name="back_url" value="<?= uri_string()  ?>"/>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td>id</td>                                                    
                    <td>seller user id</td>                                              
                    <td>buyer user id</td>
                    <td>wt set</td>

                    <td>seller amount</td>
                    <td>bonus</td>
                    <td>seller fee</td>
                    <td>status</td>

                    <td>Action</td>
                </tr>
            </thead>
            <tbody>

            <? if( empty( $search_orders['original'] ) ): ?>
                <tr><td colspan="8"><span style="text-align: center;">There is no order</span></td></tr>
            <? else: ?>
            <? foreach( $search_orders['original'] as $ao): 
                switch( $ao->wt_set )
                {
                    case 1:
                        if( $ao->initiator == 1 ) $ao->wt_set_status = 'Получает ВТ';
                        else
                            $ao->wt_set_status = 'Отдает ВТ';
                            break;
                    case 2: 
                        
                        if( $ao->initiator == 1 ) $ao->wt_set_status = 'Отдает ВТ';
                        else
                            $ao->wt_set_status = 'Получает ВТ';
                        break;    
                }
                
                ?>
                
                         
                <tr>
                        <td><a href="/opera/currency_exchange/order/<?= $ao->id ?>" target="_blank"><?= $ao->id ?></a></td>                                
                        <td><?= $ao->seller_user_id ?></td>

                        <td><?= $ao->buyer_user_id?></td>
                        <td><?= $ao->wt_set_status?></td>
                        <td><?= $ao->seller_amount ?></td>
                        <td><?= $ao->bonus ?></td>
                        <td><?= $ao->seller_fee ?></td>
                        <td>                                
                            <select style="width: 100px;" class="status" name="original_orders[<?= $ao->id ?>][status]" onchange="save_form(<?= $ao->id ?>);">                    
                                <?= renderSelect(getCurExchStatuses(),$ao->status) ?>
                            </select>
                        </td>
                        <td>              
                            <input type="hidden" id="save_<?= $ao->id ?>" name="original_orders[<?= $ao->id ?>][save]" value="0">
                            <button class="wButton greenwB" title="" name="submited" type="submit" style="line-height: 14px;">
                                <span style="padding:0;">Сохранить</span>
                            </button>
                        </td>                        
                </tr> 
            <? endforeach; ?>
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
                    <td>wt set</td>
                    <td>buyer user id</td>
                    <td>buyer order id</td>
                    <td>buyer date</td>
                    <td>seller amount</td>
                    <td>seller fee</td>
                    <td>seller document</td>
                    <td>buyer document</td>
                    <td>status</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
            <?php if( !isset($search_orders['archive']) || empty( $search_orders )  ): ?>
                <tr><td colspan="8"><span style="text-align: center;">There is no order</span></td></tr>
            <?php else: ?>
            <? foreach( $search_orders['archive'] as $ao):
                switch( $ao->wt_set )
                {
                    case 1:
                        if( $ao->initiator == 1 ) $ao->wt_set_status = 'Получает ВТ';
                        else
                            $ao->wt_set_status = 'Отдает ВТ';
                            break;
                    case 2: 
                        
                        if( $ao->initiator == 1 ) $ao->wt_set_status = 'Отдает ВТ';
                        else
                            $ao->wt_set_status = 'Получает ВТ';
                    break;
                }
                
                ?>
                <tr>
                        <td>
                            <a href="/opera/currency_exchange/order_arhiv/<?= $ao->id ?>" target="_blank"><?= $ao->id ?></a>
                        </td>
                        <td><?= $ao->original_order_id ?></td>
                        <td><?= $ao->initiator ?></td>
                        <td><?= $ao->seller_user_id ?></td>
                        <td><?= $ao->wt_set_status?></td>

                        <td><?= $ao->buyer_user_id ?></td>
                        <td><?= $ao->buyer_order_id ?></td>
                        <td><?= $ao->buyer_confirmation_date ?></td>
                        <td><?= $ao->seller_amount ?></td>
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
            <?php endif; ?>
            </tbody>            
        </table>
    </form>