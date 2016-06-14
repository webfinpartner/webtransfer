<?php if($payment_sys_user_data['selector'] == 'requizites'): ?>
    <?= _e('currency_exchange/timer_confirm/ackount')?> <?=$payment_sys_user_data['rs']?> 
    <br/>
    <div class="but greenbut but_read_more" style="cursor: pointer;" onclick="show_dop_data_to_paymen_sys_in_table($(this),$(this).next('div.hide_data_to_bank'),$(this).parent().parent());">
        <span>►</span>
        <?=_e('подробнее')?>
    </div>
    <div class="hide_data_to_bank" style="display:none;">
        <span style="display: inline-block; padding-right: 5px"><?= _e('Получатель')?>:</span> <?= @$payment_sys_user_data['recipient'] ?><br/>
        <span style="display: inline-block; padding-right: 5px"><?= _e('Расчетный счет')?>:</span><?= @$payment_sys_user_data['rs'] ?><br/>
        <span style="display: inline-block; padding-right: 5px"><?= _e('БИК')?>:</span><?= @$payment_sys_user_data['bik'] ?><br/>
        <span style="display: inline-block; padding-right: 5px"><?= _e('Наименование Банка')?>:</span><?= @$payment_sys_user_data['name'] ?><br/>
        <span style="display: inline-block; padding-right: 5px"><?= _e('Кор. счет')?>:</span><?= @$payment_sys_user_data['ks'] ?><br/>
        <span style="display: inline-block; padding-right: 5px"><?= _e('Назначение')?>:</span> <?= @$payment_sys_user_data['destination'] ?>
    </div>
<?php elseif($payment_sys_user_data['selector'] == 'requizites_univesal'): ?>
    <div class="but greenbut but_read_more" style="cursor: pointer;" onclick="show_dop_data_to_paymen_sys_in_table($(this),$(this).next('div.hide_data_to_bank'),$(this).parent().parent());">
    <span>►</span>
    <?=_e('подробнее')?>
</div>
<div class="hide_data_to_bank" style="display:none;">
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields_for_table.php'); ?>
</div>    
<?php elseif($payment_sys_user_data['selector'] == 'card'): ?>
    <?//=$payment_sys_user_data['card_recipient']?> 
    <?php /* ?>
    <span style="cursor: pointer;" onclick="show_dop_data_to_paymen_sys_in_table($(this),$(this).next('div.hide_data_to_bank'),$(this).parent().parent());">
        <span>►</span>
        <?=_e('подробнее')?>
    </span>
    <?php */
    ?>
    <div class="hide_data_to_bank" >
        <span style="display: inline-block; padding-right: 5px"><?= _e('Номер карты')?>:</span><?= implode(' - ', $payment_sys_user_data['cart_number']) ?><br/>
        <!--<span style="display: inline-block; padding-right: 5px"><?= _e('Номер карты')?>:</span><?//=Currency_exchange_model::get_ps_data_for_table_short($payment_system)?><br/>-->
        <span style="display: inline-block; padding-right: 5px"><?= _e('Получатель')?>:</span><?= @$payment_sys_user_data['card_recipient'] ?><br/>
    </div>
<?php endif; ?>