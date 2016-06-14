<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script>
    function save_skip(){
         createCookie("visualDNA_skip", "1", 365);    
         showNext();
    }
</script>

<div id="popup_VisualDNA" class="popup_window" style="z-index:9999;">
    <div class="close" onclick="$(this).parent().hide(); if('function' == typeof(takeCreditInvestClose)) takeCreditInvestClose(this);"></div>
    <h2><?=_e('block/data20')?></h2>
    <span class="standart"><?=_e('block/data21')?></span>
    <span class="garant" style="display: none"><?=_e('block/data22')?></span>
    <button class="button" style="margin-bottom: 5px;" onclick="window.open('<?=site_url('account/visualdna')?>', '_blank');$('#popup_VisualDNA').hide();"><?=_e('block/data23')?></button>
    <input type="checkbox" onclick="save_skip()" id="skip_next_time"><?=_e('Больше не предлагать')?><br>
    <a class="garant" href="#" style="display: none" onclick="showNext(); return false;"><?=_e('block/data24')?></a>
</div>