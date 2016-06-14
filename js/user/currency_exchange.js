// не забудте в шаблоне задать глобальную переменные.
// var curency_str = {840:'<?=_e('currency_symbol_840')?>', 643:'<?=_e('currency_symbol_643')?>'};
// var get_summ_and_fee_url = "<?=site_url('account/currency_exchange/ajax/get_summ_and_fee')?>";

var div_buy_glob_currency_id = 0;
var div_sell_glob_currency_id = 0;
var div_buy_payment_systems_checkbox = '';
var div_sell_payment_systems_checkbox = '';

var sell_glob_currency_id = 0;
var glob_currency_id = 0;

var global_filter_bw_style = "url(\"data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale\")"
var form_probem_message_for_operator = '';
var $global_confirmation_button = '';
var $global_confirmation_string = '';


if( sell_step === undefined ) var sell_step = 1;

function is_visa_in_pair( checkboxes )
{
    return;
    var visa = false;
    var count = 0;
    var ch, name;
    
    for( var i in checkboxes )
    {
        ch = $(checkboxes[i]);
        
        if( !ch.is(':checked') ) continue;
        console.log('is_visa_in_pair ch = ',ch);
        count ++;
        name = ch.prop('name');
        console.log('is_visa_in_pair name = ',name);
        if( name.indexOf('wt_visa_usd') !== -1 ) visa = true;
        
        if( visa && count > 1 ) return true;
    }
    
    return false;    
}

function confirm_out_submit_click(){
    var $this = $(this);
    var sms_input = $this.parent().find('input[name="sms"]');
    var $form = $('#sel_wt_form');
    var $loader = $this.parent().find('.loading-gif');

    if($('.select-visa-card select option:selected')) {
        var value = $('.select-visa-card select option:selected').val();
        $('.div_user_payment_data_save.wt_visa_usd input').attr("value", value);
    }
    

    //save_all_check_payment_system(1);

    $loader.show();

    var options = {
        'url': $ajax_check_user_for_payout,            
        success: function(responseText, statusText, xhr, $form){
                    var $res = $.parseJSON(responseText);
                    var window_error = $('.popup_window_error');
//                        
//                        $loader.hide();

                    if($res.res != 'ok')
                    {
                        window_error.find('span').html($res.text);
                        show_popup_window_and_center_display(window_error);
                    }
                    else
                    {
                        if(sms_input.is(':checked'))
                        {
                            $form.append('<input type="hidden" name="sms" value="1" />');
                        }
                        else
                        {
                            $form.append('<input type="hidden" name="sms" value="0" />');
                        }
                        $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');
                        
                        mn.security_module
                            .init()
                            .show_window('create_get_p2p_orders')
                            .done(function( res ){
                                
                                if(typeof res.res !== undefined && res.res == 'closed') return false;
                                
                                if( typeof res['success'] === undefined ) return false;

                                if( typeof res['code'] !== undefined ) $('#form_code').val( res['code'] );  
                                mn.security_module.loader.hide();
                                
                                $('#popup_debit').hide();

                                var sell_machine_name = $('.show_name_ps_in_table').eq(0).attr('title');
                                if(sell_machine_name == "Webtransfer VISA Card") {
                                    prefund_init();
                                } else {
                                    $form.submit();
                                }

                            });


                    }
                 }  // post-submit callback
    };

    $form.ajaxSubmit(options); 


};

if( div_buy_payment_systems_checkbox === undefined )
var div_buy_payment_systems_checkbox = $('.div_buy_payment_systems input[type="checkbox"][name^="buy_payment_systems"]');    

if( div_sell_payment_systems_checkbox === undefined )
var div_sell_payment_systems_checkbox = $('.div_sell_payment_systems input[type="checkbox"][name^="sell_payment_systems"]');

$(document).ready(function(){
    $('#sel_wt_form .righthomecol.div_sell_payment_systems .righthomecolvn .onehrine.first.active_group_tab[data-group-id=26] .dop_group_arrow').text('▼');
    $('#sel_wt_form .righthomecol.div_sell_payment_systems .righthomecolvn .onehrine.first.active_group_tab[data-group-id=3] .dop_group_arrow').text('▼');
    $('.lefthomecolvn .group_id_2').hide();
//    console.log($('.div_sell_payment_systems input[type="checkbox"][name^="sell_payment_systems"]'));
//    div_buy_payment_systems_checkbox = $('.div_buy_payment_systems input[type="checkbox"]');    
//    div_sell_payment_systems_checkbox = $('.div_sell_payment_systems input[type="checkbox"]');

    
    div_buy_payment_systems_checkbox = $('.div_buy_payment_systems input[type="checkbox"][name^="buy_payment_systems"]');    
    div_sell_payment_systems_checkbox = $('.div_sell_payment_systems input[type="checkbox"][name^="sell_payment_systems"]');

    
    get_checked_and_set_glob_payment_currency_id();
    
    $(document).on('click','#out_submit',function(){
        
        var is_not_set_checkbox_buy = set_red_border_for_buy_image_payment();
        var is_not_set_checkbox_sell = set_red_border_for_sell_image_payment();
        var is_not_set_payment_details = is_not_set_payment_system_details();
        
//        var is_not_set_wt = check_is_not_set_wt();
        
        set_val_for_input_siblings();
        
        var is_not_set_input = set_red_border_for_input();
        if(is_not_set_payment_details)
        {
            show_error($translate_js_error_fill_all_data); 
            
            return false;
        }
        
//        if(is_not_set_wt)
//        {
//            show_error($translate_js_error_select_wt_in_sell_or_by); 
//            
//            return false;
//        }
        
        if(!is_not_set_checkbox_buy && !is_not_set_checkbox_sell && !is_not_set_input)
        {    
//            $('#popup_debit').show('slow');
//            $('#popup_debit').show('slow');
            show_popup_window_and_center_display($('#popup_debit'));
        }
    });
    
    $('#out_submit_search').click(function(){

        var $this = $(this);
        $('#sel_wt_form input[name="all_orders"]').prop( "checked", false );
        out_submit_search($this);
    });
    
    
    $(document).on('click','#confirm_out_submit',confirm_out_submit_click );
    
    
    //!submit  button
    
    var sell_amount = $('#sell_amount');
    var sell_amount_fee = $('#sell_amount_fee');
    var sell_amount_total = $('#sell_amount_total');
    
    var buy_amount_down = $('#buy_amount_down');
    var buy_amount_up = $('#buy_amount_up');
    
    var sell_amount_down = $('#sell_amount_down');
    var sell_amount_up = $('#sell_amount_up');
    
    var timer = 0;
    
    $(document).on('keyup', 'input[name^="input_summa_sell_payment_systems"], input[name^="input_summa_buy_payment_systems"]', function(){
        var $this = $(this);
        if(timer != 0 )
        {
            clearTimeout(timer);
        }
        
        timer = setTimeout(function(){
            sell_amount_keyup($this);
        }, 500);
        
    });
    
    buy_amount_down.keyup(function(){
        var $this_s = $(this).siblings('input');
        $this_s.val($(this).val());
    });
    
    buy_amount_up.keyup(function(){
        var $this_s = $(this).siblings('input');
        $this_s.val($(this).val());
    });
    
    sell_amount_up.keyup(function(){
        var $this_s = $(this).siblings('input');
        $this_s.val($(this).val());
    });
    
    sell_amount_down.keyup(function(){
        var $this_s = $(this).siblings('input');
        $this_s.val($(this).val());
    });
    
    
    set_focus_to_input_field(sell_amount, 'sell_glob_currency_id');
    set_focus_to_input_field(sell_amount_fee, 'sell_glob_currency_id');
    set_focus_to_input_field(sell_amount_total, 'sell_glob_currency_id');
    
    div_buy_payment_systems_checkbox.each(function(){
        var $this = $(this);
        
        if($this.is(':checked'))
        {
            div_buy_glob_currency_id = $this.data('currency-id');
            return true;
        }
    });
    
    div_buy_payment_systems_checkbox.each(function(){
        var $this = $(this);
        show_hidden_fild_payment_system_data(div_buy_payment_systems_checkbox, $this);        
        add_payment_system_to_quick_list($this);
        
        hide_other_checkbox_if_select_wt(div_buy_payment_systems_checkbox, div_sell_payment_systems_checkbox);
    });
    
    
    
    div_buy_payment_systems_checkbox.change(function() {
        var $this = $(this);   
        
        if( is_visa_in_pair( div_buy_payment_systems_checkbox ) )
        {
            console.log('div_buy_payment_systems_checkbox / is_visa_in_pair TRUE');
         //   return false;
        }
        
        buy_payment_systems_checkbox_change($this)
//        hide_show_null_buy_ps();        
//        console.log('--buy--');
//        
//        if($this.attr('readonly') == 'readonly')
//        {
//            $this.prop('checked', false);
//            return false;
//        }
//        
//        show_only_one_checked_root_ps(div_buy_payment_systems_checkbox, $this);
//        
//        show_hidden_fild_payment_system_data(div_buy_payment_systems_checkbox, $this);
//        
//        add_payment_system_to_quick_list($this);
//        
//        if(div_buy_glob_currency_id == 0)
//        {
//            div_buy_glob_currency_id = $this.data('currency-id');
//        }
//
//        hide_other_checkbox_if_select_wt(div_buy_payment_systems_checkbox, div_sell_payment_systems_checkbox);
//        
//        get_checked_and_set_glob_buy_currency_id();        
//        div_buy_hide_other_curencyId();
//        div_sell_hide_other_curencyId();
//        
//        add_remove_curency_in_summ_field(sell_amount, false, sell_glob_currency_id);
//        
//        hide_all_unselected_dop_checkbox();
    });
// ######################################################################################3
// ######################################################################################3
// ######################################################################################3

    div_sell_payment_systems_checkbox.each(function(){
        var $this = $(this);
        
        if($this.is(':checked'))
        {
            div_buy_glob_currency_id = $this.data('currency-id');
            return true;
        }
    });
    
    div_sell_payment_systems_checkbox.each(function(){
        var $this = $(this);
        // убрано нам не нужно заполнять данные о платёжной системе покупателя это забота продовца
        show_hidden_fild_payment_system_data(div_sell_payment_systems_checkbox, $this);        
        add_payment_system_to_quick_list($this);
        
        hide_other_checkbox_if_select_wt(div_sell_payment_systems_checkbox, div_buy_payment_systems_checkbox);
    });
    
    
    
    div_sell_payment_systems_checkbox.change(function() {
        var $this = $(this); 
        if( is_visa_in_pair( div_sell_payment_systems_checkbox ) )
        {
            console.log('div_sell_payment_systems_checkbox / is_visa_in_pair TRUE');
            //return false;
        }
        sell_payment_systems_checkbox_change($this);
        
//        hide_show_null_sell_ps();        
//         console.log('--seell--');
//        if($this.attr('readonly') == 'readonly')
//        {
//            $this.prop('checked', false);
//            return false;
//        }
//        
//        show_only_one_checked_root_ps(div_sell_payment_systems_checkbox, $this);
//        
//        // убрано нам не нужно заполнять данные о платёжной системе покупателя это забота продавца
//        show_hidden_fild_payment_system_data(div_sell_payment_systems_checkbox, $this);
//    
//        add_payment_system_to_quick_list($this);
//        
//        if(div_buy_glob_currency_id == 0)
//        {
//            div_buy_glob_currency_id = $this.data('currency-id');
//        }
//
//        hide_other_checkbox_if_select_wt(div_sell_payment_systems_checkbox, div_buy_payment_systems_checkbox);
//        
//        get_checked_and_set_glob_sell_currency_id();
//        div_sell_hide_other_curencyId();
//        div_buy_hide_other_curencyId();
//        
//        add_remove_curency_in_summ_field(sell_amount, false, sell_glob_currency_id);
//        
//        hide_all_unselected_dop_checkbox();
    });

// ######################################################################################3
// ######################################################################################3
// ######################################################################################3
    
    $(document).on('click', '.exchange_action', function(){
        var $this = $(this);
        var input = $this.parent().parent().find('input[name="id"]');
        var popup_input = $('#popup_exchange')
                            .find('input[name="exchange_id"]')
                            .val(input.val());
        var $popup = $('#popup_exchange');
        
        $('#popup_exchange #exchange_confirm_buntton').show(); 
        $('#popup_exchange .result').html(''); 
        

        copy_from_table_search_payment_system_in_popup($popup, $this);
        show_popup_window_and_center_display($popup);
        
        return false;
    });
    
    $('.confirme_action').click(function(){
        var $this = $(this);
        var input = $this.parent().parent().find('input[name="id"]');
        var popup_input = $('#popup_confirme')
                            .find('input[name="exchange_id"]')
                            .val(input.val());
                    
        copy_timer_and_text_in_popup($('#popup_confirme'), $this);
        
        show_popup_window_and_center_display($('#popup_confirme'));
        
        return false;
    });
    
    
    $('.confirme_action_without_select_ps').click(function(){
        var $this = $(this);
        var input = $this.parent().parent().find('input[name="id"]');
        var popup_input = $('#popup_confirme_without_ps')
                            .find('input[name="exchange_id"]')
                            .val(input.val());
                    
        copy_timer_and_text_in_popup($('#popup_confirme_without_ps'), $this);
        
//        $('#popup_confirme_without_ps').show('slow');
        show_popup_window_and_center_display($('#popup_confirme_without_ps'));
        
        return false;
    });
    
    $('.cancel_action').click(function(){
        var $this = $(this);
        var input = $this.parent().parent().find('input[name="id"]');
        var popup_input = $('#popup_canceled')
                            .find('input[name="exchange_id"]')
                            .val(input.val());
        
//        $('#popup_canceled').show('slow');
        show_popup_window_and_center_display($('#popup_canceled'))
        
        return false;
    });



    $('#popup_exchange .close').click(function(){
        $(this).parent().hide();
        $(this).parent().find('.result').html('');
        $(this).parent().find('button .close').hide();
    });
    
    $('#popup_confirme .close').click(function(){
        $(this).parent().hide();
    });
    
    $('#exchange_confirm_buntton, #exchange_confirm_buntton_wt').click(function(){
        $global_confirmation_button = $(this);
        
        var obj = $(this);
        var $form = $(this).parent();
        var loader = obj.siblings('.loading-gif');
        var resulth_div = obj.siblings('.result');
        
        obj.hide();
        loader.show();
        resulth_div.html('');
        resulth_div.css('color', 'green');
        
        var $correct = $('input[name="correct"]');
        var $parent_correct = $correct.parent();
        
        $parent_correct.css('border','none')
        
        if($global_confirmation_button.length > 0 && $global_confirmation_button[0].id == 'exchange_confirm_buntton' && !$correct.is(':checked'))
        {
            $parent_correct.css('border','1px solid red');
            obj.show();
            loader.hide();
            return false;
        }
        
        var   options = {
            'url': send_check_buyer_rating,
            success:  function(responseText, statusText, xhr, $form){
                    var $res = $.parseJSON(responseText);
                    
                    loader.hide();
                    obj.show();
                    if($res.res == 'ok')
                    {
                        
                        mn.security_module
                            .init()
                            .show_window('create_get_p2p_orders')
                            .done(function( res ){
                                console.log('create_get_p2p_orders',res);
                                if( res['res'] != 'success' ) return false;
                                mn.security_module.loader.hide();
                                send_cinfirm_exchang_request_secure( res );
                            });
                        
                    }
                    else
                    {
                        resulth_div.html($res.text);
                        resulth_div.css('color', 'red');
                        obj.show();
                    }
                
                }
            }  // post-submit callback
        
        $form.ajaxSubmit(options); 
    });
    
    
    
    //включаем все чекбоксы после загрузки(на всякий случай)
    div_buy_hide_other_curencyId();
    
    add_remove_curency_in_summ_field(sell_amount, false, sell_glob_currency_id);
    add_remove_curency_in_summ_field(sell_amount_fee, false, sell_glob_currency_id);
    add_remove_curency_in_summ_field(sell_amount_total, false, sell_glob_currency_id);
    

    set_default_group_tab();
    $(document).on('click', ".save_user_payment_data", function(){
//    $(document).on('click', ".save_user_payment_data.settings", function(){
        save_user_payment_information($(this));
    });
    
//    $(document).on('focusout', ".save_user_payment_data_input:not(.settings)", function(){
////        cosole.log('>>>>');
//        var $button = $(this).siblings('.save_user_payment_data');
//        save_user_payment_information($button);
//        
//    });
    
    $(document).on('click', ".change_user_payment_data", function(){
        hide_load_button_for_user_payments($(this));
    });
    
    $(".save_new_user_payment_data").click(function(){
        save_user_new_payment_information($(this));
    });

    
//    $(document).on('click', '.button_curency_problem', function(){
//        var $this = $(this);
//        
//        $this.hide();
//        $this.parent().parent().find('.problem_chat_messages .answer-form').show();
//        return false;
//    });
    
    $('#popup_problem .close').click(function(){
       $(this).parent().hide('slow');
    });
    
    
    //TODO переделать.
//    $(document).on('change', '.quick_selected_payment input', function() {
    $(document).on('change', '.quick_selected_payment_buy input, .quick_selected_payment_sell input', function() {
        var $this = $(this);
        var name = $this.attr('name');
//        var checkbox = $('.div_buy_payment_systems input[name="'+name+'"]');
        var checkbox = '';
        var container_checkboxs = '';
        var second_container_checkboxs = '';
        var container = '';
        var container_text = '';
        
        console.log('452--');
        
        // Если вводится сумма в поле, то запускаем функцию на проверку наличия суммы на визе
        // if(is_selected_visa()) {
            // check_visa_money();
        // }


        if($this.is('input[name^=buy_payment_systems]'))
        {
            checkbox = $('.div_buy_payment_systems input[name="'+name+'"]');
            container_checkboxs = div_buy_payment_systems_checkbox;
            second_container_checkboxs = div_sell_payment_systems_checkbox;
            container =  $('.quick_selected_payment_buy');
            container_text =  $('.quick_selected_payment_buy_text');
        }
        else if($this.is('input[name^=sell_payment_systems]'))
        {
            checkbox = $('.div_sell_payment_systems input[name="'+name+'"]');
            container_checkboxs = div_sell_payment_systems_checkbox;
            second_container_checkboxs = div_buy_payment_systems_checkbox;
            container =  $('.quick_selected_payment_sell');
            container_text =  $('.quick_selected_payment_sell_text');
        }
        else
        {
            return false;
        }
        
        $this.parent().next('.input_container_div').remove();
        $this.parent().remove();
        
        if(!container.find('input').length)
        {
            container_text.hide();
        }
        checkbox.prop('checked', false);
//        show_hidden_fild_payment_system_data(div_buy_payment_systems_checkbox, checkbox);        
        show_hidden_fild_payment_system_data(container_checkboxs, checkbox);
        hide_other_checkbox_if_select_wt(container_checkboxs, second_container_checkboxs);
        hide_all_unselected_dop_checkbox();
        
        //hide null payment system
        hide_show_null_buy_sell_ps();        
    });
    
    $('select[name="problem_id"]').change(function(){
        var $this = $(this).find('option:selected');
        var machine_name = $this.data('machine-name');
        
        if(machine_name == 'other')
        {
            $this.parent().siblings('input[name="problem_subject"]').show();
        }
        else
        {
            $this.parent().siblings('input[name="problem_subject"]').hide();
        }
    });
    
    $(document).on('click','.table_row_header.htitle',function(){
        show_dop_order_data($(this));
//        var $this = $(this),
//            body = $(this).next();
//        
//        if( $(this).hasClass('active') ){
//            $this.removeClass('active');
//            body.hide();
//            return false;
//        }
//
//        body.show();
//        
//        $this.addClass('active');
//
//        order_to_completion_remains(body);
//        order_to_completion_remains_2(body);
//        
//        show_all_chat_messages(body);
    });
    
    
    //запрещаем отправку формы по нажатию ентер
    $('#sel_wt_form').keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    
    
    $(document).on('change','#popup_confirme.valid_sendconfirmation input', function(){
        activation_button_submit($(this).parent());
    });
    
    $(document).on('change','#popup_select_payment_system.valid_sendconfirmation input', function(){
        activation_button_submit($('#popup_select_payment_system.valid_sendconfirmation form'));
    });
   
    
    
    $(document).on('click','#sel_wt_form input[type="checkbox"]', function(){        
        if($(this).attr('readonly') == 'readonly')
        {
            show_note($translate_js_error_current_time_only_wt);
        }
        
    });
    
    
    open_order_by_hash();
    open_order_by_hash_oid();
    open_order_by_hash_or_id();
});


