<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
 <div class="content"  style="margin: 30px auto; width: 600px;">
<h1 class="title"><?=_e('loginza_title')?></h1>
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
<h6><?=_e('loginza_h6_mail')?></h6><span id="get_ip"><?=$this->input->ip_address()?></span>
</div>
<form id="wizard2" class="form" action="<?=site_url('loginza')?>" method="post"><fieldset id="w2first" class="step">
<input type="hidden" name="type" value="4" />



 <div id="status_answer"  style="display:  none"></div>



<div class="formRow"><label><?=_e('loginza_label_mail')?><span class="req">*</span></label>
<div class="formRight"><input id="form_email" type="text" name="email"  value="<?=$fields->email?>"/></div>
<div class="clear"></div>
</div>
</fieldset>
<div class="wizButtons">
<div id="status2" class="status"></div>
<span class="wNavButtons"> <input id="back2" class="basic" type="reset" value="<?=_e('loginza_input_reset')?>" /> <input id="next2" class="blueB ml10" type="submit" value="<?=_e('loginza_input_submit')?>" /> </span></div>
<div class="clear"></div>
</form>
<div id="w2" class="data"></div>
</div>
<div class="clearfix"></div></div>


