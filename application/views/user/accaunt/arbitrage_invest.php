<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?><br/>
<center>
<iframe id='acbab7bf' name='acbab7bf' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=26&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a1615ff4&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=26&amp;cb={random}&amp;n=a1615ff4&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</center>
<br/>
<p>
<?=_e('arbitrage_text')?>

</p>
<br/><Br/><center><a id="offer" class="but agree" href="/upload/arbitrage-<?=_e('lang')?>.pdf"><?=_e('Скачать оферту')?></a></center>
<br/><br/>
<table cellspacing="0" class="payment_table">
    <thead>
        <tr>
            <th class="payment_partner"><?=_e('new_75')?></th>
            <th class="payments_period"><?=_e('new_76')?></th>
            <th class="payments_limitations"><?=_e('new_77')?></th>
            <th class="payments_description"><?=_e('new_78')?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="images"><img title="<?=_e('new_79')?>" alt="<?=_e('new_79')?>" src="/images/bank1.png" style="width:85px;margin:10px 0px;"></td>
            <td class="payment_limits"><p><?=_e('new_81')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_80')?><br/>от $ 10,000</p></td>
            <td class="payment_description">
                <a href="#" id="bank_trigger" class="bank_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
                <!--<a href="#" class="button smallest" id="bank_trigger" onclick="return false">Пополнить баланс</a>-->
            </td>
        </tr>
        <tr>
            <td class="images"><a href="https://okpay.com" target="_blank"><img alt="ATM" src="/images/payways/okpay.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('new_215')?></p> </td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$3,000<br/></p></td>
            <td class="payment_description">
                <a href="#" id="okpay_trigger" class="okpay_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>
        <tr>
            <td class="images"><a href="https://payeer.com/" target="_blank"><img alt="Payeer" src="/images/payways/payeer.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('new_215')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="payeer_trigger" class="payeer_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>
        <tr>
            <td class="images"><a href="https://perfectmoney.is/" target="_blank"><img alt="PerfectMoney" src="/images/payways/perfect.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('new_215')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="perfectmoney_trigger" class="perfectmoney_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>

        <tr>
            <td class="images"><a href="https://nixmoney.com/" target="_blank"><img alt="Nixmoney" src="/images/nixmoney.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('new_215')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="nixmoney_trigger" class="nixmoney_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>
        <tr>
            <td class="images"><a href="https://webtransfer-finance.com/" target="_blank"><img alt="webtransfer-finance" src="/img/logo.png" width="75"></a></td>
            <td class="payment_limits"><p><?=_e('new_215')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_212')?><br/>$ 2,500<br/></p></td>
            <td class="payment_description">
                <a href="#" id="wt_trigger" class="wt_trigger but bluebut" onclick="return false"><?=_e('new_213')?></a>
            </td>
        </tr>
    </tbody>
</table>
<?
viewData()->bank_fee = $bank_fee;
viewData()->paymenyBank = $paymenyBank;
viewData()->id_user = $id_user;
viewData()->name = $name;
viewData()->f_name = $f_name;
viewData()->email = $email;
viewData()->security = $security;

?>
<? make(Content::BLOCK_ARBITRAGE, "bank"); ?>
<? // make(Content::BLOCK_ARBITRAGE, "bank_norvik");?>
<? make(Content::BLOCK_ARBITRAGE, "okpay");?>
<? make(Content::BLOCK_ARBITRAGE, "payeer");?>
<? make(Content::BLOCK_ARBITRAGE, "perfectmoney");?>
<? make(Content::BLOCK_ARBITRAGE, "nixmoney");?>
<? make(Content::BLOCK_ARBITRAGE, "wt");?>


<script>
function get_payment_permission( call_back ){
    call_back();
    return;
}
function cahckSumm(f, e){
    var val = $('#'+f+' '+e).val();
    if (isNaN(parseInt(val))){
        $('#'+f+' .error').show();
        return false;
    }
    if (parseInt(val) < 1){
        $('#'+f+' .error').show();
        return false;
    }
    if (parseInt(val) != val){
        $('#'+f+' .error').show();
        return false;
    }
    return true;
}
</script>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>