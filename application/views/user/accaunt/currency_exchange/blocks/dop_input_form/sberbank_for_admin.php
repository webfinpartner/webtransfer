<br/>
<?php if($payment_sys_user_data['selector'] == 'requizites'): ?>
    
    <span style="display: inline-block; padding-right: 5px"><?= _e('Получатель')?>:</span> <?= @$payment_sys_user_data['recipient'] ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('Расчетный счет')?>:</span><?= @$payment_sys_user_data['rs'] ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('БИК')?>:</span><?= @$payment_sys_user_data['bik'] ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('Наименование Банка')?>:</span><?= @$payment_sys_user_data['name'] ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('Кор. счет')?>:</span><?= @$payment_sys_user_data['ks'] ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('Назначение')?>:</span> <?= @$payment_sys_user_data['destination'] ?>
    
<?php elseif($payment_sys_user_data['selector'] == 'requizites_univesal'): ?>
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields_for_table.php'); ?>
<?php elseif($payment_sys_user_data['selector'] == 'card'): ?>

    <span style="display: inline-block; padding-right: 5px"><?= _e('Номер карты')?>:</span><?= implode(' - ', $payment_sys_user_data['cart_number']) ?><br/>
    <span style="display: inline-block; padding-right: 5px"><?= _e('Получатель')?>:</span><?= @$payment_sys_user_data['card_recipient'] ?><br/>
<?php endif; ?>