function confirm_order__finish($this) {
    $global_confirmation_button = $($this);
    $('.loading-gif').show();
    $form = $($this).parent();
    var   options = {
        'url': send_check_buyer_rating,
        success:  function(responseText, statusText, xhr, $form){
                var $res = $.parseJSON(responseText);
                $('.loading-gif').hide();
                
                if($res.res == 'ok')
                {
                    mn.security_module
                        .init()
                        .show_window('create_get_p2p_orders')
                        .done(function( res ){
                            console.log('create_get_p2p_orders',res);
                            if( res['res'] != 'success' ) return false;
                            mn.security_module.loader.hide();
                            send_cinfirm_exchang_request_secure( res );
                        });
                } else {

                    $('#popup_exchange .result').eq(0).css('color', 'red');
                    $('#popup_exchange .result').eq(0).text($res.text);
                }
            
            }
        } 
    
    $form.ajaxSubmit(options); 
}

function add_input_to_confirm_before_delete_order() {
    $('#popup_canceled form').append("<input type='text' name='delete_from_initiator' value='1' style='display:none'/>");
}

function sell_payment_systems_checkbox_change($this)
{
    hide_show_null_sell_ps();        
    console.log('--sell--');
  
    // можно выбрать только визу и без пар!
    // Если хоть что то выбранно то скрываем визу
    // Если выбрана виза то скрываем все


    // Отлючено использование дебита,
    // для включения закоментировать этот блок и такой же блок в buy_payment_systems_checkbox_change
    // и в контроллере по идентификатору #DETIT_OFF_2016_04_18 
    if($this.attr('name') == 'sell_payment_systems[wt_debit_usd]') {
        $('#null_give_ps').show();
        $this.prop('checked', false);
        show_note($translate_js_error_current_time_only_wt);
        console.log('--translate_js_error_current_time_only_wt--1');
        return false;
    }

    if($this.attr('readonly') == 'readonly')
    {
        $this.prop('checked', false);
        show_note($translate_js_error_current_time_only_wt);
        console.log('--translate_js_error_current_time_only_wt--1');
        return false;
    }

    show_only_one_checked_root_ps(div_sell_payment_systems_checkbox, $this);

    // убрано нам не нужно заполнять данные о платёжной системе покупателя это забота продавца
    show_hidden_fild_payment_system_data(div_sell_payment_systems_checkbox, $this);

    add_payment_system_to_quick_list($this);

    if(div_buy_glob_currency_id == 0)
    {
        div_buy_glob_currency_id = $this.data('currency-id');
    }

   hide_other_checkbox_if_select_wt(div_sell_payment_systems_checkbox, div_buy_payment_systems_checkbox);

    get_checked_and_set_glob_sell_currency_id();
    div_sell_hide_other_curencyId();
    div_buy_hide_other_curencyId();

    var sell_amount = $('#sell_amount');

    add_remove_curency_in_summ_field(sell_amount, false, sell_glob_currency_id);

    hide_all_unselected_dop_checkbox();
    visa_card_selector_handler_show($this);
}



function buy_payment_systems_checkbox_change($this)
{
    hide_show_null_buy_ps();        
    console.log('--buy--');


    // Отлючено использование дебита,
    // для включения закоментировать этот блок и такой же блок в sell_payment_systems_checkbox_change
    // и в контроллере по идентификатору #DETIT_OFF_2016_04_18 
    if($this.attr('name') == 'buy_payment_systems[wt_debit_usd]') {
        $('#null_take_ps').show();
        $this.prop('checked', false);
        show_note($translate_js_error_current_time_only_wt);
        console.log('--translate_js_error_current_time_only_wt--1');
        return false;
    }

    if($this.attr('readonly') == 'readonly')
    {
        $this.prop('checked', false);
        show_note($translate_js_error_current_time_only_wt);
        console.log('--translate_js_error_current_time_only_wt--2');
        return false;
    }

    show_only_one_checked_root_ps(div_buy_payment_systems_checkbox, $this);

    show_hidden_fild_payment_system_data(div_buy_payment_systems_checkbox, $this);

    add_payment_system_to_quick_list($this);

    if(div_buy_glob_currency_id == 0)
    {
        div_buy_glob_currency_id = $this.data('currency-id');
    }

    hide_other_checkbox_if_select_wt(div_buy_payment_systems_checkbox, div_sell_payment_systems_checkbox);

    get_checked_and_set_glob_buy_currency_id();        
    div_buy_hide_other_curencyId();
    div_sell_hide_other_curencyId();

    var sell_amount = $('#sell_amount');
    
    add_remove_curency_in_summ_field(sell_amount, false, sell_glob_currency_id);

    hide_all_unselected_dop_checkbox();

    visa_card_selector_handler_show($this);
}





function hide_show_null_sell_ps(){
    
    var is_check = check_sell_payment_systems_checkbox();
    //console.log( $('input[name="sell_payment_systems[wt]"]:checked') );
//    console.log('sell_step');
//    console.log(sell_step);
    if(typeof sell_step === 'undefined' || sell_step != 1 ) return false;
    if( is_check )
    {
        $('#null_give_ps').hide();
        if( $('input[name="sell_payment_systems[wt]"]:checked').length <= 0 ) 
        {
            $('#sell_amount_error').html( $translate_js_enter_amount );
        }
    }
    else{
        $('#null_give_ps').show();            
        
        $('.sell_fee_and_ammount').show();
        $('.sell_fee_and_ammount>.onehline.leftgo').show();
        
        $('#sell_amount_error').parent().show();
        $('#sell_amount_error').html( $translate_js_choose_ps );
        
        $('#sell_amount_fee').val('0');
        $('#sell_amount_total').val('0');
    }
}
function hide_show_null_buy_sell_ps()
{
    hide_show_null_buy_ps();
    hide_show_null_sell_ps();
}
function hide_show_null_buy_ps(){
    var is_check = check_buy_payment_systems_checkbox();

//    console.log('sell_step');
//    console.log(sell_step);

    if(typeof sell_step === 'undefined' || sell_step != 2 ) return false;
    if( is_check ){
        $('#null_take_ps').hide();
        if( $('input[name="buy_payment_systems[wt]"]:checked').length <= 0 )
        {
            $('#buy_amount_error').html( $translate_js_enter_amount );
        }

    }
    else{
        $('#null_take_ps').show();
        $('#buy_amount_error').parent().show();
        $('#buy_amount_error').html( $translate_js_choose_ps );

        $('.buy_fee_and_ammount').show();

        $('.buttons_next_prev').show();
        $('.buttons_next_prev>button:not(.button)').show();

        $('.buy_fee_and_ammount>.onehline.leftgo').show();

        $('#buy_amount_fee').val('0');
        $('#buy_amount_total').val('0');
    }
}

function hide_all_unselected_dop_checkbox()
{
    $('.quick_selected_payment_buy input, .quick_selected_payment_sell input').each(function(){
        var $this = $(this);
        var name = $this.attr('name');

        if($this.is('input[name^=buy_payment_systems]'))
        {
            checkbox = $('.div_buy_payment_systems input[name="'+name+'"]');

            if(!checkbox.is(':checked'))
            {
                $this.parent().next('.input_container_div').remove();
                $this.parent().remove();
            }
        }
        else if($this.is('input[name^=sell_payment_systems]'))
        {
            checkbox = $('.div_sell_payment_systems input[name="'+name+'"]');

            if(!checkbox.is(':checked'))
            {
                $this.parent().next('.input_container_div').remove();
                $this.parent().remove();
            }
        }
    });
}


function set_focus_to_input_field(obj, c_id)
{
    obj.focusin(function() {
        eval('var currency_id ='+c_id);
        add_remove_curency_in_summ_field($(this),true, currency_id);
    });

    obj.focusout(function() {
        eval('var currency_id ='+c_id);

        add_remove_curency_in_summ_field($(this),false, currency_id)
    });
}


function div_buy_hide_other_curencyId()
{
    //TODO пока убрана такая возможность вроде как не нужна.
    //hide_other_curency_id(div_buy_payment_systems_checkbox, 'div_buy_glob_currency_id');

    return false;
}

function div_sell_hide_other_curencyId()
{
    //TODO пока убрана такая возможность вроде как не нужна.
    //hide_other_curency_id(div_sell_payment_systems_checkbox, 'div_sell_glob_currency_id');

    return false;
}


