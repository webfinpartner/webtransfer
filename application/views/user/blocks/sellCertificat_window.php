<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
// елемент с классом bn_sellCertificat активирует это окно
?>

<div class="popup_window sellCertificat">
    <div class="close" style="z-index: 2" onclick="$(this).parent().hide();"></div>
    <div style="position: relative; height: 100%; width: 100%; z-index: 1">
        <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
            <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
        </div>
        <h2><?=_e('Подтверждаете продажу сертификата?')?></h2>
<p>
<ol style="text-align:left;">
<li><?=_e('Продавать сертификат следует только в том случае, если вам не вернули займ вовремя и Вы хотите компенсировать свои потери из Гарантийного фонда;')?></li>
<li><?=_e('Если Вы решили вернуть свои деньги до окончания срока займа.')?></li>
</ol>
</p>
        <br/>
        <input type="checkbox" id="cb_sellCertificat"/> <?=_e('Подтверждаю продажу сертификата')?><br/>
        <button class="button" id="confirm_sellCertificat" disabled="disabled" style="opacity: 0.5; cursor: default;"><?=_e('Подтвердить')?></button>
    </div>
    <form id="frm_sellSertificat" method="POST">
        <input type="hidden" name="code" id="form_code_sell" value="1"/>
    </form>
    <script>
        var security = '<?=$security?>';

        $(document).on('click', '.bn_sellCertificat', function (el) {
            var url = $(el.currentTarget).prop("href");
            if ('undefined' == typeof(url)) url = "<?=site_url('account/sellCertificate')?>/"+$(el.currentTarget).data("id");
            $('#frm_sellSertificat').attr("action", url);
            $(".sellCertificat").show();
            return false;
        });

        $(document).on('change', '#cb_sellCertificat', function() {
            if ($(this).prop("checked")){
                $('#confirm_sellCertificat').removeAttr("disabled");
                $('#confirm_sellCertificat').css("opacity", "1");
                $('#confirm_sellCertificat').css("cursor", "pointer");
            }
            else {
                $('#confirm_sellCertificat').attr("disabled", "disabled");
                $('#confirm_sellCertificat').css("opacity", "0.5");
                $('#confirm_sellCertificat').css("cursor", "default");
            }
        });

        $(document).on('click', '#confirm_sellCertificat', function(e){
            if(!security) $('#frm_sellSertificat').submit();
            else {
//                $('#out_send_window').data('call-back',standart_calc_sell).show('slow');
                 mn.security_module
                        .init()
                        .show_window('withdrawal_standart_credit')
                        .done(function(res){
                                if( res['res'] != 'success' ) return false;
                                var code = res['code'];
                                
                                $('#form_code_sell').val( code );
                                $(".sellCertificat").show();
                                $('#frm_sellSertificat').submit();
                        });
                
                $(".sellCertificat").hide();
            }
            $('.sellCertificat .close').hide();
            $('.loading-div').show();
        });
        
    </script>
</div>