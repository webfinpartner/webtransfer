<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div id="container" class="content">
    <h1 class="title"><?=_e('accaunt/automatic_first_1')?></h1>
    <p><?=_e('accaunt/automatic_first_2')?></p><br/><br/>
    <form action="<?=site_url('account/automatic/createAutomatic')?>" method="post">
        <label for="agree"><?=_e('accaunt/automatic_first_3')?></label>
        <input type="checkbox" name="agree" value="1"/>  
		<Br/>
        <center><button type="submit" name="submit" class="button"><?=_e('accaunt/automatic_first_4')?></button></center>
    </form>
</div>