<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?
$bonus = $accaunt_header['payment_account_by_bonus'][1] -$accaunt_header['all_advanced_invests_summ_by_bonus'][1]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][1];//$accaunt_header[ 'bonuses' ] - $accaunt_header[ 'all_advanced_invests_bonuses_summ' ];
$partner_funds = $accaunt_header[ 'payment_account_by_bonus' ][3] - $accaunt_header[ 'all_advanced_invests_summ_by_bonus' ][3]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][3];
$c_creds_funds =  $accaunt_header[ 'payment_account_by_bonus' ][4] - $accaunt_header[ 'all_advanced_invests_summ_by_bonus' ][4]-$accaunt_header['all_advanced_standart_invests_summ_by_bonus'][4];

?>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="/js/datatable/jquery.dataTables.columnFilter.js"></script>

<style>
    .dataTables_filter { visibility: hidden; }
    .dataTables_info { visibility: hidden; }
    .table_results tbody tr {cursor: pointer;}
    .table_cell {
        text-align: center;
    }
    
    .id_cell { border-bottom: 1px solid blue; }
    #loader_window{
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -20px;
        margin-left: -50px;
        width: 100px;
        height: 40px;
    }​    
    
</style>


<div style="padding:10px;border:1px solid #eee;margin:10px auto;white-space:nowrap">
    <?=_e('accaunt/applications_1')?> </div>

   <?if($this->lang->lang()=='ru'){?>
   <br/>
   <center>
<iframe id='ad467191' name='ad467191' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=29&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a135c7d7&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=29&amp;cb={random}&amp;n=a135c7d7&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </center>
   <br/>
 <?}?>

   
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>
<!---->
<!--/*
  *
  * Revive Adserver Javascript Tag
  * - Generated with Revive Adserver v3.1.0-beta
  * - Revive Adserver
  *
  */-->

<div id="popup_credit" class="popup_window small">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="content" >
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif">
    </center>
</div>


<div id="popup_credit_error" class="popup_window" style="z-index:1110;">
    <div onclick="$('#popup_credit_error').hide('slow');" class="close"></div>
    <div class="content" >
        <p><?=_e('accaunt/applications_2')?></p>
    </div>
    <a class="button narrow" onclick="$(this).parent().hide('slow');
            $('#popup_credit').removeClass('show');
            hideDetails();" name="submit"><?=_e('accaunt/applications_3')?></a>
</div>


<table class="table_results hover">
        <thead class="table_header">
            <tr>
                <th  data-orderable="true" data-searchable="true" data-data="id" class="table_cell"><?=_e('№');?></td>    
                <th data-orderable="true"  data-data="type" data-className="table_cell"><?=_e('Счет')?></td>    
                <th data-orderable="true"  data-data="garant" class="table_cell"><?=_e('Тип')?></td>    
                <th data-orderable="true" data-data="summa" class="table_cell"><?=_e('$')?></td>           
                <th data-orderable="true" data-data="time" class="table_cell"><?=_e('Дней')?></td>    
                <th data-orderable="true" data-data="percent" class="table_cell"><?=_e('%')?></td>   
                <th data-orderable="true" data-data="out_summ" class="table_cell"><?=_e('Возврат')?></td>   
            </tr>
        </thead>    
    </table>    





<div class="popup_window direct">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('accaunt/applications_11')?></h2>
    <span style="float:left; text-align: left;">
    <?=_e('accaunt/applications_12')?>
    </span>
</div>

<div class="popup_window" id="garant-option">
    <div class="close" onclick="$('.popup_window').hide('slow');"></div>
    <h2><?=_e('accaunt/applications_13')?></h2>
    <?=_e('accaunt/applications_14')?>
</div>
<div class="popup_window" id="standart-option">
    <div class="close" onclick="$('.popup_window').hide('slow');"></div>
    <h2><?=_e('accaunt/applications_15')?></h2>
    <?=_e('accaunt/applications_16')?>
    <br>
    <div style="display:none; color:red;" class="card_notice"><br><br><?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?></div>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" onchange="on_payment_account_change(this)" style="width: 200px;padding: 5px;"><? 
                    $selected = '';
                    echo "<option data-type='none' value='-'$selected>-</option>";
                    $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                     
                    
          ?>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
            <option value="<?=$card->id?>"  data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>          
            
         <? if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ 
             if ( $own_acc->account_type == 'E_WALLET')
                 $own_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
             else
                 $own_type = $own_acc->account_type;
             ?>
            <option value="<?=$own_acc->id?>" data-type="<?=$own_type?>"><?=Card_model::display_own_account_name($own_acc)?></option>
         <? } ?>                                    
            
          </select>
         <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
