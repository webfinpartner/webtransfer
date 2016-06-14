<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
$del_mes = "<br/><a onclick='return confirm(\""._e('Действительно отменить?')."\")' href = '" . site_url('account/delPayout') . "/{{item_id}}'>"._e('accaunt/transaction_9')."</a>";
if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>
<?}?>
<div id="form_type">
	<a href="#" onclick="$('.excel_export').show(); return  false;"><img src="/images/excel.png" style="vertical-align : middle;" /></a>
</div>

<style>
    #buttons{
        display: block;
        margin: 10px 0 20px;
        overflow: auto;
    }

    #buttons .button1{
        float: right;
    }

    #ui-datepicker-div{
        z-index: 17 !important;
    }

    .bank_details td {
        padding-left: 0px;
    }

    #otherCardForm  {
       width: 520px !important;
    }
    

   
</style>





<div class="table">







    <div class="popup_window card_digits" style="z-index:9999">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
   <?=_e('Первые и последние 4 цифры банковской карты')?>
</div>



	<div class="rower bg" style="margin-top: 10px">
            <a href="#" class="show_payment_details_link"><div class="td1"><IMG src="/images/wallet.png" style="vertical-align:middle;"> <?=_e('Мои счета')?></div></a>
		<div class="td2"><a href="#" onclick="showOtherCardForm();return false;"><?=_e('Добавить счет')?></a></div>
	</div>
	<? if (empty($wtcards)){ ?>
	         <div class="rower" style="padding-top:10px;">
                   <div class="td1" style="padding: 0px 10px 10px 7px;">
                        <div style="width:35px;display:inline-block;"><? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<IMG src="/images/vcard-sm.png" style="vertical-align:middle">'; else echo '<IMG src="/images/vcard-sm.png" style="vertical-align:middle;">'?></div>
                        <a href="<?=site_url('account/profile')?>"><?=_e('Webtransfer VISA Card')?> </a></div>
                    <div class="td2"><a href="<?=site_url('account/profile')?>"><?=_e('Активировать')?></a>
					</div>
                </div>


	   <? } else { ?>
            <? if (count($wtcards)==1) {
                $card = $wtcards[0];
                ?>

                <div class="rower" style="padding-bottom:0px;padding-top:10px">
                <div class="td1" style="padding: 0px 10px 10px 7px;">
                        <div style="width:35px;display:inline-block;"><? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<IMG src="/images/vcard-sm.png" style="vertical-align:middle">'; else echo '<IMG src="/images/pcard-sm.png" style="vertical-align:middle;">'?></div>
                        <a href="<?=site_url('account/card-lst')?>"><?=_e('Webtransfer VISA Card')?></a></div>
                    <div class="td2"><span class="card_balance" data-card_id="<?=$card->id?>"><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></span>
					<span class="rtyd"  style="margin-left: 4px;position: absolute;margin-top: -18px;font-weight: bold;"><a href="<?= site_url('account/payment') ?>" class="simptip-position-top" data-tooltip="<?=_e('Пополнить')?>" >+</a></span>
					</div>
                </div>


            <? } else { ?>

         <div class="rower" style="padding-bottom: 0px;padding-top:10px;">
              <? foreach( $wtcards as $card ){ ?>
		<div class="td1" style="padding: 0px 10px 10px 7px;">
                <div style="width:35px;display:inline-block;"><? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<IMG src="/images/vcard-sm.png" style="vertical-align:middle">'; else echo '<IMG src="/images/pcard-sm.png" style="vertical-align:middle;">'?></div>
                 <div style="display:inline-block;width:300px;font-size:14px;"><a href="<?=site_url('account/card-lst')?>">4665 **** **** <?=substr($card->pan,-4)?></a></div></div>
            <div class="td2">$ <span class="card_balance" data-card_id="<?=$card->id?>"><img id='balance_loading-gif' style="display: none" src="/images/loading.gif" height="16" width="16" /></span></div>
                <? } ?>
        </div>
            <? } ?>
        <? } ?>
		     	<? if ( $accaunt_header['payment_account_by_bonus'][6]  != 0 ){ ?>
		<div class="rower" style="display: block;padding-bottom: 0px;padding-top:0px;">
		<div class="td1" style="padding: 0px 10px 10px 7px;"><img src="/images/debit.png" style="vertical-align:middle;">   Webtransfer DEBIT Card </div>  
		<div class="td2">$ <?=price_format_double($accaunt_header['payment_account_by_bonus'][6])?> </div>

	</div>
<? } ?>
	<? if (!empty($othercards)){ ?>
        <div class="rower" id="card_other_details" style="display: block;padding-top:0px;padding-bottom:0px;">
              <? foreach( $othercards as $card ){ ?>
		<div class="td1" style="padding: 0px 10px 10px;">
                    <!--div style="width:35px;display:inline-block;"><IMG src="/images/vcard-sm.png" style="vertical-align:middle"></div-->
                    <div style="display:inline-block;width:400px;font-size:14px;">
                            <?if($card->account_type == 'BANK_CARD'){
                             echo '<img src="/images/'.$card->account_extra_data.'.png" style="vertical-align:middle"> ';
                            //echo '<img src="/images/'.$card->account_type.'.png">';
                            $nums = explode('-',$card->bank_card_num);
                            if (count($nums)==2)
                                echo " ".$nums[0]." **** **** ".$nums[1];
                            elseif(count($nums)==4)
                                echo " ".$nums[0]." **** **** ".$nums[4];


                            }
                            elseif($card->account_type == 'E_WALLET'){
                                echo '';
                               if(!empty($card->own_wallet)){
                                    echo '<img src="/images/'.$card->account_type.'.png"  style="vertical-align:middle"> ';
                                    echo $card->own_wallet;
                                } else {
                                    echo '<img src="/images/'.$card->account_extra_data.'.png" style="vertical-align:middle"> ';
                                     echo $card->account_extra_data;

                                  }
                                }
                            else {
                                echo '<img src="/images/'.$card->account_type.'.png" style="vertical-align:middle"> ';
                             } ?>
                                &nbsp;<?=$card->name?>

                    </div>
                    <span><a href="#" onclick="showOtherCardForm(<?=$card->id?>);return false;"><img src="/images/edit.png" height="11" alt="<?=_e('Редактировать')?>"></a></span>
                    <span><a href="<?=site_url('account/card-removeOther').'/'.$card->id?>" onclick="return on_delete(this);"><img height="11" src="/images/delete.png" alt="<?=_e('Удалить')?>"></a></span>
                </div>
            <div class="td2"><span>$ <?=price_format_double($card->summa)?></span></div>
                <? } ?>
        </div>
        <? } ?>

		          <? if ( $accaunt_header['payment_account_by_bonus'][2]  > 0 ){ ?>
	 <div class="rower" style="padding-bottom: 0px;padding-top:0px;">
                <div class="td1" style="padding: 0px 10px 10px;"><img src="/images/wt-heart.png" style="vertical-align:middle;">   Webtransfer USD<span style="color:red;">&#10084;</span> <?if (!isUserUSorCA()){?><!-- a href="#" onclick="$('#conv2').show(); return false;" style="font-size:11px;">(<?=_e('Конвертация')?>)</a--><?}?>  </div>
		<div class="td2">$ <?=price_format_double($accaunt_header['payment_account_by_bonus'][2])?>
		</div>
	</div>
        <? } ?>


        <? if(!empty($accaunt_header['bonuses'])) { ?>
	 <div class="rower" style="padding-bottom: 0px;padding-top:0px;">
		<div class="td1" style="padding: 0px 10px 10px 6px;"><img src="/images/bonus1.png" style="vertical-align:middle;">   Webtransfer BONUS Card </div>
		<div class="td2">$ <?= price_format_double($accaunt_header['bonuses']); ?> <span  style="margin-left: 4px;position: absolute;margin-top: -17px;font-weight: bold;"><a href="<?= site_url('account/my_invest') ?>" class="simptip-position-top" data-tooltip="<?=_e('blocks/secondary_profile_2')?>" >+</a></span></div>
	</div>
        <? } ?>
