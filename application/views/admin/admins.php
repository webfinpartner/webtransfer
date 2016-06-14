<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.json.js"></script>




<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Модератор</h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Имя:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="name" class="validate[required]" name="name" value="<?=@$item->name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Фамилия:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="family"  name="family"  value="<?=@$item->family?>"></div>
                        <div class="clear"></div>
                    </div>
	                    <div class="formRow">
                        <label>Должность:<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="doljnost" name="doljnost"  value="<?=@$item->doljnost?>"></div>
                        <div class="clear"></div>
                    </div>
                      <div class="formRow">
                        <label>Отдел:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="otdel"  name="otdel"  value="<?=@$item->otdel?>"></div>
                        <div class="clear"></div>
                    </div>
		   <div class="formRow">
                        <label>Тип пользователя:</label>
                        <div class="formRight">
                            <select id="permission"  name="permission" >
                                <?=Permissions::getInstance()->getRolesOptions($item->permission)?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                   <div class="formRow">
                        <label>Email:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="email"  name="email"  value="<?=@$item->email?>"></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Телефон:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="telephone" name="telephone"  value="<?=@$item->telephone?>"></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Логин:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="login"  name="login"  value="<?=@$item->login?>"></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Пароль:</label>
                        <div class="formRight"><input type="text"  class="validate[required]" id="password"  name="password"  value="<?=@$item->password?>"></div>
                        <div class="clear"></div>
                    </div>

                <? if ($state!='create') {?>                
		<div class="formRow">
                        <label>Подписка на системные события:</label>
                          <table id="admin_events_subscribes_table" class="easyui-datagrid" style="width:100%;height:120px"
                            data-options="
                                url: '/opera/admins/event_subscribes/<?=@$state?>',
                                method: 'post',
                                rownumbers: false,
                                singleSelect: true,
                                idField: 'id',
                                toolbar: '#admin_events_subscribes_table_toolbar'
                            ">
                            <thead>
                                <tr>
                                    <th data-options="field:'machine_name'" width="150">Название</th>
                                    <th data-options="field:'human_name'" width="240">Описание</th>
                                    <th data-options="field:'enabled_txt'" width="50">ВКЛ</th>
                                </tr>
                            </thead>
                         </table>
                        <div id="admin_events_subscribes_table_toolbar">
                            <a href="#" onclick="add_event(); return false;" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true"></a>
                            <a href="#" onclick="edit_event(); return false;" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true"></a>
                            <a href="#" onclick="remove_event(); return false;" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true"></a>
                            
                        </div>
                        <div class="clear"></div>
                    </div>
                <? } ?>


            </fieldset>







		<center>
			<?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/admins/all"><span>Отменить</span></a><?php }?>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>

     
    <? if ($state!='create') {?>                 
    <div id="event_dialog" class="easyui-dialog" title="Событие" style="width:400px;height:480px;"
        data-options="closed: true, resizable:true,modal:true,buttons:[{
                                id: 'event_dialog_save_btn',
				text:'Сохранить',
				handler:function(){ save_event(); }
			},{
				text:'Закрыть',
				handler:function(){ $('#event_dialog').dialog('close'); }
			}]">
        <div style="padding: 5px">
        Событие: <select id="event_type" class="no-uniform" onchange="reload_event_params(this)">
            <option value="0">Выберите</option>
            <? if ( !empty($events) ) foreach ($events as $event) { ?>
            <option value="<?=$event->id?>"><?=$event->machine_name?></option>
            <? } ?>
        </select>
        </div>
        <div style="padding: 5px">
            <label for="event_enabled" id="event_enabled_label">Включено</label>&nbsp;<input value="1" id="event_enabled" name="event_enabled"  type="checkbox">
        </div>
        <table id="pg" class="easyui-propertygrid" style="width:90%; height: 300px" data-options="url:'/opera/admins/event_params/1', showGroup:true,scrollbarSize:0, onBeforeLoad: function(param){ param.user_id = '<?=@$state?>'; }"></table>        
        
    </div>
     <? } ?>
                
                
<script>
    function add_event(){


        
        $('#event_type').removeAttr('disabled');
        $('#event_type option[value=0]').attr('selected', 'selected');
        // задесейблим котрые уже есть
        var data = $('#admin_events_subscribes_table').datagrid('getData');
        $('#event_type option').removeAttr('disabled');  
        for (r in data.rows) 
              $('#event_type option[value='+data.rows[r].event_id+']').attr('disabled', 'disabled');  
          
        $('#pg').data('id', 0 );
        $.uniform.update( $('#event_enabled').attr('checked', 'checked') );
        
        $('#pg').propertygrid('reload', '/opera/admins/event_params/0' );
        $('#event_dialog').dialog({'title':'Добавить событие'} );
        $('#event_dialog').dialog('open');

    }
    
    
    function edit_event(){
          var row = $('#admin_events_subscribes_table').datagrid('getSelected'); 
          if (!row){
              alert('Ни одна запись не выделена');
              return;
          }
        
        
        $('#pg').data('id', row.id );
        $('#event_type option[value='+row.event_id+']').attr('selected', 'selected');
        if ( row.enabled == 1 ){
            $.uniform.update( $('#event_enabled').attr('checked', 'checked') );
        } else
            $.uniform.update( $('#event_enabled').removeAttr('checked') );
        
        
        $('#event_type').attr('disabled', 'disabled');
        $('#pg').propertygrid('reload', '/opera/admins/event_params/'+row.event_id );
        $('#event_dialog').dialog({'title':'Редактировать событие'} );
        $('#event_dialog').dialog('open');
    }
    
    
    function remove_event(){
        var row = $('#admin_events_subscribes_table').datagrid('getSelected'); 
        if (!row){
            alert('Ни одна запись не выделена');
            return;
        }
        $.messager.confirm('Внимание', 'Вы уверены, что хотите отключить событие?', function(r){
                if (r){
                    $.ajax({
                          type: "POST",
                          url: "/opera/admins/unlink_event/"+row.id,
                          data: {}
                        })
                          .done(function( msg ) {
                              $('#admin_events_subscribes_table').datagrid('reload');
                          });
                }
        });


    }
    
    function save_event(){
        
        
        var event_id = $('#event_type').val();
        if ( event_id == 0 ){
            alert('Выберите тип события');
            return;
        }
        
        // собирам параметры
        var data = $('#pg').propertygrid('getData');
        var json_array = {};
        for( r in data.rows ){
            if ( json_array[data.rows[r].group] == undefined )
                json_array[data.rows[r].group] = {};
            json_array[data.rows[r].group][data.rows[r].json_name] =data.rows[r].value;
        }
        var json_params = $.toJSON(json_array); 
        
        
        // отправляем данные
        $.ajax({
              type: "POST",
              url: "/opera/admins/link_event/"+$('#pg').data('id'),
              data: {'params': json_params, 'enabled': $('#event_enabled').prop('checked')?1:0, 'admin_id': '<?=@$state?>', 'event_id': event_id  }
            })
              .done(function( msg ) {
                  
                  if ( msg != 'OK'){
                      alert(msg);
                      return;
                  }
                  
                  $('#admin_events_subscribes_table').datagrid('reload');
                  $('#event_dialog').dialog('close');

              });        
        
        
        
    }
    
    function reload_event_params(obj){
        
        
        $('#pg').propertygrid('reload', '/opera/admins/event_params/'+$(obj).val() );
        
    }
    
    
</script>
                    
                    