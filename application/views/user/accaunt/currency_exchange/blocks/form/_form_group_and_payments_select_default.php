<?php if(isset($group->childes)): ?>
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/form/_form_group_and_payments_select.php', array('parent'=>$group, 'payment_systems_groups' => $group->childes, 'group_cont' => 'u' )); ?>
<?php endif; ?>
     <? foreach( $payment_systems as $payment_system )
        {    
             if($group->id != $payment_system->group_id)
             {
                 continue;
             }

             $i = 0;

                 $i++;
//                 $name = 'currency_name_'.$payment_system->machine_name;
                 $name = Currency_exchange_model::get_ps($payment_system->machine_name)->humen_name;
             ?>
                 <a class="onehrine <?= ($i == 1?'first':'') ?>" >
                     <div class="onehrineico sprite_payment_systems" style="<?=$payment_system->public_path_to_icon ?>; border: 1px solid transparent;"></div>
                     <div class="onehrinetext">
                         <? // echo _e( $name ) .' '. _e( 'currency_id_'.$payment_system->currency_id); ?>
                         <? if ($payment_system->id == 115 || $payment_system->id == 116 ){ ?>
                            <?= _e( $name ) ; ?>
                         <? }else{ ?>
                            <?= _e( $name ) .' '.Currency_exchange_model::show_payment_system_code(['currency_id' => $payment_system->currency_id]); ?>
                         <? } ?>
                     </div>
                     <input type="checkbox" name="<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" data-currency-id="<?=$payment_system->currency_id;?>" <?= ( @$buy_payment_systems[ $payment_system->machine_name ] == 1 ? 'checked=""':''); ?> value="1" style="margin-top: 12px;" data-ps-mashin-name="<?= $payment_system->machine_name ?>" />
                     <input type="hidden" name="input_summa_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" value="<?=@${$user_payment_summa_field}[$payment_system->machine_name];?>" />
                <?php if(isset($show_payment_summa) && $show_payment_summa === TRUE): ?>
                    <div class="div_user_payment_summa_save <?= $payment_system->machine_name ?>" style="margin-top: 0px; margin-left: 0px; display:none;">
                        <input name="<?=$user_payment_summa_field?>[<?= $payment_system->machine_name ?>]" value="<?=${$user_payment_summa_field}[$payment_system->machine_name]; ?>" data-payment-system="<?= $payment_system->machine_name ?>"  type="text" placeholder="Желаемая сумма" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" style="height: 6px; margin-left: 0px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" />
                    </div>
                     <div class="clear"></div>
                <?php endif; ?>     
                 </a>
                 <?php if(isset($show_payment_data) && $show_payment_data === TRUE && !Currency_exchange_model::is_root_curr($payment_system->machine_name)): ?>
                     <?php if(isset($save_user_data) && $save_user_data): ?>
                     <div class="div_user_payment_data_save <?= $payment_system->machine_name ?>" style="/*position: absolute;*/ margin:-30px 0 0 54px; display:none;">
                         <?php /* if(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data): ?>
                            <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" readonly="readonly" />
                             <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data <?=@$input_settings_class_for_ps?>"><?php echo _e('currency_exchange/_form_group_and_payments_select/save'); ?></a>
                             <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
                         <?php else: ?>
                             <input style="height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input <?=@$input_settings_class_for_ps?>" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" placeholder="<?php echo _e('currency_exchange/_form_group_and_payments_select/data_transfer'); ?>" />
                             <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data <?=@$input_settings_class_for_ps?>"><?php echo _e('currency_exchange/_form_group_and_payments_select/save'); ?></a>
                             <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data"><?php echo _e('currency_exchange/_form_group_and_payments_select/change'); ?></a>
                         <?php endif; */?>
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
                         <div class="clear"></div>
                     </div>
                     <?php endif; ?>
                     

                 <?php endif; ?>
         <? } ?>
    <?php if(isset($save_user_data) && $save_user_data && $group->show_add_new == 1): ?>
        <a class="onehrine" style="height: 45px;" onclick="console.log($(this).find('span')); togle_and_switch_text(event, $(this).next('.add_new_user_payment_system'), $(this).find('span'));">
            <div class="onehrinetext" style="margin-left: 12px;">
                <?=_e('currency_exchange/_form_group_and_payments_select/more'); ?> <span>&#9658;</span> 
            </div>
        </a>
        <div class="add_new_user_payment_system" style="display:none;">
            <a class="onehrine" style="height: 79px;">
                <div class="onehrinetext" style="margin-left: 12px;">
                    <?=_e('currency_exchange/_form_group_and_payments_select/add_new'); ?>
                </div>
                <div class="clear"></div>
            </a>
            <div style="margin-top: -35px; margin-left: 10px;  padding: 0 3px; height: 35px;">
                <input style="height: 14px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 4px 3px;" class="form_input save_new_user_payment_data_input hide_after_save"  type="text" />
                <!--<input type="hidden" name="group" value="<?=$group->id;?>" />-->
                <input type="hidden" class="save_new_user_payment_data_input_group" value="<?=$group->id;?>" />
                <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree small_but save_new_user_payment_data"><?=_e('currency_exchange/_form_group_and_payments_select/add'); ?></a>
                <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
                <div class="ress" style="display: none;"></div>
            </div>
        </div>
    <?php endif; ?>
    <!--</div>-->
