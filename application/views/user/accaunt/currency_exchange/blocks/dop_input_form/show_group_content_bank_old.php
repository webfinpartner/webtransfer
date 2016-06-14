<div class="selec_country_container">
    <?=_e('Страна')?>:
    <select onchange="show_country_content($('.countent_country_overall'), $('.content_<?=$checkbox_name?>_country_'+$(this).val()), $(this).val());">
    <!--<select onchange="show_country_content($('.content_<?=$checkbox_name?>_country_<?=$country->id?>'), $('.content_<?=$checkbox_name?>_country_'+$(this).val()), $(this).val());">-->
        <option value="0"></option>
        <?php foreach($countris as $country): ?>
            <option value="<?=$country->id?>"><?=_e($country->country_name_ru);?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php foreach($countris as $country): ?>
    <div class="countent_country_overall content_<?=$checkbox_name?>_country_<?=$country->id?>" style="display: none;">
    <?php foreach( $payment_systems as $payment_system ): 

                 if($country->id != $payment_system->country_id)
                 {
                     continue;
                 }

                 $i = 0;

                     $i++;
//                     $name = 'currency_name_'.$payment_system->machine_name;
                     $name = Currency_exchange_model::get_ps($ps->id)->humen_name;
?>
                <a class="onehrine <?= ($i == 1?'first':'') ?>" >
                    <div class="onehrineico sprite_payment_systems" style="<?=$payment_system->public_path_to_icon ?>; border: 1px solid transparent;"></div>
                    <div class="onehrinetext">
                        <?//=_e( $name ); ?>
                        <?= $name ; ?>
                    </div>

                    <input type="checkbox" name="<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" data-js-parent-height="<?=(isset($save_user_data) && $save_user_data)?'95':'63'?>" data-currency-id="<?=$payment_system->currency_id;?>" <?= ( @$buy_payment_systems[ $payment_system->machine_name ] == 1 ? 'checked=""':''); ?> value="1" style="margin-top: 12px;" data-ps-mashin-name="<?= $payment_system->machine_name ?>" />

                    <input type="hidden" name="input_summa_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" value="<?=@${$user_payment_summa_field}[$payment_system->machine_name];?>" />
                    <?php if(isset($show_payment_summa) && $show_payment_summa === TRUE): ?>
                        <div class="div_user_payment_summa_save <?= $payment_system->machine_name ?>" style="margin-top: 0px; margin-left: 0px; display:none;">
                            <input name="<?=$user_payment_summa_field?>[<?= $payment_system->machine_name ?>]" value="<?=${$user_payment_summa_field}[$payment_system->machine_name]; ?>" data-payment-system="<?= $payment_system->machine_name ?>"  type="text" placeholder="Желаемая сумма" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" style="height: 6px; margin-left: 0px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" />
                        </div>
                        <div class="clear"></div>
                    <?php endif; ?>     
                </a>
                <?php  ?>  
                <div class="div_user_payment_data_save <?= $payment_system->machine_name ?>" style=" margin:-54px 0 0 54px; display:none;">
                    <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/select_currencys.php', ['currencys' => $currencys, 'payment_system'=>$payment_system] ); ?>
                    <div class="clear"></div>
                    <?php if(isset($show_payment_data) && $show_payment_data === TRUE && !Currency_exchange_model::is_root_curr($payment_system->machine_name)): ?>
                        <?php if(isset($save_user_data) && $save_user_data): ?>
                            <?php if(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data && empty($payment_system->method_get_field_and_ps_data)): ?>
                               <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" readonly="readonly" />
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
   <!--                         <div class="clear"></div>-->
                        <?php endif; ?>
                    <?php endif; ?>
                   <div class="clear"></div>
                </div>
        
                <?php  ?>
                
    <?php endforeach; ?>
        
        <?php if(isset($save_user_data) && $save_user_data && $group->show_add_new == 1): ?>
            <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/bank_add_new.php', ['group' => $group, 'country'=>$country] ); ?>
        <?php endif; ?>
        
    </div>
<?php endforeach; ?>
