<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<? if(!$notAjax){ ?>
<h1 class="title"><?= $title_accaunt ?></h1>
<? } ?>
<script src="/js/user/jquery.dd.32.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/css/user/msdropdown/dd.css" />
<?
    $sum = get_item('sum');
    $time = get_item('time');
    $persent = get_item('persent');
?>
<style>

.formRow:before {
    border-top: none;
    position: relative;
    width: 100%;
    height: auto !important;
    z-index: 9;
    padding-left: 0px;
    padding-right: 0;
}


.formRow.rowBonus {
    border-top: none;
    position: absolute;
    width: 370px;
    height: 218px !important;
    z-index: 18;
    margin-bottom: -76px;
    border-bottom: 0;
}

.formRow {
    border-top: none;
    position: relative;
    width: 100%;
    height: auto !important;
    z-index: 9;
    padding-left: 0px;
    padding-right: 0;
}


.formRow .rowWrapper {
    margin-left: 23px;
}

#loader_window{
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -20px;
    margin-left: -50px;
    width: 50px;
    height: 40px;
}​  

</style>



<?php
define( 'MIN', 50 );
define( 'MAX', 1000 );
define( 'MIN_PARTNER', 50 );
define( 'MAX_PARTNER', 1000 );
define( 'MIN_RATE', 12 );
define( 'MAX_COUNT', 10);


$sum = get_item('sum');
$time = get_item('time');
$persent = get_item('persent');




//$money = $accaunt_header[ 'net_own_funds_by_bonus' ][2] + $accaunt_header[ 'net_own_funds_by_bonus' ][5];
//$money = $accaunt_header[ 'payment_account' ] - $accaunt_header[ 'all_advanced_invests_summ' ] - $accaunt_header[ 'all_advanced_standart_invests_summ' ] - $accaunt_header[ 'total_processing_payout' ];
$reting = $accaunt_header[ 'fsr' ];
$max_loan_available = ($accaunt_header[ 'max_loan_available' ] > MAX? MAX: $accaunt_header[ 'max_loan_available' ]);
$stat = getStatistic();
$bonus_psnt = (0.5 > $stat->today->avg_rate) ? round($stat->yesterday->avg_rate,1) : round($stat->today->avg_rate,1);
$max_prsnt = 3;//($isUS2USorCA)?0.5:3;

//бонусный счет за вычетом выставленных бонусных заявок
//fix: add all_advanced_standart_invests_summ_by_bonus to var
$bonus = $accaunt_header['payment_account_by_bonus'][1] -$accaunt_header['all_advanced_invests_summ_by_bonus'][1]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][1];//$accaunt_header[ 'bonuses' ] - $accaunt_header[ 'all_advanced_invests_bonuses_summ' ];
//fix: add all_advanced_standart_invests_summ_by_bonus to var
$partner_funds = $accaunt_header[ 'payment_account_by_bonus' ][3] - $accaunt_header[ 'all_advanced_invests_summ_by_bonus' ][3]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][3];
//fix: add all_advanced_standart_invests_summ_by_bonus to var
$c_creds_funds =  $accaunt_header[ 'payment_account_by_bonus' ][4] - $accaunt_header[ 'all_advanced_invests_summ_by_bonus' ][4]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][4];
$summ_bonus2 = $accaunt_header[ 'payout_limit_by_bonus' ][2];
$summ_bonus5 = 0;
$summ_bonus6 = $accaunt_header[ 'payout_limit_by_bonus' ][6];;



$summ_garant_bonus2 = $accaunt_header[ 'availiable_garant_by_bonus' ][2];
$summ_garant_bonus5 = $accaunt_header[ 'availiable_garant_by_bonus' ][5];
$summ_garant_bonus6 = $accaunt_header[ 'availiable_garant_by_bonus' ][6];

if ($isUsaLimitedUser )
    $summ_garant_bonus2 = $accaunt_header['p2p_money_sum_transfer_from_users_by_bonus'][2] + max(0,($accaunt_header[ 'transfered_summ_from_bonus_5_to_2' ]-$accaunt_header['money_sum_withdrawal_by_bonus'][2]-$rating['money_sum_process_withdrawal_by_bonus'][2] - $rating['all_active_garant_loans_summa_by_bonus'][2] - $rating['all_posted_garant_loans_out_summ_by_bonus'][2]));



$money = $max = max($summ_bonus2, $summ_bonus5,$summ_bonus6, $bonus,$partner_funds, $c_creds_funds);

?>

<script>
    var security = '<?=$security?>';
    var maney_all = <?=$money?>;
    var partner_funds = <?=$partner_funds?>;
    var c_creds_funds = <?=$c_creds_funds?>;
    var bonus = <?=$bonus?>;
    var standart_limits = [<?=$bonus?>,<?=$summ_bonus2?>,<?=$partner_funds?>,<?=$c_creds_funds?>,<?=$summ_bonus5?>,<?=$summ_bonus6?>];
    var garant_limits = [<?=$bonus?>,<?=$summ_garant_bonus2?>,<?=$partner_funds?>,<?=$c_creds_funds?>,<?=$summ_garant_bonus5?>,<?=$summ_garant_bonus6?>];
    var remove_creds = [0,<?=$remove_creds_summ2?>,0,0,0,<?=$remove_creds_summ6?>];
    


    function standart_calc(res){
        if( res !== undefined )
        {
            if( res['res'] === undefined || res['res'] != 'success' ) return false;
            $('#form_code').val( res['code'] );
        }
        mn.security_module.loader.show();

        $('#confirm_invest2').addClass('active').click();
    }
    
    function show_popup_debit() {
        var pa_type = $('#payment_account option:selected').data('type');
        var pa_summ = $('#payment_account option:selected').data('summ');
        $('#accept_invest_arbitraj_div').css('display', (pa_type == 'card'?'block':'none'));
        
        
        $('#arbitraj_invest_summ option').remove();
        if ( pa_summ >= 50 )
        for (i= 50; i <= pa_summ; i+=50 )
            $('#arbitraj_invest_summ').append( new Option(i,i) );
                    
        
        $('#popup_debit').show('slow');
    }
    
    
    function on_accept_invest_arbitraj(){
         $('#arbitraj_invest_summ').css('display', $('#accept_invest_arbitraj').prop('checked')?'block':'none');
    }