<? } ?>

 <? if ( ($accaunt_header['c_creds_total'] + $accaunt_header['c_creds_amount_invest'] ) > 0.01 ){ ?>
        <div class="rower" style="padding-bottom: 0px;padding-top:0px;">
		<div class="td1" style="padding: 0px 10px 10px 12px;"><img src="/images/wt-creds-card.png" style="vertical-align:middle;">   CREDS (Cash) </div>
		<div class="td2">$ <?= price_format_double($accaunt_header['c_creds_total'] + $accaunt_header['c_creds_amount_invest']); ?></div>
	</div>
        <? } ?>
        <? if ( $accaunt_header['partner_funds']  > 0.01 ){ ?>
        <div class="rower" style="padding-bottom: 0px;padding-top:0px;">
                <div class="td1" style="padding: 0px 10px 10px 12px;"><img src="/images/wt-pcreds-card.png" style="vertical-align:middle;"> <?=_e('Партнерский счет:')?> <?if (!isUserUSorCA()){?>

		<? } ?></div>
		<div class="td2">$ <?= price_format_double($accaunt_header['partner_funds']); ?> </div>
	</div>
        <? } ?>



	<div class="rower bold" style="border-top:1px dotted #000;font-weight:bold;">
		<div class="td1"><?=_e('accaunt/transaction_3')?></div>
		<div class="td2" id="total_summ">$ <?= price_format_double( $gift_summa + $accaunt_header['payment_account']- $accaunt_header['payment_account_by_bonus'][5]+$accaunt_header['bonuses'] + $accaunt_header['partner_funds'] + $accaunt_header['c_creds_total'] + $accaunt_header['total_other_cards_balance']); ?></div>
	</div>



