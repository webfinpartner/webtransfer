<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<style>
    .formDicableUser{
        color: red;
    }
</style>
<style>
    .table-striped > tbody > tr.blue:nth-child(2n+1) > td,
    tr.blue td, blue{
        background-color: rgba(0,0,255,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.red:nth-child(2n+1) > td,
    tr.red td, red{
        background-color: rgba(255,0,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.green:nth-child(2n+1) > td,
    tr.green td, green{
        background-color: rgba(0,255,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.have_in:nth-child(2n+1) > td,
    tr.have_in td, have_in{
        /*background-color: #cccccc;*/
        color: #009900;
    }
    .table-striped > tbody > tr.user_deactive:nth-child(2n+1) > td,
    tr.user_deactive td, user_deactive{
        /*background-color: #595959;*/
        color: #ddd;
    }
    .table-striped > tbody > tr.std_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.std_credits td.col_id_user_ip, std_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.trans_send:nth-child(2n+1) > td.col_id_user_ip,
    tr.trans_send td.col_id_user_ip, trans_send{
        color: red;
    }
    .table-striped > tbody > tr.diff_acc_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_credits td.col_id_user_ip, diff_acc_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.diff_acc_ip:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_ip td.col_id_user_ip, diff_acc_ip{
        color: red;
    }

    td.black{
        color: black !important;
    }
    .tabs{
        width: auto;
    }
    .selector>span{
        overflow: hidden;
    }
    
    div#uniform-undefined.radio{
        margin-top:14px;
    }
    .formRow .radio input[type="radio"]{
        margin: 0;
    }
</style>
<?php //pre($item); ?>
<?php // pre($item); ?>

<div class="widget">
    <ul class="tabs">
        <li class="activeTab"><a id="tb1" href="#tab1">Инфо</a></li>
        <li><a id="tb2" href="#tab2" onclick="get_user_all_orders();">Все заявки</a></li>                
        <li><a id="tb3" href="#tab3" onclick="get_user_transactions();">Транзакции</a></li>                
        <li><a id="tb4" href="#tab4">Поиск заявок пользователя</a></li>
        <li><a id="tb5" href="#tab5" onclick="get_user_prefund_transactions();">Префанд</a></li>
    </ul>
    <div class="tab_container">
        <div id="tab1" class="tab_content" style="display: block;">
            <div id="dlg_history" class="easyui-dialog" title="История коментариев" data-options="closed: true" style="width:500px;height:300px;padding:10px">
                <table class="easyui-datagrid" style="width:400px;height:250px"
                        data-options="fitColumns:true,singleSelect:true, fit: true, nowrap: false">
                    <thead>
                        <tr>
                            <th data-options="field:'date',width:120">Дата</th>
                            <th data-options="field:'admin',width:100">Оператор</th>
                            <th data-options="field:'note',width:300">Текст</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($item->history_notes as $note){ ?>
                        <tr>
                            <td><?=$note->date_modified?></td><td><?=$note->operator_name?></td><td><?=$note->text?></td>
                        </tr>
                        <? } ?>
                    </tbody>            

                </table>
            </div>
            <form id="validate" class="form" action="<?php echo isset($form_action)?$form_action:''; ?>"  method="post">
               
                <input type="hidden" id="submited" name="submited" value="1"/>
                
                <input type="hidden" id="submited" name="back_url" value="<?= uri_string()  ?>"/>
                <input type="hidden" id="submited" name="order_url" value="<?= uri_string()  ?>"/>
                
                <input type="hidden" name="original_order_id" value="<?= $original_order_id ?>"/>
                
                <input type="hidden" name="archive_order_ids[]" value="<?= $archive_order_ids[0] ?>"/>
                <input type="hidden" name="archive_order_ids[]" value="<?= $archive_order_ids[1] ?>"/>
                

                <fieldset>
                    <div class="widget">
                        <div class="title">
                            <img class="titleIcon" alt="" src="images/icons/dark/list.png">
                            <h6>Транзакция №<?=$item->id?>
                            <? if( isset( $item->original_order_id ) && $item->original_order_id != $item->id ): ?>
                                <a target="_blank" href="/opera/currency_exchange/order/<?= $item->original_order_id ?>">Оригинал:<?= $item->original_order_id ?></a>
                            <? endif; ?>
                            </h6> 
                        </div>
                        <div class="formRow">
                            <label>
                                Инициатор (IP:<?= $item->seller_ip ?>)<br/>                        
                                    <? if( $item->seller_blocked === TRUE ){  ?>
                                    <span style="color: red;">(Заблокирован)</span>
                                <? }  ?>
                            </label>
                            <div class="formRight">
                                <input type="text" id="seller_user_id" name="seller_user_id" value="<?=@$item->seller_user_id?>" readonly="readonly"></div>
                            <div class="clear"></div>
                        </div>
                        <?php if($item->buyer_user_id != 0): ?>
                        <div class="formRow">
                            <label>
                                Контрагент (IP:<?= $item->buyer_ip ?>)<br/>                    
                                <? if( $item->buyer_blocked === TRUE ){  ?>
                                    <span style="color: red;">(Заблокирован)</span>
                                <? }  ?>
                            </label>
                            <div class="formRight"><input type="text" id="seller_user_id" name="buyer_user_id" value="<?=@$item->buyer_user_id?>" readonly="readonly"></div>
                            <div class="clear"></div>
                        </div>
                        <?php else: ?>
                        <div class="formRow">
                            <label>Контрагент</label>
                            <div class="formRight">Заявка не сведена</div>
                            <div class="clear"></div>
                        </div>
                        <?php endif; ?>

                        <div class="formRow">
                            <label>Коммисия</label>
                            <div class="formRight"><input type="text" id="seller_user_id" name="seller_fee" value="<?=@$item->seller_fee?>" readonly="readonly"></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Сумма:</label>
                            <div class="formRight"><input type="text"   id="seller_amount"  name="seller_amount"  value="<?=@$item->seller_amount?>" readonly="readonly"></div>
                            <div class="clear"></div>
                        </div>


                        <div class="formRow">
                            <div style="float:left;">
                                <label>Отдает:</label>
                                <div>
                                    <a class="wButton greenwB ml15 m10" style="float: left; height: 26px; margin: 4px 4px 0 0; line-height: 28px;" href="/opera/users/<?=@$item->seller_user_id?>" target="_balank"><span>Профиль</span></a>
                                    <a class="wButton redwB ml15 m10" style="float: left; height: 26px; margin: 4px 4px 0 0; line-height: 12px;" href="#" onclick="reset_timer(<?= $archive_order_ids[0] ?>,<?= $archive_order_ids[1] ?>,'buyer');return false;"><span>Обнулить<br/>Счетчик</span></a>
                                </div>
                            </div>
                            <div class="formRight">
                                <div style="float:left;">
                                <?php foreach($item->payment_systems as $v): ?>
                                <?php if( $v->type == Currency_exchange_model::ORDER_TYPE_SELL_WT ) continue; ?>
                                <?php if( $item->sell_system ): ?>
                                    <?php if( $item->sell_system != $v->payment_system) continue; ?>
                                <?php endif; ?>


                                    <?php
                                    if(Currency_exchange_model::get_ps($v->payment_system)->humen_name == 'Webtransfer VISA Card') {
                                        ?>
                                        <? if(!empty($item->prefund_status)): ?>
                                            <label style="color:red">
                                                    <?=$item->prefund_status; ?>


                                            </label>
                                        <? endif; ?>

                                        <?
                                    }
                                    ?>
                                        <div style="line-height: 20px; width: 200px; clear: both;">
                                            
                                            <input type="radio" name="selected_sell_system" id="ps-s-<?= $v->payment_system ?>" class="easyui-validatebox" value="<?= $v->payment_system ?>"/>
                                            
                                            <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$v->payment_system]->public_path_to_icon ?>; float:left; margin-right: 5px; background-image: url('/images/currency_exchange/spritesheet.png');  background-repeat: no-repeat;  display: block;  width: 45px;  height: 44px;"></div>
                                            <div style="float: left;width: 100px;line-height: 14px;margin-top: 10px">








                                                <?=Currency_exchange_model::get_ps($v->payment_system)->humen_name?><br/>
                                                <?= $v->summa ?> 
                                                <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $v]);?>
                                            </div>
                                            <?php if(isset($item->sell_payment_data_un[$v->payment_system]) && !empty($item->sell_payment_data_un[$v->payment_system]->payment_data)): ?>     
                                                <br/> Реквизиты: 
                                                <?php echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$v->payment_system], $item->sell_payment_data_un[$v->payment_system]->payment_data, ['order' => $item, 'curent_user' => $user_id, 'template' => 'template_admin' ]); ?>

                                            <?php endif; ?>




                                        </div>
                                
                                    
                                <?php endforeach; ?> 
                                </div>
                                <div style="float:left;">  
                                    <div style="width: 100px; float:left;"><?= $item->seller_name ?></div>
                                    <?php  if($item->wt_set == 1): ?>
                                        <div style="width: 100px; float:left;">
                                            На обмен всего<br>
                                            <? if( $item->bonus == 0 || $item->bonus == 5 ) echo "USD1: $ ".price_format_double( $item->seller_active_p2p[5] ) ?>
                                            <? if( $item->bonus == 4 ) echo "C-CREDS: $ ". price_format_double( $item->seller_active_p2p[4] ) ?>
                                            <? if( $item->bonus == 2 ) echo "USD-H: $ ". price_format_double( $item->seller_active_p2p[2] ) ?>
                                            <? if( $item->bonus == 6 ) echo "Debit: $ ". price_format_double( $item->seller_active_p2p[6] ) ?>
                                        </div>
                                        <div style="width: 100px; float:left;">
                                            Доступно<br>
                                            <? if( $item->bonus == 0 || $item->bonus == 5 ) echo "USD1: $ ".price_format_double( $item->seller_payout_limit[5] ) ?>
                                            <? if( $item->bonus == 4 ) echo "C-CREDS: $ ". price_format_double( $item->seller_payout_limit[4] ) ?>
                                            <? if( $item->bonus == 2 ) echo "USD-H: $ ". price_format_double( $item->seller_payout_limit[2] ) ?>                                            
                                            <? if( $item->bonus == 6 ) echo "Debit: $ ". price_format_double( $item->seller_payout_limit[6] ) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php  if($item->wt_set != 1 && $item->buyer_user_id != 0 ): ?>
                                        <div style="width: 100px; float:left;">
                                            <? if( !empty( $docs_link ) ) {?>
                                                <a target="_blank" href="<?= $docs_link ?>">Документы загружены.<br>>>Посмотреть</a>
                                            <?}else{?>
                                                <div>Документы<br>не загружены</div>
                                            <?}?>                            
                                        </div>
                                        <? if($item->seller_payment_data): ?>
                                        <div style="width: 250px; float:left;">
                                            <div>Реквизиты: <?= $item->seller_payment_data ?></div>                            
                                            </div>                            
                                        </div>
                                        <? endif;  ?>

                                    <?php endif; ?>


                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <div style="float:left;">
                                <label>Получает:</label> 
                                <div>
                                    <a class="wButton greenwB ml15 m10" style="float: left; height: 26px; margin: 4px 4px 0 0px; line-height: 28px;" href="/opera/users/<?=@$item->buyer_user_id?>" target="_balank"><span>Профиль</span></a>
                                </div>
                            </div>
                            
                            <div class="formRight">
                                <div style="float:left;">
                                    <?php foreach($item->payment_systems as $v): ?>
                                        <?php if( $v->type == Currency_exchange_model::ORDER_TYPE_BUY_WT ) continue; ?>
                                        <?php if( $item->payed_system ):?>
                                            <?php if( $item->payed_system != $v->payment_system) continue; ?>
                                        <?php endif; ?>



                                        <?php
                                        if(Currency_exchange_model::get_ps($v->payment_system)->humen_name == 'Webtransfer VISA Card') {
                                            ?>
                                            <? if(!empty($item->prefund_status)): ?>
                                                <label style="color:red">
                                                    <?=$item->prefund_status; ?>


                                                </label>
                                            <? endif; ?>

                                            <?
                                        }
                                        ?>

                                            <div style="line-height: 15px; width: 200px; clear: both;">
                                                <input type="radio" name="selected_payed_system" id="ps-b-<?= $v->payment_system ?>" value="<?= $v->payment_system ?>" />
                                                
                                                <div class="onehrineico sprite_payment_systems" style="<?= $payment_systems_id_arr[$v->payment_system]->public_path_to_icon ?>; float:left; margin-right: 5px; background-image: url('/images/currency_exchange/spritesheet.png');  background-repeat: no-repeat;  display: block;  width: 45px;  height: 43px;"></div>
                                                <div style="float: left;width: 100px;line-height: 14px;margin-top: 10px">
                                                    <?=Currency_exchange_model::get_ps($v->payment_system)->humen_name?><br/>
                                                    <?= $v->summa ?> 
                                                    <?=Currency_exchange_model::show_payment_system_code(['pay_sys' => $v]);?>
                                                </div>
                                                        <!-- Аккаунт: <?=$v->payment_data?>   -->
                                                   <?php if(isset($item->buy_payment_data_un[$v->payment_system]) && !empty($item->buy_payment_data_un[$v->payment_system]->payment_data)): ?>     
                                                        <br/> Реквизиты: <?//=$item->buy_payment_data_un[$v->payment_system]->payment_data?>
                                                        <?php echo Currency_exchange_model::get_ps_data_for_table($payment_systems_id_arr[$v->payment_system], $item->buy_payment_data_un[$v->payment_system]->payment_data, ['order' => $item, 'curent_user' => $user_id, 'template' => 'template_admin' ]); ?>
                                                   <?php endif; ?>


                                            </div>
                                        
                                    <?php endforeach; ?>                     
                                </div>
                                <div style="float:left;">
                                    <div style="width: 100px; float:left;"><?= $item->buyer_name ?></div>                                        
                                    <?php if($item->wt_set == 2): ?>                                        
                                        <div style="width: 100px; float:left;">
                                            На обмен всего<br>
                                            <? if( $item->bonus == 0 || $item->bonus == 5 ) echo "USD1: $ ".price_format_double( $item->buyer_active_p2p[5] ) ?>
                                            <? if( $item->bonus == 4 ) echo "C-CREDS: $ ". price_format_double( $item->buyer_active_p2p[4] ) ?>
                                            <? if( $item->bonus == 2 ) echo "USD-H: $ ". price_format_double( $item->buyer_active_p2p[2] ) ?>
                                            <? if( $item->bonus == 6 ) echo "Debit: $ ". price_format_double( $item->buyer_active_p2p[6] ) ?>
                                        </div>
                                        <div style="width: 100px; float:left;">
                                            Доступно<br>
                                            <? if( $item->bonus == 0 || $item->bonus == 5 ) echo "USD1: $ ".price_format_double( $item->buyer_payout_limit[5] ) ?>
                                            <? if( $item->bonus == 4 ) echo "C-CREDS: $ ". price_format_double( $item->buyer_payout_limit[4] ) ?>
                                            <? if( $item->bonus == 2 ) echo "USD-H: $ ". price_format_double( $item->buyer_payout_limit[2] ) ?>                                            
                                            <? if( $item->bonus == 6 ) echo "Debit: $ ". price_format_double( $item->buyer_payout_limit[6] ) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php  if( $item->wt_set != 2 && $item->buyer_user_id != 0): ?>
                                        <div style="width: 100px; float:left;">
                                            <? if( !empty( $docs_link ) ) {?>
                                                <a target="_blank" href="<?= $docs_link ?>">Документы загружены.<br>>>Посмотреть</a>
                                            <?}else{?>
                                                <div>Документы<br>не загружены</div>
                                            <?}?>
                                        </div>
                                        <? if( !empty($item->buyer_payment_data) ): ?>
                                        <div style="width: 100px; float:left;">
                                            <div>Реквизиты: <?= $item->buyer_payment_data ?></div>                            
                                        </div>
                                        <? endif;?>

                                    <?php endif; ?>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

            <!--            <div class="formRow">
                            <label>К получению:</label>
                            <div class="formRight"><input type="text"   id="buyer_amount_down"  name="buyer_amount_down"  value="<?=@$item->buyer_amount_down?>" readonly="readonly"></div>
                            <div class="clear"></div>
                        </div>-->



                        <div class="formRow">
                            <label>Дата:</label>
                            <div class="formRight">
                                <input type="text"  id="seller_set_up_date"  name="seller_set_up_date"  value="<?=@$item->seller_set_up_date?>" readonly="readonly"/>
                            </div>
                            <div class="clear"></div>
                        </div>
            <!--            <div class="formRow">
                            <label>Системы:</label>
                            <div class="formRight">
                                <?php // vre($payment_systems_id_arr); ?>

                            </div>
                            <div class="clear"></div>
                        </div>-->

                        <div class="formRow">
                            <label>Статус</label>
                            <div class="formRight">                                        
                                <select class="status" name="seller_new_order_status" >                    
                                    <?= renderSelect(getCurExchStatuses(),$item->status) ?>
                                </select>
                                <?php if(!empty($item->buyer_user_id ) ): ?>
                                <span style="color: red">РАЗРЫВАТЬ/ПОДТВЕРЖДАТЬ/ОТМЕНЯТЬ только КНОПКАМИ!</span>
                                <?php endif;?>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <?php if ( !empty($item->last_reject_note) ){ ?>
                        <div class="formRow">
                            <label>Причина отклонения:</label>
                            <div class="formRight" style="color:red">                    
                               <?=$item->last_reject_note->text?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php } ?>
                       
                        <div class="formRow">
                            <label>Коментарий оператора:</label>
                            <div class="formRight"><textarea name="admin_note_text" id="admin_note_text"><?= $item->last_note->text ?></textarea>
                                <a href="#" onclick="$('#dlg_history').dialog('open'); return false;">История коментариев</a>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="formRow">
                                <label>Повторяющиеся транзакции:</label>
                                <div class="formRight">
                                    <? if($double) {?>
                                        <table width="100%">
                                                <tbody>
                                                <tr>
                                                    <td>id</td>
                                                    <td>Заметка</td>
                                                    <td>Сумма</td>
                                                    <td>Повторений</td>
                                                </tr>
                                                <? foreach ($double as $row) {?>
                                                <tr>
                                                    <td><?=$row->id?></td>
                                                    <td><?=$row->note?></td>
                                                    <td><?=$row->summa?></td>
                                                    <td><?=$row->c?></td>
                                                </tr>
                                                    <?}?>
                                                </tbody>
                                        </table>
                                        <br/><br/>
                                    <?}else{?>
                                        <div>Отсутствуют</div>
                                    <?}?>
                                </div>
                                <div class="clear"></div>
                        </div>
 
                        <div class="formRow">
                                <label>Чат проблемы:</label>
                                <div class="formRight">
                                    <? if( !empty($problem_chat) ) {?>
                                        <a  target="_blank" href="<?= $problem_chat; ?>">Открыть</a>
                                    <?}else{?>
                                        <div>Отсутствует</div><a target="_blank" href="<?= $new_problem_chat; ?>">Создать</a>
                                    <?}?>
                                </div>
                                <div class="clear"></div>
                        </div>
