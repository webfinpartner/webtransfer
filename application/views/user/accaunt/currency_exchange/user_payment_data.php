<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<?
if( !$notAjax )
{
    ?>
    <h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>

<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>

<script type="text/javascript">
    var $ps_curency_array_currency_id = $.parseJSON('<?=  Currency_exchange_model::all_currencys_to_js() ?>');
//    var curency_str = {9840:'<?=_e('currency_symbol_9840')?>', 840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
    var curency_str = $ps_curency_array_currency_id;
    var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";
    var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');
</script>

<script async type="text/javascript" src="/js/user/currency_exchange.js"></script>




<?php // $this->load->view('user/accaunt/currency_exchange/blocks/form.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems)); ?>
<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
<?php $this->load->view($form_folder.'form_open.php'); ?>

<?php $this->load->view($form_folder.'form_payments_select.php', array('payment_systems_groups'=>$payment_systems_groups, 'payment_systems'=>$payment_systems, 'buy_payment_systems' => $buy_payment_systems)); ?>

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



<button class="button" type="button" id="out_submit" name="submit">Оформить</button>

<?php $this->load->view('user/accaunt/currency_exchange/blocks/out_submit.php'); ?>
<?php // $this->load->view('user/accaunt/blocks/renderCodeProtection_window.php', $par); ?>

<div class="clear"></div>
<div class="fcwidget">
    <div class="fcwidgetvn">
<!--        <div class="fcwtitle">
            <div class="fcwtitlevn">
                Приветствуем на сайте обменного пункта!					
            </div>
        </div>
        <p>Наш On-line сервис предназначен для тех, кто хочет быстро, безопасно и по выгодному курсу обменять такие 
            виды электронных валют: Webmoney, Яндекс. Деньги, Приват24, OKPAY, Webtransfer,  так же в ближайшем будущем 
            Qiwi, Сбербанк, Visa/Master Card, MoneyGram, Swift.<br>
            Счета в платежных системах должны принадлежать одному и тому же лицу.<br><br>
            В рамках проекта будет действовать программа лояльности, накопительная скидка и партнерская программа, 
            воспользовавшись преимуществами которых, вы сможете совершать обмен электронных валют на более выгодных 
            условиях.<br><br>
            Наш пункт обмена электронных валют – система, созданная на базе современного программного обеспечения и 
            содержащая весь набор необходимых функций для удобной и безопасной конвертации наиболее распространенных 
            видов электронных денег.  Мы делаем все возможное, чтобы ваши впечатления от нашего сервиса были только 
            благоприятными.
        </p>-->
        <br/>
        <br/>
        <p><strong>УВЕДОМЛЕНИЕ ОБ ОТВЕТСТВЕННОСТИ</strong></p>
        <br/>
        <p>
            Webtransfer гарантирует сделки проводимые в рамках площадки по обмену валют P2P 

            в пределах активов на счету одной из сторон обмена. Гарантия выражена в форме 

            Webtransfer USD. Если вы сделали все по правилам и ваш контрагент не выполнил 

            своих обязательств в рамках сделки, вы будете иметь право на компенсацию в виде 

            обеспечения на счету вашего контрагента в Webtransfer.
        </p>
        <div class="clear"></div>
    </div>
</div>
</div>

<?= $page_bottom ?>