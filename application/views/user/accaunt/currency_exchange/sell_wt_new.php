<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<?
if( !$notAjax )
{
    ?>
<h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>
<span async style="font-size: 12px; margin-left: 185px; margin-top: -40px; position: absolute;">(BETA)</span>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange_sms.css"/>



<script type="text/javascript">
    var $ps_curency_array_currency_id = $.parseJSON('<?=  Currency_exchange_model::all_currencys_to_js() ?>');
//    var curency_str = {9840:'<?=_e('currency_symbol_9840')?>', 840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
    var curency_str = $ps_curency_array_currency_id;
    var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";
    var get_cards_user = "<?=site_url('account/currency_exchange/ajax/get_cards_user')?>";
    var check_visa_money_url = "<?=site_url('account/currency_exchange/ajax/check_visa_card_money')?>";

    var save_user_payments_data_url = "<?=site_url('account/currency_exchange/ajax/save_user_data_payment')?>";
    var prefund_save_card_from = "<?=site_url('account/currency_exchange/ajax/prefund_save_card_from')?>";
    var save_user_new_payments_data_url ="<?=site_url('account/currency_exchange/ajax/save_user_data_new_payment')?>";
    var ajax_step_3_new_sell_url ="<?=site_url('account/currency_exchange/ajax/step_3_new_sell')?>";
    var ajax_step_3_new_sell_check_payment_limit_url ="<?=site_url('account/currency_exchange/ajax/step_3_new_sell_check_payment_limit')?>";
    var get_country_bank_data_url ="<?=site_url('account/currency_exchange/ajax/get_country_bank_data')?>";
    var get_add_bank_in_payment_system_url ="<?=site_url('account/currency_exchange/ajax/get_add_bank_in_payment_system')?>";
    var $ajax_check_user_for_payout ="<?=site_url('account/currency_exchange/ajax/check_user_for_payout')?>";
    var $url_get_ps = '<?=site_url('account/currency_exchange/ajax/get_ps')?>';
    var set_user_payment_data_url = "<?=site_url('account/currency_exchange/ajax/set_user_payment_data')?>";
    
//    var $ps_fee_percentage = <?//=$payment_systems['wt']->fee_percentage?>;
//    var $ps_fee_percentage_add = <?//=$payment_systems['wt']->fee_percentage_add?>;
// TODO похоже это уже не нужно
//    var ps_fee_data = {
//        'fee_percentage': <?//=$payment_systems['wt']->fee_percentage?>,
//        'fee_percentage_add': <?//=$payment_systems['wt']->fee_percentage_add?>,
//        'fee_min': <?//=$payment_systems['wt']->fee_min?>,
//        'fee_max': <?//=$payment_systems['wt']->fee_max?>,
//        };


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
    var $translate_js_score = '<?=_e('Счет');?>';
    var $translate_js_enter_amount = '<?= _e('Укажите сумму') ?>';
    var $translate_js_choose_ps = '<?= _e('Выберите платежную систему') ?>';
    
    var security = '<?=$security ?>';
    var $new_sell_glob = true;
//   var $selectbox_to_wt_sell_arr = [<?php echo implode(',',$payout_limit_arr)?>];
    var $selectbox_to_wt_sell_arr = $.parseJSON('<?=json_encode($payout_limit_arr)?>');
    var $selectbox_to_wt_buy_arr = $.parseJSON('<?=json_encode($payout_limit_arr_buy)?>');

    var $is_test_user = '<?=$is_test_user?>';
    var $selectbox_to_wt_sell_arr_skore = ['Платежный счет(CREDS)','C-CREDS'];
    var root_currency = JSON.parse('{"wt":"9840","wt_c_creds":"9842","wt_heart":"9841","wt_debit_usd":"840"}');
    
    // var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');
    var $translate_js_error_no_selec_ps = '<?=_e('Не выбрана валюта');?>';
    var $translate_js_error_no_selec_currency = '<?=_e('Не выбранна');?>';
   
   var $is_test_user = '<?=$is_test_user?>';
//   var $ps_curency_array_currency_id = {
//            9840:'<?=_e('currency_id_9840')?>',
//            840:'<?=_e('currency_id_840')?>',
//            643:'<?=_e('currency_id_643')?>',
//            980:'<?=_e('currency_id_980')?>',
//            978:'<?=_e('currency_id_978')?>',
//            974:'<?=_e('currency_id_974')?>',
//            398:'<?=_e('currency_id_398')?>',
//            9900:'<?=_e('currency_id_9900')?>',
//            826:'<?=_e('currency_id_826')?>',
//            124:'<?=_e('currency_id_124')?>',
//            203:'<?=_e('currency_id_203')?>',
//            156:'<?=_e('currency_id_156')?>'
//        };
</script>
<?php
//$lang['currency_id_840'] = "USD";
//    $lang['currency_id_643'] = "RUB";
//    $lang['currency_id_980'] = "UAH";
//    $lang['currency_id_978'] = "EUR";
//    $lang['currency_id_974'] = "BYR";
//    $lang['currency_id_398'] = "KZT";
//    $lang['currency_id_9900'] = "BTC";
//    $lang['currency_id_826'] = "GBP";
//    $lang['currency_id_124'] = "CAD";
//    $lang['currency_id_124'] = "CAD";
//    $lang['currency_id_203'] = "CZK";
//    $lang['currency_id_156'] = "CNY";
//    $lang['currency_id_9900'] = "Crypto";
?>

<?php // pre($payment_systems['wt']); ?>
<script  type="text/javascript" src="/js/user/sms_module.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange_visa.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange_new_sell.js"></script>

<script src="/js/user/select2-3.4.1/select2.js"></script>

<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/css/select21.css"/>
<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/select2-bootstrap.css"/>

<?php // if( in_array( $user->id_user, array( 500733, 500757, 82938815, 91802698 ) )  ){?>
    <style>        
        #paymentStripe {
            font: 14px Arial;
            margin: 10px 0px 25px;
        }
        #paymentStripeCenter {
            font-size: 12px;
            font-family: Arial;
            margin: 10px 0 10px auto;
            width: 85%;
        }
        .pastStripe,
        .noRightSection.udboDataWidth .pastStripe {
            float: left;
            width: 33%;
            text-align: center;
            height: 32px;
            color: #ccc;
            background: url(/images/currency_exchange/stripe/past_stripe_bckg.png) repeat-x left top;
        }
        .currentStripe,
        .noRightSection.udboDataWidth .currentStripe {
            float: left;
            width: 33%;
            text-align: center;
            height: 32px;
            color: #449d2f;
            background: url(/images/currency_exchange/stripe/current_stripe_bckg.png) repeat-x left top;
            padding: 9px 0;
        }
        .futureStripe,
        .noRightSection.udboDataWidth .futureStripe {
            float: left;
            width: 33%;
            text-align: center;
            height: 32px;
            color: #718695;
            background: url(/images/currency_exchange/stripe/future_stripe_bckg.png) repeat-x left top;
        }
        .pastStripeLeftCircle {
            background: url(/images/currency_exchange/stripe/past_stripe.png) no-repeat left bottom;
            padding: 10px 0 10px 9px;
        }
        .currentStripeLeftCircle {
            width: 13px;
            height: 27px;
            background: url(/images/currency_exchange/stripe/left_current_stripe.png) no-repeat left bottom;
            float: left;
        }
        .currentStripeRightCircle {
            width: 13px;
            height: 27px;
            background: url(/images/currency_exchange/stripe/right_current_stripe.png) no-repeat right bottom;
            float: right;
        }
        .currentStripe .currentStripeText {
            padding-top: 2px;
        }
        .futureStripeRightCircle {
            background: url(/images/currency_exchange/stripe/future_stripe.png) no-repeat right bottom;
            padding: 10px 9px 10px 0;
        }
        .futureStripe .stepNamePos {
            margin: 0 -5px 0 -6px;
        }
        #tabs ul li.longPaymentTab {
            width: 260px;
        }
        .threestepStripe .futureStripe,
        .threestepStripe .currentStripe,
        .threestepStripe .pastStripe {
            width: 213px;
        }
        .sixstepStripe {
            margin: 10px -2px!important;
        }
        .sixstepStripe .futureStripe,
        .sixstepStripe .currentStripe,
        .sixstepStripe .pastStripe {
            width: 109px;
        }
        .sixstepStripe .currentStripe .currentStripeText {
            font-size: 12px;
            line-height: 12px;
            margin-top: -11px;
        }
        .sixstepStripe .futureStripeRightCircle,
        .sixstepStripe .pastStripeLeftCircle {
            font-size: 12px;
            line-height: 12px;
            height: 26px;
            margin-top: -11px;
        }
        .dblLineStripe .currentStripe,
        .dblLineStripe .futureStripe,
        .dblLineStripe .pastStripe {
            position: relative;
        }
        .dblLineStripe .currentStripe .currentStripeText {
            position: absolute;
            left: 0;
            top: -7px;
            width: 100%;
        }
        .dblLineStripe .pastStripeLeftCircle,
        .dblLineStripe .futureStripeRightCircle {
            margin-top: -11px;
            height: 31px;
        }
        .dblLineStripe .futureStripeRightCircle {
            padding: 6px 11px 10px 0;
        }
        .dblLineStripe .pastStripeLeftCircle {
            padding: 6px 0 10px 7px;
        }
        
        
        .left_container_payment_buy_text, .right_container_payment_buy_text{
/*            float: right;
            margin-top: -500px;*/
        }
        
        .left_container_payment_buy_text .quick_selected_payment_buy_text, 
        .right_container_payment_buy_text .quick_selected_payment_sell_text{
            display: none;
        }
        
        .left_container_payment_buy_text.step_2
        {
            display: none;
        }
        
        .previous_button{
            float: left;
            width: 150px;
            margin-top: 0px;
            display: none;
        }
        
        .next_button{
            float: right;
            width: 150px;
            margin-top: 0px;
            
        }
        
        .div_sell_payment_systems{
            float:left;
        }
        
        .div_buy_payment_systems{
            float: left;
            display: none;
        }
        
        .homeobmentable.new_sell_fee_container{
            float: right;
            margin-right: 10px;
            width: 94%;
        }
        
        .new_sell_payment_sums{
            float: right;
            /*margin-top: -500px;*/
            
        }
        
        .new_sell_payment_sums.left_container_payment_buy_text, 
        .new_sell_payment_sums.right_container_payment_buy_text {
            display: none;
            /*width: 100%;*/
        }
        .new_sell_payment_sums .lefthomecol,
        .new_sell_payment_sums .righthomecol{
            width: 100%;
            /*border-top: 1px solid #ebebeb;*/
        }
        
        .new_sell_payment_sums .lefthomecol .sell_fee_and_ammount,
        .new_sell_payment_sums .righthomecol .sell_fee_and_ammount{
            border-top: 1px solid #ebebeb;
        }
        
        /*.homeobmentable*/
