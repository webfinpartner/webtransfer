<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
// елемент с id volunteer активирует этот диолог (необходима переменная $volunteer,$backPage)
?>

<div class="popup_window" id="submit" style="width:600px">
    <div class="close"></div>
    <div class="content" style="text-align: left;height: 300px; overflow-y: scroll;">
	<h2><?=_e('blocks/volunteer_dialog_1')?></h2>
        <?=_e('blocks/volunteer_dialog_2')?><br/><br/>
		<ol>
        <?=_e('blocks/volunteer_dialog_3')?>
	  </ol><br/>

    </div>
        <? if(!$volunteer){?>
          <a class="but agree right" href="<?=site_url('partner/trigerVolunteer')?>/<?=$backPage?>"><?=_e('blocks/volunteer_dialog_4')?></a>
         <?}?>
    
</div>

<script>
    $(document).ready(function(){
        $("#volunteer").click(function(){
            <?=((!$volunteer) ? "$(\"#submit\").show(\"slow\")" : "window.location = site_url + '/partner/trigerVolunteer/$backPage'")?>;
            return false;
        })
        $("#volunteer_doc").click(function(){
            $("#submit").show("slow");
            return false;
        })
    });
</script>