<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>



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
                <label>Заголовок видео:<span class="req">*</span></label>
                <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Категория:<span class="req">*</span></label>
                <div class="formRight">
                    <select id="category" name="category">
                        <?getVideoCatigotiesSelect($item->category)?>
                    </select>
                <div class="clear"></div>
                </div>
            </div>
            <div class="formRow">
                <label>Код видео на youtube:<span class="req">*</span></label>
                <div class="formRight"><input type="text"  class="" id="id_video"  name="id_video"  value="<?=@$item->id_video?>"></div>
                <div class="clear"></div>
            </div>

            <div class="formRow">
                <label>Фото:<span class="req">*</span></label>
                <div class="formRight"><input type="file"  class="" id="foto"  name="foto"  value="<?=@$item->foto?>"></div>
                <div class="clear"></div>
                <?
                if($item->foto) {
                    ?>
                    <span class="result_del_photo"></span>
                    <img src="/upload/<?=@$item->foto?>" alt="" style="max-width: 150px;" class="preview_img">
                    <div class="del_photo wButton redwB ml15 m10" style="width:100px; cursor:pointer">Удалить фото </div>
                    <script>
                        $('.del_photo').click(function(){
                            $.post("/opera/video/ajax_delete_photo/<?=@$item->id?>", function(result){
                                $(".result_del_photo").html(result);
                                $(".preview_img").attr("src", '');

                            });
                        })
                    </script>
                    <?
                }
                ?>
            </div>

            <div class="formRow">
                <label>Id пользователя:<span class="req">*</span></label>
                <div class="formRight"><input type="text"  class="" id="id_user"  name="id_user"  value="<?=@$item->id_user?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Статус:<span class="req">*</span></label>
                <div class="formRight">
                    <select id="status" name="status">
                        <option value="0" <?=((isset($item->satus) && 0 == $item->satus) ? "selected='selected'": "")?>>Скрыть</option>
                        <option value="1" <?=((isset($item->satus) && 1 == $item->satus || !isset($item->satus)) ? "selected='selected'": "")?>>Отображать</option>
                    </select>
                <div class="clear"></div>
                </div>
            </div>
            <div class="formRow">
                <label>Характеристика:<span class="req">*</span></label>
                <div class="formRight">
                    <select id="status" name="featured">
                        <option value="0" <?=((isset($item->featured) && 0 == $item->featured || !isset($item->featured)) ? "selected='selected'": "")?>>Обычное</option>
                        <option value="1" <?=((isset($item->featured) && 1 == $item->featured) ? "selected='selected'": "")?>>Лучшее</option>
                    </select>
                    <div class="clear"></div>
                </div>
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

<!-- WYSIWYG editor -->
    <div class="widget" id="WYSIWYG">
        <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Описание</h6></div>
        <textarea id="text_edit" style="width:100%; height: 300px;" name="info" rows="" cols=""><?php echo @$item->info?></textarea>
    </div>
    <center>
        <?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/video/all"><span>Отменить</span></a><?php }?>
        <a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
    </center>
</form>