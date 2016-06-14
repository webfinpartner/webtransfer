<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
	
<style>
   .modal-window-phone .form .formRight .w10{
        width: 60px !important;
    }

    .modal-window-phone #w3confirmation .formRight{
        width: 450px !important;
    }
    #w3confirmation .formRow{
        padding: 8px 10px;
    }

   .modal-window-phone .dd-select {
        border: 1px solid #CCCCCC;
        border-radius: 2px;
        cursor: pointer;
        position: relative;
        float: left;
        margin-right: 10px;

        width: 90px;
        background: none repeat scroll 0% 0% #fff;
    }
   .modal-window-phone .dd-desc {
        color: #AAAAAA;
        display: block;
        font-weight: normal;
        line-height: 1.4em;
        overflow: hidden;
    }
   .modal-window-phone .dd-selected {
        display: block;
        font-weight: bold;
        overflow: hidden;
        padding: 7px;

    }
   .modal-window-phone .dd-pointer {
        height: 0;
        margin-top: -3px;
        position: absolute;
        right: 10px;
        top: 50%;
        width: 0;
    }
   .modal-window-phone .dd-pointer-down {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #000000 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
        border-image: none;
        border-right: 5px solid rgba(0, 0, 0, 0);
        border-style: solid;
        border-width: 5px;
    }
   .modal-window-phone .dd-pointer-up {
        -moz-border-bottom-colors: none !important;
        -moz-border-left-colors: none !important;
        -moz-border-right-colors: none !important;
        -moz-border-top-colors: none !important;
        border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #000000 !important;
        border-image: none !important;
        border-style: solid !important;
        border-width: 5px !important;
        margin-top: -8px;
    }
   .modal-window-phone .dd-options_1 {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background: none repeat scroll 0 0 #FFFFFF;
        border-color: -moz-use-text-color #CCCCCC #CCCCCC;
        border-image: none;
        border-right: 1px solid #CCCCCC;
        border-style: none solid solid;
        border-width: medium 1px 1px;
        box-shadow: 0 1px 5px #DDDDDD;
        display: none;
        list-style: none outside none;
        margin: 0;
        overflow: auto;
        padding: 0;
        position: absolute;
        z-index: 2000;
        top: 33px;

        max-height: 250px;
    }
   .modal-window-phone .dd-option {
        border-bottom: 1px solid #DDDDDD;
        color: #333333;
        cursor: pointer;
        display: block;
        overflow: hidden;
        padding: 4px;
        text-decoration: none;
        transition: all 0.25s ease-in-out 0s;
    }
    .modal-window-phone .dd-options_1 > li:last-child > .dd-option {
        border-bottom: medium none;
    }
   .modal-window-phone .dd-option:hover {
        background: none repeat scroll 0 0 #F3F3F3;
        color: #000000;
    }
   .modal-window-phone .dd-selected-description-truncated {
        text-overflow: ellipsis;
        white-space: nowrap;
    }
   .modal-window-phone .dd-option-selected {
        background: none repeat scroll 0 0 #F6F6F6;
    }
   .modal-window-phone .dd-option-image, .dd-selected-image {
        float: left;
        margin-right: 5px;
        max-width: 64px;
        vertical-align: middle;
    }
   .modal-window-phone .dd-image{
        float: left;
        margin: 6px;
    }
   .modal-window-phone .dd-container {
        position: relative;
    }

   .modal-window-phone .formRight label.dd-selected-text{
        float: right;
        color: #333;
        font-weight: 400;
        font-size: 16px;
    }
   .modal-window-phone .phone-code{
        float: left;
    }
   .modal-window-phone .small{
        font-size: 12px;
        color: #555;
    }


   .form.ui-formwizard.modal-window-phone> fieldset > div > label {
    	visibility: hidden !important;
    	width: 1px !important;
   }

   .modal-window-phone .form.ui-formwizard .formRight {
		float: left !important;
		width: 100% !important;
   }

   .modal-window-phone #various1 {
   	display: none;
   }

   .modal-window-phone #phone1 {
   	height: 25px;
    	width: 160px!important;
   }
	
   .modal-window-phone .formRow.padding10-0 {
   	border:none !important;
   }
  
  .modal-window-phone .formRow.padding10-0 {
    padding-left: 0px !important;
  }

  .modal-window-phone .input-error {
    box-shadow: 0px 0px 2px 1px #F50808 !important;
  }

  .formRow .formRight {
    float: left;
  }
  #popup_debit.popup_window_exchange, .popup_window_exchange, .popup_window_error, .popup_window_notification {
      background: white none repeat scroll 0 0;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      display: none;
      left: 31%;
      padding: 10px;
      position: fixed;
      text-align: center;
      top: 25%;
      width: 660px;
      z-index: 1000;
  }
  .popup_before_save_phone .close, .vivod13 {
      position: absolute;
      background: url("../../img/close.png") no-repeat 0 0;
      width: 24px;
      height: 20px;
      background-size: contain;
      right: 5px;
      top: 10px;
      cursor: pointer;
  }

  #popup_after_save_phone {
    width: 43%; display:none;
    left: 27%;
    z-index: 999999;
    position: fixed;
    height: 120px;
    padding: 30px;
    box-sizing: border-box;

  }
  .modal-backdrop.fade.in.back-screen {
  display: none;
    width: 100%;
    height: 100%;
    position: fixed;
    left: 1px;
    top: 1px;
    background: rgba(0, 0, 0, 0.51);  
    z-index: 123;
  }