function hide_other_curency_id(checkboxs, str_glob_currency_id)
{
    var glob_currency_id = 0;
    eval(' glob_currency_id = '+str_glob_currency_id+';');
    checkboxs.parent().find('.onehrineico').css('border', '1px solid transparent');

    if( glob_currency_id == 0 )
    {
        checkboxs
                .removeAttr('disabled')
                .removeAttr('readonly')
                .prop( "checked", false );
        ;
        checkboxs.parent().find('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
    }
    else
    {
        checkboxs.each(function(){
            var $this = $(this);
            var curency_id = $this.data('currency-id');
            if(curency_id != glob_currency_id)
            {
                $this
                    .attr('disabled', 'disabled')
                    .attr('readonly');

                var filter_style = global_filter_bw_style;
                $this.parent().find('.onehrineico').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
            }
        });
    }
}

//console.log(window['get_step']);
if( window['get_step'] === undefined )
{
    function get_step(){
        return 1;
    }
}
//function hide_other_checkbox_if_select_wt(chekboxs, seond_chekboxs)
//{
//    var $checked = chekboxs.filter(':checked');
//    var $second_wt = seond_chekboxs.filter('input[name$="[wt]"]');
//    var $second_not_wt = seond_chekboxs.not('input[name$="[wt]"]');
//
//    var $wt = chekboxs.filter('input[name$="[wt]"]');
//    var $not_wt = chekboxs.not('input[name$="[wt]"]');
//    var filter_style = global_filter_bw_style;
//
//    $not_wt.removeAttr('disabled').removeAttr('readonly');
//    $not_wt.siblings('.onehrineico').css('filter', 'none');
//
//    $second_wt.removeAttr('disabled').removeAttr('readonly');
//    $second_wt.siblings('.onehrineico').css('filter', 'none');
//
//    $second_not_wt.removeAttr('disabled').removeAttr('readonly');
//    $second_not_wt.siblings('.onehrineico').css('filter', 'none');
//
//    // Если чекнут вебтрансфер - деактивируем другие чекбоксы
//    if($checked.length && $checked.is('input[name$="[wt]"]'))
//    {
//        $not_wt
//            .prop( "checked", false )
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//
//        $not_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style);
//
//        // В соседних системах деактивируем вебтрансфер так как нет смысла менять вебрансфер на вебрансфер.
//        $second_wt
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//
//        $second_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style);
//    }
//    // Если чекнут не вебтрансфер - деактивируем чекбокс вебтрансфера
//    else if($checked.length && !$checked.is('input[name$="[wt]"]'))
//    {
//        $wt
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//
//        $wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style);
//
//        // В соседних системах деактивируем всё кроме вебрансфера.
//        $second_not_wt
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//
//        $second_not_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style);
//    }
//}


//function hide_other_checkbox_if_select_wt(chekboxs, second_chekboxs)
//{
//    var filter_style = global_filter_bw_style;
//
////    .attr('readonly', true);
////////////////////////////////////
//    var $checked = chekboxs.filter(':checked');
//    var $second_checkeds_filter = second_chekboxs.filter(':checked');
//
//    if(chekboxs.filter('input[name^="sell_payment_systems"]'))
//    {
//        var $select_country = $('.selec_country_container_sell_payment_systems select');
//        var $select_bank = $('.countent_country_overall_bank_select_sell_payment_systems select');
//
//        var $select_country_second = $('.selec_country_container_buy_payment_systems select');
//        var $select_bank_second = $('.countent_country_overall_bank_select_buy_payment_systems select');
//    }
//    else if(chekboxs.filter('input[name^="buy_payment_systems"]'))
//    {
//        var $select_country_second = $('.selec_country_container_sell_payment_systems select');
//        var $select_bank_second = $('.countent_country_overall_bank_select_sell_payment_systems select');
//
//        var $select_country = $('.selec_country_container_buy_payment_systems select');
//        var $select_bank = $('.countent_country_overall_bank_select_buy_payment_systems select');
//    }
//
//    var $not_wt = chekboxs.not(add_root_curr_in_selector('input[name$="[%s]"]'));
//    var $wt = chekboxs.filter(add_root_curr_in_selector('input[name$="[%s]"]'));
//
//    var $second_not_wt = second_chekboxs.not(add_root_curr_in_selector('input[name$="[%s]"]'));
//    var $second_wt = second_chekboxs.filter(add_root_curr_in_selector('input[name$="[%s]"]'));
//
//    $not_wt.removeAttr('disabled').removeAttr('readonly');
//    $not_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
//
//    $wt.removeAttr('disabled').removeAttr('readonly');
//    $wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
//
//    $second_not_wt.removeAttr('disabled').removeAttr('readonly');
//    $second_not_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
//
//    $second_wt.removeAttr('disabled').removeAttr('readonly');
//    $second_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
//
//    $select_country.attr('disabled', false);
//    $select_bank.attr('disabled', false);
//    $select_country_second.attr('disabled', false);
//    $select_bank_second.attr('disabled', false);
//
//    // Если чекнут вебтрансфер - деактивируем другие чекбоксы
//    if($checked.length && $checked.is(add_root_curr_in_selector('input[name$="[%s]"]')))
//    {
//        $not_wt
//            .prop( "checked", false )
////            .attr('disabled', 'disabled')
//            .attr('readonly', true);
//
//        $not_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//
//        $second_wt.prop( "checked", false )
//            .attr('disabled', 'disabled')
//            .attr('readonly', true);
//        $second_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//
//        $select_country.attr('disabled', 'disabled');
//        $select_bank.attr('disabled', 'disabled');
//    }
//    // Если чекнут не вебтрансфер - деактивируем чекбокс вебтрансфера
//    else if($checked.length && !$checked.is(add_root_curr_in_selector('input[name$="[%s]"]')) )
//    {
//        $wt
//            .attr('disabled', 'disabled')
//            .attr('readonly', true);
//
//        $wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//
//        $second_not_wt.prop( "checked", false )
////            .attr('disabled', 'disabled')
//            .attr('readonly', true);
//        $second_not_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//
//        $select_country_second.attr('disabled', false);
//        $select_bank_second.attr('disabled', false);
//    }
//}

function hide_other_checkbox_if_select_wt(chekboxs, second_chekboxs)
{
    var filter_style = global_filter_bw_style;

    var $checked = chekboxs.filter(':checked');
    var $not_checked = chekboxs.not(':checked');

    var $second_checkeds_filter = second_chekboxs.filter(':checked');



    if(chekboxs.filter('input[name^="sell_payment_systems"]'))
    {
        var $select_country = $('.selec_country_container_sell_payment_systems select');
        var $select_bank = $('.countent_country_overall_bank_select_sell_payment_systems select');

        var $select_country_second = $('.selec_country_container_buy_payment_systems select');
        var $select_bank_second = $('.countent_country_overall_bank_select_buy_payment_systems select');

//        var checkboxs_is = 'sell';
    }
    else if(chekboxs.filter('input[name^="buy_payment_systems"]'))
    {
        var $select_country_second = $('.selec_country_container_sell_payment_systems select');
        var $select_bank_second = $('.countent_country_overall_bank_select_sell_payment_systems select');

        var $select_country = $('.selec_country_container_buy_payment_systems select');
        var $select_bank = $('.countent_country_overall_bank_select_buy_payment_systems select');

//        var checkboxs_is = 'buy';
    }

    var $not_wt = chekboxs.not(add_root_curr_in_selector('input[name$="[%s]"]'));
    var $wt = chekboxs.filter(add_root_curr_in_selector('input[name$="[%s]"]'));

    var $second_not_wt = second_chekboxs.not(add_root_curr_in_selector('input[name$="[%s]"]'));
    var $second_wt = second_chekboxs.filter(add_root_curr_in_selector('input[name$="[%s]"]'));

    $not_wt.removeAttr('disabled').removeAttr('readonly');
    $not_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');

    $wt.removeAttr('disabled').removeAttr('readonly');
    $wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');

    $second_not_wt.removeAttr('disabled').removeAttr('readonly');
    $second_not_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');

    $second_wt.removeAttr('disabled').removeAttr('readonly');
    $second_wt.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');

    $select_country.attr('disabled', false);
    $select_bank.attr('disabled', false);
    $select_country_second.attr('disabled', false);
    $select_bank_second.attr('disabled', false);




    //##########################################################################
//    if(!$checked.length)
//    {
//        return false;
//    }
//    console.log(chekboxs.filter(':checked'));
    //делаем серыми те иконки Купить, которые уже выделены в Продать
    chekboxs.filter(':checked').each(function(){
        var $this = $(this);
        var ps_name = $this.data('ps-mashin-name');

        second_chekboxs.filter('input[name$="['+ps_name+']"]').prop( "checked", false )
            .attr('readonly', true);
        second_chekboxs.filter('input[name$="['+ps_name+']"]')
            .siblings('.onehrineico')
            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
    });

//    console.log(second_chekboxs.filter(':checked'));
    //делаем серыми те иконки Продать, которые уже выделены в Купить
    second_chekboxs.filter(':checked').each(function(){
        var $this = $(this);
        var ps_name = $this.data('ps-mashin-name');

        chekboxs.filter('input[name$="['+ps_name+']"]').prop( "checked", false )
            .attr('readonly', true);
        chekboxs.filter('input[name$="['+ps_name+']"]')
            .siblings('.onehrineico')
            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
    });

    //выделена купить ВТ
    //отключаем Продать ВТ
    if($checked.length && $checked.is(add_root_curr_in_selector('input[name$="[%s]"]')))
    {
        console.log('$second_wt',$second_wt);
        $second_wt.prop( "checked", false )
            .attr('disabled', 'disabled')
            .attr('readonly', true);

        $second_wt
            .siblings('.onehrineico')
            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
/*
        $wt.each(function(){
            if( $(this) == $checked ) return false;

            $(this).prop( "checked", false )
                .attr('disabled', 'disabled')
                .attr('readonly', true);

            $(this)
                .siblings('.onehrineico')
                .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
        });
*/

        $select_country.attr('disabled', 'disabled');
        $select_bank.attr('disabled', 'disabled');
    }
    else
    //выделено Купить неВТ
    //отключаем Продать ВТ
    if($checked.length && !$checked.is(add_root_curr_in_selector('input[name$="[%s]"]')) )
    {
        $wt
            .attr('disabled', 'disabled')
            .attr('readonly', true);

        $wt
            .siblings('.onehrineico')
            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
    /*
        $not_wt.each(function(){
            if( $(this) == $checked ) return false;

            $(this).prop( "checked", false )
                .attr('disabled', 'disabled')
                .attr('readonly', true);

            $(this)
                .siblings('.onehrineico')
                .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
        });
    */
        $select_country_second.attr('disabled', false);
        $select_bank_second.attr('disabled', false);




        // Если выбрана виза то запрещаем все другие пары соседние визе
        if($($checked[0]).attr('name') == 'sell_payment_systems[wt_visa_usd]') {
            $inputs_left = $('.righthomecol.div_sell_payment_systems.step_1').eq(0).find('input');
            $($inputs_left).attr('disabled', 'disabled');
            $($inputs_left).attr('readonly', true);

            $($inputs_left).siblings('.onehrineico').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');

            $input_visa = $('.righthomecol.div_sell_payment_systems.step_1').eq(0).find('input[name="sell_payment_systems[wt_visa_usd]"]');
            $($input_visa).attr('disabled', false);
            $($input_visa).attr('readonly', false);


            // Так же если у нас выбранна виза то разрешаю выбор любой валюты
            // Данный код позволяет при выборе buyer currency всегда видеть активной визу
            // #neWt_neWt_off___visa_neWt_on__2016_04_18
            // так же смотреть в контроллере. эту строку закоментировать.
            $checked.length = 0;



        } else {

            if(get_step() == 1)  {
                // Если у нас выбранно что либо но не виза, то мы закрываем возможность выбирать нам визу

                $('.righthomecol.div_sell_payment_systems.step_1 .group_id_26 input[name=sell_payment_systems\\[wt_visa_usd\\]]').attr('disabled', 'disabled').attr('readonly', true);
                $('.righthomecol.div_sell_payment_systems.step_1 .group_id_26 input[name=sell_payment_systems\\[wt_visa_usd\\]]').parent().find('.onehrineico.sprite_payment_systems').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');

            } else if (get_step() == 2) {
                // Предпологается что тут нужно скрывать визу если выбрана любая валюты в правой колонке
                $('.lefthomecol.div_buy_payment_systems.step_2 .group_id_3 input[name=buy_payment_systems\\[wt_visa_usd\\]]').attr('disabled', 'disabled').attr('readonly', true);
                $('.lefthomecol.div_buy_payment_systems.step_2 .group_id_3 input[name=buy_payment_systems\\[wt_visa_usd\\]]').parent().find('.onehrineico.sprite_payment_systems').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
                need_hide_visa = true;

                //$inputs_right = $('.lefthomecol.div_buy_payment_systems.step_2').eq(0).find('input');
                //$($inputs_right).attr('disabled', 'disabled');
                //$($inputs_right).attr('readonly', true);
                //
                //$($inputs_right).siblings('.onehrineico').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
            }




        }





    }

//    console.log('>>>>>>>>>>>>');
////    console.log(checkboxs_is);
//    console.log($checked.length);
//    console.log(!$checked.is(add_root_curr_in_selector('input[name$="[%s]"]')));
//    console.log('############');
//    console.log(chekboxs.first().is('input[name^="sell_payment_systems"]'));
//    console.log(chekboxs);
//    console.log('############');
//    console.log(second_chekboxs);
//    console.log('############');

//    if(checkboxs_is == 'sell' && $checked.length > 1 && !$checked.is(add_root_curr_in_selector('input[name$="[%s]"]')))
    //выделена Продать неВТ
    //Отключаем Купить не ВТ
    if(chekboxs.first().is('input[name^="sell_payment_systems"]') &&
            $checked.length > 0 && //remove when open nonWT-nonWT > 1
            !$checked.is(add_root_curr_in_selector('input[name$="[%s]"]')))
    {
//        console.log($checked.length);
        $second_not_wt.prop( "checked", false )
            .attr('disabled', 'disabled')
            .attr('readonly', true);

        $second_not_wt
            .siblings('.onehrineico')
            .css('filter', filter_style)
            .css('-webkit-filter', 'grayscale(100%)');

        $select_country_second.attr('disabled', 'disabled');
        $select_country_second.attr('disabled', 'disabled');
    }

    // Данный код позволяет при выборе buyer currency всегда видеть активной визу
    // #neWt_neWt_off___visa_neWt_on__2016_04_18
    // так же смотреть в контроллере, эту строку закоментировать
    $('input[name=buy_payment_systems\\[wt_visa_usd\\]]').removeAttr("disabled");
    $('input[name=buy_payment_systems\\[wt_visa_usd\\]]').attr('readonly',false);



    return false;

}

//function hide_other_checkbox_if_select_wt(chekboxs, second_chekboxs)
//{
//    var filter_style = global_filter_bw_style;
//    
////////////////////////////////////
//    var $checked = chekboxs.filter(':checked'); 
//    var $second_checkeds_filter = second_chekboxs.filter(':checked'); 
//    var $not_wt = chekboxs.not('input[name$="[wt]"]');
//    var $wt = chekboxs.filter('input[name$="[wt]"]');
//    
////    $not_wt.removeAttr('disabled').removeAttr('readonly');
////    $not_wt.siblings('.onehrineico').css('filter', 'none');
////    
////    $wt.removeAttr('disabled').removeAttr('readonly');
////    $wt.siblings('.onehrineico').css('filter', 'none');
//    
//    
//     chekboxs.each(function(){
//        var $this = $(this);
//        var $name = $this.attr('name').split("[");
//        var $second_checked = second_chekboxs.filter('input[name$="'+$name[1]+'"]');
//        
//        if(!$second_checked.is(':checked'))
//        {
//            $this.removeAttr('disabled').removeAttr('readonly'); 
//            $this.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none'); 
//        }
//        
//     });
//    
//    // Если чекнут вебтрансфер - деактивируем другие чекбоксы
//    if($checked.length && $checked.is('input[name$="[wt]"]'))
//    {
//        $not_wt
//            .prop( "checked", false )
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//    
//        $not_wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//    }
//    // Если чекнут не вебтрансфер - деактивируем чекбокс вебтрансфера
//    else if($checked.length && !$checked.is('input[name$="[wt]"]') )
//    {
//        $wt
//            .attr('disabled', 'disabled')
//            .attr('readonly');
//    
//        $wt
//            .siblings('.onehrineico')
//            .css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//    }
///////////////////////////////////////////
//    
//    chekboxs.each(function(){
//        var $this = $(this);
//        var $name = $this.attr('name').split("[");
//        var $second_checked = second_chekboxs.filter('input[name$="'+$name[1]+'"]');
//
//        if($this.is(':checked'))
//        {
//            $second_checked.attr('disabled', 'disabled').attr('readonly');
//            $second_checked.siblings('.onehrineico').css('filter', filter_style).css('-webkit-filter', 'grayscale(100%)');
//        }
//        else if($name[1] != 'wt]' && !$second_checkeds_filter.is('input[name$="[wt]"]'))
//        {
//            $second_checked.removeAttr('disabled').removeAttr('readonly'); 
//            $second_checked.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none'); 
//        }
//        
//        if(!$this.is(':checked') && $name[1] == 'wt]' && !$second_checkeds_filter.length)
//        {
//            $second_checked.removeAttr('disabled').removeAttr('readonly'); 
//            $second_checked.siblings('.onehrineico').css('filter', 'none').css('-webkit-filter', 'none');
//        }
//    });
//           
//   
//}



function get_checked_and_set_glob_buy_currency_id()
{
    var isChecked = false;
    
    div_buy_payment_systems_checkbox.each(function(){
        var $this = $(this); 
        var currency_id = $this.data('currency-id');
        
        if($this.is(":checked"))
        {
            isChecked = true;
        }
        
        if($this.is(":checked"))
        {
           div_buy_glob_currency_id = currency_id;
           return false;
        }
    });
    
    if(!isChecked)
    {
        div_buy_glob_currency_id = 0;
    }
}

function get_checked_and_set_glob_sell_currency_id()
{
    var isChecked = false;
    
    div_sell_payment_systems_checkbox.each(function(){
        var $this = $(this); 
        var currency_id = $this.data('currency-id');
        
        if($this.is(":checked"))
        {
            isChecked = true;
        }
        
        if($this.is(":checked"))
        {
           div_sell_glob_currency_id = currency_id;
           return false;
        }
    });
    
    if(!isChecked)
    {
        div_sell_glob_currency_id = 0;
    }
}



function get_checked_and_set_glob_payment_currency_id()
{
    /*
    div_buy_payment_systems_checkbox.each(function(){
        var $this = $(this); 
        var currency_id = $this.data('currency-id');
        
           sell_glob_currency_id = currency_id;
    });
    */
   // Тут вт пока не меняем - потом посмотрим
//   sell_glob_currency_id = div_buy_payment_systems_checkbox.filter('input[name$="[wt]"]').data('currency-id');
   var root_system_buy = div_buy_payment_systems_checkbox.filter(add_root_curr_in_selector('input[name$="[%s]"]'));
   var root_system_sell = div_sell_payment_systems_checkbox.filter(add_root_curr_in_selector('input[name$="[%s]"]'));
   var root_system_sell_checked = root_system_sell.filter(':checked') 
   var root_system_buy_checked = root_system_buy.filter(':checked') 
   
   if(root_system_sell_checked.length)
   {
       sell_glob_currency_id = root_system_sell_checked.first().data('currency-id');
       
       return sell_glob_currency_id;
   }
   else if(root_system_buy_checked.length)
   {
       sell_glob_currency_id = root_system_buy_checked.first().data('currency-id');
       
       return sell_glob_currency_id;
   }
   
//   sell_glob_currency_id = div_buy_payment_systems_checkbox.filter(add_root_curr_in_selector('input[name$="[%s]"]')).first().data('currency-id');
   sell_glob_currency_id = root_system_sell.first().data('currency-id');
}


function add_remove_curency_in_summ_field(obj, focus, currency_id)
{
//    console.log('add_remove_curency_in_summ_field');
//    console.log(currency_id);
    var summ_str = parseFloat(obj.val());
    
    if(isNaN(summ_str))
    {
        summ_str = '';
    }
    
    if(currency_id == 0)
    {
        obj.val(summ_str);
        return false;
    }
    
    var $curency = curency_str[currency_id];
    
//    if($curency == '<span class="curency_id_heart_red">❤</span>' ||
//        $curency == '<span class=\'curency_id_heart_red\'>❤</span>' 
//        )
//    {
//        $curency = '❤';
//    }
    
    if(obj.parent().find('input[name="percent"]').val() == 1)
    {
        $curency = '%';
    }
    
    if(!focus && summ_str > 0)
    {
         summ_str += ' ' + $curency;
    }
    
//    console.log(obj.attr('type'))
//    obj.val(summ_str);
    obj.html(summ_str);
}


function sell_amount_keyup($this)
{
    var $form = $('#sel_wt_form');
    var $loader = $('.sell_fee_and_ammount').parent().find('.loading-gif');

    var sell_amount_fee = $('#sell_amount_fee');
    var sell_amount_total = $('#sell_amount_total');

    var buy_amount_fee = $('#buy_amount_fee');
    var buy_amount_total = $('#buy_amount_total');

    var $sell_amount_fee_cont = sell_amount_fee.parent();
    var $buy_amount_fee_cont = buy_amount_fee.parent();
    var input_percent = '<input type="hidden" name="percent" value="0"/>';

    var sell_amount_total_cont = sell_amount_total.parent().hide();
    var buy_amount_total_cont = buy_amount_total.parent().hide();

    var left_general_fee_cont = $buy_amount_fee_cont.parent().parent().parent();
    var right_general_fee_cont = $sell_amount_fee_cont.parent().parent().parent();

    var $error_field = $('#buy_amount_error');
    var $error_field_container = $('#buy_amount_error').parent();
    var $error_field_sell = $('#sell_amount_error');
    var $error_field_container_sell = $('#sell_amount_error').parent();

    var $submit_btn = $('#out_submit');
    if(!$sell_amount_fee_cont.find('input[name="percent"]').length)
    {
       $sell_amount_fee_cont.append(input_percent); 
    }

    if(!$buy_amount_fee_cont.find('input[name="percent"]').length)
    {
       $buy_amount_fee_cont.append(input_percent); 
    }

//    $loader.show();
    $error_field_container.hide();
    $error_field_container_sell.hide();


    // Если вводится сумма в поле, то запускаем функцию на проверку наличия суммы на визе
    if(is_selected_visa()) {
        check_visa_money();
    }

    var options = {
        url: get_summ_and_fee_url,
        success: function(responseText, statusText, xhr, $form){
            var $res = $.parseJSON(responseText);

            $loader.hide();

            if(!$res)
            {
                return false;
            }

            if($res.no_wt == 0)
            {
                if($res.error)
                {
                    $error_field_container.show();
                    $error_field.html($res.error);
                    $error_field_container_sell.show();
                    $error_field_sell.html($res.error);
                }

                var v = 1;                
                if( $res.fee != 1 ) v = $res.fee;
//                console.log('$res.fee ',$res.fee);
                $buy_amount_fee_cont.find('input[name="percent"]').val(v);                
                $sell_amount_fee_cont.find('input[name="percent"]').val(v);

                sell_amount_fee.val($res.fee);
                sell_amount_total.val($res.summ);

                buy_amount_fee.val($res.fee);
                buy_amount_total.val($res.summ);

                $sell_amount_fee_cont.show();
                $buy_amount_fee_cont.show();
                sell_amount_total_cont.show();
                buy_amount_total_cont.show();

//                left_general_fee_cont.css('float', 'left').css('margin','0');
//                right_general_fee_cont.css('float', 'left').css('margin','0');
                left_general_fee_cont.css('float', 'right').css('margin','0');
                right_general_fee_cont.css('float', 'left').css('margin','0');
            }
            else
            {
                if($res.error)
                {
                    $error_field_container_sell.show();
                    $error_field_sell.html($res.error);
                    
                    if(sell_step !== undefined && sell_step == 2)
                    {
                        show_error('<br/>'+$res.error);
                    }
                }
                
                left_general_fee_cont.css('float', 'none').css('margin','0 auto');
                right_general_fee_cont.css('float', 'none').css('margin','0 auto');

//                console.log('$res.percentage ',$res.percentage);
                var v = 1;                
                if( $res.percentage != 1 ) v = $res.percentage;                
                $buy_amount_fee_cont.find('input[name="percent"]').val(v);                
                $sell_amount_fee_cont.find('input[name="percent"]').val(v);

                sell_amount_fee.val($res.percentage);
                buy_amount_fee.val($res.percentage);
            }
            
            add_remove_curency_in_summ_field(sell_amount_fee, false, sell_glob_currency_id);
            add_remove_curency_in_summ_field(sell_amount_total, false, sell_glob_currency_id);

            add_remove_curency_in_summ_field(buy_amount_fee, false, sell_glob_currency_id);
            add_remove_curency_in_summ_field(buy_amount_total, false, sell_glob_currency_id);

            show_fee_field_in_sell_list_if_select_wt();
        }  // post-submit callback
    }

    $form.ajaxSubmit(options); 
}

// TODO скорее всего не понадобится больше.
function buy_amount_keyup($this)
{
    return false;
    var $val = parseInt($this.val());
    var buy_amount_fee = $('#buy_amount_fee');
    var buy_amount_total = $('#buy_amount_total');
    var ps_machine_name = $('input[name="payment_system_machine_name"]').val();
    
    if(!buy_amount_fee || !buy_amount_total)
    {
        return false;
    }
    
    if( !($val > 0) )
    {
        return false;
    }

    var $buy_sum_fee = get_fee($val);
    var $buy_sum_total = $val + $buy_sum_fee;
    
    buy_amount_fee.val($buy_sum_fee);
    buy_amount_total.val($buy_sum_total);

    add_remove_curency_in_summ_field(buy_amount_fee, false, sell_glob_currency_id);
    add_remove_curency_in_summ_field(buy_amount_total, false, sell_glob_currency_id);
}

function check_buy_payment_systems_checkbox(){
    var is_check = div_buy_payment_systems_checkbox.is(':checked');
    is_check = $('#sel_wt_form input[name="all_orders"]').is(':checked') || is_check;
    return is_check;
}

function set_red_border_for_buy_image_payment()
{
    var is_check = check_buy_payment_systems_checkbox()
    
    var onehrineico = div_buy_payment_systems_checkbox.parent().find('.onehrineico');
    
    onehrineico.css('border', '1px solid transparent');
    
    if(!is_check)
    {
        div_buy_payment_systems_checkbox
            .parent()
            .find('.onehrineico')
            .css('border', '1px solid red');    

        show_error($translate_js_error_fill_no_select_ps);
        
        return true;
    }
    
    return false;
}

function check_sell_payment_systems_checkbox(){
    var is_check = div_sell_payment_systems_checkbox.is(':checked');
    is_check = $('#sel_wt_form input[name="all_orders"]').is(':checked') || is_check;
    return is_check;
}



function set_red_border_for_sell_image_payment()
{
    var is_check = check_sell_payment_systems_checkbox();
    
    var onehrineico = div_sell_payment_systems_checkbox.parent().find('.onehrineico');
    
    onehrineico.css('border', '1px solid transparent');
    
    if(!is_check)
    {
        div_sell_payment_systems_checkbox
            .parent()
            .find('.onehrineico')
            .css('border', '1px solid red');  
    
        show_error($translate_js_error_fill_no_select_ps, false);    
            
        return true;
    }
    
    return false;
}



function set_red_border_for_input()
{
    var input_summa_sell_payment_systems = $('input[name^="input_summa_sell_payment_systems"]').not('[type="hidden"]');
    var input_summa_buy_payment_systems = $('input[name^="input_summa_buy_payment_systems"]').not('[type="hidden"]');
    var empty_input = false;
    
    input_summa_sell_payment_systems.css('border', '1px solid #ddd');
    input_summa_buy_payment_systems.css('border', '1px solid #ddd');
    
    input_summa_sell_payment_systems.each(function (){
        var $this = $(this);
        
        if( !($this.val() > 0) )
        {
            $this.css('border', '1px solid red');
            
            empty_input = true;
        }
    });
    
    input_summa_buy_payment_systems.each(function (){
        var $this = $(this);
        
        if( !($this.val() > 0) )
        {
            $this.css('border', '1px solid red');
            
            empty_input = true;
        }
    });
    return empty_input;
}



function set_val_for_input_siblings()
{
    var sell_amount = $('#sell_amount');
    var sell_amount_s = $('#sell_amount').siblings('input');
    
    var buy_amount_down = $('#buy_amount_down');
    var buy_amount_down_s = $('#buy_amount_down').siblings('input');
    
    var buy_amount_up = $('#buy_amount_up');
    var buy_amount_up_s = $('#buy_amount_up').siblings('input');
    
    var sell_amount_down = $('#sell_amount_down');
    var sell_amount_down_s = $('#sell_amount_down').siblings('input');
    
    var sell_amount_up = $('#sell_amount_up');
    var sell_amount_up_s = $('#sell_amount_up').siblings('input');
    
    sell_amount_s.val(parseFloat(sell_amount.val()));
    
    buy_amount_down_s.val(parseFloat(buy_amount_down.val()));
    
    buy_amount_up_s.val(parseFloat(buy_amount_up.val()));
    
    sell_amount_down_s.val(parseFloat(sell_amount_down.val()));
    
    sell_amount_up_s.val(parseFloat(sell_amount_up.val()));
}


function set_default_group_tab()
{
    var div_sell_payment_systems = $('.div_sell_payment_systems');
    var tabs = div_sell_payment_systems.find('a.active_group_tab');
    var group_id = tabs.data('group-id');
    
    var div_buy_payment_systems = $('.div_buy_payment_systems');
    var tabs_buy = div_buy_payment_systems.find('.tabhome');
    
    tabs_buy.hide();
    
    tabs_buy.each(function(){
        var $this = $(this)
        if($this.data('group-id') == group_id)
        {
            $this.show();
        }
    });
}

function show_hide_user_new_payment_information(obj, selector)
{
    var data = obj.data('ps-mashin-name');
}

// TODO не нужна код перенесён в save_user_payment_information_secure
//function save_user_payment_information(obj)
//{
//    var obj_s = obj.siblings('.save_user_payment_data_input');
//    var payment_system = obj_s.data('payment-system');
//    var value = obj_s.val();
//    var obj_cont = obj.parent();
//    var loader = obj.siblings('.loading-gif');
//    
//    
//    if(!value)
//    {
//        var $ps_name = obj_cont.prev().find('.onehrinetext').html();
//        show_error($translate_js_error_fill_paymet_data.replace(/\%s/, $ps_name));
//        return false;
//    }
//    
//    loader.show();
//    
//    $.post(
//        save_user_payments_data_url,
//        {'value': value, 'payment_system' : payment_system},
//        function(resTxt){
//            loader.hide();
//            
//            show_load_button_for_user_payments(obj);
//        }
//    );
//}

function show_load_button_for_user_payments(obj)
{
    obj.hide();
    obj.siblings('.change_user_payment_data').show();
    var input = obj.siblings('input').attr('readonly',true);
    
    input.attr('readonly',true);
    input.css('border', '1px solid transparent');
    input.css('border-bottom', '1px solid #dddddd');
}

function hide_load_button_for_user_payments(obj)
{
    obj.hide();
    obj.siblings('.save_user_payment_data').show();
    var input = obj.siblings('input');
    
    input.attr('readonly',false);
//    input.css('border', '1px solid transparent');
    input.css('border', '1px solid #dddddd');
}



function save_user_new_payment_information(obj, $data)
{
    var obj_s = obj.siblings('.save_new_user_payment_data_input');
    var obj_cont = obj.parent();
    var loader = obj.siblings('.loading-gif');
    var div_text = obj.siblings('.ress');
    
    var send_data =  {
            'value':  obj_s.val(),
//            'group':  obj.siblings('[name="group"]').val()
            'group':  obj.siblings('.save_new_user_payment_data_input_group').val()
        };
    
    if( typeof $data == 'object' )
    {
        $.extend(send_data, $data);
    }
    
    loader.show();
    
    $.post(
        save_user_new_payments_data_url,
        send_data,
        function(resTxt){
            var res = $.parseJSON(resTxt);
            if(res.res == 'ok')
            {
//                obj_s.hide();
                obj.hide();
                obj.siblings('.hide_after_save').hide();
                div_text.html(res.text).show();
                
            }
            else
            {
//                div_text.html('<span style="color:red">'+res.text+'</span>').show();
                  show_error(res.text);
            }
                
            loader.hide()
        }
    );
}


function send_cinfirm_exchang_request(obj)
{
    
    var $form = obj.parent();
    var loader = obj.siblings('.loading-gif');
    var resulth_div = obj.siblings('.result');
    var $container = obj.parent().parent();
    
    console.log('send_cinfirm_exchang_request',obj);
    
    obj.hide();
    loader.show();
    resulth_div.html('');
    resulth_div.css('color', 'green');
    
     var options = {
            'url': send_cinfirm_exchang_request_url,
            success: function(responseText, statusText, xhr, $form)
            {
                var $res = $.parseJSON(responseText);
                var redirect_confirmation_url = redirect_confirmation_url_sell;
//                console.log($res.res);
                loader.hide();
                
//                resulth_div.html($res.text);
                
                if($res.res == 'ok')
                {
                     $container.hide();
                
                    var text = '<span style="color:green;"><b>'+$res.text+'</b></span><br/><img class=\'loading-gif\' src="/images/loading.gif"/>';
                    show_error(text, false);
                    
                    $('.popup_window_error').hide();
                    setTimeout(function(){window.location.href = redirect_confirmation_url;}, 5500);



//                $this.show();
//                $('#res_search_container').html('').append(table);
                }
                else
                {
                    console.log($res.text);
                    resulth_div.html($res.text);
                    resulth_div.css('color', 'red');
                    obj.show();
    //                ok.show();
                }
            }  
        };
    
    $form.ajaxSubmit(options);
}


function show_hidden_fild_payment_system_data(objects, obj)
{
    var next = obj.parent().next('.div_user_payment_data_save');
    var $span = next.find('.select_currency_span');
    var $payment_summa = obj.next('.div_user_payment_summa_save');
    var height = obj.data('js-parent-height')||76;
    
    if(obj.attr('readonly') == 'readonly')
    {
        return false;
    }
    
    $span.html('('+$translate_js_error_no_selec_currency+')');
    
    height += 'px';
    
    if($payment_summa.length)
    {
        objects.each(function(){
            var $this = $(this);

            if(!$this.is(':checked'))
            {
                $this.parent().css('height', '45px');
                $this.siblings('.div_user_payment_summa_save').hide();
            }
        });

        if(obj.is(':checked'))
        {
            obj.parent().css('height', '70px');
            $payment_summa.show();
        }
    }
    
    if(next.length)
    {
        objects.each(function(){
            var $this = $(this);

            if(!$this.is(':checked'))
            {
                $this.parent().css('height', '45px');
                $this.parent().next('.div_user_payment_data_save').hide();
            }
        });

        if(obj.is(':checked'))
        {
//            obj.parent().css('height', '66px');
//            obj.parent().css('height', '76px');
            obj.parent().css('height', height);
            next.show();
        }
    }
}

if(typeof $new_sell_glob === 'undefined' || $new_sell_glob != 1)
{
    function add_payment_system_to_quick_list(obj)
    {
        var container = '';
        var container_text = '';
        var image = obj.siblings('.onehrineico').clone();
        var obj_name = obj.attr('name');
        var currency_id = obj.data('currency-id');
        var currency = '';
        var obj_summa_val = obj.siblings('input[name="input_summa_'+obj_name+'"]').val();
        var container_a = $('<a class="onehrine" style="width: 45px; position: relative; float: left; border: 1px solid rgb(235, 235, 235); margin-right: 1px;"></a>');

        if(obj.is('input[name^=buy_payment_systems]'))
        {
            container =  $('.quick_selected_payment_buy');
            container_text =  $('.quick_selected_payment_buy_text');
        }
        else if(obj.is('input[name^=sell_payment_systems]'))
        {
            container =  $('.quick_selected_payment_sell');
            container_text =  $('.quick_selected_payment_sell_text');
        }
        else
        {
            return false;
        }

        if(currency_id == 840)
        {
            currency = 'USD';
        }

        if(currency_id == 643)
        {
            currency = 'RUB';
        }

        image.css('border', '1px solid transparent');
        
        container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div').remove();
        container.find('input[name="'+obj_name+'"]').parent().remove();


        if(!container.find('input').length)
        {
            container_text.hide();
        }

        if(obj.is(':checked'))
        {
            container_a.prepend('<input name="'+obj_name+'" data-currency-id="840" value="1" style="position: absolute; right: 1px; top: 1px;" type="checkbox" checked="checked" />') 
            container_a.prepend(image); 
            container_a.append('<div class="clear"></div>'); 

            container.append(container_a);
            if(typeof is_page_search != 'undefined' && is_page_search == true)
            {
                container.append('<div class="input_container_div" style="display:none;">'+$translate_js_summ+'<input type="text" value="1" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
            }
            else if(typeof str_paymen_preferences != 'undefined' && str_paymen_preferences == true)
            {
                var payment_data = obj.parent().next('.div_user_payment_data_save').html();
                container.append('<div class="input_container_div" style="padding-top: 13px;">'+payment_data+'</div>');
                container.append('<div class="clear"></div>');
            }
            else
            {
                container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' <input type="text" value="'+obj_summa_val+'" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
                container.append('<div class="clear"></div>');
            }

            container_text.show();
        }
//        else
//        {
//            container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div').remove();
//            container.find('input[name="'+obj_name+'"]').parent().remove();
//
//
//            if(!container.find('input').length)
//            {
//                container_text.hide();
//            }
//        }
    }
}


function quick_answer_hide(id)
{
    $('.button_curency_problem').each(function(){
        var $this = $(this);
        var data_id = $this.data('id');
        
        if(id == data_id)
        {
//            $this.show();
            var container = $this.parent().parent();
            container.find('.button_curency_problem').show();
            container.find('.problem_chat_messages .answer-form').hide();
//            $this.parent().parent().find('.problem_chat_messages .answer-form').hide();
            return false;
        }
    });
    
    return false;
}


function send_user_probem_message(btn)
{
    var $form = $(btn).parent();
    var $loader = $form.parent().siblings('.loading-gif');
    
    
    $loader.show(); 
    var options = { 
            success: function(responseText, statusText, xhr, $form){
                        var $res = $.parseJSON(responseText);
                        var window_error = $('.popup_window_error');
                        
                        $loader.hide();
                        
                        if($res.error == 1)
                        {
                            window_error.find('span').html($res.text);
                            window_error.show('slow');
                        }
                        else
                        {
                            $form[0].reset();
                            // TODO не нужно так как теперь не меняется статус заказа
//                            if($form.find('select[name="problem_id"]').length)
//                            {
//                                window.location.href = window.location.href;
//                            }
//                            else
//                            {
                                add_last_message_in_chat($form, $res.last_message);
//                            }
                        }
                     }  // post-submit callback
    };
    
     $form.ajaxSubmit(options); 
}


function add_last_message_in_chat($form, $res, $is_hide, $operator)
{
    var container = $form.parent().parent().find('.all_chat_messages');
    
    var message = html_message($res, $is_hide);
    
    if($operator == 1)
    {
        message = $(message);
        message.find('.th_message').css('background-color', 'moccasin');
    }
    
    container.append(message);
}


function html_message($res, $is_hide)
{   
    if($res.user_id == $user_id)
    {
        var $owner = $translate_js_you;
    }
    else
    {
        var $owner = $res.user;
    }
    
    var message = '';
    
    if($is_hide == true)
    {
        message  = '<div class="hide_message" style="display: none;">';
    }
    else
    {
        message  = '<div>';
    }
    
    if($res.operator == 1)
    {
        message += '    <div id="message'+$res.id+'" class="th_message employer" style="background-color:moccasin;">';
        if($res.user_id == 0)
        {
            
            $res.text = '<span class="message_to_operator">'+($res.is_group_message?$translate_js_message_from_operator_to_all:$translate_js_message_from_operator)+'</span><br>'+$res.text;
        }
        else
        {
            $res.text = '<span class="message_to_operator">'+$translate_js_message_to_operator+'</span><br>'+$res.text;
        }
    }
    else
    {
        message += '    <div id="message'+$res.id+'" class="th_message employer">';
    }
    message += '        <div class="author">'+$owner+':';
    message += '            <div class="short_date" title="Создано: '+$res.date+'">'+$res.date+'</div>';
    message += '        </div>';
    message += '        <div class="short_text">'+$res.text+'</div>';
    
    if($res.file)
    {
       message +='<div class="problem_chat_file_load">';
       message +='  <a href="/'+$res.file+'" target="_blank" style="display: block; line-height: 16px;"> '+$translate_js_attached_file+' <img src="/images/currency_exchange/download.png" style="width: 16px; display: block; float: left;" /></a>';
       message +='</div>';
    }
    
    message += '    </div>';
    message += '</div>';
    
    return message;
}



function show_all_chat_messages(body)
{
    var chat = body.find('.problem_chat_messages');
    
    if(!chat.length)
    {
        return false;
    }
    
    if(chat.find('.all_chat_messages div').length)
    {
        return false;
    }
    
    var id = chat.find('form input[name="exchange_id"]').val();
    var all_chat_messages = chat.find('.all_chat_messages');
    var loader = chat.find('.loading-gif');
    var $form = chat.find('form');
    
    loader.show();
    
    $.post(
        get_all_chat_messages_url,
        {'id': id },
        function(resTxt){
            var res = $.parseJSON(resTxt);
            
            if(res.error == 1)
            {
                var window_error = $('.popup_window_error');
                window_error.find('span').html($res.text);
                window_error.show('slow');
            }
            else
            {
                add_all_message_in_chat($form, res);
                body.prev().find('.currency_exchange_bell_for_chat').hide();
            }
            
            loader.hide();
        }
    );
}


function add_all_message_in_chat($form, res)
{
    var count = 0;
    var container = $form.parent().parent().find('.all_chat_messages');
    var res_size = Object.keys(res).length;
    var hiden_size = res_size - 2;
    var delimeter = '';
    var otvet = '';
    
    delimeter += '<div style="text-align:center;">';
//    delimeter += '<a href="#" class="table_green_button" onclick="show_all_hidden_message($(this), \'show\'); return false;" style="width:146px;">'+$translate_js_show_all_message+'</a>';
//    delimeter += '<a href="#" class="table_green_button" onclick="show_all_hidden_message($(this), \'hide\'); return false;" style="display: none; width:146px;">'+$translate_js_hide_message+'</a>';
    delimeter += '<a href="#" class="table_green_button" onclick="show_all_hidden_message($(this), \'show\'); return false;" >'+$translate_js_show_all_message+'</a>';
    delimeter += '<a href="#" class="table_green_button" onclick="show_all_hidden_message($(this), \'hide\'); return false;" style="display: none;">'+$translate_js_hide_message+'</a>';
    delimeter += '</div>';
    
    otvet += '<div style="text-align:center;">';
    otvet += '<a href="#" class="table_green_button button_curency_problem" onclick="button_curency_problem(($(this).parent().parent().parent().parent())); return false;" style="width: 136px; background-image: url(\'../../images/ui/usualButtons.png\'); background-position: 0px -170px; border: 1px solid #9F352B;" >'+$translate_js_otvet+'</a>';
    otvet += '</div>';
    
    $.each(res, function(e){
        count++;
        if( hiden_size < count)
        {
            add_last_message_in_chat($form, this);
        }
        else if(hiden_size == count)
        {
            add_last_message_in_chat($form, this, true);
            container.append(delimeter);
        }
        else
        {
            add_last_message_in_chat($form, this, true);
        }
        
        edit_show_not_read_messages_in_top_menu(this);
    });
    
    if(count > 0)
    {
        container.append(otvet);    
    }
}


function edit_show_not_read_messages_in_top_menu(res)
{
    var $current_not_read_span = $('.show_not_read_messages_span span');
    var $current_not_read = parseInt($('.show_not_read_messages_span span').html());
    var $current_span = $('.show_not_read_messages_span');
    
    if(res.show == 0)
    {
        $current_not_read--;
    }
    
    if($current_not_read <= 0)
    {
        $current_span.hide();
    }
    else
    {
        $current_not_read_span.html($current_not_read);
    }
}


function show_all_hidden_message(obj, action)
{
    var container = obj.parent().parent();
    
    if(action == 'show')
    {
        var objects = container.find('.hide_message').show();
    }
    else
    {
        var objects = container.find('.hide_message').hide();
    }
    
    obj.hide();
    obj.siblings('a').show();
}


function order_to_completion_remains(body)
{
    var container_d = body.find('.to_completion_remains_day');
    var container_h = body.find('.to_completion_remains_hours');
    var container_m = body.find('.to_completion_remains_min');
    var container_s = body.find('.to_completion_remains_sec');
    var cont = body.find('.timer_confirm');
    var $for_my_sell_list_table_dop = body.find('.for_my_sell_list_table_dop');
    var input_timer_confirm = body.find('input[name="input_timer_confirm"]').val();
    
//    var is_bank = body.find('input[name="ps_is_bank"]').val();
    var period = body.find('input[name="ps_period"]').val();
    
    if(!(period > 0))
    {
       return false; 
    }
    
    if(input_timer_confirm == 1)
    {
        return false;
    }
    
    if(container_s.html() != '')
    {
        return false;
    }
    
    var set_up_date = body.find('input[name="set_up_date"]').val();
    var curent_date = body.find('input[name="curent_date"]').val();
    
//    var period = 24*3600;
//    
//    if(is_bank == 1)
//    {
//        period *= 7;
//    }
    
    var $res = period - (curent_date-set_up_date);
    
    if($res <= 0)
    {
        container_h.html('00');
        container_m.html('00');
        container_s.html('00');
        return false;
    }
    
    var day = 0;
    
    if($res > 24*3600)
    {
        day = parseInt($res/(24*3600));
        $res  = $res-day*24*3600;
    }
    
    var hours = parseInt($res/3600);
    var min = parseInt(($res-hours*3600)/60);
    var sec = parseInt($res-(hours*3600+min*60));


    var h_null = '';
    var m_null = '';
    var s_null = '';

    if(hours < 10)
    {
        h_null = '0';
    }

    if(min < 10)
    {
        m_null = '0';
    }

    if(sec < 10)
    {
        s_null = '0';
    }

    if(day > 0)
    {
        container_d.html(day);
        container_d.parent().show();
    }
    else
    {
        container_d.parent().hide();
    }
    
    container_h.html(h_null+hours);
    container_m.html(m_null+min);
    container_s.html(s_null+sec); 

    $res--;
    
    cont.show();
    $for_my_sell_list_table_dop.hide();
    
    
    var timerId = setInterval(function(){
        if($res <= 0)
        {
            clearInterval(timerId);
        }

        var hours = parseInt($res/3600);
        var min = parseInt(($res-hours*3600)/60);
        var sec = parseInt($res-(hours*3600+min*60));

        var h_null = '';
        var m_null = '';
        var s_null = '';

        if(hours < 10)
        {
            h_null = '0';
        }

        if(min < 10)
        {
            m_null = '0';
        }

        if(sec < 10)
        {
            s_null = '0';
        }
        
        if(day > 0)
        {
            container_d.html(day);
            container_d.parent().show();
        }
        else
        {
            container_d.parent().hide();
        }

        container_h.html(h_null+hours);
        container_m.html(m_null+min);
        container_s.html(s_null+sec); 

        $res--;

    }, 1000);    

}

function order_to_completion_remains_2(body)
{
    var container_d = body.find('.to_completion_remains_day');
    var container_h = body.find('.to_completion_remains_hours');
    var container_m = body.find('.to_completion_remains_min');
    var container_s = body.find('.to_completion_remains_sec');
    var container_s = body.find('.to_completion_remains_sec');
    var $timer_1 = body.find('.timer_1');
    var $destination = body.find('.destination');
    var $after_send = body.find('.after_send');
    var $timer_2 = body.find('.timer_2');
    
    
    var cont = body.find('.timer_confirm');
    var $for_my_sell_list_table_dop = body.find('.for_my_sell_list_table_dop');
    var input_timer_confirm = body.find('input[name="input_timer_confirm"]').val();
    var input_timer_confirm_2 = body.find('input[name="input_timer_confirm_2"]').val();
    var is_bank = body.find('input[name="ps_is_bank"]').val();
    
    var $ps_period = body.find('input[name="ps_period"]').val();
    
    if(!($ps_period > 0))
    {
       return false; 
    }
    
    if(input_timer_confirm == 1  && input_timer_confirm_2 != 1)
    {
        return false;
    }    
    
    
    if(container_s.html() != '')
    {
        return false;
    }
    
    var set_up_date = body.find('input[name="set_up_date"]').val();
    var curent_date = body.find('input[name="curent_date"]').val();
    
//    var period = 24*3600;
//    
//    if(is_bank == 1)
//    {
//        period *= 7;
//    }
    var period = $ps_period;
    
    
    
    var $res = period - (curent_date-set_up_date);
    
    if($res <= 0)
    {
        container_h.html('00');
        container_m.html('00');
        container_s.html('00');
        return false;
    }
    
    var day = 0;
    
    if($res > 24*3600)
    {
        day = parseInt($res/(24*3600));
        $res  = $res-day*24*3600;
    }
    
    var hours = parseInt($res/3600);
    var min = parseInt(($res-hours*3600)/60);
    var sec = parseInt($res-(hours*3600+min*60));


    var h_null = '';
    var m_null = '';
    var s_null = '';

    if(hours < 10)
    {
        h_null = '0';
    }

    if(min < 10)
    {
        m_null = '0';
    }

    if(sec < 10)
    {
        s_null = '0';
    }

    if(day > 0)
    {
        container_d.html(day);
        container_d.parent().show();
    }
    else
    {
        container_d.parent().hide();
    }
    
    container_h.html(h_null+hours);
    container_m.html(m_null+min);
    container_s.html(s_null+sec); 

    $res--;
    
    $timer_1.hide();
    $destination.hide();
    $after_send.hide(); 
    $timer_2.show();
    
    cont.show();
    $for_my_sell_list_table_dop.hide();
    
    
    var timerId = setInterval(function(){
        if($res <= 0)
        {
            clearInterval(timerId);
        }

        var hours = parseInt($res/3600);
        var min = parseInt(($res-hours*3600)/60);
        var sec = parseInt($res-(hours*3600+min*60));

        var h_null = '';
        var m_null = '';
        var s_null = '';

        if(hours < 10)
        {
            h_null = '0';
        }

        if(min < 10)
        {
            m_null = '0';
        }

        if(sec < 10)
        {
            s_null = '0';
        }
        
        if(day > 0)
        {
            container_d.html(day);
            container_d.parent().show();
        }
        else
        {
            container_d.parent().hide();
        }

        container_h.html(h_null+hours);
        container_m.html(m_null+min);
        container_s.html(s_null+sec); 

        $res--;

    }, 1000);    

}



function copy_timer_and_text_in_popup($popup, $this)
{
    var body = $this.parent().parent().next();
    
    var content = body.find('.timer_confirm').clone();
    
    var container = $popup.find('.content_container');
    var $file =  $popup.find('.foto_container');
    container.html('');
    $file.show();
    
    if(content.find('input[name="buy_only_wt"]').length)
    {
        $file.hide();
    }
    
    content.find('.table_cell').hide();
    container.append(content);
    
    var $sell_container = $popup.find('.select_payment_syctem_container_sell');
    var select_system = $popup.find('.div_select_pay_sys_sell div.onehrine');
    
    $sell_container.html('');
    $sell_container.append(select_system);
    
    var $buy_container = $popup.find('.select_payment_syctem_container_buy');
//    select_system = $popup.find('.div_select_pay_sys_buy div.onehrine');
    select_system = $popup.find('.div_select_pay_sys_buy').contents();
    
    $buy_container.html('');
    $buy_container.append(select_system);
}


function show_last_user_confirm_block($form, block)
{
    var $loader = $form.find('.loading-gif');
    
    var content_container = block.find('.content_container');
    var content_container_contragen = block.find('.content_container_contragent');
    var input_id = block.find('input[name="confirm_id"]');
    
    var fio = content_container.find('.content_container_fio');
    var method = content_container.find('.content_container_method');
    var summ = content_container.find('.content_container_summ');
    
    var method_contragent = content_container_contragen.find('.content_container_method_contragent');
    var account_contragent = content_container_contragen.find('.content_container_account_contragent');
    
    $loader.show(); 
    
    fio.html('');
    method.html('');
    summ.html('');
    input_id.val('');
    
    method_contragent.html('');
    account_contragent.html('');
    
    var options = { 
        success: function(responseText, statusText, xhr, $form){
            var $res = $.parseJSON(responseText);
            var window_error = $('.popup_window_error');

            $loader.hide();

            if($res.error == 1)
            {
//                window_error.find('span').html($res.text);
//                window_error.show('slow');
                show_error($res.text);
            }
            else
            {
                fio.html($res.fio);
                method.html($res.method.name+' '+$res.method.currency);
                summ.html($res.method.summ+' '+$res.method.currency);
                input_id.val($res.id);
                
                method_contragent.html($res.method.contragent.name);
                account_contragent.html($res.method.contragent.accaunt);
                
                content_container.show();
                block.find('input[name="last_user_confirm_checkbox"]')
                        .prop('checked', false)
                        .parent()
                        .show();
                
//                if($res.method.machine_name == 'wt')
                if(is_root_currency($res.method.machine_name))
                {
                    content_container.hide();
                    
                    block.find('input[name="last_user_confirm_checkbox"]')
                        .prop('checked', true)
                        .parent()
                        .hide();
                }
                
                content_container_contragen.show();
                block.find('input[name="foto"]')
                        .data('m-name', '')    
                        .parent()
                        .show()
                        ;
                
//                if($res.method.contragent.machine_name == 'wt')
                if(is_root_currency($res.method.contragent.machine_name))
                {
                    content_container_contragen.hide();
                    
                    block.find('input[name="foto"]')
                        .data('m-name', 'wt')    
                        .parent()
                        .hide()
                                                ;
                }
                
                
//                block.show();
                show_popup_window_and_center_display(block)
            }
        }  // post-submit callback
    }
    
     $form.ajaxSubmit(options); 
}

function show_last_user_confirm_block_money_send($form)
{
    var $loader = $form.find('.loading-gif');
    
    var block = $('#last_user_confirm_block_money_send');
    var content_container_contragen = block.find('.content_container_contragent');
    var input_id = block.find('input[name="confirm_id"]');
    
    var method_contragent = content_container_contragen.find('.content_container_method_contragent');
    var account_contragent = content_container_contragen.find('.content_container_account_contragent');
    
    $loader.show(); 
    
    input_id.val('');
    
    method_contragent.html('');
    account_contragent.html('');
    
    var options = { 
        success: function(responseText, statusText, xhr, $form){
            var $res = $.parseJSON(responseText);
            var window_error = $('.popup_window_error');

            $loader.hide();

            if($res.error == 1)
            {
                window_error.find('span').html($res.text);
                window_error.show('slow');
            }
            else
            {
                input_id.val($res.id);
                
                method_contragent.html($res.method.contragent.name);
                account_contragent.html($res.method.contragent.accaunt);
                
                block.find('input[name="last_user_confirm_checkbox"]')
                        .prop('checked', false)
                        .parent()
                        .show();
                
//                if($res.method.machine_name == 'wt')
                if(is_root_currency($res.method.machine_name))                
                {
                    block.find('input[name="last_user_confirm_checkbox"]')
                        .prop('checked', true)
                        .parent()
                        .hide();
                }
                
                content_container_contragen.show();
                block.find('input[name="foto"]')
                        .data('m-name', '')    
                        .parent()
                        .show()
                        ;
                
                if(is_root_currency($res.method.contragent.machine_name))
                {
                    content_container_contragen.hide();
                    
                    block.find('input[name="foto"]')
                        .data('m-name', 'wt')    
                        .parent()
                        .hide()
                                                ;
                }
                
                
                block.show();
            }
        }  // post-submit callback
    }
    
     $form.ajaxSubmit(options); 
}



function delete_order_from_initiator($form)
{
    var id = $($form[0]).find('input[name=id]').val();
    $.post( delete_order_from_initiator_url ,{order_id: id},
        function(resTxt){
            var res = $.parseJSON(resTxt);
            console.log(res);

        }
    ); 
}



function show_real_last_user_confirm_block($form)
{
    var block = $('#real_last_user_confirm_block');
    show_confirm_lock($form, block);
}


function show_buyer_confirm_receipt_block($form)
{
    var block = $('#buyer_confirm_receipt');
//    console.log(block);
    show_confirm_lock($form, block);
}


function show_confirm_lock($form, block )
{
    var $loader = $form.find('.loading-gif');
    
//    var block = $('#real_last_user_confirm_block');
    var content_container = block.find('.content_container');
    var input_id = block.find('input[name="confirm_id"]');
    
    var fio = content_container.find('.content_container_fio');
    var method = content_container.find('.content_container_method');
    var summ = content_container.find('.content_container_summ');
    
    $loader.show(); 
    
    fio.html('');
    method.html('');
    summ.html('');
    input_id.val('');
    
    var options = { 
        success: function(responseText, statusText, xhr, $form){
            var $res = $.parseJSON(responseText);
            var window_error = $('.popup_window_error');

            $loader.hide();

            if($res.error == 1)
            {
                window_error.find('span').html($res.text);
                window_error.show('slow');
            }
            else
            {
                fio.html($res.fio);
                method.html($res.method.contragent.name+' '+$res.method.contragent.currency);
//                summ.html($res.summ+' '+$res.method.contragent.currency);
                summ.html($res.method.contragent.summ+' '+$res.method.contragent.currency);
                input_id.val($res.id);
                
                block.show();
            }
        }  
    };
    
    $form.ajaxSubmit(options); 
}


function load_more_orders(url, table, curent_obj)
{
    $curent_list++;
    var loader = curent_obj.siblings('.loading-gif')
    loader.show();
    curent_obj.hide();
    
    $.post(url, {'orders_on_list':$orders_on_list, 'curent_list':$curent_list}, function(res){
        
        var res_html = $.parseHTML(res);
        var res_tr = $(res_html).find('tbody tr');
        
        table.find('tbody').append(res_tr);
        loader.hide();
        
        if(res_tr.length && $orders_on_list*2 == res_tr.size())
        {
            curent_obj.show();
        }
        if( mn.left_side_last_deals !== undefined && mn.left_side_last_deals.resize !== undefined ){
                mn.left_side_last_deals.resize();
       }
    });
}


function get_fee($amount)
{
    var $total = $amount * ps_fee_data.fee_percentage / 100.0 + ps_fee_data.fee_percentage_add;
    
    if( ps_fee_data.fee_min > 0 && $total < ps_fee_data.fee_min )
    {
        $total = ps_fee_data.fee_min;
    }
    if( ps_fee_data.fee_max > 0 && $total > ps_fee_data.fee_max )
    {
        $total = ps_fee_data.fee_max;
    }
    
    return $total;
}

function togle_and_switch_text(event, obj, arraw_obj)
{
    obj.toggle('fast',function(){
        var $this = $(this);
        if($this.css('display') == 'none')
        {
            arraw_obj.html('&#9658;');
        }
        else
        {
            arraw_obj.html('&#9660;');
        }
    });
}


function show_error(text, show_title)
{
    var $container = $('.popup_window_error');
    var title = $container.find('h2');
    var vice_title = $container.find('.vice_title');
    
    title.show();
    vice_title.hide();
    
    if(typeof show_title !== 'undefined' && show_title === false)
    {
        vice_title.show();
        title.hide();
    }
    
    $container.find('span').html(text);
    
//    $container.show('slow');
    show_popup_window_and_center_display($container);
}

function show_note(text)
{
    var $container = $('.popup_window_notification');
    
    $container.find('span').html(text);
    
    show_popup_window_and_center_display($container);
}

function is_not_set_payment_system_details()
{
    var is_empty = false;
    
    div_buy_payment_systems_checkbox.filter(':checked').each(function(){
        var $this = $(this);
        var $div_user_payment_data = $this.parent().next('.div_user_payment_data_save');
        
        if($div_user_payment_data.length)
        {
//            var input_val = $div_user_payment_data.find('input').val();
            var input_val = $div_user_payment_data.find('input.form_input').val();
            
            if(!input_val)
            {
               $.post($url_get_ps, 
                    {
                        'ps_mn':$this.data('ps-mashin-name'), 
                        'currency_id':$this.data('currency-id'), 
                        'currency':$this.data('currency') 
                    }, 
                function(res){
//                    show_error($translate_js_error_fill_all_data+' '+res, false); 
                    show_error(res, false); 
                }); 
                
               is_empty = true;
               return false;
            }
        }   
    });
    
    return is_empty;
}



function save_all_check_payment_system( def )
{
    div_buy_payment_systems_checkbox.filter(':checked').each(function(){
        var $this = $(this);
        var $div_user_payment_data = $this.parent().next('.div_user_payment_data_save');
        
        if($div_user_payment_data.length)
        {
            var save_button = $div_user_payment_data.find('.save_user_payment_data');
            
            if(save_button.css('display') != 'none' && def === undefined)
            {
                save_user_payment_information(save_button);
            }
        }   
    });
}



function send_confirmation_form($form)
{
    var $checkbox = $form.find('input[name="last_user_confirm_checkbox"]');
    var $input = $form.find('input[name="foto"]');
    var file_val = $form.find('input[name="foto"]').val();
    var $label = $checkbox.parent();
    var $m_name = $form.find('input[name="foto"]').data('m-name');
    var $loader = $form.find('.loading-gif');
    
    $loader.show();
    $label.css('border', 'none');
    $input.css('border', 'none');
    
//    if(!$checkbox.is(':checked') || file_val == '' && $m_name != 'wt')
    if(!$checkbox.is(':checked') || file_val == '' && !is_root_currency($m_name))
    {
        $label.css('border', '1px solid red');
        $input.css('border', '1px solid red');
        $loader.hide();
    }
    else
    {
        mn.security_module.loader.hide();
        $form.submit();
    }
    
    return false;
}



function send_confirmation_form_money_send($form)
{
    var $input = $form.find('input[name="foto"]');
    var file_val = $form.find('input[name="foto"]').val();
    var $m_name = $form.find('input[name="foto"]').data('m-name');
    var $loader = $form.find('.loading-gif');
    
    $loader.show();
    $input.css('border', 'none');
    
//    if( file_val == '' && $m_name != 'wt')
    if( file_val == '' && !is_root_currency($m_name))    
    {
        $input.css('border', '1px solid red');
        $loader.hide();
    }
    else
        mn.security_module
            .init()
            .show_window('create_get_p2p_orders')
            .done(function(res){
                if( res['res'] !== 'success' ) return false;


                $form.find('.form_code').remove();

                $form.append('<input class="form_code" type="hidden" value="'+res['code']+'" name="code">');
                $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');

                $form.submit();
            });     

    
    return false;
}

function send_last_confirmation_form($form)
{
    var $checkbox = $form.find('input[name="last_user_confirm_checkbox"]');
    var $label = $checkbox.parent();
    var $loader = $form.find('.loading-gif');
    
    $loader.show();
    
    $label.css('border', 'none');
    
    if($checkbox.is(':checked'))
    {
        mn.security_module
            .init()
            .show_window('create_get_p2p_orders')
            .done(function(res){
                if( res['res'] !== 'success' ) return false;

                
                $form.find('.form_code').remove();
                
                $form.append('<input class="form_code" type="hidden" value="'+res['code']+'" name="code">');
                $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');

                $form.submit();
            });        
    }
    else
    {
        $loader.hide();
        $label.css('border', '1px solid red');
    }
    
    return false;
}


function show_popup_window_and_center_display($popup)
{
    $popup.show();
    var $win_h = $(window).height();
    var $win_w = $(window).width();
    var $popup_h = $popup.height();
    var $popup_w = $popup.width();
    var $popup_max_h = $win_h*0.85;
    
    if( $popup_max_h<= $popup_h)
    {
        $popup_h = $popup_max_h;
        $popup.css('height', $popup_h).css('overflow-y', 'scroll');
    }
    
    var $margin_top = $win_h/2 - $popup_h/2;
//    var $margin_left = $win_w/2 - $popup_w/2 -33;
    var $margin_left = $win_w/2 - $popup_w/2 -52;
    
    $popup.css('top', $margin_top+'px').css('left', $margin_left+'px');
    
}


function validate_and_send_confirmation_form($form)
{
    var is_checked_sell = true;
    var is_checked_buy = $form.find('input[name="select_payment_systems_buy"]').is(':checked');
    var file_val = $form.find('input[name="foto"]').val();
    var checked_buy = $form.find('input[name="select_payment_systems_buy"]').filter(':checked').data('m-name');
    var $loader = $form.find('.loading-gif');
    
    $loader.show();
    
//    if($form.find('input[name="buy_only_wt"]').length || checked_buy == 'wt')
    if($form.find('input[name="buy_only_wt"]').length || is_root_currency(checked_buy))
    {
        file_val = true;
    }
    
    if(file_val == '' || (!is_checked_sell && is_checked_buy))
    {
        $loader.hide();
        return false;
    }
    
    $form.submit();
}


function activation_button_submit($form)
{
    var is_checked_sell = true;
    
    var is_checked_buy = $form.find('input[name="select_payment_systems_buy"]').filter(':checked').is(':checked');
    
    var elems = $('#popup_select_payment_system .select_payment_syctem_container_buy.select_payment_syctem_container');
    
    var checked_buy = $form.find('input[name="select_payment_systems_buy"]').filter(':checked').data('m-name');
    var file_val = $form.find('input[name="foto"]').val();
    var $button = $form.find('button#confirmation_block');

    $button.css('background-color', '#cccccc');
    
//    if($form.find('input[name="buy_only_wt"]').length || checked_buy == 'wt')
    if($form.find('input[name="buy_only_wt"]').length || is_root_currency(checked_buy))
    {
        file_val = true;
    }
    $button.addClass('inactive');
    
    if(file_val == '' || (!is_checked_sell && is_checked_buy))
    {
        return false;
    }
    
    var count = $form.find('input[name="select_payment_systems_buy"][type="radio"]').length;
    
    
    if( count > 1 && !is_checked_buy )
    {         
        console.log( $('#popup_select_payment_system .select_ps') );
        $('#popup_select_payment_system .select_ps').show();
        elems.css('border','2px solid red');
        return false;
    }
    
    elems.css('border','');
    $('#popup_select_payment_system .select_ps').hide();
    
    $button.css('background-color', '').removeClass('inactive');
}


function deactivation_button($form)
{
    var $button = $form.find('button#confirmation_block');

    $button.css('background-color', '#cccccc');
}



function show_form_user_send_message_operator($obj)
{
    form_probem_message_for_operator = $obj.parent();
    
//    var exchange_id = $obj.siblings('input[name="exchange_id"]').val();
    var exchange_id = $obj.parent().parent().find('input[name="exchange_id"]').val();
    
    $('.popup_window_problem_chat_operator').find('input[name="exchange_id"]').val(exchange_id);
    
    $('.popup__overlay').show();
    show_popup_window_and_center_display($('.popup_window_problem_chat_operator'));   
    return false;
}


function hide_form_user_send_message_operator($obj)
{
    $('.popup__overlay').hide();
    $obj.hide('slow');
    
    return false;
}


function send_user_probem_message_for_operator(btn)
{
    var $popup = $('.popup_window_problem_chat_operator');
    var $form = $(btn).parent();
    var $loader = $form.find('.loading-gif');
    
    $loader.show(); 
    
    var options = { 
            success: function(responseText, statusText, xhr, $form){
                        var $res = $.parseJSON(responseText);
                        var window_error = $('.popup_window_error');
                        
                        $loader.hide();
                        
                        if($res.error == 1)
                        {
                            window_error.find('span').html($res.text);
//                            window_error.show('slow');
                            show_popup_window_and_center_display(window_error);
                        }
                        else
                        {
                            $form[0].reset();
                            
                            add_last_message_in_chat(form_probem_message_for_operator, $res.last_message, 0, 1);
                            
                            hide_form_user_send_message_operator($popup);
                        }
                     }  // post-submit callback
    };
    
    $form.ajaxSubmit(options); 
}


if(typeof $new_sell_glob === 'undefined' || $new_sell_glob != 1)
{
    function show_fee_field_in_sell_list_if_select_wt()
    {
        var buy_fee_and_ammount = $('.buy_fee_and_ammount');
        var sell_fee_and_ammount = $('.sell_fee_and_ammount');

        if($(add_root_curr_in_selector('.input_container_div input[name="input_summa_buy_payment_systems[%s]"]')).val() > 0)
        {
            sell_fee_and_ammount.hide();
            buy_fee_and_ammount.show();

            return false;
        }

        if($(add_root_curr_in_selector('.input_container_div input[name="input_summa_sell_payment_systems[%s]"]')).val() > 0)
        {
            buy_fee_and_ammount.hide();
            sell_fee_and_ammount.show();

            return false;
        }

        buy_fee_and_ammount.hide();
        sell_fee_and_ammount.show();
    }
}


function copy_from_table_search_payment_system_in_popup($popup, $this)
{
    var body = $this.parent().parent().next();
    
    if(!body.find('.table_search_payment_system').length)
    {
        var tr = $this.parent().parent();
        var row = search_table.row( tr );
        var d = row.data();
        var $loader = $popup.find('.loading-gif');
        $loader.show();
        
        ajax_get( d.id, function(res){
            
            if( res['error'] )
            {
                $('#popup_exchange_wt .result').html('<span style="color: red;">'+res['error']+'</span>');
            }
            if( res['form'] )
            {            
                var res_html = $.parseHTML( res['form'] );
                var res_find = $(res_html).find('.table_search_payment_system');

                if( res_find.length == 0 )
                {
                    $sell_container.html('');
                    $sell_container.append(res);
                }else
                {
                    var content = res_find.clone();
                    var select_system = content.find('.div_select_pay_sys_sell');
                    var $sell_container = $popup.find('.select_payment_syctem_container_sell');

            select_system.css('display','inline-block');
            select_system.css('width','100%');

                    $sell_container.html('');
                    $sell_container.append(select_system);

                    // Проверка что эта функция запущена с определенного окна, и что тут сейчас в выборе указана виза. 
                    if(confirm_order__check_is_visa($popup, $this)) {
                        // Добавляем в это окно, селект виза карт
                        confirm_order__visa_load($popup, $this);
                    } else {
                        confirm_order__visa_destroy($popup, $this);
                        // Так как у нас одно popup окно, то если мы выберем один раз визу, то все все 
                        //следующие выборы других заявок не виза, нам будет снова выводиться эта виза. 
                        //нужно как то обнулять или выводить заного..

                    }

                }
            }
            $loader.hide();
        });
        
        
        return false;
    }
    
    var content = body.find('.table_search_payment_system').clone();
    var select_system = content.find('.div_select_pay_sys_sell');
    var $sell_container = $popup.find('.select_payment_syctem_container_sell');
    
    select_system.css('display','inline-block');
    select_system.css('width','100%');
    
    $sell_container.html('');
    $sell_container.append(select_system);
}


function confirme_select_payment_system_show_popup($this)
{
    var input = $this.parent().parent().find('input[name="id"]');
    
    if(!input.length)
    {
        input = $this.parent().parent().parent().prev().find('input[name="id"]');
        $this = $this.parent().parent().parent().prev().find('.confirme_select_payment_system');
    }
    
    var popup_input = $('#popup_select_payment_system')
                        .find('input[name="exchange_id"]')
                        .val(input.val());

    copy_timer_and_text_in_popup($('#popup_select_payment_system'), $this);

    show_popup_window_and_center_display($('#popup_select_payment_system'));
}






function ajax_get( id, fnc ) {
   
   $.post( search_details_url, { id: id  }, function( res ) 
   {
       var data =  [];
       
       if( res == '' )
       {
           data['error'] =  _e('Неверный ответ от сервера. Перезагрузите страницу и попробуйте еще раз.');
           return fnc( data );
       }
       
        try {
            data = JSON.parse( res );
        }catch(exc){            
            data['error'] =   fnc( _e('Неверный ответ от сервера. Перезагрузите страницу и попробуйте еще раз.') );
            return fnc( data );
        }
        
        return fnc( data );        
    }); 
    
}
function showSearchDetail( row ) {
   var d = row.data();
   
   ajax_get( d.id, 
   function(res){       
       if( res['form'] ) row.child(res['form']).show();       
   });
   
}

var search_table  = null;
var search_fields = null;

function out_submit_search($this)
{
    var is_check_buy = div_buy_payment_systems_checkbox.is(':checked');
    var is_check_sell = div_sell_payment_systems_checkbox.is(':checked');
    
    var is_not_set_checkbox_buy = false;
    var is_not_set_checkbox_sell = false;
    var is_not_set_input = false;
    
    if(!is_check_buy && !is_check_sell)
    {
        is_not_set_checkbox_buy = set_red_border_for_buy_image_payment();
        is_not_set_checkbox_sell = set_red_border_for_sell_image_payment();
    }   
    
    is_not_set_input = set_red_border_for_input();
    set_val_for_input_siblings();

    if(!is_not_set_checkbox_buy && !is_not_set_checkbox_sell && !is_not_set_input)
    {   
        var loader = $this.parent().find('div .loading-gif');

        //loader.show();
        //$this.hide();
        
        togle_and_switch_search(false);
        
        var table_lang = {};
        if (site_lang=='ru')
            table_lang  = { "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Russian.json" };

        search_fields = $( "#sel_wt_form" ).serializeArray();
        if ( search_table == null )
        {
            $('.table_results').show();
            search_table = $('.table_results').DataTable({
                "language": table_lang,                
                "searching": false,
                "processing": true,
                "serverSide": true,
                'iDisplayLength':100,
                "order": [[ 4, "asc" ]],
                "ajax": {
                    "url": search_in_url,
                    "type": "POST",
                    "data": function ( d ) {
                        //var fields = $( "#sel_wt_form" ).serializeArray();
                            jQuery.each( search_fields, function( i, field ) {
                                d[field.name] = field.value;
                            });
                            
                        }
                        
                },
                "fnDrawCallback": function(){
                    if( mn.left_side_last_deals !== undefined && mn.left_side_last_deals.resize !== undefined ){
                             mn.left_side_last_deals.resize();
                    }
                },
                "paginationType": "full_numbers"
            });

            // Array to track the ids of the details displayed rows
            var detailRows = [];

            $('.table_results tbody').on( 'click', 'tr', function () {
                var tr = $(this);
                var row = search_table.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    showSearchDetail(row);

                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );

            // On each draw, loop over the `detailRows` array and show any child rows
            search_table.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                } );
            } );        


            //$(".table_results").dataTable().columnFilter({
              // "sPlaceHolder": "head:before"
            //});
        } else {
            search_table.ajax.reload( null, false ); 
        }
    }
}