</div>
<div class="popup_window popoup_debit_credit garant borrow" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_17')?></h2>
    <?=_e('accaunt/applications_18')?>
    <br>
    <div style="display:none; color:red;" class="card_notice"><br><br><?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?></div>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" onchange="on_payment_account_change(this)" style="width: 200px;padding: 5px;"><? 
                    $selected = '';
                    echo "<option data-type='none' value='-'$selected>-</option>";
                    $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                      
          ?>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
            <option value="<?=$card->id?>"  data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>     
            
         <? if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ 
             if ( $own_acc->account_type == 'E_WALLET')
                 $own_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
             else
                 $own_type = $own_acc->account_type;
             ?>
            <option value="<?=$own_acc->id?>" data-type="<?=$own_type?>"><?=Card_model::display_own_account_name($own_acc)?></option>
         <? } ?>                                    
            
          </select>
         <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_19')?></button>

</div>

<div class="popup_window popoup_debit_credit standart borrow" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_20')?></h2>
    <?=_e('accaunt/applications_21')?>
    <br>
    <div style="display:none; color:red;" class="card_notice"><br><br><?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?></div>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account"  onchange="on_payment_account_change(this)" style="width: 200px;padding: 5px;"><? 
                    $selected = '';
                    echo "<option data-type='none' value='-'$selected>-</option>";
                    $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                    
                    echo "<option data-type='account' value='1'$selected>B-CREDS - $ ".price_format_double($bonus, TRUE, TRUE)."</option>";                             
                    echo "<option data-type='account' value='4'$selected>C-CREDS - $ ".price_format_double($c_creds_funds, TRUE, TRUE)."</option>";                             
                    
          ?>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
            <option value="<?=$card->id?>"  data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>          
            
         <? if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ 
             if ( $own_acc->account_type == 'E_WALLET')
                 $own_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
             else
                 $own_type = $own_acc->account_type;
             ?>
            <option value="<?=$own_acc->id?>" data-type="<?=$own_type?>"><?=Card_model::display_own_account_name($own_acc)?></option>
         <? } ?>                                    
            
          </select>
        <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_22')?></button>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>
<div class="popup_window popoup_debit_credit garant credit" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_23')?></h2>
    <?=_e('accaunt/applications_24')?>
    <br>
    <div style="display:none; color:red;" class="card_notice"><br><br><?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?></div>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" onchange="on_payment_account_change(this)" style="width: 200px; padding: 5px;"><? 
                    $selected = '';
                    echo "<option  data-type='none' value='-'$selected>-</option>";
                    $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                    echo "<option  data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                    
                    echo "<option data-type='account' value='1'$selected>B-CREDS - $ ".price_format_double($bonus, TRUE, TRUE)."</option>";                             
                    echo "<option data-type='account' value='4'$selected>C-CREDS - $ ".price_format_double($c_creds_funds, TRUE, TRUE)."</option>";                             
                    
          ?>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
              <option value="<?=$card->id?>"  data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>          
              
         <? if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ 
             if ( $own_acc->account_type == 'E_WALLET')
                 $own_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
             else
                 $own_type = $own_acc->account_type;
             ?>
            <option value="<?=$own_acc->id?>" data-type="<?=$own_type?>"><?=Card_model::display_own_account_name($own_acc)?></option>
         <? } ?>                                    
              
          </select>
        <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_25')?></button>
</div>

<? $this->load->view(  'user/accaunt/blocks/renderVisualDNA_window', compact($user_id)); ?>