</style>
<script type="text/javascript">
	$('.dd-pointer dd-pointer-down').click(function(){
		$('.phone-code ul').toggle();
	})

	function chek_phone_tt() {
	    var phone = document.getElementById('phone1').value;
	    var dlinna = phone.length;
	    var phone_rules = "<?= _e('accaunt/profile_36') ?>";

	    if ((dlinna < 7) || (dlinna > 15)) {
	        $('#popup_agree_confirm1').show('slow');
	        return  false;
	    } // Всплывающее окно "Не правильная длинна телефона"
	    else
	    {
	        var result = phone * 1; // умножаем на 1
	        if (!isNaN(result))
	        {
	            var chek_start_plus = phone.indexOf('+');
	            var chek_start_minus = phone.indexOf('-');

	            if ((chek_start_plus == 0) || (chek_start_minus == 0))
	            {
	                $('#popup_confirm_return').show('slow');
	                return  false;
	            }				// Всплывающее окно "Номер должен начинаться с цифры"
	            else
	            {
	                // id_user = document.getElementById('id_user').value;
	                // var data = 'query='+phone+'/'+id_user;
	                // send_ajax(data, data);
	            }
	        }
	        else {
	            $('#popup_agree_confirm0').show('slow');
	            return  false;
	        }  // Всплывающее окно "Не цифровые символы"
	    }
	}
	

	$(function () {
	    $('#sv_fact_dres').change(function ()
	    {
	        if ($(this).val() == 1 && $(this).attr('checked') == 'checked') {
	            $('input[name=f_house]').val($('input[name=r_house]').val());
	            $('input[name=f_index]').val($('input[name=r_index]').val());
	            $('input[name=f_town]').val($('input[name=r_town]').val());
	            $('input[name=f_street]').val($('input[name=r_street]').val());
	            $('input[name=f_flat]').val($('input[name=r_flat]').val());
	            $('input[name=f_kc]').val($('input[name=r_kc]').val());
	        }
	    });

	    $('#wizard2 #variousB').hide();            
            $('#phone1').prop('readonly','');
            $('.formPhone .phone-code .dd-select').removeClass('blocked');
            
	    $('.dd-selected, .dd-pointer').click(function () {
	        if ($('.dd-select').hasClass('blocked'))
	            return false;
	        $('.dd-options_1').toggleClass('dd-opened').slideToggle(100);
	    });

	    $('.dd-option').click(function () {
	        if (!$('.dd-select').hasClass('blocked'))
	            setCode(this);
	        $('.dd-options_1').slideUp(100);
	    });

	    function setCode(el) {
	        var res_code = $(el).data('code') + '',
	                short_name = $(el).data('short') + '',
	                code = res_code.trim();

	        $('.dd-options_1 .dd-option-selected').removeClass('dd-option-selected');
	        $(el).addClass('dd-option-selected');

	        $('.dd-selected-text').html('+' + code);
	        $('.dd-selected-code-value').val(code);
	        $('.dd-selected-short-name-value').val(short_name);
	    }

	    if ($('.dd-options_1 .dd-option-selected').length == 1) {
	        setCode('.dd-options_1 .dd-option-selected');
	    }
	});

