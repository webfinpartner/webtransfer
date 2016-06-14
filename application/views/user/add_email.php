<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
.mail {margin: 0 auto; text-align: center;  height:  300px;}
</style>
 <div class="mail">
    <h2><?=_e('add_email_h2')?></h2>
        <form action="" method="POST">
            <div>
                <div style="width:70px; display:inline-block;"><?=_e('accaunt/add_email_1')?></div>
                <input type="text" style="" name="email" value="<?=set_value('email')?>" />
            </div>

            <div style="margin-top:10px"><input type="submit" name="sub" value="<?=_e('accaunt/add_email_2')?>" /></div>
            <span style='color: rgb(5, 155, 216); font-size:14px'><?=form_error('email')?></span>
        </form>
 </div>