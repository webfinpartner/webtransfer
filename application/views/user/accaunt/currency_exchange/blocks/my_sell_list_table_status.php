    <?php // vre(Currency_exchange_model::get_order_status($val)); ?>
<?php 

$details = unserialize( $val->order_details_arhiv );
$orig_order = $details[0]->orig_order_data;
// $send_currency_machine_name = unserialize($orig_order)->payment_systems[$val->id + 1]->machine_name;
$ps = unserialize($orig_order)->payment_systems; // В данном масиве два элемента. первое направление платежа, и второе.
$buy_currency_machine_name  = reset($ps)->machine_name;
$send_currency_machine_name = end($ps)->machine_name;
switch (Currency_exchange_model::get_order_status($val))
{
    //Контрагент должен подтвердить отправку денег wt_set = 1
    case 1: 
?>  
    <?
    $onclick_url = "confirme_select_payment_system_show_popup($(this));";//default url
    $text = _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer');
    if($send_currency_machine_name == 'wt_visa_usd') {
        $onclick_url = "confirme_select_payment_visa_card($(this).parent().parent());";
        $text = _e('Перевести средства');
    }
    ?>
    <a href="#" class="confirme_select_payment_system table_green_button" onclick="<?=$onclick_url?>; return false;" >
        <?=$text ?> 
    </a>
    <a href="#" class="cancel_action table_green_button table_red_button" style="margin-top: 5px;">
        <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
    </a>
<?php       
    break;

    //Инициатор должен подтвердить получение денег wt_set = 1
    case 2:
    ?>
        <?=_e('Ожидание подтверждения');?> <br/>
        <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent().parent().next().find('form'), $('#last_seller_confirm_block')); return false;" style=" width: 156px;"><?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
        </a><a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" style="margin-top: 5px;  width: 156px;" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
    <?php 
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 1
    // Статус - Контр агент ожидает подтверждения.
    case 3:
        echo _e('currency_exchange/my_sell_list_table_status/wait_responce');
    break; 

    // Инициатор должен подтвердить отправку денег wt_set = 1
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 4:
        echo _e('currency_exchange/my_sell_list_table_status/wait_confirm_get');
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2
    // Статус - Ожидание ответа контрагента. 
    case 5:
        echo _e('currency_exchange/my_sell_list_table_status/wait_responce');
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 2   
    case 6:
        $onclick_url = "show_last_user_confirm_block($(this).parent().parent().next().find('form'), $('#last_user_confirm_block'));";
        $text = _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer');        
        if($buy_currency_machine_name == 'wt_visa_usd') {
            // $onclick_url = "confirme_select_payment_visa_card($(this).parent().parent());";
            $text = _e('Перевести средства');
        
        }
?>
    <a href="#" class="table_green_button" onclick="<?=$onclick_url?>; return false;"  >
        <?= $text ?>
    </a>
    <a href="#" class="cancel_action table_green_button table_red_button" style="margin-top: 5px;" >
        <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
    </a>
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 7:

    echo _e('currency_exchange/my_sell_list_table_status/wait_confirm_get');

    break;

    // Контрагент должен подтвердить получение денег wt_set = 2
    case 8:
?>
    <a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent().parent().next().find('form'));return false;" >
        <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
    </a><br/>
    <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->original_order_id)?>" target="_blank" class="table_green_button" style="margin-top: 5px;  width: 156px;" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
        
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    case 9:
?>
    
    <?
    $onclick_url = "confirme_select_payment_system_show_popup($(this));"; //default url
    $text = _e('currency_exchange/my_sell_list_table_status/but_confirm_transfer');        
    if($send_currency_machine_name == 'wt_visa_usd') {
        $onclick_url = "confirme_select_payment_visa_card($(this).parent().parent());";
        $text = _e('Перевести средства');
    }
    ?>
    <a href="#" class="confirme_select_payment_system table_green_button" onclick="<?=$onclick_url?>; return false;" >
        <?= $text ?> 
    </a>        

    
    <a href="#" class="cancel_action table_green_button table_red_button" style="margin-top: 5px;">
        <?= _e('currency_exchange/my_sell_list_table_status/but_cancel')?>
    </a>
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание ответа контрагента.
    case 10:
        echo _e('currency_exchange/my_sell_list_table_status/wait_responce');
    break;

    // Инициатор должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 11:
        echo _e('currency_exchange/my_sell_list_table_status/wait_confirm_get');
    break;

    case 12:
