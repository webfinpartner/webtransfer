
$(document).on('click','.btn-group.bootstrap-select .btn', function(){
    $('.btn-group.bootstrap-select').find('div.dropdown-menu').toggleClass('open');
});
$(document).on('click','.btn-group.bootstrap-select div.dropdown-menu li', function(){
    var text = $(this).find('.text').html();
    var index = $(this).data('original-index');
    
    $('.btn-group.bootstrap-select').find('div.dropdown-menu').toggleClass('open');
    $('.btn-group.bootstrap-select').find('.filter-option').html( text );
    $('.btn-group.bootstrap-select').find('input').val( index );
    
});

if( mn === undefined ) var mn = {};

mn.base_url = '';
mn.get_sec_data_url = '/'+mn.site_lang+'/security/ajax/get_sec_data';

mn._e = function( str ){
    
    return _e( str );
}

mn.get_ajax = function( uri ,data, call_back, params )
{
    console.log('data',data);
    
    $.ajax({
        method: "POST",
        type: "POST",
        url: uri,
        data: data
      }).complete(
        function(jqXHR, status){
            
            var result = jqXHR.responseText;
            console.log('get_ajax',jqXHR, status);
            
            var res, cb = call_back;
            if( !result || status != 'success' )
            {
                mn.send_wrong_answer(result,uri,'wrong_responce');
                
                if( cb && mn.security_module.window.visible === true )
                {
                    return cb( {'error': mn._e( 'wrong_responce' )}, params );
                }else{
                    return mn.security_module.loader.show(mn._e( 'wrong_responce' ), 20000);
                }
            }
            
            try {
                res = JSON.parse(result);
                console.log(res);
            } catch (e) {
                console.log('cb_1');
                mn.send_wrong_answer(result,uri,'wrong_responce');
                if( cb && mn.security_module.window.visible === true )
                {
                    return cb( {'error': mn._e( 'wrong_responce' )}, params );
                }else
                    return mn.security_module.loader.show(mn._e( 'wrong_responce' ), 20000);
                
            }

            console.log('cb_2');
            //mn.send_wrong_answer(result,uri,'wrong_responce');
            if( cb ) return cb( res, params );
        });
    
};

mn.send_wrong_answer = function(){
    //mn.get_ajax();
}
mn.die = function(){
    console.log( arguments );
};
mn.empty = function( o ){
    console.log('empty',o, isNaN( o ));
    if( o === undefined || o === '' ) return true;
    
    return false;
}

