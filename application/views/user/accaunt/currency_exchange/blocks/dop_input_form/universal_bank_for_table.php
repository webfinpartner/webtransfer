<?= _e('currency_exchange/timer_confirm/ackount')?> <?=$payment_sys_user_data['wire_beneficiary_account']?> 
<br/>
<div class="but greenbut but_read_more" style="cursor: pointer;" onclick="show_dop_data_to_paymen_sys_in_table($(this),$(this).next('div.hide_data_to_bank'),$(this).parent().parent());">
    <span>►</span>
    <?=_e('подробнее')?>
</div>
<div class="hide_data_to_bank" style="display:none;">
        
    <?php /*foreach($fields as $field): ?>
        <?php if(!empty($payment_sys_user_data[$field[1]])): ?>
            <span style="display: inline-block; padding-right: 5px">
                <?=$field[0]?>:
            </span> 
            <?=$payment_sys_user_data[$field[1]]; ?>
            <br/>
        <?php endif; ?>
    <?php endforeach; */?>
            
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields_for_table.php'); ?>
</div>