</div>
<!--OWN ACCOUNT FORM-->
<style>
    .formRight select {
        border: 1px solid #999999;
        padding: 0px;
        margin-left: 3px;
        color: #999999;
        width: 90%;
        height: 35px;
        text-align: center;
    }
</style>

<div class="popup lava_payment" id="otherCardForm">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Добавление счета')?></h6>
        </div>
        <form method="post" onsubmit="return on_submit(this);" action="<?=site_url('account/card-saveOther')?>">
            <input type="hidden" name="id" value="">
            <input type="hidden" name="code" value="">
            <input type="hidden" name="purpose" value="">
            <div class="formRow">
                <label style="padding-top:10px"><?=_e('Тип')?>:</label>
                <div class="formRight">
                    <select name="type" onchange="on_account_type_change()">
                        <option value=""><?=_e('Выберите тип счета')?> </option>
                        <option value="BANK_CARD"><?=_e('Банковская карта')?> </option>
                        <option value="BANK_ACCOUNT"><?=_e('Банковский счет')?> </option>
                        <option value="E_WALLET"><?=_e('Электронный кошелек')?> </option>
                    </select>
                </div>
            </div>
            <div class="formRow" id="BANK_CARD_AREA" style="display: none;">
                <label style="padding-top:10px"><?=_e('Карта')?>: </label>
                <div class="formRight">
                    <select name="BANK_CARD_account_extra_data">
                            <option value="">-</option>
                            <option value="VISA">VISA</option>
                            <option value="MasterCard">Master Card</option>
                            <option value="Amex">Amex</option>
                    </select>
                    <br><br>
                    <input name="BANK_CARD_NUM[]" id="BANK_CARD_NUM1"   onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4"  min = "1" max = "9999"  type="number" style="width: 50px;height:28px;margin-left:3px;">
                    <input name="BANK_CARD_NUM[]" id="BANK_CARD_NUM2" type="number"   onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4"  min = "1" max = "9999" style="width: 50px;height:28px;">
                    <input name="BANK_CARD_NUM[]" id="BANK_CARD_NUM3" type="number"   onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4"  min = "1" max = "9999" style="width: 50px;height:28px;">
                    <input name="BANK_CARD_NUM[]" id="BANK_CARD_NUM4" type="number"   onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength = "4"  min = "1" max = "9999" style="width: 50px;height:28px;">
                </div>
            </div>

            <div class="formRow" id="E_WALLET_AREA" style="display: none;">
                <label style="padding-top:10px"><?=_e('Кошелек')?>: </label>
                <div class="formRight">
                    <select name="E_WALLET_account_extra_data" onchange="on_wallet_change()">
                            <option value="">-</option>
							<option value="Paypal">Paypal</option>
							 <option value="Skrill">Skrill</option>
							 <option value="Alipay">Alipay</option>
							 <option value="Google">Google Wallet</option>
							 <option value="Okpay">Okpay</option>
							 <option value="Qiwi">Qiwi</option>
                                                         <option value="Webmoney">Webmoney</option>
                                                         <option value="Yandex">Yandex</option>
							 <option value="Liqpay">Liqpay</option>
							 <option value="Perfectmoney">Perfect Money</option>
                                                         <option value="Payeer">Payeer</option>
							 <option value="Other"><?=_e('Добавить свой кошелек')?></option>
                    </select>

                </div>
            </div>

            <div class="formRow area_ewallet_own_name" style="display: none;">
                <label style="padding-top:20px"><?=_e('Название')?>: </label>
                <div class="formRight">
                    <input  type="text" name="ewallet_own_name" style="height:32px;width:220px;" value=""/>
                </div>
            </div>

            <div class="formRow BANK_CARD_area_name" style="display: none;">
                <label style="padding-top:20px"><?=_e('Банк')?>: </label>
                <div class="formRight">
                    <input  type="text" name="BANK_CARD_name" style="height:32px;width:220px;" value=""/>
                </div>
            </div>

            <div class="formRow BANK_ACCOUNT_area_name" style="display: none;">
                    <table class="bank_details">
                        <tr>
                            <td style="width: 33%"><?=_e('Банк')?>: <input  type="text" name="BANK_ACCOUNT_name" style="height:20px;width:110px;" value=""/></td>
                            <td style="width: 33%"><?=_e('Получатель')?>: <input  type="text" name="wire_beneficiary_name" style="height:20px;width:110px;" value=""/></td>
                            <td style="width: 33%"><?=_e('Страна банка')?>:
                                <select name="wire_beneficiary_bank_country">
                                    <option value="">-</option>
                                    <? foreach ($countries as $country) { ?>
                                        <option value="<?=$country->iso2?>"><?=_e($country->name)?></option>
                                    <? } ?>
                                </select>
                        </tr>
                        <tr>
                            <td>Sort code/ABA: <input  type="text" name="wire_sort" style="height:20px;width:110px;" value=""/></td>
                            <td><?=_e('Счет получателя / IBAN')?>: <input  type="text" name="wire_beneficiary_account" style="height:20px;width:110px;" value=""/></td>
                            <td>SWIFT: <input  type="text" name="wire_beneficiary_swift" style="height:20px;width:110px;" value=""/></td>
                        </tr>
                        <tr>
                            <td><?=_e('Банк-корреспондент')?><br/>(НОСТРО USD)<input  type="text" name="wire_corresponding_bank" style="height:20px;width:110px;" value=""/></td>
                            <td><?=_e('SWIFT банка-корреспондента')?> <input  type="text" name="wire_corresponding_bank_swift" style="height:20px;width:110px;" value=""/></td>
                            <td><?=_e('Счет в банке-корреспонденте')?> <input  type="text" name="wire_corresponding_account" style="height:20px;width:110px;" value=""/></td>
                        </tr>


                    </table>
            </div>



            <div class="formRow area_account" style="display: none;">
                <label style="padding-top:20px"><?=_e('Счет')?>: </label>
                <div class="formRight">
                    <input  type="text" name="account_personal_num" style="height:32px;width:220px;" value=""/>
                </div>
            </div>


            <div class="formRow area_summa"  style="display: none;">
                <label style="padding-top:10px"><?=_e('Сумма в')?> USD: </label>
                <div class="formRight">
                    <input  type="number" name="summa" style="height:32px;width:237px;" value=""/>
                </div>
            </div>
    </div>
    <center>
        <button class="button" type="submit" name="submitted" ><?=_e('Добавить')?></button>
        <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
        <p class="center error" id="form_error" style="display: none;"></p>
    </center>
    </form>
