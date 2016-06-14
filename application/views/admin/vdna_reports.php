<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.json.js"></script>


<!--link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/metro-gray/easyui.css"">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>js/admin/jeasyui/themes/icon.css"-->

<style>
    td[field=action]   { text-align: center; vertical-align: middle; }
</style>


<script>
    
    var xhr = new XMLHttpRequest();
    
    function onGenDialogOpen(){
        
        $('#gen_report_dialog').dialog('setTitle', 'Генерация отчета');   
        
        $('#dateFrom').datebox('setValue', '' );
        $('#dateTo').datebox('setValue', '' );
        $('#dateFrom').datebox('enable');
        $('#dateTo').datebox('enable');
        
        $('#gen_report_dialog').dialog('open')        
        $('#startBtn').linkbutton('enable');
        $('#sendBtn').linkbutton('disable');
        $('#pg').propertygrid('loadData', {'rows':[]});
    }
    
    
    function ShowStep( res ){
        
        console.log(res);
        if ( res.act == 'step'){
            $('#progressor').css('width', res.perc+'%');
            $('#progressor').text( res.perc + "%" + ' - ' + res.mem );
        } else if ( res.act == 'result' ){
            $('#dg').datagrid('reload');
            onLoad( res.id );
        }
    }
    
    function onSend()
    {
        if ( $('#gen_report_dialog').data('status') == <?=Visualdna_model::REPORT_STATUS_SENDED_TO_VDNA?> )
            $.messager.confirm('Внимание','Отчет уже был отправлен в VDNA. Вы уверены что хотите повторить отправку?',function(r){
                if (r) window.location.replace('<?=base_url().'opera/vdna_reports/send/'?>'+$('#gen_report_dialog').data('id'));
            });
        else
            window.location.replace('<?=base_url().'opera/vdna_reports/send/'?>'+$('#gen_report_dialog').data('id'));
    }
    
    function onLoad(id){
        
        
        $.ajax({
            url: '<?=base_url().'opera/vdna_reports/'?>'+id,
            dataType : "json",                     
            success: function (data, textStatus) { 
                //$('#gen_report_dialog').data('data', data);   
                $('#gen_report_dialog').data('id', id);   
                $('#gen_report_dialog').data('status', data.status); 
                $('#dateFrom').datebox('setValue', data.date_from );
                $('#dateTo').datebox('setValue', data.date_to );
                $('#dateFrom').datebox('disable');
                $('#dateTo').datebox('disable');
                
                $('#pg').propertygrid('loadData', data.stat);
                $('#progressor').css('display', 'none');
                $('#startBtn').linkbutton('disable'); 
                $('#sendBtn').linkbutton('enable');
                $('#gen_report_dialog').dialog('setTitle', 'Просмотр отчета от '+data.create_date);   
                $('#gen_report_dialog').dialog('open');   
            } 
        });        
        
    }
    
    function onGen(){
        $('#startBtn').linkbutton('disable');
        $('#progressor').css('display', 'block');
        $('#progressor').css('width', '0%');
        $('#progressor').text( '' );
        
        $('#pg').propertygrid('loadData', []);
        
        xhr.previous_text = '';
        xhr.onerror = function() { 
                $('#gen_report_dialog textarea').val( 'Ошибка. Попробуйте позже или обратитесь к разработчикам.');
                $('#startBtn').linkbutton('enable');
         };
        xhr.onreadystatechange = function() 
        {
                try
                {
                    console.log( "stat: " + xhr.readyState ) ;
                    if (xhr.readyState > 2)
                    {
                        var new_response = xhr.responseText.substring(xhr.previous_text.length);
                        console.log(new_response);
                        if (new_response != ''){
                            try {
                                var result = $.evalJSON( new_response );
                                console.log(result);
                                ShowStep( result );
                            } catch(e){}
                        }
                        xhr.previous_text = xhr.responseText;
                    }   
                }
                catch (e){}
                 
                 
        };
     
        xhr.open("POST", "<?=base_url().'opera/vdna_reports/gen'?>", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("dateFrom="+$('#gen_report_dialog input[name=dateFrom]').val()+"&dateTo="+$('#gen_report_dialog input[name=dateTo]').val());            
    }
    
    
</script>    


<div id="gen_report_dialog" class="easyui-dialog" title="Генерация отчета" style="width:450px;height:500px;"
        data-options="iconCls:'icon-save',resizable:true,modal:true,closed:true,
			buttons:[{
                                id: 'sendBtn',
				text:'Отправить',
                                disabled: true,
				handler:function(){ onSend();  }
			},{
                                id: 'startBtn',
                                text:'<b>Старт</b>',
				handler:function(){ onGen(); }
			},{
                                id: 'closeBtn',
				text:'Закрыть',
				handler:function(){ if( xhr ) xhr.abort();  $('#gen_report_dialog').dialog('close');}
			}
                        ]">
    <div style="margin: 5px; text-align: center">
        С <input name='dateFrom' id='dateFrom' type="text" class="easyui-datebox" style="width:120px;">
        По <input name='dateTo' id='dateTo' type="text" class="easyui-datebox" style="width:120px;"><br><br>
        <b>Результаты:</b>
        <table id="pg" class="easyui-propertygrid" style="width:100%; height: 330px"
            data-options="nowrap:false, showGroup:false,scrollbarSize:0, 
                        columns: [[
                                  {field:'name', title:'Параметр', width: '300px'},
                                  {field:'value', title:'Количество', width: '70px'}
                                ]]">
        </table>        
        
        <div  style="border:1px solid #ccc; margin: 5px; width:95%; height:16px; text-align: center; overflow:auto; background:#eee;">
            <div id="progressor" style="background:#07c; width:0%; height:100%; font-size: 10px"></div>
        </div>    
    </div>
    
    <!--textarea name="output" style="width: 95%; height: 100px; display: none"></textarea-->
</div>

       
        
<br>
<a style="margin: 5px 0px;float:right;" class="button greenB" title="" onclick="onGenDialogOpen();return false;" href="#"><span>Сформировать отчет</span></a>
<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Управление отчетами VisualDNA</h6></div>                          

    
  <table class="easyui-datagrid" id="dg" style="width:100%;height:450px" data-options="singleSelect:true,
                multiSort:false,
                collapsible:false,
                pagination:true, 
                pageList:[10,30,100],
                nowrap:false,
                onDblClickRow: function(index,row){
                    onLoad( row.id );
                },
                url:'<?=base_url().'opera/vdna_reports/'?>',
                method:'post'
                 ">
        <thead>
            <tr valign="middle">
                <!--th data-options="field:'id',width:50,sortable:true">ID</th-->
                <th data-options="field:'create_date',width:180,sortable:true">Дата формирования</th>
                <th data-options="field:'period',width:180">Период</th>
                <th data-options="field:'records_count',width:60,valign:'center'">Записей</th>
                <th data-options="field:'status_text',width:70,valign:'center'">Статус</th>
                <th data-options="field:'report_app_path',width:130,valign:'center'">AppReport</th>
                <th data-options="field:'report_bhvr_path',width:130,valign:'center'">BehaviourReport</th>
                <th data-options="field:'action',width:'70px'" valign="middle"></th>
            </tr>
        </thead>
    </table>
  



       