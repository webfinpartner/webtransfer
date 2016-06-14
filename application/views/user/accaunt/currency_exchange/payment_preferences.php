<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<?
if( !$notAjax )
{
    ?>
    <h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>

<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange_sms.css"/>



<script type="text/javascript">
    var $ps_curency_array_currency_id = $.parseJSON('<?=  Currency_exchange_model::all_currencys_to_js() ?>');
//    var curency_str = {9840:'<?=_e('currency_symbol_9840')?>', 840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
    var curency_str = $ps_curency_array_currency_id;
    var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";
    var save_user_payments_data_url = "<?=site_url('account/currency_exchange/ajax/save_user_data_payment')?>";
    var save_user_new_payments_data_url ="<?=site_url('account/currency_exchange/ajax/save_user_data_new_payment')?>";
    var get_country_bank_data_url ="<?=site_url('account/currency_exchange/ajax/get_country_bank_data')?>";
    var get_add_bank_in_payment_system_url ="<?=site_url('account/currency_exchange/ajax/get_add_bank_in_payment_system')?>";
    
//    var $ps_fee_percentage = <?=$payment_systems['wt']->fee_percentage?>;
//    var $ps_fee_percentage_add = <?=$payment_systems['wt']->fee_percentage_add?>;
// TODO похоже это уже не нужно
//    var ps_fee_data = {
//        'fee_percentage': <?//=$payment_systems['wt']->fee_percentage?>,
//        'fee_percentage_add': <?//=$payment_systems['wt']->fee_percentage_add?>,
//        'fee_min': <?//=$payment_systems['wt']->fee_min?>,
//        'fee_max': <?//=$payment_systems['wt']->fee_max?>,
//        };
    var str_paymen_preferences = true; 
    
    var $translate_js_summ = '<?=_e('currency_exchange/translate_js/summ'); ?>';
    var $translate_js_error_fill_all_data = '<?= _e('currency_exchange/translate_js/error_fill_all_data')?>';
    var $translate_js_error_select_wt_in_sell_or_by = '<?= _e('currency_exchange/translate_js/error_select_wt_in_sell_or_by')?>';
    var $translate_js_you = '<?= _e('currency_exchange/translate_js/you')?>';
    var $translate_js_message_from_operator = '<?= _e('currency_exchange/translate_js/message_from_operator')?>';
    var $translate_js_message_to_operator = '<?= _e('currency_exchange/translate_js/message_to_operator')?>';
    var $translate_js_attached_file = '<?= _e('currency_exchange/translate_js/attached_file')?>';
    var $translate_js_show_all_message = '<?= _e('currency_exchange/translate_js/show_all_message')?>';
    var $translate_js_hide_message = '<?= _e('currency_exchange/translate_js/hide_message')?>';
    var $translate_js_otvet = '<?= _e('Ответить')?>';
    var $translate_js_error_current_time_only_wt = '<?= _e('currency_exchange/translate_js/error_current_time_only_wt')?>';
    var $translate_js_error_fill_paymet_data = '<?=_e('currency_exchange/translate_js/error_fill_paymet_data'); ?>';
    var $translate_js_error_fill_no_select_ps = '<?=_e('currency_exchange/translate_js/error_no_select_ps'); ?>';
    var $translate_js_error_no_selec_currency = '<?=_e('Не выбранна');?>';
    
    var security = '<?=$security ?>';
    
    var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');

    $(document).ready(function(){
        $('input[name=buy_payment_systems\\[wt_visa_usd\\]]').parent().remove();
    });
</script>

<script async type="text/javascript" src="/js/user/sms_module.js"></script>
<!--<script async type="text/javascript" src="/js/user/currency_exchange.js"></script>-->
<?=add_js_script("/js/user/currency_exchange.js", 'currency_exchange', ['async'=>'async'])?>
<?=add_js_script("/js/user/currency_exchange_visa.js", 'currency_exchange_visa', ['async'=>'async'])?>

<!--<script src="/js/user/select2-3.4.1/select2.js"></script>-->
<?=add_js_script("/js/user/select2-3.4.1/select2.js", 'currency_exchange')?>

<!--<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/css/select21.css"/>-->
<?=add_css_style("/js/user/select2-3.4.1/css/select21.css", 'currency_exchange')?>
<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/select2-bootstrap.css"/>
<style>
div.left_right_container_payment_buy_text
{
    border: none;
}

.left_right_container_payment_buy_text div.right_container_payment_buy_text 
{
    border: none;
    width:300px;
}
.quick_selected_payment_buy .input_container_div input
{
    /*border: none;*/
    width:160px !important;
}

</style>
    

<?php if(@$no_show_select_currency === true): ?>
<input type="hidden" name="no_show_select_currency" value="1" />
<?php endif ?>
<?php // $this->load->view('user/accaunt/currency_exchange/blocks/form.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems)); ?>
<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
<?php // $this->load->view($form_folder.'form_open.php'); ?>

<?php $this->load->view($form_folder.'form_selected_payment_system_to_preferences.php', array('no_show_text'=> true)); ?>

<?php $this->load->view($form_folder.'form_payments_preferences.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems, 'save_user_data' => true)); ?>



<?php 
$input_array = array(
    'sell_sum_amount' => $sell_sum_amount,
    'sell_sum_fee' => $sell_sum_fee,
    'sell_sum_total' => $sell_sum_total,
    'buy_amount_down' => $buy_amount_down,
    'buy_amount_up' => $buy_amount_up,
); 
?>
<?php // $this->load->view($form_folder.'form_inputs_sell.php', $input_array); ?>

<?php // $this->load->view($form_folder.'form_close.php'); ?>



<!--<button class="button" type="button" id="out_submit" name="submit">Оформить</button>-->

<?php // $this->load->view('user/accaunt/currency_exchange/blocks/out_submit.php'); ?>
<?php // $this->load->view('user/accaunt/blocks/renderCodeProtection_window.php', $par); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_ps_input_container.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_error.php'); ?>
<div class="clear"></div>
<div class="fcwidget">
    <div class="fcwidgetvn">
        <div class="fcwtitle">
            <div class="fcwtitlevn">
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
</div>

<?= $page_bottom ?>
