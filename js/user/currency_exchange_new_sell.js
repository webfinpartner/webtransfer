if( sell_step === undefined ) var sell_step = 1;

$(document).ready(function(){
    $(document).on('click','button.next_button',function(){
        show_next_sell_step();
        hide_show_null_buy_sell_ps( sell_step );
    });
    
    $(document).on('click','button.previous_button',function(){
        show_prev_sell_step();
        hide_show_null_buy_sell_ps( sell_step )
    });
    
    $(document).on('change', 'select[name^="input_summa_sell_payment_systems"], select[name^="input_summa_buy_payment_systems"]', function(){
        var $this = $(this);
        sell_amount_keyup($this);
    });
    var timer = 0;
    
    $(document).on('keyup', 'input[name^="input_summa_buy_payment_systems"]', function(){
        
        var $inputs = $('.quick_selected_payment_buy input[name^="input_summa_buy_payment_systems"]');
        var send = true;
//        
        $inputs.each(function(){
           var $this = $(this);
           
           if(!($this.val() > 0))
           {
                send = false;
           }
        });
        
        if(send !== true)
        {
            return false;
        }
        
        timer = setTimeout(function(){
            check_diskont_limit('', function(){});
        }, 5000);
        
    });
    
    $(document).on('change', 'select[name^="input_summa_buy_payment_systems"]', function(){
        var $this = $(this);
        
        if($this.val() > 0)
        {
            check_diskont_limit('', function(){});
        }
    });
});



function show_next_sell_step()
{
    sell_step += 1;
    
    show_current_sell_step();
}



function show_prev_sell_step()
{
    sell_step -= 1;
    
    show_current_sell_step();
}



function show_current_sell_step()
{
    var $previous_button = $('button.previous_button');
    var $next_button = $('button.next_button');
    var $out_submit = $('#out_submit');
    
    $previous_button.hide();
    $next_button.hide();
    $out_submit.hide();
    
//    var is_not_set_checkbox_buy = set_red_border_for_buy_image_payment();
//    var is_not_set_checkbox_sell = set_red_border_for_sell_image_payment();
    
    if(sell_step > 3 || sell_step < 1)
    {
        sell_step = 1;
    }
    
    if(sell_step == 1)
    {
        show_step_1();
        $next_button.show();
    }
    
    if(sell_step == 2)
    {
        var is_not_set_checkbox_sell = set_red_border_for_sell_image_payment();
        var is_not_set_input = set_red_border_for_input_step_2_new_sell();
        var is_not_select_currency = !check_select_currency(div_sell_payment_systems_checkbox);
        
        if(is_not_set_checkbox_sell || is_not_set_input || is_not_select_currency)
        {
            sell_step = 1;
            show_current_sell_step();
            return false;
        }
        
        show_step_2();
        $previous_button.show();
        $next_button.show();
    }
    
    if(sell_step == 3)
    {
        var is_not_set_checkbox_buy = set_red_border_for_buy_image_payment();
        var is_not_set_payment_details = is_not_set_payment_system_details();
        var is_not_set_input = set_red_border_for_input();
        var is_not_select_currency = !check_select_currency(div_buy_payment_systems_checkbox);
        
//        if(is_not_set_payment_details)
//        {
//            show_error($translate_js_error_fill_all_data); 
//        }
        
        if(is_not_set_checkbox_buy || is_not_set_payment_details || is_not_set_input || is_not_select_currency)
        {
            sell_step = 2;
            show_current_sell_step();
            return false;
        }
        
        show_step_3();
        $previous_button.show();
        $out_submit.show();
    }
}



function show_step_1()
{
    $('.step_2').hide();
    $('.step_3').hide();
    $('.step_1').show();
    
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: 0}, 800);
}



function show_step_2()
{
    $('.step_1').hide();
    $('.step_3').hide();
    $('.step_2').show();
    show_first_step_summ();
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: 0}, 800);
}



function show_step_3()
{
    $('.step_1').hide();
    $('.step_2').hide();
    
    
    var $container = $('.form_table_step_3_new_sell'); 
    var $loader = $container.find('.loading-gif'); 
    var $container_div = $container.find('div'); 

    $loader.show();
    $container_div.html('');
    
    show_second_step_summ();
    
    $('.step_3').show();
    
    check_diskont_limit($loader, show_step_3_table);
    
    jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: 0}, 800);
    
