<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Уведомление</h6>
                    <?php if($state!='create'){?>

                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Заголовок:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Название:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="url"  name="name"  value="<?=@$item->name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Адресат:</label>
                        <div class="formRight">
                            <div class="selector" id="uniform-undefined"><span style="-moz-user-select: none;">Usual select dropdown</span><select name="state" style="opacity: 0;">
                                <option <?php if(@$item->state==1)echo "selected='selected'"?> value="1">Админ</option>
                                <option <?php if(@$item->state==2)echo "selected='selected'"?> value="2">Пользователь</option>

                            </select></div>
                        </div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow" id="form_mail">
                        <label>Адреса:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class=""  name="mail" id="tags" value="<?=@$item->mail?>"></div>
                        <div class="clear"></div>
                    </div>

						<div class="formRow">
                        <label>Статус:</label>
                        <div class="formRight">
                            <div class="floatL"><div id="uniform-checkReq" class="checker"><span class="checked"><input style="opacity: 0;" value="1" id="checkReq" name="active" <?php if(@$item->active==1)echo "checked='checked'"?> data-prompt-position="topRight:102" type="checkbox"></span></div><label for="checkReq" id="state_checkReq">Включить</label></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Язык:<span class="req">*</span></label>
                        <div class="formRight">
                            <select id="lang" name="lang">
                                <?=render_leng_select($item->lang);?>
                            </select>
                        <div class="clear"></div>
                        </div>
                    </div>
                    <div class="formRow">
                        <label>Whitelabel:<span class="req">*</span></label>
                        <div class="formRight">
                            <select id="master" name="master">
                                <?=render_whitelabel_select($item->master);?>
                            </select>
                        <div class="clear"></div>
                        </div>
                    </div>

            </fieldset>
                            <div class="widget" >
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Инструкция по шаблонизации:</h6></div>
            <p>{{status}} назанчается статус в тексте(статус проверки документов, будет на этом месте)<br />
            {{NOW}} текущее время<br />
            {{users.field}} любое поле из базы данных клиентов подставить  вместо field<br />
            {{credits.field}} любое поле из базы данных кредитов подставить  вместо field<br />
            </p>
            </div>
                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Тект</h6></div>
            <textarea  style="width:100%; height: 300px;" name="text" rows="" cols=""><?=(!empty($item->text))?$item->text:"\n
Best regards,
Webtransfer Team"?></textarea>
        </div>

		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/sender/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
				<script>
			$('select[name="state"]').change(function(){

				if($(this).val()==1)
						$("#form_mail").show();

				else if($(this).val()==2)
					$("#form_mail").hide();

			}).trigger('change');
			</script>