</div>

<!--div class="popup_window borrow" id="otherCardForm" style="z-index:9999;">
     <div class="close"></div>
    <h2><?=_e('Добавление карты')?></h2>
    <form method="post" action="<?=site_url('account/card-saveOther')?>">
        <input type="hidden" name="card_id" value="">
        <div class="widget">
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('Название')?>:</label>
             <div class="formRight">
                <input type="text" required id='card_name' name="card_name" value="" placeholder="<?=_e('Название')?>">
            </div>
            </div>
            <div class="formRow">
            <input type="number" required id='summa' name="summa" value="" placeholder="<?=_e('Сумма')?>">
            </div>
        </div>

    <button class="button" type="submit" onclick="$(this).parent().validate()"  name="submit" ><?=_e('Добавить')?></button>
    <img id='otherCardForm-loading-gif' style="display: none" src="/images/loading.gif"/>
    <span id="error"></span>
    </form>
</div-->

<!-- Календарь -->
<script type="text/javascript">
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength)
              object.value = object.value.slice(0, object.maxLength)
          }
	$(function() {

            /*$("#otherCardForm form").validationEngine('attach',{onValidationComplete: function(form, status) {
                if (status == true)
                   $('#otherCardForm-loading-gif').show();
            },
            scroll: false
            });*/
            $.datepicker.setDefaults($.extend(
		  $.datepicker.regional["ru"])
		);

	$("#date_from").datepicker({
		maxDate: "+0"
	});

	$("#date_last").datepicker({
		maxDate: "+0"
	});

	$("#date_1").datepicker({
		maxDate: "+0"
	});

	$("#date_2").datepicker({
		maxDate: "+0"
	});



});
function on_submit(form){
    console.log( "on_submit" );
    var $this = $(form);

    $('#form_error').hide();
    if (   parseFloat( $("#otherCardForm input[name=summa]").val() ) % 50 > 0  )
    {
        $('#form_error').html('<?=_e('Сумма должна быть кратной 50')?>');
        $('#form_error').show();
        return false;
    }
    console.log( "submit " + $this.find('[name=code]').val());
    if ( $this.find('[name=code]').val() == ''){
        mn.security_module
            .init()
            .show_window('withdrawal_standart_credit')
            .done(function(res){
                if( res['res'] != 'success' ) return false;
                var code = res['code'];
                $this.find('[name=code]').val( code );
                $this.find('[name=purpose]').val(  mn.security_module.purpose );
                 $("#otherCardForm .loading-gif").show();
                window.setTimeout(function(){  $this.submit() }, 400);
                console.log("submit submit");
            });
            return false;
        }
          console.log("submit return true");
    return true;


    //$(this).parent().validate();

}


function on_delete(object){
    if ( !window.confirm('<?=_e('Вы уверены что хотите удалить счет?')?>')  )
        return false;

    var url = $(object).attr('href');
      mn.security_module
            .init()
            .show_window('withdrawal_standart_credit')
            .done(function(res){
                if( res['res'] != 'success' ) return false;
                var code = res['code'];
               location.replace(url+"?code="+code+"&purpose="+mn.security_module.purpose);
            });
    return false;
}

function on_wallet_change(){

    var v = $("#otherCardForm select[name=E_WALLET_account_extra_data] option:selected").val();
    if ( v == 'Other')
         $('.area_ewallet_own_name').show();
    else
         $('.area_ewallet_own_name').hide();

}

