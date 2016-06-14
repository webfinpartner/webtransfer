<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
 <?php $stat = getStatistic();
//print_r($stat->today);
//print_r($stat->yesterday);
//
//stdClass Object
//(
//[id] => 45
//[date] => 2014-08-11
//[avg_rate] => 0.98
//[avg_summa] => 63.64
//[avg_period] => 7.33
//[total_deals] => 902.00
//[total_volume] => 57400.00
//[public_deals] => 2706.00
//[public_volume] => 172200.00
//[new_users] => 1520
//)
//stdClass Object
//(
//[id] => 36
//[date] => 2014-08-10
//[avg_rate] => 1.33
//[avg_summa] => 69.25
//[avg_period] => 8.91
//[total_deals] => 899.00
//[total_volume] => 60900.00
//[public_deals] => 3115.00
//[public_volume] => 215713.00
//[new_users] => 1924
//)

    if( !isset($stat) || empty($stat) ) {
        $stat = new stdClass();
    }
    if( !isset( $stat->today ) || empty( $stat->today )){
        $stat->today = new stdClass();

        $stat->today->public_deals = 0;
        $stat->today->public_volume = 0;
        $stat->today->avg_period = 0;
        $stat->today->avg_summa = 0;
        $stat->today->avg_rate = 0;
        $stat->today->certificats = 0;
        $stat->today->certificats_summ = 0;
        $stat->today->new_users = 0;
        $stat->today->users = 0;

        $stat->today->avg_period = 0;
    }
    if( !isset( $stat->yesterday ) || empty( $stat->yesterday )){
        $stat->yesterday = new stdClass();

        $stat->yesterday->public_deals = 0;
        $stat->yesterday->public_volume = 0;
        $stat->yesterday->avg_period = 0;
        $stat->yesterday->avg_summa = 0;
        $stat->yesterday->avg_rate = 0;
        $stat->yesterday->certificats = 0;
        $stat->yesterday->certificats_summ = 0;
        $stat->yesterday->new_users = 0;
        $stat->yesterday->users = 0;

        $stat->yesterday->avg_period = 0;
    }
?>

<style>
    .zifra {font-size: 32px; line-height: 30px; color: rgb(99, 124, 151);}
    .odd {padding:10px 5px;margin-bottom:5px;}
    .left {float:left;font-size:12px;}
    .right {float:right;font-size:12px;}
</style>
<div style="width:191px;border: 1px solid rgb(204, 204, 204);padding-bottom:10px;">
<div style="padding:10px;font-weight:bold;text-align:center;">Статистика на <?=date_formate_view(date('Y-m-d'))?></div>
<div class="odd">
    <span class="left">кол-во сделок (шт.)</span>
    <span class="right"><?=price_format_view($stat->today->public_deals)?></span>
</div>

<div class="odd">
    <span class="left">сумма сделок</span>
    <span class="right">$ <?=price_format_view($stat->today->public_volume)?></span>
</div>

<div class="odd">
    <span class="left">средний срок (дн.)</span>
    <span class="right"><?=$stat->today->avg_period?></span>
</div>
<div class="odd">
<span class="left">средняя сумма</span>
<span class="right">$ <?=price_format_view($stat->today->avg_summa)?></span>
</div>
<div class="odd">
<span class="left">средняя ставка</span>
<span class="right"><?=$stat->today->avg_rate?>%</span>
</div>
<div class="odd">
<span class="left">продано сертификатов</span>
<span class="right"><?=price_format_view($stat->today->certificats)?></span>
</div>
<div class="odd">
<span class="left">продано на сумму</span>
<span class="right">$ <?=price_format_view($stat->today->certificats_summ)?></span>
</div>
<div class="odd">
<span class="left">новых участников</span>
<span class="right"><?=price_format_view($stat->today->new_users)?></span>
</div>
<div class="odd">
<span class="left">всего участников</span>
<span class="right"><?=price_format_view($stat->today->users)?></span>
</div>

</div>
<br/>
<div style="width:191px;border: 1px solid rgb(204, 204, 204);padding-bottom:10px;">

<div style="padding:10px;font-weight:bold;text-align:center;">Статистика на <?=date_formate_view(date("Y-m-d", time() - 60 * 60 * 24))?></div>
<div class="odd">
<span class="left">кол-во сделок (шт.)</span>
<span class="right"><?=price_format_view($stat->yesterday->public_deals)?></span>
</div>
<div class="odd">
<span class="left">сумма сделок</span>
<span class="right">$ <?=price_format_view($stat->yesterday->public_volume)?></span>
</div>
<div class="odd">
<span class="left">средний срок (дн.)</span>
<span class="right"><?=$stat->yesterday->avg_period?></span>
</div>
<div class="odd">
<span class="left">средняя сумма</span>
<span class="right">$ <?=price_format_view($stat->yesterday->avg_summa)?></span>
</div>
<div class="odd">
<span class="left">средняя ставка</span>
<span class="right"><?=$stat->yesterday->avg_rate?>%</span>
</div>
<div class="odd">
<span class="left">продано сертификатов</span>
<span class="right"><?=price_format_view($stat->yesterday->certificats)?></span>
</div>
<div class="odd">
<span class="left">продано на сумму</span>
<span class="right">$ <?=price_format_view($stat->yesterday->certificats_summ)?></span>
</div>
<div class="odd">
<span class="left">новых участников</span>
<span class="right"><?=price_format_view($stat->yesterday->new_users)?></span>
</div>
<div class="odd">
<span class="left">всего участников</span>
<span class="right"><?=price_format_view($stat->yesterday->users)?></span>
</div>

</div>