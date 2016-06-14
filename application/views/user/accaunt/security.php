<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<!--<script type="text/javascript" src="jquery-1.11.0.min.js"></script>		<!-- Ajax library Русаков -->


<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<style>
    .form .formRight .w10{
        width: 60px !important;
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
    .label_{
        margin: 0 11px;
    }
</style>


<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>-->
<script type="text/javascript" src="js/user/accounting.min.js"></script>
<script src="https://malsup.github.com/jquery.form.js"></script>
<!--<script type="text/javascript" src="js/user/sms_module.js"></script>-->

<script>
    var old_security_type = security;
    var new_security_type = null;
    
    function save_security_type(force) {
//        var $this = $(this);

//            if( $(this).hasClass('closed') ) return false;
//        $(this).addClass('closed');            

        new_security_type = $('input[name="security"]:checked').val();
        security_type = new_security_type;

        if ((force == false) && (old_security_type == new_security_type))
            return false;

        if( new_security_type == 'email' )
        {
            mn.security_module.loader.show();
            
            mn.get_ajax( '/' + mn.site_lang + '/security/ajax/email_state', {},
            function(res){
                console.log('save_security_call_back',res);

                if( res['error'] )
                {
                    mn.security_module.loader.show(res['error'],20000);
                    return false;
                }

                if( res['success'] )
                {
                    console.log('save_security_type-success');
                    
                    mn.security_module
                        .init()
                        .show_window('change_security_type')
                        .done(save_security_call_back);

                    return false;
                }

            });
            return false;
        }
        
        mn.security_module
                    .init()
                    .show_window('change_security_type')
                    .done(save_security_call_back);

               
        return false;
    };
    
    var code_data = null;
    function save_security_call_back(res)
    {
        
        if( typeof res['res'] === undefined || res['res'] != 'success' ) return false;
        console.log( 'code',window.code_data );

        mn.security_module.loader.show();

        if( mn.empty( new_security_type ) || new_security_type == 'email' )
        {
            var data = {old: { type: old_security_type, code: res['code'] }, 'new': { type: new_security_type, code: 0 } };
            console.log( data );
            
            
            
            mn.get_ajax( '/' + mn.site_lang +'/security/ajax/save_security_type', {data: JSON.stringify(data) },
            function(res){
                console.log('save_security_call_back',res);
                if( res['action'] == 'refesh_page' ) location.reload();
                
                if( res['error'] )
                {
                    mn.security_module.loader.show(res['error'],20000);
                    return false;
                }

                if( res['success'] )
                {
                    mn.security_module.loader.show(res['success'],10000);
                    return false;
                }

            });
            return false;
                
        }else{
            if( window.code_data != null && window.code_data.t != mn.security_module.security_type )
            {
                var data = {old: window.code_data, 'new': { type: new_security_type, code: res['code'] } };
                console.log( data );

                mn.get_ajax( '/'+mn.site_lang+'/security/ajax/save_security_type', {data: JSON.stringify(data) },
                function(res){
                    console.log('save_security_call_back',res);
                    if( res['action'] == 'refesh_page' ) location.reload();
                    
                    if( res['error'] )
                    {
                        mn.security_module.loader.show(res['error'],20000);
                        
                    }
                    
                    if( res['success'] )
                    {
                        mn.security_module.loader.show(res['success'],10000);
                    }
                    window.code_data = null;
                });
                return false;
            }
        }

        if( window.code_data == null ) 
        {
            window.code_data = { t: mn.security_module.security_type ,type : old_security_type, code: res['code'] };
            console.log( 'window.code_data',window.code_data );
        }
        

        console.log( window.code_data );

        mn.security_module
                .init()
                .show_window('set_security_type',new_security_type)
                .done(save_security_call_back);

    }

    
</script>


<div class="widget profile security">

        <input class="ui-wizard-content" name="submited" value="1" type="hidden">
        <input class="ui-wizard-content" name="id_user" id="id_user" value="500733" type="hidden">
        <fieldset style="display: block;" class="step ui-formwizard-content">
            <div class="title" style="cursor: pointer;" onclick="$(this).next().slideToggle();"><img src="/images/icons/dark/pencil.png" alt="" class="titleIcon">
                <h6><span style="display: inline-block; margin-right: 10px;">►</span><?=_e('new_249')?></h6>
            </div>
            <div class="cont-security">
                <div class="formRow padding10-0">
                    <label class="label_"><input type="radio" name="security" value="" autocomplete="off" <?=((!$i->value)?"checked":"")?>> <?=_e('new_244')?></label>
                    <?if(0){?>
                        <label class="label_"><input type="radio" name="security" value="sms" autocomplete="off" <?=(("sms" == $i->value)?"checked":"")?>> <?=_e('new_245')?></label>
                    <?}?>
                    <label class="label_"><input type="radio" name="security" value="code" autocomplete="off" <?=(("code" == $i->value)?"checked":"")?>> <?=_e('new_246')?></label>
                    <?if(0){?>
                        <!--label class="label_"><input type="radio" name="security" value="whatsapp" autocomplete="off" <?=(("whatsapp" == $i->value)?"checked":"")?>> <?=_e('new_247')?></label>
                        <label class="label_"><input type="radio" name="security" value="viber" autocomplete="off" <?=(("viber" == $i->value)?"checked":"")?>> <?=_e('new_248')?></label-->
                     <?}?>
                    <?if( $no_email == TRUE ){?>
                        <label class="label_"><input type="radio" name="security" value="email" autocomplete="off" <?=(("email" == $i->value)?"checked":"")?>> <?=_e('new_270')?></label>
                    <?}?>
                    <label class="label_"><input id="one_pass" type="radio" name="security" value="one_pass" autocomplete="off" <?=(("one_pass" == $i->value)?"checked":"")?>> <?=_e('security_one_pass')?>
                    <?php if( $i->value == 'one_pass' ): ?>
                        <?php if( _e('lang') == 'en' ){ ?>
                            <br/><a href="#" style="height: 0px;line-height: 0px;width: 70px;margin: 5px 0 0 0;display: block;float: right;" class="but blueB" onclick="$('#one_pass').attr('checked', 'checked'); save_security_type(true); return false;"><?= _e('new_one_pass') ?></a>
                        <?php }else{ ?>
                            <br/><a href="#" style="height: 0px;line-height: 0px;width: 100px;margin: 5px 0 0 0;display: block;float: right;" class="but blueB" onclick="$('#one_pass').attr('checked', 'checked'); save_security_type(true); return false;"><?= _e('new_one_pass') ?></a>
                        <?php } ?>
                    <?php endif; ?>
                    </label>
                    <a id="security" onclick="save_security_type(false); return false;" style="float:right;" class="but blueB" href="#" data-value="<?=$i->value?>" data-name="<?= $i->machine_name ?>" >
                        <span><?=_e('new_243')?></span>
                    </a>
                    <script>
                        $(".label_").click(function(){
                            $("#security").data("value",$(this).find("input").val());
                        });
                    </script>
                </div>
            </div>

        </fieldset>
        <form id="change_pass" action="<?=site_url('account/security')?>" method="POST">
        <fieldset style="display: block;" class="step ui-formwizard-content" >
            <input type="hidden" name="submited" value="1"/>
            <div class="title" style="cursor: pointer;" onclick="$(this).next().slideToggle();"><img src="/images/icons/dark/pencil.png" alt="" class="titleIcon">
                <h6><span style="display: inline-block; margin-right: 10px;">►</span><?=_e('accaunt/security_4')?></h6>
            </div>
            <div class="cont-security" style="display:none;">
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/security_5')?><span class="req">*</span>:</label>
                    <div class="formRight">
                        <input type="password" id="password_old_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="old_password"  style="padding:5px;margin:5px;" value="<?= $pass_old ?>" />
                    </div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/security_6')?><span class="req">*</span>:</label>
                    <div class="formRight">
                        <input type="password" id="password_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]] ui-wizard-content" name="password"  style="padding:5px;margin:5px;" value="<?= $pass_new ?>" />
                    </div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/security_7')?><span class="req">*</span>:</label>
                    <div class="formRight">
                        <input type="password" id="password_password2"  style="padding:5px;margin:5px;" class=" validate[required,equals[password_password]] ui-wizard-content" name="password2" value="<?= $pass_new_confirm ?>" />
                    </div>
                </div>
                <div class="formRow padding10-0">
                    <span class="wNavButtons right" style="width: 119px; /* margin-right: 10px; */ ">
                        <input id="pass_change_save" class="but blueB" value="<?=_e('new_243')?>" type="submit" style="float: right;width: 102px;/* height: 40px; */">
                    </span>
                </div>
            </div>
            
            
                <div class="title" style="cursor: pointer;" onclick="$(this).next().slideToggle();"><img src="/images/icons/dark/pencil.png" alt="" class="titleIcon">
                    <h6><span style="display: inline-block; margin-right: 10px;">►</span><?=_e('accaunt/security_9')?></h6>
                </div>
                <div class="cont-security" style="display:none;">
                    <div class="formRow padding10-0">
                        <label><?=_e('accaunt/security_10')?><span class="req">*</span>:</label>
                        <div class="formRight" style="width: 400px;">
                            <a class="but blueB ml10 ui-wizard-content ui-formwizard-button right" href="<?=site_url('ask/forget')?>"><?=_e('accaunt/security_11')?></a>
                        </div>
                    </div>
                </div>
            
            <? if( @$confirm_email === TRUE ): ?>
                <div class="title" style="cursor: pointer;" onclick="$(this).next().slideToggle();"><img src="/images/icons/dark/pencil.png" alt="" class="titleIcon">
                    <h6><span style="display: inline-block; margin-right: 10px;">►</span><?=_e('Подтверждение email')?></h6>
                </div>
                <div class="cont-security" style="display:none;">
                <div class="formRow padding10-0">
                    <label><?=_e('Подтвердить email')?><span class="req">*</span>:</label>
                    <div class="formRight" style="width: 400px;">
                        <a class="but blueB ml10 ui-wizard-content ui-formwizard-button right" href="<?=site_url('login/email_activate')?>"><?=_e('Подтвердить')?></a>
                    </div>
                </div>
                </div>
            <? endif; ?>
        </fieldset>

    </form>
    <div class="data" id="w2"></div>
</div>
<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
<div class="password_change">
    <div onclick="$('.password_change').hide('slow');" class="close"></div>
    <form action="" id="password_change" method="POST">
        <input  type="hidden"  name="submited"  value='1' />
        <div>
            <div style="width:180px; display:inline-block;"><?=_e('accaunt/security_12')?></div><input type="password" id="password_old_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="old_password"  style="padding:5px;margin:5px;" value="" /><br/>
            <div style="width:180px; display:inline-block;"><?=_e('accaunt/security_13')?></div><input type="password" id="password_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="password"  style="padding:5px;margin:5px;" value="" /><br/>
            <div style="width:180px; display:inline-block;"><?=_e('accaunt/security_14')?></div><input type="password" id="password_password2"  style="padding:5px;margin:5px;" class=" validate[required,equals[password_password]]" name="password2" value="" />
        </div>

        <button name="submit" id="bn_login" type="submit" class="button"><?=_e('accaunt/security_15')?></button>
        <div class="password_return"></div>
    </form>

</div>