function search_all_orders($this)
{
    
    var elementClick = $this.attr("href");
    elementClick = elementClick.split("#");
    elementClick = elementClick[1];
    
//    var destination = $('a[name="'+elementClick+'"]').offset().top;
//    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
    
    $('#sel_wt_form input[name="all_orders"]').prop( "checked", true );
    $('#buy_amount_down, #buy_amount_up, #sell_amount_down, #sell_amount_up').val(0);
    
    out_submit_search($('#out_submit_search'));
    
    //$('#sel_wt_form input[name="all_orders"]').prop( "checked", false );
}


function cancel_action($this)
{
    var id = $this.data('order-id');
    var popup_input = $('#popup_canceled')
                        .find('input[name="exchange_id"]')
                        .val(id);

    $('#popup_canceled').show('slow');

    return false;
}



function exchange_action_wt($this)
{
    var input = $this.parent().find('input[name="id"]');
    var popup_input = $('#popup_exchange_wt')
                        .find('input[name="exchange_id"]')
                        .val(input.val());
    var $popup = $('#popup_exchange_wt');

    $('#popup_exchange_wt #exchange_confirm_buntton_wt').show(); 
    $('#popup_exchange_wt .result').html(''); 

    copy_from_table_search_payment_system_in_popup($popup, $this);

    show_popup_window_and_center_display($popup);

    return false;
}



