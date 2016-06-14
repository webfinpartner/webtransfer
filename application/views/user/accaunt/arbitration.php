<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php

$own = $accaunt_header['max_arbitrage_amount'];
$own = ( $own < 0 ? 0 : $own);

$credit = $own * 3;
$credit = round($credit / 50) * 50;

if( $credit > $max_arbitration_sum )
    $credit = $max_arbitration_sum;

if( $credit > 3000 ) $credit = 3000;

if( $credit < 0 ) $credit = 0;

if( $own < 10 && $credit < 50 && $isNewbie ) $credit = 50;

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

</style>

<div class="widget">
    <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('accaunt/arbitration_1')?></h6>
    </div>
    <form class="form" id="credit_form" action="<?=site_url('account/my_credits')?>" method="post">
        <fieldset >
            <div class="formRow">
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
                        for ($i = 3; $i <= 30; $i++) {
                            echo "<option value=\"$i\">$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <label><span><?=_e('accaunt/arbitration_4')?></span></label>
                    <input class="form_input" id="select-percent-arbitr" type="text" value="0.5%" disabled="disabled" />
                </div>
            </div>


            <div class="formRow">
                <div class="col">
                    <label><?=_e('accaunt/arbitration_5')?></label>
                    <input class="form_input" id="summ_to_return_arbitr" type="text" value="" disabled="disabled" />
                </div>
                <!--                <div class="col">
                                    <label><?=_e('accaunt/arbitration_6')?></label>
                                    <input class="form_input" type="text" disabled="disabled" value="$ <?= price_format_double($own, TRUE, TRUE) ?>"/>
                                </div>                -->
            </div>

            <div class="formRow">
                <div id="payment_types">
                    <img alt="Webtransfer" src="/img/Webtransfer.png" class="payment_types" />
                    <?
                    $paymentsType = paymentArray();
                    foreach ($paymentsType as $pay) {
                        echo '&nbsp;&nbsp;<img src="/img/' . $pay . '.png" alt="' . $pay . '" style="opacity: 0.15">';
                    }
                    ?>
                    <!--
                    <input type="checkbox" name="payment[]" value="'.$pay.'" id="'.$pay.'" /><label class="pmt" for="'.$pay.'">

                    <br/>&nbsp;&nbsp;<input type="checkbox"  class="other_payment" id="drugoe" value=""/><label class="pmt" for="drugoe"> <?=_e('accaunt/arbitration_7')?></label>
                    <input type="text" name="payment[]" class="other_pay_input"  style="width:215px;" value=""/>-->
                </div>
            </div>
            <div style=" height: 60px;">
                <button class="button" id="arbitration"  type="submit" name="submit" ><?=_e('accaunt/arbitration_8')?></button>

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

<p><strong style="text-align: justify;"><span style="font-size: 1em;"><?=_e('accaunt/arbitration_12')?></span></strong></p>
<?=_e('accaunt/arbitration_13')?>

<div id="mc_payment" class="popup" style="opacity: 1; display: none;">
    <div class="close">&nbsp;</div>
    <div class="text"></div>
    <center><img class="loading-gif" style="display: none;" src="/images/loading.gif" alt="" /></center>
</div>

<script>
//кредит на арбитраж
                    $(function() {

                        $('#select-sum-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);
                        $('#select-period-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);
                        $('#select-percent-arbitr').change(change_arbitr_form).keyup(change_arbitr_form);

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
                        change_arbitr_form();

                        var own = <?= $own ?>,
                                max_credit = Math.round((<?= $credit ?>) / 50) * 50;

                        $('#select-sum-arbitr').val(max_credit);

                        $('#arbitration').click(function() {
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

                        function showError(message) {
                            $('#mc_payment .text').html('<p>' + message + '</p>');
                        }
                        ;
                        function send_ajax() {
                            var data = new Object();
                            data.credit_sum = $('#select-sum-arbitr').val();
                            data.period = $('#select-period-arbitr').val();
                            $.ajax({// делаем ajax запрос
                                type: "POST",
                                url: site_url + "/account/ajax_user/arbitration_auto",
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
                    });
</script>
