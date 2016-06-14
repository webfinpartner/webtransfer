<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?$this->load->helper('form')?>
<form id="validate" class="form" action="<?php base_url()?>opera/shablon/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Шаблон</h6>
                    <?php if($state!='create'){?>
						<a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/shablon/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Заголовок:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->sh_title?>"></div>
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

                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Тект</h6><? if($state== 13 or $state==14 or $state==15 or $state==16){?>
				<a style="margin: 4px 4px; float: right; "  href="/opera/credit/view_kontract_sablon/<?=$state?>" class="button greenB" title=""><span>Просмотреть</span></a><?}?></div>
            <textarea  style="width:100%; height: 300px;" name="text"  rows="" cols=""><?=@form_prep($item->sh_content)?></textarea>
        </div>

		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/shablon/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
