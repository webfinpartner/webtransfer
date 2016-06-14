<table class="table_results add_more_orders table_search_orders" cellspacing="0" width="100%">
        <thead class="table_header">
      <td class="table_cell"><?=_e('ID');?></td>    
        <?php /* ?>
            <td class="table_cell">
                <?php if(isset($table_search_set_title_contragent) && $table_search_set_title_contragent === true): ?>
                <?=_e('currency_exchange/table_search/contragent')?>
                <?php else: ?>
                <?=_e('currency_exchange/table_search/user')?>
                <?php endif; ?>
            </td>    
        <?php */?>
            <?php /* if(isset($table_search_set_title_contragent) && $table_search_set_title_contragent === true): ?>
            <td class="table_cell">
                <?=_e('currency_exchange/table_search/contragent')?>
            </td>  
            <?php endif; */ ?>
            <!--<td class="table_cell"><?//=_e('currency_exchange/table_search/sell')?></td>-->     
            <td class="table_cell"><?=isset($table_header_sell)?sprintf(_e($table_header_sell), $curent_user_data->name_sername):sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername)?></td>     
            <td class="table_cell"><?=_e('currency_exchange/table_search/date')?></td>    
            <!--<td class="table_cell"><?//=_e('currency_exchange/table_search/buy')?></td>-->    
            <td class="table_cell"><?=isset($table_header_buy)?sprintf(_e($table_header_buy), $curent_user_data->name_sername):sprintf(_e('Вы получаете'), $curent_user_data->name_sername)?></td>                
            <td class="table_cell"><?=_e('currency_exchange/table_search/status')?></td>                  
        </thead>    
        <tbody class="table_body"> 
          <?php foreach($res_search as $val): ?>
          <?php
                $payment_systems = $val->payment_systems;
                $first_ps = array_shift($payment_systems);
                $curency_id = $payment_systems_id_arr[$first_ps->payment_system]->currency_id;
            ?>  
            <tr class="table_row_header htitle">
                <td class="table_cell">
                    
                    <?php //echo isset($val->original_order_id)? "$val->id /<span style='color: #777;' title='"._e('Номер оригинальной заявки')."'>{$val->original_order_id}</span>":$val->id; ?> 
                    <?php echo isset($val->original_order_id)? "$val->id/{$val->original_order_id}":$val->id; ?> 
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/not_read_message_chat.php', array('order' => $val)); ?>
                    <input type="hidden" name="id" value="<?php echo $val->id; ?>">                    
                </td>  
                <?php /* if(isset($table_search_set_title_contragent) && $table_search_set_title_contragent === true): ?>
                <td class="table_cell" style="text-align: left; padding-left: 14px;">
                    <?php if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS ){ ?>                    
                        <?php if(isset($table_search_set_value_contragent) && $table_search_set_value_contragent === true){
                            echo hide_data( $val->second_order->seller_user_id);
                        }else{
                            echo hide_data( $val->seller_user_id );
                        }?>
                    <?php }else{?>
                        -
                    <?php }?>
                </td>   
                <?php endif; */ ?>
                <td class="table_cell">
                    <?php if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS || in_array($val->status, [Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR, Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR])){ ?>
                        <?php if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $user_id ): ?>
                            <?php $show_payment_system = isset($val->sell_system)?$val->sell_system:FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system)); ?>
                        <?php else: ?>
                            <?php $show_payment_system = isset($val->payed_system)?$val->payed_system:FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system)); ?>
                        <?php endif; ?>
                    <?php }else{  ?>
                            <?php $show_payment_system = FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system)); ?>
                    <?php }?>
                    
                </td>    
                <!--<td class="table_cell"><?php // echo $val->buyer_amount_down; ?> <?//=_e('currency_id_'.$curency_id)?></td>-->    
                <td class="table_cell"><?php echo date('d.m.y',strtotime($val->seller_set_up_date)); ?></td>    
                <td class="table_cell" style="padding-top: 5px;">
                    <?php if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS || in_array($val->status, [Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR, Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR])){ ?>
                        <?php if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $user_id): ?>
                            <?php $show_payment_system = isset($val->payed_system)?$val->payed_system:FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system)); ?>
                        <?php else: ?>
                            <?php $show_payment_system = isset($val->sell_system)?$val->sell_system:FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system)); ?>
                        <?php endif; ?>
                    <?php }else{  ?>
                            <?php $show_payment_system = FALSE; ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system)); ?>
                    <?php }?>
                </td>                    
                <td class="table_cell" style="width: 100px;">
                    <? //=$user_id ?>
                    <? //=$val->seller_user_id ?>
                    <?php if(isset($button) && $button == 'confirme'): ?>
                            <a href="#" class="confirme_action"><?=_e('currency_exchange/table_search/confirm')?></a>
                    <?php elseif(isset($button) && $button === FALSE): ?>    
                        <?php if ($user_id ==  $val->seller_user_id  && $val->buyer_confirmed != 1 && !($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS)): ?>
                            <?php if($val->status == Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED): ?>
                                <?=_e('currency_exchange/table_search/reject')?>
                            <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_CANCELED): ?>
                                <?=_e('currency_exchange/table_search/canceled')?>
                            <?php /* elseif($val->status > Currency_exchange_model::ORDER_STATUS_CANCELED && $val->status <= Currency_exchange_model::ORDER_STATUS_REMOVED &&  $val->status != Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR): ?>
                                <?//=_e('currency_exchange/table_search/canceled')?>
                                <?=_e('Отменена')?>
                            <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR): ?>
                                <?=_e('Отменена')?>
                            <?php */ ?>
                            <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING): ?>
                                <?=_e('currency_exchange/table_search/processing')?>
                                <form action="" method="post">
                                    <input type="hidden" name="processing_cancell" value="1"/>
                                    <input type="hidden" name="delete_id" value="<?php echo $val->id; ?>"/>
                                    <a href="#" class="table_green_button table_red_button" onclick="$(this).parent().submit(); return false;"><?=_e('currency_exchange/table_search/delete')?></a>
                                </form>
                            <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING_SB): ?>
                            <?=_e('currency_exchange/table_search/processing_sb')?>
                            <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_SET_OUT): ?>
                                <?=_e('currency_exchange/table_search/set_up')?>
                                <form action="" method="post">
                                    <input type="hidden" name="delete" value="1"/>
                                    <input type="hidden" name="delete_id" value="<?php echo $val->id; ?>"/>
                                    <a href="#" class="table_green_button table_red_button" onclick="$(this).parent().submit(); return false;"><?=_e('currency_exchange/table_search/delete')?></a>
                                </form>
                            <?php else: ?>
                                <?php $this->load->view('user/accaunt/currency_exchange/blocks/table_search_status.php', array('status' => $val->status)); ?>
                            <?php endif; ?>
                        <?php elseif($val->status >= Currency_exchange_model::ORDER_STATUS_CANCELED && $val->status <= Currency_exchange_model::ORDER_STATUS_REMOVED): ?>                                        
                             <?php $this->load->view('user/accaunt/currency_exchange/blocks/table_search_status.php', array('status' => $val->status)); ?>                        
                        <?php endif; ?> 
                                
                    <?php else: ?>                            
                        <?php if ($user_id ==  $val->seller_user_id): ?>
                            <?=_e('currency_exchange/table_search/you_order')?>
                        <?php else: ?>
                            <?php 
                                $temp_sell_payment_data = $val->sell_payment_data_un;
                                $first_sell_payment_data = array_shift($temp_sell_payment_data);
                            ?>
                            <?php if(Currency_exchange_model::is_root_curr($first_sell_payment_data->machine_name)): ?>
                            <a href="#" class="table_green_button" onclick="exchange_action_wt($(this)); return false;"><?=_e('currency_exchange/table_search/change')?></a>
                            <?php else: ?>
                            <a href="#" class="exchange_action" onclick="return false;"><?=_e('currency_exchange/table_search/change')?></a>
                            <?php  endif; ?>
                            
                        <?php endif; ?>    
                    <?php endif; ?> 
                        
                    <?php if($val->buyer_confirmed == 1 && $val->seller_confirmed == 1): ?>   
                        <?=_e('currency_exchange/table_search/end')?>
                    <?php endif; ?>    
                        
                    <?php if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS): ?>    
                        <?=_e('currency_exchange/table_search/problem')?>
                    <?php endif; ?>    
                        
                </td>    
            </tr>
            <tr class="table_row hbody" style="display: none;">
                <td class="table_cell" colspan="7">
                    <?php //заменить на вьюху table_search_details! ?>
                    <div class="table_row">
                        <?php if(isset($table_search_set_title_contragent) && $table_search_set_title_contragent === true): ?>
                        <div class="table_cell col2"><?= _e('currency_exchange/my_sell_list_table/contragent')?>:</div>
                         <div class="table_cell col2">
                            <a href="<?=Currency_exchange_model::get_social_link($val->second_order->seller_user_id) ;?>" target="_blank">
                                <?php echo Currency_exchange_model::get_user_data($val->second_order->seller_user_id, ['sername', 'name']); ?>
                            </a>
                        </div>

                        <div class="clear"></div>
                        <?php endif; ?>
                        <div class="table_cell col2" ><?=_e('currency_exchange/table_search/fee')?></div>
                        <div class="table_cell col2" style="padding-left: -34px;">
                            <?php 
                                $buy_payment_data = unserialize($val->buy_payment_data);
                                $sell_payment_data = unserialize($val->sell_payment_data);

                                if( $buy_payment_data[0]->payment_system_id == 115 || $sell_payment_data[0]->payment_system_id == 115 ):?>
                            <?=$val->get_fee?> USD VISA
                            <?php elseif(isset($val->get_fee)): ?>
                                <?=$val->get_fee?>
                                <?//=_e('currency_id_'.$curency_id)?>
                                
                                <?=Currency_exchange_model::show_payment_system_code(['currency_id' => $val->get_fee_currency_id ]);?>
                            <?php else: ?>
                                <?=$val->get_percent?> %
                            <?php endif; ?>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/for_search_table_dop.php', array('order' => $val)); ?>
                        
                        
                        <?php // if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS): ?>
                        <?php if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM ): ?>
                        <div class="clear"></div>
                            <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
                                    <?= _e('Написать контрагенту')?>
                            </a>
                                
                            <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" ><?=_e('currency_exchange/table_search/problem')?></a>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <div class="table_row">
                    </div>                    
                    <?php // if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS): ?>
                    <?php if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM): ?>
                        <!-- начало коментариев -->   
                        <?php // $this->load->view('user/accaunt/currency_exchange/blocks/user_comment_chat.php', array('order' => $val, 'responce' => true)); ?>
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/user_comment_chat.php', array('order' => $val)); ?>
                            
                    <?php endif; ?>
                </td>
            </tr>
            
            
          <?php endforeach; ?>  
            <?php //if ( $current_show_records < $total_count ) { ?>
            <!--tr><td colspan="6"><a href="#search" onclick="load_search_table_page($(this), <?=$res_page?>, <?=$all_orders?>); return false;">Еще</a></td></tr-->
            <?php //} ?>
        </tbody>
    </table>    
   
