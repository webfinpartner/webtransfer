<? $this->load->helper('form'); ?>
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.json.js"></script>

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

<form id="validate" class="form" enctype='multipart/form-data' action="<?=base_url()?>opera/currency_exchange_paysys/<?=($state=='create')?"ps_create":"ps"?>/<?=@$item->id?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
<input type="hidden" name="id" value="<?=@$item->id?>"/>
            <fieldset>
                <div class="widget" id="fields">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($state=='create')?'Новая группа':'Редактирование группы'?> </h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/currency_exchange_paysys/ps_delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Машинное имя:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  id="machine_name" class="validate[required]" style="width: 200px" name="machine_name" value="<?=@$item->machine_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Группа:<span class="req">*</span></label>
                        <div class="formRight"><?=form_dropdown('group_id', $groups, @$item->group_id, 'id="group_id" class="validate[required]" style="width: 70%"')?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Путь к иконке:</label>
                        <div class="formRight"><input type="text" id="public_path_to_icon" style="width: 50%" name="public_path_to_icon" value="<?=@$item->public_path_to_icon?>"><div class="onehrineico <?php if(!empty($item->public_path_to_icon)) echo 'sprite_payment_systems';?>" style="<?=@$item->public_path_to_icon?>"></div>
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
                        <label>payment_id:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="payment_id" name="payment_id" value="<?=@$item->payment_id?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Банк:<span class="req">*</span></label>
                        <div class="formRight"><?=form_checkbox('is_bank',1,(@$item->is_bank==1), 'style="width: 70%" id="is_bank"')?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>present_out:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="present_out" name="present_out" value="<?=@$item->present_out?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>present_in:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="present_in" name="present_in" value="<?=@$item->present_in?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Основной процент:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="fee_percentage" name="fee_percentage" value="<?=@$item->fee_percentage?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Добавочный процент:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="fee_percentage_add" name="fee_percentage_add" value="<?=@$item->fee_percentage_add?>"></div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="formRow">
                        <label>Минимальный размер:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="fee_min" name="fee_min" value="<?=@$item->fee_min?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Максимальный размер:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%"  id="fee_max" name="fee_max" value="<?=@$item->fee_max?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Функция получения курса валюта:</label>
                        <div class="formRight"><input type="text" style="width: 70%" id="method_calc_fee" name="method_calc_fee" value="<?=@$item->method_calc_fee?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Валюта:<span class="req">*</span></label>
                        <div class="formRight"><?=form_dropdown('currency_id', $currencies, @$item->currency_id, 'id="currency_id" class="validate[required]" style="width: 70%"')?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Активна:<span class="req">*</span></label>
                        <div class="formRight"><?=form_checkbox('active',1,(@$item->active==1), 'style="width: 70%" id="active"')?> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Сортировка:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" id="order" name="order" value="<?=@$item->order?>"></div>
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
