<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<link async rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script async type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
<script async type="text/javascript" charset="utf8" src="/js/datatable/jquery.dataTables.columnFilter.js"></script>
<style>
    #DataTables_Table_0_processing.dataTables_processing{
        background: transparent repeating-linear-gradient(-45deg, rgb(70, 82, 152), rgb(70, 82, 152) 25%, rgb(63, 73, 136) 25%, rgb(63, 73, 136) 50%, rgb(70, 82, 152) 50%) repeat fixed left top / 30px 30px;
        background-size: 30px 30px;
        color: white;
        opacity: 0.9;
    }
    .dataTables_length {margin-bottom: 5px}
</style>
<?
if(  @!$notAjax )
{
    ?>
    <h1 class="title"><?= @$title_accaunt ?></h1>
<? } ?>

<?
if( 0 )
{
    ?>
    <div class="rower"><div class="td1"><? _e( 'accaunt/balans_5' ) ?></div><div class="td2">- $<? //price_format_double($outcomeSumma)    ?></div></div>
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
    var send_cinfirm_exchang_request_url = "<?=site_url('account/currency_exchange/ajax/exchange_confirmation')?>";
    var send_check_buyer_rating = "<?=site_url('account/currency_exchange/ajax/check_buyer_rating')?>";
    var redirect_confirmation_url_sell = "<?=site_url('account/currency_exchange/my_sell_list')?>";
    var redirect_confirmation_url_buy = "<?=site_url('account/currency_exchange/my_buy_list')?>";
    var search_in_url = "<?=site_url('account/currency_exchange/ajax/sell_search')?>";
    var search_details_url = "<?=site_url('account/currency_exchange/ajax/search_order_details')?>";
    var get_country_bank_data_url ="<?=site_url('account/currency_exchange/ajax/get_country_bank_data')?>";
    var get_add_bank_in_payment_system_url ="<?=site_url('account/currency_exchange/ajax/get_add_bank_in_payment_system')?>";
    var is_page_search = true;
    var get_user_payment_system_to_order_url = "<?=site_url('account/currency_exchange/ajax/get_user_payment_system_to_order')?>";

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
    var $translate_js_choose_ps = '<?= _e('Выберите платежную систему') ?>';
    var $translate_js_enter_amount = '<?= _e('Укажите сумму') ?>';
    var $translate_js_error_no_selec_currency = '<?=_e('Не выбранна');?>';

    var security = '<?=$security ?>';
    var site_lang = "<?=_e('lang')?>";
    var content_width = $('.content').width();
    var sell_step = 1;

    var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');

    $(document).ready(function(){
        var content_width = $('.content').width();
        console.log(content_width);
    });

</script>

<!--<script async type="text/javascript" src="/js/user/sms_module.js"></script>-->
<script async type="text/javascript" src="/js/user/currency_exchange.js"></script>
<script src="/js/user/select2-3.4.1/select2.js"></script>

<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/css/select21.css"/>
<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/select2-bootstrap.css"/>

<script>
    $(function(){
        <?php
        $action = $this->input->get('action', TRUE);
        $currency = $this->input->get('currency', TRUE);
        $summa = $this->input->get('summa', TRUE);
        $summa = round( $summa / 50 ) * 50;
        if( $summa <= 0 ) $summa = 50;

        if( !empty( $action ) && !empty( $currency ) && !empty( $summa ) )
        {
            if( $action == 'withdrawal' ): ?>
                setTimeout(function(){
                    withdrawal_search( '<?= $currency ?>', '<?= $summa ?>' );
                },3000);
            <?php elseif( $action == 'add_money' ): ?>
                setTimeout(function(){
                    add_money_search( '<?= $currency ?>', '<?= $summa ?>' );
                },1000);
            <?php endif;
        } ?>
    });
</script>

<? if( !empty($show_start) ): ?>
<script>
    $(function(){
            search_all_orders($('#all_offers'));
    });
</script>
<? endif; ?>

<input type="hidden" name="save_user_data" value="<?=(int)$save_user_data?>" />

<div class="search_fields_container">
    <a id="all_offers" onclick="search_all_orders($(this)); return false;" style="float: right; margin: -49px 2px 0 0; text-align: center; width:135px;" class="table_green_button" href="#search">
        <?= _e('currency_exchange/sell_wt_search/all_orders')?>
    </a>

    <?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
    <?php $this->load->view($form_folder.'form_open.php'); ?>
    <?php $this->load->view($form_folder.'form_payments_select.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems, 'show_select_all_payment_system' => true/*'save_user_data' => true*/)); ?>

    <?php $this->load->view($form_folder.'form_selected_payment_system.php'); ?>
    <a name="search"></a>
    <?php
    $input_array = array(
        'sell_sum_amount' => $sell_sum_amount,
        'sell_sum_fee' => $sell_sum_fee,
        'sell_sum_total' => $sell_sum_total,
        'buy_amount_down' => $buy_amount_down,
        'buy_amount_up' => $buy_amount_up,
    );
    ?>
    <?php $this->load->view($form_folder.'form_inputs_sell_search.php', $input_array); ?>
    <?php $this->load->view($form_folder.'form_close.php'); ?>
    <button class="button" type="button" id="out_submit_search" name="submit"><?= _e('currency_exchange/sell_wt_search/search_button')?></button>
</div>

    <?php $this->load->view('user/accaunt/currency_exchange/blocks/exchange_confirmation_block.php'); ?>
    <?php $this->load->view('user/accaunt/currency_exchange/blocks/exchange_confirmation_wt_block.php'); ?>


<div class="new_search_button_container" style="display: none;">
    <a onclick="togle_and_switch_search(true); return false;" style="float: right; margin: -49px 2px 0 0; text-align: center; width:135px;" class="table_green_button" href="#search">
        <?= _e('Фильтры')?>
    </a>
</div>

<?php $this->load->view('user/accaunt/currency_exchange/blocks/exchange_confirmation_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/exchange_confirmation_wt_block.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_ps_input_container.php'); ?>
<div id="res_search_container"></div>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/sell_wt_search__table.php'); ?>

<!--
<?php /*if(!empty($res_search)): ?>
    <?php  $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', array('res_search' => $res_search, 'payment_systems_id_arr' => $payment_systems_id_arr, 'user_id'=>$user_id)); ?>
<?php elseif (isset($error_message)): ?>
<table class="table_results">
    <thead class="table_header">
       <!--  <td class="table_cell"><?=_e('ID');?></td>
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/contragent')?></td>    -->
        <!--<td class="table_cell"><?//= _e('currency_exchange/sell_wt_search/sell')?></td>-->
        <td class="table_cell"><?=sprintf(_e('%s отдаёт'), $curent_user_data->name_sername)?></td>
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/date')?></td>
        <!--<td class="table_cell"><?//= _e('currency_exchange/sell_wt_search/buy')?></td>-->
        <td class="table_cell"><?=sprintf(_e('%s получает'), $curent_user_data->name_sername)?></td>
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/status')?></td>
    </thead>
    <tbody class="table_body">
        <tr class="table_row_header htitle">
            <td class="table_cell" colspan="6"><span style="color: #ff7f66;"><?php echo $error_message ?></span></td>
        </tr>
    </tbody>
</table>
<?php endif; */?>
-->
<div style="text-align: center;"><img class="loading-gif" src="/images/loading.gif" style="display: none"></div>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_error.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_notification.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/currencys_list_container.php'); ?>

<div class="fcwidget">
    <div class="fcwidgetvn">
        <br/>
        <br/>
        <?//= _e('currency_exchange/sell_wt/disclaimer')?>
        <div class="clear"></div>
    </div>
</div>
</div>
<?= $page_bottom ?>
