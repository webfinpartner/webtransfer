<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript" src="js/admin/jquery.ba-replacetext.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/tiny_mce/settings.js"></script>
<script type="text/javascript">




 function ereg_wi(){


 $('#text_ifr').contents().find('#tinymce').each(function(){
            var s = $(this).html();
            var d = s.replace(/\(\*\*\*\*\*Метка шаблона\*\*\*\*\*\)/gi, " " );
        $(this).html( d );

});





}


$(function(){
if($('#shablon').val()=='novosti') $("#WYSIWYG").css('display',"none");
else if($('#shablon').val()=='faq') $("#WYSIWYG").css('display',"none");
else $("#WYSIWYG").css('display',"block");


$('#shablon').change(function(){
var value=$(this).val();

if(value=='novosti') $("#WYSIWYG").css('display',"none");
else if($('#shablon').val()=='faq') $("#WYSIWYG").css('display',"none");
else $("#WYSIWYG").css('display',"block");
if(value=="")ereg_wi();
else{ereg_wi(); $('#text_ifr').contents().find('#tinymce').append("(*****Метка шаблона*****)");}
	});
});

</script>

<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":$state?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Настройки Сайта</h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button greenB" title="" target="_blank" href="<?=create_link($item->url)?>"><span>Показать</span></a>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Название Страницы:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label for="tags">Meta-Keywords:</label>
                        <div class="formRight"><input type="text"  value="<?=@$item->meta_words?>" class="tags" name="meta_words" id="tags" style="display: none;">
                      </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Meta-Description:</label>
                        <div class="formRight"><textarea name="meta_descript" cols="" rows="8"><?=@$item->meta_descript?></textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Ссылка:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="url"  name="url"  value="<?=@$item->url?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Родитель:</label>
                        <div class="formRight">
                            <div class="selector" id="uniform-undefined"><span style="-moz-user-select: none;">Usual select dropdown</span><select name="parent" style="opacity: 0;">
                                <option value="">Родитель</option>
                                <?php
                                 $parents=get_patents();
                                foreach($parents as $parent){if(!empty($item) and @$item->id_page==$parent->id_page)continue;?>

                                <option value="<?=$parent->id_page ?>" <?php if(@$item->id_parent==$parent->id_page) echo"selected='selected'";?>><?=$parent->title ?></option>
                                <?php }?>

                            </select></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Шаблон:</label>
                        <div class="formRight">
                            <div class="selector" id="uniform-undefined"><span style="-moz-user-select: none;">Usual select dropdown</span><select id="shablon" name="shablon" style="opacity: 0;">
                                <option value="" <?php if(empty($item->shablon)) echo"selected='selected'";?>>Текстовый</option>
				<option value="novosti"<?php if(@$item->shablon=="novosti") echo"selected='selected'";?>>Новости</option>
				<option value="faq" <?php if(@$item->shablon=="faq") echo"selected='selected'";?>>FAQ</option>
                                <?php
                                $shablons=$this->db->get('shablon')->result();
                                foreach($shablons as $shablon){?>

                                <option value="<?=$shablon->id_shablon ?>" <?php if(@$item->shablon==$shablon->id_shablon) echo"selected='selected'";?>>
				<?=$shablon->sh_title ?></option>
                                <?php }?>

                            </select></div>
                        </div>
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
                        <label>Позиция:</label>
                        <div class="formRight"><input type="text"  class="validate[required,custom[onlyNumberSp]]" id="sort"  name="sort"  value="<?=@$item->sort?>"></div>
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
            </fieldset>


        <!-- WYSIWYG editor -->
                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Контент</h6></div>
            <textarea id="text_edit" style="width:100%; height: 300px;" name="content" rows="" cols=""><?php echo @$item->content?></textarea>
        </div>




		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/page/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