function check_is_not_set_wt()
{
    var is_check_buy_wt = div_buy_payment_systems_checkbox.filter(':checked').is(add_root_curr_in_selector('input[name="buy_payment_systems[%s]"]'));
    var is_check_sell_wt = div_sell_payment_systems_checkbox.filter(':checked').is(add_root_curr_in_selector('input[name="sell_payment_systems[%s]"]'));
    
    
    if(is_check_buy_wt || is_check_sell_wt)
    {
        return false;
    }
    
    return true;
}


function standart_calc(){
    var $form = $('#sel_wt_form');
    $('#form_code').val( $('#code').val() );
    
    $form.submit();
}

function send_cinfirm_exchang_request_secure( res )
{
    if( res == undefined || res['res'] != 'success' ) return false;
    
    var $form = $global_confirmation_button.parent();
    $form.find('.form_code').remove();
    $form.append('<input class="form_code" type="hidden" value="'+res['code']+'" name="code">');
    $form.append('<input class="form_purpose" type="hidden" value="'+mn.security_module.purpose+'" name="purpose">');
    
    
    send_cinfirm_exchang_request($global_confirmation_button);
}


function send_confirmation_form_secure($form)
{
    $global_confirmation_button = $form;
    var $form = $global_confirmation_button;
    
    if( security )
    {
        mn.security_module
            .init()
            .show_window('create_get_p2p_orders')
            .done(function(res){
                if( res['res'] !== 'success' ) return false;

                $form.find('.form_code').remove();
                
                $form.append('<input class="form_code" type="hidden" value="'+res['code']+'" name="code">');
                $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');

                
                send_confirmation_form($form);                
            });
    }else{
        $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');            
        
        send_confirmation_form($form);
    }
}