//    var options = {
//            'url': ajax_step_3_new_sell_check_payment_limit_url,
//            success: function(responseText, statusText, xhr, $form)
//            {
//                var $res = $.parseJSON(responseText);
//                var window_error = $('.popup_window_error');
//                
//                if($res.res == 'error')
//                {
//                    window_error.find('span').html($res.text);
//                    show_popup_window_and_center_display(window_error);
//                    $loader.hide();
//                    
//                    sell_step = 2;
//                    show_current_sell_step();
//                    return false;
//                }
//                elseif($res.res == 'ok')
//                {
//                    $('#sel_wt_form').ajaxSubmit(options);
//                }
//            }  
//        };
//    
//    $('#sel_wt_form').ajaxSubmit(options);
    
//    var options = {
//            'url': ajax_step_3_new_sell_url,
//            success: function(responseText, statusText, xhr, $form)
//            {
//                $loader.hide();
//                $container_div.html(responseText);
//            }  
//        };
//    
//    $('#sel_wt_form').ajaxSubmit(options);
}



function check_diskont_limit($loader, success_func)
{
    var options = {
            'url': ajax_step_3_new_sell_check_payment_limit_url,
            success: function(responseText, statusText, xhr, $form)
            {
                var $res = $.parseJSON(responseText);
                var window_error = $('.popup_window_error');
                
                if($res.res == 'error')
                {
                    window_error.find('span').html($res.text);
                    show_popup_window_and_center_display(window_error);
                    
                    if(typeof $loader == 'object' && $loader.length)
                    {
                        $loader.hide();
                    }
                    
                    sell_step = 2;
                    show_current_sell_step();
                    return false;
                }
                else if($res.res == 'ok')
                {
                    success_func();
//                    $('#sel_wt_form').ajaxSubmit(options);
                }
            }  
        };
    
    $('#sel_wt_form').ajaxSubmit(options);
}



function show_step_3_table()
{
    var $container = $('.form_table_step_3_new_sell'); 
    var $loader = $container.find('.loading-gif'); 
    var $container_div = $container.find('div'); 

    $loader.show();
    $container_div.html('');
     var options = {
            'url': ajax_step_3_new_sell_url,
            success: function(responseText, statusText, xhr, $form)
            {
                if(typeof $loader == 'object' && $loader.length)
                {
                    $loader.hide();
                }
                
                if(typeof $container_div == 'object' && $container_div.length)
                {
                    $container_div.html(responseText);
                }
            }  
        };
    
    $('#sel_wt_form').ajaxSubmit(options);
}



function set_red_border_for_input_step_2_new_sell()
{
    var input_summa_sell_payment_systems = $('input[name^="input_summa_sell_payment_systems"]').not('[type="hidden"]');
    var empty_input = false;
    
    input_summa_sell_payment_systems.css('border', '1px solid #ddd');
    
    input_summa_sell_payment_systems.each(function (){
        var $this = $(this);
        
        if( !($this.val() > 0) )
        {
            $this.css('border', '1px solid red');
            
            empty_input = true;
        }
    });
    
    return empty_input;
}



