<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h1 class="title"><?= _e('profile/personal_info55') ?></h1>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<style>
    .form .formRight .w10{
        width: 60px !important;
    }

    #w3confirmation .formRight{
        width: 450px !important;
    }
    #w3confirmation .formRow{
        padding: 8px 10px;
    }

    .dd-select {
        border: 1px solid #CCCCCC;
        border-radius: 2px;
        cursor: pointer;
        position: relative;
        float: left;
        margin-right: 10px;

        width: 90px;
        background: none repeat scroll 0% 0% #fff;
    }
    .dd-desc {
        color: #AAAAAA;
        display: block;
        font-weight: normal;
        line-height: 1.4em;
        overflow: hidden;
    }
    .dd-selected {
        display: block;
        font-weight: bold;
        overflow: hidden;
        padding: 7px;

    }
    .dd-pointer {
        height: 0;
        margin-top: -3px;
        position: absolute;
        right: 10px;
        top: 50%;
        width: 0;
    }
    .dd-pointer-down {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #000000 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
        border-image: none;
        border-right: 5px solid rgba(0, 0, 0, 0);
        border-style: solid;
        border-width: 5px;
    }
    .dd-pointer-up {
        -moz-border-bottom-colors: none !important;
        -moz-border-left-colors: none !important;
        -moz-border-right-colors: none !important;
        -moz-border-top-colors: none !important;
        border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #000000 !important;
        border-image: none !important;
        border-style: solid !important;
        border-width: 5px !important;
        margin-top: -8px;
    }
    .dd-options {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background: none repeat scroll 0 0 #FFFFFF;
        border-color: -moz-use-text-color #CCCCCC #CCCCCC;
        border-image: none;
        border-right: 1px solid #CCCCCC;
        border-style: none solid solid;
        border-width: medium 1px 1px;
        box-shadow: 0 1px 5px #DDDDDD;
        display: none;
        list-style: none outside none;
        margin: 0;
        overflow: auto;
        padding: 0;
        position: absolute;
        z-index: 2000;
        top: 33px;

        max-height: 250px;
    }
    .dd-option {
        border-bottom: 1px solid #DDDDDD;
        color: #333333;
        cursor: pointer;
        display: block;
        overflow: hidden;
        padding: 4px;
        text-decoration: none;
        transition: all 0.25s ease-in-out 0s;
    }
    .dd-options > li:last-child > .dd-option {
        border-bottom: medium none;
    }
    .dd-option:hover {
        background: none repeat scroll 0 0 #F3F3F3;
        color: #000000;
    }
    .dd-selected-description-truncated {
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .dd-option-selected {
        background: none repeat scroll 0 0 #F6F6F6;
    }
    .dd-option-image, .dd-selected-image {
        float: left;
        margin-right: 5px;
        max-width: 64px;
        vertical-align: middle;
    }
    .dd-image{
        float: left;
        margin: 6px;
    }
    .dd-container {
        position: relative;
    }

    .formRight label.dd-selected-text{
        float: right;
        color: #333;
        font-weight: 400;
        font-size: 16px;
    }
    .phone-code{
        float: left;
    }
    .small{
        font-size: 12px;
        color: #555;
    }
</style>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/user/accounting.min.js"></script>

<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.js"></script>

<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/user/jquery.validate.min.js"></script>

<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<? if('ru' == _e('lang')){ ?><script type="text/javascript" src="/js/user/messages_ru.js"></script><?}?>

<script type="text/javascript">
    var wire_bank_reqired_fileds = JSON.parse('<?= $wire_bank_reqired_fileds ?>');

    var security = 'sms';
    var security_type = 'sms';
</script>

<script type="text/javascript" src="/js/user/form_reg.js"></script>
<script type="text/javascript" src="/js/user/sms_module.js"></script>

<script type="text/javascript">

    function show_save_button(el, turn)
    {
        if( turn == 0 )
        {
            $('#various1').hide();
            $('#variousB').show();
            $('#phone').prop('readonly','true');
            $('.formPhone .phone-code .dd-select').addClass('blocked');
            return false;
        }

        $(el).hide();
        $('#various1').show();
        $('#phone').prop('readonly','');
        $('.formPhone .phone-code .dd-select').removeClass('blocked');
    }

    function strlen(string) {
        return string.length;
    }

    set_phone_without_sms = true;

    function obr_button() {
        var phone_val = document.getElementById('phone').value;
        var dlinna = strlen(phone_val);
        var code_src = $('.dd-selected-code-value').val(),
                code = code_src.replace(' ', ''),
                short = $('.dd-selected-short-name-value').val();

        if (code == '' || code == 'NULL' || code == undefined) {
            $('#popup_agree_confirm2').show('slow');
            return  false;
        }
        else if ((dlinna < 7) || (dlinna > 15)) {
            $('#popup_agree_confirm1').show('slow');
            return  false;
        } else {
            var phone = code + phone_val;
            var result = phone * 1; // умножаем на 1

            if (!isNaN(result))
            {
                $('#popup_phone .phone-text').html('+' + code + ' ' + phone_val);
                $('#popup_phone').show('slow');
                return  false;
            }
            else {
                $('#popup_agree_confirm0').show('slow');
                return  false;
            }  // Всплывающее окно "Не цифровые символы"

        }
        return  false;
    }

    function show_choose_verification_window() {
        $('#popup_phone_choose_verification').show();
        $('#out_send_window_sms').hide();
        $('#out_send_window_voice').hide();
    }
    function hide_choose_verification_window() {
        $('#popup_phone_choose_verification').hide();
    }

    function show_sms_request_window() {
        get_voice_verification_state();
        $('#out_send_window_sms').show();
        hide_choose_verification_window();
    }
    function show_voice_request_window() {
        $('#out_send_window_voice').show();
        hide_choose_verification_window();
    }

    function send_ajax__set_phone_callback( res )
    {
        console.log( 'send_ajax__set_phone_callback',res );

        if( typeof res['res'] === undefined ) return false;

        if( res['res'] == 'error'  ) return false;

        //success
        if( res['res'] != 'success'  ) return false;

        var phone = document.getElementById('phone').value;
        var code_src = $('.dd-selected-code-value').val(),
            pcode = code_src.replace(' ', ''),
            short = $('.dd-selected-short-name-value').val(),
            sec_code = 0;

        if( res['code'] !== undefined ) sec_code = res['code'];

        var window = '#out_send_window_message',
                url = site_url + '/account/ajax_user/set_phone',
                data = {pcode: pcode, phone: pcode + phone, short: short,
                        code: sec_code,
                        purpose: 'set_phone',
                        security_type:mn.security_module.security_type
                    };


        $.ajax({// делаем ajax запрос
            type: "POST",
            url: url,
            data: data,
            success: function (responce) { 			// выполнится после AJAX запроса
                if (responce == null) {
                    showMessage(window, '<?= _e('new_224') ?>', 'error');
                    $(window).show();
                    return;
                }
                var html = null;
                try {
                    html = JSON.parse(responce);
                } catch (e) {
                    showMessage(window, '<?= _e('new_225') ?>', 'error');
                    $(window).show();
                    return;
                }
<?php /*
                var enabled_voice_verification = false;
                if (html['data'] && html['data']['voice_verification_enabled'])
                {
                    enabled_voice_verification = html['data']['voice_verification_enabled'];
                }

                console.log('enabled_voice_verification');
                console.log(enabled_voice_verification);
                console.log(html);
*/?>
                if (html['error']) {
<?php /*
                    if (enabled_voice_verification == true)
                    {
                        $(window).hide();
                        window = '#popup_phone_choose_verification'
                        show_choose_verification_window();
                    }
                    else
*/?>

                    mn.security_module.loader.show(html['error'],5000);
                } else if (html['success']) {

                    show_save_button(null, 0);
                    mn.security_module.loader.show('<?php echo _e('security/data20') ?>',5000);

<?php /*
                    if (enabled_voice_verification == true)
                    {
                        $(window).hide();
                        //window = '#popup_phone_choose_verification'
                        show_choose_verification_window();
                    }
                    else
                    {
                        $('#out_send_window_sms').show();
                        sendSms('#out_send_window_sms', '/account/ajax_user/sms_request');
                    }

*/?>
                    //$('#choose_verification_type').show();
                } else {
                    showMessage(window, '<?= _e('new_226') ?>', 'error');
                    $(window).show();
                }
            }
        });



    }
    function send_ajax( )
    {
        mn.security_module.loader.show();

        mn.security_module
                    .init()
                    .show_window('set_phone')
                    .done(send_ajax__set_phone_callback);

        return false;

    }

    function set_phone_verified(res) {

        if (typeof res['res'] === undefined || res['res'] != 'success') {
            if (res['res'] == 'closed')
                location.reload();
            return false;
        }

        var data = {"code": res['code']};
        console.log(data);

        saved_code = res['code'];

        mn.get_ajax('/' + mn.site_lang + '/security/ajax/save_phone_verification', {data: JSON.stringify(data)},
        function (res) {
            console.log('save_phone_verification', res);

            if (res['error'])
            {
                mn.security_module.loader.show(res['error'], 20000);

            }

            if (res['success'])
            {
                mn.security_module.loader.show(res['success'], 5000);
                setTimeout(function () {
                    mn.security_module
                    .init()
                    .show_window('set_security_type', 'one_pass')
                    .done(save_security_call_back);
                }, 3000);
            }
        });
        return false;

        //mn.security_module.loader.show('<?php echo _e('Телефон подтвержден') ?>', 3000);
        //setTimeout(function () {
        //    mn.security_module
        //            .init()
        //            .show_window('set_security_type', 'one_pass')
        //            .done(save_security_call_back);
        //}, 3000);
    }

    function send_ajax_old( )
    {

        var phone = document.getElementById('phone').value;
        var code_src = $('.dd-selected-code-value').val(),
                code = code_src.replace(' ', ''),
                short = $('.dd-selected-short-name-value').val();

        var window = '#out_send_window_message',
                url = site_url + '/account/ajax_user/set_phone',
                data = {code: code, phone: code + phone, short: short};


        $.ajax({// делаем ajax запрос
            type: "POST",
            url: url,
            data: data,
            success: function (responce) { 			// выполнится после AJAX запроса
                if (responce == null) {
                    showMessage(window, '<?= _e('new_224') ?>', 'error');
                    $(window).show();
                    return;
                }
                var html = null;
                try {
                    html = JSON.parse(responce);
                } catch (e) {
                    showMessage(window, '<?= _e('new_225') ?>', 'error');
                    $(window).show();
                    return;
                }
                var enabled_voice_verification = false;
                if (html['data'] && html['data']['voice_verification_enabled'])
                {
                    enabled_voice_verification = html['data']['voice_verification_enabled'];
                }

                console.log('enabled_voice_verification');
                console.log(enabled_voice_verification);
                console.log(html);

                if (html['error']) {

                    if (enabled_voice_verification == true)
                    {
                        $(window).hide();
                        window = '#popup_phone_choose_verification'
                        show_choose_verification_window();
                    }
                    else
                        $(window).show();

                    showMessage(window, html['error'], 'error');
                } else if (html['success']) {

                    if (set_phone_without_sms) {
                        mn.security_module.loader.show('<?php echo _e('security/data20') ?>',5000);
                        return false;
                    }

                    if (enabled_voice_verification == true)
                    {
                        $(window).hide();
                        //window = '#popup_phone_choose_verification'
                        show_choose_verification_window();
                    }
                    else
                    {

                        mn.security_module
                                .init()
                                .show_window('phone_verification')
                                .done(set_phone_verified);

                        //$('#out_send_window_sms').show();
                        //sendSms('#out_send_window_sms', '/account/ajax_user/sms_request');
                    }


                    //$('#choose_verification_type').show();
                } else {
                    showMessage(window, '<?= _e('new_226') ?>', 'error');
                    $(window).show();
                }
            }
        });


    }
    function show_format() {
        alert('<?= _e('new_227') ?>');
    }

</script>
<script>
    $(function () {
        $('#sv_fact_dres').change(function ()
        {
            if ($(this).val() == 1 && $(this).attr('checked') == 'checked') {
                $('input[name=f_house]').val($('input[name=r_house]').val());
                $('input[name=f_index]').val($('input[name=r_index]').val());
                $('input[name=f_town]').val($('input[name=r_town]').val());
                $('input[name=f_street]').val($('input[name=r_street]').val());
                $('input[name=f_flat]').val($('input[name=r_flat]').val());
                $('input[name=f_kc]').val($('input[name=r_kc]').val());
            }
        });

        $('.dd-selected, .dd-pointer').click(function () {
            if ($('.dd-select').hasClass('blocked'))
                return false;
            $('.dd-options').toggleClass('dd-opened').slideToggle(100);
        });

        $('.dd-option').click(function () {
            if (!$('.dd-select').hasClass('blocked'))
                setCode(this);
            $('.dd-options').slideUp(100);
        });

        function setCode(el) {
            var res_code = $(el).data('code') + '',
                    short_name = $(el).data('short') + '',
                    code = res_code.trim();

            $('.dd-options .dd-option-selected').removeClass('dd-option-selected');
            $(el).addClass('dd-option-selected');

            $('.dd-selected-text').html('+' + code);
            $('.dd-selected-code-value').val(code);
            $('.dd-selected-short-name-value').val(short_name);
        }

        if ($('.dd-options .dd-option-selected').length == 1) {
            setCode('.dd-options .dd-option-selected');
        }
    });
</script>
<? if (!empty($id_change) and empty($message)) echo '<div class="message error">'._e('accaunt/profile_13').'</div>'; ?>
<div class="profile_top" style="padding:10px;border:1px solid #eee;margin:10px auto;">
    <?= _e('accaunt/profile_14') ?>
</div>

<?if($isntAjax){?>
<script type='text/javascript'><!--//<![CDATA[
   document.MAX_ct0 = unescape('{clickurl_enc}');

    var m3_u = (location.protocol == 'https:' ? 'https://webtransfer-finance.com/reklama/www/delivery/ajs.php' : 'https://webtransfer-finance.com/reklama/www/delivery/ajs.php');
    var m3_r = Math.floor(Math.random() * 99999999999);
    if (!document.MAX_used)
        document.MAX_used = ',';
    document.write ("<scr" + "ipt type='text/javascript' src='" + m3_u);
            <?php if ($this->lang->lang() == 'ru'){ ?>
            document.write ("?zoneid=7");
            <?php } ?>
            <?php if ($this->lang->lang() == 'en'){ ?>
            document.write ("?zoneid=8");
            <?php } ?>
            document.write('&amp;cb=' + m3_r);
    if (document.MAX_used != ',')
        document.write("&amp;exclude=" + document.MAX_used);
    document.write(document.charset ? '&amp;charset=' + document.charset : (document.characterSet ? '&amp;charset=' + document.characterSet : ''));
    document.write("&amp;loc=" + escape(window.location));
    if (document.referrer)
        document.write("&amp;referer=" + escape(document.referrer));
    if (document.context)
        document.write("&context=" + escape(document.context));
    if ((typeof (document.MAX_ct0) != 'undefined') && (document.MAX_ct0.substring(0, 4) == 'http')) {
        document.write("&amp;ct0=" + escape(document.MAX_ct0));
    }
    if (document.mmm_fo)
        document.write("&amp;mmm_fo=1");
    document.write("'><\/scr" + "ipt>");
//]]>--></script><br/><br/>
<?}?>
<div class="widget profile">
    <form id="wizard2" method="post" action="<?= site_url('account/profile') ?>" class="form">
        <input type="hidden" name="purpose" value="save_security_settings" />
        <input type="hidden" name="code" id="sms-code" value="" />
        <input type="hidden" name="submited" value="1" />
        <input type="hidden" name="buy_card" value="-1" />
        <input type="hidden" name = "id_user" id = "id_user" value="<?= $user->id_user ?>">
        <?= $profile_fieldsets ?>
        <div class="wizButtons">
            <div class="status" id="status2"></div>
            <span class="wNavButtons">
                <!--input class="basic" id="back2" value="Back" type="reset" /-->
                <input class="blueB ml10" id="next2" value="Next" type="submit"/>
            </span>
        </div>
    </form>
    <div class="data" id="w2"></div>
</div>

<!--
<a onclick="$('.password_change').show('slow');
        return  false;" style="padding:7px;margin-left:0px;margin-top:-8px;" class="but cancel" href="#">Изменить  пароль</a>
<a style="padding:7px;margin-left:0px;margin-top:-8px;" class="but cancel" href="/ask/forget">Запросить  пароль</a>
<div class="password_change">
    <div onclick="$('.password_change').hide('slow');" class="close"></div>
    <form action="" id="password_change" method="POST">
        <input  type="hidden"  name="submited"  value='1' />
        <div>
            <div style="width:180px; display:inline-block;">Старый  пароль:</div><input type="password" id="password_old_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="old_password"  style="padding:5px;margin:5px;" value="" /><br/>
            <div style="width:180px; display:inline-block;">Новый пароль:</div><input type="password" id="password_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="password"  style="padding:5px;margin:5px;" value="" /><br/>
            <div style="width:180px; display:inline-block;">Повторите пароль:</div><input type="password" id="password_password2"  style="padding:5px;margin:5px;" class=" validate[required,equals[password_password]]" name="password2" value="" />
        </div>

        <button name="submit" id="bn_login" type="submit" class="button">Изменить</button>
        <div class="password_return"></div>
    </form>

</div>
-->
<!--<div id="popup_preference">
    <div onclick="$('#popup_preference').hide('slow');" class="close"></div>
    <h2>Информация</h2>
    Если в Вашем аккаунте введены данные нескольких платежных систем, <br>
    то Вы може избрать предпочтительную платежную систему, <br>
    для получения на нее денежных средств.

    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" >Закрыть</a>
</div>	 -->
<div class="popup_window" id="popup_phone">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_16') ?></h2>
    <?= _e('accaunt/profile_17') ?> <span class='phone-text'></span>

    <center>
        <a class="button narrow inline-block" onclick="
                $(this).parent().parent().hide('slow');
                send_ajax();
                return false;" ><?= _e('accaunt/profile_18') ?></a>
        <a class="button narrow inline-block" onclick="$(this).parent().parent().hide('slow');
                return false;" ><?= _e('accaunt/profile_19') ?></a>
    </center>
</div>
<div class="popup_window" id="popup_phone_choose_verification" style="width:425px">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('Выберите тип верификации') ?></h2>

    <center>
        <a class="button narrow inline-block" onclick="show_sms_request_window();
                return false;" style="float: left; margin-right: 20px"><?= _e('СМС') ?></a>
        <a class="button narrow inline-block" onclick="show_voice_request_window();
                return false;" style="line-height: 14px;" ><?= _e('Голосовая верификация') ?></a>
    </center>
</div>

<div id="popup_payment">
    <div onclick="$('#popup_payment').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_20') ?></h2>
    <?= _e('accaunt/profile_21') ?>

    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_22') ?></a>
</div>

<div id="popup_debit">
    <div onclick="$('#popup_debit').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_23') ?></h2>
    <?= _e('accaunt/profile_24') ?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>
<div class="popup_window" id="popup_bank">
    <div onclick="$('#popup_bank').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_26') ?></h2>
    <?= _e('accaunt/profile_27') ?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>

<div class="popup_window"  id="popup_agree_confirm1" >
    <div onclick="$('#popup_agree_confirm1').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_28') ?></h2>
    <?= _e('accaunt/profile_29') ?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>

<div class="popup_window" id="popup_agree_confirm0">
    <div onclick="$('#popup_agree_confirm0').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_30') ?></h2>
    <?= _e('accaunt/profile_31') ?><br />
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>
<div class="popup_window" id="popup_agree_confirm2">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_32') ?></h2>
    <?= _e('accaunt/profile_33') ?> <br />
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>

<div id="popup_confirm_return" class="popup_window" >
    <div onclick="$('#popup_confirm_return').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_34') ?></h2>
    <?= _e('accaunt/profile_35') ?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit" ><?= _e('accaunt/profile_25') ?></a>
</div>
<div id="choose_verification_type" class="popup_window" >
    <div onclick="$('#choose_verification_type').hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_16_1') ?></h2>

    <label class="label_">
        <input type="radio" name="security" value="sms" checked='' > <?= _e('accaunt/profile_16_2') ?>
    </label>
    <!--label class="label_">
        <input type="radio" name="security" value="whatsapp" > <?= _e('accaunt/profile_16_3') ?>
    </label>
    <label class="label_">
        <input type="radio" name="security" value="viber" > <?= _e('accaunt/profile_16_4') ?>
    </label-->
    <a class="button narrow"><?= _e('accaunt/security_19') ?></a>
</div>

<div id="out_send_window_whatsapp" class="popup_window" style="z-index:99999;">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('new_228') ?></h2>
    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <label class="label"><?= _e('new_229') ?></label>
        <div class="formRight" style="width: 68% !important;">
            <div class="sms-content">
                <input class="form_input" id="whatsapp_code" type="text" value="" style="height: 17px; width: 100px;" />
                <a class="but blueB ml10 send_code_link right" href="#"><?= _e('new_230') ?></a>
            </div>
        </div>
        <div class="res-message"></div>
    </div>
    <a class="button narrow" >Ok</a>
</div>

<div id="out_send_window_viber" class="popup_window" style="z-index:99999;">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('new_231') ?></h2>
    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <label class="label"><?= _e('new_229') ?></label>
        <div class="formRight" style="width: 68% !important;">
            <div class="sms-content">
                <input class="form_input" id="viber_code" type="text" value="" style="height: 17px; width: 100px;" />
                <a class="but blueB ml10 send_code_link right" href="#"><?= _e('new_230') ?></a>
            </div>
        </div>
        <div class="res-message"></div>
    </div>
    <a class="button narrow" >Ok</a>
</div>


<div id="popup_wire_bank" class="popup_window" style="z-index:99999;">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('accaunt/profile_bank_12') ?></h2>
    <?= _e('accaunt/profile_bank_13') ?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');">Ok</a>
</div>

<div id="out_send_window_voice" class="popup_window" style="display: none;">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('Подтверждение голосом') ?></h2>
    <div style="width: 490px; margin: auto auto  10px auto; text-align: justify;"><?=
        _e('Воспользуйтесь бесплатной голосовой верификацией. После нажатия кнопки, вам позвонит оператор-робот и назовет код голосом.')
        ?></div>
    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <label class="label" style="margin-top:-5px"><?= _e('voice_choose_language') ?></label>
        <div class="formRight" style="width:64%!important">
            <div class="sms-content">
                <select class="voice_language" name="language" style="height: 27px; width: 140px; margin-right: 5px; -webkit-appearance: menulist-button;">
                    <option value="" selected="">-</option>
                    <option value="ru">Russian</option>
                    <option value="en">English</option>
                </select>
                <a class="but blueB ml10 send_code_link right" onclick="send_voice_code_link();
                        return false;" style="padding: 6px !important; line-height: 12px; padding-bottom: 8px !important" href="#"><?= _e('Позвоните мне') ?></a>
            </div>

        </div>
    </div>
    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <label class="label" style="margin-top:-5px"><?= _e('new_229') ?></label>
        <div class="formRight" style="width:64%!important">
            <div class="sms-content">
                <input class="form_input" id="voice_code" type="text" value="" style="height: 17px;width:135px;"/>
            </div>

        </div>
        <div class="res-message"></div>
    </div>
    <a class="button narrow" >Ok</a>
</div>
<div id="out_send_window_sms" class="popup_window">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('СМС-подтверждение') ?></h2>

    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <label class="label" style="margin-top:-5px"><?= _e('new_229') ?></label>
        <div class="formRight" style="width:64%!important">
            <div class="sms-content">
                <input class="form_input" id="sms_code" type="text" value="" style="height: 17px;width:125px;"/>
                <a class="but blueB ml10 send_code_link right" href="#" onclick="sendSms('#out_send_window_sms', '/account/ajax_user/sms_request');
                        return false;"><?= _e('new_232') ?></a>
            </div>

        </div>
        <div class="res-message"></div>
    </div>

    <a class="button narrow out_send_window_sms_ok_button" onclick="out_send_window_sms_ok_button();
            return false;">Ok</a>
    <a class="button narrow show_choose_verification_button" onclick="show_choose_verification_window();
            return false;" style="display: none; line-height: 14px;"><?= _e('Выбрать метод<br/>верификации') ?></a>

</div>
<div id="out_send_window_message" class="popup_window">
    <div onclick="$(this).parent().hide('slow');" class="close"></div>
    <h2><?= _e('Информация') ?></h2>
    <div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">
        <div class="res-message"></div>
    </div>
    <a class="button narrow" onclick="$('#out_send_window_message').hide();">Ok</a>
</div>


<div id="card_window_message" class="popup_window">
       <div class="modal fade in modal-visa-card" id="modal-visa-card" role="dialog" aria-hidden="false" style="left: 0px; display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="$('#card_window_message').hide();"></div>
                    <h4 class="modal-title"><?= _e('Webtransfer VISA Card') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="girl-cardn"></div>
                    <div class="i-want-card"> <?= _e('хотите бесплатную Webtransfer VISA card (virtual)?') ?></div>
                    <form class="form-style-modal">
                        <button type="button" class="btn btn-primary left-wt-b" data-dismiss="modal" onclick="buy_card(); return false;"><?= _e('ДА') ?></button>
                        <button type="button" class="btn right-wt-b" data-dismiss="modal" onclick="skip_card();return false;"><?= _e('НЕТ') ?></button>
                        <label>
                            <input type="checkbox" name="nomore"/>
                           <?= _e('Больше не показывать') ?>
                        </label>

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="res-message"></div>
                    <center>
                        <img class="loader" style="display: none" src="/images/loading.gif">
                        <center></center>
                    </center>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

   var valid_regex = {"latin": /[^a-z0-9\.\,\- \-]/i, "latin_num_symbols": /[^a-z0-9 ,./\\-]/i, "latin_num": /[^a-z0-9 ]/i, "num": /[^0-9]/i};
    $("#wizard2 input[data-filter]").each(function () {
        var filter = $(this).data('filter');
        var register = $(this).data('register');
        if (filter != undefined && valid_regex[filter] != undefined) {
            $(this).on('keyup', function (e) {
                this.value = this.value.replace(valid_regex[filter], "", "");
                switch (register) {
                    case "upper":
                        this.value = this.value.toUpperCase();
                        break;
                    default:
                        break;
                }

            });
            $(this).on('keydown', function (e) {
                var key = event.which || event.keyCode || event.charCode;
                var limiter = $(this).data('limiter');
                if (limiter != undefined && parseInt(limiter) <= this.value.length && key != 8 && !e.ctrlKey) {
                    e.preventDefault();
                }
            });
        }
    });


    function send_voice_code_link() {
        var lang = $('.voice_language').val();

        if (!lang)
        {
            $('.voice_language').css('border', '2px solid red');
            return false;
        }
        $('.voice_language').css('border', '1px solid #ccc');

        sendSms('#out_send_window_voice', '/account/ajax_user/voice_request', lang, 1);
        return false;
    }
    ;

    var saved_code = null;

    function out_send_window_sms_ok_button() {
        var code = $('#sms_code').val();
        saved_code = code;
        send_verification('#out_send_window_sms', {code: code}, '/account/ajax_user/phone_confirm/');
        return false;
    }

    $(function () {

        console.log('show_data');
        $._data($("#user-main-menu_item_amount"), "events");

        //​$._data( $("#user-main-menu_item_amount")[0], "events" );


        $('#choose_verification_type .button').on('click', function () {

            security_type = $('input[name=security]:checked').val();

            if (security_type == 'sms')
            {
                $('#out_send_window_sms').show();
                //sendSms( '#out_send_window_sms','/account/ajax_user/sms_request');
            }

            $('#choose_verification_type').hide();
            return false;
        });
        $('#next3').on('click', function () {
            obr_cod();
            return false;
        });


        $(document).on('click', '#out_send_window_voice .button', function () {
            var code = $('#voice_code').val();
            send_verification('#out_send_window_voice', {code: code}, '/account/ajax_user/phone_confirm/');
            return false;
        });




    });
    function showMessage(el, message, type, counter) {
        if (counter == undefined) {
            var counter = 2000;
        }
        $(el).find('.res-message')
                .html(message)
                .removeClass('error')
                .removeClass('success')
                .addClass(type)
                .css('display', 'block');

        if (counter > 0)
            setTimeout(function () {
                $(el).find(' .res-message').hide('slow')
                        .html('').hide(1);
            }, counter);
    }

    function get_message_text(timer) {
        var text = '<?= _e('new_233') ?> <span id="phoneTimer">' + timer + '</span> <?= _e('new_234') ?>.';
        return text;
    }

    function get_voice_verification_state()
    {
        $.post(site_url + '/account/ajax_user/voice_state')
                .done(function (data) {
                    $('.loading-gif').hide();

                    try {
                        message = JSON.parse(data);
                    } catch (e) {
                        showMessage(window, '<?= _e('accaunt/security_20') ?>', 'error');
                    }

                    var enabled_voice_verification = message['state'];

                    if (enabled_voice_verification == true)
                    {
                        $('.show_choose_verification_button').css('float', 'right').show();
                        $('.out_send_window_sms_ok_button').css('float', 'left');
                    }

                });
    }

    function sendSms(window, url, lang, ret) {
        var purpose = 'phone_verification',
                message = null;

        if (!ret)
        {
            var ret = 1;
            get_voice_verification_state();
        }

        $.post(site_url + url, {purpose: purpose, lang: lang})
                .done(function (data) {
                    $('.loading-gif').hide();

                    try {
                        message = JSON.parse(data);
                    } catch (e) {
                        showMessage(window, '<?= _e('accaunt/security_20') ?>', 'error');
                    }

                    var enabled_voice_verification = false;
                    if (message['data'] && message['data']['voice_verification_enabled'])
                    {
                        enabled_voice_verification = message['data']['voice_verification_enabled'];
                    }

                    console.log('enabled_voice_verification-22-');
                    console.log(enabled_voice_verification);
                    console.log(message);


                    if (message['error']) {

                        if (enabled_voice_verification == true && !$('#out_send_window_voice').is(":visible"))
                        {
                            $(window).hide();
                            window = '#popup_phone_choose_verification'
                            show_choose_verification_window();
                        }
                        else
                            showMessage(window, message['error'], 'error');

                    } else
                    if (message['success'] == 'OK' && message['action'] == 'start-counter')
                    {
                        var message_text = get_message_text(120);

                        showMessage(window, message_text, 'success', 0);
                        $(window).find('.send_code_link').hide();
                        console.log(window);

                        var val = $('#phoneTimer').html();
                        val = parseInt(val);

                        var phoneTimer = setInterval(function () {

                            val--;
                            message_text = get_message_text(val);

                            $(window)
                                    .find('.res-message')
                                    .show()
                                    .html(message_text);

                            if (val - 1 <= 0) {
                                //if( phone_sms_attempts < 3 )
                                $(window).find('.send_code_link').show();
                                //else
                                //    $(window).find( '.send_voice_code_link' ).show();

                                $(window).find('.res-message').hide();
                                clearInterval(phoneTimer);
                            }

                        }, 1000);
                    } else
                    if (message['success']) {
                        showMessage(window, message['success'], 'success');
                    } else {
                        showMessage(window, '<?= _e('accaunt/security_21') ?>', 'error');
                    }

                    return false;
                });
        return false;
    }
    function send_verification(window, data, url) {			// подпрограмма Ajax
        $.post(site_url + url, data)
                .done(function (data) {
                    $('.loading-gif').hide();

                    try {
                        message = JSON.parse(data);
                    } catch (e) {
                        showMessage(window, '<?= _e('accaunt/security_20') ?>', 'error');
                    }

                    if (message['error']) {
                        showMessage(window, message['error'], 'error');
                    } else
                    if (message['success']) {
                        if (message['action'] && message['action'] == 'show-ok') {
                            showMessage(window, message['success'], 'success');
                            setTimeout(function () {
                                $('#out_send_window_sms').hide();
                                mn.security_module
                                        .init()
                                        .show_window('set_security_type', 'one_pass')
                                        .done(save_security_call_back);
                            }, 3000);
                        } else
                            showMessage(window, message['success'], 'success');
                    } else {
                        showMessage(window, '<?= _e('accaunt/security_21') ?>', 'error');
                    }

                    return false;
                });

        return false;
    }

    function save_security_call_back(res)
    {

        if (typeof res['res'] === undefined || res['res'] != 'success') {
            if (res['res'] == 'closed')
                location.reload();
            return false;
        }

        var data = {"old": {"type": "", "code": saved_code}, 'new': {type: 'one_pass', code: res['code']}};
        console.log(data);

        mn.get_ajax('/' + mn.site_lang + '/security/ajax/save_security_type', {data: JSON.stringify(data)},
        function (res) {
            console.log('save_security_call_back', res);

            if (res['error'])
            {
                mn.security_module.loader.show(res['error'], 20000);

            }

            if (res['success'])
            {
                mn.security_module.loader.show(res['success'], 10000);
            }

            saved_code = null;
            location.reload();
        });
        return false;

    }

    function chek_phone_tt() {
        var phone = document.getElementById('phone').value;
        var dlinna = strlen(phone);
        var phone_rules = "<?= _e('accaunt/profile_36') ?>";

        if ((dlinna < 7) || (dlinna > 15)) {
            $('#popup_agree_confirm1').show('slow');
            return  false;
        } // Всплывающее окно "Не правильная длинна телефона"
        else
        {
            var result = phone * 1; // умножаем на 1
            if (!isNaN(result))
            {
                var chek_start_plus = phone.indexOf('+');
                var chek_start_minus = phone.indexOf('-');

                if ((chek_start_plus == 0) || (chek_start_minus == 0))
                {
                    $('#popup_confirm_return').show('slow');
                    return  false;
                }				// Всплывающее окно "Номер должен начинаться с цифры"
                else
                {
                    // id_user = document.getElementById('id_user').value;
                    // var data = 'query='+phone+'/'+id_user;
                    // send_ajax(data, data);
                }
            }
            else {
                $('#popup_agree_confirm0').show('slow');
                return  false;
            }  // Всплывающее окно "Не цифровые символы"
        }
    }


    function buy_card(){
        
        //window.open('https://webtransfercard.com');
        if ( $('#card_window_message [name=nomore]:checked').length ){
             createCookie("reg_buy_card_dialog", "1", 365);
        }


        $('#card_window_message').hide();
        $('#wizard2 [name=buy_card]').val(1);
        $('#next2').click();

    }

    function skip_card(){
        if ( $('#card_window_message [name=nomore]:checked').length ){
             createCookie("reg_buy_card_dialog", "1", 365);
        }

        $('#card_window_message').hide();
        $('#wizard2 [name=buy_card]').val(0);
        $('#next2').click();
    }

    function is_all_fields_filled(){

        return ( $('[name=n_name]').val() != '' && $('[name=f_name]').val() != '' && $('[name=born_date]').val() != '' && $('[name=r_index]').val() != '' & $('[name=r_town]').val() != '' && $('[name=r_street]').val() != '' && $('[name=email123]').val() != '');

    }

    function on_reg_form_submit(){

          //createCookie("reg_buy_card_dialog", "", -1);
        <? if ( empty($wtcards) && config_item("create_card_on_registration") ){ ?>
        if ( $('#wizard2 [name=buy_card]').val() == -1 && is_all_fields_filled() && getCookie("reg_buy_card_dialog") != "1" ){
            $('#card_window_message').show();
            return false;
        }
        <? } ?>
        return true;

    }

</script>
