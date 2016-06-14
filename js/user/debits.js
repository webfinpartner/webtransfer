function conf_invest_sms_callback( res ){


    console.log(res);
    if( res === undefined || res['res'] === undefined || res['res'] !== 'success' ) return false;
    var id =  $('#popup_agree_confirm0 #confirm_invest').attr('rel');
    console.log(id);
    var code = res['code'];

    $('#popup_window').show('slow');

    $('#popup_window .loading-gif').show();
    $('#popup_window .content').html('<p class="center">Загрузка данных...</p>');

    var url = '/account/inbox_agree/' + id;

    $.post(site_url + url, {code:code, purpose: mn.security_module.purpose})
    .done(function(responce) {
        var html = null;
        $('#popup_window .loading-gif').hide();

        if (responce == '') {
            showError('#popup_window','Неверный ответ от сервера.');
            return false;
        }
        try {
            html = JSON.parse(responce);
        } catch (e) {
            showError('#popup_window','Неверный ответ от сервера.');
            return false;
        }
        if (html['error']) {
            showError('#popup_window',html['error']);
        } else if (html['success']) {
            showError('#popup_window',html['success']);
            setTimeout(function(){
                location.reload();
            },3000);
        }
    });
}
function showError(el, message, loading ) {
    if( loading ){
        $(el).find('.loading-gif').show();
    }
    $(el).find('.content').html('<p>' + message + '</p>');
    $(el).show('slow');
};
$(function(){console.log('toggle дебит init');
    // Открытие дополнительной информации
    $(document).on('click', '.view_all', function() {
        $(this).parent().find('.content_drop').toggle()
    })

    // Открывать и закрывать карточку дебита
    $(document).on('click', '.toggle .title', function() {console.log('toggle дебит');
        $(this).parent().find('.body').slideToggle();
    });


    //  что-то очищает, если что-то отмечено :)
    $('.other_payment').change(function() {
        if ($(this).prop("checked") == true)
            $('.other_pay_input').show();
        else
            $('.other_pay_input').val('').hide();
    }).trigger('change');

    // ЭТО отправляет запрос на сервер для одобрение входящей заявки
    $(document).on('click', '#popup_agree_confirm1 #confirm_invest, #popup_agree_confirm #confirm_invest', function() {
        var id = $(this).attr('rel'),
            wrap = $('.list_applications div[data-id=' + id + ']'),
            type = $(this).data('type'),
            all = 0;

        $('.popup_window').hide('slow');
        $('#popup_window').show('slow');

        $('#popup_window .loading-gif').show();
        $('#popup_window .content').html('<p class="center">Загрузка данных...</p>');

        $.post(site_url + '/account/inbox_agree/' + id, function(responce) {
            var html = null;
            $('#popup_window .loading-gif').hide();

            if (responce == '') {
                showError('#popup_window','Неверный ответ от сервера.');
                return false;
            }
            try {
                html = JSON.parse(responce);
            } catch (e) {
                showError('#popup_window','Неверный ответ от сервера.');
                return false;
            }
            if (html['error']) {
                showError('#popup_window',html['error']);
            } else if (html['success']) {
                showError('#popup_window',html['success']);
                setTimeout(function(){
                    location.reload();
                },1000);
            }
        });
        return false;
    });

    $(document).on('click', '.return_arbitrage_part', function() {
        var id = $(this).data('id');
        $('#return_arbitration_part_dialog').data('id', id);
        $('#return_arbitration_part_dialog').show('slow');
        return false;
    });
    
    $(document).on('click', '#return_arbitration_part_but', function() {
        var id = $('#return_arbitration_part_dialog').data('id');
        location.replace( site_url + '/account/return_arbitration_part/'+id );
        return false; 
    });    
    
    


    $(document).on('click', '#popup_agree_confirm0 #confirm_invest,#popup_agree_confirm_own_direct #confirm_invest', function() {
        var id = $(this).attr('rel'),
            wrap = $('.list_applications div[data-id=' + id + ']'),
            type = $(this).data('type'),
            all = 0;
        
        // приходится так делать(
        $('#popup_agree_confirm0 #confirm_invest').attr('rel', id);
            


        mn.security_module
            .init()
            .show_window('withdrawal_standart_credit')
            .done(conf_invest_sms_callback);

        return false;

        if( security )
        {
            $('.popup_window').hide('slow');
            //**
            $('#out_send_window')//.data('call-back',conf_invest_sms_callback)
                                .data('called-el-id', $(this).attr('id') );//.show('slow');
                        
            
                        
        }else{
            $('.popup_window').hide('slow');
            $('#popup_window').show('slow');

            showError('#popup_window','Загрузка данных...');

            $.post(site_url + '/account/inbox_agree/' + id, function(responce) {
                var html = null;
                $('#popup_window .loading-gif').hide();

                if (responce == '') {
                    showError('#popup_window','Неверный ответ от сервера.');
                    return false;
                }
                try {
                    html = JSON.parse(responce);
                } catch (e) {
                    showError('#popup_window','Неверный ответ от сервера.');
                    return false;
                }
                if (html['error']) {
                    showError('#popup_window',html['error']);
                } else if (html['success']) {
                    showError('#popup_window',html['success']);
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            });
        }
        return false;
    });


    // Открывать попап при одобрении входящей заявки, для КРЕДИТА
    $(document).on('click', '.list_applications .conf_credit', function() {
        $('#popup_agree_confirm').show('slow');
        $('#popup_agree_confirm #confirm_invest').attr('rel', $(this).parents('.list_applications > div').data('id')).data('type', 1);
        return false

    })

    // Открывать попап при одобрении входящей заявки, для гарантированного вклада
    $(document).on('click', '.list_applications .conf1', function() {
        $('#popup_agree_confirm1').show('slow');
        $('#popup_agree_confirm1 #confirm_invest').attr('rel', $(this).parents('.list_applications > div').data('id')).data('type', 2);
        return false

    })

    // Открывать попап при одобрении входящей заявки, для НЕ гарантированного вклада
    $(document).on('click', '.list_applications .conf0', function() {
        var type =  $(this).data('type');
        var popup_selector = '#popup_agree_confirm0';
        if ( type == 'own_direct' ){
            popup_selector = '#popup_agree_confirm_own_direct';
            $(popup_selector + ' #extra_info').html(  $(this).data('extra') );
        }
        
        $(popup_selector).show('slow');
        $(popup_selector + ' #confirm_invest')
                .attr('rel', $(this).parents('.list_applications > div').data('id') )
                .data('type', 2);
        
        return false;

    })

// Открывать попап при одобрении входящей заявки, для CARD вклада
    $(document).on('click', '.list_applications .conf7', function() {
        $('#popup_agree_confirm7').show('slow');
        $('#popup_agree_confirm7 #confirm_invest')
                .attr('rel', $(this).parents('.list_applications > div').data('id') )
                .data('type', 2);
        return false;

    })

	// Открывать попап при одобрении входящей заявки, для CARD вклада
    $(document).on('click', '.list_applications .conf2', function() {
        $('#popup_agree_confirm2').show('slow');
        $('#popup_agree_confirm2 #confirm_invest')
                .attr('rel', $(this).parents('.list_applications > div').data('id') )
                .data('type', 2);
        return false;

    })
	
    // Открытие попап окошки для перевода средств на карту другого пользователя
    $(document).on('click', '.alfa', function() {
        $('#other_methods').hide('slow');
        $('#send_money').hide('slow');
        $('#popup_alfa').show('slow');
        return false

    })
    // Открытие попап окошки для для других методов оплаты
    $(document).on('click', '.other_methods', function() {
        $('#send_money').hide('slow');
        $('#other_methods').show('slow');
        return false

    })
    // Открытие попап окошки для получение реквизитов пользователя
    $(document).on('click', '#open_rekviziti', function() {
        $('#other_methods').hide('slow');
        $('#send_money').hide('slow');
        $('#rekviziti').show('slow');
        return false

    })

    // Отклонение входящей заявки
    $(document).on('click', '.list_applications .cancel', function() {
        if (!confirm("Отклонить заявку?"))
            return
        wrap = $(this).parents('.list_applications > div');
        $.post(site_url + '/account/inbox_cancel/' + wrap.data('id'), {cause: ''})
        wrap.hide();
        return false
    })


    // подтверждения на получение средств
    $(document).on('click', '.confirm_return', function() {
        $('#popup_confirm_return  .confirm-button').data('id', $(this).data('id'))
        $('#popup_confirm_return').show();
        return false
    })

    $(document).on('click', '#popup_confirm_return .confirm-button', function() {
        $.post(site_url + '/account/confirm_return/' + $(this).data('id'), function() {
            window.location.reload();
        })
    })

    // подтверждения на отправку средств
    $(document).on('click', '.confirm_payment', function() {
        $('#popup_confirm_payment .button').data('id', $(this).data('id'))
        $('#popup_confirm_payment').show();
        return false
    })

    $(document).on('click', '.confirm_payment_cancel', function() {
        $.post(site_url + '/account/confirm_payment_cancel/' + $(this).data('id'), function() {
            window.location.reload();
        });
    })


    $(document).on('click', '#popup_confirm_payment .button', function() {
        $.post(site_url + '/account/confirm_payment/' + $(this).data('id'), function() {
            window.location.reload();
        });
    });

    // Открытие окна для жалобы
    $(document).on('click', '.report_send', function() {
        $('#report_send .confirm-button').data('id', $(this).data('id'))
        $('#report_send').show();
        return false
    })

    //отправка жалобы
    $(document).on('click', "#report_send .confirm-button", function() {
        $('#report_send .loading-gif').show()
        $.post(site_url + '/account/report/' + $(this).data('id'), $('#report_form').serialize(), function() {
            alert('Ваша жалоба успешно отправлена оператору сети и кредитору')
            $('#report_send .loading-gif').hide()
            $('#report_send').hide();
        })
        return false
    })



    // открывает и заполняет поля попап для отправки средств
    $(document).on('click', '.confirm_send', function() {
        $('#rekviziti').hide('slow');
        $('#send_money').find('#extra_info_div').hide();
        $('#send_money').find('#cards_list_div').hide();
        
        reciver = $(this).parent().siblings('.load_user').text();
        id = $(this).data('id');
        var type = $(this).data('type');
        var card_id = $(this).data('card');
        var extra_info = $(this).data('extra');

        id_user = $(this).parent().siblings('.load_user').attr('rel')

        $.post(site_url + '/account/user_payment_info/' + id_user, function(data) {

            $('#rekviziti fieldset').html(data)
        })


        $('#send_money').find('input[name="reciver"]').val(reciver)
            if ( type == 'own_direct'){
                $('#send_money').find('#extra_info_div').show();    
                $('#send_money').find('#extra_info').html(extra_info);    
            }
            else if ( type == 'card'){
                $('#send_money').find('#cards_list_div').show();    
                $('#send_money').find('#cards_list option[value='+card_id+']').prop('selected', true);    
            }            
        


        if( id_user == '90831137' )
        {
            $.post(site_url + '/account/ajax_user/get_credit_calc',{id:id})
                .done(function( data ){
                    if( data == '' ) return;
                    var message = [];

                    try{
                        message = JSON.parse( data );
//                        console.log( message );
                    }catch( e ){
//                        console.log( message );
                        return false;
                    }
//                    console.log( message );
                    if( message['success'] ){

                        $('#send_money').find('input[name="amount"]').val( message['success'] );
                        $('#send_money').show();
                        $('#send_money').find('#out_send').data('id', id)
                        $('#send_money').find('#out_send_return').data('id', id)
                    }
                    return false;
                });
        }
        else
        {
            summa = $(this).parent().parent().parent().find('.out-summa').text()
            $('#send_money').find('input[name="amount"]').val(summa)
            $('#send_money').show();
            $('#send_money').find('#out_send').data('id', $(this).data('id'))
            $('#send_money').find('#out_send_return').data('id', $(this).data('id'))
        }




        return false;
    })



    function defaultDone(el, responce) { 			// выполнится после AJAX запроса
        var html = null;
        $(el).find('.loading-gif').hide();
        if (responce == '') {
            showError(el, 'Неверный ответ от сервера.');
            return;
        }
        try {
            html = JSON.parse(responce);
        } catch (e) {
            showError(el, 'Неверный ответ от сервера.');
            return;
        }
        if (html['error']) {
            showError(el, html['error']);
        } else if (html['success']) {
            showError(el, html['success']);
            if (html['action'] && html['action'] == 'reload') {
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }
        }

    }
    //возврат Арбитража - показывает поп-ап
    $(document).on('click','.arbitrage_repayment',function() {
        var id = parseInt( $(this).data('id') );
        if( isNaN(id)){
            showError('#user_popup','Ошибка данных 203. Сообщите, пожалуйста, об этом в поддержку.');
            return false;
        }
        $('#arbitrage_repayment input.id').val( id );
        
        if (  $(this).data('type') == 'gift' ){
            $('#arbitrage_repayment_pa_block').show();
        } else {
            $('#arbitrage_repayment_pa_block').hide();
        }

        $('#arbitrage_repayment').show('slow');
        
        
        
        return false;
    });
    
    
    $(document).on('click','#arbitrage_repayment .confirm-button',function() {
        var id = parseInt( $('#arbitrage_repayment input.id').val()  );
        var account_type =  $('#arbitrage_repayment #payment_account option:selected').data('type');
        var account_id =  $('#arbitrage_repayment #payment_account option:selected').val();
        $('#arbitrage_repayment').hide();
        showError('#user_popup','Идет обновление данных', true);

        $(this).data('active',false);
        $.post( site_url + "/arbitrage/ajax_user/arbitrage_credit_repayment/", {'id':id, account_type: account_type,account_id:account_id  })
        .done(function( data ) {
            $('#arbitrage_repayment').hide('slow',function(){
                defaultDone( '#user_popup',  data );
            });
        });
    });

    //пролонгация Арбитража - показывает поп-ап
    $(document).on('click','.arbitrage_prolongation',function(event) {
        var id = parseInt( $(this).data('id') ),
            time = parseInt( $(this).data('time') ),
            maxtime = parseInt( $(this).data('maxtime') ),
            summa = parseFloat( $(this).data('summa') ),
            active = $(this).data('active'),
            list = '';

        if( isNaN(id)
            || isNaN(time)
            || isNaN(summa)
        ){
            showError('#user_popup','Ошибка данных 200. Сообщите, пожалуйста, об этом в поддержку.');
            return false;
        }
        if( active == false ){
            return;
        }
        $('#arbitrage_prolongation input.id').val( id );
        $('#arbitrage_prolongation input.summa').val( summa );
        $('#arbitrage_prolongation input.time').val( time );

        var period = maxtime - time;
        if( period <= 0 ){
            //showError('#user_popup','Ошибка данных 201. Сообщите, пожалуйста, об этом в поддержку.');
            showError('#user_popup','Вы не можете пролонгировать кредит.');
            return false;
        }
        var list = '';
        for(var i = 1; i <= period; i++){
            if( i == 1 ) list += "<option value='"+i+"' selected=''>"+i+"</option>\n";
            else
                list += "<option value='"+i+"'>"+i+"</option>\n";
        }

        $('#arbitrage_prolongation select').html( list );
        reculculate();
        $('#arbitrage_prolongation').show('slow');
        return false;
    });

    function reculculate() {
        var id = parseInt( $('#arbitrage_prolongation input.id').val()  ),
            time = parseInt( $('#arbitrage_prolongation input.time').val() ),
            summa = parseFloat( $('#arbitrage_prolongation input.summa').val() ),
            add_time = parseInt( $('#arbitrage_prolongation .period-add').val() ),
            amount = summa*(1 + (time + add_time)*0.005);

        $('#arbitrage_prolongation .amount').val( Math.round( amount*100 )/100 );
    }
    $(document).on('keydown','#arbitrage_prolongation select', reculculate );
    $(document).on('change','#arbitrage_prolongation select', reculculate );

    $(document).on('click','#arbitrage_prolongation .confirm-button',function() {
        var id = parseInt( $('#arbitrage_prolongation input.id').val()  ),
            add_time = parseInt( $('#arbitrage_prolongation .period-add').val() );

        $('#arbitrage_prolongation').hide();
        showError('#user_popup','Идет обновление данных', true);

        $.post( site_url + "/arbitrage/ajax_user/arbitrage_credit_prolongation/",{'id':id, 'add_time':add_time})
        .done(function( data ) {
            defaultDone( '#user_popup',  data );
        });
        return false;
    });



    // Отправка средств
    $(document).on('click', '#out_send', function() {
        $('#send_money .loading-gif').show()
        $.post(site_url + '/account/confirm_send/' + $(this).data('id'), function() {
            window.location.reload();
        })
        return false;
    })


    // Отправка средств
    $(document).on('click', '#out_send_return', function() {
        $('#send_money .loading-gif').show()
        $.post(site_url + '/account/confirm_send_return/' + $(this).data('id'), {card_id: $('#send_money').find('#cards_list').val() }, function() {
            window.location.reload();
        })
        return false;
    })


    // Загрузка данные о пользователе
    $(document).on('click', '.load_user', function() {

        var id = $(this).data('id');
        if( id === undefined ) id = 0;

        $.get(site_url + '/account/ajax_user/get_user_data/1/' + $(this).attr('rel') + '/'+id, function(data) {
            wrap = $('#user_popup');
            wrap.show('slow');
            wrap.find('.content').html(data);
            wrap.find('.content').find('.button').hide();
        });
    })

    // Обработчик на закрытие попап окошек
    $(document).on('click', '.popup_window .close, #popup_debit .close', function() {
        $(this).parent().hide('slow');
    });

});