<!--link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/metro-gray/easyui.css">
<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">


<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/datagrid-filter.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/datagrid-groupview.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/locale/easyui-lang-ru.js"></script-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="/js/admin/amcharts_3_13_1/amcharts.js" type="text/javascript"></script>   
<script src="/js/admin/amcharts_3_13_1/serial.js" type="text/javascript"></script>   
<script src="/js/admin/amcharts_3_13_1/themes/light.js" type="text/javascript"></script>   

<style>
    hr {margin: 10px}
    
    div.checker {
        float: none;
        display: inline-block;
    }
    
    .dateFromTo {
        padding: 5px;
    }
    .dateFromTo input[type=text] {
        width: 150px;
        font-size: 12px;
        height: 20px;
    }
    

               .ui-dropdownchecklist-dropcontainer
               {   
                width: 155px !important;  
                height: 175px !important;  
               }


                    
</style>


<script>

  function onLoadStart(container){
        $('#loading').html('Идет загрузка...');
        $('#'+container).html('');
        
        
        
  }
  function onLoadEnd(container){
        $('#loading').html('');
  }  
  

  function showChart(container, url, chart_name, params){
        onLoadStart(container);
        $.ajax({
          type: "POST",
          url: url,
          data: {'container':container, 'chart_name':chart_name, 'params': JSON.stringify(params)},
          dataType: "script", 
          complete: function(){
              onLoadEnd(container);
              
            //alert('Load was performed.');
          }
        });   
  }
  
  function loadData(container, url, chart_name, params){
        $.ajax({
          type: "POST",
          url: url,          
          data: {'container':container, 'chart_name':chart_name, 'params': JSON.stringify(params) },
          dataType: "json", 
          success: function(){
            chartData.push(dataObject);
            chart.validateData();
            
          },
          complete: function(){
             onLoadEnd(container); 
          }
        });   
  }
  
  function loadServices( source, target ){
       var source_selector = '#' + source;
       var target_selector = '#' + target;
        $.ajax({
          type: "POST",
          url: '/opera/statistic_messenger/get_services',
          data: {'messenger': $(source_selector + ' option:selected').val() },          
          dataType: "json", 
          success: function(data){
             $(target_selector + ' option[value!=ALL]').remove();
             $(target_selector + ' option').attr('selected','selected');
             for( idx in data )
                $(target_selector).append('<option value="'+data[idx].machine_name+'">'+data[idx].human_name+'</option>');           
             
             
          }
        });   
  }  
  
  
  <?php
      $dateStart = date_sub( new DateTime(), date_interval_create_from_date_string('3 days'));
      
  ?>

  $(function() {
    $( "#tabs" ).tabs();
    $('.dateFromTo input[name=dateFrom]').datetimepicker({format: 'd.m.Y H:i', lang:'ru', mask: true});
    $('.dateFromTo input[name=dateFrom]').val('<?=  date_format($dateStart, 'd.m.Y H:00')?>');
    $('.dateFromTo input[name=dateTo]').datetimepicker({format: 'd.m.Y H:i', lang:'ru', mask: true});
    $('.dateFromTo input[name=dateTo]').val('<?=date('d.m.Y 23:59')?>');
    $('#stat_sms_total_service_selector').dropdownchecklist({emptyText:'Все сервисы',width:400, maxDropHeight: 150,minWidth: 140 });
    


    // 5min
    showChart('5minChart', '/opera/statistic_messenger/get_chart','5minChart', {messenger: 'ALL', from: $('#stat5min input[name=dateFrom]').val(), to: $('#stat5min input[name=dateTo]').val()} );
    $('#stat5min_messanger_selector').change(function(){
         showChart( '5minChart', '/opera/statistic_messenger/get_chart','5minChart', {messenger: $('#stat5min_messanger_selector option:selected').val(), service: $('#stat5min_service_selector option:selected').val(), from: $('#stat5min input[name=dateFrom]').val(), to: $('#stat5min input[name=dateTo]').val() });
         loadServices( 'stat5min_messanger_selector', 'stat5min_service_selector'  );
    });
    
    $('#stat5min_service_selector').change(function(){
         showChart( '5minChart', '/opera/statistic_messenger/get_chart','5minChart', {messenger: $('#stat5min_messanger_selector option:selected').val(), service: $('#stat5min_service_selector option:selected').val(), from: $('#stat5min input[name=dateFrom]').val(), to: $('#stat5min input[name=dateTo]').val()});
    });
    //1 hour
    showChart('1hourChart', '/opera/statistic_messenger/get_chart','1hourChart', {messenger: 'ALL', from: $('#stat1hour input[name=dateFrom]').val(), to: $('#stat1hour input[name=dateTo]').val()} );
    $('#stat1hour_messanger_selector').change(function(){
         showChart( '1hourChart', '/opera/statistic_messenger/get_chart','1hourChart', {messenger: $('#stat1hour_messanger_selector option:selected').val(), service: $('#stat1hour_service_selector option:selected').val(), from: $('#stat1hour input[name=dateFrom]').val(), to: $('#stat1hour input[name=dateTo]').val() });
         loadServices( 'stat1hour_messanger_selector', 'stat1hour_service_selector' );
    });
    
    $('#stat1hour_service_selector').change(function(){
         showChart( '1hourChart', '/opera/statistic_messenger/get_chart','1hourChart', {messenger: $('#stat1hour_messanger_selector option:selected').val(), service: $('#stat1hour_service_selector option:selected').val(), from: $('#stat1hour input[name=dateFrom]').val(), to: $('#stat1hour input[name=dateTo]').val()});
    });
    
    // sms balance
    showChart('stat_sms_balance_chart', '/opera/statistic_messenger/get_chart','smsBalanceChart', {messenger: 'ALL', from: $('#stat_sms_balance input[name=dateFrom]').val(), to: $('#stat_sms_balance input[name=dateTo]').val()} );
    $('#stat_sms_balance_service_selector').change(function(){
         showChart( 'stat_sms_balance_chart', '/opera/statistic_messenger/get_chart','smsBalanceChart', {service: $('#stat_sms_balance_service_selector option:selected').val(),from: $('#stat_sms_balance input[name=dateFrom]').val(), to: $('#stat_sms_balance input[name=dateTo]').val(), 'daily': $('#stat_sms_balance_daily').is(':checked') });
    });    
    $('#stat_sms_balance_daily').change(function () { $('#stat_sms_balance_service_selector').trigger('change'); });    
    
    // sms total
    showChart('stat_sms_total_chart', '/opera/statistic_messenger/get_chart','smsTotalChart', {service: 'ALL', from: $('#stat_sms_total input[name=dateFrom]').val(), to: $('#stat_sms_total input[name=dateTo]').val()} );
    $('#stat_sms_total_service_selector').change(function(){
         var service_list = "";
         var selector = document.getElementById('stat_sms_total_service_selector'); 
         for ( i=0; i< selector.options.length; i++ )
             if (selector.options[i].selected ){
                 if (service_list!="") service_list += ",";
                 service_list += selector.options[i].value.toLowerCase();
             }
         
         
         showChart( 'stat_sms_total_chart', '/opera/statistic_messenger/get_chart','smsTotalChart', {service: service_list,from: $('#stat_sms_total input[name=dateFrom]').val(), to: $('#stat_sms_total input[name=dateTo]').val() });
    });    

    
  });   
