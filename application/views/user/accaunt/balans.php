<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>
<?}else{?>
<br/><center>
<iframe id='a0396f9b' name='a0396f9b' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=37&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=ad0ac50b&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=37&amp;cb={random}&amp;n=ad0ac50b&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
<br/></center>
<?}?>
<?
//$incomeSumma = $incomePribil = $otchisleniya = $outcomeSumma = $outcomePercent = 0;
//foreach ($list as $item) {
//
//    if ($item->type == 2) {
//        $incomeSumma += $item->summa;
//        $incomePribil += $item->income;
//        if ($item->garant == 0) {
//            $otchisleniya += $item->income * 0.10;
//        } else if ($item->garant == 1) {
//            $otchisleniya += $item->income * ((garantPercent($item->time) / 100));
//        }
//    } else if ($item->type == 1) {
//        $outcomeSumma += $item->summa;
//        $outcomePercent += $item->income;
//    }
//}
?>
<!--<div class="table">
    <div class="rower"><div class="td1"><?  _e('accaunt/balans_1') ?></div><div class="td2">$ <?  //price_format_double($incomeSumma) ?></div></div>
    <div class="rower"><div class="td1"><?  _e('accaunt/balans_2') ?></div><div class="td2">$ <?  //price_format_double($incomePribil) ?></div></div>
    <div class="rower"><div class="td1"><?  _e('accaunt/balans_3') ?></div><div class="td2">- $<?  //price_format_double($otchisleniya) ?></div></div>
    <div class="rower bg"><div class="td1"><?  _e('accaunt/balans_4')?></div><div class="td2">$ <?  //price_format_double($incomeSumma + $incomePribil - $otchisleniya) ?></div></div>

    <br/>
    <div class="rower"><div class="td1"><? _e('accaunt/balans_5')?></div><div class="td2">- $<?  //price_format_double($outcomeSumma) ?></div></div>
    <div class="rower"><div class="td1"><? _e('accaunt/balans_6')?></div><div class="td2">- $<?  //price_format_double($outcomePercent) ?></div></div>
    <div class="rower bg"><div class="td1"><? _e('accaunt/balans_7')?></div><div class="td2">- $<?  //price_format_double($outcomeSumma + $outcomePercent) ?></div></div>
    <br/>

    <?
//    $moneyOut = $moneyIn = 0;
//    foreach ($money as $item) {
//        if ($item->status == 3)
//            $moneyOut += $item->summa;
//        else if ($item->status == 1)
//            $moneyIn += $item->summa;
//    }
    ?>
    <div class="rower">
        <div class="td1"><? _e('accaunt/balans_8')?></div>
        <div class="td2">$ <? //price_format_double($moneyIn) ?></div>
    </div>
    <div class="rower">
        <div class="td1"><? _e('accaunt/balans_9')?></div>
        <div class="td2">- $ <? //price_format_double($moneyOut) ?></div>
    </div>
    <div class="rower bg">
        <div class="td1"><? _e('accaunt/balans_10')?></div>
        <div class="td2">$ <?  //price_format_double($moneyIn - $moneyOut) ?></div>
    </div>
    <br/>
    <div class="rower bg"><div class="td1"><? _e('accaunt/balans_11')?></div><div class="td2"> $ <? //price_format_double(($incomeSumma + $incomePribil - $otchisleniya) - ($outcomeSumma + $outcomePercent) + ($moneyIn - $moneyOut)); ?></div></div>
</div>-->

