<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Добавление в faq</h6>
                    <?php if($state!='create'){?>

                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Вопрос:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="question" value="<?=@$item->question?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Позиция:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required,custom[onlyNumberSp]]" id="url"  name="position"  value="<?=@$item->position?>"></div>
                        <div class="clear"></div>
                    </div>



            </fieldset>



        <!-- WYSIWYG editor -->
                <div class="widget" id="WYSIWYG">
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Ответ</h6></div>
            <textarea id="text" style="width:100%; height: 300px;" name="answer" rows="" cols=""><?=@$item->answer?></textarea>
        </div>





		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/faq/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
