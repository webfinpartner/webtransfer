<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">


<!--link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/metro-gray/easyui.css"">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/icon.css"-->

<style>
    td[field=action]   { text-align: center; vertical-align: middle; }
</style>


<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>

<br>
<a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/system_messages/create"><span>Добавить системное сообщение</span></a>
<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Управление  системными сообщениями</h6></div>                          

    
  <table class="easyui-datagrid" id="dg" style="width:100%;height:450px" data-options="remoteFilter:true, 
                defaultFilterType:'label',
                singleSelect:true,
                multiSort:true,
                collapsible:false,
                pagination:true, 
                pageList:[10,30,100],
                nowrap:false,
                onDblClickRow: function(index, row){ window.location = '<?=base_url().'opera/system_messages/'?>'+ row.id; },
                url:'<?=base_url().'opera/system_messages/'?>',
                method:'post'
                 ">
        <thead>
            <tr valign="middle">
                <th data-options="field:'id',width:50,sortable:true">ID</th>
                <th data-options="field:'message_id',width:80,sortable:true">MSG ID</th>
                <th data-options="field:'start_datetime',width:130,valign:'center',sortable:true">Дата начала</th>
                <th data-options="field:'exp_datetime',width:130,sortable:true">Дата окончания</th>
                <th data-options="field:'type_name',width:140,sortable:true">Тип сообщения</th>
                <th data-options="field:'status_name',width:80,sortable:true">Статус</th>
                <th data-options="field:'text',width:'150px'">Текст</th>
                <th data-options="field:'action',width:'70px'" valign="middle"></th>
            </tr>
        </thead>
    </table>
  

  <!--table style="width:100%;height:450px">
        <thead>
            <tr valign="middle">
                <th data-options="field:'id',width:50,sortable:true">ID</th>
                <th data-options="field:'message_id',width:80,sortable:true">MSG ID</th>
                <th data-options="field:'start_datetime',width:130,valign:'center',sortable:true">Дата начала</th>
                <th data-options="field:'exp_datetime',width:130,sortable:true">Дата окончания</th>
                <th data-options="field:'status_name',width:80,sortable:true">Статус</th>
                <th data-options="field:'text',width:'150px'">Текст</th>
                <th data-options="field:'action',width:'100px'" valign="middle"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($messages as $m){ ?>
            <tr>
                <td><?=$m['id']?></td>
                <td><?=$m['message_id']?></td>
                <td><?=$m['start_datetime']?></td>
                <td><?=$m['exp_datetime']?></td>
                <td><?=$m['status_name']?></td>
                <td><?=$m['text']?></td>
                <td><nobr><a title="Редактировать" class="smallButton" href="/opera/system_messages/'<?=$m['id']?>" ><img src="images/icons/018.png"></a>
                          <a title="Удалить" class="smallButton del" onclick="return yes_no()" href="/opera/sysmsgs/delete/<?=$m['id']?>" ><img src="images/icons/101.png"></a></nobr>
                </td>
            </tr>
            <? } ?>
            
            
        </tbody>
    </table-->  
  
    <!--
    <script>
        $(function(){
            var dg = $('#dg').datagrid();
            dg.datagrid('enableFilter', [{
                field:'start_datetime',
                type:'datebox',
                op:['equal']
            
            }]);
        });
    </script>   
    -->
    
    


       