<!--<h3 style="color: red;margin: 20px 0 30px;"><? _e('accaunt/balans_12')?></h3>-->
<div class="table">
    <div class="block">
        <div class="rower bold"><div class="td1"><?=_e('accaunt/balans_13')?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_14')?></div><div class="td2">$ <?=price_format_double($accaunt_header['payment_account'], TRUE)?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_15')?></div><div class="td2">$ <?=price_format_double($accaunt_header['bonuses'])?></div></div>
		        <div class="rower"><div class="td1"><?=_e('1.3. Партнерский счет')?></div><div class="td2">$ <?= price_format_double($accaunt_header['partner_funds']); ?></div></div>
        <div class="rower bg"><div class="td1"><?=_e('accaunt/balans_16')?></div><div class="td2">$ <?=price_format_double($accaunt_header['payment_account']+$accaunt_header['partner_funds']+$accaunt_header['bonuses'])?></div></div>

        <div class="rower"><div class="td1"><?=_e('accaunt/balans_17')?></div><div class="td2">$ <?= price_format_double($accaunt_header['investments_garant']+$accaunt_header['investments_garant_bonuses'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_18')?></div><div class="td2">$ <?=price_format_double($accaunt_header['my_investments_garant_percent'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_19')?></div><div class="td2">$ <?= price_format_double($accaunt_header['investments_standart'])?></div></div>
        <div class="rower bg white-line"><div class="td1"><?=_e('accaunt/balans_20')?></div><div class="td2">$ <?= price_format_double($accaunt_header['investments_standart']+$accaunt_header['investments_garant']+$accaunt_header['investments_garant_bonuses']+$accaunt_header['my_investments_garant_percent'])?></div></div>
        <div class="rower bg"><div class="td1"><?=_e('accaunt/balans_21')?></div><div class="td2">$ <?= price_format_double($accaunt_header['total_assets']) ?></div></div>
    </div>

    <div class="block">
        <div class="rower bold"><div class="td1"><?=_e('accaunt/balans_22')?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_23')?></div><div class="td2">$ <?= price_format_double($accaunt_header['loans'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_24')?></div><div class="td2">$ <?= price_format_double($accaunt_header['loans_percentage'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_25')?></div><div class="td2">$ <?= price_format_double($accaunt_header['bonuses']+$accaunt_header['investments_garant_bonuses'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_26')?></div><div class="td2">$ <?= price_format_double( $accaunt_header['total_arbitrage'] )?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_42')?></div><div class="td2">$ <?= price_format_double( $accaunt_header['overdue_garant_interest'], TRUE )?></div></div>
        <div class="rower bg"><div class="td1"><?=_e('accaunt/balans_27')?></div><div class="td2">$ <?= price_format_double( $accaunt_header['all_liabilities']+$accaunt_header['bonuses']+$accaunt_header['investments_garant_bonuses'])?></div></div>
    </div>

    <div class="block">
        <div class="rower bold"><div class="td1"><?=_e('accaunt/balans_28')?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_29')?></div><div class="td2">$ <?= price_format_double($accaunt_header['total_future_income'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_30')?></div><div class="td2">$ <?=price_format_double($accaunt_header['soon'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_31')?></div><div class="td2">$ <?=price_format_double($accaunt_header['future_interest_payout'])?></div></div>
        <div class="rower"><div class="td1"><?=_e('accaunt/balans_32')?></div><div class="td2">$ <?=price_format_double($accaunt_header['future_income'])?></div></div>

    </div>
    <div class="block">
        <div class="rower bold bg">
            <div class="td1"><?=_e('accaunt/balans_33')?></div>
            <div class="td2">$ <?= price_format_double( $accaunt_header['balance'] )?></div>
        </div>
    </div>
    <div class="block">
        <div class="rower bold">
            <div class="td1"><?=_e('accaunt/balans_34')?></div>
            <div class="td2"></div>
        </div>

        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_35')?></div>
            <div class="td2">$ <?=price_format_double($accaunt_header['total_garant_loans'])?></div>
        </div>
        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_36')?><span class="phone_format" style="color: #00F;cursor:pointer;" onclick="$('#partner_network').show('slow');  return  false;">(?)</span>:</div>
            <div class="td2">$ <?= price_format_double($accaunt_header['partner_network_value'])?></div>
        </div>

        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_36_3')?><span class="phone_format" style="color: #00F;cursor:pointer;" onclick="$('#social-integration-value').show('slow');  return  false;">(?)</span>:</div>
            <div class="td2">$ <?= price_format_double($accaunt_header['social_integration_value'])?></div>
        </div>

        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_37')?></div>
            <div class="td2">$ <?= price_format_double( $accaunt_header['arbitrage_collateral'])?></div>
        </div>
        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_38')?></div>
            <div class="td2">$ <?= price_format_double( $accaunt_header['max_loan_available'], TRUE )?></div>
        </div>
        <div class="rower">
            <div class="td1"><?=_e('accaunt/balans_38_2')?></div>
            <div class="td2">$ <?= price_format_double( $accaunt_header['top_up_sum_overflow'], TRUE )?></div>
        </div>
    </div>
</div>
<center>
<A href="<?=site_url('partner/my_balance')?>" class="but blueB"><?=_e('new_3')?></a></center>
<br/><br/>
<div class="popup_window" id="partner_network">
    <div class="close" onclick="$(this).parent().hide(); return 0;"></div>
    <h2><?=_e('accaunt/balans_39')?></h2>
    <?=_e('accaunt/balans_40')?>
    <a class="button narrow" onclick="$(this).parent().hide(); return 0;"><?=_e('accaunt/balans_41')?></a>
</div>
</div>

<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
    <iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</div>
<?}?>