function add_payment_system_to_quick_list(obj, skip_select_curency)
{
    var container = '';
    var container_text = '';
//    var image = obj.siblings('.onehrineico').clone();
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
    
    var $input_select_currency = $('input[name="select_curency_'+obj_name+'"]');
    
    if($input_select_currency.length && 
            obj.is(':checked') && 
            skip_select_curency != true
        )
    {
//        eval($input_select_currency.data('show-currencys-list'));
        return false;
    }
    
    
    if($input_select_currency.length)
    {
        currency = $input_select_currency.data('currency');
//        console.log(currency);
    }
    else if(currency_id == 840)
    {
        currency = 'USD';
    }
    else if(currency_id == 643)
    {
        currency = 'RUB';
    }
    
//    if(obj.is(':checked') && container.find('input[name="'+obj_name+'"]').length)
//    {
//        return false;
//    }
//    console.log(obj_name);
//    console.log(container);
//    console.log('input[name="input_summa_'+obj_name+'"]');
//    console.log(container.find('input[name="input_summa_'+obj_name+'"]'));
//    console.log(container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div'));
//    console.log(container.find('[name="input_summa_'+obj_name+'"]').parent(), '>>>');
//    console.log(container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div_dop'));
//    console.log(container.find('input[name="'+obj_name+'"]').parent().remove());
    
    container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div').remove();
    container.find('[name="input_summa_'+obj_name+'"]').parent().remove();
    container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div_dop').remove();
    container.find('input[name="'+obj_name+'"]').parent().remove();
    
//    console.log(container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div'));
//    console.log(container.find('[name="input_summa_'+obj_name+'"]').parent(), '>>>2');
//    console.log(container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div_dop'));
//    console.log(container.find('input[name="'+obj_name+'"]').parent().remove());

    if(!container.find('input').length)
    {
        container_text.hide();
    }
    
    var image = obj.siblings('.onehrineico').clone();
    var title = obj.siblings('.onehrinetext').attr('title')|| obj.siblings('.onehrinetext').text();
    
    image.css('border', '1px solid transparent').attr('title', $.trim(title));
    
//console.log(obj.siblings('.onehrineico'));
//console.log(image);
//console.log(obj);

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
//        else if(obj.is(add_root_curr_in_selector('input[name="sell_payment_systems[%s]"]')) && typeof $selectbox_to_wt_sell_arr !== 'undefined' && $is_test_user != 1)
        else if(obj.is(add_root_curr_in_selector('input[name="sell_payment_systems[%s]"]')) && typeof $selectbox_to_wt_sell_arr !== 'undefined')
        {
//            console.log(obj);
            var $select_box = '<select name="input_summa_'+obj_name+'" class="form_select">';
            var root_ps = obj.data('ps-mashin-name');
            var $summ_arr = $selectbox_to_wt_sell_arr[root_ps];
//            console.log(root_ps);
//            console.log($selectbox_to_wt_sell_arr);
//            console.log($selectbox_to_wt_sell_arr[root_ps]);
            
            for( var key in $summ_arr)
            {
                if(obj_summa_val == $summ_arr[key])
                {
                    $select_box += '<option value="'+$summ_arr[key]+'" selected >'+$summ_arr[key]+'</option>';
                }
                else
                {
                    $select_box += '<option value="'+$summ_arr[key]+'">'+$summ_arr[key]+'</option>';
                }
            }
            
            $select_box += '</select>';
            
//            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' <input type="text" value="'+obj_summa_val+'" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
            
            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' '+$select_box+'</div>');
            container.append('<div class="clear"></div>');
           

            sell_amount_keyup($('select[name="input_summa_'+obj_name+'"]'));
        }