mn.security_module = {  
    security_type : null,
    security_request_code_url: null,
    security_check_code_url: null,
    purpose : null,
    get_security_data : function( purpose , call_back, security_type )
    {
        console.log( 'get_security_data',purpose , call_back, security_type );
        var data = {}, cb = call_back;
        data['purpose']  = purpose;
        
        if( security_type !== undefined ) data['security_type']  = security_type;
        
        mn.get_ajax( mn.base_url + mn.get_sec_data_url, data, cb );
    },
    call_back_security_data : function( result )
    {
        console.log( 'call_back_security_data',result );
        if( result['error'] )
        {
            console.log('error');
            mn.security_module.loader.show( result['error'], 20000 );
            return;
        }

        if( result['success'] )
        {
            mn.security_module.loader.hide();
            //var data = result['res'];
console.log('success');
//            console.log( result['form'] );
//            console.log( result['security_type'] );

            mn.security_module.security_type = result['security_type'];
            mn.security_module.security_request_code_url = result['security_request_code_url'];
            mn.security_module.security_check_code_url = result['security_check_code_url'];
            
            if( result['form'] && result['security_type'] != null )
            {
                mn.security_module.window
                        .load_html( result['form'] )
                           .show();
            }else{
                mn.security_module.call_back_fn({ 'res' : 'success', 'security_type': null });
            }
            return;
        }
        console.log('v3');
        mn.security_module.loader.show(mn._e('incorrect_responce'), 'error');
    },
    
    window:{
        window_handle:null,
        window_loader_handle:null,
        visible: false,
        centered : function(html){   
            var lh = mn.security_module.window.window_handle;
            var lw = lh.width();
            var dw = $(document).width();
            lh.css('left', Math.round( (dw - lw)/2 ) );
            return this;
        },
        loader :{
            handle: null,
            show: function(){   
                mn.security_module.window.loader.handle.fadeIn(300);
                return this;
            },
            hide: function(fnc){   
                mn.security_module.window.loader.handle.fadeOut(300,fnc);
                return this;
            },
        },
        send_button :{
            handle: null,
            show: function(){   
                mn.security_module.window.send_button.handle.slideDown(300);
                return this;
            },
            hide: function(){   
                mn.security_module.window.send_button.handle.slideUp(300);
                return this;
            },
        },
        show_voice:function(){
            mn.get_ajax( mn.base_url + '/account/ajax_user/voice_state', [], 
            function(res){
                if( res['state'] === undefined ) return false;
                
                if (res['state'] == true)
                    mn.security_module.window_handle.find('.panel-footer.choose').show();
            });
        },
        message_timer:{
            handle: null,
            time: 60,
            message: '', 
            counter_handle: null, 
            set_time:function(){
                
                var mt = mn.security_module.window.message_timer;
                
                $(mt.counter_handle).html( mt.time );
                console.log('time', $(mt.counter_handle), mt.time);
                return this;
            },
            start:function(message, counter_handle, time){
                console.log( 'message_timer',message, counter_handle, time );
                var mt = mn.security_module.window.message_timer;
                var mh = mn.security_module.window.message.handle;
                
                mt.time = time;
                
                mt.message = message;
                mn.security_module.window.message.show( mt.message, 'success' );
                mt.counter_handle = mh.find(counter_handle);
                
                mn.security_module.window.message_timer.set_time( time );
                
                if( mt.handle !== undefined ) clearInterval( mt.handle );
                
                mt.handle = 
                setInterval(function() {
                    mn.security_module.window.message_timer.set_time( mt.time );
                    if (mt.time - 1 < 0) {
                          mn.security_module.window.send_button.show();
                          mn.security_module.window.message_timer.stop();                          
                          mn.security_module.window.show_voice();                          
                    }
                    mt.time--;
                  }, 1000);                
            },
            stop:function(){
                var mt = mn.security_module.window.message_timer;
                var mh = mn.security_module.window.message.handle;
                clearInterval( mt.handle );
                mn.security_module.window.message.hide();
                mn.security_module.window.send_button.show();
            },
        },
        message : 
        {
            handle: null,
            show: function(text, type, time){
                console.log( 'show_message' );
                this.handle.html(text).removeClass('error').removeClass('wrong').removeClass('success').addClass(type).slideDown();
                if( time === undefined ) return false;
                if( time === undefined || time <= 5000 ) time = 5000;
                
                setTimeout(function(){
                    mn.security_module.window.message.hide();
                },time);
            },
            hide : function(){
                console.log( 'hide_message' );
                this.handle.slideUp();
            }
        },
        input:{
            handle : null,
            light_on_empty: function(){
                console.log('light_on_empty');
                if( mn.empty( mn.security_module.window.input.val() ) === true )
                {                    
                    this.light_on();
                    return true;
                }

                this.light_off();
                return false;
            },
            light_on: function(){
                console.log('light_on');
                mn.security_module.window.input.handle.addClass('wrong');
            },
            light_off: function(){
                console.log('light_off');
                mn.security_module.window.input.handle.removeClass('wrong');
            },
            val: function( val ){
                console.log('input.val');
                if( val !== undefined )
                {
                    mn.security_module.window.input.handle.val( val );
                    return this;
                }
                return mn.security_module.window.input.handle.val();
            }
        },
        select:{
            handle : null,
            
            val: function( val ){
                console.log('input.val');
                if( mn.security_module.window.select.handle.lenght == 0 ) return false;
                if( val !== undefined )
                {
                    mn.security_module.window.select.handle.val( val );
                    return this;
                }
                return mn.security_module.window.select.handle.val();
            }
        },

        show : function(){
            console.log('show');
            if( !this.window_handle || this.window_handle === undefined ) return mn.die('window-show: wrong handle');
            
            this.visible =  true;
            this.centered();
            mn.security_module.backdrop.show();
            this.window_handle.fadeIn(300);
            
            return this;
        },
        hide : function(){
            console.log('hide');
            if( !this.window_handle || this.window_handle === undefined ) return mn.die('window-hide: wrong handle');
            this.visible =  false;
            mn.security_module.backdrop.hide();
            this.window_handle.fadeOut(300);
            return this;
        },
        load_html : function(html){
            console.log('load_html',html);
            var handle = $( html );
            
            
            mn.security_module.backdrop.handle = handle.find('.modal-backdrop').hide();
            
            this.window_handle = handle.find('#confirmDialog').hide();
            this.loader.handle = this.window_handle.find('.loader');
            
            this.message.handle = this.window_handle.find('.res-message');
            this.send_button.handle = this.window_handle.find('.send_button');
            this.input.handle = this.window_handle.find('.form_input');
            this.select.handle = this.window_handle.find('.btn-group.bootstrap-select input[name="lang"]');
            
            $('#sm').remove();
            $('body').append( handle );
            return this;
        }
    },
    loader:{        
        loader_handle:$('#security_module_loading'),
        loader_message_handle:$('#security_module_loading .msg_text'),        
        visible: false,
        default_message: mn._e('wait_processing_request'),
        show : function( text, time ){
            console.log('loader-show');
                        
            
            if( !this.loader_handle || this.loader_handle === undefined ) return mn.die('window-show: wrong handle');
            
            this.centered();            
            if( text !== undefined ) this.set_message(text);
            else
                this.set_default_message();
            
            this.visible = true;
            this.loader_handle.fadeIn(300);
            
            if( time !== undefined && time > 3000 && time < 30000)
                setTimeout(function(){
                    mn.security_module.loader.hide();
                }, time);
            
            
            return this;
        },
        set_message:function( text ){
            console.log('set_message',text);
            if( mn.empty( text ) ) return false;
            
            this.loader_handle.removeClass('no-close');
            
            this.loader_message_handle.html( text );
            this.ring.hide();
        },
        ring:{
            show:function(){
                mn.security_module.loader.loader_handle.removeClass('no-ring');
            },
            hide:function(){
                mn.security_module.loader.loader_handle.addClass('no-ring');
            }
        },
        set_default_message:function(){
            this.set_message( this.default_message );
            this.loader_handle.addClass('no-close')
                                .removeClass('no-ring');
            this.ring.show();
        },
        hide : function(fnc){
            console.log('loader-hide');
            if( !this.loader_handle || this.loader_handle === undefined ) return mn.die('window-hide: wrong handle');
            this.visible = false;
            console.log( this.loader_handle );
            
                        
             this.loader_handle.fadeOut(300,function(){
                 mn.security_module.loader.loader_handle.css({'opacity':''}).removeClass('no-ring').addClass('no-close');
                
                if( fnc !== undefined ) fnc();
            });            
            
            return this;
        },
        centered : function(html){   
            var lh = mn.security_module.loader.loader_handle;
            var lw = lh.width();
            var dw = $(document).width();
            lh.css('left', Math.round( (dw - lw)/2 ) );
        },
        init : function(html){    
            console.log( 'loader-init' );
            if( html !== undefined )
            {
                this.loader_handle = $( loader_handle ).hide();
                $('body').append( this.loader_handle );
            }
            this.centered();
            
            return this;
        },
    },
    
    backdrop:{
        handle: null,
        show: function(){   
            mn.security_module.backdrop.handle.fadeIn(300);
            return this;
        },
        hide: function(fnc){   
            mn.security_module.backdrop.handle.fadeOut(300,fnc);
            return this;
        },
    },
    
    show_window : function( purpose, security_type ){
        console.log( 'show_window' );
        this.loader.show();
        
        this.purpose = purpose;
        this.security_type = security_type;
         
        this.get_security_data(purpose, this.call_back_security_data, security_type);
        return this;
    },
    change_security_type:function( purpose, security_type ){
        console.log( 'change_security_type' );
        //mn.security_module.window.hide();
        
        
        setTimeout(function(){
//            mn.security_module.window.handle.remove();
            mn.security_module.loader.show();
        
            mn.security_module.purpose = purpose;
            mn.security_module.security_type = security_type;

            mn.security_module.get_security_data(purpose, mn.security_module.call_back_security_data, security_type); 
        },300);
    },
    call_back_confirm_code : function( result ){
        mn.security_module.window.loader.hide();
        
        console.log( 'call_back_confirm_code',result );
        
        if (result['action']) {
            if (result['action'] == 'change_element') {
                $(result['data']['id_element']).html(result['data']['content']);
            }
        }
        
        if( result['error'] )
        {
            console.log('error');
            mn.security_module.window.message.show( result['error'] , 'error');
            return false;
        }

        if( result['success'] )
        {
            
            if( mn.security_module.call_back_fn && typeof mn.security_module.call_back_fn !== undefined  ) 
                mn.security_module.call_back_fn({ 'res' : 'success', 'code' : mn.security_module.window.input.val() });
               
            mn.security_module.window.hide();
console.log('success-1-');

            return false;
        }
        
        console.log('v3');
        
        
        if( mn.security_module.call_back_fn && typeof mn.security_module.call_back_fn !== undefined  ) 
                mn.security_module.call_back_fn({ 'res' : 'success', 'code' : mn.security_module.window.input.val() });
            
        mn.security_module.window.hide();
        
    },
    confirm_code : function(){
        console.log( 'confirm_code',this.purpose , this.security_type );
        
        if( this.window.input.light_on_empty() === true ) return false;
        
        var data = {};//, cb = call_back;
        data['purpose']  = this.purpose;
        data['code']  = this.window.input.val();
        
        
        this.window.loader.show();
        console.log( '-1-' );
        
        if( mn.security_module.security_type !== undefined ) data['security_type']  = mn.security_module.security_type;
        
        console.log( '-2-' );
        
        mn.get_ajax( mn.base_url + mn.security_module.security_check_code_url, data, mn.security_module.call_back_confirm_code );
        
        console.log( '-3-' );
        return false;
    },
    call_back_get_code : function( result )
    {
        console.log('call_back_get_code',result);
        mn.security_module.window.loader.hide(function(){
            console.log('call_back_get_code-2-',result);
            if( result['error'] )
            {
                console.log('error');
                mn.security_module.window.message.show( result['error'] , 'error');
                mn.security_module.window.send_button.show();
                return;
            }

            if( result['success'] )
            {
                if( result['action'] && result['action'] == 'start-counter' )
                {
                    mn.security_module.window.message_timer.start( result['success'],result['counter-handle'],result['counter-time'] );

                    return false;
                }
    console.log('success');


                return false;
            }
            console.log('v3');
            this.window.message.show(mn._e('incorrect_responce'), 'error');
            mn.security_module.window.send_button.show();
        });
        
    },
    close:function(){
        this.window.hide();
        this.backdrop.hide();
        if( mn.security_module.call_back_fn && typeof mn.security_module.call_back_fn !== undefined  ) 
                mn.security_module.call_back_fn({ 'res' : 'closed' });
    },
    get_code : function(){
        console.log( 'get_code',this.purpose , this.security_type );
        
        var data = {};//, cb = call_back;
        data['purpose']  = this.purpose;
        data['lang']  = this.window.select.val();
        
        this.window.message.hide();
        this.window.loader.show();        
        console.log( '-1-' );
        
        if( mn.security_module.security_type !== undefined ) data['security_type']  = mn.security_module.security_type;
        
        console.log( '-2-' );
        
        mn.security_module.window.send_button.hide();
        
        mn.get_ajax( mn.base_url + mn.security_module.security_request_code_url, data, mn.security_module.call_back_get_code );
        
        console.log( '-3-' );
        return false;
    },
    
    confirm_code_by_enter: function(event){
        console.log( 'confirm_code_by_enter' );
        var charCode = (typeof event.which === "number") ? event.which : event.keyCode;
        console.log( 'confirm_code_by_enter', charCode );
        if( charCode == 13 )
        {
            mn.security_module.confirm_code();
            return false;
        }
        return true;
    },
    
    done : function( fn ){
        console.log( 'this.call_back_fn' );
        this.call_back_fn = fn;
        return this;
    },
    init : function(){
        console.log( 'sec_mod-init' );
        mn.security_module.loader.init();        
        return this;
    }
};


//console.log('---0---');
//    mn.security_module
//        .init()
//        .show_window('save_p2p_payment_data')
//        .done(save_user_payment_information_secure);
//console.log('---1---');