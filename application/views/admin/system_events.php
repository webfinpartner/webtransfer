<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>


<!--link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/metro-gray/easyui.css"">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/icon.css"-->

<style>
    td[field=action]   { text-align: center; vertical-align: middle; }
</style>





<br>
<a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/system_events/create"><span>Добавить системное событие</span></a>
<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Управление  системными  событиями</h6></div>                          

    
  <table class="easyui-datagrid" id="dg" style="width:100%;height:450px" data-options="remoteFilter:false, 
                defaultFilterType:'label',
                singleSelect:true,
                multiSort:false,
                collapsible:false,
                pagination:true, 
                pageList:[10,30,100],
                nowrap:false,
                onDblClickRow: function(index, row){ window.location = '<?=base_url().'opera/system_events/'?>'+ row.id; },
                url:'<?=base_url().'opera/system_events/'?>',
                method:'post'
                 ">
        <thead>
            <tr valign="middle">
                <!--th data-options="field:'id',width:50,sortable:true">ID</th-->
                <th data-options="field:'machine_name',width:180,sortable:true">Событие</th>
                <th data-options="field:'human_name',width:230,valign:'center',sortable:true">Название</th>
                <th data-options="field:'action',width:'70px'" valign="middle"></th>
            </tr>
        </thead>
    </table>
  



       