</script>
<script type="text/javascript">
  function confirm_entered_number() {

    var prefix = $('.dd-selected-code-value').val();
    var number = $('.maskPhone_new_1').val();

    
    if(prefix == 'КОД' || prefix == 'CODE') {
      $('.result-error').text("<?=_e('phone_validation/please_enter_your_country')?>");
      $('.result-error').show();
      $('.dd-selected').addClass('input-error');
      return false;
    }

    $('.dd-selected').removeClass('input-error');


    if(number.length < 7 || number.length > 17) {
      $('.result-error').text("<?=_e('phone_validation/please_enter_your_number')?>");
      $('.result-error').show();
      $('#phone1').addClass('input-error');
      return false;
    }

    $('#phone1').removeClass('input-error');
    $('.result-error').hide();
    
    $('.dd-selected').removeClass('input-error');
    $('#popup_before_save_phone').show();

    $('.phone-num').text('+'+prefix + ' ' + number);

    return false; 
  }

  function send_save_phone() {

    $('#popup_before_save_phone').hide();

    var prefix = $('.dd-selected-code-value').val();
    var number = $('.maskPhone_new_1').val();
    var short_name = $('.dd-selected-short-name-value').val();

    var phone = prefix + number;
    var data = {phone: phone, code: prefix, short_name: short_name};
    
    mn.get_ajax( '/'+mn.site_lang+'/account/ajax_save_phone' ,data, function(res) {
            var message = '';
            console.log(res);
            if( res['success'] !==undefined )
            {
                
                $('#sm').hide();
                $('#popup_after_save_phone').show();
                $('.back-screen').show();
                setTimeout(function(){$('#popup_after_save_phone').hide()},5000);  
                setTimeout(function(){$('.back-screen').hide()},5000); 
              
            }else
                if( res['error'] !==undefined )
                     $('.result-error').show().html( res['error'] );
            
            $('.cancel_action').html(data);
        });

  }
</script>

<div id="sm" >
    <div class="modal fade in" id="confirmDialog" role="dialog" aria-hidden="false" style="display: none; ">
        <div class="modal-dialog" style="width:470px">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="pull-right" style="margin-top: -5px;"><img src="/images/security_module/universal_window/logo-wt.png" alt=""></span>
                    <h4 class="modal-title"><?=_e('phone_validation/enter_you_phone')?>
                    </h4>
                                    </div>
                <div class="modal-body" style="width:100%;     box-sizing: border-box;">


<?=_e('phone_validation/for_work_into_site')?>
<form 
id="wizard2" method="post" action="/account/profile" class="form ui-formwizard  modal-window-phone" novalidate="novalidate">
<fieldset class="step ui-formwizard-content" id="w2first" style="display: block;">

<? echo 
str_replace('dd-options', 'dd-options_1', 
str_replace('obr_button()', '', 
str_replace('id="phone"','id="phone1"', 
str_replace('maskPhone_new', 'maskPhone_new_1', $phone_input))));?>
</fieldset>
</form>
<div class="result-error" style="    padding: 4px;
    color:red; display:none">
  
</div>
   </div>
                <div class="modal-footer">
                                        <button type="button" class="btn btn-primary pull-left"  onclick="confirm_entered_number() "><?=_e('phone_validation/confirm')?></button>
                    <button type="button" class="btn pull-right " data-dismiss="modal" onclick="location.reload();return false;"><?=_e('phone_validation/not')?></button>
                                    </div>
                            </div>
        </div>
    </div>

    <div class="modal-backdrop fade in" style="display: none;"></div>
</div>


<div id="popup_before_save_phone" class="popup_window_exchange" style="width: 45%; left: 27%; z-index: 99999;     position: fixed; display: none;">
   <div class="close" onclick="$(this).parent().hide();"></div>
   <h2><?=_e('phone_validation/text_confirm')?></h2>
   <span class="info" style="font:16px/35px 'Lucida Grande','Lucida Sans Unicode',Arial">
    <?=_e('phone_validation/you_shore')?>
   </span>
   <br>
   <span class="phone-num" style="font:21px/35px 'Lucida Grande','Lucida Sans Unicode',Arial"></span>
   <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
 
 

   <center style="    margin-top: 30px;">
    <button class="button" type="submit" id="confirmation_block" onclick="send_save_phone();" style="display: inline-block;float:none;margin-left: 0; width:100px"><?= _e('phone_validation/modal/yes')?></button>
      <button class="button" type="button" onclick="$('#popup_before_save_phone').hide()" style="display: inline-block;float:none;margin-left: 10px; width:100px"><?= _e('phone_validation/modal/no')?></button>
   </center>
</div>


   <div class="modal-backdrop fade in back-screen"  onclick="$(this).hide(); $('#popup_after_save_phone').hide();"></div>

<div id="popup_after_save_phone" class="popup_window_exchange" >
   <div class="close" onclick="$(this).parent().hide(); $('.back-screen').hide();"></div>
   <h2 style="font: 16px/40px 'Lucida Grande','Lucida Sans Unicode',Arial;
    color: black;"><?=_e('phone_validation/success_save')?>  </h2>
</div>