function on_use_arbtr_checked(){
    var use_arbitr = $('#use_arbitr').prop("checked");
    
    if (!use_arbitr){
        on_payment_account_change();
        return;
    }
    var old_summ = $('#select-sum').val();
    var old_time = $('#select-period').val();
    var pa = $('#payment_account option:selected').val();
    var garant = $('#garant').prop("checked");
    var summ = (garant)?garant_limits[pa-1]:standart_limits[pa-1]; //$('#payment_account option:selected').data('summ');
    summ = Math.max(0, summ);
    if ( use_arbitr )
        summ = $('#use_arbitr').data('summ');
    
    var amount = '', days = '';
    
    for (var i = 50; i <= Math.min(summ,1000); i += 50)
        if ( i == old_summ)
            amount += '<option selected="selected" value="' + i + '">' + i + '</option>';
        else
            amount += '<option value="' + i + '">' + i + '</option>';
    
    if ( amount == '')
        amount += '<option value="0">0</option>';


    <?if ( $accaunt_header['max_arbitrage_days_by_bonus'][6] >=3 ) { ?>
    for (var i = 3; i <= <?=$accaunt_header['max_arbitrage_days_by_bonus'][6]?>; i++)
        if ( i == old_time )
            days += '<option selected="selected"  value="' + i + '">' + i + '</option>';    
        else
            days += '<option value="' + i + '">' + i + '</option>';    
    <? } ?>

    $('#select-sum').html(amount);
    $('#select-period').html(days);
    
    if ( use_arbitr ){
        $('#c_creds_fee_div').hide();
        $('#usd2_creds_fee_div').hide();
    }
}

