<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<?
    if( isset( $accaunt_header['max_loan_available'] ) ) $own = $accaunt_header['max_loan_available'];
    else
        $own = 0;

    $own = ( $own < 0 ? 0 : $own);
    $credit = $own*3;
?>
<?php if(!$isAuthorized): ?>
<script>
    $(function(){
      $('.bn_wt_login_form').click(function () {
        $("#wt-login-form").show();
        return false;
      });
    });
</script>
<?php endif; ?>

<script>
    $(function() {
        //ПОДДЕРЖКА
        $('.left_480 .txt .info-tit').click(function() {

            $('.left_480 .txt .info-txt').slideUp("slow");
            $('.left_480 .txt .info-txt').removeClass('act');
            $('.left_480 .txt .info-txt').addClass('deact');

            $('.left_480 .txt .info-tit').removeClass('act');

            if ($(this).next().css('display') == 'none') {
                $(this).next().slideDown("slow");
                $(this).next().removeClass('deact');
                $(this).next().addClass('act');
                $(this).addClass('act');
            } else {
                $(this).removeClass('act');
            }
        });
    });
    //кредит на арбитраж
    $(function() {
       var own = <?=$own?>,
           max_credit = Math.round((<?=$credit?>)/50)*50;
        $('#payment_account').val( own );
        $('#credit_sum').val( max_credit );
        $('#max_credit_sum').html( max_credit );

        $('#arbiration').click(function() {
        <? if ( $credit < 50 ) { ?>
                        $("#mc_payment .widget").remove();
                        $('#mc_payment .close').after('<p><?=_e('page_js_error')?></p>');
        <? } ?>
            $('#mc_payment').show();
            return false;
        });
        $('#agree_form').change(function() {
            if ($(this).prop('checked'))
                $('#mc_send_button').removeClass('inactive');
            else
                $('#mc_send').addClass('inactive');
        });
        $('#credit_sum').keyup(function(){
           var val = parseInt($(this).val());
           if( val > max_credit ) val = max_credit;

           $(this).val( val );
        });
        $('#mc_send_button').click(function() {
            if ($(this).hasClass('inactive'))
                return false;
            var select = $('#select-period');


            if (select.val() == '<?=_e('page_js_select_period')?>'){
                select.addClass('wrong');
            }
            else
            {
                select.removeClass('wrong');
                send_ajax();
                $('#mc_payment .widget').remove();
                $('#mc_payment .loading-gif').show();
            }

            return false;
        });
        function showError(message) {
            $("#mc_payment .widget").remove();
            $('#mc_payment .close').after('<p>' + message + '</p>');
        };
        function send_ajax() {
            var data = new Object();
            data.user_id = $('#user_id').val();
            data.payment_account = $('#payment_account').val();
            data.credit_sum = $('#credit_sum').val();
            data.period = $('#select-period').val();
            $.ajax({// делаем ajax запрос
                type: "POST",
                url: site_url + "/arbitrage/ajax_user/arbitrage_credit",
                dataType: "text",
                cache:false,
                data:data,
                success: function(responce) { 			// выполнится после AJAX запроса
                        var html = null;
                        $('#mc_payment .loading-gif').hide();
                        if (responce == '') {
                            showError('<?=_e('page_js_showError')?>');
                            return;
                        }
                        try {
                            html = JSON.parse(responce);
                        } catch (e) {
                            showError('<?=_e('page_js_showError')?>');
                            return;
                        }
                        if (html['error']) {
                            showError(html['error']);
                        } else if (html['success']) {
                            showError(html['success']);
                        }

                    }
                }
            );
            //   alert('<?=_e('page_js_alert')?>');
            return false;
        }
    });
</script>
<div id="container" class="content">
    <h1 class="title"><?= $content->title ?></h1>
    <?php
    $GLOBALS['setting'] = $setting;
    echo shablon($content);
    ?>
	<br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
</div>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='a41169eb' name='a41169eb' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=48&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=aed9b45d&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=48&amp;cb={random}&amp;n=aed9b45d&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>