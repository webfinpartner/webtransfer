<? foreach( $payment_systems_groups as $group ): ?>
    <?php if(isset($group->childes)): ?>
        <?php $this->load->view('user/accaunt/currency_exchange/blocks/form/_form_payments_select.php', array('payment_systems_groups' => $group->childes)); ?>
    <?php else: ?>
        <div onclick="$(this).next().toggle();"> 
            <a class="onehrine <?= ($i == 1?'first active_group_tab':'') ?>" data-group-id="<?=$group->id?>" onclick="return false;">
<!--                <div class="onehlug"></div>
                <div class="onehldopug"></div>-->
                <?php /* if($group->public_path_to_icon): ?>     
                    <div class="onehlineico" style="background: url('<?= $payment_system->public_path_to_icon ?>') no-repeat center center"></div>
                <?php else :?>
                    <div class="onehlineico" ></div>
                <?php endif; */ ?>
                <div class="onehlinetext">                                
                    <? // echo _e( 'currency_name_'.$payment_system->machine_name) .' '. _e( 'currency_id_'.$payment_system->currency_id); ?>
                    <?php  echo $group->human_name ?>
                </div>
                <div class="clear"></div>
            </a>
        </div>
        <!--<div class="tabhome act" id="napobmento-25" style="display:none;" data-group-id="<?=$group->id?>">-->
        <div class="tabhome act" style="display:none;">
        <? foreach( $payment_systems as $payment_system )
       {    
            if($group->id != $payment_system->group_id)
            {
                continue;
            }

            $i = 0;
            if( $payment_system->present_out == 2 )
            { 
                $i++;
//                $name = 'currency_name_'.$payment_system->machine_name;
                $name = Currency_exchange_model::get_ps($payment_system->machine_name)->humen_name;
            ?>
                <a class="onehrine <?= ($i == 1?'first':'') ?>" >
                    <div class="onehrineico" style="background: url('<?= $payment_system->public_path_to_icon ?>') no-repeat center center; border: 1px solid transparent;"></div>
                    <div class="onehrinetext">
                        <? // echo _e( $name ) .' '. _e( 'currency_id_'.$payment_system->currency_id); ?>
                            <?= $name.' '.Currency_exchange_model::show_payment_system_code(['currency_id' => $payment_system->currency_id]); ?>
                    </div>
                    <input type="checkbox" name="buy_payment_systems[<?= $payment_system->machine_name ?>]" data-currency-id="<?=$payment_system->currency_id;?>" <?= ( $buy_payment_systems[ $payment_system->machine_name ] == 1 ? 'checked=""':''); ?> value="1" style="margin-top: 12px;" data-ps-mashin-name="<?= $payment_system->machine_name ?>" />
                    <div class="clear"></div>
                </a>
                <?php if(isset($save_user_data) && $save_user_data): ?>
                    <!--<div class="clear"></div>-->
                    <div class="div_user_payment_data_save <?= $payment_system->machine_name ?>" style="position: absolute; margin-top: -30px; margin-left: 10px; display:none;">
                        <?php if(isset($payment_system->payment_sys_user_data) && $payment_system->payment_sys_user_data): ?>
                        <input style="border: 1px solid transparent; border-bottom: 1px solid #dddddd; height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" readonly="readonly" />
                            <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data">Сохранить</a>
                            <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data">Изменить</a>
                        <?php else: ?>
                            <input style="height: 6px; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; width: 161px; float: left; padding: 0 3px; height: 20px" class="form_input save_user_payment_data_input" name="user_payment_data[<?= $payment_system->machine_name ?>]" data-payment-system="<?= $payment_system->machine_name ?>" value="<?= @$payment_system->payment_sys_user_data ?>" type="text" />
                            <a style="margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree save_user_payment_data">Сохранить</a>
                            <a style="display:none; margin-left: 9px; margin-top: 0px; margin-bottom: 12px; float: left;" class="but agree change_user_payment_data">Изменить</a>
                        <?php endif; ?>
                        <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
                    </div>
                <?php endif; ?>

            <? } ?>
        <? } ?>
        </div>
        
<?php endif; ?>
<?php endforeach; ?>