/*        .homeobmentable.new_sell_fee_container.righthomecol{
            width: 100%;
            
        }
        .new_sell_fee_container.lefthomecol{
            width: 100%;
        }*/

        .step_3{
            display: none;
        }
        
        .form_table_step_3_new_sell{
            margin-bottom: 20px;
        }
        
        .form_table_step_3_new_sell .loading-gif{
            display: block;
            margin: 0 auto; 
        }
        
        #out_submit{
            display: none;
            /*margin-left: 93px;*/
            float: right;
            margin-top: 0;
        }
        
        
        #paymentStripe .step_3,
        #paymentStripe .step_2{
            display: none;
        }
        
        .form_select{
            background: #fff none repeat scroll 0 0;
            border: 1px solid #ddd;
            box-shadow: 0 0 0 2px #f4f4f4;
            color: #656565;
            font-family: Arial,Helvetica,sans-serif;
            font-size: 11px;
            /*height: 14px;*/
            padding: 2px 6px 6px;
            /*width: calc(100% - 12px);*/
            
            width: 180px;
            margin: 0 6px;            
            height: 34px;
            text-align: center;
            font-size: 14px;
            line-height: 14px;
        }
        
        .form_select.for_test_users{
           height: 25px; 
        }
        .input_container_div.for_test_user>div{
            width: 70px !important;
        }
        
        .overall_step_summ{
            margin-top: 11px;
            position: absolute;
            width: 222px;
            color: red;
        }
        
        
    </style>


