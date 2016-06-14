<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
// елемент с классом bn_delExchangeCertificat активирует это окно
?>

<div class="popup_window delExchangeCertificat">
    <div class="close" style="z-index: 2" onclick="$('.delExchangeCertificat').hide();"></div>
    <div style="position: relative; height: 100%; width: 100%; z-index: 1">
        <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
            <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
        </div>
        <h2><?=_e('Подтверждаете удаление CREDS с биржи?')?></h2>
        <br/>
        <button class="button" style='display: inline-block;' id="send_delExchangeCertificat"><?=_e('Подтвердить')?></button>
        <button class="button" style='display: inline-block;' onclick="$('.delExchangeCertificat').hide();"><?=_e('Отменить')?></button>
    </div>
    <script>
        $(document).on('click', '.bn_delExchangeCertificat', function (el) {
            $("#send_delExchangeCertificat").data("id",$(el.currentTarget).data("id"));
            $(".delExchangeCertificat").show();
            return false;
        });

        $(document).keydown(function(e) {
            switch(e.which) {
                case 13: if($(".delExchangeCertificat").is(':visible'))
                            $("#send_delExchangeCertificat").trigger("click");// left
                break;
                case 27: $('.delExchangeCertificat').hide();// left
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });

        $(document).on('click', '#send_delExchangeCertificat', function(e){
            window.location = site_url + "/account/back4exchangeCertificate/"+$("#send_delExchangeCertificat").data("id");
            $('.delExchangeCertificat .close').hide();
            $('.loading-div').show();
        });
    </script>
</div>