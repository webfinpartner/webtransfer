<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<?
if( !$notAjax )
{
    ?>
<h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>
<span style="font-size: 12px; margin-left: 185px; margin-top: -40px; position: absolute;">(BETA)</span>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange_sms.css"/>



<script type="text/javascript">
    var $ps_curency_array_currency_id = JSON.parse('<?=  Currency_exchange_model::all_currencys_to_js() ?>');
//    var curency_str = {9840:'<?=_e('currency_symbol_9840')?>', 840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
    var curency_str = $ps_curency_array_currency_id;
    var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";
    var save_user_payments_data_url = "<?=site_url('account/currency_exchange/ajax/save_user_data_payment')?>";
    var save_user_new_payments_data_url ="<?=site_url('account/currency_exchange/ajax/save_user_data_new_payment')?>";
    var $ajax_check_user_for_payout ="<?=site_url('account/currency_exchange/ajax/check_user_for_payout')?>";

    
//    var $ps_fee_percentage = <?=$payment_systems['wt']->fee_percentage?>;
//    var $ps_fee_percentage_add = <?=$payment_systems['wt']->fee_percentage_add?>;
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
    
    
    
    var security = '<?=$security ?>';
    var root_currency = JSON.parse('{"wt":"9840","wt_c_creds":"9842","wt_heart":"9841","wt_debit_usd":"840"}');
   
    // var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');
</script>
<?php // pre($payment_systems['wt']); ?>
<script async type="text/javascript" src="/js/user/sms_module.js"></script>
<script  type="text/javascript" src="/js/user/currency_exchange.js"></script>

<script src="/js/user/select2-3.4.1/select2.js"></script>

<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/css/select21.css"/>
<link rel="stylesheet" type="text/css" href="/js/user/select2-3.4.1/select2-bootstrap.css"/>

<?php if( in_array( $user->id_user, array( 500733, 500757 ) )  ){?>
    <style>        
        #paymentStripe {
            font: 12px Arial;
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
            width: 20%;
            text-align: center;
            height: 32px;
            color: #ccc;
            background: url(/images/currency_exchange/stripe/past_stripe_bckg.png) repeat-x left top;
        }
        .currentStripe,
        .noRightSection.udboDataWidth .currentStripe {
            float: left;
            width: 20%;
            text-align: center;
            height: 32px;
            color: #449d2f;
            background: url(/images/currency_exchange/stripe/current_stripe_bckg.png) repeat-x left top;
            padding: 8px 0;
        }
        .futureStripe,
        .noRightSection.udboDataWidth .futureStripe {
            float: left;
            width: 20%;
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
            width: 10px;
            height: 27px;
            background: url(/images/currency_exchange/stripe/left_current_stripe.png) no-repeat left bottom;
            float: left;
        }
        .currentStripeRightCircle {
            width: 10px;
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
        
    </style>

    <div id="paymentStripe" class="">
        <div class="currentStripe">
           <div class="currentStripeRightCircle">&nbsp;</div>
           <div class="currentStripeLeftCircle">&nbsp;</div>
           <div class="currentStripeText">
              <div class="stepNamePos">
                 отдаю
              </div>
           </div>
        </div>
<!--        <div class="pastStripe">
           <div class="pastStripeLeftCircle">
              <div class="stepNamePos">
                 отдаю
              </div>
           </div>
        </div>-->
        
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">выбор сумм</div>
           </div>
        </div>
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">получаю</div>
           </div>
        </div>
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">
                 выбор сумм
              </div>
           </div>
        </div>
        <div class="futureStripe">
           <div class="futureStripeRightCircle">
              <div class="stepNamePos">
                 подтверждение
              </div>
           </div>
        </div>
        <div class="clear"></div>
     </div>

<?php }?>

<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
<?php $this->load->view($form_folder.'form_open.php'); ?>

<?php $this->load->view($form_folder.'form_payments_select.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems, 'save_user_data' => true)); ?>

<?php $this->load->view($form_folder.'form_selected_payment_system.php'); ?>

<?php 
$input_array = array(
    'sell_sum_amount' => $sell_sum_amount,
    'sell_sum_fee' => $sell_sum_fee,
    'sell_sum_total' => $sell_sum_total,
    'buy_amount_down' => $buy_amount_down,
    'buy_amount_up' => $buy_amount_up,
); 
?>



<?php $this->load->view($form_folder.'form_inputs_sell.php', $input_array); ?>

<?php $this->load->view($form_folder.'form_close.php'); ?>



<button class="button" type="button" id="out_submit" name="submit"><?php echo _e('currency_exchange/sell_wt/issue'); ?></button>

<?php $this->load->view('user/accaunt/currency_exchange/blocks/out_submit.php'); ?>
<?php // $this->load->view('user/accaunt/blocks/renderCodeProtection_window.php', $par); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_error.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_save_notification.php'); ?>
<?php $this->load->view('user/accaunt/currency_exchange/blocks/user_payment_helper.php'); ?>

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
