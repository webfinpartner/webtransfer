//SMS-module
var phoneTimer = null;
var phoneTimerValInit = 120;
var phoneTimerVal = phoneTimerValInit;
$(function(){

//      // enable onShow and onHide
//      $.each(['show', 'hide'], function (i, ev) {
//        var el = $.fn[ev];
//        $.fn[ev] = function () {
//          this.trigger(ev);
//          return el.apply(this, arguments);
//        };
//      });

      initSMS();
});

function initSMS() {
    if("undefined" == typeof security || !security) return;
console.log(security);

    if ( security == "whatsapp" || security == "viber" )
        security = "sms";

    if ("code" == security) add_code_form();
    else if ("whatsapp" == security) add_whatsapp_form();
    else if ("viber" == security) add_viber_form();
    else if ("sms" == security) add_sms_form();//sms
    else if ("email" == security) add_email_form();//email

    var page_hash = window.page_hash;
    if( window.page_hash === undefined )
    {
        var page_hash = '';
    }
    
    if( window.standart_calc && typeof standart_calc == 'function')
        $('#out_send_window').data('call-back',standart_calc); //standart_calc function defined in the code

    if($("#out_send_window").length > 0)
    {
        
         $(document).on('show','#out_send_window', function(){
             console.log('show-out-send');
             $('#out_send_window input#code.form_input[type=text]').val('');
             //console.log('show' + $('#out_send_window').data().count );
            if ( $('#out_send_window').data().count == 0  || $('#out_send_window').data().count === undefined )
            {
                //очистим все
                $('#out_send_window .send_code_link').css('display', 'block');
                $('.sms-content #code').val('');
                 $('#sms-code .res-message')
                    .html('')
                    .removeClass('error')
                    .removeClass('success')
                    .css('display', 'none');

                $('#out_send_window').data('count',1);
                //$('#out_send_window .send_code_link').css('display', 'none');
                 d_sendSms();
                 //console.log('autorun');





            }
         });

         $('#out_send_window').on('hide', function(){
            //console.log('hide');
            $('#out_send_window').data('count',0);
            if (phoneTimer!=null){
                clearInterval(phoneTimer);

            }


         });


        $('#out_send_window .send_code_link').unbind('click');
        $('#out_send_window .send_code_link').off();
        $("#out_send_window").on('click','#out_send_window .send_code_link', function(){
            var $this = $(this);

            if( $(this).hasClass('closed') ) return false;
            $(this).addClass('closed');

            $('#out_send_window').data('count',1);
            console.log('on click');
            d_sendSms(function(){
                $this.removeClass('closed');
            });
            return false;
        });

        $("#out_send_window").on('click','#out_send_window .button', function() {
            //$('#out_send_window .send_code_link').css('display', 'block');
            var wrongs = 0,
            strFun = $('#out_send_window').data('call-back');

            wrongs = d_checkField('#code', wrongs);

            if (wrongs)
                return false;

            $('#out_send_window').hide('slow');
            //console.log( strFun );
            if( strFun !== undefined ) strFun();
        });

        $("#out_send_window").keydown(function(e) {//console.log("sms",e.which);
            switch(e.which) {
                case 13: if($("#out_send_window").is(':visible'))
                            $("#out_send_window .button").trigger("click");// left
                break;
                case 27: $('.out_send_window').hide();// left
                break;

                default: return; // exit this handler for other keys
            }
            e.preventDefault(); // prevent the default action (scroll / move caret)
        });
    }
}

function distroySMS(){
    $('#out_send_window').remove();
    security = '';
    standart_calc = '';
}