function on_use_arbtr_b7_checked(){
    var use_arbitr = $('#use_arbitr_b7').val();
    
    if (use_arbitr == 'none'){
        on_payment_account_change();
        return;
    }
    var old_summ = $('#select-sum').val();
    var old_time = $('#select-period').val();
    var pa = $('#payment_account option:selected').val();
    var garant = $('#garant').prop("checked");
    var summ = (garant)?garant_limits[pa-1]:standart_limits[pa-1]; //$('#payment_account option:selected').data('summ');
    summ = Math.max(0, summ);
    if ( use_arbitr != 'none')
        summ = $('#use_arbitr_b7 option:selected').data('summ');
    
    var amount = '', days = '';
    
    for (var i = 50; i <= Math.min(summ,1000); i += 50)
        if ( i == old_summ)
            amount += '<option selected="selected" value="' + i + '">' + i + '</option>';
        else
            amount += '<option value="' + i + '">' + i + '</option>';
    
    if ( amount == '')
        amount += '<option value="0">0</option>';

    if ( use_arbitr == 'rating'){   
        var max_days = Math.floor( Math.min(summ,1000)/50 );
        for (var i = 3; i <= max_days; i++)
            if ( i == old_time )
                days += '<option selected="selected"  value="' + i + '">' + i + '</option>';    
            else
                days += '<option value="' + i + '">' + i + '</option>';    
    } else {
        <?if ( $accaunt_header['max_arbitrage_days_by_bonus'][7] >=3 ) { ?>
        for (var i = 3; i <= <?=$accaunt_header['max_arbitrage_days_by_bonus'][7]?>; i++)
            if ( i == old_time )
                days += '<option selected="selected"  value="' + i + '">' + i + '</option>';    
            else
                days += '<option value="' + i + '">' + i + '</option>';    
        <? } ?>
    }

    $('#select-sum').html(amount);
    $('#select-period').html(days);
    
    if ( use_arbitr != 'none' ){
        $('#c_creds_fee_div').hide();
        $('#usd2_creds_fee_div').hide();
    }
}

    function on_payment_account_change(){

        var pa = $('#payment_account option:selected').val();
        var pa_type = $('#payment_account option:selected').data('type');
        var pa_summ = $('#payment_account option:selected').data('summ');
        var garant = $('#garant').prop("checked");
        var is_ccreds_fee = $('#is_ccreds_fee').prop("checked");
        var is_usd6creds_fee = $('#is_usd6creds_fee').prop("checked");

        if ( pa_type == 'card' || pa_type == 'own_account')
            pa = '2';


        var summ = (garant)?garant_limits[pa-1]:standart_limits[pa-1]; //$('#payment_account option:selected').data('summ');
        var amount = '', days = '', psnt = '';
        if ( pa_type == 'card' || pa_type == 'own_account')
            summ = pa_summ;

        $('#account_type').val(  pa_type );
        $('#c_creds_fee_div').hide();
        $('#use_arbitr_div').hide();
        $('#use_arbitr_b7_div').hide();
        
        $('#usd6_creds_fee_div').hide();
        $('#usd2_creds_fee_div').hide();
        
        if ( !is_ccreds_fee )
            $('#your_ccreds_invest_minus').hide();




        switch ( pa ){
           case '1':
                    $('#standart-span').hide();
                    $('#c_creds_fee_div').hide();
                    $('#use_arbitr_div').hide();
                    $('#use_arbitr_b7_div').hide();
                    $("#garant").attr("checked", '');
                    $("#standart").removeAttr("checked");

                    for (var i = 50; i <= summ&& i <= 1000; i += 50)
                        amount += '<option value="' + i + '">' + i + '</option>';

                    for (var i = 3; i <= 10; i++)
                        days += '<option value="' + i + '">' + i + '</option>';

                    for (var i = <?= $bonus_psnt ?>; i <= <?=$max_prsnt?>; i = Math.round((i + 0.1)*10)/10)
                        psnt += '<option value="' + i.toFixed(1) + '">' + i.toFixed(1) + '%</option>';
                    break;


         case '4':
                    $('#standart-span').hide();
                    $('#c_creds_fee_div').hide();
                    $('#use_arbitr_div').hide();
                    $('#use_arbitr_b7_div').hide();
                    $("#garant").attr("checked", '');
                    $("#standart").removeAttr("checked");

                for (var i = 50; i <= summ && i <= 1000; i += 50)
                    amount += '<option value="' + i + '">' + i + '</option>';

                for (var i = 3; i <= 30; i++)
                    days += '<option value="' + i + '">' + i + '</option>';

                for (var i = 0.5; i <= <?=$max_prsnt?>; i = Math.round((i + 0.1)*10)/10)
                    psnt += '<option value="' + i.toFixed(1) + '">' + i.toFixed(1) + '%</option>';
                 break;

            case '3':
                    $('#standart-span').hide();
                    $('#c_creds_fee_div').hide();
                    $('#use_arbitr_div').hide();
                    $('#use_arbitr_b7_div').hide();
                    $("#garant").attr("checked", '');
                    $("#standart").removeAttr("checked");

                for (var i = 50; i <= summ && i <= 1000; i += 50)
                    amount += '<option value="' + i + '">' + i + '</option>';

                days += '<option value="' + 40 + '">' + 40 + '</option>';
                psnt += '<option value="' + 0.5 + '">' +0.5 + '%</option>';
                break;

            case '-':
                    $('#standart-span').hide();
                    $('#c_creds_fee_div').hide();
                    $('#use_arbitr_div').hide();
                    $('#use_arbitr_b7_div').hide();
                    $("#garant").attr("checked", '');
                    $("#standart").removeAttr("checked");

                      amount += '<option value="0">0</option>';
                      days += '<option value="0">0</option>';
                      psnt += '<option value="0">0</option>';
                      break;
            case '2':
			  if ( pa == 2){
				$('#c_creds_fee_div').hide();
                                $('#use_arbitr_div').hide();
                                $('#use_arbitr_b7_div').hide();
                            }

            case '5':

            case '6':
                if ( pa == 5){
                    $('#standart-span').hide();
                    $('#c_creds_fee_div').hide();
                    $('#use_arbitr_div').hide();
                    $('#use_arbitr_b7_div').hide();
                    $("#garant").attr("checked", '');
                    $("#standart").removeAttr("checked");
                } else {
                    $('#standart-span').show();
                }


                
                
                for (var i = 50; i <= Math.min(summ,1000); i += 50)
                        amount += '<option value="' + i + '">' + i + '</option>';

                for (var i = 3; i <= 30; i++)
                        days += '<option value="' + i + '">' + i + '</option>';                    
                
                for (var i = 0.5; i <= <?=$max_prsnt?>; i = Math.round((i + 0.1)*10)/10)
                    psnt += '<option value="' + i.toFixed(1) + '">' + i.toFixed(1) + '%</option>';

                break;

            }

            if ( amount == '')
                amount += '<option value="0">0</option>';


            $('#select-sum').html(amount);
            $('#select-period').html(days);
            $('#select-percent').html(psnt);


            on_summ_change();
            onChange();


    }


    function on_summ_change(){
        var newsumm = $('#select-sum').val();
        var pa = $('#payment_account option:selected').val();
        var pa_type = $('#payment_account option:selected').data('type');
        var garant = $('#garant').prop("checked");
        var use_arbitr = $('#use_arbitr').prop("checked");
        var use_arbitr_b7 = $('#use_arbitr_b7').val();

        $('#c_creds_fee_div').hide();
        $('#use_arbitr_div').hide();
        $('#use_arbitr_b7_div').hide();
        $('#usd6_creds_fee_div').hide();   
        $('#usd2_creds_fee_div').hide();
        $('#is_usd2_creds_fee').prop('checked', false);
        
        
        
         <? if (in_array(get_current_user_id(),explode(',',get_sys_var('usd2_fee_users_available')) )){ ?>
         if ( garant && pa_type == 'card')
            $('#usd2_creds_fee_div').show();
         <? } ?>
        // установим можно ли платить c_creds
        if ( garant && newsumm > 0 && ( pa == 2 ) && ( pa_type != 'card' && pa_type != 'own_account')  ){
            console.log( newsumm + ' -- ' + remove_creds[2-1] + " " + garant);
                    if ( newsumm <= remove_creds[2-1] )
                            $('#usd6_creds_fee_div').show();
                    else
                        $('#usd6_creds_fee_div').hide();
                }


       <?//$calc['calc_sum_own']=50;?>
        if ( garant && pa != 2 || (pa == 2 && <?=$isUsaLimitedUser?1:0?>==0))
        if ( !use_arbitr && newsumm > 0 && ( pa == 6 ) && ( pa_type != 'card' && pa_type != 'own_account')  ){
                    if ( <?=$c_creds_funds?> > get_fee()*<?=(get_sys_var('USD1_TO_CCREDS_PERC')/100)?> /*&& is_can_ccreds( newsumm, pa  )*/ && newsumm <= <?=$calc['calc_sum_own']?>  )
                            $('#c_creds_fee_div').show();
                    if ( newsumm > <?=$calc['calc_sum_own']?> && newsumm <= <?=($calc['calc_sum_own']+$calc['calc_sum_loan'])?>  )
                        $('#usd2_creds_fee_div').show();
              }

        if ( garant && pa == 6 && pa_type != 'card')
            $('#use_arbitr_div').show();
        if ( garant && pa_type == 'card' ){
            $('#use_arbitr_b7_div').show();
            if (garant &&  use_arbitr_b7=='rating' ){
                var days = '';
                var maxsumm = $('#use_arbitr_b7 option:selected').data('summ');
                var max_days = Math.floor( maxsumm/ newsumm);
                var old_time = $('#select-period').val();
                for (var i = 3; i <= max_days; i++)
                        days += '<option '+((i == old_time)?'selected="selected"':'')+' value="' + i + '">' + i + '</option>';    
                 $('#select-period').html(days);
            }
                

        }
    }



    function is_can_ccreds(summa, pa){
            if ( summa >= remove_creds[pa-1] )
                return true;
            else
                return false;

            return true;
            if (summa == "")summa = 0;
            var arbitration_active = <?=$calc['calc_sum_arbitration']?>;
            if(arbitration_active >= summa) {
                    sum_own         = 0;
                    sum_arbitration = summa;
            } else if(arbitration_active > 0) {
                    sum_own         = summa - arbitration_active;
                    sum_arbitration = arbitration_active;
            } else {
                    sum_own         = summa;
                    sum_arbitration = 0;
            }
            console.log( sum_own + " >= " +sum_arbitration  );
            return ( sum_own >= sum_arbitration );

    }



    $(function(){

    try {
        $('#payment_account').msDropDown(
                {   animStyle: '',
                    on: {create: function(){$('.formRow.rowBonus').css('z-index', 9)},
                      open: function(){$('.formRow.rowBonus').css('z-index', 10)},
                      close: function(){$('.formRow.rowBonus').css('z-index', 9)}

                 }}
                    );
    } catch(e) {
        alert(e.message);
    }


        $('#confirm_invest2').click(
        function confirm(){
            var std = 0;
            $('#popup_debit').hide('slow');
            if(!security){
                $('#popup_load').show('slow');
                return true;
            }else{
                if( !$("#garant").prop("checked") )
                {
                    if( $(this).hasClass('active') ){
                        $('#popup_load').show('slow');
                        return true;
                    }else{
                        mn.security_module
                            .init()
                            .show_window('withdrawal_standart_credit')
                            .done(standart_calc);
                        return false;
                    }
                }else{
                    $('#popup_load').show('slow');
                    return true;
                }
            }
        });


    });

    function countNumAvalaible() {
        var pa = $('#payment_account option:selected').val();
        var pa_type = $('#payment_account option:selected').data('type');
        var count_money = bonus;
        var garant = $('#garant').prop("checked");
        var use_arbitr = $('#use_arbitr').prop("checked");
        var use_arbitr_b7 = $('#use_arbitr_b7').val();
        if ( pa == '3' ) {
            count_money = partner_funds;
        } else if (pa=='4') {
            count_money = c_creds_funds;
        } else if (pa=='2') {
            count_money = (garant)?<?=$summ_garant_bonus2?>:<?=$summ_bonus2?>;
        } else if (pa=='5') {
            count_money = (garant)?<?=$summ_garant_bonus5?>:<?=$summ_bonus5?>;
        }else if (pa=='6') {
            count_money = (garant)?<?=$summ_garant_bonus6?>:<?=$summ_bonus6?>;
            if (garant &&  $('#use_arbitr').prop("checked") ){
                //count_money = Math.max(0,count_money);
                //count_money += $('#use_arbitr').data('summ'); 
                count_money += $('#use_arbitr').data('summ'); 
            }
        } else if ( pa_type=='card'){
            count_money = $('#payment_account option:selected').data('summ');
            if (garant &&  use_arbitr_b7=='arbitr' )
                count_money += $('#use_arbitr_b7 option:selected').data('summ'); 
            if (garant &&  use_arbitr_b7=='rating' )
                count_money = $('#select-sum').val();             
        }
        var c = count_money / $('#select-sum').val();
        c = (<?=MAX_COUNT?> < c) ? <?=MAX_COUNT?> : c;
        var cOptions = '';
        for (var i = 1; i <= c; i++) {
            cOptions += '<option value="' + i + '">' + i + '</option>';
        }
        $('#count_invests').html(cOptions);
    };
    
    
    function check_card_qiwi(){
        var pa = $('#payment_account option:selected').val();
        var pa_type = $('#payment_account option:selected').data('type');
        
        if ( pa_type != 'card')
            return;
        
        var select_summ = $('#select-sum').val();
        
        if ( !$('#is_usd2_creds_fee').prop('checked') ){
            onChange();
            return;
        }
        
        $('#loader_window').show();
        $.post(site_url + '/account/card-transactions_summ_by_service', { card_id: pa},
               function(data) {
                        $('#loader_window').hide();
                        if ( data.summ < select_summ ){
                            //alert( "Недостаточно пополенний для Qiwi"+"("+data.summ+")");
                            $('#cur_qiwi_summ').html(data.summ);
                            $('#usd2_check_result_info').show();
                            $('#is_usd2_creds_fee').prop('checked', false);
                        }
                        onChange();
                       
               }, 'json');                
    }
