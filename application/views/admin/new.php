<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script>
    $(function(){
        
             var dt_str = "<?=@$item->data?>";
             //alert(dt_str);
             var parts =dt_str.split('-');
            $('#date').datepicker('setDate', new Date(parts[0],parts[1]-1,parts[2]));
        
    });
</script>


<script type="text/javascript" src="<?=base_url()?>js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/tiny_mce/settings.js"></script>
<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6> <?if(!empty($view_all['system_news'])) echo "Системная"?> Новость</h6>
                    <?php if($state!='create'){?>
                    <?if(!empty($view_all['system_news'])){?><a style="margin: 4px 4px; float: right; " class="button greenB" title="" target="_blank" href="/news/<?=@$item->url?>"><span>Показать</span></a><?}?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Заголовок новости:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Ссылка:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="url"  name="url"  value="<?=@$item->url?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Разослать всем:<span class="req">*</span></label>
                        <div class="formRight"><input type="checkbox"  class="" id="to_all"  name="to_all"  value="1"></div>
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
                    </div>
		    <div class="formRow">
                        <label>Дата:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required] datepicker" id="date"  name="data"  value="<?=@$item->data?>" style="width: 84px !important;"></div>
                        <div class="clear"></div>
                    </div>

            </fieldset>
		          <? $this->load->view('admin/blocks/image_tpl')?>

        <!-- WYSIWYG editor -->
                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Контент</h6></div>
            <textarea id="text_edit" style="width:100%; height: 300px;" name="text" rows="" cols=""><?php echo @$item->text?></textarea>
        </div>





		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/news/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