?>
    <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
        <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
        <input type="hidden" name="id" value="<?=$val->id?>" />
        <input type="hidden" name="last_user_confirm" value="1" />
        <!--<a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent()); return false;" >-->
        <!--<a href="#" class="table_green_button" onclick="show_confirm_lock($(this).parent(), $('#initiator_confirm_money_block')); return false;" >-->
        <a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#initiator_confirm_money_block')); return false;" >
            <?= _e('currency_exchange/my_sell_list_table/confirm')?>
        </a><br/>
        <a href="<?=site_url('account/currency_exchange/buy_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" style="margin-top: 5px;  width: 156px;" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?></a>
    </form>
<?php
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    // Статус - Ожидаем отправку средств контрагентом.
    case 13:
        echo _e('Ожидаем отправку средств контрагентом');
    break;

    // Контрагент должен подтвердить отправку денег wt_set = 0
    case 14:
?>
    <form method="post" action="<?=site_url('account/currency_exchange/ajax/last_user_confirm')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
        <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
        <input type="hidden" name="id" value="<?=$val->id?>" />
        <input type="hidden" name="last_user_confirm" value="1" />
        <!--<a href="#" class="table_green_button" onclick="show_last_user_confirm_block($(this).parent(), $('#last_user_confirm_block')); return false;">-->
        <a href="#" class="table_green_button" onclick="show_last_user_confirm_block_money_send($(this).parent()); return false;">
            <?= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
        </a>
    </form>





    <?

    reset($ps);
    $seller_ps = end($ps);
    if($seller_ps->machine_name == 'wt_visa_usd') {
        ?>
        <form method="post" action="<?=site_url('account/currency_exchange/ajax/ajax_last_user_confirm1')?>" accesskey="" style="margin: 0px; padding: 0px; display: inline;">
            <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
            <input type="hidden" name="id" value="<?=$val->id?>" />
            <input type="hidden" name="last_user_wont_delete" value="1" />
            
            <a href="#" class="cancel_action table_green_button table_red_button" onclick="add_input_to_confirm_before_delete_order();" style="margin-top: 5px;">
                <?= _e('Отказаться')?>
            </a>
            <!-- <a href="#" class=""> -->
                <!--  -->
                <? //= _e('currency_exchange/my_sell_list_table/confirm_trans')?>
            </a>
        </form>

        <?
    }
    ?>
<?php
    break;

    // Контрагент должен подтвердить получение денег wt_set = 0
    // Статус - Ожидание подтверждения получения средств контрагентом
    case 15:

    echo _e('currency_exchange/my_sell_list_table_status/wait_confirm_get');

    break;

    // Контрагент должен подтвердить получения денег wt_set = 0
    case 16:
?>
    <a href="#" class="table_green_button" onclick="show_real_last_user_confirm_block($(this).parent().parent().next().find('form'));return false;" >
        <?= _e('currency_exchange/my_sell_list_table_status/but_confirm_get')?>
    </a><br/>
    <a href="<?=site_url('account/currency_exchange/sell_application_doc/'.$val->id)?>" target="_blank" class="table_green_button" style="margin-top: 5px;  width: 156px;" ><?= _e('currency_exchange/my_sell_list_table/show_doc')?>
        </a>
<?php
    break;
    case 17:
?>
    <form id="counterparty_confirm_money_block" method="POST" >
        <input type="hidden" name="counterparty_confirm_money_block" value="1"/>
        <input type="hidden" name="confirm_id" value="<?=$val->id ?>"/>
        
        <a href="#" class="table_green_button" onclick="document.getElementById('counterparty_confirm_money_block').submit(); return false;" >
            <?= _e('Получить средства')?>
        </a>
    </form>
    
    
<?php
    break;

    case 18:
?>    
        <?= _e('Ожидание получения средств контрагентом')?>  
    
<?php
    break;
    
    // На рассмотрении у оператора
    case 50:
        
        if ($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING_SB )
        {
            echo  _e('currency_exchange/my_sell_list_table_status/status_processing_sb');
        }
        else
        {
            echo _e('currency_exchange/my_sell_list_table_status/status_processing');
        }
        
    break;
}
?>
