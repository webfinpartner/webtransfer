<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($state=='create')?'Новая системная переменная':'Редактирование системной переменной'?> </h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Название переменной(machine):<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="machine_name" class="validate[required]" name="machine_name" value="<?=@$item->machine_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Название переменной(human):</label>
                        <div class="formRight"><input type="text" id="machine_name" class="" name="human_name" value="<?=@$item->human_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Значение по умолчанию:</label>
                        <div class="formRight"><input type="text"  class="" id="default_value"  name="default_value"  value="<?=@$item->default_value?>"></div>
                        <div class="clear"></div>
                    </div>

		 <div class="formRow">
                        <label>Значение:</label>
                        <div class="formRight"><input type="text" class="" id="value"  name="value"  value="<?=@$item->value?>"></div>
                        <div class="clear"></div>
                    </div>
		 <div class="formRow">
                        <label>Описание:<span class="req">*</span></label>
                        <div class="formRight"><textarea type="" class="validate[required]" id="value"  name="description"><?=@$item->description?></textarea></div>
                        <div class="clear"></div>
                    </div>

            </fieldset>

        
		<center>
			<a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/<?=$controller?>/all"><span>Отменить</span></a>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
			
			</center>
			</form>
    </div>