<?php /* ?>
                    </div>
<?php */ ?>
                </fieldset>
                <input type="hidden" name="id" value="<?=$item->id?>" /> 
                <div>
                    <label>
                        <input type="checkbox" name="go_back_to_all" value="1" /> 
                        <span style="margin-left: 10px;">Вернуться в общий список заявок</span>
                    </label> 
                </div>
                
                <div style="float:left;width: 49%; ">
                    <center>            
                        <?php if( $item->status != Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK): ?>
                            <a class="wButton greenwB ml15 m10" onclick="save_order($('#validate')); return (false);" title="" href="#"><span>Сохранить</span></a><br>
                        <?php elseif(isset($item->original_order_id) && 
                                ($item->status == Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK || 
                                    $item->status == Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR) ||
                                    $item->status == Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR
                                ): ?>
                            <a class="wButton greenwB ml15 m10" onclick="save_order_arhiv($('#validate')); return (false);" title="" href="#"><span>Сохранить</span></a><br>
                        <?php else: ?>
                            <div style="width: 300px; display: inline-flex;">
                                Функция сохранить недоступна для данной заявки.
                            </div>
                        <?php endif; ?>

                        <?php if($item->buyer_user_id == 0): ?>
                            <a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/page/all"  onclick="reject_order($('#validate')); return (false)">
                                <span>Отклонить</span>
                            </a>
                        <?php else: ?>

                            <?php if( $item->status != Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK && 
                                            (!isset($buyer_item) || $buyer_item->seller_send_money_date == 0)): ?>
                                <a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/page/all"  onclick="reject_order($('#validate')); return (false)">
                                    <span>Отклонить</span>
                                </a>
                            <?php else: ?>
                                <div style="width: 300px; display: inline-flex;">

                                Функция отклонить недоступна для данной заявки.
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                                <? //if ($this->admin_info->permission == 'admin') { ?>
                        <!--<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false);" title="" href="#"><span>Подтвердить</span></a>-->
                            <?php if( $item->status != Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK &&
                                    (!isset($buyer_item) || $buyer_item->seller_send_money_date == 0)): ?>
                                <a class="wButton greenwB ml15 m10" onclick="confirm_order($('#validate')); return (false);" title="" href="#">
                                    <span>Подтвердить</span>
                                </a>
                            <?php else: ?>
                                <div style="width: 300px; display: inline-flex;">                        
                                    Функция подтвердить недоступна для данной заявки.
                                </div>
                            <?php endif; ?>

                                <? //} ?>
                    </center>
                </div>
                <div style="float:left; width: 50%; ">
                    <center>
                        <?php if( !empty($item->buyer_user_id) && !empty($item->seller_user_id) &&
                                in_array($item->status, array( Currency_exchange_model::ORDER_STATUS_CONFIRMATION, Currency_exchange_model::ORDER_STATUS_PROCESSING, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB ) )
                            ): ?>
                                <a class="wButton bluewB ml15 m10" onclick="operator_last_confirm_ordr($('#validate')); return (false);" title="" href="#"><span>Провести сделку</span></a>
                                <input type="hidden" name="back_url" value="/opera/currency_exchange/all" />

                                <?php /* ?><a class="wButton redwB ml15 m10" onclick="operator_cancell_order($('#validate')); return (false);" title="" href="#"><span>Разорвать сделку</span></a>
                                <br/><?php */ ?>
                                <a class="wButton redwB ml15 m10" onclick="operator_cancell_order_with_cancel_original($('#validate')); return (false);" title="" href="#" style=" background-color: #803939; background-image: none; height: 40px; line-height: 19px;"><span>Разорвать сделку с <br/>отменой оригинала</span></a>
                        <?php else: ?>
                            <!--<a class="wButton bluewB ml15 m10" onclick="operator_last_confirm_ordr($('#validate')); return (false);" title="" href="#"><span>Поровести сделку</span></a>-->
                                <div style="width: 300px; display: inline-flex;">
                                        Функция "Провести сделку" недоступна для данной заявки.
                                </div>
                                <div style="width: 300px; display: inline-flex;">
                                    Функция "Разорвать сделку" недоступна для данной заявки.
                                </div>   
                        <?php endif; ?>            
                    </center>
                </div>
                
            </form>
        </div>
        <div id="tab2" class="tab_content" style="display: none;min-height: 100px">
            <div data-dtable="loading" class="loading">
                <center>
                    <span>Loading...</span><br/>
                    <img src="/images/loading.gif" class="loading-gif"></label>
                </center>
            </div>         
            <div class="result"></div>
        </div>
        <div id="tab3" class="tab_content" style="display: none;min-height: 100px">
            <div data-dtable="loading" class="loading">
                <center>
                    <span>Loading...</span><br/>
                    <img src="/images/loading.gif" class="loading-gif"></label>
                </center>
            </div>
            <div class="result"></div>
        </div>
        <div id="tab4" class="tab_content" style="display: none;min-height: 100px" >
            <div id="search_user_orders_form" style="overflow:auto">
                <div style="margin-bottom: 10px;">
                    <input type="search" name="user_id_search_orders" style="width: 50%;float:left; " placeholder="Введите ИД пользователя" value="<?= $user_id_search_orders ?>"/>
                    
                    <a class="wButton greenwB ml15 m10" style="cursor: pointer; float: left; height: 24px; margin: 0px 4px 0 10px; line-height: 26px;" onclick="search_user_all_orders('#search_user_orders_form'); return false;"><span>Поиск</span></a>
                </div>
            </div>
            <div data-dtable="loading" class="loading" style="display:none;">
                <center>
                    <span>Loading...</span><br/>
                    <img src="/images/loading.gif" class="loading-gif"></label>
                </center>
            </div>
            <div class="result"></div>
        </div>

        <div id="tab5" class="tab_content" style="display: none;min-height: 100px">
            <div data-dtable="loading" class="loading">
                <center>
                    <span>Loading...</span><br/>
                    <img src="/images/loading.gif" class="loading-gif"></label>
                </center>
            </div>
            <div class="result"></div>
        </div>
        
    </div>
