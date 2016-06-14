<script>
//$ss = '{"embeds":{"helpCenterForm":{"embed":"helpCenter","props":{"color":"#ff7000","contextualHelpEnabled":false}},"launcher":{"embed":"launcher","props":{"color":"#ff7000"}},"ticketSubmissionForm":{"embed":"submitTicket","props":{"customFields":[{"id":24830472,"type":"integer","title":"№ Кошелька (ID)","required":true}],"color":"#ff7000"}}},"locale":"ru-RU"}';        
//        
//var $ee = $.parseJSON($ss);
//        
//console.log('^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^6');
//var $ps_curency_array_currency_id = $.parseJSON('{"9840":"USD1","9841":"<span class=\"curency_id_heart_red\">\u2764<\/span>","9842":"\u0421-CREDS","9900":" ","9910":" ","971":"AFN","978":"EUR","008":"ALL","012":"DZD","840":"USD","973":"AOA","951":"XCD","032":"ARS","051":"AMD","533":"AWG","654":"SHP","036":"AUD","944":"AZN","044":"BSD","048":"BHD","050":"BDT","052":"BBD","974":"BYR","084":"BZD","952":"XOF","060":"BMD","064":"BTN","356":"INR","068":"BOB","984":"BOV","977":"BAM","072":"BWP","578":"NOK","986":"BRL","826":"GBP","096":"BND","702":"SGD","975":"BGN","108":"BIF","116":"KHR","764":"THB","950":"XAF","124":"CAD","132":"CVE","136":"KYD","990":"CLF","152":"CLP","156":"CNY","170":"COP","970":"COU","174":"KMF","554":"NZD","188":"CRC","643":"RUB","191":"HRK","931":"CUC","192":"CUP","532":"ANG","203":"CZK","976":"CDF","208":"DKK","262":"DJF","214":"DOP","818":"EGP","232":"ERN","230":"ETB","238":"FKP","242":"FJD","953":"XPF","270":"GMD","981":"GEL","936":"GHS","292":"GIP","320":"GTQ","324":"GNF","328":"GYD","332":"HTG","340":"HNL","344":"HKD","348":"HUF","352":"ISK","360":"IDR","364":"IRR","368":"IQD","376":"ILS","388":"JMD","392":"JPY","400":"JOD","398":"KZT","404":"KES","414":"KWD","417":"KGS","418":"LAK","422":"LBP","426":"LSL","430":"LRD","434":"LYD","756":"CHF","446":"MOP","807":"MKD","969":"MGA","454":"MWK","458":"MYR","462":"MVR","478":"MRO","480":"MUR","484":"MXN","979":"MXV","498":"MDL","496":"MNT","504":"MAD","943":"MZN","104":"MMK","516":"NAD","524":"NPR","558":"NIO","566":"NGN","408":"KPW","949":"TRY","512":"OMR","586":"PKR","590":"PAB","598":"PGK","600":"PYG","604":"PEN","608":"PHP","985":"PLN","634":"QAR","946":"RON","646":"RWF","882":"WST","682":"SAR","941":"RSD","690":"SCR","694":"SLL","090":"SBD","706":"SOS","710":"ZAR","410":"KRW","728":"SSP","144":"LKR","938":"SDG","968":"SRD","748":"SZL","752":"SEK","947":"CHE","948":"CHW","760":"SYP","678":"STD","901":"TWD","972":"TJS","834":"TZS","776":"TOP","780":"TTD","788":"TND","934":"TMT","800":"UGX","980":"UAH","784":"AED","940":"UYI","858":"UYU","860":"UZS","548":"VUV","937":"VEF","704":"VND","886":"YER","967":"ZMW"}');
var $ps_curency_array_currency_id = $.parseJSON('{"9840":"USD1","9841":"<span class=\'curency_id_heart_red\'>USD\u2764<\/span>"}');
//console.log($ps_curency_array_currency_id);
 
 var $selectbox_to_wt_sell_arr = $.parseJSON('<?=json_encode($payout_limit_arr)?>');
// console.log($selectbox_to_wt_sell_arr);
 
 var $selectbox_to_wt_buy_arr = $.parseJSON('<?=json_encode($payout_limit_arr_buy)?>');
// console.log($selectbox_to_wt_buy_arr);
 
 var root_currency = $.parseJSON('<?=json_encode(Currency_exchange_model::root_currencys_js())?>');
// console.log(root_currency);
// console.log('&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&');
</script>
<?php echo $b; ?>