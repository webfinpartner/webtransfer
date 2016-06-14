<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>

<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/user/styles1.css" />
    <link rel="stylesheet" href="/css/user/accaunt.css" />
    <link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
    <title><?=_e('accaunt/token_1')?></title>
<style>
    #buttons{
        display: block;
        margin: 10px 0 20px;
        overflow: auto;
    }
    #buttons .button1{
        float: right;

    }
    .formRow{
        overflow: auto;

    }
    .formRow .formRight{
        padding-top: 9px;
        width:400px !important;
    }
    .small{
        font-size: 14px;
        line-height: 14px;
        color: #999;
        margin-bottom: 7px;
        width: 200px;
    }
</style>
</head>

<body>

<div id="out_send_window" class="popup_window" style="z-index: 99999; display: block;">
    <!--<div onclick="$(this).parent().hide('slow');" class="close"></div>-->
    <form action="/opera" method="POST">
        <h2><?=_e('accaunt/token_2')?> <?=  Permissions::$SEC_TYPES[$admin->sec_auth]?></h2>

        <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
            <div class="formRight" style="width: 90% !important;">
                <div class="sms-content" style="text-align: center;">
                    Введите код:  <input class="form_input" name="code" type="text" value="" style="height: 27px;margin-top:-5px;width:135px  !important;" />
                    <? if ( $admin->sec_auth == 'sms' ) { ?>
                    <a id="sms" class="but blueB ml10 send_code_link right" href="#" style="">Запрос SMS</a>
                    <? } ?>
                </div>
            </div>


            <div class="res-message">

            </div>

        </div>

        <button type="submit" name="wt_token" value="<?=_e('accaunt/token_4')?>" class="button narrow"><?=_e('accaunt/token_5')?></button>
    </form>
		<div style="font-size:12px;float:right;"><a href="/opera/auth/logout"><?=_e('accaunt/token_6')?></a></div>
</div>
<script>
    $("#out_send_window").show();
    $("#sms").click(function(){
        $.get("/opera/auth/sendSms",function(res){if('ok' == res)$(".res-message").html("смс отправлено").show();else $(".res-message").html("ошибка отправки").show();});
        return false;
    })
</script>

</body>

</html>