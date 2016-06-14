<table class="table_results table_sell_orders">
        <thead class="table_header">
            <tr>
                <td class="table_cell"><?=_e('ID');?></td>    
                <!--<td class="table_cell"><?//= _e('currency_exchange/my_sell_list_table/contragent')?></td>-->    
                <td class="table_cell"><?=sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername)?></td>           
                <td class="table_cell"><?= _e('currency_exchange/my_sell_list_table/date')?></td>    
                <td class="table_cell"><?=sprintf(_e('Вы получаете'), $curent_user_data->name_sername)?></td>    
                <td class="table_cell"><?= _e('currency_exchange/my_sell_list_table/status')?></td>   
            </tr>
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
                    <?php echo $val->original_order_id; ?> <?php $this->load->view('user/accaunt/currency_exchange/blocks/not_read_message_chat.php', array('order' => $val, 'my_sell_list' => true)); ?>
                    <input type="hidden" name="id" value="<?php echo $val->id; ?>">
                    <input type="hidden" name="or_id" value="<?php echo $val->original_order_id; ?>">
                </td>  
                <!--<td class="table_cell"><?php //echo hide_data($val->second_order->seller_user_id); ?></td>-->    
                <td class="table_cell">                                        
                <?php if($val->type == $val->confirm_type): ?>
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT)); ?>
                <?php else: ?>
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT)); ?>
                <?php endif; ?>
                </td>    
                <td class="table_cell"><?php echo date('d.m.y', strtotime($val->seller_set_up_date)); ?></td>
                <td class="table_cell" style="padding-top: 5px;">
                    
                <?php if($val->type == $val->confirm_type): ?>                    
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT)); ?>
                <?php else: ?>                    
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT)); ?>
                <?php endif; ?>
                </td>                   
                <?php if($val->old_orders == 1): ?>
                <td class="table_cell td_status_action">
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_old.php', array('val' => $val)); ?>
                </td>
                <?php else: ?>
                <td class="table_cell td_status_action" style="width: 100px">
                    <?php // $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_old.php', array('val' => $val)); ?>
                     <!--<br/>==========<br/>--> 
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status.php', array('val' => $val)); ?>
                </td>    
                <?php endif; ?>
            </tr>
            <tr class="table_row hbody" style="display: none;">
                <td class="table_cell" colspan="7">
                    <div class="table_row">
                        
                        
                        <div class="table_cell col2"><?= _e('currency_exchange/my_sell_list_table/contragent')?>:</div>
                        <div class="table_cell col2">
                            <a href="<?=Currency_exchange_model::get_social_link($val->second_order->seller_user_id) ;?>" target="_blank">
                                <?php echo Currency_exchange_model::get_user_data($val->second_order->seller_user_id, ['sername', 'name']); ?>
                            </a>
                        </div>
                        <div class="clear"></div>
                        <div class="table_cell col2"><?= _e('currency_exchange/my_sell_list_table/fee')?></div>
                        <div class="table_cell col2">
                            <?php 
                                $buy_payment_data = unserialize($val->buy_payment_data);
                                $sell_payment_data = unserialize($val->sell_payment_data);

                                if( $buy_payment_data[0]->payment_system_id == 115 || $sell_payment_data[0]->payment_system_id == 115 ):?>
                            <?=$val->get_fee?> USD VISA
                            <?php elseif(isset($val->get_fee)): ?>
                                <?=$val->get_fee?>
                                <?//=_e('currency_id_'.$val->get_fee_currency_id)?>
                                <?=Currency_exchange_model::show_payment_system_code(['currency_id' => $val->get_fee_currency_id]);?>
                            <?php else: ?>
                                <?=$val->get_percent?> %
                            <?php endif; ?>
                        </div>    
                        <div class="clear"></div>
                        <?php if(Currency_exchange_model::ORDER_STATUS_CONFIRMATION == $val->status): ?>
                            <?php if($val->wt_set == 2 &&  
                                        ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                                            $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                                         $val->initiator == 1 && 
                                        $val->bank_set != 1 && 
                                        strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
                                    ):?>

                             <div class="table_row" style="text-align: center; color: red; font-weight: bold;">
                                <?//= _e('Время отведённое на перевод средств истекло.')?>
                             </div>
                            <?php endif; ?>
                            <div class="clear"></div>

                            <?php if($val->wt_set == 2 &&  
                                        ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                                            $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                                         $val->initiator == 1 && 
                                        $val->bank_set == 1 && 
                                        strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK <= time()
                                    ):?>

                             <div class="table_row" style="text-align: center; color: red; font-weight: bold;">
                                <?//= _e('Время отведённое на перевод средств истекло.,')?>
                             </div>
                            <?php endif; ?>
                            <div class="clear"></div>

                            <?php if($val->wt_set == 1 &&  
                                        ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                                            $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                                     $val->initiator == 0 && strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
                                    ):?>

                             <div class="table_row" style="text-align: center; color: red; font-weight: bold;">
                                <?//= _e('Время отведённое на перевод средств истекло..')?>
                             </div>
                            <?php endif; ?>


                            <?php
                           //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 2
                            if($val->wt_set == 2 &&  
                                    ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                                     $val->seller_send_money_date != '0000-00-00 00:00:00')
                            )
                            {
                               //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                                if($val->initiator == 0 && $val->bank_set != 1 &&
                                        strtotime($val->seller_send_money_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time()) 
                                {
                                    echo '<div class="table_row" style="text-align: center; color: red; font-weight: bold;">'
                                    ./*_e('Время отведённое на перевод средств истекло...')*/''
                                    .'</div>';
                                }

                                if($val->initiator == 0 && $val->bank_set == 1 &&
                                        strtotime($val->seller_send_money_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK <= time()) 
                                {
                                    echo '<div class="table_row" style="text-align: center; color: red; font-weight: bold;">'
                                    ./*_e('Время отведённое на перевод средств истекло...,')*/''
                                    .'</div>';
                                }
                            }

                             //покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                            if($val->wt_set == 1 &&  
                                    ($val->buyer_send_money_date != '0000-00-00 00:00:00' &&  
                                     $val->seller_send_money_date == '0000-00-00 00:00:00')
                            )
                            {
                                if($val->initiator == 1 && $val->bank_set != 2 &&
                                        strtotime($val->buyer_send_money_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time()) 
                                {
                                    echo '<div class="table_row" style="text-align: center; color: red; font-weight: bold;">'
                                    ./*_e('Время отведённое на перевод средств истекло....')*/''
                                    .'</div>';
                                }

                                if($val->initiator == 1 && $val->bank_set == 2 &&
                                        strtotime($val->buyer_send_money_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK <= time()) 
                                {
                                    echo '<div class="table_row" style="text-align: center; color: red; font-weight: bold;">'
                                    ./*_e('Время отведённое на перевод средств истекло....,')*/''
                                    .'</div>';
                                }
                            } 

                            ?>
                        
                        <?php endif; ?>
                         
                        <div class="clear"></div>
                    <?php if($val->status == Currency_exchange_model::ORDER_STATUS_CONFIRMATION): ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/timer_confirm.php', array('order' => $val)); ?>

                            <div class="clear"></div>

                        </div>
                        <div class="clear"></div>
                        
                        <div class="clear"></div>
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/for_my_sell_list_table_dop.php', array('order' => $val)); ?>
                        <div class="clear"></div>
<!--############################-->
                            <?php if($val->old_orders == 1): ?>
                                <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom_old.php'); ?>
                            <?php else: ?>
                                <?php // $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom_old.php'); ?>
                                    <!--%%%%%%%%%%-->
                                <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom.php'); ?>
                            <?php endif; ?>
<!--############################-->
                            <div class="clear"></div>
                        <!-- начало коментариев -->   
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/user_comment_chat.php', array('order' => $val)); ?>
                        <!-- конец коментариев -->    
                    <?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING || $val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING_SB): ?>
                        <div class="clear"></div>
                            <?php  $this->load->view('user/accaunt/currency_exchange/blocks/for_my_sell_list_table_dop.php', array('order' => $val)); ?>
                        <div class="clear"></div>
<!--############################-->
                        <?php if($val->old_orders == 1): ?>
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom_old.php'); ?>
                        <?php else: ?>
                            <?php // $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom_old.php'); ?>
                                <!--%%%%%%%%%%-->
                            <?php $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table_status_bottom.php'); ?>
                        <?php endif; ?>
<!--############################-->
                        <div class="clear"></div>
                        <!-- начало коментариев -->   
                        <?php $this->load->view('user/accaunt/currency_exchange/blocks/user_comment_chat.php', array('order' => $val)); ?>
                    <?php endif; ?>
                    <div class="table_cell col2"></div>
                    <div class="clear"></div>
                </td>
                
            </tr>
            
            <?php unset($sps);?>
          <?php endforeach; ?>  
            
        </tbody>
    </table>    
