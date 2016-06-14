/* SHARED VARS */
var firstrun = true,
    touch = false,
    clickEv = 'click';


var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};


/* #jQuery appear
================================================== */
(function(e){e.fn.appear=function(t,n){var r=e.extend({data:undefined,one:true,accX:0,accY:0},n);return this.each(function(){var n=e(this);n.appeared=false;if(!t){n.trigger("appear",r.data);return}var i=e(window);var s=function(){if(!n.is(":visible")){n.appeared=false;return}var e=i.scrollLeft();var t=i.scrollTop();var s=n.offset();var o=s.left;var u=s.top;var a=r.accX;var f=r.accY;var l=n.height();var c=i.height();var h=n.width();var p=i.width();if(u+l+f>=t&&u<=t+c+f&&o+h+a>=e&&o<=e+p+a){if(!n.appeared)n.trigger("appear",r.data)}else{n.appeared=false}};var o=function(){n.appeared=true;if(r.one){i.unbind("scroll",s);var o=e.inArray(s,e.fn.appear.checks);if(o>=0)e.fn.appear.checks.splice(o,1)}t.apply(this,arguments)};if(r.one)n.one("appear",r.data,o);else n.bind("appear",r.data,o);i.scroll(s);e.fn.appear.checks.push(s);s()})};e.extend(e.fn.appear,{checks:[],timeout:null,checkAll:function(){var t=e.fn.appear.checks.length;if(t>0)while(t--)e.fn.appear.checks[t]()},run:function(){if(e.fn.appear.timeout)clearTimeout(e.fn.appear.timeout);e.fn.appear.timeout=setTimeout(e.fn.appear.checkAll,20)}});e.each(["append","prepend","after","before","attr","removeAttr","addClass","removeClass","toggleClass","remove","css","show","hide"],function(t,n){var r=e.fn[n];if(r){e.fn[n]=function(){var t=r.apply(this,arguments);e.fn.appear.run();return t}}})})(jQuery);



/* Fucntion get width browser */
function getWidthBrowser() {
	"use strict";
	var myWidth;

	if( typeof( window.innerWidth ) == 'number' ) { 
		//Non-IE 
		myWidth = window.innerWidth;
		//myHeight = window.innerHeight; 
	} 
	else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) { 
		//IE 6+ in 'standards compliant mode' 
		myWidth = document.documentElement.clientWidth; 
		//myHeight = document.documentElement.clientHeight; 
	} 
	else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) { 
		//IE 4 compatible 
		myWidth = document.body.clientWidth; 
		//myHeight = document.body.clientHeight; 
	}
	
	return myWidth;
}

/* Function: Refresh Zoom */
function alwaysUpdateZoom(){
	"use strict";
	
  if(touch == false){
    
    if($('.elevatezoom').length){
      
      var zoomImage = $('.elevatezoom .img-zoom');

      zoomImage.elevateZoom({
        gallery:'gallery_main', 
        galleryActiveClass: 'active', 
        zoomType: 'window',
        cursor: 'pointer',
        zoomWindowFadeIn: 300,
        zoomWindowFadeOut: 300,
		zoomWindowWidth: 330,
		zoomWindowHeight: 400,
        scrollZoom: true,
        easing : true
      });
      
      
        //pass the images to Fancybox
        $(".elevatezoom .img-zoom").bind("click", function(e) {  
          var ez =   $('.elevatezoom .img-zoom').data('elevateZoom');	
          $.fancybox(ez.getGalleryList(),{
            closeBtn  : false,
            helpers : {
              buttons	: {}
            }
          });
          return false;
        });
      
    }
    
  }
       // is touch
       else{
         
       }
}
      
