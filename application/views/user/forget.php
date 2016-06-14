<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
.mail {margin: 0 auto; text-align: center;  height:  300px;}
</style>
 <div class="mail">
 <? if($error==1){?><div class="mail_m"><h2><?=_e('forget_error1')?><h2></div><?}?>
<? if($error==2){?><div class="mail_m"><h2><?=_e('forget_error2')?></h2></div><?}?>
<? if($error==3){?><div class="mail_m"><h2><?=_e('forget_error3')?></h2></div><?}?>
<? if($error==4){?><div class="mail_m"><h2><?=_e('forget_error4')?></h2></div><?}?>
<?if($error != 1){?>
    <h2><?=_e('forget_h2')?></h2>
    <form action="" method="POST">


    <div><input type="text" style="padding:10px;width:400px;" name="mail" value="" /></div>
    <center><div id="forget_recapcha"></div></center>
    <div style="margin-top:10px">
    <button name="sub" id="bn_login" type="submit" class="button"><?=_e('forget_send')?></button>
    </div>
    <span style='color: rgb(5, 155, 216); font-size:14px'><?=form_error('mail')?></span>
    </form>
<?}?>
</div>
