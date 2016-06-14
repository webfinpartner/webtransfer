<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Start of <?=$GLOBALS["WHITELABEL_NAME"] ?> Zendesk Widget script -->
<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","webtransfer.zendesk.com<?=(($this->lang->lang()=='ru')? "" : "/hc/en-us")?>");/*]]>*/</script>
<!-- End of <?=$GLOBALS["WHITELABEL_NAME"] ?> Zendesk Widget script -->


<script src='https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoadCallback&render=explicit' async defer></script>
<script>
  
    var onRecaptchaLoadCallback = function() {
        
        grecaptcha.render('login_recapcha', { 'sitekey' : '<?=config_item('publickeyCapcha')?>' });
        grecaptcha.render('forget_recapcha', { 'sitekey' : '<?=config_item('publickeyCapcha')?>' });       
    }
</script>

<style>
    #recaptcha_area,
    #recaptcha_table {
        line-height: 0!important;
    }
    #recaptcha_response_field{
        height: 16px;
    }
    #recaptcha_table,
    #recaptcha_table td,
    #recaptcha_table th {
        margin: 0!important;
        border: 0!important;
        padding: 0!important;
        border-collapse: collapse!important;
        vertical-align: middle!important;
    }
    #recaptcha_area{
        margin: 0 auto;
    }
    #recaptcha_area input {
        height: auto;
        display: inline;
   }
</style>
<div id="wt-login-form" class="popup">
    <div class="widget">
        <div class="title"><img alt="" src="/images/icons/dark/pencil.png" class="titleIcon">
            <h6><?=_e('blocks/loginForm_window_1')?></h6><div class="close" onclick="$(this).parent().parent().parent().hide()"></div>
        </div>
        <form accept-charset="utf-8" action="" class="w400" id="fm_login">
            <div class="formRow">
                <label style="margin-top:15px;"><?=_e('blocks/loginForm_window_2')?></label>
                <div class="formRight">
                    <input id="inp_login" type="text" value="" name="login">
                </div>
            </div>
            <div class="formRow">
                <label style="margin-top:15px;"><?=_e('blocks/loginForm_window_3')?></label>
                <div class="formRight">
                    <input id="inp_pass" type="password" value="" name="password"><br/>
                    <div style="font-size: 11px; text-align: left; margin-top: -15px;">
                    <a href="<?=site_url('ask/forget')?>" style="font-size:11px;text-decoration:underline;"><?=_e('blocks/loginForm_window_4')?></a></div>
                </div>
            </div>
<!--            <div class="formRow">
                <label style="margin-top:15px;"></label>
                <div class="formRight" style="text-align:left;margin-top:10px;">
                    <input type="checkbox" value="" name="new_client" id="new_client">
                    <label id="label_new_client" for="new_client" class=""><?=_e('blocks/loginForm_window_5')?></label>
                </div>
            </div>-->
            <?//=$reCapcha?>
            
            <center>
                <div id="login_recapcha"></div>
                <button name="submit" id="bn_login" type="submit" class="button" onClick="return false;"><?=_e('blocks/loginForm_window_6')?></button>
                <img src="/images/loading.gif" style="display: none" class="loading-gif">
                <div id="error_login_pass" style="display:none" class="error"><?=_e('blocks/loginForm_window_7')?> <a href="<?=site_url('ask/forget')?>"><?=_e('blocks/loginForm_window_8')?></a></div>
                <div id="error_blocked" style="display:none" class="error"><?=_e('blocks/loginForm_window_9')?></div>
                <div id="error_new" style="display:none" class="error"><?=_e('blocks/loginForm_window_10')?> <a id="resentConfermEmail" href="<?=site_url('login/resentConfermEmail')?>"><?=_e('blocks/loginForm_window_11')?></a></div>
                <div id="error_not_all" style="display:none" class="error"><?=_e('blocks/loginForm_window_12')?></div>
                <div id="error_not_captcha" style="display:none" class="error"><?=_e('blocks/loginForm_window_13')?></div>
                <div id="error_login" style="display:none" class="error"><?=_e('blocks/loginForm_window_14')?> <a href="<?=site_url('ask/forget')?>"><?=_e('blocks/loginForm_window_15')?></a></div>
                <div id="ok_auth" style="display:none" class="error"><?=_e('blocks/loginForm_window_16')?></div>
                <div id="error_email" style="display:none" class="error"><?=_e('blocks/loginForm_window_17')?></div>
                <div id="error_dont_know" style="display:none" class="error"><?=_e('blocks/loginForm_window_18')?></div>
                <div id="blocked" style="display:none" class="error"><?=_e('blocks/loginForm_window_19')?></div>
            </center>
        </form>
        <script>
            $('.bn_wt_login_form').click(function (el) {
                $("#wt-login-form").show();
                return false;
            });

            $('#new_client').change(function() {
                if ($(this).prop("checked")){
                    $("#bn_login").html("<?=_e('blocks/loginForm_window_20')?>");
                }
                else {
                     $("#bn_login").html("<?=_e('blocks/loginForm_window_21')?>");
                }
            });

            $("#resentConfermEmail").click(function(){
                $('.loading-gif').show();
                $("[id^='error_']").hide();

                var l = $("#inp_login").val();
                var p = $("#inp_pass").val();
                var n = $("#new_client").prop("checked");

                var scs = function(d,t,x){
                    $('.loading-gif').hide();
                    if(d.e)
                        errorShow(d.e)
                    else {
                        $('#wt-login-form').hide();
                        $("#fm_login").trigger( 'reset' );
                        alert("<?=_e('blocks/loginForm_window_22')?>");
                    }
                };

                $.post(site_url + "/login/resentConfermEmail",
                    {email: l, password: p, new: n},
                    scs,
                    "json");
                return false;
            });

            $("#bn_login").click(function(){
                $('.loading-gif').show();
                $("[id^='error_']").hide();

                var l = $("#inp_login").val();
                var p = $("#inp_pass").val();
                var n = $("#new_client").prop("checked");
                var g_recaptcha_response = $("#g-recaptcha-response").val();
                var scs = function(){};

                if (n)
                    scs = function(d,t,x){
                        $('.loading-gif').hide();
                        if(d.e)
                            errorShow(d.e)
                        else {
                            $('#wt-login-form').hide();
                            $("#fm_login").trigger( 'reset' );
                            alert("<?=_e('blocks/loginForm_window_23')?>");
                        }
                    };
                else
                    scs = function(d,t,x){
                        $('.loading-gif').hide();
                        if(d.e)
                            errorShow(d.e)
                        else {
                            window.location = site_url + d.page;
                            $('#ok_auth').show();
                            $('.loading-gif').show();
                        }
                    };


                $.post(site_url + "/login/logining",
                    {email: l, password: p, new: n, 'g-recaptcha-response': g_recaptcha_response},
                    scs ,
                    "json");
            });

            function errorShow(e){
                $("#"+e+"").show();
                //Recaptcha.reload();
                grecaptcha.reset();
            }

        </script>
    </div>
</div>