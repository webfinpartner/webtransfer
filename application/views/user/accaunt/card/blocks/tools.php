<script>
    var purpose = 'withdrawal_standart_credit';    
    if ( security == ''){
      security = 'email';
      purpose = 'card_security_action';  
  }
  
  
     function exportCard(card_id){
        $('#info_window').show();
        $('#info_window #caption').text( "<?=_e('Данные для экспорта')?>" );
        $('#message').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/export_card", 
        { card_id: card_id },  
            function(data){ 
                   if(data.message) $('#message').html( data.message );
                    
             }, "json");               
     }
     
    function updateData(card_id){
        $('#info_window').show();
        $('#info_window #caption').text( "<?=_e('Обновление данных')?>" );
        $('#message').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/updateData_card", 
            { card_id: card_id},  
                function(data){ 
                       if(data.message) $('#message').html( data.message );
             }, "json");              
    }
                
      
     function import_card_handler(){
        var card_num = $('#card_num').val();
        if ( card_num == ''){
           $( '#import_info').html( '<?=_e('Введите реквизиты карты')?>' );
           return;        
       }
        $('#out_send_window').data('card_num', card_num);
        if ( security )
        {        
            mn.security_module
                .init()
                .show_window('card_import_list')
                .done(import_card);
        }
         else
            import_card();
       
         
     }  
      

    function import_card(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        
       var code = res['code'];
       var card_num = $('#card_num').val();
       

      $('#import_info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
       $.post(site_url +  "/account/ajax_user/import_card", { card_num: card_num, code: code, purpose:mn.security_module.purpose },  function(data){ 
               if(data.card_info){
                  if ( data.card_info == 'ok'){
                    $('.popup_window').hide('slow');    
                    location.reload();
                  } else  $( '#import_info').html( data.card_info );
                }
         }, "json");         
       
        
    }  
    
    function showPin(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        
        var code = res['code'];
        var card_id =  $('#pin_window').data('card_id');
        
        
        $('#pin_window').show();
        $('#pin').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/get_card_pin", 
        { card_id: card_id, code: code, purpose:mn.security_module.purpose },  
        function(data){ 
               if(data.pin){
                  $('#pin').html( data.pin );
                }
         }, "json");      
        
    }
    
  <?/*  function showInfo(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        
        var code = res['code'];
        var card_id =  $('#details_window').data('card_id');
        
         
        $('#details_window').show();
        $('#info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/get_card_info", 
        { card_id: card_id, code: code, purpose:mn.security_module.purpose },  function(data){ 
               if(data.info){
                   if ( data.info.nameOnCard == undefined )
                       $('#info').html( data.info );
                   else 
                  $('#info').html( 
                        '<b>CARHODLER NAME: </b>' + data.info.nameOnCard + '<br>' +
                        '<b>CARD NUMBER: </b>' + data.info.pan + '<br>' +
                        '<b>EXPIRY: </b>' + data.info.expiryDate + '<br>' +
                        '<b>CVV: </b>' + data.info.cvv + '<br>'
                    );
                }
         }, "json");      
        
    } */?>
    function sendToOwnCard(card_id){
        $('#send_window').show();
        
        $('#send_card_list option').show();
        $('#send_card_list option').addClass('show');
        $('#send_card_list option[value='+card_id+']').hide();
        $('#send_card_list option[value='+card_id+']').removeClass('show');
        

        $.each($('#send_card_list option.show'), function(idx){
                if ( idx == 0 )
                    $(this).prop('selected', true);
        });
        
        
        $('.from_cards').hide();
        $('#card_from_id_'+card_id).show();
        
        
        $('#from_card_id').val(card_id);
        
    }
    
    
    function sendToOwnCardSubmit(){
        var summ = $('#send_window #summa').val();
        var to_card_id = $('#send_window #send_card_list').val();
        var from_card_id = $('#from_card_id').val();
        if ( summ == ""){
            $('#send_window #send_info').html('<?=_e("Вы не указали сумму")?>');          
            return;
        }
        
        $('#send_window #send_info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/send_to_own_card", { from_card_id: from_card_id, to_card_id: to_card_id, summ: summ},  function(data){ 
              if ( data == 'ok'){
                location.reload();
            } else {
                $('#send_window #send_info').html(data);          
              }
         }, "html");                      
        
    }
    
    function setBlock(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        
        var code = res['code'];
        var card_id =  $('#pin_window').data('card_id');
        $.post(site_url +  "/account/ajax_user/set_card_blocked", { card_id: card_id, code: code, purpose:mn.security_module.purpose },  function(data){ 
               location.reload();
         }, "json");                      

    }
    
    function setUnblock(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        var code = res['code'];
        var card_id =  $('#pin_window').data('card_id');
        $.post(site_url +  "/account/ajax_user/set_card_unblocked", { card_id: card_id, code: code, purpose:mn.security_module.purpose  },  function(data){ 
               location.reload();
         }, "json");                      

    }


    function upgrade(card_id){
        $('#upgrade_window').show();
        $('#upgrade_window_card_id').val(card_id);        
        $('#upgrade_window #upgrade_info').html('');        
    }
    
    function upgrade_handler(){
        $('#upgrade_window #upgrade_info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/upgrade_card", { card_id:  $('#upgrade_window_card_id').val() },  function(data){ 
              if ( data == 'ok'){
                location.reload();
            } else {
                $('#upgrade_window #upgrade_info').html(data);          
              }
         }, "html");         
    }

    
    function outsend(card_id){
        $('#outsend_window').show();
        $('#outsend_window_from_card_id').val(card_id);
    }
    
    
    
    function outsend_handler(){
        if ( security )
        {        
            mn.security_module
                .init()
                .show_window('card_security_action')
                .done(outsendSubmit);
        }
         else
            outsendSubmit();  
        }
        
    function outsendSubmit(res){
        if( res === undefined || res['res'] != 'success' ) return false;
        
        var code = res['code'];        
        var summ = $('#outsend_window #summa').val();
        var from_card_id = $('#outsend_window_from_card_id').val();
        var payment_code = $('#outsend_window #payout_sys').val(); 
        var payment_wallet = $('#outsend_window #paysys_wallet').val(); 
        
        if ( payment_wallet == ""){
            $('#outsend_window #send_info').html('<?=_e("Вы не указали номер кошелька")?>');          
            return;
        }        
        if ( summ == ""){
            $('#outsend_window #send_info').html('<?=_e("Вы не указали сумму")?>');          
            return;
        }
        
        if (payment_code == '-'){
            $('#outsend_window #send_info').html('<?=_e("Вы не выбрали платежную систему")?>');          
            return;            
        }
        
        
        $('#outsend_window #send_info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/send_to_paysys", { from_card_id: from_card_id, payment_code: payment_code, payment_wallet: payment_wallet, summ: summ, code: code, purpose:mn.security_module.purpose},  function(data){ 
              if ( data == 'ok'){
                location.reload();
            } else {
                $('#outsend_window #send_info').html(data);          
              }
         }, "html");         
    }
        
    
    function sendToOwnCard(card_id){
        $('#send_window').show();
        
        $('#send_card_list option').show();
        $('#send_card_list option').addClass('show');
        $('#send_card_list option[value='+card_id+']').hide();
        $('#send_card_list option[value='+card_id+']').removeClass('show');
        
        $('#send_card_list').find('.show:first').prop('selected', 'selected');
        
        
        $('.from_cards').hide();
        $('#card_from_id_'+card_id).show();
        
        
        $('#from_card_id').val(card_id);
        
    }
    
    
    function sendToOwnCardSubmit(){
        var summ = $('#send_window #summa').val();
        var to_card_id = $('#send_window #send_card_list').val();
        var from_card_id = $('#from_card_id').val();
        if ( summ == ""){
            $('#send_window #send_info').html('<?=_e("Вы не указали сумму")?>');          
            return;
        }
        
        $('#send_window #send_info').html( "<img class='loading-gif' src='/images/loading.gif'/>" );
        $.post(site_url +  "/account/ajax_user/send_to_own_card", { from_card_id: from_card_id, to_card_id: to_card_id, summ: summ},  function(data){ 
              if ( data == 'ok'){
                location.reload();
            } else {
                $('#send_window #send_info').html(data);          
              }
         }, "html");                      
        
    }
    
    
    
    function on_menu_select(menu, card_id, item){
        
        $(menu).find('option[value=menu]').attr('selected','selected');
        
        switch ( item ){
            case 'last_transactions':
                location.replace("<?=  base_url('account/card-transactions')?>?selected="+card_id);
                break;       
            case 'unblock':
                    $('#pin_window').data('card_id', card_id);
                    if ( security )
                    {
//                        $('#out_send_window').data('call-back',setUnblock).show('slow');
                        mn.security_module
                            .init()
                            .show_window('card_security_action')
                            .done(setUnblock);
                    }
                    else
                        setUnblock();                     
            break;
            case 'block':
                if (confirm("<?=_e('Вы уверены что хотите заблокировать карту?')?>")) {
                    $('#pin_window').data('card_id', card_id);
                    if ( security )
                    {
//                        $('#out_send_window').data('call-back',setBlock).show('slow');
                        mn.security_module
                            .init()
                            .show_window('card_security_action')
                            .done(setBlock);
                    }
                    else
                        setBlock();
                }
            break;
            case 'pin':
                $('#pin_window').data('card_id', card_id);
                if ( security )
                {
//                    $('#out_send_window').data('call-back',showPin).show('slow');
                    mn.security_module
                            .init()
                            .show_window('card_security_action')
                            .done(showPin);
                }
                else
                    showPin();
            break;      
            case 'info':
                $('#details_window').data('card_id', card_id);
                if ( security )
                {
//                    $('#out_send_window').data('call-back',showInfo).show('slow');
                    mn.security_module
                            .init()
                            .show_window('card_security_action')
                            .done(showInfo);
                }
                else
                    showInfo();
            break;                
            case 'pay':
                location.replace("<?=  base_url('account/payment')?>");
                break;
            case 'exportCard':
                exportCard(card_id);
            break;    
            case 'updateData':
                updateData(card_id);
            break;                

            case 'send':
                sendToOwnCard(card_id);
                break;
            case 'outsend':                
                outsend(card_id);
                break;
            case 'upgrade_level':                
                upgrade(card_id);
                break;                
        }

    }
    
 </script>

 
 <div class="popup_window" id="pin_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('Ваш PIN')?></h2>
    <div id="pin"></div>
    <button class="button" onclick="$('.popup_window').hide('slow');return false;" name="submit"><?=_e('Закрыть')?></button>
