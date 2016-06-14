<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Системные  настройки</h6>

                    </div>
                    <div class="formRow">
                        <label>Email:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="email" class="validate[required]" name="email" value="<?=@$item->email?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Телефон:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="telephon"  name="telephon"  value="<?=@$item->telephon?>"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <label>Телефоны уведомлений<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="tags" class="validate[required]" name="sms" value="<?=@$item->sms?>"></div>
                        <div class="clear"></div>
                    </div>
	            <div class="formRow">
                        <label>Ставка для простых кредиторов:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="p_new_credior" name="p_new_credior"  value="<?=@$item->p_new_credior?>"></div>
                        <div class="clear"></div>
                    </div>
                      <div class="formRow">
                        <label>Ставка для повторных кредиторов:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="p_old_credior"  name="p_old_credior"  value="<?=@$item->p_old_credior?>"></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
                        <label>Ставка для vip кредиторов:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="vip_credior"  name="vip_credior"  value="<?=@$item->vip_credior?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Штраф %:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="shtraf"  name="shtraf"  value="<?=@$item->shtraf?>"></div>
                        <div class="clear"></div>
                    </div>
				<div class="formRow">
                        <label>Ставка  зарегестированного  пользователя:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="regpartner" name="regpartner"  value="<?=@$item->regpartner?>"></div>
                        <div class="clear"></div>
                    </div>
					 <div class="formRow">
                        <label>Количество кредитов для первого уровня:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="flc" name="flc"  value="<?=@$item->flc?>"></div>
                        <div class="clear"></div>
                    </div>

					<div class="formRow">
                        <label>Первый уровень коммисии:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="flp" name="flp"  value="<?=@$item->flp?>"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <label>Второй уровень комисии:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="slp" name="slp"  value="<?=@$item->slp?>"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <label>IP белый список</label>
                        <div class="formRight"><input type="text" id="ip_white" name="ip_white" value="<?=@$item->ip_white?>"></div>
                        <div class="clear"></div>
                        <script>$('#ip_white').tagsInput({width:'100%'});</script>
                    </div>
                    <div class="formRow">
                        <label>IP фаервола</label>
                        <div class="formRight"><input type="text" id="ip_firewall" name="ip_firewall" value="<?=@$item->ip_firewall?>"></div>
                        <div class="clear"></div>
                        <script>$('#ip_firewall').tagsInput({width:'100%'});</script>
                    </div>
                    <div class="formRow">
                        <label>WireBank Список Email</label>
                        <div class="formRight"><input type="text" id="wirebank_emails" name="wirebank_emails" value="<?=@$item->wirebank_emails?>"></div>
                        <div class="clear"></div>
                        <script>$('#wirebank_emails').tagsInput({width:'100%'});</script>
                    </div>
                    <div class="formRow">
                        <label>VDNA Список Email</label>
                        <div class="formRight"><input type="text" id="vdna_emails" name="vdna_emails" value="<?=@$item->vdna_emails?>"></div>
                        <div class="clear"></div>
                        <script>$('#vdna_emails').tagsInput({width:'100%'});</script>
                    </div>                    
		 <? $this->load->view('admin/blocks/image_tpl')?>

            </fieldset>







		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/page/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
