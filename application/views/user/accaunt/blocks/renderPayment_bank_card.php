<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<script>
   function countries_onChange(){
       var country = $('#bank_card_payment select[name=country]').val();
       $('#bank_card_payment select[name=bank] option').remove();
       if ( country == '-')
            return;
       $('#bank_card_payment select[name=bank]').append( new Option('<?=_e('Загрузка...')?>','-') );
        $.post(site_url +  "/account/ajax_user/get_card_banks", { country: country },  function(data){ 
                    _loadCombobox('#bank_card_payment select[name=bank]', data);
          }, "json");         
       
       
   }


</script>


<style>
    #bank_card_payment select {
        border: 1px solid #999999;
        padding: 0px;
        margin-left: 3px;
        color: #999999;
        width: 90%;
        height: 35px;
        text-align: center;
    }
</style>

<div class="popup lava_payment" id="bank_card_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Пополнение через Банковский перевод')?></h6>
        </div>
       <form  class="form" >
            <div class="formRow">
                <label style="padding-top:10px"><?=_e('Пополнить')?>:</label>
                <div class="formRight">
                    <select name="card" onchange="on_payment_account_change(this)" style="padding: 5px;">
                     <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                        <option value="<?=$card->id?>"><?=Card_model::display_card_name($card)?></option>
                     <? } else  { ?>
                        <option value="card_create_virtual"><?=_e('Заказать  Webtransfer VISA Virtual')?></option>
                        <option value="card_create_plastic"><?=_e('Заказать  Webtransfer VISA Black')?></option>
                      <? } ?>
                       <!--  <option value="0"><?=_e('Webtransfer Debit Card')?> </option> -->
                    </select>                    
                </div>
            </div>
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('block/data6')?></label>
                <div class="formRight">
                    <input  type="text" class="maskMoneyCustom" name="summa" style="height:23px;" value="<?= (!empty($_SESSION['invest_summa'])) ? $_SESSION['invest_summ'] : "" ?>"/>
                    <div class="error" style="display:none" ><?=_e('block/data7')?></div>
                    <div class="error1" style="display:none" ><?=_e("Минимум $100 000")?></div>
                </div>
            </div>
            <div class="formRow">
                <label style="padding-top:10px"><?=_e('Страна')?>: </label>
                <div class="formRight">
                    <select name="country" id="country" onChange="countries_onChange()">
                        <option value="-">-</option>
                        <? foreach ($countries_fb as $country) { ?>
                        <option value="<?=$country->iso2?>"><?=_e($country->name)?></option>
                        <? } ?>
                    </select>
                </div>
            </div>
            <div class="formRow">
                <label style="padding-top:10px"><?=_e('Банк')?>: </label>
                <div class="formRight">
                    <select name="bank" id="bank">
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <center>
        <button class="button" onclick="return false;" onclick=" return  false;" id="print_check" type="submit" name="submit" value=""><?=_e('block/data11')?></button>
        <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
        <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
    </center>
</div>
<div class="popup_window" id="order_bank_card_print" style="padding: 0px; margin-right: -200px; width: 495px; z-index: 9999">
    <div onclick="$('.popup_window').hide('slow'); window.location.reload();" class="close"></div>
    <h2><?=_e('Счет на оплату')?></h2>

    <button class="button" onclick="return false;" id="card_check_print"><?=_e('block/data13')?></button>
</div>
        

<script>
    $('#bank_card_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
            $("#bank_card_payment").fadeIn();
        });
    }).css('cursor', 'pointer');


    $('#bank_card_payment #print_check').click(function(){
        $('p.error').hide();
        //if(!cahckSumm("bank_payment", 'input[name="summa"]'))
          //  return false;
        var summ = parseInt($('#bank_card_payment input[name="summa"]').val());
        var bank = $('#bank_card_payment select[name=bank]').find(":selected").val();
        var country = $('#bank_card_payment select[name=country]').find(":selected").val();
        var card = $('#bank_card_payment select[name=card]').find(":selected").val();
        


        if ( country === undefined ||  country == '-'){
            alert("<?=_e('Вы не выбрали страну')?>");
            return;
        }
        
        if ( country === undefined ||  bank == '-'){
            alert("<?=_e('Вы не выбрали банк')?>");
            return;
        }

        /*if (100000 > s) {
            $('#bank_card_payment .error1').show();
            return false;
        }*/

       
        
        $('.loading-gif').show();
        $.post(site_url + '/account/pay_to_card', {
            summa: summ,
            method: 'FastBankTransfer',
            bank: bank,
            country: country,
            card: card
        },
        function(data) {
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
            data.id = $.trim(data.id);
            if (data.checkLink != "") {
                $('.loading-gif').hide();
                $('#card_check_print').attr('rel', data.checkLink);
                $('#bank_card_payment').hide();
                $('#order_bank_card_print').show('slow');
            } else {
                $('.loading-gif').hide();
                $('p.error').show();
            }
        }, 'json');
    });
    $('#card_check_print').click(function() {
        $('#order_bank_card_print').hide('slow');
        window.open( '<?=base_url()?>' + $(this).attr('rel'), '_blank');
        $("#secondary-nav a[href*=payment]").trigger('click');
    });
</script>