</div>


<? /*$this->load->view('admin/blocks/user_block_window.php');*/ ?>
<script>
var $operator_last_confirm_action = '<?php echo isset($operator_last_confirm_action)?$operator_last_confirm_action:''; ?>';  
var $operator_cancell_order = '<?php echo isset($operator_cancell_order)?$operator_cancell_order:''; ?>';  
var $save_order_arhiv = '<?php echo isset($save_order_arhiv)?$save_order_arhiv:''; ?>';  
    
function load_tab_data( id, page_name ){
    var url = 'opera/currency_exchange/'+page_name;
    $(id).find('.loading').show();
    $(id).find('.result').html('');
    
    $.get(url).done(function( resp ){
        
       $(id).find('.loading').hide();
       $(id).find('.result').html( resp );
    });
};

function get_user_transactions(){
    var tab = '#tab3';
    if( $(tab).find('.result').html() != '' ) return false;
    
    load_tab_data(tab,'get_user_transactions/<?= (!empty(  $item->original_order_id )? $item->original_order_id : $item->id) ?>');
};

function get_user_prefund_transactions(){
    var tab = '#tab5';
    if( $(tab).find('.result').html() != '' ) return false;

    load_tab_data(tab,'get_user_prefund_transactions/<?= (!empty(  $item->original_order_id )? $item->original_order_id : $item->id) ?>');
};

