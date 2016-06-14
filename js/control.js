jQuery(function($){
    var device = 0, //desktop
        window_height = 0,
        window_width = 0,
        head_height = 0,
        page_height = 0,
        bottom_height = 0,
        min_page_height = 570, //min page hight
        max_page_height = 1300, //min page hight
        header = $('#header'),
        first_poly = [],
        middle_poly = [],
        last_poly = [],
        graph_link = null,
		page_type = 1;
				
        
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
        device = 1;//mobile
    
    
    //size was changed
    function resize_window( _refresh ){
        window_height = $(window).height();
        bottom_height = $('#page-social .page-social_counters-bar').height();
        window_width = $(window).width();		
		
        head_height = $('#header').height()+parseInt( $('#header').css('border-top-width') )+parseInt( $('#header').css('border-bottom-width') );        
		
		page_height = window_height - head_height;
//		
        if( window_height > max_page_height){
			page_height = window_height/2 - head_height/2;
			page_type = 2;			
		}
		
		if( page_height < min_page_height)
			page_height = min_page_height;
		
		

//        $('#page-social').css( 'padding-top', head_height+8+'px' );
        $('.page').height( page_height+'px' );
        $('#page-social .page-wrapper_img').css( {height:(page_height-bottom_height)+'px',width:window_width+'px'} );
        
        
//        if( device && _refresh ) location.reload();
    }
    function graph(){
        polylineCalculate( last_poly[0], x, false );
        polylineCalculate( last_poly[1], x, false );
        polylineCalculate( last_poly[2], x, true );
        
        if( j >= 0 ){
            polylineCalculate( middle_poly[0], j, false );
            polylineCalculate( middle_poly[1], j, false );
            polylineCalculate( middle_poly[2], j, true );            
        }else
            $('#page-grow .page-grow_graphic.middle').addClass('visible');
        
        if( k >= 0 ){
            polylineCalculate( first_poly[0], k, false );
            polylineCalculate( first_poly[1], k, false );
            polylineCalculate( first_poly[2], k, true );
        }else
            $('#page-grow .page-grow_graphic.first').addClass('visible');
        k -= 2;
        x -= 20;
        j -= 8;
        if( x <= 0 ){
            $('#page-grow .page-grow_graphic.last').addClass('visible');
            clearInterval( graph_link );
        }
    }
    
    function scroll_window(){
        if( page_type == 1 && $(window).scrollTop() >= $('#page-three-blocks').position().top - 500||
			page_type == 2 && $(window).scrollTop() >= $('#page-three-blocks').position().top - page_height )
            $('#page-three-blocks .page-three-blocks_block').addClass('shown');
//            $('#page-three-blocks .page-three-blocks_block p, \n\
//                #page-three-blocks .page-three-blocks_block a')
//            .animate({opacity:1},1500,function(){
//                $('#page-three-blocks .page-three-blocks_block').addClass('shown');
//            });
            
//        if( page_type == 1 && $(window).scrollTop() >= $('#page-grow').position().top-500 && graph_link == null ||
//			page_type == 2 && $(window).scrollTop() >= $('#page-grow').position().top-page_height && graph_link == null)
//            graph_link = setInterval(graph,100);
    }
    
    //events
    $(window).resize( resize_window );
    $(window).on( "orientationchange", function(){ resize_window(1); });
    $(window).scroll( scroll_window );
    
    var top = 0, left = 0, width = 0, left_sert = 0;
    function click(){
        
        if( $('#certificate-big').hasClass('showed') ){
            
            $('#certificate-big').animate({ width:'249px'},300,function(){
                $(this).css('display','none').removeClass('showed');
            });
            $('#curtain').animate({opacity:0},300,function(){
                $('#curtain').css('display','none');
            });
            
            return false;
        }
                
        top = (window_height/5); 
        left = $('#certificate').position().left;
        width = (window_width/2);
        left_sert = left - (window_width - width)/2 + 70;
       
        
        $('#curtain').css('display','block').animate({opacity:1});
        $('#certificate-big')
            .css({'display':'block'})
            .animate({width:width+'px'},300,
            function(){
                $(this).addClass('showed');
            }
        );
        return false;
    }
	 $('#wt-login').click(function() {

        $("#wt-login-form").fadeIn();
    }).css('cursor', 'pointer');
    $('#certificate-link').click( click );    
    $('.close').click(function() {
        $(".popup").fadeOut();
        $('.parentFormvalidate').css('display', "none")
    })
    $('#certificate-link').mouseover(function(){        
        $('#certificate').css({'opacity':0,'display':'block'}).animate({opacity:1},500);
    }); 
    $('#certificate-link').mouseleave(function(){        
        $('#certificate').animate({opacity:0},300,function(){ $(this).css({display:'none'}); });
    }); 
    $('#curtain').click( click );
    
    function polylineToArray( _path ){
            var points = $(_path).attr('points'),                
                obj = [],
                ar = null,
                o_ar = null;
            
            ar = points.split(' ');
            
            for( var i = 0; i < ar.length; i++ ){
                if( ar[i] == '' ) continue;                
                o_ar = ar[i].split(',');
                
                if( o_ar.length == 1 ) continue;
                
                obj.push( parseFloat(o_ar[0]) );
                obj.push( parseFloat(o_ar[1]) );
            }
            return { obj: $(_path),path: obj };
        }        
        
        
    //calculate all the points of the polyline
    function polylineCalculate( _obj, _h, _all ){           
        if( _obj.obj == undefined ||
            _obj.obj != undefined && _obj.obj.length == 0 ) return false;

        var new_obj = [],
            obj_len = _obj.path.length;

        for( var i = 0; i <obj_len; i += 2 ){            
            if( (i == 0 
                    || i+2 == obj_len 
                    && _all !== true
                    ) 
                    ||_all == true 
             ) {
                new_obj.push( (_obj.path[i])+','+(_obj.path[i+1]+_h) );
             }
            else
                new_obj.push( _obj.path[i]+','+_obj.path[i+1]);
        }


        var new_path = new_obj.join(' ');            
        $( _obj.obj ).attr('points',new_path);
    }
        
	//on load - loading all the pathes to the paths variable
    function svgLoad(){        
        $('#page-grow .page-grow_graphic.first polyline').each(function() {
            first_poly.push( polylineToArray($(this)) );
        });
        $('#page-grow .page-grow_graphic.middle polyline').each(function() {
            middle_poly.push(polylineToArray($(this)));
        });
        $('#page-grow .page-grow_graphic.last polyline').each(function() {
            last_poly.push(polylineToArray($(this)));
        });

	}
  
    //change language        
    if( $('#about-us_lang li').length != 0 )
        $('#about-us_lang li').click(function(){
            var lang = $(this).attr('data-url'),   
                href = window.location.href;

            //get domain and path
            href = href.match('^(http[s]{0,1}://[\\w\\d.-]*/)(.*)$');        
            //add language
            

            $('#about-us_lang .about-us_lang_select').html( $(this).html() );
            $('#about-us_lang .about-us_lang_list').slideUp(200, function(){
                $(this).css('display','');
                if( href )window.location.href = href[1]+lang+href[2];;
                
            });
        });
        
		
	
//	$('#google_translate_element').val('en').change();
					
//	console.log( Math['random']()*1E9 );
		
    $('#slider .slides').htmSlider();    
    //first time
    resize_window();
    svgLoad();
    
    var  x = 380, j = 105, k = 28;
    
    
    //graph();
});