</script>
<script type="text/javascript" src="/js/user/sms_module.js"></script>
<style>
    .pmt {
        cursor: pointer !important;
        float: none !important;
        margin-right: 0px !important;
        padding: 0 !important;
    }

    .zayavka_ind:hover {
        background-color:#fff;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#garant').change(function() {
            if ($(this).prop("checked"))
                $('.payment_types').css('opacity', '0.15')

             //$('#payment_account option[value=1]').show();
             //$('#payment_account option[value=3]').show();
             //$('#payment_account option[value=4]').show();
//            $('.rowBonus').show();

            on_payment_account_change();

        });
        $('#standart').change(function() {
            if ($(this).prop("checked"))
                $('.payment_types').css('opacity', '1');


            //$('#payment_account option[value=1]').hide();
            //$('#payment_account option[value=3]').hide();
            //$('#payment_account option[value=4]').hide();
//            $('.rowBonus').hide();
            on_payment_account_change();
        });

});

</script><br/>

 <? if($notAjax){ ?>
   <br/>
   <center>
        <iframe id='a9240a81' name='a9240a81' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=15&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=ab672b4a&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=15&amp;cb={random}&amp;n=ab672b4a&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </center>
   <br/>
 <?}?>

<!--<script type="text/javascript">
        var RecaptchaOptions = {
                lang : 'ru', // Unavailable while writing this code (just for audio challenge)
                theme : 'white' // Make sure there is no trailing ',' at the end of the RecaptchaOptions dictionary
        };
