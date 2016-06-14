// Функции с префиксом confirm_order__ относятся к второй части p2p
// Первая часть это создание заявки, вторая это проведение заявки.


function prefund_start_transaction() {
    // card_id = $('.selector_visa_card_to_prefund .form_select').val();
    //еще нужно будет добавить проверку one_pass тут!!
    card_id = $( ".select-visa-card select").val();
    price   = $('#modal_visa_prefund .price span').text();
    $("#modal_visa_prefund .loading-gif").show();
    mn.security_module
        .init()
        .show_window('create_get_p2p_orders')
        .done(function( res ){
            
            if(typeof res.res !== undefined && res.res == 'closed') return false;
            
            if( typeof res['success'] === undefined ) return false;

            if( typeof res['code'] !== undefined ) $('#form_code').val( res['code'] );  
            
            $.post(prefund_save_card_from, {'card_id':card_id},
                function(resTxt){
                    // var res = $.parseJSON(resTxt);
                    // alert(123);
                    // тут вызывать prefund а потом основную форму закрывать
                    $('#sel_wt_form').submit();
                }
            );
       
        });
}

function prefund_init() {
    // Тут нужно проверить, что sell == visa
    // и тогда вывести Popup об переводе денег

    // var input_price_sell = $('.righthomecol.div_sell_payment_systems.step_1').eq(1).find('input.form_input').eq(0);
    var input_price_sell = $('.righthomecol.div_sell_payment_systems.step_1').eq(1).find('input.form_input[value!=1]').last();

    // Проверяем точно ли это выбранный тип валюты
    if(input_price_sell.attr('name') == 'input_summa_sell_payment_systems[wt_visa_usd]') {
        price = input_price_sell.val();
        
        price = price * 1.01;

        // $('.modal_visa_prefund')
        // var content = '<p>'+ price +'</p>';
        $('#modal_visa_prefund .price span').html(price); 
        // Показываем с какой карты будет сделана транзакция
        selected_card_text = $( ".select-visa-card select option:selected").text();
        $('#modal_visa_prefund .selected_visa_card_to_prefund p').html(selected_card_text);
        $('#modal_visa_prefund').show();
      

    } 
}


function confirm_order__check_is_visa($popup, $this) {
    input_name = $('#popup_exchange .form_input.save_user_payment_data_input').attr('name');
    if(input_name == 'user_payment_data[wt_visa_usd]' 
        && $($this[0]).hasClass('exchange_action')
        && $($popup[0]).hasClass('popup_window_exchange') 
    ) {
        return true;
    }
    return false;
}


function confirm_order__visa_button_show(purpose) {
    if(purpose == 'show') {
        $('#exchange_confirm_buntton').hide();
        $('#exchange_confirm_buntton_visa').show();
    } else if(purpose == 'hide') {
        $('#exchange_confirm_buntton').show();
        $('#exchange_confirm_buntton_visa').hide();
        
    }
}

function confirm_order__visa_load($popup, $this) {
    confirm_order__visa_button_show('show');
    confirm_order__visa_button_status('disabled');
    confirm_order__load_visa_cards_to_popup($popup, $this);
}

function confirm_order__visa_destroy($popup, $this) {
    confirm_order__visa_button_show('hide');
    $popup.find('.select_payment_syctem_container_sell').show();
    $popup.find('.payment_container_visa').hide();
}