//        else if(obj.is(add_root_curr_in_selector('input[name="sell_payment_systems[%s]"]')) && typeof $selectbox_to_wt_sell_arr !== 'undefined' && $is_test_user == 1)
//        {
//            console.log('>>>>>>2');
//            var $select_box = '<select name="input_summa_'+obj_name+'" class="form_select for_test_users">';
//            
//            for( var key in $selectbox_to_wt_sell_arr)
//            {
//                if(obj_summa_val == $selectbox_to_wt_sell_arr[key])
//                {
//                    $select_box += '<option value="'+$selectbox_to_wt_sell_arr[key]+'" selected >'+$selectbox_to_wt_sell_arr[key]+'</option>';
//                }
//                else
//                {
//                    $select_box += '<option value="'+$selectbox_to_wt_sell_arr[key]+'">'+$selectbox_to_wt_sell_arr[key]+'</option>';
//                }
//            }
//            
//            $select_box += '</select>';
//            
//            var $select_box_score = '<select name="input_skore_'+obj_name+'" class="form_select for_test_users">';
//            
//            for( var key in $selectbox_to_wt_sell_arr_skore)
//            {
//                $select_box_score += '<option value="'+$selectbox_to_wt_sell_arr_skore[key]+'">'+$selectbox_to_wt_sell_arr_skore[key]+'</option>';
//            }
//            
//            $select_box_score += '</select>';
//            
//            var local_container = $('<div class="input_container_div_dop" />').css({'float': 'right', 'width': '278px', 'margin-top': '2px'});
//            
//            local_container.append('<div class="input_container_div for_test_user">'+$translate_js_summ+' '+currency+' '+$select_box+'</div>');
//            
////            local_container.append('<div class="input_container_div for_test_user"><div style="width: 76px; display: inline-block;">'+$translate_js_score+':</div> '+$select_box_score+'</div>');
//            
//            local_container.append('<div class="clear"></div>');
//            local_container.append('<div></div>');
//            
//            container.append(local_container);
//
//            sell_amount_keyup($('select[name="input_summa_'+obj_name+'"]'));
//        }
        else if(obj.is(add_root_curr_in_selector('input[name="buy_payment_systems[%s]"]')) && typeof $selectbox_to_wt_buy_arr !== 'undefined' && $is_test_user != 1)
        {
            var $select_box = '<select name="input_summa_'+obj_name+'" class="form_select">';
            
            for( var key in $selectbox_to_wt_buy_arr)
            {
                if(obj_summa_val == $selectbox_to_wt_sell_arr[key])
                {
                    $select_box += '<option value="'+$selectbox_to_wt_buy_arr[key]+'" selected >'+$selectbox_to_wt_buy_arr[key]+'</option>';
                }
                else
                {
                    $select_box += '<option value="'+$selectbox_to_wt_buy_arr[key]+'">'+$selectbox_to_wt_buy_arr[key]+'</option>';
                }
            }
            
            $select_box += '</select>';
            
//            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' <input type="text" value="'+obj_summa_val+'" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' '+$select_box+'</div>');
            container.append('<div class="clear"></div>');
            
            sell_amount_keyup($('select[name="input_summa_'+obj_name+'"]'));
        }
        else if(obj.is(add_root_curr_in_selector('input[name="buy_payment_systems[%s]"]')) && typeof $selectbox_to_wt_buy_arr !== 'undefined' && $is_test_user == 1)
        {
            var $select_box = '<select name="input_summa_'+obj_name+'" class="form_select for_test_users">';
            
            for( var key in $selectbox_to_wt_buy_arr)
            {
                if(obj_summa_val == $selectbox_to_wt_sell_arr[key])
                {
                    $select_box += '<option value="'+$selectbox_to_wt_buy_arr[key]+'" selected >'+$selectbox_to_wt_buy_arr[key]+'</option>';
                }
                else
                {
                    $select_box += '<option value="'+$selectbox_to_wt_buy_arr[key]+'">'+$selectbox_to_wt_buy_arr[key]+'</option>';
                }
            }
            
            $select_box += '</select>';
            
            var $select_box_score = '<select name="input_skore_'+obj_name+'" class="form_select for_test_users">';
            
            for( var key in $selectbox_to_wt_sell_arr_skore)
            {
                $select_box_score += '<option value="'+$selectbox_to_wt_sell_arr_skore[key]+'">'+$selectbox_to_wt_sell_arr_skore[key]+'</option>';
            }
            
            $select_box_score += '</select>';
            
            /*
//            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' <input type="text" value="'+obj_summa_val+'" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' '+$select_box+'</div>');
            container.append('<div class="clear"></div>');
            */
           
            var local_container = $('<div class="input_container_div_dop" />').css({'float': 'right', 'width': '278px', 'margin-top': '2px'});
            
            local_container.append('<div class="input_container_div for_test_user">'+$translate_js_summ+' '+currency+' '+$select_box+'</div>');
            
            local_container.append('<div class="input_container_div for_test_user"><div style="width: 76px; display: inline-block;">'+$translate_js_score+':</div> '+$select_box_score+'</div>');
            
            local_container.append('<div class="clear"></div>');
            local_container.append('<div></div>');
            
            container.append(local_container);

            sell_amount_keyup($('select[name="input_summa_'+obj_name+'"]'));
        }
        else
        {
            container.append('<div class="input_container_div">'+$translate_js_summ+' '+currency+' <input type="text" value="'+obj_summa_val+'" name="input_summa_'+obj_name+'" class="form_input" style="float: right; margin: 9px 10px 0 0;"></div>');
            container.append('<div class="clear"></div>');
        }

        container_text.show();
    }