function validate_and_send_confirmation_form_secure($form, el)
{
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
            }
            $form.append('<input type="hidden" name="purpose" value="create_get_p2p_orders" />');
            
            
            validate_and_send_confirmation_form($form);
        });
    
    
}

function save_user_payment_information(obj)
{
    $global_confirmation_button = obj;

    console.log('---0---');
    mn.security_module
        .init()
        .show_window('save_p2p_payment_data')
        .done(save_user_payment_information_secure);
    console.log('---1---');
//    
//    if(security)>
//    {
//        
//        $('#out_send_window_sms').data('call-back',save_user_payment_information_secure).show('slow');
//
//        return false;
//    }
//    else
//    {
//        save_user_payment_information_secure(obj);
//    }
}

function save_user_payment_information_secure(res )
{
    console.log('save_user_payment_information_secure', res );
    if( res['res'] != 'success' ) return false;
    
    var $code = res['code'];
    
    var obj = $global_confirmation_button;
    var obj_s = obj.siblings('.save_user_payment_data_input');
    var payment_system = obj_s.data('payment-system');
    var value = obj_s.val();
    var obj_cont = obj.parent();
    var loader = obj.siblings('.loading-gif');
    //var $code = '';
    
//    if($('#code_sms').length)
//    {
//        $code = $('#code_sms').val();
//    }
    console.log('-1-',value, $global_confirmation_button);
    
    if(!value)
    {
        var $ps_name = obj_cont.prev().find('.onehrinetext').html();
        show_error($translate_js_error_fill_paymet_data.replace(/\%s/, $ps_name));
        return false;
    }
    console.log('-2-');
    loader.show();
    
    $.post(
        save_user_payments_data_url,
        {'value': value, 'payment_system' : payment_system, 'code':$code, 'purpose': 'save_p2p_payment_data'},
        function(resTxt){
            console.log('-3-');
            var res = $.parseJSON(resTxt);
            loader.hide();
            
            if(res.res == 'ok')
            {
                show_load_button_for_user_payments(obj);
            }
            else
            {
                show_error(res.text);
            }
            
        }
    );
    console.log('-4-');
}



