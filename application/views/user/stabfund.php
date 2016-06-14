<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<? 	$cackle_user_info = get_cackle_user_info();
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
	<br>
	<br>
	
<div data-role="post-content" class="post-content poll-notif">
<div class="avatar hovercard">
<a href="https://disqus.com/by/Webtransfer/" class="user" data-action="profile" data-username="Webtransfer">
<img data-role="user-avatar" data-user="87209183" src="//a.disquscdn.com/uploads/users/8720/9183/avatar92.jpg?1398522904" alt="Аватар">
</a>
</div>
<div class="post-body" style="
    margin-left: 60px;
">
<header>
  <span class="post-byline">
<span class="author publisher-anchor-color"><a href="https://disqus.com/by/Webtransfer/" data-action="profile" data-username="Webtransfer" data-role="username" target="_blank">Webtransfer</a></span>


</span>
<style>
.poll-notif {
	    padding: 12px;
    background-color: #f2f4f5;
    background-color: rgba(0,39,59,.05);
}
.post .avatar {
    margin-right: 12px;
}
.avatar {
    float: left;
}
.btn-block, .input-block-level {
    box-sizing: border-box;
}

.post .avatar {
    margin-right: 12px;
}
.avatar {
    float: left;
}
.btn-block, .input-block-level {
    box-sizing: border-box;
}
.post-content .post-message-container {
    position: relative;
    overflow: hidden;
    zoom: 1;
    width: 100%;
}
.avatar img {
    width: 48px;
    height: 48px;
}

.highlighted>.post-content {
    padding: 12px;
    border-radius: 3px;
}

.avatar {
    float: left;
}

.publisher-anchor-color a {
    color: rgb(51, 144, 238);
}

.post-list {
    list-style-type: none;
    margin: 0;
}

.post-content header .author, .post-content header a {
    font-weight: 700;
}

.post-content header {
    color: #777;
    line-height: 1;
    font-size: 13px;
    padding-right: 46px;
    margin-bottom: 3px;
}
</style></header>

<div class="post-body-inner">
<div class="post-message-container" data-role="message-container">
<div class="publisher-anchor-color" data-role="message-content">
<div class="post-message " data-role="message" dir="auto">
<p style="
    font-weight: 400;
    margin-top: 5px;
">
<? if($this->lang->lang() == 'ru'){ ?>
Уважаемые партнеры!  Для удобства голосования, пишите, пожалуйста, свои данные латиницей только в следующем формате:<br>
Roman Krichmaryov 12345678 <br>
В других форматах заявки не будут приниматься. Спасибо за понимание!

<?} else { ?>
Dear partners! For convenience of voting, please write your data in Latin letters only in the following format:<br>
Roman Krichmaryov 12345678<br>
In other formats, the application will not be accepted. Thank you for understanding!
<?} ?>
</p>
</div>

<span class="post-media"><ul data-role="post-media-list"></ul></span>
</div>
</div>

</div>


</div>

<div data-role="blacklist-form"></div>
<div class="reply-form-container" data-role="reply-form"></div>
</div>

<br><br>
<div id="hypercomments_widget"></div>
<script type="text/javascript">
_hcwp = window._hcwp || [];
_hcwp.push({widget:"Stream", widget_id: 66570});
(function() {
if("HC_LOAD_INIT" in window)return;
HC_LOAD_INIT = true;
var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase();
var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/66570/"+lang+"/widget.js";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hcc, s.nextSibling);
})();
</script>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='a41169eb' name='a41169eb' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=48&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=aed9b45d&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=48&amp;cb={random}&amp;n=aed9b45d&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>