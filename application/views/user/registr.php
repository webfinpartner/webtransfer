<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
 <div class="content"  style="margin: 30px auto; width: 600px;">
<h1 class="title"><?=_e('regist_title')?></h1>
<div class="news_full">


 <link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/user/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="/js/user/ui.datepicker-ru.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/user/accounting.min.js"></script>
<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.js"></script>
<script type="text/javascript" src="/js/user/jquery.validate.js"></script>
<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<? if('ru' == _e('lang')){ ?><script type="text/javascript" src="/js/user/messages_ru.js"></script><?}?>
<script type="text/javascript" src="/js/user/form_reg.js"></script>

<!-- Wizard with custom fields validation -->
<div class="widget">
<div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
<h6><?=_e('regist_title')?></h6><span id="get_ip"><?=$this->input->ip_address()?></span>
</div>
<form id="wizard2" class="form" action="<?=site_url('registration')?>" method="post"><fieldset id="w2first" class="step">
<input type="hidden" name="type" value="4" />



  <div class="formRow">
                        <span><input type="radio"  checked="checked" name="face" value="1" /><?=_e('regist_face1')?> <input type="radio" name="face" value="2" /><?=_e('regist_face2')?> </span>
                        <div class="formRight"></div>
                        <div class="clear"></div>
<div class="formRow  yur"><label><?=_e('regist_yur_w_name')?></label>
<div class="formRight"><input id="form_w_name" type="text" name="w_name" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label class="yur_name"><?=_e('regist_yur_n_name')?><span class="req">*</span></label>
<div class="formRight"><input type="text" name="n_name" value="<?=$fields->n_name?>" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label class="yur_family"><?=_e('regist_yur_f_name')?><span class="req">*</span></label>
<div class="formRight"><input type="text" name="f_name" value="<?=$fields->f_name?>" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label><?=_e('regist_yur_o_name')?><span class="req">*</span></label>
<div class="formRight"><input type="text" name="o_name"  value="<?=$fields->o_name?>" /></div>
<div class="clear"></div>
</div>
<div class="formRow yur"><label><?=_e('regist_yur_w_who')?></label>
<div class="formRight"><input id="form_w_who" type="text" name="w_who" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label><?=_e('regist_phone')?><span class="req">*</span></label>
<div class="formRight"><input class="maskPhone" type="text" name="phone" value="<?=$fields->phone?>" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label class="yur_place"><?=_e('regist_region')?></label>
<div class="formRight" style="margin-top:-10px;"><?=get_region(true)?></div>
<div class="clear"></div>
</div>



<div class="formRow"><label><?=_e('regist_email')?><span class="req">*</span></label>
<div class="formRight"><input id="form_email" type="text" name="email"  value="<?=$fields->email?>"/></div>
<div class="clear"></div>
</div>
<div class="formRow"><label><?=_e('regist_password')?><span class="req">*</span></label>
<div class="formRight"><input id="form_password" type="password" name="password" value="" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label><?=_e('regist_password2')?><span class="req">*</span></label>
<div class="formRight"><input type="password" name="password2" value="" /></div>
<div class="clear"></div>
</div>

</fieldset>
<div class="wizButtons">
<div id="status2" class="status"></div>
<span class="wNavButtons"> <input id="back2" class="basic" type="reset" value="<?=_e('regist_reset')?>" /> <input id="next2" class="blueB ml10" type="submit" value="<?=_e('regist_submit')?>" /> </span></div>
<div class="clear"></div>
</form>
<div id="w2" class="data"></div>
</div>
<div class="clearfix"></div></div>


