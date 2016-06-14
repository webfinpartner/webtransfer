<a class="onehrine" style="height: 45px;" onclick="togle_and_switch_text(event, $(this).next('.add_new_user_payment_system'), $(this).find('span'))">
    <div class="onehrinetext" style="margin-left: 12px;">
        <?=_e('currency_exchange/_form_group_and_payments_select/more'); ?> <span>&#9658;</span> 
    </div>
</a>
<div class="add_new_user_payment_system" style="display:none;">
    <?php /**/ ?>
    <a class="onehrine" style="height: auto;">
        <div class="onehrinetext" style="margin-left: 12px;">
            <?=_e('currency_exchange/_form_group_and_payments_select/add_new'); ?>
        </div>
        <div class="clear"></div>
    
    <div class="new_field_container" data-url="<?=_e('Ссылка');?>" data-name="<?=_e('Название');?>" style="margin-top: -30px; margin-left: 10px;  padding: 0 3px; height: 55px;">
        <input style="height: 14px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 4px 3px;" class="form_input save_new_user_payment_data_input hide_after_save"  type="text" placeholder="<?=_e('Название');?>" />
        <input style="height: 14px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 4px 3px;" class="form_input hide_after_save save_new_user_payment_data_input_url"  type="text" placeholder="<?=_e('Ссылка');?>" />
<!--        <input type="hidden" name="group" value="<?//=$group->id;?>" />
        <input type="hidden" name="country" value="<?//=$country->id;?>" />-->
        <input type="hidden" class="save_new_user_payment_data_input_group" value="<?=$group->id;?>" />
        <input type="hidden" class="save_new_user_payment_data_input_country" value="<?=$country->id;?>" />
        
        <span class="but agree small_but" onclick="bank_add_new($(this));">
            <?=_e('currency_exchange/_form_group_and_payments_select/add'); ?>
        </span>
        <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
        <div class="clear"></div>
        <div class="ress" style="display: none;"></div>
    </div>
    <div class="clear"></div>
    </a>
    <?php /**/ ?>
</div>