</div>

<div class="popup_window" id="details_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('Детали карты')?></h2>
    <div id="info"></div>
    <button class="button" onclick="$('.popup_window').hide('slow');return false;" name="submit"><?=_e('Закрыть')?></button>
</div>


<div class="popup_window" id="info_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2 id="caption"></h2>
    <div id="message"></div>
    <button class="button" onclick="$('.popup_window').hide('slow');return false;" name="submit"><?=_e('Закрыть')?></button>
</div>


<div class="popup_window" id="import_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('Импорт карты')?></h2>
    <?=_e('Введите код импорта карты полученный на Webtransfercard.com: ')?><br/><br/>
    <input type="text" name="card_num" id="card_num" style="padding:10px;width:300px;"><br/>
	<?=_e('в формате XXXXXXXXXXXXXXX-XXXXXX')?>
    <div id="import_info"></div>
    <button class="button" onclick="import_card_handler();return false;" name="submit"><?=_e('Импортировать')?></button>
</div>

<div class="popup_window" id="send_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('Перевести на свою карту')?></h2>
    
    <input type="hidden" id="from_card_id" name="from_card_id" value="-1">
    
    <div style="text-align: left;">
    <? foreach ($wtcards_list['ACTIVE'] as $card ) { ?>  
        <div class="from_cards" id="card_from_id_<?=$card->id?>"><?=_e('Карта списания')?>:<br> <input type="text" disabled="disable" id="from_card_name" value="<?=Card_model::display_card_name($card)?>" style="padding:5px;width:300px;"><br/></div>        
    <? } ?>        
        <br>
        <div><?=_e('Карта пополнения')?>:<br>

    <select name="card" id="send_card_list" style="padding:5px;width:300px;">
    <? foreach ($wtcards_list['ACTIVE'] as $card ) { ?>  
        <option value="<?=$card->id?>"><?=Card_model::display_card_name($card)?></option>
    <? } ?>
    </select>
    </div>
    <br>
    <div><?=_e('Сумма')?>:<br> <input type="text" name="summa" id="summa" style="padding:5px;width:100px;"><br/></div>
    </div>
    
    <div id="send_info"></div>
    <button class="button" onclick="sendToOwnCardSubmit();return false;" name="submit"><?=_e('Перевести')?></button>