//    else
//    {
//        container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div').remove();
//        container.find('input[name="'+obj_name+'"]').parent().next('.input_container_div_dop').remove();
//        container.find('input[name="'+obj_name+'"]').parent().remove();
//
//
//        if(!container.find('input').length)
//        {
//            container_text.hide();
//        }
//    }
}



function show_fee_field_in_sell_list_if_select_wt()
{
    var buy_fee_and_ammount = $('.buy_fee_and_ammount');
    var sell_fee_and_ammount = $('.sell_fee_and_ammount');

    if($(add_root_curr_in_selector('.input_container_div select[name="input_summa_buy_payment_systems[%s]"]')).val() > 0)
    {
        sell_fee_and_ammount.hide();
        buy_fee_and_ammount.show();

        return false;
    }

    if($(add_root_curr_in_selector('.input_container_div select[name="input_summa_sell_payment_systems[%s]"]')).val() > 0)
    {
        buy_fee_and_ammount.hide();
        sell_fee_and_ammount.show();

        return false;
    }

    buy_fee_and_ammount.hide();
    sell_fee_and_ammount.show();
}



function show_first_step_summ()
{
//    var div_sell_ps = $('.quick_selected_payment_sell [name^="input_summa_sell_payment_systems"]'); 
//    var checkbox = $('.div_sell_payment_systems input[name^="sell_payment_systems"]').filter(':checked'); 
//    var $currency = checkbox.first().data('currency');

    var div_sell_ps = $('.quick_selected_payment_sell [name^="input_summa_sell_payment_systems"]').first(); 
    var checkbox = $('.div_sell_payment_systems input[name^="sell_payment_systems"]').filter(':checked'); 
    var input_summa_name = div_sell_ps.attr('name').split('[');
    var $currency = checkbox.filter('[name$="'+input_summa_name[1]+'"]').data('currency');


    if(div_sell_ps.length > 0 && $currency)
    {
        $('div.first_step_summ').html(div_sell_ps.val()+' '+$currency);
    }
    else if(div_sell_ps.length > 0)
    {
        $('div.first_step_summ').html(div_sell_ps.val()+' '+$ps_curency_array_currency_id[checkbox.first().data('currency-id')]);
    }
    else
    {
        $('div.first_step_summ').html('');
    }
}



function show_second_step_summ()
{
//    var div_sell_ps = $('.quick_selected_payment_buy [name^="input_summa_buy_payment_systems"]'); 
//    var checkbox = $('.div_buy_payment_systems input[name^="buy_payment_systems"]').filter(':checked'); 
//    var $currency = checkbox.first().data('currency');
    var div_sell_ps = $('.quick_selected_payment_buy [name^="input_summa_buy_payment_systems"]').first(); 
    var checkbox = $('.div_buy_payment_systems input[name^="buy_payment_systems"]').filter(':checked'); 
    var input_summa_name = div_sell_ps.attr('name').split('[');
    var $currency = checkbox.filter('[name$="'+input_summa_name[1]+'"]').data('currency');
    
    
    if(div_sell_ps.length > 0 && $currency)
    {
        $('div.second_step_summ').html(div_sell_ps.val()+' '+$currency);
    }
    else if(div_sell_ps.length > 0)
    {
        $('div.second_step_summ').html(div_sell_ps.val()+' '+$ps_curency_array_currency_id[checkbox.first().data('currency-id')]);
    }
    else
    {
        $('div.second_step_summ').html('');
    }
}



function check_select_currency(checkboxs, show_error)
{
    var error = false;
    
    checkboxs.filter(':checked').each(function(){
        var $this = $(this)
        var name = $this.attr('name');
        var $val = $('[name="select_curency_'+name+'"]').val();
        
        if($('[name="select_curency_'+name+'"]').length && ( $val == 0 || $val == ''))
        {
            error = true;
            return false;
        }
    });
    
    if(error == true && show_error !== false)
    {
//        show_error($translate_js_error_no_selec_ps);
        show_error2($translate_js_error_no_selec_ps);
    }
        
    return !error;
    
}



function show_error2(text)
{
    var $container = $('.popup_window_error');
    
    $container.find('span').html(text);
    
    show_popup_window_and_center_display($container);
}
