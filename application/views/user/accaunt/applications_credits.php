<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="/js/datatable/jquery.dataTables.columnFilter.js"></script>

<style>
    .dataTables_filter { visibility: hidden; }
    .dataTables_info { visibility: hidden;  }
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
<? $this->load->view(  'user/accaunt/blocks/renderSocialWallpost', []); ?>


<div style="padding:10px;border:1px solid #eee;margin:10px auto;white-space:nowrap">
    <?=_e('Действительные заявки не сведенные системой в настоящее время.')?>
</div>

<div style="padding:10px;border:1px solid #eee;margin:10px auto;white-space:nowrap;">
    <?=_e('Лимит на p2р-переводы по Webtransfer VISA Card в сутки (получение-отправка): <br>  LEVEL 1 (Уровень 1) - 10 переводов, макс $500 за транзакцию, лимит карты $2,500<br> LEVEL 2  (Уровень 2) - 20 переводов, макс $1,000 за транзакцию, всего $20,000.')?>
</div>

<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>
<!---->

<center>
<iframe id='a3f0f07c' name='a3f0f07c' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=27&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=af9fbd4a&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=27&amp;cb={random}&amp;n=af9fbd4a&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</center>

<br/>

<div id="popup_credit" class="popup_window small">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="content" >
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif">
    </center>
</div>

<!--div class="search-buttons">
    <a href="#" class="search-buttons_button s" id="garant"><span><?=_e('Найти')?></span><img src="/images/garant_stat0.png" alt=""/></a>
    <a href="#" class="search-buttons_button g" id="standart"><span><?=_e('Найти')?></span><img src="/images/garant_stat1.png" alt=""/></a>
    <a href="#" class="search-buttons_button gd" id="garant-d"><span><?=_e('Найти')?></span><img src="/images/garant-direct.png" alt=""/></a>
    <a href="#" class="search-buttons_button sd" id="standart-d"><span><?=_e('Найти')?></span><img src="/images/standart-direct.png" alt=""/></a>
    <select id="search_select_box">
        <option value="all">Все</option>      
        <option value="BANK_CARD">Банковская карта</option>                        
        <option value="BANK_ACCOUNT">Банковский счет</option>                        
        <option value="EWALLET">Электронный кошелек</option>                        
        <option value="card">Webtransfer VISA</option>                        
    </select>


</div-->

<table class="table_results hover">
        <thead class="table_header">
            <tr>
                <th  data-orderable="true" data-searchable="true" data-data="id" class="table_cell"><?=_e('№');?></td>    
                <th data-orderable="true"  data-data="type" class="table_cell"><?=_e('Счет')?></td>    
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
    <h2><?=_e('Директ')?><?//=_e('accaunt/applications_11')?></h2>
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
</div>
<div class="popup_window popoup_debit_credit garant borrow" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_17')?></h2>
    <?=_e('accaunt/applications_18')?>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" style="width: 200px; padding: 5px;"><? 
                    $selected = '';
                    echo "<option data-type='none' value='-'$selected>-</option>";
                   // $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                   // echo "<option data-type='account' value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][2], TRUE, TRUE)."</option>";                             
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
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_19')?></button>

</div>

<? $this->load->view(  'user/accaunt/blocks/renderVisualDNA_window', compact($user_id)); ?>

<div class="popup_window popoup_debit_credit standart borrow" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_20')?></h2>
    <?=_e('accaunt/applications_21')?>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" style="width: 200px"><? 
                    $selected = '';
                    echo "<option value='-'$selected>-</option>";
                    //$selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                   // echo "<option value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                    
          ?></select>    
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_22')?></button>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>
<div class="popup_window popoup_debit_credit garant credit" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_23')?></h2>
    <?=_e('accaunt/applications_24')?>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" style="width: 200px"><? 
                    $selected = '';
                    echo "<option value='-'$selected>-</option>";
                 //   $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                   // echo "<option value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                    
          ?></select>    
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_25')?></button>
</div>

<div class="popup_window popoup_debit_credit standart credit" style="z-index:9999;">
    <div class="close" onclick="takeCreditInvestClose(this);"></div>
    <h2><?=_e('accaunt/applications_26')?></h2>
    <?=_e('accaunt/applications_27')?>
    <br><br><?=_e('Выберите счет: ') ?><br>
          <select id="payment_account" name="payment_account" style="width: 200px"><? 
                    $selected = '';
                    echo "<option value='-'$selected>-</option>";
                 //   $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                  //  echo "<option value='5'$selected>WTUSD1 - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][5], TRUE, TRUE)."</option>";
                    $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                    echo "<option value='2'$selected>WTUSD&#10084; - $ ".price_format_double($rating_by_bonus['payout_limit_by_bonus'][2], TRUE, TRUE)."</option>";                             
                    $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                    echo "<option data-type='account' value='6'$selected>WTDEBIT - $ ".price_format_double($rating_by_bonus['availiable_garant_by_bonus'][6], TRUE, TRUE)."</option>";                             
                    
                                
          ?></select>    
    <button class="button" type="submit" id="confirm_invest2" onclick="$(this).parent().hide('slow');
            takeCreditInvest(this);" name="submit" ><?=_e('accaunt/applications_28')?></button>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>

