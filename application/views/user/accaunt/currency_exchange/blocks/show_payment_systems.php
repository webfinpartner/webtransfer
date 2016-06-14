<?php $count = 0; ?>

<?php foreach( $val->payment_systems as $v ): ?>

    
    <?php if( $v->type == $order_type ) continue; ?>
    <?php if(  isset( $show_payment_system ) && $show_payment_system !== FALSE && $v->payment_system != $show_payment_system  ) continue; ?>
    <?php if( ++$count > 2 ): ?>
        <div style="width:140px; line-height: 2px; margin-bottom: 10px">...</div>  
        <?php break;
    endif;
    ?>    
    <div style="width:140px; line-height: 23px; text-align: left">
        <!--<div class="onehrineico sprite_payment_systems bg_size_half" style="<?//= Currency_exchange_model::half_bg( $payment_systems_id_arr[ $v->payment_system ]->public_path_to_icon ); ?>;"></div>-->
        <div class="onehrineico sprite_payment_systems bg_size_half" style="<?= Currency_exchange_model::half_bg(Currency_exchange_model::get_ps($v->payment_system)->public_path_to_icon ); ?>;"></div>
        <?php echo $v->summa ?>
    <?php if($payment_systems_id_arr[ $v->payment_system ]->currency_id >= 9900): ?>
         <?//=_e('currency_name_'.$payment_systems_id_arr[$v->payment_system]->machine_name)?>
         <?//=Currency_exchange_model::show_payment_system($payment_systems_id_arr[$v->payment_system]->machine_name)?>
         <?=Currency_exchange_model::get_ps($v->payment_system)->humen_name;?>
    <?php else: ?>
        <?//= _e( 'currency_id_' . $payment_systems_id_arr[ $v->payment_system ]->currency_id ) ?>
        <?=Currency_exchange_model::show_payment_system_code(['order' => $val, 'curent_ps'=>$v]); ?>
    <?php endif; ?>
    </div>
<?php endforeach; ?> 
