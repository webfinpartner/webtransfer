<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Добавление баннера для  партнера</h6>
                    <?php if($state!='create'){?>

                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Заголовок баннера:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
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
            </fieldset>
                     <? $this->load->view('/admin/blocks/image_tpl');?> </div>
		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/<?=$controller?>/all">
				<span>Отменить</span>
			</a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