function d_showLoading( show ) {
    if( show === undefined || show === 'show' ) $('#out_send_window .loading-gif').show();
    else
        $('#out_send_window .loading-gif').hide();
}
function d_showMessage(message, type) {
    $('#sms-code .res-message')
            .html(message)
            .removeClass('error')
            .removeClass('success')
            .addClass(type)
            .css('display', 'block');
     $('#out_send_window').data('count',1);
}
function d_sendSms(fnc) {
    if( "code" == security )
    {
        return false;
    }

    $('#out_send_window .send_code_link').css('display', 'none');
    $('#out_send_window input#code.form_input[type=text]').val('');
    var message = null,
        url = '/account/ajax_user/sms_request';

    var purpose = window.purpose;

    if( window.purpose === undefined )
    {
        var purpose = 'withdrawal_standart_credit';
    }
    
    var page_hash = window.page_hash;
    if( window.page_hash === undefined )
    {
        var page_hash = '';
    }

    if ("whatsapp" == security)
    url = '/account/ajax_user/whatsapp_request';

    if ("email" == security)
    url = '/account/ajax_user/email_request';


    if ("viber" == security)
    url = '/account/ajax_user/viber_request';

    //console.log( 'd_sendSms: ' + url );
    d_showLoading( 'show' );
    $.post(site_url + url, {purpose: purpose, page_hash: page_hash })
    .done(function(data) {
        if( fnc !== undefined && typeof fnc === 'function')
        {
            fnc();
        }

        d_showLoading( 'hide' );

        if( !data ){
            d_showMessage(_e('Неверный ответ от сервера. Перезагрузите страницу.'), 'error');
            $('#out_send_window .send_code_link').css('display', 'block');
        }

        try {
            message = JSON.parse(data);
        } catch (e) {
            d_showMessage(_e('Неверный ответ от сервера. Перезагрузите страницу.'), 'error');
            $('#out_send_window .send_code_link').css('display', 'block');
        }

        if (message['error']) {
            d_showMessage(message['error'], 'error');
            $('#out_send_window .send_code_link').css('display', 'block');
        } else
        if (message['success']) {
            $('#out_send_window .send_code_link').css('display', 'none');
            if(  message['action'] == 'start-counter' )
            {
                d_showMessage(message['success']+' <span id="phoneTimer">'+phoneTimerValInit+'</span>  '+_e('сек.'), 'success');
                //$(window).find( '.send_code_link' ).hide();
                if (phoneTimer!=null)
                    clearInterval(phoneTimer);
                phoneTimerVal = phoneTimerValInit;
                phoneTimer = setInterval(function() {
                    phoneTimerVal--;
                    //console.log("val:"+phoneTimerVal);
                    $('.res-message' )
                            .show()
                            .html(message['success'] + '<span id="phoneTimer">'+phoneTimerVal+'</span>   '+_e('сек.'));

                    if (phoneTimerVal - 1 <= 0) {
                        $( '.send_code_link' ).show();
                        $('.res-message' ).hide();
                        clearInterval(phoneTimer);
                        phoneTimer = null;
                        phoneTimerVal = phoneTimerValInit;
                    }

                }, 1000);
            } else {
                d_showMessage(message['success'], 'success');
                $('#out_send_window .send_code_link').css('display', 'block');
            }
        } else {
            d_showMessage(_e('Неизвестная ошибка. Обратитесь в службу поддержки.'), 'error');
        }

        return false;
    });
    return false;
}
function d_checkField(_link, _wrongs, _pattern) {
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

function add_spec_form( form_name )
{
    $('#out_send_window').remove();

    switch( form_name ){
        case 'whatsapp': add_whatsapp_form();
            break;
        case 'sms': add_sms_form();
            break;
        case 'code': add_code_form();
            break;
        case 'email': add_email_form();
            break;
    }
}
function add_sms_form(){
    var sms_window =
            '<div id="out_send_window" class="popup_window" style="z-index:99999;">'+
                    '<div onclick="$(this).parent().hide(\'slow\');" class="close"></div>'+
                    '<h2>'+_e('Двух этапная авторизация')+'</h2>'+
                    '<div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">'+
                        '<label class="label">'+_e('Код подтверждения')+':</label>'+
                        '<div class="formRight" style="width: 64% !important;">'+
                            '<div class="sms-content">'+
                                '<input class="form_input" id="code" type="text" value="" style="height: 17px;"/>'+
                                '<a class="but blueB ml10 send_code_link right" href="#">'+_e('Запрос СМС')+'</a>'+
                            '</div>'+
                        '</div>'+
                        '<div class="res-message"></div>'+
                        '<img class="loading-gif" style="display: none" src="/images/loading.gif"/>'+
                    '</div>'+
                    '<a class="button narrow" >Ok</a>'+
                '</div>';
    $('#container').append( $( sms_window ) );

}
function add_whatsapp_form(){
    var sms_window =
            '<div id="out_send_window" class="popup_window" style="z-index:99999;">'+
                    '<div onclick="$(this).parent().hide(\'slow\');" class="close"></div>'+
                    '<h2>'+_e('Двух этапная авторизация')+' WhatsApp</h2>'+
                    '<div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">'+
                        '<label class="label">'+_e('Код подтверждения')+':</label>'+
                        '<div class="formRight" style="width: 64% !important;">'+
                            '<div class="sms-content">'+
                                '<input class="form_input" id="code" type="text" value="" style="height: 17px;"/>'+
                                '<a class="but blueB ml10 send_code_link right" href="#">'+_e('Запрос кода')+'</a>'+
                            '</div>'+
                        '</div>'+
                        '<div class="res-message"></div>'+
                        '<img class="loading-gif" style="display: none" src="/images/loading.gif"/>'+
                    '</div>'+
                    '<a class="button narrow" >Ok</a>'+
                '</div>';
    $('#container').append( $( sms_window ) );

}
function add_viber_form(){
    var sms_window =
            '<div id="out_send_window" class="popup_window" style="z-index:99999;">'+
                    '<div onclick="$(this).parent().hide(\'slow\');" class="close"></div>'+
                    '<h2>'+_e('Двух этапная авторизация')+' Viber</h2>'+
                    '<div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">'+
                        '<label class="label">'+_e('Код подтверждения')+':</label>'+
                        '<div class="formRight" style="width: 64% !important;">'+
                            '<div class="sms-content">'+
                                '<input class="form_input" id="code" type="text" value="" style="height: 17px;"/>'+
                                '<a class="but blueB ml10 send_code_link right" href="#">'+_e('Запрос кода')+'</a>'+
                            '</div>'+
                        '</div>'+
                        '<div class="res-message"></div>'+
                        '<img class="loading-gif" style="display: none" src="/images/loading.gif"/>'+
                    '</div>'+
                    '<a class="button narrow" >Ok</a>'+
                '</div>';
    $('#container').append( $( sms_window ) );

}
function add_code_form(){
    var sms_window =
            '<div id="out_send_window" class="popup_window" style="z-index:99999;">'+
                    '<div onclick="$(this).parent().hide(\'slow\');" class="close"></div>'+
                    '<h2>'+_e('Двух этапная авторизация')+' Webtransfer Auth'+'</h2>'+
                    '<div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">'+
                        '<label class="label">'+_e('Код подтверждения')+':</label>'+
                        '<div class="formRight" style="width: 64% !important;">'+
                            '<div class="sms-content">'+
                                '<input class="form_input" id="code" type="text" value="" style="height: 17px;"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="res-message"></div>'+
                        '<img class="loading-gif" style="display: none" src="/images/loading.gif"/>'+
                    '</div>'+
                    '<a class="button narrow" >Ok</a>'+
                '</div>';
    $('#container').append( $( sms_window ) );
}
function add_email_form(){
    var sms_window =
            '<div id="out_send_window" class="popup_window" style="z-index:99999;">'+
                    '<div onclick="$(this).parent().hide(\'slow\');" class="close"></div>'+
                    '<h2>'+_e('Двух этапная авторизация')+' E-Mail</h2>'+
                    '<div id="sms-code" class="formRow padding10-40" style="border-bottom:0;">'+
                        '<label class="label">'+_e('Код подтверждения')+':</label>'+
                        '<div class="formRight" style="width: 64% !important;">'+
                            '<div class="sms-content">'+
                                '<input class="form_input" id="code" type="text" value="" style="height: 17px;"/>'+
                                '<a class="but blueB ml10 send_code_link right" href="#">'+_e('Запрос кода')+'</a>'+
                            '</div>'+
                        '</div>'+
                        '<div class="res-message"></div>'+
                        '<img class="loading-gif" style="display: none" src="/images/loading.gif"/>'+
                    '</div>'+
                    '<a class="button narrow" >Ok</a>'+
                '</div>';
    $('#container').append( $( sms_window ) );

}
