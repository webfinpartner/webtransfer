<? /* <script>
    function on_payment_account_change(obj){
       var $this = $(obj);
       if ($this.find('option:selected').data('type') != 'card' ){
            $this.next().hide();
            return;
        }
        
       $this.next().show();
       $this.next().find('span').html('');
       
       var card_id = $this.find('option:selected').val();
       $this.next().find('#balance_loading-gif').show();
         $.post(site_url +  "/account/ajax_user/get_card_balance", { card_id: card_id, useCache: 0},  function(data){ 
                $this.next().find('#balance_loading-gif').hide();          
                $this.next().find('span').html(data.balance);
        }, "json");         
                
        
    }
    
    
function build_form(data){
    console.log(data);
    console.log(data[0].templateData);
    
    var form_html = '';
    data[0].templateData.forEach(function(item){
        
        console.log( item);
        var req = '';
        if (item.required)
            req = '<span style="color:red">*</span>';
        form_html += item.title + ' ('+item.minLength+'-'+item.maxLength+')'+req+'<br><input type="text" name="form.'+item.id+'" id="'+item.id+'"><br>';
        
    });
    
    if ( form_html != ''){
        data[0]
        form_html += 'Валюта<br><input type="text" name="bankAccountCurrencyCode" value="'+data[0].templateCurrency+'"><br>';
        form_html += '<input type="hidden" name="bankAccountTemplateId" value="'+data[0].templateId+'"><br>';
        form_html += '<input type="hidden" name="bankAccountCountryCode" value="'+data[0].templateCountry+'"><br>';

        form_html += '<button type="submit"><?=_e('Отправить')?></button>';
    }
    
    $('#form').html(form_html);
    
    
}
    
function on_country_change(obj){
     var $this = $(obj);
     var country = $this.find('option:selected').val();
     if ( country  == '-')
         return;
     $('#form').html( '<img src="/images/loading.gif" height="16" width="16" />');
     $.post(site_url +  "/account/ajax_user/get_bank_account_template", { country: country },  function(data){ 
                build_form(data);
        }, "json");             
}
  
</script>
<br><br>
<form method="post">
<?=_e('Выберите счет: ') ?><br>
          <select id="card_id" name="card_id" onchange="on_payment_account_change(this)" style="width: 200px; padding: 5px;">
           <option value="-">-</option>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
            <option value="<?=$card->id?>"  data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>          
          </select>
         <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
         
      
<br><?=_e('Сумма: ') ?><br>         
<input type="text" name="summa">
<br><?=_e('Выберите страну: ') ?><br>
          <select id="country" name="country" onchange="on_country_change(this)" style="width: 200px">
           <option value="-">-</option>
          <? if (!empty($countries)) foreach( $countries as $item ){ ?>
            <option value="<?=$item['isoAlpha2Code']?>"><?=$item['name']?></option>
          <? } ?>          
          </select>

<br>
<div id="form">
    
    
    
</div>
</form> */ ?>