function get_user_all_orders(){
    var tab = '#tab2';
    
    if( $(tab).find('.result').html() != '' ) return false;
    load_tab_data(tab,'get_user_orders/<?= (!empty(  $item->original_order_id )? $item->original_order_id : $item->id) ?>');   
};

function search_user_all_orders(form_id){
    
    var val = $(form_id).find("input[name=user_id_search_orders]").val();
    var tab = '#tab4';
    if( val == '' || val === undefined ) 
    {
        $(tab).find('.result').html('Wrong Val element');
        return false;
    }
    
    var user_id = parseInt( val );
    load_tab_data(tab,'search_user_all_orders/'+user_id);   
};
    
function restartPage()
{
//    alert('>>>>');
    window.location.href = "<?=site_url('/opera/users/'.@$item->seller_user_id)?>";
}


function save_order($form)
{
    $form.append('<input type="hidden" name="save" value="1" />');
    $form.submit();
}

function reset_timer(arch_order_id1, arch_order_id2, user_type)
{
    var order_url = $('input[name="order_url"]').val();
    $.post( "/opera/currency_exchange/reset_timer", 
    { arch_order_id1: arch_order_id1, arch_order_id2: arch_order_id2, user_type: user_type, order_url: order_url})
    .done(function( data_src ) {
        if( !data_src )
        {
            alert( "Server error" );
            return false;
        }
        var data = JSON.parse(data_src);
        
        if( !data_src )
        {
            alert( "Server error" );
            return false;
        }
        if( data['error'] ) alert( data['error'] );
        else
            if( data['success'] ) alert( data['success'] );
        
    });
}

