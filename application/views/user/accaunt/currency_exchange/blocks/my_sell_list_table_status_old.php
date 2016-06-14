<?php // vre($val->confirm_type); ?>
<?php if($val->confirm_type == Currency_exchange_model::ORDER_TYPE_BUY_WT && $val->status == Currency_exchange_model::ORDER_STATUS_CONFIRMATION): ?>
<!--1-->
    <?php if($val->buyer_user_id && !$val->seller_confirmed): ?>
        <?php if(!$val->buyer_confirmed): ?>    
            <?php /*if($val->wt_set == 2 &&  
                        ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                            $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                         $val->initiator == 0 && 
                        $val->bank_set != 1 && 
                        strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
                    ):?>
                    <a href="<?=site_url('account/currency_exchange/user_reject_order_before_end_time/'.$val->id)?>" class="redB but" style="width: 150px;" >
                        <?= _e('Разорвать сделку')?>
                    </a>
            <?php elseif($val->wt_set == 2 &&  
                        ($val->buyer_send_money_date == '0000-00-00 00:00:00' &&  
                            $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                         $val->initiator == 0 && 
                        $val->bank_set == 1 && 
                        strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK <= time()
                    ):?>
                    <a href="<?=site_url('account/currency_exchange/user_reject_order_before_end_time/'.$val->id)?>" class="redB but" style="width: 150px;" >
                        <?= _e('Разорвать сделку')?>
                    </a>
            <?php *///else: ?>        
                    <?= _e('currency_exchange/my_sell_list_table_status/wait_responce')?>
            <?php //endif; ?> 
        <?php else: ?>
        <?= _e('currency_exchange/my_sell_list_table_status/wait_confirm_get')?>
        <?php endif; ?>
    <?php elseif($val->buyer_user_id && $val->seller_confirmed): ?>         
        <a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent().parent().next().find('form'));return false;" >
            <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
        </a>
    <?php else: ?>     

            <?php if($val->type != $val->confirm_type && !$val->buyer_user_id  && !$val->seller_confirmed && $val->seller_document_image_path): ?>
        
                <?php if(!$val->buyer_confirmed && !Currency_exchange_model::is_root_curr($val->sell_payment_data_un[$val->sell_system]->machine_name)): ?>
                <a href="#" class="table_green_button" onclick="show_buyer_confirm_receipt_block($(this).parent().parent().next().find('form.form_buyer_confirm_receipt_block')); return false;" style="margin: 5px 0;" >
                    <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
                </a>
                <?php else: ?>
                <a href="#" class="confirme_select_payment_system table_green_button" onclick="confirme_select_payment_system_show_popup($(this)); return false;" >
                    <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer')?> 
                </a>
                <!--<a href="#" class="cancel_action redB but" style="width: 150px;">-->
                <a href="#" class="cancel_action table_green_button table_red_button" style="margin-top: 5px;">
                    <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
                </a>
                <?php endif; ?>
                
            <?php else: ?>
                <a href="#" class="confirme_select_payment_system table_green_button" onclick="confirme_select_payment_system_show_popup($(this)); return false;" >
                    <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer')?>
                </a>
                <!--<a href="#" class="cancel_action redB but" style="width: 150px;" >-->
                <a href="#" class="cancel_action table_green_button table_red_button" style=" margin-top: 5px;" >
                <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
                </a>
            <?php endif; ?>
    <?php endif; ?> 
<?php elseif($val->status == Currency_exchange_model::ORDER_STATUS_CONFIRMATION): ?> 
            <!--2-->
    <?php if($val->buyer_user_id && !$val->seller_confirmed): ?>
        <?php if(!$val->seller_document_image_path): ?>
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent().parent().next().find('form'), $('#last_user_confirm_block')); return false;"  >
                <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer')?>
            </a>
            <!--<a href="#" class="cancel_action redB but" ><?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?></a>-->
            <a href="#" class="cancel_action table_green_button table_red_button" style="margin-top: 5px;" >
                <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
            </a>
        <?php else: ?>    
            <?=_e('Ожидание подтверждения');?> <br/>
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent().parent().next().find('form'), $('#last_seller_confirm_block')); return false;">
                <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
            </a>
            <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" style="margin-top: 5px;" >
                <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
            </a>
        <?php endif; ?>
    <?php elseif(!$val->buyer_user_id && !$val->seller_confirmed && !$val->payed_system && $val->sell_system && !$val->seller_document_image_path): ?>     
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block_money_send($(this).parent().parent().next().find('form')); return false;" >
                <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_send')?>
            </a>
    <?php elseif($val->buyer_user_id && $val->seller_confirmed): ?>     
        <?= _e('currency_exchange/my_sell_list_table_status/wait_confirm_get')?>
    <?php else: ?>
        <?php /*if($val->wt_set == 1 &&  
                ($val->buyer_send_money_date == '0000-00-00 00:00:00' && $val->seller_send_money_date == '0000-00-00 00:00:00') &&
                 $val->initiator == 1 && 
                 strtotime($val->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
                ):?>
            <a href="<?=site_url('account/currency_exchange/user_reject_order_before_end_time/'.$val->id)?>" class="redB but" style="width: 150px;" >
                <?= _e('Разорвать сделку')?>
            </a>
        <?php *///else: ?>    
            <?= _e('currency_exchange/my_sell_list_table_status/wait_responce')?>
        <?php //endif; ?>
    <?php endif; ?> 
<?php elseif ($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING ): ?>
        <?= _e('currency_exchange/my_sell_list_table_status/status_processing')?>
<?php elseif ($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING_SB ): ?>
        <?= _e('currency_exchange/my_sell_list_table_status/status_processing_sb')?>
<?php endif;?>