function open_order_by_hash()
{
    var $id = get_hash('id');
    
    if($id == false)
    {
        return false;
    }
    
    var $order = $('.table_search_orders input[name="id"][value="'+$id+'"]');
    
    if(!$order.length)
    {
        return false;
    }
    
    var $row = $order.parent().parent();
    
    show_dop_order_data($row);
    
    var destination = $row.offset().top-55;
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
}



function open_order_by_hash_oid()
{
    var $id = get_hash('oid');
    
    if($id == false)
    {
        return false;
    }
    
    var $order = $('.table_sell_orders input[name="id"][value="'+$id+'"]');
    
    if(!$order.length)
    {
        return false;
    }
    
    var $row = $order.parent().parent();
    show_dop_order_data($row);
    
    var destination = $row.offset().top-55;
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
}

function open_order_by_hash_or_id()
{
    var $id = get_hash('id');
    
    if($id == false)
    {
        return false;
    }
    
    var $order = $('.table_sell_orders input[name="or_id"][value="'+$id+'"]');
    
    if(!$order.length)
    {
        return false;
    }
    
    var $row = $order.parent().parent();
    show_dop_order_data($row);
    
    var destination = $row.offset().top-55;
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 800);
}


function get_hash($var)
{
   var $hash = window.location.hash;
   var $hash_str = $hash.replace("#", "");
   var $hash_arr = $hash_str.split('&');
   
   for(var key in $hash_arr)
   {
       var res = $hash_arr[key].split('=');
       
       if(res[0] == $var)
       {
           return res[1];
       }
   }
   
   return false;
}



function show_dop_order_data($this)
{
    var body = $this.next();

    if($this.hasClass('active') ){
        $this.removeClass('active');
        body.hide();
        return false;
    }

    body.show();

    $this.addClass('active');

    order_to_completion_remains(body);
    order_to_completion_remains_2(body);

    show_all_chat_messages(body);
}
//show_popup_window_and_center_display($popup)



function is_root_currency(currency)
{
    if (typeof root_currency[currency] !='undefined')
    {
        return true;
    }
    
    return false;
}



function show_country_content($all_cont, $country_id, $checkbox_name, $add_new_content, $add_new_content_all)
{
//    console.log($all_cont);
//    console.log($country_id);
//    console.log($checkbox_name);
//    console.log($add_new_content);
//    console.log($add_new_content_all);
//    
//    return false;
    
    var loader = $all_cont.find('.loading-gif');
    var $select = $all_cont.find('.container_select_bank');
    
    $all_cont.show();
    
    $select.remove();
    
    loader.show();
    
//    $add_new_content_all.hide();
//    
//    $add_new_content.show();
    var $all_cont_div = $all_cont.find('div');
    
    $.post(
        get_country_bank_data_url,
        {'id': $country_id, checkbox_name: $checkbox_name},
        function(resTxt){
            loader.hide();
            
//            $all_cont.html(resTxt);
//            $all_cont.append(resTxt);
//            $all_cont.find('.select_country_banks').select2(
            $all_cont_div.html(resTxt);
            $all_cont_div.find('.select_country_banks').select2(
                    { placeholder : '', 
                        escapeMarkup: function(markup) {
                            var replace_map = {
                                    '\\': '&#92;',
                                    '&': '&amp;',
                                    '<': '&lt;',
                                    '>': '&gt;',
                                    '"': '&quot;',
                                    "'": '&#39;',
                                    "/": '&#47;',
                                    "{": '<span class="swift_code_for_bank_select" title="%s">',
                                    "}": '</span>',
                                    "[": '<div class="bank_left_short_name" title="%s">',
                                    "]": '</div>'
                                };

                            var split_string = String(markup).split(/[\[\]]/g);    

                            var result_string =  String(markup).replace(/[&<>"'\/\\\{\}\[\]]/g, function (match) {
                                return replace_map[match];
                            });

                            result_string = String(result_string).replace(/\%s/g, split_string[1]);

                            return result_string;
                        } 
                    }
                );
        }
    );
}
//function show_country_content($all_cont, $country_id, $checkbox_name, $add_new_content, $add_new_content_all)
//{
//    var loader = $all_cont.find('.loading-gif');
//    var $select = $all_cont.find('.container_select_bank');
//    
//    $all_cont.show();
//    
//    $select.remove();
//    
//    loader.show();
//    
//    $add_new_content_all.hide();
//    
//    $add_new_content.show();
//    
//    $.post(
//        get_country_bank_data_url,
//        {'id': $country_id, checkbox_name: $checkbox_name},
//        function(resTxt){
//            loader.hide();
//            
////            $all_cont.html(resTxt);
//            $all_cont.append(resTxt);
//            $all_cont.find('.select_country_banks').select2(
//                    { placeholder : '', 
//                        escapeMarkup: function(markup) {
//                             var replace_map = {
//                                     '\\': '&#92;',
//                                     '&': '&amp;',
//                                     '<': '&lt;',
//                                     '>': '&gt;',
//                                     '"': '&quot;',
//                                     "'": '&#39;',
//                                     "/": '&#47;',
//                                     "{": '<span class="swift_code_for_bank_select" title="%s">',
//                                     "}": '</span>',
//                                     "[": '<div class="bank_left_short_name" title="%s">',
//                                     "]": '</div>'
//                                 };
//                                 
//                             var split_string = String(markup).split(/[\[\]]/g);    
//                             
//                             var result_string =  String(markup).replace(/[&<>"'\/\\\{\}\[\]]/g, function (match) {
//                                 return replace_map[match];
//                             });
//                             
//                             result_string = String(result_string).replace(/\%s/g, split_string[1]);
//                             
//                             return result_string;
////                             return String(markup).replace(/[&<>"'\/\\\{\}\[\]]/g, function (match) {
////                                 return replace_map[match];
////                             });
//                        } 
//                     }
//                );
//        }
//    );
//}
//function show_country_content($all_cont, $cont, $val)
//{
//    $all_cont.hide();
//    
//    if($val == 0)
//    {
//        return false;
//    }
//    
//    $cont.show();
//}

function sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name)
{
    if($checkbox_name == 'sell_payment_systems')
    {
        sell_payment_systems_checkbox_change($curent_checkbox);
    }
    else if($checkbox_name == 'buy_payment_systems')
    {
        buy_payment_systems_checkbox_change($curent_checkbox);
    }
}