// handle quickshop position
function positionQuickshop(){
  "use strict";
  if(touch == false){
    var quickshops = $('.quick_shop');
    
    quickshops.each(function() {
      var parent = $(this).parents('.hoverBorder');
      $(this).css({
        'top': ((parent.height() / 2) - ($(this).outerHeight() / 2)) + 'px',
        'left': ((parent.width() / 2) - ($(this).outerWidth() / 2)) + 'px',
      });
    });
  }
}

   
// handle Animate
function handleAnimate(){
	"use strict";
	
  if(touch == false){
    $('[data-animate]').each(function(){
      
      var $toAnimateElement = $(this);
      
      var toAnimateDelay = $(this).attr('data-delay');
      
      var toAnimateDelayTime = 0;
      
      if( toAnimateDelay ) { toAnimateDelayTime = Number( toAnimateDelay ); } else { toAnimateDelayTime = 200; }
      
      if( !$toAnimateElement.hasClass('animated') ) {
        
        $toAnimateElement.addClass('not-animated');
        
        var elementAnimation = $toAnimateElement.attr('data-animate');
        
        $toAnimateElement.appear(function () {
          
          setTimeout(function() {
            $toAnimateElement.removeClass('not-animated').addClass( elementAnimation + ' animated');
          }, toAnimateDelayTime);
          
        },{accX: 0, accY: -100},'easeInCubic');
        
      }
    });
  }
}
    
// handle scroll-to-top button
function handleScrollTop() {
  "use strict";
  function totop_button(a) {
    var b = $("#scroll-to-top");
    b.removeClass("off on");
    if (a == "on") { b.addClass("on") } else { b.addClass("off") }
  }
  
  $(window).scroll(function() {
    var b = $(this).scrollTop();
    var c = $(this).height();
    if (b > 0) { 
      var d = b + c / 2;
    } 
    else { 
      var d = 1 ;
    }
    
    if (d < 1e3 && d < c) { 
      totop_button("off");
    } 
    else {
      
      totop_button("on"); 
    }
  });
  
  $("#scroll-to-top").click(function (e) {
    e.preventDefault();
    $('body,html').animate({scrollTop:0},800,'swing');
  });
}
      
/* Function update scroll product thumbs */
function updateScrollThumbs(){
	"use strict";
  if($('#gallery_main').length){
    
    if(touch){
      $('#product-image .main-image').on('click', function() {
        var _items = [];
        var _index = 0;
        var product_images = $('#product-image .image-thumb');
        product_images.each(function(key, val) {
          _items.push({'href' : val.href, 'title' : val.title});
          if($(this).hasClass('active')){
            _index = key;
          }
        });
        $.fancybox(_items,{
          closeBtn: false,
          index: _index,
          openEffect: 'none',
          closeEffect: 'none',
          nextEffect: 'none',
          prevEffect: 'none',
          helpers: {
            buttons: {}
          }
        });
        return false;
      });
    }
    else{
      
    }

    $('#product-image').on('click', '.image-thumb', function() {

      var $this = $(this);
      var background = $('.product-image .main-image .main-image-bg');
      var parent = $this.parents('.product-image-wrapper');
      var src_original = $this.attr('data-image-zoom');
      var src_display = $this.attr('data-image');
      
      background.show();
      
      parent.find('.image-thumb').removeClass('active');
      $this.addClass('active');
      
      parent.find('.main-image').find('img').attr('data-zoom-image', src_original);
      parent.find('.main-image').find('img').attr('src', src_display).load(function() {
        background.hide();
      });
      
      return false;
    });
  }
}
    
/* Function update scroll product thumbs */
function updateScrollThumbsQS(){
	"use strict";
  if($('#gallery_main_qs').length){
    
    $('#quick-shop-image .fancybox').on(clickEv, function() {
      var _items = [];
      var _index = 0;
      var product_images = $('#gallery_main_qs .image-thumb');
      product_images.each(function(key, val) {
        _items.push({'href' : val.href, 'title' : val.title});
        if($(this).hasClass('active')){
          _index = key;
        }
      });
      $.fancybox(_items,{
        closeBtn: true,
        index: _index,
        helpers: {
          buttons: {}
        }
      });
      return false;
    });

    $('#quick-shop-image').on(clickEv, '.image-thumb', function() {

      var $this = $(this);
      var background = $('.product-image .main-image .main-image-bg');
      var parent = $this.parents('.product-image-wrapper');
      var src_original = $this.attr('data-image-zoom');
      var src_display = $this.attr('data-image');
      
      background.show();
      
      parent.find('.image-thumb').removeClass('active');
      $this.addClass('active');
      
      parent.find('.main-image').find('img').attr('data-zoom-image', src_original);
      parent.find('.main-image').find('img').attr('src', src_display).load(function() {
        background.hide();
      });
      
      return false;
    });
  }
}
    
