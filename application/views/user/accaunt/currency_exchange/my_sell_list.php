<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<?
if( !$notAjax )
{
    ?>
    <h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>

<?
if( 0 )
{
    ?>
    <div class="rower"><div class="td1"><? _e( 'accaunt/balans_5' ) ?></div><div class="td2">- $<? //price_format_double($outcomeSumma)    ?></div></div>
<? } ?>

<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange_sms.css"/>
<link rel="stylesheet" href="/css/user/filter.css" type="text/css" media="screen" />


<script type="text/javascript">
    var $ps_curency_array_currency_id = $.parseJSON('<?=  Currency_exchange_model::all_currencys_to_js() ?>');
//    var curency_str = {9840:'<?=_e('currency_symbol_9840')?>', 840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
    var curency_str = $ps_curency_array_currency_id;
    var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";
    var delete_order_from_initiator_url = "<?=site_url('account/currency_exchange/ajax/delete_from_initiator')?>";
    var save_user_payments_data_url = "<?=site_url('account/currency_exchange/ajax/save_user_data_payment')?>";
    var save_user_new_payments_data_url ="<?=site_url('account/currency_exchange/ajax/save_user_data_new_payment')?>";
    var send_cinfirm_exchang_request_url = "<?=site_url('account/currency_exchange/ajax/exchange_confirmation')?>";
    var get_all_chat_messages_url = "<?=site_url('account/currency_exchange/ajax/get_all_chat_messages')?>";
    var make_prefund_first_transaction_url = "<?=site_url('account/currency_exchange/ajax/make_prefund_first_transaction')?>";
    var set_user_payment_data_url = "<?=site_url('account/currency_exchange/ajax/set_user_payment_data')?>";
    var check_visa_money_from_order_url = "<?=site_url('account/currency_exchange/ajax/check_visa_money_from_order')?>";
    var prefund_take_money_url = "<?=site_url('account/currency_exchange/ajax/prefund_take_money')?>";
    var $user_id = "<?=$user_id?>";
    var $orders_on_list = '<?=$orders_on_list?>';
    var $curent_list = 1;
    
    var $translate_js_summ = '<?=_e('currency_exchange/translate_js/summ'); ?>';
    var $translate_js_error_fill_all_data = '<?= _e('currency_exchange/translate_js/error_fill_all_data')?>';
    var $translate_js_error_select_wt_in_sell_or_by = '<?= _e('currency_exchange/translate_js/error_select_wt_in_sell_or_by')?>';
    var $translate_js_you = '<?= _e('currency_exchange/translate_js/you')?>';
    var $translate_js_message_from_operator = '<?= _e('currency_exchange/translate_js/message_from_operator')?>';
    var $translate_js_message_from_operator_to_all = '<?= _e('Сообщение от оператора: к обеим сторонам')?>';
    var $translate_js_message_to_operator = '<?= _e('currency_exchange/translate_js/message_to_operator')?>';
    var $translate_js_attached_file = '<?= _e('currency_exchange/translate_js/attached_file')?>';
    var $translate_js_show_all_message = '<?= _e('currency_exchange/translate_js/show_all_message')?>';
    var $translate_js_hide_message = '<?= _e('currency_exchange/translate_js/hide_message')?>';
    var $translate_js_otvet = '<?= _e('Ответить')?>';
    var $translate_js_error_current_time_only_wt = '<?= _e('currency_exchange/translate_js/error_current_time_only_wt')?>';
    var $translate_js_error_fill_paymet_data = '<?=_e('currency_exchange/translate_js/error_fill_paymet_data'); ?>';
    var $translate_js_error_fill_no_select_ps = '<?=_e('currency_exchange/translate_js/error_no_select_ps'); ?>';
    var security = '<?=$security ?>';
    
    var root_currency = $.parseJSON('{"wt":"9840","wt_c_creds":"9842","wt_heart":"9841","wt_debit_usd":"840"}');
    // var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');
</script>
<script async type="text/javascript" src="/js/user/sms_module.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange_visa.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange.js"></script>

<?php if(!empty($orders_arhive)): ?>
    <h1 class="title"><?=_e('currency_exchange/my_sell_list/confirmation_expected')?></h1>
    <?php  $this->load->view('user/accaunt/currency_exchange/blocks/my_sell_list_table.php', array('res_search' => $orders_arhive, 'payment_systems_id_arr' => $payment_systems_id_arr, 'button'=>$button, 'script' => false)); ?>
    <br/>
    <?php if(!empty($orders)): ?>
        <h1 class="title"><?=_e('currency_exchange/my_sell_list/my_orders')?></h1>
    <?php endif; ?>
<?php endif; ?>
    

<?
if($page_name == 'my_sell_list_arhive') {
    ?>
    <div style="margin-right: 171px; text-align: right; margin-top: -45px; margin-bottom: 21px;" class="export-excel">
        <a href="#" onclick="$('.excel_export').show(); return  false;">
            <img src="/images/excel.png" style="vertical-align : middle;">
        </a>
    </div>

    <div id="form_type">
        <form method="POST">
            <select name="filter_state" onchange="if (this.selectedIndex) this.form.submit ()">
                <option disabled="disabled"><?=_e('accaunt/credits_enqueries_35')?></option>
                <option value="t0" <?=(($filter_state != 't0') ? NULL : 'selected="selected"')?>>
                    <?=_e('filter/all')?></option>
                <option value="t1" <?=(($filter_state != 't1') ? NULL : 'selected="selected"')?>>
                    <?=_e('filter/completed')?></option>
                <option value="t2" <?=(($filter_state != 't2') ? NULL : 'selected="selected"')?>>
                    <?=_e('filter/canceled_operator')?></option>
                <option value="t3" <?=(($filter_state != 't3') ? NULL : 'selected="selected"')?>>
                    <?=_e('filter/cancel_counterparty')?></option>
                <option value="t4" <?=(($filter_state != 't4') ? NULL : 'selected="selected"')?>>
                    <?=_e('filter/operator_confirmed')?></option>
            </select>
        </form>
    </div>
    <?
} 
?>
<?php if(count($orders)): ?>
    <div class="my_order_and_confirm_list">
        <?php $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', array('res_search' => $orders, 'payment_systems_id_arr' => $payment_systems_id_arr, 'button'=>FALSE, 'script' => FALSE, 'user_id'=>$user_id)); ?>
        <?php if($count_all_orders > $orders_on_list): ?>
        <div style="text-align: center; margin: 10px;">
            <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
            <a href="#" class="table_green_button" onclick="load_more_orders('<?=$url_load_more_orders?>', $('.my_order_and_confirm_list table.add_more_orders'), $(this));return false;">
                <?=_e('currency_exchange/my_sell_list/more')?>
            </a>
        </div>
        <?php endif; ?>
    </div>     
<?php else: ?> 
    <?
    if(empty($orders_arhive)) {
        ?>
        <div class="message"> <?=_e('currency_exchange/my_sell_list/no_new')?></div>
        <?
    }
    ?>
<?php endif; ?>
        
<?php /*if(count($orders_arhive_problem)): ?>
    <h1 class="title"><?=_e('currency_exchange/my_sell_list/problem_orders')?></h1>    
    <?php  $this->load->view('user/accaunt/currency_exchange/blocks/table_problem_orders.php', array('res_search' => $orders_arhive_problem, 'payment_systems_id_arr' => $payment_systems_id_arr, 'button'=>FALSE, 'user_id'=>$user_id)); ?>
<?php endif; */?>

<?php $this->load->view('user/accaunt/currency_exchange/blocks/confirmation_block.php'); ?>
<?php // $this->load->view('user/accaunt/currency_exchange/blocks/problem_block.php');3 ?>

<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_error.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_notification.php'); ?>
        
<?php $this->load->view('user/accaunt/currency_exchange/blocks/last_user_confirm_block_money_send.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/last_seller_confirm_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/last_user_confirm_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/real_last_user_confirm_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/initiator_confirm_money_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/buyer_confirm_receipt.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/select_payment_system_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/modal_select_visa_card.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/canceled_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/modal_export_excel.php', array('type' => 'order_archive')); ?>
    
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_send_message_operator.php'); ?>
<?= $page_bottom ?>
<?php //add_universal_popup( 'text', 'p2p/popup_visa_tranfer_money_instruction_title', 'p2p/popup_visa_tranfer_money_instruction_body', 'popup_visa_tranfer_money' ); ?>    