function confirm_order__load_visa_cards_to_popup($popup, $this){
    confirm_order__visa_button_status('disabled');

    last_container =  $popup.find('.select_payment_syctem_container_sell').has('div');
    container = $popup.find('.payment_container_visa');
    last_container.hide();
    $popup.find('.confirm_order_text_1').hide();
    $popup.find('.payment_container_visa').show();
    $.post( get_cards_user,{v: 'select-1'},
        function(resTxt){
            var res = $.parseJSON(resTxt);
            if( res['success'] == 1) {
                text = '<div class="col select-visa-card-confirm-order input_container_div" style="margin: 0px auto;padding: 0px; width:60%; height:75px"><div style="margin: 15px 0; line-height: 18px;">'+res['text']+':</div><select class="form_select" name="visa_card_id" style="width: 100%; margin:0;background: #fff none repeat scroll 0 0;    border: 1px solid #ddd; box-shadow: 0 0 0 2px #f4f4f4;     color: #656565; font-family: Arial,Helvetica,sans-serif;    font-size: 11px;  padding: 2px 6px 6px; margin: 0 6px; height: 34px; text-align: center; font-size: 14px; line-height: 14px;">';

                for (var i = 0 ; i < res['result'].length; i++) {
                    result_line = '<option value="'+res['result'][i]['id']+'">'+res['result'][i]['name']+'</option>';
                    text+= result_line;

                };
                text += '</select></div>';
                text += '<div styel="clear:both"></div>';

                // При выборе visa появляется select
                confirm_order__visa_button_status('enabled');
            } else {
                text = '<br><div style="border: 1px solid red; width: 400px; padding: 5px; margin: 0 auto; text-align: left; box-sizing: border-box;">' + res['text']+ '</div>';    
            }
            container.html(text);
        }
    );  
}

function confirm_order__onclick_visa_confirm($this) {
    correct = $this.parent().find('input[name=correct]');
    if(correct.is(':checked')  ) {
        correct.parent().css('border','none'); 

        card_id = $('.select-visa-card-confirm-order select').val();

        // mn.security_module
            // .init()
            // .show_window('create_get_p2p_orders')
            // .done(function( res ){
                
                // if(typeof res.res !== undefined && res.res == 'closed') return false;
                
                // if( typeof res['success'] === undefined ) return false;
// 
                // if( typeof res['code'] !== undefined ) $('#form_code').val( res['code'] );  
                

                // При выборе нужной карты отправляем ajax На сохранение ее
                var select_card_id = $('#popup_exchange select[name=visa_card_id]').val();
                send_save_payment_data_visa_card(select_card_id);
                confirm_order__finish($this);
  
           
            // });


    } else {
        correct.parent().css('border','1px solid red');
    }
}

function confirm_order__visa_button_status(purpose) {
    if(purpose == 'enabled') {
        $('#exchange_confirm_buntton_visa').attr('style', 'cursor: pointer; none repeat scroll 0% 0% #3390EE);');
        $('#exchange_confirm_buntton_visa').attr('onclick', 'confirm_order__onclick_visa_confirm($(this))');
    } else if (purpose == 'disabled') {
        $('#exchange_confirm_buntton_visa').attr('style', 'cursor: default; background: none 0% 0% repeat scroll rgb(158, 158, 158);');
        $('#exchange_confirm_buntton_visa').off();
    }
}

function next_button_handler_active(step, purpose) {
    if(step == 'step_1') {
        var next_button = $('.button.next_button');
        if(purpose == 'show') {
            next_button.attr('disabled', false);
            next_button.css('background', 'none repeat scroll 0% 0% #3390EE');
            next_button.css('cursor', 'pointer');
        } else if (purpose == 'hide') {
            next_button.attr('disabled', true);
            next_button.css('background', 'none repeat scroll 0% 0% #9E9E9E');
            next_button.css('cursor', 'default');
        }    
    }
    
}