/* Handle Carousel */
function handleCarousel(){
	"use strict";
  
  /* Handle main slideshow */
  if($('#home-slider').length){
    $('#home-slider').camera({
      height: '36%',
      pagination: false,
      thumbnails: false,
      autoAdvance: true
    });
  }
  
  /* Handle Featured Collections */
  if($("#home_collections").length){
      $("#home_collections").owlCarousel({
        navigation : true,
        pagination: false,        
        slideSpeed : 300,
        paginationSpeed : 800,
        rewindSpeed : 1000,
        items: 3,
        itemsDesktop : [1199,4],
        itemsDesktopSmall : [979,4],
        itemsTablet: [768,3],
        itemsTabletSmall: [540,2],
        itemsMobile : [360,1],
        //scrollPerPage: true,
        navigationText: ['<i class="fa fa-chevron-left btooltip" title="Previous"></i>', '<i class="fa fa-chevron-right btooltip" title="Next"></i>'],
        beforeMove: function(elem) {
          if(touch == false){
          }
        },
        afterInit: function(elem){
          elem.find('.btooltip').tooltip();
        }
      });
  }
  
  /* Handle Featured Products */
  
  if($("#home_products_2").length){
      $("#home_products_2").owlCarousel({
        navigation : true,
        pagination: false,
        items: 3,
        slideSpeed : 300,
        paginationSpeed : 800,
        rewindSpeed : 1000,
        itemsDesktop : [1199,4],
        itemsDesktopSmall : [979,4],
        itemsTablet: [768,3],
        itemsTabletSmall: [540,2],
        itemsMobile : [360,1],
        //scrollPerPage: true,
        navigationText: ['<i class="fa fa-chevron-left btooltip" title="Previous"></i>', '<i class="fa fa-chevron-right btooltip" title="Next"></i>'],
        beforeMove: function(elem) {
          if(touch == false){
          }
        },
        afterUpdate: function() {
          positionQuickshop();
        },
        afterInit: function(elem){
          elem.find('.btooltip').tooltip();
        }
      });
  }
  
  
  /* Handle Partners Logo */
  if($('#partners').length){
    imagesLoaded('#partners', function() {
      $("#partners").owlCarousel({
        navigation : true,
        pagination: false,
        items: 8,
        itemsDesktop : [1199,6],
        itemsDesktopSmall : [979,5],
        itemsTablet: [768,4],
        itemsTabletSmall: [540,2],
        itemsMobile : [360,1],
        scrollPerPage: true,
        navigationText: ['<i class="fa fa-caret-left btooltip" title="Previous"></i>', '<i class="fa fa-caret-right btooltip" title="Next"></i>'],
        beforeMove: function(elem) {
          if(touch == false){
            var items = elem.find('.not-animated');
            items.removeClass('not-animated').unbind('appear');
          }
        },
        afterInit: function(elem){
          elem.find('.btooltip').tooltip();
        }
      });
    });
  }
   
  /* Handle product thumbs */
  if($("#gallery_main").length){
    $("#gallery_main").owlCarousel({
      navigation : true,
      pagination: false,
      items: 4,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3],
      itemsTablet: [768,3],
      itemsMobile : [479,3],
      scrollPerPage: true,
      navigationText: ['<i class="fa fa-caret-left btooltip" title="Previous"></i>', '<i class="fa fa-caret-right btooltip" title="Next"></i>'],
      afterInit: function(elem){
        elem.find('.btooltip').tooltip();
      }
    });
  }
  
  /* Handle related products */
  if($(".prod-related").length){
    $(".prod-related").owlCarousel({
      navigation : true,
      pagination: false,
      items: 3,
      slideSpeed : 200,
      paginationSpeed : 800,
      rewindSpeed : 1000,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3],
      itemsTablet: [768,2],
      itemsTabletSmall: [540,2],
      itemsMobile : [360,1],
      //scrollPerPage: true,
      navigationText: ['<i class="fa fa-chevron-left btooltip" title="Previous"></i>', '<i class="fa fa-chevron-right btooltip" title="Next"></i>'],
      beforeMove: function(elem) {
        if(touch == false){
        }
      },
      afterUpdate: function() {
        positionQuickshop();
      },
      afterInit: function(elem){
        elem.find('.btooltip').tooltip();
      }
    });
  }
}