function check_otherForm(){
    var enabled = ( $("#otherCardForm input[name=name]").val() != '' &&
         $("#otherCardForm input[name=summa]").val() != '' &&
         $("#otherCardForm select[name=type] option:selected").val()  != '' &&
         $("#otherCardForm select[name=type] option:selected").val()  != '' &&
         $("#otherCardForm select[name="+$("#otherCardForm select[name=type] option:selected").val()+"_account_extra_data] option:selected").val() != ''
      );

      if ( enabled )
          $("#otherCardForm button").removeAttr('disabled');
      else
          $("#otherCardForm button").attr('disabled', 'disabled');

}

function on_account_type_change(){
    var type = $("#otherCardForm select[name=type] option:selected").val();
    $('.BANK_CARD_area_name').hide();
    $('.BANK_ACCOUNT_area_name').hide();

    if (type != ''){
        $('.area_summa').show();
        if ( type == 'E_WALLET'){
            $('.area_name').hide();
            $('.area_account').show();
        }
        else {
            $('.area_name').show();
            $('.'+type+'_area_name').show();
            $('.area_account').hide();
        }
    } else {
        $('.area_name').hide();
        $('.area_summa').hide();
    }
    $('[id*=_AREA]').hide();
    $('#'+type+'_AREA').show();
    //check_otherForm();
}


function show_payment_account_details(){

    $('#pa_details').toggle();

}

function showOtherCardForm(id){

       $("#otherCardForm input").val('');
       $("#otherCardForm select option[value='']").attr('selected','selected');
        on_account_type_change();

       if ( id !== undefined ){
         $.ajax({
            type: "POST",
            url: "<?=site_url('account/card-getOther').'/'?>"+id,
            data: {resultType:"ajax"},
            success: function(data) {
                $("#otherCardForm input[name=id]").val( data.id );
                $('.area_common').show();
                $("#otherCardForm input[name=name]").val( data.name );
                $("#otherCardForm input[name=summa]").val( data.summa );
                $("#otherCardForm input[name=account_personal_num]").val( data.account_personal_num );
                if ( data.account_type == 'BANK_CARD' && data.bank_card_num != '' && data.bank_card_num != null){
                    var nums = data.bank_card_num.split('-');
                    if (nums.length == 2){
                        $('#BANK_CARD_NUM1').val(nums[0]);
                        $('#BANK_CARD_NUM2').val();
                        $('#BANK_CARD_NUM3').val();
                        $('#BANK_CARD_NUM4').val(nums[1]);
                    } else if (nums.length == 4){
                        $('#BANK_CARD_NUM1').val(nums[0]);
                        $('#BANK_CARD_NUM2').val(nums[1]);
                        $('#BANK_CARD_NUM3').val(nums[2]);
                        $('#BANK_CARD_NUM4').val(nums[3]);
                    }

                }



                $("#otherCardForm select[name=type] option[value="+data.account_type+"]").attr('selected','selected');
                on_account_type_change();

                if (  data.account_type == 'BANK_ACCOUNT'){
                    if ( data.account_extra_data != null)
                     for(var k in data.account_extra_data)
                              $('#otherCardForm [name='+k+']').val( data.account_extra_data[k] );
                } else
                    $("#otherCardForm select[name="+data.account_type+"_account_extra_data] option[value="+data.account_extra_data+"]").attr('selected','selected');

                 on_wallet_change();
                 if ( data.account_type == 'E_WALLET' && data.own_wallet != '' && data.own_wallet != null)
                      $("#otherCardForm input[name=ewallet_own_name]").val( data.own_wallet );

                $("#otherCardForm h6").html("<?=_e('Редактирование счета')?>");
                $("#otherCardForm button").html("<?=_e('Сохранить')?>");

                $("#otherCardForm").show();
            },
            dataType: 'json'
        });

       } else {
            $("#otherCardForm h6").html("<?=_e('Добавление счета')?>");
            $("#otherCardForm button").html("<?=_e('Добавить')?>");

            $("#otherCardForm").show();
       }
}

$(function(){
    var summ = <?=($accaunt_header['payment_account']+$accaunt_header['bonuses'] + $accaunt_header['partner_funds'] + $accaunt_header['c_creds_total'] + $accaunt_header['total_other_cards_balance'])?>;
    $('.card_balance').each(function (i) {
	console.log("get_card_balance " + i);
       var $this = $(this);
       var card_id = $this.data('card_id');
       $this.find('#balance_loading-gif').show();
         $.post(site_url +  "/account/ajax_user/get_card_balance", { card_id: card_id, useCache: 0},  function(data){
                $this.find('#balance_loading-gif').hide();
                $this.html(data.balance);
                if ( data.balance != '-'){
                    summ += parseFloat(data.balance.replace(' ', '') );
                    $('#total_summ').html(  summ.format(2,3,' ','.')  );
                }

        }, "json");


    });

});

Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

</script>
<!-- Календарь -->

<!--popup export modal dialog-->
<?php $this->load->view('user/accaunt/currency_exchange/blocks/modal_export_excel.php'); ?>
<!--popup-->