function save_order_arhiv($form)
{
    $.messager.prompt('Запрос', 'Введите причину', function(r){
        if (r){
            $form.append('<textarea style="display: none" name="admin_note_text">'+r+'</textarea>');
//            $form.append('<input type="hidden" name="reject" value="1" />');
            $form.attr('action', $save_order_arhiv);
            $form.submit();
        } else return;
    });
//    admin_note_text
//    $form.attr('action', $save_order_arhiv);
//    $form.submit();
}
function save_form($id)
{    
    var input = $('#save_'+$id);

    if( input.length != 1 ) return false;
    input.val(1);
    
}
    

function confirm_order($form)
{
    $form.validationEngine('detach');
    
    $form.submit();
}

//function reject_order($form)
//{
//    $.messager.prompt('Запрос', 'Введите причину', function(r){
//                if (r){
//                    $form.append('<textarea style="display: none" name="reject_text">'+r+'</textarea>');
//                    $form.append('<input type="hidden" name="reject" value="1" />');
//                    $form.submit();
//                } else return;
//            });
//    
//}
function reject_order($form)
{
    $.messager.prompt('Запрос', 'Введите причину', function(r){
        if(!r)
        {
            return false;
        }
        else
        {
            var reject_text = r;
        }
        
        $.messager.prompt('Запрос', 'Введите причину (будет видна пользователю)', function(r){
            if (r){
                $form.append('<textarea style="display: none" name="reject_text">'+reject_text+'</textarea>');
                $form.append('<textarea style="display: none" name="reject_text_to_user">'+r+'</textarea>');
                $form.append('<input type="hidden" name="reject" value="1" />');
                $form.submit();
            } 
            else{
                return;
            }
        });
    });
    
}

