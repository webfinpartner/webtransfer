<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<style>
    
</style>
<div class="mail">
    
    <h2><?=_e('Редактировать данные')?></h2>
    
    <div class="profile_top" style="padding:10px;border:1px solid #eee;margin:10px auto;">
        <?=_e('accaunt/edit_profile_username_01');?>
    </div>
    
    <div class="widget profile">
        <form novalidate="novalidate" id="wizard2" method="post" action="<?= _e('lang') ?>/login/edit_profile_username" class="form ui-formwizard">
            
            <input class="ui-wizard-content" name="submited" value="1" type="hidden">
            <input class="ui-wizard-content" name="id_user" id="id_user" value="<?= @$user['id_user']  ?>" type="hidden">
            
            <fieldset style="display: block;" class="step ui-formwizard-content" id="w2first">
                
                <div class="formRow padding10-0">
                    <label>
                        <div><?=_e('accaunt/edit_profile_username_02');?></div>
                        <div class="small"><?=_e('accaunt/edit_profile_username_03');?></div>
                    </label>
                    <div class="formRight"><input class="ui-wizard-content" name="nickname" value="<?= @$user['nickname']  ?>" type="text"></div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/edit_profile_username_04');?></label>
                    <div class="formRight">
                        <input class="ui-wizard-content" name="is_show" value="1" type="checkbox" <?= ($user["is_show"] === TRUE)?'checked="checked"':'' ?> >
                    </div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/edit_profile_username_05');?> <span class="req">*</span></label>
                    <div class="formRight"><input class="ui-wizard-content" name="n_name" value="<?= @$user["n_name"]  ?>" type="text"></div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/edit_profile_username_06');?> <span class="req">*</span></label>
                    <div class="formRight"><input class="ui-wizard-content" name="f_name" value="<?= @$user["f_name"]  ?>" type="text"></div>
                </div>
                <div class="formRow padding10-0">
                    <label><?=_e('accaunt/edit_profile_username_07');?></label>
                    <div class="formRight"><input class="ui-wizard-content" name="o_name" value="<?= @$user["o_name"]  ?>" type="text"></div>
                </div>
                
            </fieldset>
            <div class="wizButtons">
                <div class="status" id="status2"></div>
                <span class="wNavButtons">                    
                    <input class="blueB ml10 ui-wizard-content ui-formwizard-button" id="next2" value="<?=_e('accaunt/edit_profile_username_08');?>" type="submit">
                </span>
            </div>

        </form>
        <div class="data" id="w2"></div>
    </div>
</div>