<script>
    
    function confirm_invest2_process(res)
    {                
        var wrongs = 0,
        id = $('#out_send_window').data('credit-id'),
        garant = $('#out_send_window').data('credit-garant'),
        code = 0;
        
        if( res === undefined && res['res'] == 'success' )
        {
            if( res['res'] === undefined || res['res'] != 'success' ) return false;
            code = res['code'];
        }
        mn.security_module.loader.show();
        
        var uri = '/'+mn.site_lang+'/account/take_credit/' + id + '/'+code,
            data = {code:code, payment_account: $('#payment_account').val() };
        
        mn.get_ajax( uri ,data, function(res) {
            var message = '';
            
            if( res['success'] ===undefined || res['error'] === undefined )
            {
                message = getMessage( res.state, 0, garant );
            }else 
                if( res['success'] !==undefined ) message = res['success'];
                else 
                    if( res['error'] !==undefined ) message = res['error'];
            
            mn.security_module.loader.show( message, 5000 );
            $('#popup_credit').delay(4000).removeClass('show');
        });
        
    }
    $(function(){

        $('#confirm_invest2').click(function(){

            var std = 0;
            $('#popup_credit').hide('slow');

            if( $("#standart").attr("checked") )
            {
                if( $(this).hasClass('active') ){
                    $('#popup_load').show('slow');
                    return true;
                }else{
                    
                    mn.security_module
                        .init()
                        .show_window('withdrawal_standart_credit')
                        .done(confirm_invest2_process);
                    
                    return false;
                }
            }else{
                $('#popup_load').show('slow');
                confirm_invest2_process();
                return true;
            }
            
        });


    });
   
</script>
<script type="text/javascript" src="/js/user/sms_module.js"></script>
<script>
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
                    "url": "<?=$url_credit?>",
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
                
                viewDetails($(this), site_url + '/account/ajax_user/get_user_data/2/' + data.id_user + '/' + data.id, event);
            }).on('mouseout', 'tr', function() {
                hideDetails();
            });
            */
            
            $(".table_results tbody").delegate("tr", "click", function() {
                var data = search_table.fnGetData( this );
                console.log(data);
                viewDetails($(this), site_url + '/account/ajax_user/get_user_data/2/' + data.id_user + '/' + data.id);
            });            


        });
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


        /*jQuery('#popup_credit').mouseover(function() {
            clearTimeout(detailViewTimerID);
        }).mouseout(function() {
            hideDetails();
        });*/

        var detailViewTimerID = 0;
        function viewDetails(el, url) {
            //if ($("#popup_credit").hasClass('show'))
              //  return;

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
                        var tooltipHeight = 248;//$('#popup_credit').height();
                        $('#popup_credit').css('position', 'absolute').css('z-index', 1100);

                        var timeout = jQuery('#popup_credit').is(':visible') ? 300 : 0;

                        $('#popup_credit').animate({top: p.top - tooltipHeight, left: 500/*event.pageX*/}, timeout);
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
        
            <?if ( $social_bonuses_today == 0) {?>
            if ( WT_Social_WallPost.showDialog(function(){ takeCreditInvest(el) } ) == true )
                return false;
             <? } ?>                    
        
            $('.popup_debit').hide('slow');
            var id = $(el).parent().attr('data-id'),
                type = $(el).parent().attr('data-type'),
                button = $(el).parent().attr('data-button'),
                garant = $(el).parent().attr('data-garant');
        
        
            console.log('takeCreditInvest',id,type,button,garant);
            
            console.log($(el));
            console.log($(el).parent());
            console.log($(el).parent().find('#payment_account'));
        
           var payment_account = $(el).parent().find('#payment_account option:selected').val();     

            //not allowed to take
            $('.loading-gif').show();

            if (type == 'credit')
                takeCredit(id, garant, payment_account);
            else
                takeInvest(id, garant, payment_account);

//            $('#popup_credit').removeClass('show').hide('slow');
        }
        function takeInvest(id, garant, payment_account) {
           
            var uri = '/'+mn.site_lang+'/account/take_invest/' + id,
                data = { payment_account: payment_account, post_vk: WT_Social_WallPost.post_vk, post_fb: WT_Social_WallPost.post_fb };

           $('#loader_window').show();

            mn.get_ajax( uri ,data, 
            function(res) {
                WT_Social_WallPost.reinit();
                mn.security_module.loader.show( res.message, 5000 );
                $('#popup_credit').removeClass('show');
                $('#loader_window').hide();

    <?
    (!empty($curSocial["vk"]["access_token"]) ? "postMsgWall('<?=_e('accaunt/applications_62')?> " .
            "<?=_e('accaunt/applications_63')?> " .
            base_url() . "');" : "")
    ?>

                
            });
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