/* Handle search box on mobile */
function callbackSearchMobile(){
  "use strict";
  var button = $('.is-mobile .is-mobile-search i');
  var form = $('.is-mobile .is-mobile-search .search-form');
  button.mouseup(function(search) {
    form.show();
  });
  form.mouseup(function() { 
    return false;
  });
  $(this).mouseup(function(search) {
    if(!($(search.target).parent('.is-mobile .is-mobile-search').length > 0)) {
      form.hide();
    }
  });  
}
/* Handle search box on pc */
function handleBoxSearch(){
  "use strict";
  if($('#header-search input').length){
    $('#header-search input').focus(function() {
      $(this).parent().addClass('focus');
    }).blur(function() {
      $(this).parent().removeClass('focus');
    });
  }
}
    
/* Handle Map with GMap */
function handleMap(){
  "use strict";
  if(jQuery().gMap){
    if($('#contact_map').length){
      $('#contact_map').gMap({
        zoom: 17,
        scrollwheel: false,
        maptype: 'ROADMAP',
        markers:[
          {
            address: '249 Ung Văn Khiêm, phường 25, Ho Chi Minh City, Vietnam',
            html: '_address'
          }
        ]
      });
    }
  }
}
    
/* Handle Grid - List */
function handleGridList(){
  "use strict";
  if($('#goList').length){
    $('#goList').on(clickEv, function(e){
      $(this).parent().find('li').removeClass('active');
      $(this).addClass('active');
      
      $('#sandBox .element').addClass('full_width');
      $('#sandBox .element .row-left').addClass('col-md-6');
      //$('#sandBox .element .row-left').addClass('col-sm-6');
      $('#sandBox .element .row-right').addClass('col-md-14');
      //$('#sandBox .element .row-right').addClass('col-sm-14');
      
      $('#sandBox').isotope('reLayout');
      if(clickEv == 'touchstart'){
      $(this).click();
      return true;
    }
      
      /* re-call handle position */
      positionQuickshop();
    });
  }
  
  if($('#goGrid').length){
    $('#goGrid').on(clickEv, function(e){
      $(this).parent().find('li').removeClass('active');
      $(this).addClass('active');
      
      $('#sandBox .element').removeClass('full_width');
      $('#sandBox .element .row-left').removeClass('col-md-6');
      //$('#sandBox .element .row-left').removeClass('col-sm-6');
      $('#sandBox .element .row-right').removeClass('col-md-14');
      //$('#sandBox .element .row-right').removeClass('col-sm-14');
      
      $('#sandBox').isotope('reLayout');
      if(clickEv == 'touchstart'){
      $(this).click();
      return true;
    }
      
      /* re-call handle position */
      positionQuickshop();
    });
  }
}

