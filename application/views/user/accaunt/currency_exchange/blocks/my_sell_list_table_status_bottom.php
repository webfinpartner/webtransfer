<?php 
//vre(Currency_exchange_model::get_order_status($val));
switch (Currency_exchange_model::get_order_status($val)){
    //Контрагент должен подтвердить отправку денег wt_set = 1
    case 1: 
?>
    <div class="table_row" style="text-align: center;">
        <a href="#" class="confirme_select_payment_system table_green_button" onclick="confirme_select_payment_system_show_popup($(this)); return false;" >
            <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
        </a>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#"  onclick="show_form_user_send_message_operator($(this)); return false;"  class="table_green_button"  style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php       
    break;

    // Инициатор должен подтвердить получение денег wt_set = 1
    case 2:

        $select_pay_sys = unserialize($val->sell_payment_data); 
        $select_pay_sys_buy = unserialize($val->buy_payment_data); 

        foreach($select_pay_sys as $sps_val)
        {
            if($sps_val->payment_system_id == $val->sell_system)
            {
                $sps = $sps_val;
                break;
            }
        }

        foreach($select_pay_sys_buy as $sps_buy_val)
        {
            if($sps_buy_val->payment_system_id == $val->payed_system)
            {
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
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        <?php endif; ?>

        <?php if(!$val->buyer_document_image_path): ?>
        <a href="#" class="redB but" onclick="cancel_action($(this)); return false;" data-order-id="<?=$val->id;?>" style=" display: inline-block;">
            <?= _e('currency_exchange/my_sell_list_table/reject')?>
        </a>
        <?php endif; ?>
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>
        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
        <div class="clear"></div>
    </div>
<?php
    break;
    
    // Контрагент должен подтвердить отправку денег wt_set = 1
    // Статус - Контр агент ожидает подтверждения.
    case 3:
?>
    <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
    </a>

    <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
        <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
    </a>
<?php
    break;
    
    // Инициатор должен подтвердить отправку денег wt_set = 1
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 4:
?>
    <div class="table_row" style="text-align: center;">
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2
    // Статус - Ожидание ответа контрагента.
    case 5:
?>
    <div class="table_row" style="text-align: center;">
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту');?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem');?>
        </a>
    </div>
<?php
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2
    case 6:
?>
    <div class="table_row" style="text-align: center; height: auto;">
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#last_user_confirm_block')); return false;">
                <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
            </a>
        </form>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>
        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
        <div class="clear"></div>
    </div>
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 7:
?>
    <div class="table_row" style="text-align: center;">
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php        
    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    case 8:
?>
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
<?php  
    break;
    
    // Контрагент должен подтвердить отправку денег wt_set = 0
    case 9: 
?>
    <div class="table_row" style="text-align: center;">
        <a href="#" class="confirme_select_payment_system table_green_button" onclick="confirme_select_payment_system_show_popup($(this)); return false;" >
            <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
        </a>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#"  onclick="show_form_user_send_message_operator($(this)); return false;"  class="table_green_button"  style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php       
    break;
    
    // Контрагент должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание ответа контрагента.
    case 10:
?>
    <div class="table_row" style="text-align: center;">
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
    
<?php
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 11:
?>
    <div class="table_row" style="text-align: center;">
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    case 12:
?>
    <div class="table_row" style="text-align: center;">
        <div style="margin-bottom: 10px;">
            <?php printf(_e('currency_exchange/my_sell_list_table/you_means'),
//                    Currency_exchange_model::get_ps($val->sell_system)->humen_name, 
//                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->sell_system]));
                    Currency_exchange_model::get_ps($val->payed_system)->humen_name, 
                    Currency_exchange_model::show_payment_system_code(['order' => $val, 'payment_systems_id' => $val->payed_system]));
            ?>
        </div>
    </div>    
    <div class="table_row" style="text-align: center; height: auto;">
        <?php /**/ ?>
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <!--<a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent()); return false;" >-->
            <!--<a href="#" class="table_green_button" onclick="show_confirm_lock($(this).parent(), $('#initiator_confirm_money_block')); return false;" >-->
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#initiator_confirm_money_block')); return false;" >
                <?= _e('currency_exchange/my_sell_list_table/confirm')?>
            </a>
        </form>
        
        <?php /* ?>

        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block_money_send($(this).parent()); return false;" ><?= _e('currency_exchange/my_sell_list_table/confirm_send')?></a>
        </form>
        <?php */ ?>
        
        <? if($val->buyer_document_image_path && $val->buyer_document_image_path != 'wt'): ?>
            <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
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
<?php  
    break;
    
    // Инициатор должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 13:
?>
    <div class="table_row" style="text-align: center;">
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
            <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 14:
?>
    <div class="table_row" style="text-align: center; height: auto;">
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_confirm" value="1" />
            <!--<a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#last_user_confirm_block')); return false;">-->
            <a href="#" class="table_green_button" onclick="show_last_user_confirm_block_money_send($(this).parent()); return false;">
                <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
            </a>
        </form>

        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>
        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
        <div class="clear"></div>
    </div>
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 15:
?>
    <div class="table_row" style="text-align: center;">
        <? // if($val->buyer_document_image_path && $val->buyer_document_image_path != 'wt'): ?>
            <a href="<?=site_url('account/currency_exchange/sell_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" >
                <?= _e('currency_exchange/my_sell_list_table/show_doc')?>
            </a>
        <?php // endif; ?>
        
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
    </div>
<?php
    break;


    case 16:
    ?>
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
    <?php  
    break;
    
    // На рассмотрении у оператора
    case 50:
?>
        <a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent())); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('Написать контрагенту')?>
        </a>

        <a href="#" class="table_green_button" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-image: url('../../images/ui/usualButtons.png'); background-position: 0px -170px; border: 1px solid #9F352B;" data-id="<?=$val->id?>" >
            <?= _e('currency_exchange/my_sell_list_table/there_problem')?>
        </a>
<?php 
    break;
} 
?>