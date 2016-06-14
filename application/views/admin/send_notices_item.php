<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/admin/jeasyui/themes/icon.css">


<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script>
    var search_type = 'none';
    function setSearchType(el, type){
            var text = $(el).text();
            search_type = type;
            $('#search_by_menu').menubutton({ text: text });
    } 
    
    function addRecipient(index, row){
        row.status = 'Новый';
        row.act = '<a title="Удалить" onclick="delRecipient(this)" class="smallButton"><img src="images/icons/101.png"></a>'
        $('#recipient_dg').datagrid('appendRow', row );
    }
    
    
    function delRecipient(el){
        index  = $(el).closest('tr').attr('datagrid-row-index');
        $('#recipient_dg').datagrid('deleteRow', index );
    }    
    
    function send(){
          var text = $('#send_form textarea[name=message]').val(); 
          if ( text.length == 0)
          {
              $.messager.alert('Внимание','Текст сообщения не должен быть пустым');
              return;
          }        
          var rdata = $('#recipient_dg').datagrid('getData' );   
          if ( rdata && rdata.total == 0  ){
              $.messager.alert('Внимание','Не указано ни одного получателя');
              return;
          }
          
          var recipients = [];
          for ( var i=0; i < rdata.rows.length; i++ )
              recipients.push(rdata.rows[i].id_user);
          $('#send_form input[name=recipients]').val(recipients.join(','));
          console.log(recipients);
        
          $('#send_button').hide();
          $('.loading-gif').show();
          $( "#send_form" ).submit();       
    }

    
</script>
    

<div class="widget">
     <div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" /><h6><?=($mode=='create'?'Новое уведомление':'Просмотр уведомления #'.$item->id.' от '.$item->send_datetime)?></h6></div>
<div class="body">

Получатели:<br>
    <table class="easyui-datagrid"  style="width:95%;height:250px" id="recipient_dg"
            data-options="singleSelect:true,collapsible:true,fitColumns: true">
        <thead>
            <tr>
                <th data-options="field:'id_user',width:80">User ID</th>
                <th data-options="field:'sername',width:100">Фамилия</th>
                <th data-options="field:'name',width:100">Имя</th>
                <th data-options="field:'patronymic',width:100">Отчество</th>
                <th data-options="field:'security',width:100">Безопасность</th>
                <th data-options="field:'phone',width:90">Телефон</th>
                <th data-options="field:'email',width:80">E-Mail</th>
                <th data-options="field:'status',width:70">Статус</th>
                <th data-options="field:'act',width:60"></th>
            </tr>
        </thead>
        <? if ($mode!='create') {?>
        <tbody>
            <?foreach ($item->recipients as $r){ ?>
            <tr>
                <td><?=$r->user_id?></td>
                <td><?=$r->sername?></td>
                <td><?=$r->name?></td>
                <td><?=$r->patronymic?></td>
                <td><?=$r->security?></td>
                <td><?=$r->phone?></td>
                <td><?=$r->email?></td>
                <td><?=$r->status_text?></td>
                <td></td>
            </tr>   
            <? } ?>
        </tbody>
        <? } ?>
    </table>
      <? if ($mode=='create') {?>
       <a href="javascript:void(0)" id="search_by_menu" class="easyui-menubutton" data-options="menu:'#search_by_menu_items'" style="width:180px">Поиск</a>
        <div id="search_by_menu_items" style="width:180px;">
                    <div  onclick="setSearchType(this, 'user_id')">Поиск по User ID</div>
                    <div onclick="setSearchType(this, 'fio')">Поиск по ФИО</div>
                    <div onclick="setSearchType(this, 'phone')">Поиск по телефону</div>
                    <div onclick="setSearchType(this, 'email')">Поиск по E-Mail</div>
        </div>
       <input class="easyui-combogrid" style="width:350px" value="" id="search_combo"  data-options="
            panelWidth: 750,
            idField: 'user_id',
            textField: 'user_info',
            url: '/opera/send_notices/ajax_search',
            mode: 'remote',
            method: 'post',
            onClickRow: addRecipient,
            onBeforeLoad: function( param ){ param.type = search_type; return true; },
            
            columns: [[
                {field:'id_user',title:'User ID',width:80},
                {field:'sername',title:'Фамилия',width:120},
                {field:'name',title:'Имя',width:80},
                {field:'patronymic',title:'Отчество',width:120},
                {field:'security',title:'Безопасность',width:80},
                {field:'phone',title:'Телефон',width:80},
                {field:'email',title:'E-Mail',width:60}
            ]],
            fitColumns: true
        ">
    <? } ?>
<form action="/opera/send_notices/<?=$item->id?>" method="post" id="send_form">        
<input type="hidden" name="submited" value="1">
<input type="hidden" name="recipients" value="">
<br>
Сообщение:<br />
<textarea  style="width:100%; height: 300px;" name="message" rows="" cols="">
<? if ($mode=='create') {?>




Best regards,
<b>Webtransfer Team</b>


<a href="mailto:support@webtransfer.com">support@webtransfer.com</a>
<a href="http://<?=base_url_shot()?>">http://www.<?=base_url_shot()?></a>

<img src="https://<?=base_url_shot()?>/img/logo13.gif">
<? } else echo $item->send_text; ?>
</textarea>
<br />
<center>
                        <img class='loading-gif' style="display: none;" src="/images/loading.gif"/>
			  <? if ($mode=='create') {?><a id="send_button" class="wButton greenwB ml15 m10" onclick="send();return false;" title="" href="#"><span>Отправить</span></a> <? } ?>
                        <a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/send_notices/all"><span>Назад</span></a>
    
</center>


</form>

 </div>
 </div>
</div>