</script>


<div id="tabs">
  <ul>
    <li><a href="#stat5min">5-минутная статистика</a></li>
    <li><a href="#stat1hour">Почасовая статистика</a></li>
    <li><a href="#stat_sms_balance">Статистика баланса СМС</a></li>
    <li><a href="#stat_sms_total">Общая статистика СМС</a></li>
    
  </ul>
  
  
  <div id="stat5min">
        <div class="dateFromTo">
            С <input type="text"  name="dateFrom"> по <input type="text" name="dateTo"><br>
        </div>
      <hr>
       <select id="stat5min_messanger_selector">
          <option value="ALL">Все мессенжеры</option>
          <? foreach( $messengers as $messenger ) { ?>
          <option value="<?=$messenger->id?>"><?=$messenger->human_name?></option>
          <? }?>
       </select> 
       <select id="stat5min_service_selector">
          <option value="ALL">Все</option>
       </select>                                                              
       <a class="button greenB" title="" href="#" onclick="$('#stat5min_messanger_selector').trigger('change'); return false;"><span>Обновить</span></a>
       <span id="loading"></span>
        <div id="5minChart" style="width: 100%; height: 600px;"></div>  
                                                                    
                                                                  
  </div>
  
  
  <div id="stat1hour">
        <div class="dateFromTo">
            С <input type="text"  name="dateFrom"> по <input type="text" name="dateTo"><br>
        </div>
      <hr>
       <select id="stat1hour_messanger_selector">
          <option value="ALL">Все мессенжеры</option>
          <? foreach( $messengers as $messenger ) { ?>
          <option value="<?=$messenger->id?>"><?=$messenger->human_name?></option>
          <? }?>
       </select> 
       <select id="stat1hour_service_selector">
          <option value="ALL">Все сервисы</option>
       </select>   
       <select id="stat1hour_provider_selector">
          <option value="ALL">Все провайдеры</option>
       </select>                           
       <select id="stat1hour_country_selector">
          <option value="ALL">Все страны</option>
          <? foreach( $countries as $country ) { ?>
          <option value="<?=$country?>"><?=$country?></option>
          <? }?>          
       </select>                                                                   
       <a class="button greenB" title="" href="#" onclick="$('#stat1hour_messanger_selector').trigger('change'); return false;"><span>Обновить</span></a>
       <span id="loading"></span>  
       <div id="1hourChart" style="width: 800px; height: 600px;"></div>  
  
  </div>
  
  <div id="stat_sms_balance">
    <div class="dateFromTo">
        С <input type="text"  name="dateFrom"> по <input type="text" name="dateTo">
    </div>
      <hr>
       <select id="stat_sms_balance_service_selector">
          <option value="ALL">Все сервисы</option>
          <? foreach( $sms_services as $service ) { ?>
          <option value="<?=$service->machine_name?>"><?=$service->human_name?></option>
          <? }?>            
          
       </select>                       
      <div style="white-space:nowrap;">
        <a class="button greenB" title="" href="#" onclick="$('#stat_sms_balance_service_selector').trigger('change'); return false;"><span>Обновить</span></a>      
        <input type="checkbox" id="stat_sms_balance_daily" name=stat_sms_balance_daily">&nbsp;<label for="stat_sms_balance_daily">Показать расход по дням</label>
      </div>
      
       
       <span id="loading"></span>  
       <div id="stat_sms_balance_chart" style="width: 800px; height: 600px;"></div>  
  
  </div>
    
  <div id="stat_sms_total">
    <div class="dateFromTo">
        С <input type="text"  name="dateFrom"> по <input type="text" name="dateTo"><br>
    </div>
      <hr>
      <select id="stat_sms_total_service_selector" multiple="multiple">
          <? foreach( $sms_services as $service ) { ?>
          <option value="<?=$service->machine_name?>"><?=$service->human_name?></option>
          <? }?>             
      </select>&nbsp;
       <a class="button greenB" title="" href="#" onclick="$('#stat_sms_total_service_selector').trigger('change'); return false;"><span>Обновить</span></a>
       <span id="loading"></span>  
       <div id="stat_sms_total_chart" style="width: 800px; height: 600px;"></div>  
  
  </div>    

    
</div>

     
     