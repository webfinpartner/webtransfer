<!--<div class="selec_country_container selec_country_container_<?//=$checkbox_name?>">-->
    <!--<a href="#" onclick="alert('%%%%%'); return false;"><?//=_e('Добавить банк');?></a>-->
    <a class="onehrine <?= ($i == 1?'first':'') ?>" onclick="show_popup_select_bank($('#dop_ps_select_country_<?=$checkbox_name?>'), '<?=$checkbox_name?>'); return false;" >
        <div class="onehrineico sprite_payment_systems" style="background-image: url(/img/add_icon_bw.png); background-size: 45px auto; background-position: -3px; border: 1px solid transparent;"></div>
        <div class="onehrinetext"><?=(isset($add_bank_button_text))?$add_bank_button_text:_e('Добавить банк');?></div>
    </a>
<!--</div>-->

<div class="countent_country_overall_<?=$checkbox_name?>" style="display: none;">
    <center><img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;"></center>
</div>

<?php if(isset($user_all_payment_data)): ?>
    <?php foreach ($user_all_payment_data as $pament_data): ?>
    <?php
        if(Currency_exchange_model::get_ps($pament_data->payment_system_id)->is_bank != 1 || Currency_exchange_model::get_ps($pament_data->payment_system_id)->group_id != 1 )
        {
            continue;
        }
        
        $data =[
            'payment_system' => Currency_exchange_model::get_ps($pament_data->payment_system_id),
            'checkbox_name' => $checkbox_name,
            'name' => Currency_exchange_model::get_ps($pament_data->payment_system_id)->humen_name,
            'currencys' => $currencys,
            'save_user_data' => @$save_user_data,
            'no_show_select_currency' => @$no_show_select_currency,
            'ps_checked' => [$pament_data->payment_system_id => 1]
        ];
    ?>
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/show_bank.php', $data); ?>   
    <?php endforeach; ?>
<?php endif; ?>


<?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_ps_select_country.php'); ?>