<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
// елемент с классом qestion активирует это окно
?>


<script type='text/javascript' src='/js/quickselect/quicksilver.js'></script>
<script type='text/javascript' src='/js/quickselect/jquery.quickselect.min.js'></script>
<link rel="stylesheet" type="text/css" href="/js/quickselect/jquery.quickselect.css" />

<div id="qestion" class="popup partner_question">
    <div class="widget" style="position: relative">
        <div class="title"><img alt="" src="/images/icons/dark/pencil.png" class="titleIcon">
                    <h6><?=_e('blocks/qestion_window_1')?></h6><div class="close"></div>
        </div>
        <form accept-charset="utf-8" action="" id="fm_question" style="width: 562px; padding: 20px;">
            <div style="width: 100%">
                <input type="text" style="width: 98%; padding: 0" id="topic_text" name="topic" placeholder="<?=_e('blocks/qestion_window_2')?>" value="" />
                <textarea style="width: 98%; height: 152px" id="question_text" name="question_text" placeholder="<?=_e('blocks/qestion_window_3')?>"></textarea>
                <center style="width: 100%">
                    <button name="submit" id="bn_send" type="submit" class="button" onClick="return false;"><?=_e('blocks/qestion_window_4')?></button>
                    <img src="/images/loading.gif" style="display: none" class="loading-gif" />
                    <div id="no_text" style="display:none" class="error"><?=_e('blocks/qestion_window_5')?></div>
                </center>
            </div>
        </form>
        <script>
            $(function(){
              var topics = <?=getTopicsAsJSON()?>;

              $('#topic_text').quickselect({
                maxItemsToShow:10,
                autoSelectFirst:false,
                data:topics
              });
            });

            $(window).load(function(){
                $('.qestion').click(function (el) {
                    $("#qestion").show();
                    return false;
                });

                $("#bn_send").click(function(){
                    var txt = $("#question_text").val();
                    var tpc = $("#topic_text").val();
                    if (7 > txt.length) {
                        alert("<?=_e('blocks/qestion_window_6')?>");
                        return;
                    }
                    var scs = function(d,t,x){
                            $('.loading-gif').hide();
                            if(d.e)
                                errorShow(d.e)
                            else {
                                $('#qestion').hide();
                                $("#fm_question").trigger( 'reset' );
                                alert("<?=_e('blocks/qestion_window_7')?>");
                            }
                        };
                    $('.loading-gif').show();
                    $("[id^='error_']").hide();

                    $.post(site_url + "/account/ajaxSendQuestion",
                        {text: txt, topic: tpc},
                        scs ,
                        "json");
                });
            });

            function errorShow(e){
                $("#"+e+"").show();
            }

        </script>
    </div>
</div>
