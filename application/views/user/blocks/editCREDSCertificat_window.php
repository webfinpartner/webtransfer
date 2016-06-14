<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class="popup_window editExchangeCertificat">
    <div class="close" style="z-index: 2" onclick="$('.editExchangeCertificat').hide();"></div>
    <div style="position: relative; height: 100%; width: 100%; z-index: 1">
        <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
            <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
        </div>
        <h2><?=_e('Вы можете изменить стоимость CREDS')?></h2>
        <p>
            <?=_e('Максимальная стоимость CREDS:')?> $ <span id="min_summ"></span><br/>
            <?=_e('Минимальная стоимость CREDS:')?> $ <span id="max_summ"></span>
        </p>
        <br/>
        <form id="frm_editExchangeCertificat" action="<?=site_url('account/editExchangeCertificate')?>" method="POST">
            <input type="hidden" id="cb_editExchangeCertificat_id" name="id" value="0"/>

            <label><?=_e('Установить цену:')?></label>
            <input type="text" id="cb_editExchangeCertificat_summ" name="summ" value=""/>
        </form>
        <button class="button" style='display: inline-block;' id="send_editExchangeCertificat"><?=_e('Подтвердить')?></button>
        <button class="button" style='display: inline-block;' onclick="$('.editExchangeCertificat').hide();"><?=_e('Отказаться')?></button>
    </div>
    <script>

        $(document).keydown(function(e) {
            switch(e.which) {
                case 13: if($(".editExchangeCertificat").is(':visible'))
                            $("#send_editExchangeCertificat").trigger("click");// left
                break;
                case 27: $('.editExchangeCertificat').hide();// left
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });

        $(document).on('click', '#send_editExchangeCertificat', function(e){
            var min = parseFloat($("#send_editExchangeCertificat").data("min"));
            var max = parseFloat($("#send_editExchangeCertificat").data("max"));
            var sum = parseFloat($("#cb_editExchangeCertificat_summ").val());
                if(isNaN(sum)) sum = 0;
            if(min > sum || max < sum) return false;
            $('#frm_editExchangeCertificat').submit();
            $('.editExchangeCertificat .close').hide();
            $('.loading-div').show();
        });
    </script>
</div>