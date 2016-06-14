<?php if(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data): ?>
    <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input user_payment_data_<?= $payment_system->id ?> <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?=Currency_exchange_model::get_ps_data_for_table_short($payment_system)?>" type="text" readonly="readonly" />
    <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree" onclick="show_dop_ps_input_data('<?=$payment_system->id?>','<?=site_url('account/currency_exchange/ajax/get_dop_ps_data_multi_field')?>' , '<?=site_url('account/currency_exchange/ajax/set_dop_ps_data_multi_field')?>');"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
<?php else: ?>
    <input style="height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input user_payment_data_<?= $payment_system->id ?> <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?=Currency_exchange_model::get_ps_data_for_table_short($payment_system)?>" type="text" placeholder="<?php echo _e('Заполните реквизиты'); ?>" readonly="readonly" />
    <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree " onclick="show_dop_ps_input_data('<?=$payment_system->id?>','<?=site_url('account/currency_exchange/ajax/get_dop_ps_data_multi_field')?>' , '<?=site_url('account/currency_exchange/ajax/set_dop_ps_data_multi_field')?>');"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
<?php endif; ?>
    
<?php /* if(isset($ajax_template) && $ajax_template === true): ?>    
<div class="bank_ajax_fields" style="display: none;">
    <div class="dop_ps_input_data_continer dop_ps_input_data_<?=$payment_system->id?>">
        <br/>
        <br/>
        <input type="hidden" name="id" value="<?=$payment_system->id?>" />
        <div class="cont_requizites cont_requizites_universal_bank" <?=@$ps_user_data['selector']=='card'?'style="display:none;"':'';?>>
            
            <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields.php'); ?>
            
            <button class="button" type="button" onclick="send_form_dop_ps_input_data($(this).parent().parent(), $(this).parent().find('input[name=wire_beneficiary_account]').val())" style="width: 366px;" >
                <?= _e('Сохранить')?>
            </button>
        </div>

        <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
    </div>
</div>
<?php endif; */?>    