</script>-->
   <? $rating_by_bonus = $accaunt_header;?>
   <!--OWN:<?=$calc['calc_sum_own']?>
   LOANS: <?=$calc['calc_sum_loan'] ?>
   ARB: <?=$calc['calc_sum_arbitration'] ?>-->
   
   
<div class="widget">
    <div class="title">
        <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('accaunt/invests_1')?></h6>
    </div>
    <input type="hidden" name="ccreds_perc" id="ccreds_perc" value="<?=(get_sys_var('USD1_TO_CCREDS_PERC')/100)?>" />
    <form class="form" id="form_invest" action="<?=site_url('account/my_invest')?>" method="post">
        <input type="hidden" name="code" id="form_code" value="1"/>
        <input type="hidden" name="account_type" id="account_type" value="none"/>
                        <input type="hidden" name="use_arbitr_b7" value="none">
        
        
        <fieldset >
            <div class="formRow rowBonus">
                    <div class="rowWrapper">

                        <?//  if (  $rating_bonus[2]['net_own_funds'] > 0 && $rating_bonus[5]['net_own_funds'] > 0  ) { ?>
                            <label><?=_e('Счет: ')?> </label>&nbsp;
                            <select id="payment_account" name="payment_account" onchange="on_payment_account_change()" style="width: 300px;margin-bottom:10px;color:#000000;"><?
                            echo "<option selected=\"selected\" data-type='none' data-summ='0' value='-'>"._e('Выберите счет')."</option>";
                            $selected  = '';
                            //$selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                            //if ( $rating_bonus['max_garant_vklad_real_available_by_bonus'][5] > 0)
                            //echo "<option data-type='pa' data-summ='{$summ_garant_bonus5}' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_bonus['max_garant_vklad_real_available_by_bonus'][5])."</option>";

                            
                            if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                               <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-summ="<?=($card->last_balance - $rating_bonus['showed_cards_invests'])?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
                            <? }                            
                            
                            //$selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                            if ( $rating_bonus['max_garant_vklad_real_available_by_bonus'][2] > 0)
                            echo "<option style='color:red' data-summ='{$summ_garant_bonus2}' data-type='pa' value='2'$selected>WTUSD<span style='color:red'>&#10084;</span> - $ ".price_format_double($rating_bonus['max_garant_vklad_real_available_by_bonus'][2])."</option>";
                            
                            //if ( $rating_bonus['max_garant_vklad_real_available_by_bonus'][6] > 0)
                            if ( $rating_bonus['max_arbitrage_calc_by_bonus'][6] > 0 || $rating_bonus['max_garant_vklad_real_available_by_bonus'][6] > 0)
                            echo "<option style='color:red' data-summ='{$summ_garant_bonus6}' data-type='pa' value='6'$selected>WTDEBIT- $ ".price_format_double(max(0,$rating_bonus['max_garant_vklad_real_available_by_bonus'][6]))."</option>";


                            //$selected = ( $default_bonus_account==1 )?' selected="selected" ':'';
                            if ( $bonus >= MIN )
                            echo "<option data-type='pa' data-summ='{$bonus}' value='1'$selected>BONUS - $ ".price_format_double($bonus)."</option>";

                            //$selected = ( $default_bonus_account==3 )?' selected="selected" ':'';
                          //  if ( $partner_funds >= MIN )
                          //  echo "<option data-type='pa' data-summ='{$partner_funds}' value='3'$selected>P-CREDS - $ ".price_format_double($partner_funds)."</option>";

                            //$selected = ( $default_bonus_account==4 )?' selected="selected" ':'';
                          //  if ( $c_creds_funds >= MIN )
                          //  echo "<option data-type='pa' data-summ='{$c_creds_funds}' value='4'$selected>C-CREDS - $ ".price_format_double($c_creds_funds)."</option>";



                             ?>
                            <?  if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ ?>
                                <option value="<?=$own_acc->id?>" data-image="<?=Card_model::get_own_account_icon($own_acc)?>" data-summ="<?=$own_acc->summa?>" data-type="own_account"><?=Card_model::display_own_account_name($own_acc)?> - $ <?=price_format_double($own_acc->summa)?> </option>
                            <? } ?>
                            </select>
                        <? //} ?>





                    </div>
                </div>

            <div style="z-index: 100;    clear: both;    overflow: hidden;    margin-top: 61px;">
            <div class="formRow" >
                <div class="col">
                    <label><?=_e('accaunt/invests_2')?></label>
                    <select id="select-sum" name="summ"  class="form_select" onchange="on_summ_change()"><?
                       //$max = max($accaunt_header['net_own_funds_by_bonus'][2], $accaunt_header['net_own_funds_by_bonus'][5]);
                        //for( $i = 50; $i <= min($max,1000); $i +=50 )
                            //echo "<option value=\"$i\">$i</option>";
                            echo "<option value=\"0\">0</option>";

                        ?></select>
                </div>
                <div class="col">
                    <label><?=_e('accaunt/invests_3')?></label>
                    <select id="select-period" name="time" class="form_select"><?
                        for( $i = 3; $i <= 30; $i++ ){
                            if ( (empty($time) && 15 == $i) || $time==$i ) echo "<option value=\"$i\" selected=\"selected\">$i</option>";
                            else echo "<option value=\"$i\">$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <label>
                        <span><?=_e('accaunt/invests_4')?></span>
                        <a href="#"  onclick="$('.procent').show('slow');
        return  false;" style="text-decoration:1px dotted" id="cert_info1">(?)</a>:
                    </label>
                    <select id="select-percent" name="percent" class="form_select"><? getPsntSelect($persent) ?></select>
                </div>
            </div>
            <div class="formRow">
                <div class="rowWrapper">
                    <input type="radio"  name="garant" id="garant"  value="1" checked />
                    <label class="pmt" for="garant"> <?=_e('accaunt/invests_5')?></label>
                    <a href="#"  onclick="$('.garant').show('slow');
        return  false;" style="text-decoration:1px dotted" id="cert_info1">(?)</a> &nbsp;&nbsp;
                    <span id="standart-span" style="display: none;">
                        <input type="radio"  name="garant" id="standart"  value="0" />
                        <label class="pmt" for="standart" id="label_standart"> <?=_e('accaunt/invests_6')?></label>
                    </span>
                    <a href="#"  onclick="$('.standart').show('slow');
        return  false;" style="text-decoration:1px dotted" id="cert_info1"></a>
        
        
            <div style=" position: absolute; margin-left: 215px; margin-top: -20px; width: 320px; display: none;" id="c_creds_fee_div">
                <span class="formRight" style="float: none; width: auto;">
                    <input type="checkbox" onchange="onChange()" id="is_ccreds_fee" name="is_ccreds_fee" value="1"  style="margin-top:6px;"/>
                    <label class="pmt" for="is_ccreds_fee"><?=_e('Отчисление CREDS')?> <a href="#"  onclick="$('.creds').show('slow');
        return  false;" style="text-decoration:1px dotted" id="creds_info1">(?)</a></label> &nbsp;&nbsp;
                    <input type="text" style="width: 100px; display: none;" id="your_ccreds_invest_minus" disabled="disabled">
                </span>
            </div>

            <div style=" position: absolute; margin-left: 265px; margin-top: -20px; width: 320px; display: none;" id="usd6_creds_fee_div">
                <span class="formRight" style="float: none; width: auto;">
                    <input type="checkbox" onchange="onChange()" id="is_usd6creds_fee" name="is_usd6creds_fee" value="1"  style="margin-top:6px;"/>
                    <label class="pmt" for="is_usd6creds_fee"><?=_e('Прибыль на DEBIT Card')?> <a href="#"  onclick="$('.usd6_creds').show('slow');
        return  false;" style="text-decoration:1px dotted" id="usd6_creds_info1">(?)</a></label> &nbsp;&nbsp;

                </span>
            </div>
        
        <?if ( in_array(get_current_user_id(),[500150, 92156962, 93517463]) ){ ?>
          <? } ?>
           <?if ($accaunt_header[ 'payment_account_by_bonus' ][2] > 0 ) { ?>
            <div style=" position: absolute; margin-left: 215px; margin-top: -20px; width: 320px; display: none;" id="usd2_creds_fee_div">
                <span class="formRight" style="float: none; width: auto;">
                    <input type="checkbox" onchange="check_card_qiwi()" id="is_usd2_creds_fee" name="is_usd2_creds_fee" value="1"  style="margin-top:6px;"/>  <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                    <label class="pmt" for="is_usd2creds_fee"><?=_e('Отчисление с USD<span style="color:red">&#10084;</span>')?>  &nbsp;&nbsp;
                </span>
            </div>        
           <? } ?>
			
          

                </div>



            </div>



            <div class="formRow">
                <div class="formRight biger">
                    <span class="oneFour field">
                        <div class="text"><?=_e('accaunt/invests_10')?></div>
                        <input type="text" value="" id="your_invest">
                    </span>
                    <span class="oneFour sign">+</span>
                    <span class="oneFour field">
                        <div class="text"><?=_e('accaunt/invests_11')?></div>
                        <input type="text" value="" id="your_invest_plus">
                    </span>
                    <span class="oneFour sign">-</span>
                    <span class="oneFour field">
                        <div class="text"><?=_e('accaunt/invests_12')?><span id="ohearth" style="display: none; color:red">&#10084;</span><a href="#"  onclick="$('.otchislenie').show('slow');
        return  false;" style="text-decoration:1px dotted" id="cert_info1">(?)</a>:</div>
                        <input type="text" value="" id="your_invest_minus"></span>
                    <span class="oneFour sign">=</span>
                    <span class="oneFour field">
                        <div class="text"><?=_e('accaunt/invests_13')?></div>
                        <input id="summ_to_return" type="text" value="" disabled="disabled" />
                    </span>
                </div>
            </div>
            </div>


            <div style=" height:60px;">
                <input type="hidden" name='submit' value='1' />

                <button class="button" onclick="show_popup_debit();countNumAvalaible();
<?= (!empty( $curSocial[ "vk" ][ "access_token" ] ) ? "postMsgWall('"._e('accaunt/invests_15')." " . base_url() . "');" : "") ?> return  false;"  type="submit" name="submit" ><?=_e('accaunt/invests_16')?></button>

<a href="<?=site_url('account/applications_invest')?>"  class="but bluebut sendmoney_card_trigger" style="margin-top: -60px; cursor: pointer; float:right; margin-right:10px" ><?=_e('Выдать на бирже')?></a>
            </div>

            <div id="popup_debit">
                <div class="close"></div>
                <h2><?=_e('new_85')?></h2>
                <?=_e('new_86')?>
                <a href="<?=site_url('page/about/terms')?>" target="_new"><?=_e('new_87')?></a>,
                <?=_e('new_88')?>

                    <br/>
                    <br/>
                     <center>
                        <?=_e('new_89')?>
                        <select id="count_invests" name="count">
                            <option value="1">1</option>
                        </select>
                        <?=_e('копий данной заявки')?>
                    </center>
                    <?if ( in_array(get_current_user_id(),[500150, 92156962, 93517463, 90831296, 72752493,90840332]) ){ ?>
                    <div id="accept_invest_arbitraj_div" style="display: none">
                        <br>
                    <input name="accept_invest_arbitraj" id="accept_invest_arbitraj" onclick="on_accept_invest_arbitraj()" type="checkbox"/><label for="accept_invest_arbitraj"><?=_e('Я хочу запустить арбитраж')?></label>
                    <center>
                    <select id="arbitraj_invest_summ" name="arbitraj_invest_summ" style="display: none"></select>                    
                    </center>
                    </div>
                    <? } ?>
                <button class="button" type="submit" id="confirm_invest2" name="submit" ><?=_e('new_90')?></button>
                <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
            </div>

        </fieldset>
    </form>
</div>
<center>
 <a class="button narrow" href="<?=site_url('account/guarant')?>" style="margin-top:32px"><?=_e('Выпустить Гарантию')?></a>
</center>
<?
if( !empty( $credits ) )
{
    $i = 0;
    foreach( $credits as $item )
    {
        $i++;
        if( $i > 3 )
            break;
        $this->load->view( 'user/accaunt/blocks/renderInvests_part.php', compact( "item" ) );
    }
    if( count( $credits ) > 3 ):
        ?>
        <a class="button narrow see_else" href="<?=site_url('account/invests_enqueries')?>" style="margin-top:32px"><?=_e('new_92')?></a>
    <? endif; ?>
    <?
}
else
    echo "<div class='message'>" . _e('new_93') . "</div>";
?>

<!-- POPUP WINDOWS -->
<? $this->load->view( 'user/blocks/sellCertificat_window', compact($security) ); ?>
<? $this->load->view( 'user/blocks/exchangeCertificat_window', compact($security) ); ?>
<? $this->load->view( 'user/blocks/delExchangeCertificat_window' ); ?>
<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>
<script type="text/javascript" src="/js/user/sms_module.js"></script>


<div class="popup_window" id="return_arbitration_part_dialog">
    <div class="close"></div>
    <h2><?=_e('Возврат арбитражной части')?></h2>
    <?=_e('')?>
    <br/><br/>
    <a href="#" id="return_arbitration_part_but" class="but cancel bluebut"><?=_e('Подтвердить')?></a>
</div>

<div class="popup_window procent">
    <div class="close"></div>
    <?=_e('new_94')?>
</div>

<div class="popup_window check_overdraft">
    <div class="close"></div>
    <?=_e('new_95')?>
</div>

<div class="popup_window otchislenie">
    <div class="close"></div>
    <?=_e('new_96')?>
</div>


<div class="popup_window small" id="user_popup">
    <div class="close"></div>
    <div class="content" ></div>
</div>

<div class="popup_window" id="popup_agree_confirm0">
    <div class="close"></div>
    <h2><?=_e('accaunt/applications_23a')?></h2>
   <?=_e('accaunt/applications_24a')?><br/><br/>
    <?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?><br/><br/>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div class="popup_window" id="popup_agree_confirm_own_direct">
    <div class="close"></div>
    <h2><?=_e('Выдача займа на сторонний счет')?></h2>
   <?=_e('Вы выдаете займ на счет третьего лица. Подтверждая, вы соглашаетесь с
    <a href="'.site_url('/page/about/terms').'" target="_new">Правилами и Условиями использования</a>,
    в т.ч. на передачу ваших персональных данных заемщику.')?><br/><br/>
    <?=_e('При совершении данной операции ваи необходимо перевести средства на счет контрагента, а затем подтвердить отправку средств.')?><br/><br/>
    <div id="extra_info"></div>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div class="popup_window"  id="popup_agree_confirm1" >
    <div  class="close"></div>
    <?=_e('new_99')?>
    <a href="<?=site_url('page/about/terms')?>" target="_new"><?=_e('new_100')?></a>.<br />
    <button class="button" type="submit" id="confirm_invest" name="submit" value=""><?=_e('new_90')?></button>
</div>

<div class="popup_window garant">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <?=_e('new_101')?>
    <a href="<?=site_url('page/about/terms')?>" target="_new"><?=_e('new_102')?></a>.<br />
    <?=_e('new_103')?><br />
</div>

    <div class="popup modal fade in modal-visa-card creds" id="creds_text" role="dialog" aria-hidden="false" style="left: 0px; display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="$('.popup').hide('slow');"></div>
                    <h4 class="modal-title"><?=_e('Отчисление CREDS')?></h4>
                </div>
                <div class="modal-body" style="padding:20px;text-align:center;">
<?=_e('Данная опция, позволяет вам отчислять в гарантийный фонд ваш процент (40-50%) от прибыли используя CREDS.<br/><Br/>Использовать CREDS для отчислений в гарантийный фонд можно только при работе с Webtransfer DEBIT Card.')?>
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
	
	
   <div class="popup_window heart">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2>WTUSD<span style="color:red;">&#10084;</span> - Happy Birthday Webtransfer!</h2>
    <?=_e('Вывод средств со счета WTUSD<span style="color:red;">&#10084;</span> осуществляется в течение <br/>1 часа (максимум) с 08.00 до 20.00 по Гринвичу (время сайта)')?> *<br/><br/>
	<hr>
		<span style="font-size:11px;">* <?=_e('Действительно для собственных средств введенных после 25.07.2015г.')?><Br/>
	<?=_e('accaunt/bonus2')?>.</span>
</div>

<div class="popup_window overdraft">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <?=_e('new_104')?>
</div>
<div class="popup_window direct">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <?=_e('new_105')?>
</div>

<div id="popup_alfa"  class="popup_window" style="width:800px;height:85%;top:5%;left:50%;margin-left:-400px">
    <div class="close"></div>
    <iframe src="https://3ds.payment.ru/P2P_PSBR/card_form.html" frameborder="0" width="95%" height="95%" align="center">
        <?=_e('new_106')?>
	</iframe>
</div>


<div  class="popup_window" id="popup_load">
    <center><?=_e('new_107')?><br/>
        <img src="/images/loaders/loader12.gif"></center>
</div>

<div class="popup_window" id="popup_confirm_payment">
    <div class="close"></div>
    <?=_e('new_108')?>
    <button class="button" type="submit" class="confirm-button" name="submit"><?=_e('new_90')?></button>
</div>


<div id="popup_confirm_return" class="popup_window" >
    <div class="close"></div>
    <?=_e('new_109')?>
    <button class="button confirm-button" type="submit" name="submit"><?=_e('new_90')?></button>
</div>

<div id="popup_window" class="popup_window small">
    <div class="close"></div>
    <div class="content" style="padding-top: 15px;">
        <p class="center"><?=_e('new_110')?></p>
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif">
    </center>
</div>

<div class="popup" id="send_money">
    <div class="close"  onclick="$('#send_money').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('new_111')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="mc_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_112')?>: </label>
                    <div class="formRight">
                        <input type="text" name="reciver" disabled="disabled" style="color: black"    value=""/>
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('new_113')?>:</label>
                    <div class="formRight">
                        <input type="text" name="amount" disabled="disabled" style="color: black"   value=""/>
                    </div>

                </div>
                <center>
                    <button class="button narrow"  type="submit" data-id=""   id="out_send" name="submit"><?=_e('new_114')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
    <br/>
    <a href="#" class="but cancel other_methods"><?=_e('new_115')?></a>
</div>


<div class="popup" id="rekviziti">
    <div class="close"  onclick="$('#rekviziti').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('new_116')?></h6>
        </div>
        <fieldset>

        </fieldset>
    </div>
</div>

<div class="popup_window" id="other_methods">
    <div class="close"></div>
    <?=_e('new_117')?>
    <br/><br/>
    <a href="#" class="but cancel alfa"><?=_e('new_118')?></a>
    <a href="#" id="open_rekviziti" class="but cancel bluebut"><?=_e('new_119')?></a>
</div>

<div class="popup_window"  id="popup_accept_invest_arbitraj" >
    <div  class="close"></div>
    <h2><?=_e('new_120')?></h2>
    условия... <!-- В Lang файл условия пожалуйста... -->
    ....
    <input id="accept_invest_arbitraj" type="checkbox"/><label for="accept_invest_arbitraj"><?=_e('new_121')?></label>
    <button class="button" type="submit" id="confirm_invest_arbitraj" name="submit" value=""><?=_e('new_122')?></button>
</div>

    <div class="popup modal fade in modal-visa-card" id="usd2_check_result_info" role="dialog" aria-hidden="false" style="left: 0px; display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="$('.popup').hide('slow');"></div>
                    <h4 class="modal-title"><?=_e('Отчисление с USD<span style="color:red">&#10084;</span>')?></h4>
                </div>
                <div class="modal-body" style="padding:20px;text-align:center;">
<?=_e('Данная опция доступна только для займов-гарант выданных с Webtransfer VISA Card.')?>
 <br><br><?=_e('В настоящее вы можете выдать займ-гарант и погасить с Webtransfer USD<span style="color:red">&#10084;</span>')?> - $<span id="cur_qiwi_summ">0</span>	<br/><br/>
<?=_e('<a href="/ru/account/payment">Пополнить Webtransfer VISA Card</a>')?> 
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
	
	
<div id="loader_window" class="popup_window small" style="z-index: 99999999;top:30%;">
    <center>
        <img id="loader-loading-gif" class="loader-loading-gif"  src="/images/loading.gif">
    </center>
</div>


<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
</div>
<? if($notAjax){ ?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>
<?$this->load->view('user/accaunt/blocks/renderSendMessagePopup.php', compact("item"));?>