/* Handle detect platform */
function handleDetectPlatform(){
  "use strict";
  /* DETECT PLATFORM */
  $.support.touch = 'ontouchend' in document;
  
  if ($.support.touch) {
    touch = true;
    $('body').addClass('touch');
    clickEv = 'touchstart';
  }
  else{
    $('body').addClass('notouch');
    if (navigator.appVersion.indexOf("Mac")!=-1){
      if (navigator.userAgent.indexOf("Safari") > -1){
        $('body').addClass('macos');
      }
      else if (navigator.userAgent.indexOf("Chrome") > -1){
        $('body').addClass('macos-chrome');
      }
        else if (navigator.userAgent.indexOf("Mozilla") > -1){
          $('body').addClass('macos-mozilla');
        }
    }
  }
}
    
/* Handle tooltip */
function handleToolTip(){
  "use strict";
  if(touch == false){
    if($('.btooltip').length){
      $('.btooltip').tooltip();
    }
  }
}
    
/* Handle product quantity */
function handleQuantity(){
  "use strict";
  if($('.quantity-wrapper').length){
    $('.quantity-wrapper').on(clickEv, '.qty-up', function() {
      var $this = $(this);
      
      var qty = $this.data('src');
      $(qty).val(parseInt($(qty).val()) + 1);
    });
    $('.quantity-wrapper').on(clickEv, '.qty-down', function() {
      var $this = $(this);
      
      var qty = $this.data('src');
      
      if(parseInt($(qty).val()) > 1)
        $(qty).val(parseInt($(qty).val()) - 1);
    });
  }
}
    
/* Handle sidebar */
function handleSidebar(){
  "use strict";
  /* Add class first, last in sidebar */
  if($('.sidebar').length){
    $('.sidebar').children('.row-fluid').first().addClass('first');
    $('.sidebar').children('.row-fluid').last().addClass('last');
  }
}
    
/* Handle sort by */
function handleSortBy(){
  "use strict";
  if($('#sortForm li.sort').length){
    $('#sortForm li.sort').click(function(){
      
      var button = $('#sortButton');
      var box = $('#sortBox');
      
      $('#sortButton .name').html($(this).html());
      
      button.removeClass('active');
      box.hide().parent().removeClass('open');
    });
  }
}
    
/* Handle dropdown box */
function handleDropdown(){
  "use strict";
  if($('.dropdown-toggle').length){
    $('.dropdown-toggle').parent().hover(function (){
      if(touch == false && getWidthBrowser() > 768 ){
        $(this).find('.dropdown-menu').stop(true, true).slideDown(300);
      }
    }, function (){
      if(touch == false && getWidthBrowser() > 768 ){
        $(this).find('.dropdown-menu').hide();
      }
    });
  }
  
  $('nav .dropdown-menu').each(function(){
    $(this).find('li').last().addClass('last');
  });
  
  $('.dropdown').on('click', function() {
      if(touch == false && getWidthBrowser() >= 768 ){
        var href = $(this).find('.dropdown-link').attr('href');
        window.location = href;
    }
  });
  
  $('.cart-link').on('click', function() {
      if(touch == false && getWidthBrowser() > 768 ){
        var href = $(this).find('.dropdown-link').attr('href');
        window.location = href;
    }
  });
}
    
/* Handle collection tags */
function handleCollectionTags(){
  "use strict";
  if($('#collection_tags').length){
    $('#collection_tags').on('change', function() {
      window.location = $(this).val();
    });
  }
}
   
/* Handle menu with scroll*/
function handleMenuScroll(){
  "use strict";
  var scrollTop = $(this).scrollTop();
  var heightNav = $('#top').outerHeight();
  
  if(touch == false && getWidthBrowser() >= 1024){
    if(scrollTop > heightNav){
      if(!$('#top').hasClass('on')){
        $('<div style="min-height:'+heightNav+'px"></div>').insertBefore('#top');
        $('#top').addClass('on').addClass('animated');
      }
    }
    else{
      if($('#top').hasClass('on')){
        $('#top').prev().remove();
        $('#top').removeClass('on').removeClass('animated');
      }
    }
  }
}

