<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="unverifieds_limit" class="popup_window" style="z-index: 999;">
    <div onclick="$('#unverifieds_limit').hide('slow');" class="close"></div>
    <h2><?=_e('accaunt/payout_19')?></h2>
    <p><?=_e('accaunt/payout_20')?></p>
    <center>
        <p><?=_e('accaunt/payout_21')?></p>
        <p><?=_e('accaunt/payout_22')?></p>
    </center>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit"><?=_e('accaunt/payout_23')?></a>
</div>