function add_bank_in_payment_system($id, $curent_select, $container_country, $checkbox_name)
{
    var loader = $container_country.find('.loading-gif');
    var loader_parent = $container_country.find('.loading-gif').parent();
    var selected_checkbox_name = $curent_select.find('option:selected').data('checkbox-name');
    var $curent_checkbox = $('input[name="'+selected_checkbox_name+'"]');
    
    $('.hidden_input_selected_bank_'+$checkbox_name).val($id);
    
    $container_country.show();
    
    loader_parent.addClass('border_eb');
    
    loader.show();
    
    if($curent_checkbox.length)
    {
        
        loader.hide();
        loader_parent.removeClass('border_eb');
        
        // если чекбокс уже чекнут то тогда унчекаем его и запускаем функцию 
        // чендж чекбокс для того чтоб избезать дублирования в куик_лист справа
        if($curent_checkbox.prop('checked'))
        {
            $curent_checkbox.prop('checked', false);
            sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
        }
        
        $curent_checkbox.prop('checked', true);
        
        sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
        
        $curent_select.prop('selectedIndex',0);
        
        show_currencys_list_in_common_popup($('.selec_currency_container_'+$checkbox_name+'_'+$id), $('#dop_ps_select_country_'+$checkbox_name+' .currency_container_common_popup'));
        
        return false;
    }
    
    var no_show_select_currency_input = $('input[name="no_show_select_currency"]');
    
    if(no_show_select_currency_input.length)
    {
        var no_show_select_currency = no_show_select_currency_input.val();
    }
    else
    {
        var no_show_select_currency = 0;
    }
    
    var save_user_data_input = $('input[name="save_user_data"]');
    var save_user_data = 1;
    
    if(save_user_data_input.length)
    {
        var save_user_data = save_user_data_input.val();
    }
    
    
    $.post(
        get_add_bank_in_payment_system_url,
        {
            'id': $id, 
            checkbox_name: $checkbox_name, 
            'no_show_select_currency': no_show_select_currency, 
            'save_user_data':save_user_data
        },
        function(resTxt){
            loader.hide();
            loader_parent.removeClass('border_eb');
            
//            $container_country.append(resTxt);
            $container_country.find('center').after(resTxt);
            
            var $curent_checkbox = $('input[name="'+selected_checkbox_name+'"]');
            
            if($checkbox_name == 'sell_payment_systems')
            {
                div_sell_payment_systems_checkbox = $('.div_sell_payment_systems input[type="checkbox"][name^="sell_payment_systems"]');
            }
            else if($checkbox_name == 'buy_payment_systems')
            {
                div_buy_payment_systems_checkbox = $('.div_buy_payment_systems input[type="checkbox"][name^="buy_payment_systems"]');
            }
            
            sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
            
            $curent_select.prop('selectedIndex',0);
            
            show_currencys_list_in_common_popup($('.selec_currency_container_'+$checkbox_name+'_'+$id), $('#dop_ps_select_country_'+$checkbox_name+' .currency_container_common_popup'));
//            show_currencys_list($('.selec_currency_container_buy_payment_systems_887478'), $('.popup_window_currencys_list_container')); return false;
        }
    );
}
//function add_bank_in_payment_system($id, $curent_select, $container_country, $checkbox_name)
//{
//    var loader = $container_country.find('.loading-gif');
//    var loader_parent = $container_country.find('.loading-gif').parent();
//    var selected_checkbox_name = $curent_select.find('option:selected').data('checkbox-name');
//    var $curent_checkbox = $('input[name="'+selected_checkbox_name+'"]');
//    
//    $container_country.show();
//    
//    loader_parent.addClass('border_eb');
//    
//    loader.show();
//    
//    if($curent_checkbox.length)
//    {
//        
//        loader.hide();
//        loader_parent.removeClass('border_eb');
//        
//        // если чекбокс уже чекнут то тогда унчекаем его и запускаем функцию 
//        // чендж чекбокс для того чтоб избезать дублирования в куик_лист справа
//        if($curent_checkbox.prop('checked'))
//        {
//            $curent_checkbox.prop('checked', false);
//            sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
//        }
//        
//        $curent_checkbox.prop('checked', true);
//        
//        sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
//        
//        $curent_select.prop('selectedIndex',0);
//        
//        return false;
//    }
//    
//    var no_show_select_currency_input = $('input[name="no_show_select_currency"]');
//    
//    if(no_show_select_currency_input.length)
//    {
//        var no_show_select_currency = no_show_select_currency_input.val();
//    }
//    else
//    {
//        var no_show_select_currency = 0;
//    }
//    
//    var save_user_data_input = $('input[name="save_user_data"]');
//    var save_user_data = 1;
//    
//    if(save_user_data_input.length)
//    {
//        var save_user_data = save_user_data_input.val();
//    }
//    
//    
//    $.post(
//        get_add_bank_in_payment_system_url,
//        {
//            'id': $id, 
//            checkbox_name: $checkbox_name, 
//            'no_show_select_currency': no_show_select_currency, 
//            'save_user_data':save_user_data
//        },
//        function(resTxt){
//            loader.hide();
//            loader_parent.removeClass('border_eb');
//            
////            $container_country.append(resTxt);
//            $container_country.find('center').after(resTxt);
//            
//            var $curent_checkbox = $('input[name="'+selected_checkbox_name+'"]');
//            
//            if($checkbox_name == 'sell_payment_systems')
//            {
//                div_sell_payment_systems_checkbox = $('.div_sell_payment_systems input[type="checkbox"][name^="sell_payment_systems"]');
//            }
//            else if($checkbox_name == 'buy_payment_systems')
//            {
//                div_buy_payment_systems_checkbox = $('.div_buy_payment_systems input[type="checkbox"][name^="buy_payment_systems"]');
//            }
//            
//            sell_payment_systems_checkbox_change_by_checkbox_name($curent_checkbox, $checkbox_name);
//            
//            $curent_select.prop('selectedIndex',0);
//        }
//    );
//}



function add_root_curr_in_selector(str)
{
    var res_string = '';
    var sep = '';        
    
    for(var key in root_currency)
    {
        res_string += sep+str.replace(/\%s/g, key); 
        sep = ', ';
    }
    
    return res_string;
}


function show_only_one_checked_root_ps(checkboxs, $obj)
{
    var root_currency = checkboxs.filter(add_root_curr_in_selector('input[name$="%s]"]'));
//    console.log(root_currency);
    if(typeof $obj == 'undefined')
    {
        $obj = root_currency.filter(':checked').first();
    }
    
    root_currency = root_currency.not($obj).filter(':checked');
    
    if(root_currency.length)
    {
        root_currency.prop('checked', false);
        
        root_currency.each(function() {
            var $this = $(this);
            add_payment_system_to_quick_list($this);
        });
    }
    
    get_checked_and_set_glob_payment_currency_id();
}

function togle_and_switch_search(is_clear_search)
{
    var obj_search = $('.search_fields_container');
    var obj_new_search = $('.new_search_button_container');
    var dt_wrapper = $('#DataTables_Table_0_wrapper');
    
    if(is_clear_search == true)
    {
        var form = $('#sel_wt_form');
        form[0].reset();
//        sell_payment_systems[abb_bank_cmabrumm]
//        console.log(div_buy_payment_systems_checkbox.filter('[name="sell_payment_systems[abb_bank_cmabrumm]"]'));
//        console.log(div_sell_payment_systems_checkbox.filter('[name="sell_payment_systems[abb_bank_cmabrumm]"]'));
        div_sell_payment_systems_checkbox.filter(':checked').each(function(){
            var $this = $(this);
            $this.prop( "checked", false );
        });
        
        div_buy_payment_systems_checkbox.filter(':checked').each(function(){
            var $this = $(this);
            $this.prop( "checked", false );
        });
        
        
        div_buy_payment_systems_checkbox.each(function(){
            var $this = $(this);
            show_hidden_fild_payment_system_data(div_buy_payment_systems_checkbox, $this);        
            add_payment_system_to_quick_list($this);

            hide_other_checkbox_if_select_wt(div_buy_payment_systems_checkbox, div_sell_payment_systems_checkbox);
        });
        
        div_sell_payment_systems_checkbox.each(function(){
            var $this = $(this);
            // убрано нам не нужно заполнять данные о платёжной системе покупателя это забота продовца
            show_hidden_fild_payment_system_data(div_sell_payment_systems_checkbox, $this);        
            add_payment_system_to_quick_list($this);

            hide_other_checkbox_if_select_wt(div_sell_payment_systems_checkbox, div_buy_payment_systems_checkbox);
        });
    }
    
    obj_search.toggle('fast');
    obj_new_search.toggle('fast');
    dt_wrapper.toggle('fast');
    
    if( mn.left_side_last_deals !== undefined && mn.left_side_last_deals.resize !== undefined ){
        setTimeout(function(){
             mn.left_side_last_deals.resize();
        },1000);
    }
    
//    obj.toggle('fast',function(){
//        var $this = $(this);
//        if($this.css('display') == 'none')
//        {
//            arraw_obj.html('&#9658;');
//        }
//        else
//        {
//            arraw_obj.html('&#9660;');
//        }
//    });
}


function button_curency_problem(container)
{
    container.find('.button_curency_problem').hide();
    container.find('.problem_chat_messages .answer-form').show();
    
    return false;
}



function show_currencys_list($list, $popup)
{
    var list = $list.contents().clone();
//    var list = $list.clone();
    var container = $popup.find('.container');
    
    container.html('');
    container.append(list);
    
//    console.log($list);
//    console.log(list);
//    console.log(list.find('select'));
//    return false;
    
    list.find('select').select2(
            { placeholder : '', 
               escapeMarkup: function(markup) {
                    var replace_map = {
                            '\\': '&#92;',
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;',
                            "/": '&#47;',
                            "{": '<span class="currecy_code">',
                            "}": '</span>'
                        };
                    return String(markup).replace(/[&<>"'\/\\\{\}]/g, function (match) {
                        return replace_map[match];
                    });
                } 
            }
    );

    show_popup_window_and_center_display($popup);
}


function show_currencys_list_in_common_popup($list, container)
{
    var list = $list.contents().clone();
    
    if(!list.length)
    {
        container.parent().parent().find('button').show();
        return false;
    }
    
    container.html('');
    container.append(list);
    
    list.find('select').select2(
            { placeholder : '', 
               escapeMarkup: function(markup) {
                    var replace_map = {
                            '\\': '&#92;',
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;',
                            "/": '&#47;',
                            "{": '<span class="currecy_code">',
                            "}": '</span>'
                        };
                    return String(markup).replace(/[&<>"'\/\\\{\}]/g, function (match) {
                        return replace_map[match];
                    });
                } 
            }
    );
    
    container.parent().show();
    container.parent().parent().find('button').show();
}


function select_currency($select, checkbox)
{
    var $name = $select.data('input-name');
    var $input = $('input[name="'+$name+'"]');
    var $span = $input.siblings('a').find('.select_currency_span'); 
    var $val = $select.val();
    var $data = $select.find('option:selected').data('currency');
//    var $checkbox = $('.countent_country_overall input[name="'+checkbox+'"]');
//    var $checkbox = $('form input[name="'+checkbox+'"]');
    var $checkbox = $('div[class^="countent_country_overall_"] input[name="'+checkbox+'"]');
    
    $input.val($val).data('currency', $data);
    
    $span.html('('+$data+')');
    
    //записываем в оба чекбокса
    $('input[name="'+checkbox+'"]').data('currency', $data);
    
    if($checkbox.is(':checked'))
    {
        add_payment_system_to_quick_list($checkbox, true);
    }
}

//function show_dop_ps_input_data($container, $action)
//{
//    var $popup = $('#dop_ps_input_container');
//    var $container_popup = $popup.find('form');
//    
//    $container_popup.html($container.html());
////    $container_popup.html('');
////    $container_popup.append($container.contents());
//    
//    $container_popup.attr('action', $action);
//    
//    show_popup_window_and_center_display($popup);
//}
function show_dop_ps_input_data($id, $ajax_action, $action)
{
    var save_user_data_input = $('input[name="save_user_data"]');
    
    if(save_user_data_input.length)
    {
        var save_user_data = save_user_data_input.val();
        
        if(save_user_data == 0)
        {
            return false;
        }
    }
//    console.log($id, $ajax_action, $action);
    var $popup = $('#dop_ps_input_container');
    var $loader = $popup.find('.loading-gif');
    var $container_popup = $popup.find('form');
    
    $container_popup.html('');
    
    show_popup_window_and_center_display($popup);
    
    $loader.show();
    
    $.post($ajax_action, 
        {'id':$id}, 
        function(res){
            
            $loader.hide();
            
            $container_popup.html(res);
//    
            $container_popup.attr('action', $action);
            
            show_popup_window_and_center_display($popup);
    });
}


function send_form_dop_ps_input_data($form, $string)
{
    $global_confirmation_button = $form;
    $global_confirmation_string = $string;
    
//    if(security)
//    {
        //$('#out_send_window').data('call-back', send_form_dop_ps_input_data_secure).show('slow');
        mn.security_module
            .init()
            .show_window('save_p2p_payment_data')
            .done(send_form_dop_ps_input_data_secure);
        
        return false;
//    }
//    else
//    {
//        send_form_dop_ps_input_data_secure( {res: 'success', code:0} );
//    }
}


//function send_form_dop_ps_input_data_secure($form, $string)
function send_form_dop_ps_input_data_secure( res )
{
    if( res['res'] === undefined || res['res'] != 'success' ) return false;
    
    var $form = $global_confirmation_button;
    var $string = $global_confirmation_string;
    
    $form.find('.form_code').remove();
    $form.append('<input class="form_code" type="hidden" value="'+res['code']+'" name="code">');
    $form.append('<input class="form_code" type="hidden" value="'+mn.security_module.purpose+'" name="purpose">');
    
    
    var $loader = $form.find('.loading-gif');
    var $container = $form.parent();
    var $bank_error = $form.find('.bank_error');
    
    if($bank_error.length)
    {
        $bank_error.remove();
    }
    
    $form.find('input').css('border', '1px solid #ddd');
    
    $loader.show();
//    console.log('>>>>>',$form, $string);
    var options = {
            success: function(responseText, statusText, xhr, $form_res){
                        var $res = $.parseJSON(responseText);
                        $loader.hide();
                        
                        if($res.res != 'ok')
                        {
//                            show_error($res.text);
                            $form.append('<div class="bank_error" style="color:red; font-weight: bold;">'+$res.text+'</div>');
                            
                            if($res.field)
                            {
                                $form.find('[name^="'+$res.field+'"]').css('border','1px solid red');
                            }
                        }
                        else
                        {
                            var $ps_id = $form.find('input[name="id"]').val();
                            $('.user_payment_data_'+$ps_id).val($string);
                            
                            $form_res.find('input').each(function(){
                                var $val = $(this).val();
                                
                                if($(this).is(':radio') && $(this).is(':checked'))
                                {
                                    $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').prop('checked', true);
                                    $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').attr('checked', true);
                                }
                                else if($(this).is(':radio'))
                                {
                                    $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').prop('checked', false);
                                    $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').attr('checked', false);
                                }
                                
                                $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').val($val);
                                $('.dop_ps_input_data_'+$ps_id).find('input[name="'+$(this).attr('name')+'"]').attr('value',$val);
                            });
                           
                            $container.hide();  
                        }
                     }  // post-submit callback
        };
    
    $form.ajaxSubmit(options);
}



function show_dop_data_to_paymen_sys_in_table($this, $obj_show, $obj_height)
{
    var arraw_obj = $this.find('span');
    
    $obj_show.toggle(1,function(){
        if($obj_show.is(":visible"))
        {
//            var $h = $obj_height.height();
//            var $obj_show_h = $obj_show.height();
            
//            $obj_height.data('real_height',$h);
            
//            $obj_height.height($obj_show_h+$h);
            
            arraw_obj.html('&#9660;');
        }
        else
        {
//            var $h = $obj_height.height($obj_height.data('real_height'));
//            $obj_height.height($h);
            arraw_obj.html('&#9658;');
        }
    });   
}

function bank_add_new(obj)
{
    var $data = {
        'url':  obj.siblings('.save_new_user_payment_data_input_url').val(),
        'country':  obj.siblings('.save_new_user_payment_data_input_country').val()
//        'url':  obj.siblings('[name="url"]').val(),
//        'country':  obj.siblings('[name="country"]').val()
    };
    
    save_user_new_payment_information(obj, $data);
}

function show_popup_select_bank($container, $checkbox_name)
{
    $container.find('.country_content_container select').select2("val", "");
    
    $container.find('.countent_country_overall_bank_select').hide().find('div').html('');
    
    var container_currency = $container.find('.currency_container_common_popup');
    container_currency.html('');
    container_currency.parent().hide();
    
    $container.find('button').hide();
    
    show_popup_window_and_center_display($container);
}

function withdrawal_search( cur, sum )
{
    if( cur === undefined || cur == '' ) return false;
    
    var name = "input[name='sell_payment_systems["+cur+"]']";
    
    $(name).prop( "checked", true );
    
    $('#sell_amount_down').val(sum);
    $('#sell_amount_up').val(sum);
    
    $('#out_submit_search').trigger('click');
}

function add_money_search( cur, sum )
{
    if( cur === undefined || cur == '' ) return false;
    
    var name = "input[name='buy_payment_systems["+cur+"]']";
    
    $(name).prop( "checked", true );
    
    $('#buy_amount_down').val(sum);
    $('#buy_amount_up').val(sum);
    
    $('#out_submit_search').trigger('click');
}
//save_new_user_payment_data