</div>

<div class="popup_window" id="outsend_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <input type="hidden" id="outsend_window_from_card_id" name="from_card_id" value="-1">
    <div><?=_e('Платежная система')?><br>
    <select name="payout_sys" id="payout_sys" style="padding:5px;width:300px;" onchange="$('#paysys_wallet').val( $(this).find('option:selected').data('wallet') )">;
        <option value="-" data-wallet="">-</option>
        <option value="312" data-wallet="<?=$payeer?>">Payeer</option>
        <option value="319" data-wallet="<?=$okpay?>">OkPay</option>
        <option value="318" data-wallet="<?=$perfectmoney?>">Perfect</option>
    </select>
    </div>
    <div><?=_e('Кошелек')?><br> <input type="text" name="paysys_wallet" id="paysys_wallet" style="padding:5px;width:100px;"><br/></div>
    <div><?=_e('Сумма')?><br> <input type="text" name="summa" id="summa" style="padding:5px;width:100px;"><br/></div>
    <div id="send_info"></div>
    <button class="button" onclick="outsend_handler();return false;" name="submit"><?=_e('Перевести')?></button>
</div>

 
 <div class="popup_window" id="upgrade_window" style="padding: 0px; margin-right: -200px; width: 450px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <input type="hidden" id="upgrade_window_card_id" name="from_card_id" value="-1">
    <h2><?=_e('Увеличение лимита')?></h2>
    <?=_e('Убедитесь что у вас загружены и подтвержедны документы и нажмите кнопку "Отправить"')?><br>
    <div id="upgrade_info" style="color: red;"></div>
    <button class="button" onclick="upgrade_handler();return false;" name="submit"><?=_e('Отправить')?></button>
</div>