function visa_card_selector_handler_show(obj, purpose) {
    var select_visa_step_1 = 'sell_payment_systems[wt_visa_usd]';
    var select_visa_step_2 = 'buy_payment_systems[wt_visa_usd]';
    var selector_name = $('.select-visa-card');
    var visa_old_input = $('.div_user_payment_data_save.wt_visa_usd');
    var step_1 = $('.left_container_payment_buy_text.step_1');
    var step_2 = $('.left_container_payment_buy_text.step_2');
    var cards = $('.select-visa-card select option');
    var modal_visa_informer = $('#modal_visa_informer');
    
    if(purpose == 'show')
        selector_name.show();
    else if(purpose == 'hide') {
        selector_name.hide();
        visa_old_input.hide();
    }

    if(obj) {
        if(obj.attr('name') == select_visa_step_2 
            && obj[0].checked == true 
            && step_2.is(':visible') ) {
            // проверяем что сейчас открыт ШАГ 2
            // в будущем при смене направления заявки, делать по этому примеру!

            // Скрываем поле для ввода номера карты
            visa_old_input.hide();
            if(cards.length > 0) {
                selector_name.show();
            } else {
                $('.next_button').eq(0).css('background-color', '#ccc');
                $('.next_button').eq(0).off();
                $('.next_button').eq(0).prop('disabled', true);

                $.post( get_cards_user,{v: 'select-2'},
                    function(resTxt){
                        var res = $.parseJSON(resTxt);
                        if( res['error'] == 1) {
                            $('.buy_fee_and_ammount').after('<div class="onehline leftgo visa_informer_error" style="display: block; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(238, 238, 238); height: 55px;"> <span style="color: red;">'+res['text']+'</span> <br> </div>');
                        }
                    }
                );
                //Если у пользователя нету карт, то выводим popup
                //modal_visa_informer.show(0).delay(5000).hide(0); 
            }

        } else if (obj.attr('name') == select_visa_step_1 
            && obj[0].checked == true ) {
    next_button_handler_active('step_1', 'hide');
            
            //step 1 OPEN visa selector
            visa_old_input.hide();
            if(cards.length > 0) {
                $('.right_container_payment_buy_text.step_1 .homeobmentable.quick_selected_payment_sell').after(selector_name);
                selector_name.show();
                // next_button_handler_active('step_1', 'hide');


            } else {
                //Если у пользователя нету карт, то выводим popup
                //modal_visa_informer.show(0).delay(5000).hide(0); 
            }

        } else if (obj.attr('name') == select_visa_step_1 
            && obj[0].checked == false) {
            //step 1 CLOSE visa selector
            selector_name.hide();

        } else if (obj.attr('name') == select_visa_step_2 
            && obj[0].checked == false) {

           selector_name.hide();

        }
    }
}

function send_save_payment_data_visa_card(card_number) {
    $.post( set_user_payment_data_url,
        { 'card_id': card_number },
        function(resTxt){
            var res = $.parseJSON(resTxt);
              
        }
    );
}

function set_text_modal_informer(text) {
    $('#modal_visa_informer').find('.content_container').html(text);
}

function get_step() {
    var input = $('.right_container_payment_buy_text.step_1');
    if(input.is(":visible")) {
        return 1;
    } else {
        return 2;        
    }
}

function is_selected_visa() {
    var money_input = $('.right_container_payment_buy_text.step_1 input[name="input_summa_sell_payment_systems[wt_visa_usd]"]');
    if(money_input.is(":visible")) {
        return true;
    } 
    return false;
}

function check_visa_money() {
    var money_input = $('.right_container_payment_buy_text.step_1 input[name="input_summa_sell_payment_systems[wt_visa_usd]"]');
    var card_id = 0; 
    var money   = 0;
    if(money_input.is(":visible")) {
        //step 1
        money = money_input.val();
        card_id = $( ".select-visa-card select option:selected").val();
        next_button_handler_active('step_1', 'hide');
    } else {
        //step 2
    }
    
    $.post( check_visa_money_url,
        { 'card_id': card_id, 'money' : money },
        function(resTxt){
            var res = $.parseJSON(resTxt);
                $('.visa_informer_error').remove();
            if(res['error'] == 1 && res['show'] == 1) {

                var error_div = $('.sell_fee_and_ammount .onehline.leftgo').eq(0);

                var error = '<div class="onehline leftgo visa_informer_error" style="display: block; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(238, 238, 238); height: 55px;"> <span  style="color: red;">' +res['text'] + '</span> <br> </div>';
                error_div.before(error);

                // $('#modal_visa_informer').show(0).delay(5000).hide(0);                 
            } else if (res['success'] == 1 ) {

                $('.visa_informer_error').hide();
                $('#sell_amount_error').parent().remove();
                next_button_handler_active('step_1', 'show');

                //TODO delete it
                // prefund_init();
            }
        }
    );
}



