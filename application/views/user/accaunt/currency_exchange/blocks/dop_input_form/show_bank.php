<a class="onehrine" >
    <div class="onehrineico sprite_payment_systems" title="<?=$name; ?>" style="<?=$payment_system->public_path_to_icon ?>; border: 1px solid transparent;"></div>
        <div class="onehrinetext show_bank_name_in_sell" title="<?=$name; ?>">
            <?=$name ?>
        </div>
    <input type="checkbox" name="<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" data-js-parent-height="<?=(isset($save_user_data) && $save_user_data)?'95':'63'?>" data-currency-id="<?=$payment_system->currency_id;?>" checked="checked" value="1" data-ps-mashin-name="<?= $payment_system->machine_name ?>" onchange="<?=$checkbox_name?>_checkbox_change($(this));" style="margin-top: 12px;" />

    <input type="hidden" name="input_summa_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" value="" />
</a>

<div class="div_user_payment_data_save <?= $payment_system->machine_name ?>" style=" margin:-54px 0 0 54px; display:none;">
    <?php if(@$no_show_select_currency !== true): ?>
        <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/select_currencys.php', ['currencys' => $currencys, 'payment_system'=>$payment_system] ); ?>

        <div class="clear"></div>
    <?php endif; ?>
    <?php if(isset($save_user_data) && $save_user_data && !Currency_exchange_model::is_root_curr($payment_system->machine_name)): ?>
        <?php if(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data && empty($payment_system->method_get_field_and_ps_data)): ?>
           <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" readonly="readonly" />
           <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data <?=@$input_settings_class_for_ps?>"><?php echo _e('currency_exchange/_form_group_and_payments_select/save'); ?></a>
           <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
       <?php // elseif(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data && !empty($payment_system->method_get_field_and_ps_data)): ?>
       <?php elseif(!empty($payment_system->method_get_field_and_ps_data)): ?>
           <?php echo Currency_exchange_model::get_field_and_ps_data($payment_system); ?>
       <?php else: ?>
           <input style="height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" placeholder="<?php echo _e('currency_exchange/_form_group_and_payments_select/data_transfer'); ?>" />
           <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data <?=@$input_settings_class_for_ps?>"><?php echo _e('currency_exchange/_form_group_and_payments_select/save'); ?></a>
                <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
       <?php endif; ?>
        <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
    <?php endif; ?>
   <div class="clear"></div>
</div>