<div class="popup_window popoup_debit_credit standart credit" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_26')?></h2>
    <?=_e('accaunt/applications_27')?>
    
    <div style="display:none; color:red;" class="card_notice"><br><br><?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?></div>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" onchange="on_payment_account_change(this)" style="width: 200px;padding: 5px;"><? 
                    $selected = '';
                    echo "<option data-type='none' value='-'$selected>-</option>";
                    $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                            
                    
          ?>
          <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
            <option value="<?=$card->id?>" data-type="card"><?=Card_model::display_card_name($card)?></option>
          <? } ?>
            
         <? if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ 
             if ( $own_acc->account_type == 'E_WALLET')
                 $own_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
             else
                 $own_type = $own_acc->account_type;
             ?>
            <option value="<?=$own_acc->id?>" data-type="<?=$own_type?>"><?=Card_model::display_own_account_name($own_acc)?></option>
         <? } ?>                                    
            
          </select>
        <div class="card_balance" style="display: none"><?=_e('Баланс: ')?>$ <span class="summ"></span><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></div>
          
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_28')?></button>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>
<script>
    var security = '<?=$security?>';
    function standart_calc(){
        var wrongs = 0,
        id = $('#out_send_window').data('credit-id'),
        garant = $('#out_send_window').data('credit-garant'),
        pa = $('#out_send_window').data('pa'),
        code = $('#code').val();
        

        wrongs = checkField('#code', wrongs);

        if( wrongs ) return false;

        $('#out_send_window').hide('slow');
        $('#loader_window').show();
        $.post(site_url + '/account/take_credit/' + id + '/'+code, {code:code, payment_account: pa}, function(data) {
            $('.loading-gif').hide();

            var wrap = $('#answer_contnet');

            wrap.html( getMessage( data.state, 0, garant ) );
             $('#loader_window').hide();

            $('#popup_credit').delay(4000).removeClass('show');
        }, 'json');
    }

     var search_table = null;
        $().ready(function() {
         var table_lang = {};
         if ('<?=_e('lang')?>'=='ru')
            table_lang  = { "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Russian.json" };    
          
            // Setup - add a text input to each footer cell
           $('.table_results thead th').each( function () {
               if ( $(this).data('data') == 'garant'){
                   var html = "<select id='garant_select' style='width: 80px'>";
                    html += '<option value="">-</option>';      
                    html += '<option value="1"><?=_e('Гарант') ?></option>';                         
                    html += '<option value="0"><?=_e('Стандарт') ?></option>';                       
                    html += "</select>";
                    $(this).html( $(this).html() + '<br>'+html );
              }  else if ( $(this).data('data') == 'type'){
                   var html = "<select id='type' style='width: 100px'>";
                    html += '<option value="">-</option>';      
                    html += '<option value="USDh">Webtransfer USD&#10084;</option>';                         
                    html += '<option value="card">Webtransfer VISA</option>'; 
					html += '<option value="USDD">Webtransfer DEBIT</option>';     
					
                    html += '<option value="BANK_CARD"><?=_e('Банковская карта') ?></option>';                         
                    html += '<option value="BANK_ACCOUNT"><?=_e('Банковский счет') ?></option>';                         
                    html += '<option value="E_WALLET"><?=_e('Электронные кошельки') ?></option>';                        
                    html += '<option value="E_WALLET_Paypal"> - Paypal</option>';  
                    html += '<option value="E_WALLET_Skrill"> - Skrill</option>';  
                    html += '<option value="E_WALLET_Alipay"> - Alipay</option>';  
                    html += '<option value="E_WALLET_Google"> - Google Wallet</option>';  
                    html += '<option value="E_WALLET_Okpay"> - Okpay</option>';  
                    html += '<option value="E_WALLET_Qiwi"> - Qiwi</option>';  
                    html += '<option value="E_WALLET_Webmoney"> - Webmoney</option>';  
                    html += '<option value="E_WALLET_Yandex"> - Yandex</option>';  
                    html += '<option value="E_WALLET_Liqpay"> - Liqpay</option>';  
                    html += '<option value="E_WALLET_Perfectmoney"> - Perfect Money</option>';  
                    html += '<option value="E_WALLET_Payeer"> - Payeer</option>';  
                    html += '<option value="E_WALLET_Skrill"> - Skrill</option>';  
                    html += '<option value="E_WALLET_Other"> - <?=_e('Другие')?></option>';
                    html += "</select>";
                    $(this).html( $(this).html() + '<br>'+html );
              } else
                    $(this).html( $(this).html() + '<br><input class="input_search"  style="width: 50px" type="text" placeholder="" />' );
           } );          
          
         search_table = $('.table_results').dataTable({
                "language": table_lang,                
                "searching": true,
                "processing": true,
                "serverSide": true,
                'iDisplayLength':25,
                "order": [[ 1, "desc" ]],
                "columns": [
                    { className: "table_cell", render: function ( data, type, row ) { return '<span class="id_cell">'+data+'</span>' } },
                    { className: "table_cell" },
                    { className: "table_cell" },
                    { className: "table_cell" },
                    { className: "table_cell" },
                    { className: "table_cell" },
                    { className: "table_cell" }
                ],
                
                "ajax": {
                    "url": "<?=$url_invest?>",
                    "type": "POST",
                    "data": function ( d ) {
                        console.log(d);
                        //var fields = $( "#sel_wt_form" ).serializeArray();
                            //jQuery.each( search_fields, function( i, field ) {
                              //  d[field.name] = field.value;
                            //});
                        }
                },
                "paginationType": "full_numbers"
            });            
            

            // Apply the search
             search_table.api().columns().every( function () {
                 var that = this;

                 $( 'input', this.header() ).on( 'click', function (e) {
                     e.stopPropagation();
                 } );
                 $( 'select', this.header() ).on( 'click', function (e) {
                     e.stopPropagation();
                 } );

                 
                 $( 'input', this.header() ).on( 'keyup change', function () {
                     if ( that.search() !== this.value ) {
                         that
                             .search( this.value )
                             .draw();
                     }
                 } );
                 
                 $( 'select', this.header() ).on( 'keyup change', function () {
                     
                     if ( that.search() !== this.value ) {
                         that
                             .search( this.value )
                             .draw();
                     }
                 } );                 
             } );            
           
           /*
            $('.table_results tbody').on('mouseover', 'tr', function(event) {
                var data = search_table.fnGetData( this );
                console.log(data);
                
                viewDetails($(this), site_url + '/account/ajax_user/get_user_data/1/' + data.id_user + '/' + data.id, event);
            }).on('mouseout', 'tr', function() {
                hideDetails();
            });
            */
            
            $(".table_results tbody").delegate("tr", "click", function() {
                var data = search_table.fnGetData( this );
                console.log(data);
                viewDetails($(this), site_url + '/account/ajax_user/get_user_data/1/' + data.id_user + '/' + data.id);
            });                   


        
        

        $('#confirm_invest2').click(function(){

            var std = 0;
            $('#popup_debit').hide('slow');
            if(!security){
                $('#popup_load').show('slow');
                return true;
            }else{
                if( $("#standart").attr("checked") )
                {
                    if( $(this).hasClass('active') ){
                        $('#popup_load').show('slow');
                        return true;
                    }else{
                        $('#out_send_window').show('slow');
                        return false;
                    }
                }else{
                    $('#popup_load').show('slow');
                    return true;
                }            
            }
        });


    });