<? if (!empty($payout)) { ?>
<h3 onclick="$('#t_payout').toggle('slow');$('#t_payment').hide('slow');$('#show_payoit').toggle('slow');" style="cursor: pointer; text-decoration: underline;"><center><?=_e('accaunt/transaction_4')?><span id="show_payoit">...</span></center></h3>
<div id="t_payout" style="display: none;">
    <table cellspacing="0" class="payment_table">
            <thead>
                    <tr>
                            <th><?=_e('accaunt/transaction_5')?></th>
                            <th><?=_e('accaunt/transaction_6')?></th>
                            <th><?=_e('accaunt/transaction_7')?></th>
                            <th><?=_e('accaunt/transaction_8')?></th>
                    </tr>
            </thead>
            <tbody>
                    <? foreach ($payout as $item) { ?>
                            <tr>
                                    <td style='text-align:center;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;width:120px;'><?= date_formate_view($item->date) ?></td>
                                    <td style='padding-left:10px;padding-right:10px;width:auto;border-right:1px dotted #ccc; border-bottom:1px dotted #ccc;'><?= $item->note ?><br/><span style="font-size:11px;color:#858585;"><?= $item->id ?> // <?= _e(getTransactionsMethodLabel($item->metod, TRUE)) ?></span></td>
                                    <td style='text-align:right;width:80px;padding-right:15px;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;'>$ <?= price_format_double($item->summa) ?></td>

                                    <td style='text-align:center;'><?= _e(getTransactionLabelStatus($item->status)) ?>
                                            <? if ((in_array($item->metod, array_flip(config_item("payment_bank_shablon")))) and Base_model::TRANSACTION_STATUS_DELETED != $item->status) { ?> <? } ?>
                                            <? if ("out" == $item->metod && (Base_model ::TRANSACTION_STATUS_IN_PROCESS == $item->status || 10 == $item->status || Base_model::TRANSACTION_STATUS_NOT_RECEIVED== $item->status) and (310 != $item->type && 323 != $item->type)) echo str_replace ("{{item_id}}", $item->id, $del_mes);?>

                                    </td>
                            </tr>
                    <? } ?>
            </tbody>
    </table>

    <?= $pages_payout; ?>
<br/>
<br/>
</div>
<br/>
<? } ?>
<? if (!empty($payment)) { ?>
<h3 onclick="$('#t_payment').toggle('slow');$('#t_payout').hide('slow');$('#show_payment').toggle('slow');" style="cursor: pointer; text-decoration: underline;">
    <center><?=_e('accaunt/transaction_18')?><span id="show_payment">...</span></center>
</h3>
<div id="t_payment" style="display: none;">
    <table cellspacing="0" class="payment_table">
            <thead>
                    <tr>
                            <th><?=_e('accaunt/transaction_5')?></th>
                            <th><?=_e('accaunt/transaction_6')?></th>
                            <th><?=_e('accaunt/transaction_7')?></th>
                            <th><?=_e('accaunt/transaction_8')?></th>
                    </tr>
            </thead>
            <tbody>
                    <? foreach ($payment as $item) { ?>
                            <tr>
                                    <td style='text-align:center;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;width:120px;'><?= date_formate_view($item->date) ?></td>
                                    <td style='padding-left:10px;padding-right:10px;width:auto;border-right:1px dotted #ccc; border-bottom:1px dotted #ccc;'><?= $item->note ?><br/><span style="font-size:11px;color:#858585;"><?= $item->id ?> // <?= _e(getTransactionsMethodLabel($item->metod, TRUE)) ?></span></td>
                                    <td style='text-align:right;width:80px;padding-right:15px;border-right:1px dotted #ccc;border-bottom:1px dotted #ccc;'>$ <?= price_format_double($item->summa) ?></td>

                                    <td style='text-align:center;'><?= _e(getTransactionLabelStatus($item->status)) ?>
                                            <?if ( $item->metod == 'bank' && $item->type==327 ){
                                                    $id_hash = md5($item->id.'_'.$item->id_user); ?>
                                                    <a href="/upload/checks/cardcheck-<?=$id_hash?>.pdf" target="_blank"><img src="/images/PDF-Ripper.gif" style="vertical-align : middle;" /></a>
                                             <? } ?>

                                            <? if ((in_array($item->metod, array_flip(config_item("payment_bank_shablon")))) and Base_model::TRANSACTION_STATUS_DELETED != $item->status) { ?> <? } ?>
                                            <? if ((in_array($item->metod, array_flip(config_item("payment_bank_shablon")))) and Base_model::TRANSACTION_STATUS_NOT_RECEIVED == $item->status) echo str_replace ("{{item_id}}", $item->id, $del_mes);?>
                                            <? if (75 == $item->type and Base_model::TRANSACTION_STATUS_NOT_RECEIVED == $item->status) echo "<br/><a onclick='$(\"#send_form_user\").attr(\"action\",\"".site_url('account/confermSendMoney')."/$item->id\"); $(\"#sendmoney\").show(); return false;' href = '' style='white-space:nowrap;padding:0px 3px;'>Ввести код</a>";?>
                                            <? if ($item->type  == 24 && $item->status == Base_model::TRANSACTION_STATUS_NOT_RECEIVED && $item->bonus == 7 ) { ?>
                                                    <br/><a onclick='$("#recive_waiting_invest_dialog").data("id", <?=$item->id?>); $("#recive_waiting_invest_dialog").show(); return false;' href="" style='white-space:nowrap;padding:0px 3px;'><?=_e('Получить на карту')?></a>
                                            <? } ?>
                                            <? if ($item->type  == 16 && $item->metod=='wtcard' && $item->status == Base_model::TRANSACTION_STATUS_NOT_RECEIVED && $item->bonus == 7 ) { ?>
                                                    <br/><a onclick='$("#recive_waiting_invest_dialog").data("id", <?=$item->id?>); $("#recive_waiting_invest_dialog").show(); return false;' href="" style='white-space:nowrap;padding:0px 3px;'><?=_e('Получить на карту')?></a>
                                            <? } ?>
                                                    

                                    </td>
                            </tr>
                    <? } ?>
            </tbody>
    </table>

    <?= $pages_payment; ?>
<br/>
<br/>
</div>
<br/>
<? get_instance()->load->view('user/accaunt/blocks/renderCodeProtection_window.php'); ?>
<? } ?>

