<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/easyui/datagrid-filter.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/datagrid-groupview.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>




<div class="formRow" id="row_templete" style="display: none">
    <label><select name="language_new[]"><?=render_leng_select('ru')?></select></label>
    
    <div class="formRight"><nobr>
            
            <input style="width:30%" type="text" name="title_new[]" placeholder="Заголовок" value="">
            <input type="text" style="width:60%" class="validate[required]" placeholder="Сообщение" name="text_new[]" value="">
            <a title="Удалить" class="smallButton del" href="#" onclick="del_lang(this); return false;"><img src="images/icons/101.png"></a></nobr> </div>
       
    <div class="clear"></div>
</div>  

<form id="validate" class="form" enctype='multipart/form-data' action="<?php base_url()?>opera/system_messages/<?=($state=='create')?"create":"$state"?>"  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
<input type="hidden" name="message_id" value="<?=@$item->message_id?>"/>
            <fieldset>
                <div class="widget" id="fields">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6><?=($state=='create')?'Новое системное сообщение':'Редактирование системного сообщения'?> </h6>
                    <?php if($state!='create'){?>
                    <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/system_messages/delete/<?=$state?>"><span>Удалить</span></a>
                    <?php }?>
                    </div>
                    <div class="formRow">
                        <label>Дата начала:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="start_datetime" class="validate[required] easyui-datetimebox" style="width: 150px" name="start_datetime" value="<?=@$item->start_datetime?>"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Дата конца:<span class="req">*</span></label>
                        <div class="formRight"><input type="text" id="exp_datetime" class="validate[required] easyui-datetimebox" style="width: 150px" name="exp_datetime" value="<?=@$item->exp_datetime?>"></div>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="formRow">
                        <label>Статус:<span class="req">*</span></label>
                        <div class="formRight"><?=form_dropdown('status', @$statuses, @$item->status )?></div>
                        <div class="clear"></div>  
                    </div>                    
                    
                    <div class="formRow">
                        <label>Тип сообщения:<span class="req">*</span></label>  
                        <div class="formRight"><?=form_dropdown('type_id', @$types, @$item->type_id )?></div>
                        <div class="clear"></div> 
                    </div>                    
                    
                    
                    <div class="formRow">
                        <label>Заголовок языка по умолчанию(en):<span class="req">для модальных окон</span></label>
                        <div class="formRight"><input type="text" name="base_title" value="<?=@$item->base_title?>"></div>
                        <div class="clear"></div>
                    </div> 
                    
                    <div class="formRow">
                        <label>Текст языка по умолчанию(en):<span class="req">*</span></label>
                        <div class="formRight"><input type="text" class="validate[required]"  name="base_text" value="<?=@$item->base_text?>"></div>
                        <div class="clear"></div>
                    </div>                    
                        
                        
                    <!-- сообщения из БД -->    
                    <? if (!empty($item->text) ) foreach($item->text as $id=>$text){ ?>
                    <div class="formRow">
                        <!--label><?=form_dropdown('language['.$id.']', @$languages, @$item->language[$id] )?></label-->
                        <label><select name="language[<?=$id?>]"><?=render_leng_select(@$item->language[$id]);?></select></label>
                        <div class="formRight"><nobr>
                            <input style="width:30%" type="text" name="title[<?=$id?>]" value="<?=@$item->title[$id]?>">
                            <input style="width:60%" type="text" class="validate[required]" name="text[<?=$id?>]" value="<?=@$text?>">
                            <a title="Удалить" class="smallButton del" href="#" onclick="del_lang(this); return false;"><img src="images/icons/101.png"></a></nobr>
                            </div>
                        
                        <div class="clear"></div>
                    </div>                    
                    <? } ?>
                    <!-- новые сообщения - нужно для перегрузки данных в случае ошибкм-->
                    <? if (!empty($item) && !empty($item->language_new) && !empty($item->text_new)) foreach($item->text_new as $idx=>$text){ ?>
                    <div class="formRow">
                        <label><select name="language_new[]"><?=render_leng_select(@$item->language_new[$idx]);?></select></label>
                        <div class="formRight"><nobr>
                                <input style="width:30%" type="text" name="title_new[]" value="<?=@$item->title_new[$idx]?>">
                                <input style="width:60%" type="text" class="validate[required]" name="text_new[]" value="<?=@$text?>">
                            <a title="Удалить" class="smallButton del" href="#" onclick="del_lang(this); return false;"><img src="images/icons/101.png"></a></nobr>
                            </div>
                        
                        <div class="clear"></div>
                    </div>                    
                    <? } ?>
                    

                </div> 

            </fieldset>
            
                        

        
		<center>
                        <a class="wButton greenwB ml15 m10" id="add_lang" title="" href="#" onclick="add_lang(); return false;"><span>Добавить язык</span></a>
			<a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/system_messages/all"><span>Отменить</span></a>
			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
			
			</center>
			</form>
    </div>
                
                
    <script>
          function add_lang(){
              $('#row_templete').clone().css('display', 'block').appendTo('#fields');
          }      
          function del_lang( obj ){
              $(obj).parent().parent().parent().remove();
          }      
                
                
    </script>                