function operator_last_confirm_ordr($form)
{
    // Если в этой заявки используется виза то выводим сообщение.
    is_order_have_visa = <?=$item->is_order_have_visa?>;

    $.messager.prompt('Запрос', 'Введите новую сумму WT или оставте пустым для сохранения прежней.', function(r)
    {
        $form.append('<input type="hidden" name="operator_wt_summ" value="'+r+'" />');

        $form.attr('action', $operator_last_confirm_action);
        if(is_order_have_visa == 1) {
            $.messager.confirm('Хотите Отменить/Провести заявку?', '<span style="color:red"> (Это может повлечь за собой перечисление денег на карту)</span>', function(r){
                if (r){
                    $form.submit();
                }
            });
        } else {
            $form.submit();
        }
    });
//    $form.submit(); закоментировал, так как если эта строка выполняется то обработчик вылетает с ошибкой в базе!
}


function operator_cancell_order($form)
{
    $.messager.prompt('Запрос', 'Введите причину.', function(r)
    {
        if (r)
        {
            $form.append('<input type="hidden" name="reject_text" value="'+r+'" />');
            $form.attr('action', $operator_cancell_order);
            $form.submit();
        } 
        else
        {
            return;
        }
    });
//    $form.submit();
}

function operator_cancell_order_with_cancel_original($form)
{
    // Если в этой заявки используется виза то выводим сообщение.
    is_order_have_visa = <?=$item->is_order_have_visa?>;

    $.messager.prompt('Запрос', 'Введите причину.', function(r)
    {
        if (r)
        {
            $form.append('<input type="hidden" name="reject_text" value="'+r+'" />');
            $form.append('<input type="hidden" name="cancel_original" value="1" />');
            $form.attr('action', $operator_cancell_order);

            if(is_order_have_visa == 1) {
                $.messager.confirm('Хотите Отменить/Провести заявку?', '<span style="color:red"> (Это может повлечь за собой перечисление денег на карту)</span>', function(r){
                    if (r){
                        $form.submit();
                    }
                });
            } else {
                $form.submit();
            }
        }
        else
        {
            return;
        }
    });
//    $form.submit();
}
</script>

<!-- -->




<!--<script src="/js/jquery.js"></script>-->
<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="css/cssDTableFont.css"/>
<style>
    .button span {
        height: auto;
    }
    .loading {
        color: #000000;
    }

    .order-by a {
        color: #222222;
    }

    .order-by a:hover, .order-by a:active, .order-by a:focus {
        outline: 0;
        text-decoration: none;
    }

    .order-by .active {
        color: #0099FF;
        text-decoration: none;
    }
    .glyphicon {
        /*display: none;*/
    }
    .active {
        /*display: block;*/
    }
    .order-by {
        display: block;
    }
    #table th {
        text-align: center;
    }
    .row_on_click{
        cursor: pointer;
    }
    .col_id_state {
        text-align: center;
    }
</style>

