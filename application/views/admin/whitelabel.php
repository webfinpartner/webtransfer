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
</style>
<!--link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/metro-gray/easyui.css"">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/icon.css"-->

<style>
    td[field=action]   { text-align: center; vertical-align: middle; }
</style>



<div id="tt" class="easyui-tabs" style="width:100%px;height:600px;">
    <div title="Сайты-партнеры" style="padding:20px;">  


<br>
<a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="#" onclick="add_partner(); return false;"><span>Добавить сайт-партнер</span></a>
<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Список сайтов-партнеров</h6></div>                          

    
  <table class="easyui-datagrid" id="dg" style="width:100%;height:400px" data-options="remoteFilter:false, 
                defaultFilterType:'label',
                singleSelect:true,
                multiSort:false,
                collapsible:false,
                pagination:true, 
                pageList:[10,30,100],
                showFooter: true,
                nowrap:false,
                url:'<?=base_url().'opera/whitelabel/partners_list'?>',
                method:'post'
                 ">
        <thead>
            <tr valign="middle">
                <th data-options="field:'id',width:70,sortable:true">ID</th>
                <!--th data-options="field:'user_id',width:100,sortable:true">Создал</th-->
                <th data-options="field:'name',width:80,valign:'center',sortable:true">Название</th>
                <th data-options="field:'secret_key',width:110,valign:'center',sortable:true">Ключ</th>
                <th data-options="field:'url',width:120,valign:'center',sortable:true">URL</th>
                <th data-options="field:'master',width:80,valign:'center',sortable:true">Master</th>
                <th data-options="field:'img',width:120,valign:'center',sortable:true">Логотип</th>
                <th data-options="field:'action',width:'70px'" valign="middle"></th>
            </tr>
        </thead>
    </table>

    </div>
        </div>
    
   
    
    
</div>


<script>

    function generatePass(plength){

        var keylistalpha="abcdefghijklmnopqrstuvwxyz";
        var keylistint="123456789";
        var keylistspec="!@#_";
        var temp='';
        var len = plength/2;
        var len = len - 1;
        var lenspec = plength-len-len;

        for (i=0;i<len;i++)
            temp+=keylistalpha.charAt(Math.floor(Math.random()*keylistalpha.length));

        for (i=0;i<lenspec;i++)
            temp+=keylistspec.charAt(Math.floor(Math.random()*keylistspec.length));

        for (i=0;i<len;i++)
            temp+=keylistint.charAt(Math.floor(Math.random()*keylistint.length));

            temp=temp.split('').sort(function(){return 0.5-Math.random()}).join('');

        return temp;
    }

    function add_partner(){
        $('#partner_dialog .result-msg').text('');
        $('#partner_dialog [name=id]').val( '' );
        $('#partner_dialog [name=secret]').val( generatePass(16) );
        $('#partner_dialog').dialog('open');        
        
    }

    function delete_record(id){
        if ( yes_no() ){
            $.post('<?=base_url().'opera/gift_guarant/delete_record/'?>'+id,{},
                 function(data) {
                     if (data.status=='success'){
                             $('#dg').datagrid('reload');
                         } else {
                             alert(data.message);
                         }
                 }, 'json');               
        }
    }
 
    
    function edit_partner(){
       var id = $('#edit_dialog [name=id]').val();
       var post_data = {
           nominal: $('#edit_dialog [name=nominal]').val(),
           market_price: $('#edit_dialog [name=market_price]').val()
       };
        $('#edit_dialog .loading-gif').show();
       $.post('<?=base_url().'opera/gift_guarant/change_record/'?>'+id,  post_data ,
            function(data) {
                $('#edit_dialog .loading-gif').hide();
                $('#edit_dialog .result-msg').text(data.message);
                if (data.status=='success'){
                        $('#edit_dialog').dialog('close');
                        $('#dg').datagrid('reload');
                        $('#edit_dialog .result-msg').text('');
                    } 
                  
                
            }, 'json');        
        
    }
    
    
    function show_edit_record_dialog(id){
    
            $.post('<?=base_url().'opera/gift_guarant/get_record/'?>'+id,{},
                 function(data) {
                     if (data.status=='success'){
                            $('#edit_dialog').dialog('open'); 
                            for(var k in data.data) 
                              $('#edit_dialog [name='+k+']').val( data.data[k] );
                             
                         } else{
                             alert(data.message);
                         }
                 }, 'json');               
    
    
    }
    
   
    
    </script>
    
    

    <div id="partner_dialog" class="easyui-dialog" title="Добавление сайта-партнера" style="width:650px;height:500px;"
        data-options="closed: true, resizable:true,modal:true,buttons:[{
                                id: 'event_dialog_save_btn',
				text:'Добавить',
				handler:function(){ save_partner(); }
			},{
				text:'Закрыть',
				handler:function(){ $('#partner_dialog').dialog('close'); }
			}]">
        <table>
            <tr><td>
        <input type="hidden" name="id" value="">
        <div style="padding: 5px">
            <b>Название:</b> <br><input style="width:300px" type="text" name="name" value="">
        </div>
        <div style="padding: 5px">
            <b>Ключ:</b> <br><input style="width:300px" type="text" name="secret" value="">
        </div>
        <div style="padding: 5px">
            <b>URL:</b> <br><input style="width:300px" type="text" name="url" value="">
        </div>
        <div style="padding: 5px">
            <b>Master:</b> <br><input style="width:300px" type="text" name="master" value="">
        </div> 
        </td>
        <td>
        <div style="padding: 5px">
            <b>Логотип:</b> <br><img style="width: 100px; height: 100px" src="about::blank"><br><input type="file" value="Загрузить">
        </div>              
        </td>    
            
        </tr>
        <tr>
            <td colspan="2">
                <div style="padding: 5px">
                 <b>Почтовые сервера:</b> <br> <div class="formRight">
                              <table class="easyui-treegrid" style="width:100%;height:140px"
                                data-options="
                                    url: '/opera/system_events/subscribers/<?=@$item->id?>',
                                    method: 'post',
                                    rownumbers: false,
                                    idField: 'id',
                                    treeField: 'fio'
                                ">
                                <thead>
                                    <tr>
                                        <th data-options="field:'id'" width="50">ID</th>
                                        <th data-options="field:'service_name'" width="100">Название</th>
                                        <th data-options="field:'host'" width="100">Хост</th>
                                        <th data-options="field:'port'" width="50">Порт</th>
                                        <th data-options="field:'username'" width="80">Username</th>
                                        <th data-options="field:'password'" width="80">Password</th>
                                        <th data-options="field:'protocol'" width="80">Протокол</th>
                                        <th data-options="field:'enabled'" width="80">Включен</th>
                                        <th data-options="field:'from'" width="80">From</th>

                                    </tr>
                                </thead>
                             </table>
                            </div>
                </div>                  
            </td>
        </tr>
        </table>
        <div style="text-align:center">
            <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
            <p class="center result-msg"></p>
        </div>
            
    </div>
      

    
  




       