<div class="buttons_bottom">    
    
<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
    
    
<?php $this->load->view($form_folder.'form_top_line_new_sell.php'); ?>
    
<?php $this->load->view($form_folder.'form_open.php'); ?>

<?php // $this->load->view($form_folder.'form_payments_select.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems, 'save_user_data' => true)); ?>
<?php $this->load->view($form_folder.'form_payments_select_new_sell.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems, 'save_user_data' => true)); ?>

<?php // $this->load->view($form_folder.'form_selected_payment_system_new_sell.php'); ?>

<?php 
$input_array = array(
    'sell_sum_amount' => $sell_sum_amount,
    'sell_sum_fee' => $sell_sum_fee,
    'sell_sum_total' => $sell_sum_total,
    'buy_amount_down' => $buy_amount_down,
    'buy_amount_up' => $buy_amount_up,
); 
?>



<?php // $this->load->view($form_folder.'form_inputs_sell_new_sell.php', $input_array); ?>
<?php // $this->load->view($form_folder.'form_table_step_3_new_sell.php'); ?>
    <div class="form_table_step_3_new_sell step_3">
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
        <div></div>
    </div>
<?php $this->load->view($form_folder.'form_close.php'); ?>


<!--<button class="button" type="button" id="out_submit" name="submit"><?php // echo _e('currency_exchange/sell_wt/issue'); ?></button>-->

<?php $this->load->view('user/accaunt/currency_exchange/blocks/out_submit.php'); ?>
<?php // $this->load->view('user/accaunt/blocks/renderCodeProtection_window.php', $par); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_error.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_notification.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_helper.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/currencys_list_container.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_ps_input_container.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/modal_visa_informer.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/modal_visa_prefund.php'); ?>
<div class="clear"></div>
<div class="fcwidget">
    
    
    <div class="fcwidgetvn">

        <br/>
        <br/>
        <?php // echo _e('currency_exchange/sell_wt/disclaimer'); ?>

        <div class="clear"></div>
    </div>
</div>
</div>

<?= $page_bottom ?>
    <div id="out_send_window_sms" class="popup_window" style="z-index:99999;">
        <div onclick="$(this).parent().hide('slow');" class="close"></div>
        <h2><?= _e('Двух этапная авторизация') ?></h2>
        <div id="sms-code" class="formRow padding10-40" style="border-bottom:0; overflow: auto;О">
            <label class="label" style="float: left; margin-top: 4px;"><?= _e('Код подтверждения'); ?></label>
            <div class="formRight" style="width: 64% !important;float: right;">
                <div class="sms-content" style="width: 78%;">
                    <input class="form_input" id="code_sms" type="text" value="" style="height: 17px;width: 130px; margin-right: 10px;"/>
                    <a class="but blueB ml10 send_code_link right" href="#" style="margin-top: 4px"><?= _e('Запрос СМС'); ?></a>
                </div>
            </div>
            <div class="res-message"></div>
            <img class="loading-gif" style="display: none" src="/images/loading.gif"/>
        </div>
        <a class="button narrow" >Ok</a>
    </div>
<script>
    $(function(){
        load_visa_cards_number();
        // load_visa_cards_full_numbers();

         $('#out_send_window_sms .send_code_link').unbind('click');
         $('#out_send_window_sms .send_code_link').off();
         $(document).on('click','#out_send_window_sms .send_code_link', function(){
             var $this = $(this);

             if( $(this).hasClass('closed') ) return false;
             $(this).addClass('closed');

             $('#out_send_window_sms').data('count',1);
             console.log('on click');
             window.purpose = 'save_p2p_payment_data';
    
             d_sendSms(function(){
                 $this.removeClass('closed');
                 window.purpose = 'withdrawal_standart_credit';
             }, '/account/ajax_user/sms_request','#out_send_window_sms');
             return false;
         });

         $(document).on('click','#out_send_window_sms .button', function() {
             //$('#out_send_window_sms .send_code_link').css('display', 'block');
             var wrongs = 0,
             strFun = $('#out_send_window_sms').data('call-back');

             wrongs = d_checkField('#code_sms', wrongs);

             if (wrongs)
                 return false;

             $('#out_send_window_sms').hide('slow');
             //console.log( strFun );
             if( strFun !== undefined ) strFun();
             window.purpose = 'withdrawal_standart_credit';
         });

         $("#out_send_window_sms").keydown(function(e) {//console.log("sms",e.which);
             switch(e.which) {
                 case 13: if($("#out_send_window_sms").is(':visible'))
                             $("#out_send_window_sms .button").trigger("click");// left
                 break;
                 case 27: $('.out_send_window_sms').hide();// left
                 break;

                 default: return; // exit this handler for other keys
             }
             e.preventDefault(); // prevent the default action (scroll / move caret)
         }); 
    });
</script>
