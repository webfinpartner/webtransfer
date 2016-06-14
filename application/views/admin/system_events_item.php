<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.json.js"></script>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>

<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/system_events/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
<input type="hidden" name="id" value="<?=@$item->id?>"/>
            <fieldset>
                <div class="widget" id="fields">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($state=='create')?'Новое системное событие':'Редактирование системного события'?> </h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/system_events/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Название(латиницей):<span class="req">*</span></label>
                        <div class="formRight"><input type="text"  class="validate[required]" style="width: 200px" name="machine_name" value="<?=@$item->machine_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Описание:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]" style="width: 70%" name="human_name" value="<?=@$item->human_name?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Параметры:<span class="req">*</span></label>
                        <div class="formRight"><textarea class="validate[required]" style="width: 70%;height: 150px" name="params"><?=@$item->params?></textarea></div>
                        <div class="clear"></div>
                    </div>           
                    <? if ($state!='create') { ?>
                    <div class="formRow">
                        <label>Подписчики:<span class="req"></span></label>
                        <div class="formRight">
                          <table class="easyui-treegrid" style="width:100%;height:250px"
                            data-options="
                                url: '/opera/system_events/subscribers/<?=@$item->id?>',
                                method: 'post',
                                rownumbers: false,
                                idField: 'id',
                                treeField: 'fio'
                            ">
                            <thead>
                                <tr>
                                    <th data-options="field:'fio'" width="240">ФИО</th>
                                    <th data-options="field:'enabled'" width="50">ВКЛ</th>
                                    <th data-options="field:'addr_type'" width="100">Тип</th>
                                    <th data-options="field:'addr'" width="150">Адрес</th>
                                    <th data-options="field:'max_messages_in_hour'" width="80">Max в час</th>
                                </tr>
                            </thead>
                         </table>
                        </div>
                        <div class="clear"></div>
                    </div>   
                    <? } ?>
                  
                </div> 

            </fieldset>
            
                        

        
		<center>
			<a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/system_events/all"><span>Отменить</span></a>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
			
			</center>
			</form>
    </div>
                
                
    <script>
        
        

    </script>                
