<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>

<style>
    ul.tabs { background: none; border-bottom: 1px; }
    ul.tabs li {
      height: 35px;
      border-right: none; 
    }    
    .tree-icon{
            display:none;
    }    
    .datagrid-row {
         height: 45px;
    }
    .onehrineico{height:40px;width:40px;overflow:hidden;float:left;margin:-12px 10px 0 0; padding: 0;border: 1px solid transparent;}
    .sprite_payment_systems {
        background-image: url('/images/currency_exchange/spritesheet.png');
        background-repeat: no-repeat;
        display: block;
        width: 40px;
        height: 40px;
    }
    
    .datagrid-cell, .datagrid-cell-group, .datagrid-header-rownumber, .datagrid-cell-rownumber {   overflow: visible; }
    td  {  vertical-align: middle; }
    td[field=order] {text-align: center;}
</style>
<script>
    
    function set_state(id, state){
        
        $.ajax({
          type: "POST",
          url: 'opera/currency_exchange_paysys/new_set_ps_state/' + id,          
          data: { state: state },
          success: function(){ $('#new_grid').datagrid('reload');  },
          complete: function(){}
        });  
        
    }
    
    function delete_new(id, state){
        
        $.ajax({
          type: "POST",
          url: 'opera/currency_exchange_paysys/new_delete/' + id,          
          success: function(){ $('#new_grid').datagrid('reload');  },
          complete: function(){}
        });  
        
    }    
    
    function add_group(){
        
        var sel =  $('#tg').treegrid('getSelected');
        if ( sel != null ){
            var id_arr = sel.id.split('_');
            if ( id_arr[0] == 'g')
                location.replace('opera/currency_exchange_paysys/group_create/' + id_arr[1] );
        } else {
            location.replace('opera/currency_exchange_paysys/group_create/0');
        }
        
    }
    function add_ps(){
        
        var sel =  $('#tg').treegrid('getSelected');
        if ( sel != null ){
            var id_arr = sel.id.split('_');
            if ( id_arr[0] == 'g')
               location.replace('opera/currency_exchange_paysys/ps_create/'+id_arr[1]);
            else 
               location.replace('opera/currency_exchange_paysys/ps_create/'+sel.group_id);
        }         
    }
    
    
    function add_img(value,row){ 
        
        if ( row.public_path_to_icon != '' )
            return '<div class="onehrineico sprite_payment_systems" style="'+row.public_path_to_icon+'"></div>'+value;
        return value;
    }
</script>

<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Платежные системы</h6></div>                          

<div id="tt" class="easyui-tabs" style="width:100%px;height:600px;">
    <div title="Все системы" style="padding:20px;">  
            <div id="tb">
                <a href="#" onclick="add_group(); return false;"  class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">Добавить группу</a>
                <a href="#" onclick="add_ps(); return false;"  class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">Добавить платежную систему</a>
             </div>
            <table id="tg" class="easyui-treegrid" style="width:100%;height:250px"
              data-options="
                  url:'<?=base_url().'opera/currency_exchange_paysys/all'?>',
                  queryParams: {'tabledata_all':1},
                  method: 'post',
                  rownumbers: false,
                  idField: 'id',
                  treeField: 'name',
                  nowrap:false,
                  fit: true,
                  toolbar: '#tb',
                  onBeforeLoad: function(row, param){ console.log(row); if (row != null) param.type = row.type;  return true; }
              ">
              <thead>
                  <tr>
                      <th data-options="field:'name',formatter:add_img" valign="middle" width="250">Название</th>
                      <th data-options="field:'order'" valign="middle" width="100">Сортировка</th>
                      <th data-options="field:'action'"  width="100"></th>
                      
                  </tr>
              </thead>
           </table>  
    </div>
    <div title="Запросы на добавление"  style="padding:20px">
                <table id='new_grid' class="easyui-datagrid" style="width:100%;height:450px" data-options="remoteFilter:true, 
                        singleSelect:true,
                        collapsible:false,
                        pagination:false, 
                        fitColumns:true,
                        sortable: true,
                        multiSort:true,
                        collapsible:false,
                        pagination:true, 
                        pageList:[10,30,100],                
                        nowrap:false,
                        onDblClickRow: function(index, row){ window.location = '<?=base_url().'opera/currency_exchange_paysys/'?>'+ row.id; },
                        url:'<?=base_url().'opera/currency_exchange_paysys/all'?>',
                        queryParams: {'tabledata_new':1}
                         ">
            <thead>
                <tr>
                    <th data-options="field:'id'">ID</th>
                    <th data-options="field:'name'"  width="150">Название</th>
                    <th data-options="field:'count'"  width="60">Количество запросов</th>
                    <th data-options="field:'added'"  width="100">Стутус</th>
                    <th data-options="field:'action'"></th>
                    
                </tr>
            </thead>
        </table>
    </div>
</div>  
