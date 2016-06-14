<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
.mail {margin: 0 auto; text-align: center;  height:  300px;}
</style>
 <div class="mail">


        <h2><?=_e('recovery_h2')?></h2>
 <? if($error===1){?><div class="mail_m"><h2><?=_e('recovery_error1')?><h2></div><?}?>
<? if($error==2){?><div class="mail_m"><h2><?=_e('recovery_error2')?></h2></div><?}?>
<? if($error==3){?><div class="mail_m"><h2><?=_e('recovery_error3')?><script>setTimeout(function(){window.location= site_url + "/account/"}, 3000 );</script></h2></div><?}?>
<? if($form){?>
<form action="" id="recovery_password" method="POST">


    <div><div style="width:180px; display:inline-block;"><?=_e('recovery_password_n')?></div><input type="password" id="password_password" class="validate[required,custom[onlyAlphaDash], minSize[6],maxSize[15]]" name="password" value="" /><br/>
	<div style="width:180px; display:inline-block;"><?=_e('recovery_password_r')?></div><input type="password"  id="password_password2"  class=" validate[required,equals[password_password]]" name="password2" value="" /></div>

<div style="margin-top:10px"><input type="submit" name="sub" value="<?=_e('recovery_submit')?>" /></div>
<span style='color: rgb(5, 155, 216); font-size:14px'><?=form_error('password')?></span><br>
<span style='color: rgb(5, 155, 216); font-size:14px'><?=form_error('password2')?></span></form>
<?}?>

</div>
