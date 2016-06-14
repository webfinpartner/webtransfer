<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Добавление новости</h6>
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
                        <label>Ссылка для кнопки:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="url"  name="url"  value="<?=@$item->url?>"></div>
                        <div class="clear"></div>
                    </div>

		 <div class="formRow">
                        <label>Текст для кнопки:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="date"  name="button"  value="<?=@$item->button?>"></div>
                        <div class="clear"></div>
                    </div>
                    		 <div class="formRow">
                        <label>Раздел:<span class="req">*</span></label>
                        <div class="formRight"><? echo form_dropdown('section', get_menu_banner(), @$item->section);?></div>
                        <div class="clear"></div>
                    </div>

            </fieldset>

                     <? $this->load->view('/admin/blocks/image_tpl');?> </div>

        <!-- WYSIWYG editor -->
                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Контент</h6></div>
            <textarea  style="width:100%; height: 300px;" name="text" rows="" cols=""><?php echo @$item->text?></textarea>
        </div>





		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/banner/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
