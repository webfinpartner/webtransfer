<? $this->load->helper('form'); ?>
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>


<style>
    .onehrineico{height:40px;width:40px;overflow:hidden;float:left;margin:0 10px 0 0; border: 1px solid transparent;}
    .sprite_payment_systems {
        background-image: url('/images/currency_exchange/spritesheet.png');
        background-repeat: no-repeat;
        display: block;
        width: 40px;
        height: 40px;
    }
    
    .datagrid-cell, .datagrid-cell-group, .datagrid-header-rownumber, .datagrid-cell-rownumber {   overflow: visible; }
</style>


<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>

<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/currency_exchange_paysys/<?=($state=='create')?"group_create":"group"?>/<?=@$item->id?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
<input type="hidden" name="id" value="<?=@$item->id?>"/>
            <fieldset>
                <div class="widget" id="fields">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($state=='create')?'Новая группа':'Редактирование группы'?> </h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/currency_exchange_paysys/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Машинное имя:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  id="machin_name" class="validate[required]" style="width: 200px" name="machin_name" value="<?=@$item->machin_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Название:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="human_name" class="validate[required]" style="width: 70%" name="human_name" value="<?=@$item->human_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Родительская группа:<span class="req">*</span></label>
                        <div class="formRight"><?=form_dropdown('parent_id', $groups, @$item->parent_id, 'id="group_id" class="validate[required]" style="width: 70%"')?></div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="formRow">
                        <label>Путь к иконке:</label>
                        <div class="formRight"><input type="text" style="width: 50%" name="public_path_to_icon" value="<?=@$item->public_path_to_icon?>"><div class="onehrineico <?php if(!empty($item->public_path_to_icon)) echo 'sprite_payment_systems';?>" style="<?=@$item->public_path_to_icon?>"></div>
                        <br><a href="#" onclick="$('#load_new_icon').toggle(); return false;">Загрузка</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="load_new_icon" class="formRow" style="display:none;">
                        <label></label>
                        <div class="formRight"><b>Выберите новую иконку(резмер: 40х40)</b> <br><input type="file" id="new_public_path_to_icon" style="width: 50%" name="new_public_path_to_icon"></div>
                        <div class="clear"></div>
                    </div>

                                        
                    <div class="formRow">
                        <label>Язык:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="language_id" class="validate[required]" style="width: 70%" name="language_id" value="<?=isset($item->language_id)?$item->language_id:0?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Разрешить добавление новых ПС:</label>
                        <div class="formRight"><?=form_checkbox('show_add_new',1,(@$item->show_add_new==1), 'style="width: 70%" id="show_add_new"')?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Сортировка:<span class="req">*</span></label>
                        <div class="formRight"><input id="order" type="text" class="validate[required]" style="width: 70%" name="order" value="<?=@$item->order?>"></div>
                        <div class="clear"></div>
                    </div>
                    
                    
                    
                </div> 

            </fieldset>
            
        
		<center>
			<a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/currency_exchange_paysys/all"><span>Отменить</span></a>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
			
			</center>
			</form>
    </div>
                
                
    <script>
        
        

    </script>                
