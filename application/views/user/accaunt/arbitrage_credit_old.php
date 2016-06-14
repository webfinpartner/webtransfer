<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php

//$own = $accaunt_header['max_arbitrage_amount'];
//$own = ( $own < 0 ? 0 : $own);

//$credit = $own * 4;
//$credit = round( $own * 4 / 50) * 50;


//if( $credit > $max_arbitration_sum )
//    $credit = $max_arbitration_sum;
//
//if( $credit > 5000 ) $credit = 5000;

//$credit = $max_arbitration_sum;
$credit = $rating_bonus['max_arbitrage_calc_by_bonus'][$default_bonus_account];

if( $credit < 0 ) 
    $credit = 0;
if( $credit < 50 && $isNewbie ) $credit = 50;

$disabled = '';
if ($credit == 0)
    $disabled = 'disabled="disabled"';
?>

<style>
    .pmt {
        cursor: pointer !important;
        float: none !important;
        margin-right: 0px !important;
        padding: 0 !important;
    }

    .zayavka_ind:hover {background-color:#fff;}

</style><br/>
<center>
<iframe id='a024f4e0' name='a024f4e0' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=25&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60' allowtransparency='true'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a9eb3317&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=25&amp;cb={random}&amp;n=a9eb3317&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</center>
<br/>
<div class="widget">
    <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('accaunt/arbitration_1')?></h6>
    </div>
    <form class="form" id="credit_form" action="<?=site_url('account/my_credits')?>" method="post">
        <fieldset >
		            <div class="formRow">
			 <div class="col">
                        <label><?=_e('Получить арбитраж на счет: ')?></label>&nbsp;
                        <select id="payment_account" name="payment_account" onchange="on_payment_account_change()" class="form_select" style="width: 200px"><? 
                            echo "<option data-type='none' data-summ='0' value='-'>-</option>";
                          //  $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                          //  echo "<option value='5'$selected>WTUSD1 - $ {$rating_bonus['max_arbitrage_calc_by_bonus'][5]}</option>";
                         //   $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                         //   echo "<option value='2'$selected>WTUSD&#10084; - $ {$rating_bonus['max_arbitrage_calc_by_bonus'][2]}</option>";                            
                            $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                            echo "<option value='6'$selected>WTDEBIT - $ {$rating_bonus['max_arbitrage_calc_by_bonus'][6]}</option>";                                                        
                        ?></select>
                </div>
				
                <div class="col">
                    <label><?=_e('accaunt/arbitration_2')?></label>

                    <select id="select-sum-arbitr" name="credit_sum"  class="form_select" <?= $disabled ?> >
                        <?php
                        if ($credit == 0)
                            echo "<option value=\"0\">0</option>";
                        else
                            for ($i = 50; $i <= $credit; $i +=50) {
                                if ($i == $credit)
                                    echo "<option value=\"$i\" selected>$i</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label><?=_e('accaunt/arbitration_3')?></label>
                    <select id="select-period-arbitr"  name="time" class="form_select" <?= $disabled; ?> ><?
                        if ( $rating_bonus['max_arbitrage_days_by_bonus'][$default_bonus_account] >= 3 ){
                                for ($i = 3; $i <= $rating_bonus['max_arbitrage_days_by_bonus'][$default_bonus_account]; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                        } else {
                            echo "<option value=\"0\">-</option>";
                        }
                        ?></select>
                </div>

            </div>


            <div class="formRow">
			                <div class="col">
                    <label><span><?=_e('accaunt/arbitration_4')?></span></label>
                    <input class="form_input" id="select-percent-arbitr" type="text" value="0.5%" disabled="disabled" />
                </div>
                <div class="col">
                    <label><?=_e('accaunt/arbitration_5')?></label>
                    <input class="form_input" id="summ_to_return_arbitr" type="text" value="" disabled="disabled" />
                </div>
                
            </div>

            <div style=" height: 60px;">
                <button class="button" id="arbitration" type="submit" name="submit" ><?=_e('accaunt/arbitration_8')?></button>

            </div>

            <div id="popup_debit">
                <div class="close"></div>
                <h2><?=_e('accaunt/arbitration_9')?></h2>
                <?=_e('accaunt/arbitration_10')?>
                <button class="button" type="submit"  onclick="$('#popup_debit').hide('slow');
                        $('#popup_load').show('slow');" name="submit"><?=_e('accaunt/arbitration_11')?></button>
            </div>
            
        </fieldset>
    </form>
</div>

    <div class="popup modal fade in modal-visa-card creds" id="disable_info_window" role="dialog" aria-hidden="false" style="left: 0px; display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="$('.popup').hide('slow');"></div>
                    <h4 class="modal-title"><?=_e('Арбитраж v. 2.0')?></h4>
                </div>
                <div class="modal-body" style="padding:20px;text-align:center;">
<?=_e('Кредит на арбитраж теперь доступен автоматически при соблюдении условий для его получения. Возьмите займ (или займы) Гарант на нужную сумму, отметьте чек-бокс "Использовать арбитраж", и вы сможете дополнительно выдать сумму, в три раза превышающую сумму взятых займов Гарант. <br/><br/> Не обязательно выдавать все доступные средства сразу на весь срок займов Гарант, арбитраж будет доступен на всем протяжении срока действия полученных вами займов.')?>
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
	
	
            <div id="" class="popup_window" style="opacity: 1; display: none;">
                <div class="close">&nbsp;</div>
                <div class="text"><?=_e('') ?></div>
            </div>            


<p><strong style="text-align: justify;"><span style="font-size: 1em;"><?=_e('accaunt/arbitration_12')?></span></strong></p>
<?=_e('accaunt/arbitration_13')?>

<div id="mc_payment" class="popup" style="opacity: 1; display: none;">
    <div class="close">&nbsp;</div>
    <div class="text"></div>
    <center><img class="loading-gif" style="display: none;" src="/images/loading.gif" alt="" /></center>
</div>





<script>
    var limits = { 2: <?=$rating_bonus['max_arbitrage_calc_by_bonus'][2]?>, 5:<?=$rating_bonus['max_arbitrage_calc_by_bonus'][5]?>, 6:<?=$rating_bonus['max_arbitrage_calc_by_bonus'][6]?> };
    var limits_days = { 2: <?=$rating_bonus['max_arbitrage_days_by_bonus'][2]?>, 5:<?=$rating_bonus['max_arbitrage_days_by_bonus'][5]?>, 6:<?=$rating_bonus['max_arbitrage_days_by_bonus'][6]?> }; 
    
     function on_payment_account_change(){
                            var payment_account = $('#payment_account').val();
                            var options_html = '';
                            var options_html_days = '';
                            var max_summ = 0;
                            var max_days = 0;
                            if ( payment_account != '-'){
                                max_summ = limits[payment_account];
                                max_days = limits_days[payment_account];
                            }
                            
                            
                            if ( max_summ == 0){
                                options_html += "<option value=\"0\">0</option>";
                            } else {
                                  for (i = 50; i <= max_summ; i +=50) 
                                    if (i == max_summ)
                                        options_html += "<option value=\""+i+"\" selected>"+i+"</option>";
                                    else
                                        options_html += "<option value=\""+i+"\">"+i+"</option>";
                            }
                            
                          if ( max_days < 3){
                                options_html_days += "<option value=\"0\">-</option>";
                            } else {
                                  for (i = 3; i <= max_days; i++) 
                                    if (i == max_days)
                                        options_html_days += "<option value=\""+i+"\" selected>"+i+"</option>";
                                    else
                                        options_html_days += "<option value=\""+i+"\">"+i+"</option>";
                            }                            
                            
                            $('#select-sum-arbitr').html(options_html);
                            $('#select-period-arbitr').html(options_html_days);
                            change_arbitr_form();      
                        }
                        function change_arbitr_form() {
                            time = $('#select-period-arbitr').val();
                            summ = parseInt($('#select-sum-arbitr').val());
                            percent = parseFloat($('#select-percent-arbitr').val());

                            pribil = summ * (percent / 100 * time)
                            console.log( pribil );
                            out_sum = summ + pribil;

                            out_sum = summ_format((Math.round((out_sum + 0.00001) * 100) / 100));

                            if( $("#summ_to_return_arbitr").length !=0 )
                                $("#summ_to_return_arbitr").val("$ " + out_sum);
                        }


                        function showError(message) {
                            $('#mc_payment .text').html('<p>' + message + '</p>');
                        }
                        
                        function send_ajax() {
                            var data = new Object();
                            data.credit_sum = $('#select-sum-arbitr').val();
                            data.period = $('#select-period-arbitr').val();
                            data.payment_account = $('#payment_account option:selected').val();
                            $.ajax({// делаем ajax запрос
                                type: "POST",
                                url: site_url + "/arbitrage/ajax_user/arbitrage_credit_auto",
                                dataType: "text",
                                cache: false,
                                data: data,
                                success: function(responce) { 			// выполнится после AJAX запроса
                                    var html = null;
                                    $('#mc_payment .loading-gif').hide();
                                    if (responce == '') {
                                        showError('<p><?=_e('accaunt/arbitration_15')?></p>');
                                        return;
                                    }
                                    try {
                                        html = JSON.parse(responce);
                                    } catch (e) {
                                        showError('<p><?=_e('accaunt/arbitration_16')?></p>');
                                        return;
                                    }
                                    if (html['error']) {
                                        showError(html['error']);
                                    } else if (html['success']) {
                                       showError(html['success']);
                                        if (html['action'] && html['action'] == 'reload')
                                            setTimeout(function() {
                                                location.reload();
                                            }, 2000);
                                    }

                                }
                            }
                            );
                            return false;
                        }                        
                        
                    //кредит на арбитраж
                    $(function() {

                        $('#select-sum-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);
                        $('#select-period-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);
                        $('#select-percent-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);
                        
                        change_arbitr_form();
                        var max_credit = <?= $credit ?>;
                        $('#select-sum-arbitr').val(max_credit);
                        $('#arbitration').click(function() {
                             $('#disable_info_window').show(); 
                             return false;
                            
                            <? if ($credit < 50) { ?>
                                    showError('<p><?=_e('accaunt/arbitration_14')?></p>');
                                    $('#mc_payment').show();
                            <? } else { ?>
                                    send_ajax();
                                    $('#mc_payment .loading-gif').show();
                                    $('#mc_payment').show();
                            <? } ?>

                            return false;
                        });                        
                       

                      
                    });
</script>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>