function load_visa_cards_number() {
    $.post( get_cards_user,{v: 'select-3'},
        function(resTxt){
            var res = $.parseJSON(resTxt);
            
            if( res['success'] == 1 ) {
                container =  $('.left_container_payment_buy_text.step_2');
                text = '<div class="col select-visa-card right_container_payment_buy_text" style="margin-bottom: 15px; padding-left: 10px; width: 330px; display:none"><div style="margin-bottom:5px">'+res['text']+':</div><select class="form_select " style="width: 100%; margin:0">';

                text_for_prefund = '<div style="margin-bottom:5px">'+res['text']+':</div><br><br><select class="form_select prefund_sell_input" style="width: 100%; margin:0">';


                for (var i = 0 ; i < res['result'].length; i++) {
                    result_line = '<option value="'+res['result'][i]['id']+'">'+res['result'][i]['name']+'</option>';
                    text+= result_line;
                    text_for_prefund+= result_line;

                };
                text += '</select></div>';
                text_for_prefund += '</select></div>';

                // При выборе visa появляется select
                container.after(text);
                container.after('<div class="clear"></div>');

                // Этот select добавим в конце заявки, при перечисление в prefund
                $('#modal_visa_prefund .selector_visa_card_to_prefund').html(text_for_prefund);

                //Изначально ставим первую карту 
                if(res['result'].length > 0) {
                    send_save_payment_data_visa_card(res['result'][0]['id']);
                }

                $( ".select-visa-card select" ).change(function() {
                    var now_step = get_step();
                    if(now_step == 1) {
                        check_visa_money();
                    } else if (now_step == 2) {
                        var selected_value = $( ".select-visa-card select").val();
                        send_save_payment_data_visa_card(selected_value);
                    }

                });
            } else {
                // Если у пользователя нету карт, то устнавливаем текст, но не выводим.
                // Вывод этого всплывающего окна будет в функции visa_card_selector_handler_show
                // set_text_modal_informer(res['text']);
                // $('#modal_visa_informer .content_container').html();



            }
        }
    );
}

function load_visa_cards_full_numbers() {
    $.post(get_cards_user + '_full',{v: 'select-4'},
        function(resTxt){
            var res = $.parseJSON(resTxt);

            $( ".select-visa-card select option" ).each(function( index ) {
                // console.log( index + ": " + $( this ).text() );
});

            
        }
    );
}


function confirme_order__check_money_func(order_id, card_id) {
    $('#select_payment_visa_card .loading-gif').show();
    $('#select_payment_visa_card #confirmation_block').css('background-color', '#ccc');
    $('#select_payment_visa_card #confirmation_block').attr('onclick','return false;');

    $.post( check_visa_money_from_order_url,
        { 'order_id': order_id, 'card_id' : card_id },
        function(resTxt){
            var res = $.parseJSON(resTxt);
            if(res['success'] == 0) {
                $('#select_payment_visa_card .loading-gif').hide();
                if(res['show']) {
                    if($('.visa-selector-info').length > 0){
                        $('#select_payment_visa_card .visa-selector-info').text(res['text']);
                    } else {
                        $('#select_payment_visa_card .loading-gif').hide();

                        var p = '<p class="visa-selector-info" style="text-align: center;color: red;">' +res['text']+ '</p>';
                        $('#select_payment_visa_card .visa_card').after(p);
                    }
                }
            } else 
            {
                $('#select_payment_visa_card .loading-gif').hide();
                
                
                $('.visa-selector-info').text('');
                $('#select_payment_visa_card #confirmation_block').css('background-color', '#0085F3');
                $('#select_payment_visa_card #confirmation_block').attr('onclick','validate_and_send_confirmation_form_pay_visa_card($(this).parent(), this); return false;');
            }
        }
    );
}