</script>

<script>
    
    function on_payment_account_change(obj){
       var $this = $(obj);
       if ($this.find('option:selected').data('type') != 'card' ){
            $this.next().hide();
            return;
        }
        
       $this.next().show();
       $this.next().
               find('span').html('');
       
       var card_id = $this.find('option:selected').val();
       $this.next().find('#balance_loading-gif').show();
         $.post(site_url +  "/account/ajax_user/get_card_balance", { card_id: card_id, useCache: 0},  function(data){ 
                $this.next().find('#balance_loading-gif').hide();          
                $this.next().find('span').html(data.balance);
        }, "json");         
                
        
    }
    
          function checkField(_link, _wrongs, _pattern) {
            var elem = $(_link),
                    res = 0;

            if (elem.length == 0)
                return -1;

            if (elem.val() == '') {
                elem.addClass('wrong');
                res = 1;
            }
            else
                elem.removeClass('wrong');

            return _wrongs + res;
        }

        /*
        jQuery('#popup_credit').mouseover(function() {
            clearTimeout(detailViewTimerID);
        }).mouseout(function() {
            hideDetails();
        });
        */

        var detailViewTimerID = 0;
        function viewDetails(el, url) {
            if ($("#popup_credit").hasClass('show'))
                return;

            clearTimeout(detailViewTimerID);
            detailViewTimerID = setTimeout(function() {
                $('#loading-gif').show();
                $("#popup_credit .content").html('');
                $.ajax({
                    type: "POST",
                    url: url,
                    cache: false,
                    success: function(data) {
                        $('#loading-gif').hide();
                        $("#popup_credit .content").html(data);
                        var p = jQuery(el).offset();
                        var tooltipWidth = 294;//$('#popup_credit').width();
                        var tooltipHeight = 248;//$('#popup_credit').height();
                        $('#popup_credit').css('position', 'absolute').css('z-index', 1100);

                        var topDistance = p.top - $(document).scrollTop();
                        var rightDistance = $(window).outerWidth() - (p.left - $(document).scrollLeft()) - jQuery(el).width();
                        var timeout = jQuery('#popup_credit').is(':visible') ? 300 : 0;

                        $('#popup_credit').animate({top: p.top - tooltipHeight, left: 500/*event.pageX*/}, timeout);

//                                if (topDistance >= tooltipHeight) {
//                                    if (rightDistance >= tooltipWidth)//правый верхний
//                                        $('#popup_credit').animate({top: p.top - tooltipHeight, left: p.left + jQuery(el).width()}, timeout);
//                                    else
//                                        $('#popup_credit').animate({top: p.top - tooltipHeight, left: p.left - tooltipWidth}, timeout);
//                                }
//                                else {
//                                    if (rightDistance >= tooltipWidth)
//                                        $('#popup_credit').animate({top: p.top + jQuery(el).height(), left: p.left + jQuery(el).width()}, timeout);
//                                    else
//                                        $('#popup_credit').animate({top: p.top + jQuery(el).height(), left: p.left - tooltipWidth}, timeout);
//                                }
                        $('#popup_credit').fadeIn(300);
                    }
                });
            }, 1);
        }
        function hideDetails() {
            if ($('#popup_credit').hasClass('show'))
                return;
            clearTimeout(detailViewTimerID);
            detailViewTimerID = setTimeout(function() {
                jQuery('#popup_credit').fadeOut(300);
            }, 700);
        }
        function takeCreditInvestClose(el) {
            $(el).parent().hide('slow');
            $('#popup_credit').removeClass('show').hide('slow');
        }

        function takeCreditInvest(el) {
            console.log('takeCreditInvest');
            $('.popup_debit').hide('slow');
            var id = $(el).parent().attr('data-id'),
                type = $(el).parent().attr('data-type'),
                button = $(el).parent().attr('data-button'),
                bonus = $(el).parent().attr('data-bonus'),
                garant = $(el).parent().attr('data-garant');

            var payment_account = $(el).parent().find('#payment_account option:selected').val();     
            //not allowed to take
            //$('.loading-gif').show();

            console.log(id,type,button,bonus, garant);

            if (type == 'credit')
                takeCredit(id, garant, payment_account);
            else
                takeInvest(id, garant, payment_account);

//            $('#popup_credit').removeClass('show').hide('slow');
        }
        function takeCredit( id, garant, payment_account) {
            console.log('takeCredit',id, garant, payment_account);
             if( security ){
                if( garant == true ){
                     $('#loader_window').show();
                    $.post(site_url + '/account/take_credit/' + id,  {payment_account: payment_account },  function(data) {
                        //$('.loading-gif').hide();
                        $('#loader_window').hide();
                        var wrap = $('#answer_contnet');

                        wrap.html( data.message );

                        $('#popup_credit').delay(4000).removeClass('show');
                    }, 'json');
                }else{
                    $('#popup_credit').delay(4000).removeClass('show');
                    
                    mn.security_module
                        .init()
                        .show_window('withdrawal_standart_credit')
                        .done(function(res){
                            var code = res['code'];

                            if( res['res'] != 'success' ) return false;
                            
                            mn.security_module.loader.show();

                            var uri = '/'+mn.site_lang+'/account/take_credit/' + id + '/'+code,
                                data = {code:code, payment_account: payment_account};
                                
                            $('#loader_window').show();        
                            mn.get_ajax( uri ,data, function(res) {
                                mn.security_module.loader.show( res.message, 5000 );
                                 $('#loader_window').hide();
                                
                            });
                        });
                }                
             }else{
                var post_data =  {};
                if ( $('#payment_account').val() )
                    post_data = {payment_account: $('#payment_account').val()};                 
                 $('#loader_window').show();
                $.post(site_url + '/account/take_credit/' + id, {payment_account: payment_account }, function(data) {
                    //$('.loading-gif').hide();

                    var wrap = $('#answer_contnet');
                     $('#loader_window').hide();

                    wrap.html(data.message );

                    $('#popup_credit').delay(4000).removeClass('show');
                }, 'json');
            }
        }
</script>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>


<div id="loader_window" class="popup_window small" style="z-index: 99999999">
    <center>
        <img id="loader-loading-gif" class="loader-loading-gif"  src="/images/loading.gif">
    </center>
</div>