<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Бизнес  центр</h6>
                    <?php if($state!='create'){?>

                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Название:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="title" class="validate[required]" name="title" value="<?=@$item->title?>"></div>
                        <div class="clear"></div>
                    </div>

                      <div class="formRow">
                        <label>Тип:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="type" class="validate[required]" name="type" value="<?=@$item->type?>"></div>
                        <div class="clear"></div>
                    </div>
            </fieldset>


		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset"  title="" href="/opera/business_center/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