<form method="post" id="filter" style="height:30px;">
		<input type="text" name="date_1" id="date_from" value="<?=$date['first'][0]?>" style="width:70px;" />
		-     <input type="text" name="date_2" id="date_last"  style="width:70px;" value="<?=$date['last'][0]?>" />
	<div id="right" style="margin-top:3px;">

             <? if(!empty($accaunt_header['payment_bonus_account'][2])) { ?>
                <input type="checkbox" name="type_2" value="2" id="type_2" <?=(($types[2] == 1) ? 'checked="checked" ' : NULL)?>/>
                <label for="type_2">WTUSD<span style="color:red;">&#10084;</span></label>
               <? } ?>
             <? if(!empty($accaunt_header['payment_bonus_account'][6])) { ?>
                <input type="checkbox" name="type_6" value="6" id="type_6" <?=(($types[6] == 1) ? 'checked="checked" ' : NULL)?>/>
                <label for="type_6">DEBIT</label>
               <? } ?>

			   <? if(!empty($accaunt_header['bonuses'])) { ?>
                <input type="checkbox" name="type_1" value="1" id="type_1" <?=(($types[1] == 1) ? 'checked="checked" ' : NULL)?>/>
		<label for="type_1"><?=_e('BONUS')?></label>
<? } ?>
 <? if ( ($accaunt_header['c_creds_total'] + $accaunt_header['c_creds_amount_invest'] ) > 0 ){ ?>
                <input type="checkbox" name="type_4" value="4" id="type_4" <?=(($types[4] == 1) ? 'checked="checked" ' : NULL)?>/>
		<label for="type_4"><?=_e('CREDS')?></label>
		<? } ?>
                <? if ( $accaunt_header['partner_funds']  > 0 ){ ?>
				<input type="checkbox" name="type_3" value="3" id="type_3" <?=(($types[3] == 1) ? 'checked="checked" ' : NULL)?>/>
		<label for="type_3"><?=_e('P-CREDS')?></label>
                <? } ?>

		<button type="submit" style="cursor: pointer;"><img src="/img/enter.png" /></button>
	</div>
</form>
<? if (!empty($pays)) { ?>

	<table cellspacing="0" class="payment_table">
		<thead>
			<tr>
				<th><?=_e('accaunt/transaction_5')?></th>
				<th><?=_e('accaunt/transaction_6')?></th>
				<th><?=_e('accaunt/transaction_7')?></th>
				<th><?=_e('accaunt/transaction_8')?></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($pays as $item) {
                            ?>
				<tr>
					<td style='text-align:center;'><?= date_formate_view($item->date) ?></td>
					<td style='padding-left:10px;padding-right:10px;width:auto;'><?= $item->note ?><br/><span style="font-size:11px;color:#858585;"><?= $item->id ?> // <?=getBonusLabelUser( $item->bonus ) ?> // <?= _e(getTransactionsMethodLabel($item->metod, TRUE)) ?></span></td>
					<td style='text-align:right;width:80px;padding-right:15px;'>$ <?= price_format_double($item->summa) ?></td>

					<td style='text-align:center;'><?= _e(getTransactionLabelStatus($item->status)) ?>
                                                <?if ( $item->metod == 'bank' && $item->type==327 ){
                                                    $id_hash = md5($item->id.'_'.$item->id_user); ?>
                                            <a href="/upload/checks/cardcheck-<?=$id_hash?>.pdf" target="_blank"><img src="/images/PDF-Ripper.gif" style="vertical-align : middle;" /></a>
                                                <? } ?>
						<? if ((in_array($item->metod, array_flip(config_item("payment_bank_shablon")))) and Base_model::TRANSACTION_STATUS_DELETED != $item->status) { ?> <? } ?>
                                                <? if (("out" == $item->metod and (Base_model::TRANSACTION_STATUS_IN_PROCESS == $item->status or 10 == $item->status)  and (310 != $item->type or 323 != $item->type or 327 != $item->type) and time() <= (strtotime($item->date) + 31400000)) or ((in_array($item->metod, array_flip(config_item("payment_bank_shablon")))) and Base_model::TRANSACTION_STATUS_NOT_RECEIVED == $item->status) ) echo str_replace ("{{item_id}}", $item->id, $del_mes);?>
					</td>
				</tr>
			<? } ?>
		</tbody>
	</table>

	<?= $pages; ?>
<? } ?>
<br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
</div>

