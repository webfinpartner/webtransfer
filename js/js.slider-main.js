/**
 * Slider was written by Artem Drachkov.
 * It was written for private usage and is not supported permanently.
 * e-mail:artem.prof(at)gmail.com
 */
(function($)
{

var DATA_CMDS = 'slider-menu';

    
    function getInt( _str ){
        var str = _str.substring(0, _str.length-2);

        return parseInt( str );
    }
    
    var SliderMain = function(alt, options){
        var $this = alt,
            menuName = '';
        
        var Menu = function( slider, alt, menuName, id ){
                       var menu = null,
                           li = null,
                           lis = null,
                           items = new Array(10);
                           
                       var contructor = function( menuName ){
                               menu = $(alt).find(menuName);
                               if( menu.length == 0 ) return 0;                               
                           };
                       //add item menu    
                       this.add = function(_str, active, id ){
                           if( options.noTextLinks ) _str = '';
                           menu.append( '<li><a href="">'+_str+'</a></li>' );
                           li = menu.find('li').last();
                           
                           if( active != null ) li.addClass('first');
                           items[ id ] = li;
                           
                           lis = menu.find( 'li' );
                           
                           li.bind('click',function(){
                               slider.showSlideId( id );                               
                               return false;
                           });
                       }
                       //activate item menu    
                       this.activeId = function(id){
                           lis.removeClass('active');
                           items[id].addClass('active');
                       }
                       //arrangement menu
                       this.after = function(){
                           /*
                           var count = lis.length,
                               w = 0;
                           for( var i = 0; i<count;i++ )
                               w += items[i].width();
                           
                           if( w > menu.width() ) w = 0,9*w;
                           
                           var ww = Math.floor( (menu.width() - w -(count - 1))/(count*2 ) );
                           for( i = 0; i<count;i++ ){
                               //items[i].css( 'padding-left',ww );
                               //items[i].css( 'padding-right',ww );
                               items[i].width(ww*2+items[i].width());
                           }
                           */
                       }
                       contructor( menuName );
                   };
        var Sliders = function( alt, name, menu, options ){
                       var slide = null,
                           menuItems = null,
                           ul = null,
                           lis = null,
                           li = null,
                           nowId = 0,
                           count = 0,
                           blocked = 0,
                           nextSlide = 0,
                           nextPrev = 0
                        ;
                       var contructor = function( name, menu ){
                               slide = $(alt).find(name);
                               ul = slide.find('ul');
                               lis = ul.find('li');
                               li = lis.first();
                               
                               if( slide.length == 0 ) return 0;
                               menuItems = new Array( lis.length );
                               var span = '';

                               for( var i = 0; i < lis.length; i++ ){
                                   if( menu != null ) span = li.find('span');
                                   menuItems[i] = li;
                                   if( menu != null ) menu.add( ((span.length != 0)?span.html():'Слайд'), (i==0)?1:null, i );
                                   if( i == 0 ) li.css('left',0);
                                   else
                                       li.css('left',-li.width());
                                   li = li.next();
                               }                               
                               
                               
                               
                               count = lis.length;
                               if( menu != null ) menu.activeId( nowId );
                           };
                       //show any slide
                       this.showSlideId = function(id, prev){
                           
                           if( id == nowId ) return 0;
                           if( prev == null ) prev = (id<nowId)?0:1;
                           if( blocked ){
                               nextSlide = id;
                               nextPrev = prev;
                               return false;
                           }
                           nextSlide = -1;
                           blocked = 1;
                           var width = menuItems[ id ].width(),
                               begIdPos = 0,
                               endIdPos = 0,
                               begNowIdPos = 0;
                           
                           if( prev ){
                                begIdPos = width;
                                begNowIdPos = -width;
                           }else{
                                begIdPos = -width;
                                begNowIdPos = width;
                           }
                           
                           menuItems[ id ].css('left', begIdPos );
                                
                           menuItems[ nowId ].animate({
                               left:begNowIdPos
                           },options.animate);

                            menuItems[ id ].animate({
                                left:endIdPos
                            },options.animate,function(){
                                
                                if( menu != null ) menu.activeId( id );
                                blocked = 0;
                                if( nextSlide!= -1 ) showSlideId( nextSlide, nextPrev );
                            });    

                           nowId = id;
                       } 
                       var showSlideId = this.showSlideId;
                       
                       this.showNextSlide = function(){
                           
                           if(count == 1) return false;
                           
                           var i = ( nowId + 1 >= count )?0:nowId+1;
                           showSlideId( i, true );
                       }
                       this.showPrevSlide = function(){
                           if(count == 1) return false;
                           var i = ( nowId - 1 < 0 )?count-1:nowId-1;
                           showSlideId( i, false );
                       }
                       contructor( name, menu );
                   };
        
        this.showSlideId = function(id){
            timerStop();
            sliders.showSlideId(id);
            timerStart();
        }
        
        var menu = null,
            sliders = null;
        this.timerStart = function(){
            
            if( options.period != null) 
            $this.everyTime(options.period,'slider',function(){
                sliders.showNextSlide();
                
            });
        }
        var timerStart = this.timerStart;
        
        this.timerStop = function(){
            if( options.period != null)  $this.stopTime('slider');
        }
        var timerStop = this.timerStop;
        //parce additional options
        function parceOptions(){
            options.noTextLinks = 0;
            
            if( options.options.indexOf('no-textlinks') != -1 ) options.noTextLinks = 1;            
        }
        //main constructor
        function contructor(elt){
            parceOptions();
            
            if( options.animate == null ) options.animate = 500;
            
            if( options.menuName != null ) menu = new Menu( elt, alt, options.menuName );
            
            sliders = new Sliders( alt, '.wrapper', menu, options );

            if( options.menuName != null ) menu.after();

            if( options.period != null ) timerStart();
            
        }
        
        
        $this.find(options.prev).click(function(){
            
            timerStop();
            sliders.showPrevSlide();
            timerStart();
        });
        $this.find(options.next).click(function(){
            timerStop();
            sliders.showNextSlide();
            timerStart();
        });
        
        contructor(this);
    }
    
    $.fn.slider_main = function(options){
           
        if( $(this).length == 0 ) return 0;
           
        var slider = new SliderMain(this,options);
           
        return slider;
    };

})(jQuery);

