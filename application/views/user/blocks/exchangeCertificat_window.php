<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
// елемент с классом bn_exchangeCertificat активирует это окно
?>

<div class="popup_window exchangeCertificat">
    <div class="close" style="z-index: 2" onclick="$('.exchangeCertificat').hide();"></div>
    <div style="position: relative; height: 100%; width: 100%; z-index: 1">
        <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
            <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
        </div>
        <h2><?=_e('blocks/exchangeCertificat_window_1')?></h2>
        <p>
            <?=_e('blocks/exchangeCertificat_window_6')?>
        </p><br/>
        <p>
            <?=_e('blocks/exchangeCertificat_window_2')?> $ <span id="min_summ"></span><br/>
            <?=_e('blocks/exchangeCertificat_window_3')?> $ <span id="max_summ"></span>
        </p>
        <br/>
        <form id="frm_exchangeCertificat" action="<?=site_url('account/exchangeCertificate')?>" method="POST">
            <input type="hidden" id="cb_exchangeCertificat_id" name="id" value="0"/>
            <input type="hidden" name="code" id="form_code_exch" value="1"/>

            <label><?=_e('blocks/exchangeCertificat_window_4')?></label><br/>
            <input type="text" id="cb_exchangeCertificat_summ" name="summ" style="padding:10px;" value=""/>
        </form>
        <button class="button" id="send_exchangeCertificat"><?=_e('blocks/exchangeCertificat_window_5')?></button>
    </div>
    <script>
        var security = '<?=$security?>';
        var min, max;

        $(document).on('click', '.bn_exchangeCertificat', function (el) {
            min = parseFloat($(el.currentTarget).data("summ"));
            max = parseFloat($(el.currentTarget).data("out_summ"));
            $("#min_summ").html(min);
            $("#max_summ").html(max);
            $("#cb_exchangeCertificat_id").val($(el.currentTarget).data("id"));
            $(".exchangeCertificat").show();
            return false;
        });

        $(document).keydown(function(e) {
                switch(e.which) {
                    case 13:
                        if($(".exchangeCertificat").is(':visible'))
                            $("#send_exchangeCertificat").trigger("click");// left
//                        if($("#out_send_window").is(':visible'))
//                            $("#out_send_window .button").trigger("click");
                    break;
                    case 27: $('.exchangeCertificat').hide();// left
//                             $('.out_send_window').hide();
                    break;

                    default: return; // exit this handler for other keys
                }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });

        $(document).on('click', '#send_exchangeCertificat', function(e){
            var sum = parseFloat($("#cb_exchangeCertificat_summ").val());
                if(isNaN(sum)) sum = 0;
            if(min > sum || max < sum) return false;
            if(!security) $('#frm_exchangeCertificat').submit();
            else {
//                $('#out_send_window').data('call-back',standart_calc_exch).show('slow');
                mn.security_module
                    .init()
                    .show_window('withdrawal_standart_credit')
                    .done(standart_calc_exch);
            
                $(".exchangeCertificat").hide();
            }
            $('.exchangeCertificat .close').hide();
            $('.loading-div').show();
        });
        function standart_calc_exch(res)
        {
            var code = res['code'];

            if( res['res'] != 'success' ) return false;

            mn.security_module.loader.show();
                            
            $('#form_code_exch').val( code );
            $(".exchangeCertificat").show();
            $('#frm_exchangeCertificat').submit();
        }
    </script>
</div>