/* Handle Quickshop */
function handleQuickshop(){
  "use strict";
	$('body').on('click','.quick_shop',function(e){
		var action = $(this).attr('data-href');
		var target = $(this).attr('data-target')
		$(target).load(action, function() {
			$('#top').addClass('z-idx');
			
			$('.btooltip').tooltip();
			var zoomImage = $('.elevatezoom_qs .img-zoom');
			
			zoomImage.elevateZoom({
			  gallery:'gallery_main_qs', 
			  galleryActiveClass: 'active', 
			  zoomType: 'window',
			  cursor: 'pointer',
			  zoomWindowFadeIn: 300,
			  zoomWindowFadeOut: 300,
			  zoomWindowWidth: 250,
			  zoomWindowHeight: 350,
			  scrollZoom: true,
			  easing : true,
			});
			
			//pass the images to Fancybox
			$(".elevatezoom_qs .img-zoom").bind("click", function(e) {  
			  var ez =   $('.elevatezoom_qs .img-zoom').data('elevateZoom');	
			  $.fancybox(ez.getGalleryList(),{
				closeBtn  : false,
				helpers : {
				  buttons	: {}
				}
			  });
			  return false;
			});
			
			$("#gallery_main_qs").show().owlCarousel({
			  navigation : true,
			  pagination: false,
			  items: 3,
			  itemsDesktop : [1199,3],
			  itemsDesktopSmall : [979,3],
			  itemsTablet: [768,3],
			  itemsMobile : [479,3],
			  scrollPerPage: true,
			  navigationText: ['<i class="fa fa-angle-left" title="Previous"></i>', '<i class="fa fa-angle-right" title="Next"></i>']
			});
		});
		
		e.preventDefault();
	});
  
	$('body').on('hidden.bs.modal', '.modal', function() {
		$(this).empty();
		$('.zoomContainer').css('z-index', '1');
		$('#top').removeClass('z-idx');
	});
}

/* Handle when window resize */
$(window).resize(function() {
  "use strict";

  /* re-call position quickshop */
  positionQuickshop();
  
  /* reset menu with condition */
  if(touch == true || getWidthBrowser() < 1024){
    if($('#top').hasClass('on')){
      $('#top').prev().remove();
      $('#top').removeClass('on').removeClass('animated');
    }
    
    $('#goGrid').trigger('click');
  }
});


/* Handle when window scroll */
$(window).scroll(function() {
  "use strict";
  /* re-call handle menu when scroll */
  handleMenuScroll();
});

/* handle when window loaded */
$(window).load(function() {
  "use strict";
  /* re-call position quickshop */
  positionQuickshop();
});

jQuery(document).ready(function($) {
  "use strict";
  
  /* DETECT PLATFORM */
  handleDetectPlatform();
  
  /* Handle Animate */
  handleAnimate();
  
  /* Handle Carousel */
  handleCarousel();
  
  /* Handle search box on pc */
  handleBoxSearch();
  
  /* Handle search box on mobile */
  callbackSearchMobile();
  
  /* Handle ajax quickshop */
  handleQuickshop();
  /* Handle position quickshop */
  positionQuickshop();

  /* Handle scroll to top */
  handleScrollTop();
  
  /* Handle dropdown box */
  handleDropdown();
  
  /* handle menu when scroll */
  handleMenuScroll();
  
  /* Handle tooltip */
  handleToolTip();
  
  /* Handle Map with GMap */
  handleMap();
  
  /* Handle sidebar */
  handleSidebar();
  
  /* Handle zoom for product image */
  alwaysUpdateZoom();
  
  /* Handle quantity */
  handleQuantity();
  
  /* Handle product thumbs */
  if(touch){
    updateScrollThumbs();
  }
  else{
    
  }

  /* Handle sort by */
  handleSortBy();
     
  /* Handle grid - list */
  handleGridList();
  
  /* Handle collection tags */ 
  handleCollectionTags();
     
  $('.dropdown-menu').on(clickEv, function (e) {
    e.stopPropagation();
  });
  $('.dropdown-menu').on('click', function (e) {
    e.stopPropagation();
  });
  $('.btn-navbar').on('click', function() {
    return true;
  });
   
  
});