function confirme_order__check_money() {
    var card_id  = $('#select_payment_visa_card .visa_card option:selected').val();
    var order_id = $('#select_payment_visa_card .origin-id-data').text();
    confirme_order__check_money_func(order_id, card_id);
}

function confirme_select_payment_visa_card($this)
{
    var getting_price = $this[0];
    var getting_price_1 = $(getting_price).find('td').eq(1)[0];
    var getting_price_2 = $(getting_price_1).find('div')[0];
    var getting_price_3 = $(getting_price_2).text();
    var price = parseInt(getting_price_3) * 1.01;
    $('#select_payment_visa_card .price span').text(price);

    $order_id = $this.find('input');

    var base_line =$this[0];
    or_id = $(base_line).find('input[name="or_id"]')[0].value;
    $('.origin-id-data').text(or_id);



    confirme_order__check_money();
    $('#select_payment_visa_card select').off();
    $('#select_payment_visa_card select').change(function() {
        confirme_order__check_money();
    });


    show_popup_window_and_center_display($('#select_payment_visa_card'));
}

function validate_and_send_confirmation_form_pay_visa_card($form, el)
{
    $('#select_payment_visa_card .loading-gif').show();
    
    $('#select_payment_visa_card .form_select ').on('change', function(){
        $('#select_payment_visa_card .info-text-error').text('');
    })

    $global_confirmation_button = $form;

    if( el != undefined && $(el).hasClass('inactive') ) return false;
    mn.security_module
        .init()
        .show_window('create_get_p2p_orders')
        .done(function(res){
            if( typeof res['success'] === undefined ) return false;
            
            if( typeof res['code'] !== undefined )
            {
                var $form = $global_confirmation_button;
    
                $form.find('.form_code').remove();
                $form.append('<input class="form_code" type="hidden" value="1" name="code">');
                $form.find('.form_code').val( res['code'] );




                if(typeof res['code'] !== 'undefined') {
                    var card_id = $('.visa_card option:selected').val();
                    var order_id = $('.origin-id-data').text();
                    
                    $('#select_payment_visa_card #confirmation_block').css('background-color', '#ccc');
                    $('#select_payment_visa_card #confirmation_block').attr('onclick','return false;');

                    $.post( make_prefund_first_transaction_url,
                        { 'order_id': order_id, 'card_id' : card_id },
                        function(resTxt){
                            var res = $.parseJSON(resTxt);
                            $('#select_payment_visa_card .loading-gif').hide();
                            if(res['success'] == 1) {
                                $('#select_payment_visa_card .info-text-error').show();
                                $('#select_payment_visa_card').css('visibility','hidden');
                                //window.location.replace("/account/currency_exchange/my_sell_list");
                            } else {
                                $('#select_payment_visa_card .info-text-error').text(res['error']);
                                $('#select_payment_visa_card .info-text-error').show();
                            }
                        }
                    );
                }
            $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');


            }

            //////////////validate_and_send_confirmation_form($form);
        });
}

function get_money_prefund(id) {
	var order_id = $.trim(id);

    // alert(order_id);

	// $.post( prefund_take_money_url,
	//     { 'order_id': order_id },
	//     function(resTxt){
	//         var res = $.parseJSON(resTxt);
	//         if(res['error'] && res['show'] == 1 ) {
	//         	popup_window = '<div style="display: block;z-index: 1101;padding: 20px;" class="popup_window " id="p2p-informer-window-prefund"> <div class="close" onclick="$(this).parent().hide();"></div> <h2 style="text-align: center; font-size: 17px; line-height: 20px;">'+res['text']+'</h2> </div>';

	//         	$('#close-p2p-value').after(popup_window);
	//         } else {
	//         	// window.location.replace("/account/currency_exchange/my_sell_list_arhive");
	//         }

	//     }
	// );

	
}