<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>


<script>
    var max_sums = {2:<?=$accaunt_header['payout_limit_by_bonus'][2]?>, 3:<?=$accaunt_header['payout_limit_by_bonus'][3]?>,5:<?=$accaunt_header['payout_limit_by_bonus'][5]?>};

    function on_conv_type_change(){
        var type = $('#conv_type option:selected').val();
        var max_summ = $('#conv_type option:selected').data('summ');
        $('#conv_summ').val( max_summ );
        on_conv_summ_change();
    }

    function on_conv_summ_change(){
        var summ = $('#conv_summ').val();
        var conv_perc = <?=get_sys_var('USD1_TO_CCREDS_PERC')?>;
        if ( isNaN(parseFloat(summ)) )
            $('#conv_ccreds_receive').val(  (0).toFixed(2) );
        else
            $('#conv_ccreds_receive').val(  (summ*conv_perc/100).toFixed(2) );

    }

    function on_conv2_summ_change(){
        var summ = $('#conv2_summ').val();
        var conv_perc = <?=get_sys_var('USD2_TO_CCREDS_PERC')?>;
        if ( isNaN(parseFloat(summ)) )
            $('#conv2_ccreds_receive').val(  (0).toFixed(2) );
        else
            $('#conv2_ccreds_receive').val(  (summ*conv_perc/100).toFixed(2) );

    }


     function conv_check(){
          $('#conv_form_error').hide();

          var type = $('#conv_type option:selected').val();
          var summ = $('#conv_summ').val();
          if ( type == "") return false;
          var max = max_sums[ type ];
          if ( summ > max ){
             $('#conv_form_error').html("<?=_e('Вы запросили сумму больше, чем имется на счету')?>");
             $('#conv_form_error').show();
             return false;
          }
          $('#conv .loading-gif').show();
          return true;
    }

     function conv2_check(){
          $('#conv2_form_error').hide();

          var type = $('#conv2_type option:selected').val();
          var summ = $('#conv2_summ').val();
          if ( type == "") return false;
          var max = max_sums[ type ];
          if ( summ > max ){
             $('#conv2_form_error').html("<?=_e('Вы запросили сумму больше, чем имется на счету')?>");
             $('#conv2_form_error').show();
             return false;
          }
          $('#conv2 .loading-gif').show();
          return true;


    }



</script>



<div id="card_window_message" class="popup_window">
       <div class="modal fade in modal-visa-card" id="modal-visa-card" role="dialog" aria-hidden="false" style="left: 0px; display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="$('#card_window_message').hide();"></div>
                    <h4 class="modal-title"><?= _e('Webtransfer VISA Card') ?></h4>
                </div>
                <div class="modal-body">
                    <div class="girl-cardn"></div>
                    <div class="i-want-card" style="text-transform:none !important;"> <?= _e('Моментальное зачисление средств на карту и снятие наличности в двух миллионах банкоматов по всему миру') ?></div>
                    <form class="form-style-modal" style="text-align:center;">
                        <button type="button" class="btn btn-primary center-wt-b" data-dismiss="modal" onclick="buy_card(); return false;"><?= _e('Получить') ?></button>
                        <label>
                            <input type="checkbox" name="nomore"/>
                           <?= _e('Больше не показывать') ?>
                        </label>

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="res-message"></div>
                    <center>
                        <img class="loader" style="display: none" src="/images/loading.gif">
                        <center></center>
                    </center>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>






<? ci()->load->view('user/accaunt/blocks/renderReceiveWaitingInvestDialog') ?>
<? ci()->load->view('user/accaunt/adv/adv') ?>



<script>
    function on_popup_card_adv_close(){
        if ( $('#card_window_message [name=nomore]:checked').length ){
             createCookie("reg_buy_card_dialog", "1", 365);
        }
        $('#card_window_message').hide();

    }
    function buy_card(){
        window.open('<?= site_url('account/profile') ?>');
       on_popup_card_adv_close();
    }

    function skip_card(){
        on_popup_card_adv_close();
    }
    


    <? if ( empty($wtcards) && 1==1){ ?>
    $(function(){
        if ( getCookie("reg_buy_card_dialog") != "1"  )
            $('#card_window_message').hide();
    });
    <? } ?>
</script>

