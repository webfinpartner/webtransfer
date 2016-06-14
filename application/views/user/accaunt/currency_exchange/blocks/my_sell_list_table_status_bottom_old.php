<?php if($val->buyer_user_id && $val->type == $val->confirm_type && !$val->seller_confirmed ): ?>
    <?php 
        $select_pay_sys = unserialize($val->sell_payment_data); 
        $select_pay_sys_buy = unserialize($val->buy_payment_data); 

        foreach($select_pay_sys as $sps_val)
        {
            if($sps_val->payment_system_id == $val->sell_system){
                $sps = $sps_val;
                break;
            }
        }

        foreach($select_pay_sys_buy as $sps_buy_val)
        {
            if($sps_buy_val->payment_system_id == $val->payed_system){
                $sps_buy = $sps_buy_val;
                break;
            }
        }
    ?>
    <?if(!Currency_exchange_model::is_root_curr($sps_buy->payment_system_id)): ?>
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/you_means'),
                    Currency_exchange_model::get_ps($sps_buy->payment_system_id)->humen_name,
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->payed_system]))
                ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if(!$val->seller_document_image_path): ?>
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/contr_means'),
                    Currency_exchange_model::get_ps($sps->payment_system_id)->humen_name, 
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->sell_system]), 
                     Currency_exchange_model::get_ps_data_for_table_short($payment_systems_id_arr[$sps->payment_system_id], $sps->payment_data, ['order' => $val, 'curent_user'=> $user_id]) )
                    ?>

        </div>
    </div>
    <?php endif; ?>

    <div class="table_row" style="text-align: center; height: auto;">
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <?php if(!$val->seller_document_image_path): ?>
                <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#last_user_confirm_block')); return false;"><?= _e('currency_exchange/my_sell_list_table/confirm_trans')?></a>
            <?php else: ?>    
                <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#last_seller_confirm_block')); return false;"><?= _e('currency_exchange/my_sell_list_table/confirm_receipt')?></a>
            <?php endif; ?>

        </form>

        <? if($val->buyer_document_image_path != 'wt'): ?>
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?></a>
        <?php endif; ?>

        <?php if(!$val->buyer_document_image_path): ?>
        <a href="#" class="redB but" onclick="cancel_action($(this)); return false;" data-order-id="<?=$val->id;?>" style=" display: inline-block;"><?= _e('currency_exchange/my_sell_list_table/reject')?></a>
        <?php endif; ?>
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>
        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
        <div class="clear"></div>
    </div>
<?php elseif(!$val->buyer_user_id && !$val->seller_confirmed && !$val->payed_system && $val->sell_system && !$val->seller_document_image_path): ?>     
<!--%%%%%%-->
    <?php 
        $select_pay_sys = unserialize($val->sell_payment_data); 
        $select_pay_sys_buy = unserialize($val->buy_payment_data); 

        foreach($select_pay_sys as $sps_val)
        {
            if($sps_val->payment_system_id == $val->sell_system){
                $sps = $sps_val;
                break;
            }
        }
    ?>
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/contr_means'),
                    Currency_exchange_model::get_ps($sps->payment_system_id)->humen_name, 
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->sell_system])
                    , Currency_exchange_model::get_ps_data_for_table_short($payment_systems_id_arr[$sps->payment_system_id], $sps->payment_data, ['order' => $val, 'curent_user'=> $user_id]))
            ?>
        </div>
    </div>

    <div class="table_row" style="text-align: center; height: auto;">
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block_money_send($(this).parent()); return false;" ><?= _e('currency_exchange/my_sell_list_table/confirm_send')?></a>
        </form>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" ><?= _e('currency_exchange/my_sell_list_table/there_problem')?></a>
    </div>
<!--%%%%%%-->
<?php elseif($val->type != $val->confirm_type && !$val->buyer_user_id  && !$val->seller_confirmed && $val->seller_document_image_path): ?>    

<!--####################-->
    <?php if(!Currency_exchange_model::is_root_curr($val->sell_payment_data_un[$val->sell_system]->machine_name)): ?>
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/you_means'),
                    Currency_exchange_model::get_ps($sps->payment_system_id)->humen_name, 
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->sell_system]))
            ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="table_row" style="text-align: center;">
        <a href="#" class="confirme_select_payment_system table_green_button" onclick="confirme_select_payment_system_show_popup($(this)); return false;" >
            <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
        </a>
        <?php if(Currency_exchange_model::is_root_curr($sps->payment_system_id)): ?>
        <a href="#" class="redB but" onclick="cancel_action($(this)); return false;" data-order-id="<?=$val->id;?>" style="width: 136px; display: inline-block;"><?= _e('currency_exchange/my_sell_list_table/reject')?></a>
        <?php endif;?>

        <?php if(!$val->buyer_confirmed && !Currency_exchange_model::is_root_curr($val->sell_payment_data_un[$val->sell_system]->machine_name)): ?>
        <form class="form_buyer_confirm_receipt_block" method="post" action="<?=site_url('account/currency_exchange/ajax/buyer_confirm_receipt')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <a href="#" class="table_green_button" onclick="show_buyer_confirm_receipt_block($(this).parent()); return false;" ><?= _e('currency_exchange/my_sell_list_table/confirm_receipt')?></a>
        </form>
        <?php endif; ?>

        <? if($val->seller_document_image_path && $val->seller_document_image_path != 'wt'): ?>
        <a href="<?=site_url('account/currency_exchange/sell_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?></a>
        <?php endif; ?>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>
        <a href="#"  onclick="show_form_user_send_message_operator($(this)); return false;"  class="table_green_button"  style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
                <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>

<!--####################-->

<?php elseif($val->type != $val->confirm_type && $val->buyer_user_id  && $val->seller_confirmed): ?>    
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/you_means'),
                    Currency_exchange_model::get_ps($val->sell_system)->humen_name, 
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->sell_system]));
            ?>
        </div>
    </div>    
    <div class="table_row" style="text-align: center; height: auto;">
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent()); return false;" >
                <?= _e('currency_exchange/my_sell_list_table/confirm')?>
            </a>
        </form>

        <? if($val->seller_document_image_path && $val->seller_document_image_path != 'wt'): ?>
            <a href="<?=site_url('account/currency_exchange/sell_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
                <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
            </a>
        <?php endif; ?>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;"  style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php elseif($val->buyer_document_image_path): ?>  
    <div class="table_row" style="text-align: center;">
        <? if($val->buyer_document_image_path != 'wt' && $val->buyer_document_image_path): ?>
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        <?php elseif(isset($val->seler_document_image_path) && $val->seler_document_image_path != 'wt' && $val->seller_document_image_path): ?>
        <a href="<?=site_url('account/currency_exchange/sell_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        <?php endif; ?>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php else: ?> 
    <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
    </a>

    <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
        <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
    </a>
<?php endif; ?>