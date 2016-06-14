<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie-9">
  <![endif]-->
  <!--[if gt IE 9]>
  <!-->
  <html>
    <!--<![endif]-->
<head>
    <meta charset="utf-8">
		<meta name="globalsign-domain-verification" content="D_j0skh7gHkFIlAfIzhCdG-a2s06djlHg4BcqQOMlU" />
		        <script type="text/javascript">
            var site_url = "<?=site_url('/')?>";
        </script>
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
    <![endif]-->
            <title><?=_e('home_old_head_title')?></title>
        <!-- <meta name="verify-admitad" content="da7eb9a412" /> -->
		<meta name="verify-admitad" content="14f5f5b613" />
        <meta name="interkassa-verification" content="5e3dcbfee6c17c560ee835fc6bc67c6e" />
        <meta name="alexaVerifyID" content="7tateDHkqQOLGG6m06giu--7wdE"/>
        <? if (empty($cache_enable)) { ?>
            <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
            <meta http-equiv="Pragma" content="no-cache"/>
        <? } ?>
        <? if(isset($this->user)) { ?>
            <meta property="og:url" content="<?= site_url("?id_partner=" . $this->user->id_user) ?>"/>
        <? } ?>
<?if($this->lang->lang()=='ru'){?>
<meta property="og:image" content="https://webtransfer.com/images/banner_new_ru.jpg" />
<link rel="image_src" href="https://webtransfer.com/images/banner_new_ru.jpg" />
<?} else {?>
<meta property="og:image" content="https://webtransfer.com/images/banner_new_en.jpg" />
<link rel="image_src" href="https://webtransfer.com/images/banner_new_en.jpg" />
<?}?>
<meta property="og:title" content="<?=_e('Взаимное кредитование Webtransfer!')?>">
<meta property="og:description" content="<?=_e('Выдаю займы участникам соц сетей на срок до 30 дней под 45% и зарабатываю до $5000 в месяц. Всем моим партнерам бонус $50!')?>">
        <meta name="okpay-verification" content="7eec106b-2898-4a67-9c9b-aabc14274e6d" />
        <meta name="google-site-verification" content="jfQPhvGixQF0hU-L41d3aM7MFOs_Aoy4FmHCRHNn8kg" />
        <meta name="keyword" content="<?=_e('home_old_head_keyword')?>"  lang="ru" />
		<meta name="description" content="<?=_e('home_old_head_meta_description2')?>" />
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/mainpage/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/mainpage/css/bootstrap-datetimepicker.min.css" />
    <link href="/mainpage/js/jquery.formstyler.css" rel="stylesheet" />
    <script  type="text/javascript"src="/mainpage/js/jquery.min.js"></script>
    <script type="text/javascript" src="/mainpage/js/jquery.formstyler.js"></script>
    
    <script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({includedLanguages: 'de,en,es,fr,it,iw,ru,pt,tr,zh-CN', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
}
</script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <link rel="stylesheet" href="/mainpage/css/style.css">
    <link rel="stylesheet" href="/mainpage/css/bootstrap.css">
     <!--<script type="text/javascript" href="/mainpage/js/bootstrap.js">-->
    <!-- Slider Kit styles -->
    <link rel="stylesheet" type="text/css" href="/mainpage/css/sliderkit-core.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="/mainpage/css/sliderkit-demos.css" media="screen, projection" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,300italic,300,700italic,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/mainpage/css/style.css">
    <link rel="stylesheet" href="/mainpage/css/bigvideo.css">
    <script type="text/javascript" src="/mainpage/js/modernizr-2.5.3.min.js"></script>
    <link href="/mainpage/assets/stylesheets/cs.animate.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="/mainpage/dist/css/swiper.min.css">
    <link rel="stylesheet" href="/mainpage/css/mobile.css">
    <script src="/mainpage/js/jquery-ui.js"></script>

    <script>
  $(function() {
    $( "#slider-range-max" ).slider({
      range: "max",
      min: 1,
      max: 10,
      value: 2,
      slide: function( event, ui ) {
        $( "#amount" ).val( ui.value );
      }
    });
    $( "#amount" ).val( $( "#slider-range-max" ).slider( "value" ) );
  });</script>
  <script type="text/javascript" >
  $(document).ready(
    function() {
        $(".social-more").click(function() {
            $("#moresoc").toggle();
        });
    });
  </script>

    <script>
//  function hexFromRGB(r, g, b) {
//    var hex = [
//      r.toString( 16 ),
//      g.toString( 16 ),
//      b.toString( 16 )
//    ];
//    $.each( hex, function( nr, val ) {
//      if ( val.length === 1 ) {
//        hex[ nr ] = "0" + val;
//      }
//    });
//    return hex.join( "" ).toUpperCase();
//  }
  function refreshSwatch() {
    var summ = $( "#sum" ).slider( "value" ),
      time = $( "#time" ).slider( "value" ),
      percent = $( "#persent" ).slider( "value" );
//      percent = percent.replace(/,/g,'.');
    $( "#sum" ).parent().find('input').val(summ);
    $( "#time" ).parent().find('input').val(time);
    $( "#persent" ).parent().find('input').val(percent);

    var pa = '3';

    var pribil = summ * (percent / 100 * time)

    var out_sum = summ + pribil;
    var minus = 0
    var garant = 0
    var gar_check = true; //$('#garant').prop("checked")
    if (gar_check)
    {
		if(pa == '3'){
			garant = 20;
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus;
		} else {
			if (time <= 10)
				garant = 50;
			else if (time <= 20)
				garant = 45;
			else if (time <= 30)
				garant = 40;
			//else  if(time>28) garant= 15
			minus = pribil * (garant / 100);
			out_sum = out_sum - minus
      }
  }
    else
    {
        garant = 10
        minus = pribil * (garant / 100)
        out_sum = out_sum - minus
  }

//    $('#your_invest').val("$ " + summ_format((Math.round((summ + 0.00001) * 100) / 100)))
//    $('#your_invest_plus').val("$ " + summ_format((Math.round((pribil + 0.00001) * 100) / 100)))
//    $('#your_invest_minus').val("$ " + summ_format((Math.round((minus + 0.00001) * 100) / 100)) + " (-" + garant + "%)")


    out_sum = (Math.round((out_sum + 0.00001) * 100) / 100);

    $("#out_sum").val((summ + pribil));

//    if( $("#summ_to_return").length !=0 ){
//        if($("#form_invest").length)
//            $("#summ_to_return").val("$ " + out_sum);
//        else $("#summ_to_return").val("$ " + (summ + pribil));
//    }
  }
  function sendForm(el){
      var data = $(el).parent().find('form').serialize();
      window.location = '<?=site_url('account/my_credits')?>?'+data;
      return false;
      if ( $('#take_credit').prop('checked') )
          window.location = '<?=site_url('account/my_credits')?>?'+data;
      else
          window.location = '<?=site_url('account/my_invest')?>?'+data;
      return false;
  }
  
  $(function() {
      var conf = {
      orientation: "horizontal",
      range: "min",
//        slide: refreshSwatch,
      change: refreshSwatch
      };
      conf.step = 50;
      conf.value = 500;
      conf.min = 50;
      conf.max = 1000;
    $( "#sum" ).slider(conf);
      conf.step = 1;
      conf.value = 30;
      conf.min = 3;
      conf.max = 30;
    $( "#time" ).slider(conf);
      conf.step = 0.1;
      conf.value = 1.5;
      conf.min = 0.5;
      conf.max = 3.0;
    $( "#persent" ).slider(conf);
    });
  </script>
<style type="text/css">
#tubular-container {
height:700px !important;
/*position:relative !important*/;
}
a.icon-soc-big.more.social-more:after {
    content: '';
    width: 40px;
    height: 30px;
    position: absolute;
    background: url(/mainpage/images/more.png) 0 0 no-repeat;
    background-size: contain;
    margin-left: -15px;
    margin-top: 13px;
	
}
div#google_translate_element {
    overflow: hidden;
    height: 40px !important;
	width: 180px;
}
.skiptranslate.goog-te-gadget {
    float: right;
}
.icon-soc-big.more {
opacity: 0.6;
    background-position: -124px 0;
    background-color: #FFF;
    border-radius: 50px;
    -webkit-border-radius: 50px;
    -moz-border-radius: 50px;
}
.icon-soc-big.more:hover {
opacity: 0.5;
}
div#moresoc {
    display: none;
	cursor: pointer;
}
html, body {
	background-color: #FFF;
}
img.big-image {
    width: 100%;
}
.vjs-control-bar {
    display: none;
}
.calc p {
    margin-top: 17px;
}
.footer {
    z-index: 0 !important;
}
.footer .inner {
    background: #242a2f !important;
    padding-top: 35px !important;
    padding-bottom: 34px !important;
    margin-bottom: 8px !important;
}
.footer p {
    font-size: 13px;
    line-height: 22px;
    font-weight: 400;
    color: #5f676d;
    margin: 0;
    padding-top: 5px;
}


@media (min-width: 769px) {
  .footer {
    background: #1D1D1D;
    padding-bottom: 0px;
    height: auto;
    position: relative;
}
}

@media (max-width: 767px) {
.footer {
    background: #1D1D1D;
    padding-bottom: 0px;
    height: auto;
    position: relative;
    margin-top: -10px;
}
}
</style>

<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"9qQ7k1a0CM00oQ", domain:"<?= $_SERVER["HTTP_HOST"]; ?>",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->  

        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
</head>

<body>
<script src="/mainpage/js/jquery.imagesloaded.min.js"></script>
<script src="/mainpage/js/video43.js"></script>
<!-- BigVideo  -->
<script src="/mainpage/js/video.min.js"></script>

<!-- Tutorial Demo -->
<script src="/mainpage/js/jquery.transit.min.js"></script>
<script>$(function() {
	$bigImage = $('.big-image');
	$window = $(window);
$bigImage
                    .css('position','relative')
                    .imagesLoaded(adjustImagePositioning);
                // and on window resize
                $window.on('resize', adjustImagePositioning);
				function adjustImagePositioning() {
                $bigImage.each(function(){
                    var $img = $(this),
                        img = new Image();

                    img.src = $img.attr('src');

                    var windowWidth = $window.width(),
                        windowHeight = $window.height(),
                        r_w = windowHeight / windowWidth,
                        i_w = img.width,
                        i_h = img.height,
                        r_i = i_h / i_w,
                        new_w, new_h, new_left, new_top;

                    if( r_w > r_i ) {
                        new_h   = windowHeight;
                        new_w   = windowHeight / r_i;
                    }
                    else {
                        new_h   = windowWidth * r_i;
                        new_w   = windowWidth;
                    }

                    $img.css({
                        width   : new_w,
                        height  : new_h,
                        left    : ( windowWidth - new_w ) / 2,
                        top     : ( windowHeight - new_h ) / 2
                    })

                });

            } });
        $(function() {

            // Use Modernizr to detect for touch devices,
            // which don't support autoplay and may have less bandwidth,
            // so just give them the poster images instead
            var screenIndex = 1,
                numScreens = $('.screen').length,
                isTransitioning = false,
                transitionDur = 1000,
                BV,
                videoPlayer,
                isTouch = Modernizr.touch,
                $bigImage = $('.big-image'),
                $window = $(window);
               var windowWidth = $window.width();
		if (!isTouch || windowWidth >= '768' ) {
                // initialize BigVideo
                BV = new $.BigVideo({forceAutoplay:isTouch});
                BV.init();
                showVideo();
              BV.getPlayer().on('loadeddata', function() {
                    onVideoLoaded();
                });
 BV.getPlayer().on('error', function() {
                    onVideoLoaded();
                });
                // adjust image positioning so it lines up with video
                
            }

            // Next button click goes to next div
            $('#next-btn').on('click', function(e) {
                e.preventDefault();
                if (!isTransitioning) {
                    next();
                }
            });

            function showVideo() {
                BV.show($('#screen-'+screenIndex).attr('data-video'),{ambient:true});
            }

            function next() {
                isTransitioning = true;

                // update video index, reset image opacity if starting over
                if (screenIndex === numScreens) {
                    $bigImage.css('opacity',1);
                    screenIndex = 1;
                } else {
                    screenIndex++;
                }

                if (!isTouch) {
                    $('#big-video-wrap').transit({'left':'-100%'},transitionDur)
                }

                (Modernizr.csstransitions)?
                    $('.wrapper').transit(
                        {'left':'-'+(100*(screenIndex-1))+'%'},
                        transitionDur,
                        onTransitionComplete):
                    onTransitionComplete();
            }

            function onVideoLoaded() {
                $('#screen-'+screenIndex).find('.big-image').transit({'opacity':0},500)
            }

            function onTransitionComplete() {
                isTransitioning = false;
                if (!isTouch) {
                    $('#big-video-wrap').css('left',0);
                    showVideo();
                }
            }

            
        });
    </script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none" height="1" width="1" alt="" /></noscript>
        <script type="text/javascript">
        if ( window.self != window.top ) {
            window.top.location.href=window.location.href;
        }
       </script>

        <? $this->load->view('user/blocks/banner_profile_login_home'); ?>
    <!-- Codrops top bar -->
    <div class="codrops-top white-sh">
	
	
	
      <div class="container">
        <a>
          <div class="logowt">
            <img src="/mainpage/images/logowt.png" class="image-logo" />
          </div>
        </a>
        <div class="flr">
          <div id="google_translate_element"></div>

        </div>
        <div class="clr"></div>
      </div>
    </div>
    <!--/ Codrops top bar -->




      <div id="section-full" class="js-scroll-section ">





        <div class="wrapper">
          <div class="screen" id="screen-1" data-video="https://biggerhost.com/upload/dock.mp4">
            <img src="/mainpage/images/bgs.jpg" class="big-image" />

          </div>

        </div>

        <div class="container">
          <div class="row clearfix zz-tind">
            <div class="col-md-12 col-sm-12 not-animated posaps50" data-animate="fadeIn" data-delay="100">
              <div class="">
               <h4 class="tex-mob hidden-md hidden-sm hidden-lg" ><?= _e('Добро пожаловать в Webtransfer!') ?></h4>
                <h4 class="text-center nomgt not-animated hidden-xs" style="font-weight:bold" data-animate="fadeInDown" data-delay="1100"><?= _e('Добро пожаловать в Webtransfer') ?></h4>

                <div class="text">
                  <div class="text-block">
                    <?= _e('Делитесь горячими новостями: событиями, мнениями, фотографиями, видео') ?>
                  </div>
                  <div class="text-block">
                    <?= _e('Заказывайте интересные и нужные товары из десятков магазинов и аукционов') ?>
                  </div>
                  <div class="text-block">
                    <?= _e('Используйте современные прозрачные способы P2P кредитования без посредников') ?>
                  </div>


                </div>

              </div>







            <div id="modal2" class="modal_div">
              <!-- <span class="modal_close">X</span>
            -->
            <div class="bg-form not-animated" data-animate="flipInY" data-delay="100">


              <div class="row">
                <div class="col-lg-12">







                <form id="login-form-popup">
<input type="hidden" name="lang" value="<?=_e('lang')?>">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">

<h2 class="modal-title text-center" id="dialogLabel"><?=_e('Вход в систему')?></h2>
</div>
<div class="modal-body">
<div class="form-group">
<input type="text" id="form-login" name="login" onkeyup="checkForm()" onchange="checkForm()" class=" dfs" placeholder="<?=_e('blocks/loginForm_window_2')?>">
</div>
<div class="form-group">
<input type="password" id="form-pass" onkeyup="checkForm()" onchange="checkForm()" name="password" class=" dfs" placeholder="<?=_e('blocks/loginForm_window_3')?>">
</div>
 <div class="g-recaptcha" data-sitekey="<?=  config_item('publickeyCapcha')?>"></div>

<div class="text-center" style="display: none;color:red;" id="login_popup_error"></div></div>
<div class="modal-footer">
<div class="text-center">
 <a href="<?=site_url('ask/forget')?>" style="color:#007EFF"><?=_e('blocks/loginForm_window_4')?></a>
</div>
<br>
<div class="text-center">
<button type="submit" id="form-submit" class="btn btn-primary button newbt" disabled=""><?=_e('blocks/loginForm_window_6')?></button>
</div>
</div>
</div>
</div>
</form>

                </div>
              </div>
            </div>
          </div>


          <!-- ссылкa с href="#modal1", oткрoет oкнo с  id = modal1-->
          <div id="overlay"></div>
          <!-- Пoдлoжкa, oднa нa всю стрaницу -->




      <div class="enter-site">
      <!-- кнопка входа -->
     <!--   <a class="block-a button text-center not-animated" data-animate="bounceIn" data-delay="2100" href="#">Войти</a> -->
        <!-- кнопка входа -->
 <? if(isset($this->user)) { ?>
			
			<a class="block-a button text-center not-animated" data-animate="bounceIn" data-delay="2100" href="<?=site_url('page/about/blog')?>"><?=_e('Войти на сайт')?></a>
 <?} else {?>
 
<p class="not-animated" data-animate="fadeInBottom" data-delay="200"><?=_e('Войти в личный кабинет без регистрации')?></p>



<ul class="list-unstyled list-inline social" style="z-index:9999">
<li class="not-animated" data-animate="bounceIn" data-delay="1100"><a href="#" class="icon-soc-big fb social-facebook">fb</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="1300"><a href="#" class="icon-soc-big gl social-google_plus">gl</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="700"><a  href="#modal2" class="open_modal icon-soc-big in" style="cursor: pointer">in</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="500"><a href="#" class="icon-soc-big tw social-twitter">tw</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="500"><a href="#" onclick="return false" class="icon-soc-big more social-more ">more</a></li></ul>
<div id="moresoc">
<ul class="list-unstyled list-inline social" style="z-index:9999">
<li class="not-animated" data-animate="bounceIn" data-delay="100"><a href="#" class="icon-soc-big od social-odnoklassniki">od</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="300"><a href="#" class="icon-soc-big vk social-vkontakte">vk</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="900"><a href="#" class="icon-soc-big ml social-mail_ru">ml</a></li>
<li class="not-animated" data-animate="bounceIn" data-delay="900"><a href="#" class="icon-soc-big ren social-renren">ren</a></li>

</ul>
</div>
<?}?>
</div>

  </div>



      </div>





    </div>





  </div>
  <!-- Section 1 end -->



      <div class="scroll-container">
      <!-- Section 1 start -->

      <div class="profile">
        <section class="oranges2">

          <h3 class="text-center not-animated fs-new" data-animate="fadeInDown" data-delay="300"><?=_e('Как это работает') ?></h3>
          <div class="container">
            <div class="col-lg-4 col-md-4 col-sm-4">
              <h3 class="tr-new not-animated mg-xs-tp" data-animate="flipInX" data-delay="300"><?=_e('Общайтесь') ?></h3>
              <p class="tr-new2">
                <?=_e('Делитесь горячими новостями: событиями, мнениями, фотографиями, видео') ?>
              </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <h3 class="tr-new not-animated" data-animate="flipInX" data-delay="300"><?=_e('Покупайте') ?></h3>
              <p class="tr-new2">
                <?=_e('Заказывайте интересные и нужные товары из десятков магазинов и аукционов') ?>
              </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <h3 class="tr-new not-animated" data-animate="flipInX" data-delay="300"><?=_e('Реализуйте') ?></h3>
              <p class="tr-new2">
                <?=_e('Используйте современные прозрачные способы P2P кредитования без посредников') ?>
              </p>

            </div>
          </div>


        </section>

      </div>


  <!-- Section 2 start -->
  <div id="section-full2" class="js-scroll-section  section  section-full2 section-nn">
    <!-- Swiper -->
    <div class="swiper-container">
      <div class="parallax-bg" data-swiper-parallax="-23%"></div>
      <div class="swiper-wrapper">
       <!--  <div class="swiper-slide">
          <div class="title" data-swiper-parallax="-100">

            <div class="blocks nwbgsnew">
              <div class="container">
                <div class="row clearfix zz-tind">
                  <div class="col-md-8 col-sm-8 not-animated" data-animate="fadeIn" data-delay="100">
                    <h4 class="weip-rf">
                      <a href="http://skypasser.ru/ru/" target="_blank">
                        <img class="img-responisve hov-act mtg-hd" src="images/logosw.png" alt="" />
                      </a>
                      <?=_e('Пользуйтесь Skypasser') ?>
                      <br>
                      <span><?=_e('Самый удобный способ путешествовать') ?></span>
                    </h4>

                    <div class="col-md-10 col-sm-4">
                      <div class="row">
                        <div class="row bg-form srew not-animated ntr-sw" data-animate="fadeInLeft" data-delay="200">

                          <div class="row">
                            <div class="col-lg-12">
                              <form class="clearfix mgtl mgtls">

                                <div class="row">
                                  <div class="col-lg-12 sizer-n">
                                    <input type="text" name="namer" class="dfs dfsd dfsdf ntye newstq otkuda" placeholder="<?=_e('Откуда?') ?>">
                                    <input type="tel" name="phone" class="dfs dfsd dfsdf ntye kuda" placeholder="<?=_e('Куда?') ?>">




                                    </div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-12">
                                    <div class="mgsel">

									 <div class="cl-bs-xs">
                                      <select>
                                        <option><?=_e('Эконом') ?></option>
                                        <option><?=_e('Бизнес') ?></option>
                                        <option><?=_e('Премиум') ?></option>
                                        <option><?=_e('Первый класс') ?></option>
                                      </select>

                                      <select>
                                        <option><?=_e('Туда-Обр...') ?></option>
                                        <option><?=_e('В один конец') ?></option>
                                      </select>

                                      <select>
                                        <option><?=_e('Взрослые') ?></option>

                                      </select>

                                      <select>
                                        <option><?=_e('Дети') ?></option>

                                      </select>

                                       <select>
                                        <option><?=_e('Младенцы') ?></option>

                                      </select>




									 </div>

									 <div class="picker-area">

                                          <div class="form-group form-groupq">
                                        <div class="input-group date" id="datetimepicker1">
                                          <input type="text" class="form-control2" placeholder="19.10.2015"/>
                                          <span class="input-group-addon">
                                            <span class="glyphicon-calendar glyphicon"></span>
                                          </span>
                                        </div>
                                      </div>

                                      <div class="form-group form-groupq">
                                        <div class="input-group date" id="datetimepicker2">
                                          <input type="text" class="form-control2" placeholder="20.10.2015"/>
                                          <span class="input-group-addon">
                                            <span class="glyphicon-calendar glyphicon"></span>
                                          </span>
                                        </div>
                                      </div>
									 </div>

                                    </div>
                                  </div>
                                </div>
                                 <a href="http://skypasser.ru/ru/" target="_blank">
                                <div class="button newbt frtwwe"><?=_e('Найти') ?></div>
                              </a>

                              </form>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4 col-sm-4  not-animated" data-animate="fadeIn" data-delay="100"></div>

                </div>
              </div>

            </div>

            <div class="eye-light-wrap"></div>
            <div id="dark-slide-1" class="js-before-after-slide-wrap">
              <div class="js-before-after-slide">
                <div class="eye-phone-wrap">
                  <img class="eye-phone" src="" alt="" />

                </div>
              </div>
            </div>
            <span id="drag-1" class="js-before-after-drag  before-after-drag">
               </span>
          </div>

        </div>-->
        <div class="swiper-slide">
          <div class="title" data-swiper-parallax="-100">

            <div class="blocks nwbgs2">
              <div class="container">
                <div class="row clearfix zz-tind">
                  <div class="col-md-8 col-sm-8 not-animated" data-animate="fadeIn" data-delay="100">
                    <h4 class="weip-rf sizer-nw">
                      <img class="img-responisve nh-wt hov-act" src="/mainpage/images/logowt.jpg" alt="" />
                      <?=_e('Webtransfer VISA card') ?>
                      <br>
                      <span><?=_e('Лучшая карта для P2P займов') ?></span>
                    </h4>
                    <!-- <h5 class="weip-rf2">
                    <?=_e('Самый удобный способ получать') ?>
                    <br><?=_e('взаймы в соцсети') ?></h5>
                  -->
                  <div class="col-md-10 col-sm-4">
                    <div class="row">
                      <div class="row srew not-animated ntr-sw mga-mdx" data-animate="fadeInLeft" data-delay="200">

                        <div class="row">
                          <div class="col-lg-12">
                            <form class="clearfix mgtl mgtl2">

                              <p >
                                <span class="ui-icon ui-icon-pencil" style="float:left; margin:-2px 5px 0 0;"></span>
                              </p>
                              
                              <!--div style="position: absolute; z-index: 888;">
                              <label><input  checked="checked" id="take_credit" type="radio" name="request_type">Взять кредит</label>
                              <label><input type="radio" id="give_credit" name="request_type">Дать кредит</label>
                              </div-->
                             
                              <div class="calc fll">
                                <input type="text" name="sum" readonly class="style-slick" value="500"/>
                                <div id="sum"></div>
                                <p><?=_e('Сумма') ?> ($)</p>
                              </div>

                              <div class="calc fll">
                                <input type="text" name="time" readonly class="style-slick" value="30"/>
                                <div id="time"></div>
                                <p><?=_e('Продолжительность') ?></p>
                              </div>

                              <div class="calc fll">
                                <input type="text" name="persent" readonly class="style-slick" value="1.5"/>
                                <div id="persent"></div>
                                <p><?=_e('Процент') ?> (%)</p>
                              </div>

                              <div class="calc fll">
                                <input id="out_sum" type="text" readonly class="style-slick inpo-size" value="725">
                                <p class="mg-top-calc"><?=_e('СУММА ВОЗВРАТА') ?> ($)</p>
                              </div>

                            </form>
							<? if(isset($this->user)) { ?>
			
			<a href="#" onclick="sendForm(this);return false;">
                              <div class="button newbt frtwwe frtwwe2 bgcol bg-xs-con"><?=_e('Подать заявку') ?></div>
                            </a>
							<?} else {?>
                            <a href="#modal2" class="open_modal in">
                              <div class="button newbt frtwwe frtwwe2 bgcol bg-xs-con"><?=_e('Подать заявку') ?></div>
                            </a><?};?>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          <div class="eye-light-wrap"></div>
          <div id="dark-slide-1" class="js-before-after-slide-wrap">
            <div class="js-before-after-slide">
              <div class="eye-phone-wrap">
                <img class="eye-phone" src="/img/black-phone.png" alt="" />

              </div>
            </div>
          </div>
          <span id="drag-1" class="js-before-after-drag  before-after-drag">
            <!-- --> </span>
        </div>
      </div>

    </div>

    <!-- Add Navigation 
    <div class="swiper-button-prev swiper-button-white"></div>
    <div class="swiper-button-next swiper-button-white"></div> -->
  </div>
</div>
</div>
<!-- Section 2 end -->
<!-- Scroll container end -->


<footer class="footer">
  <div class="inner hidden-xs">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6">

          <ul class="list-unstyled">
            <li><a href="<?=site_url('page/about')?>" target="_blank"><?=_e('blocks/footer_4')?></a></li>
           <!-- <li><a href="http://m.webtransfer.com"><?=_e('Мобильная версия')?></a></li-->
			<li><a href="<?=site_url('marketing')?>" target="_blank"><?=_e('Реклама на сайте')?></a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ul class="list-unstyled">
            <li><a href="<?=site_url('page/about/terms')?>" target="_blank"><?=_e('blocks/footer_10')?></a></li>
            <li><a href="<?=site_url('page/privacy-policy')?>" target="_blank"><?=_e('blocks/footer_11')?></a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <ul class="list-unstyled">
            <li><a href="https://webtransfer.zendesk.com/hc/ru" target="_blank"><?=_e('home_old_support')?></a></li>
                    <?if($this->lang->lang()=='ru'){?>
                    <li>
                        <a href="http://webtransfer-business.com" target="_blank"><?=_e('blocks/footer_20')?></a>
                    </li>
                    <?}?>
          </ul>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
          <ul class="list-unstyled list-inline social" style="text-align:right;margin-top:10px;">
           <!-- <li><a href="http://www.odnoklassniki.ru/group/51729362518158" target="_blank" class="icon od">od</a></li> -->
            <li><a href="http://vk.com/club55660968" target="_blank" class="icon vk">vk</a></li>
            <li><a href="http://www.twitter.com/webtransfercom" target="_blank" class="icon tw">tw</a></li>
            <!-- <li><a href="http://my.mail.ru/community/webtransfer/" target="_blank" class="icon ml">ml</a></li> -->
            <li><a href="http://www.facebook.com/webtransfer" target="_blank" class="icon fb">fb</a></li>
            <li><a href="https://plus.google.com/u/0/118412304892954038780/posts" target="_blank" class="icon gl">gl</a></li>
			<li><a href="https://www.youtube.com/channel/UCg3SLdMR7oGe2T_JWQWurQw" target="_blank" class="icon youtube">yt</a></li>
<li><a href="http://www.alexa.com/siteinfo/webtransfer.com" target="_blank" class="icon alexa">al</a></li>
          </ul>
        </div>
      </div>



    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pull-right">

        </div>
        <p>&copy; All rights reserved Webtransfer, 2006—2015</p>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    (function($) {
    $(function() {
      $('input, select').styler({
        selectSearch: true
      });
    });
    })(jQuery);
  </script>
    <script type="text/javascript">
                        var _gaq = _gaq || [];         _gaq.push(['_setAccount', 'UA-33029110-3']);
        _gaq.push(['_trackPageview']);
        (function() {
                            var ga = document.createElement('script');
                            ga.type = 'text/javascript';
                            ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(ga, s);
        })();
    </script>
	
    <!-- Yandex.Metrika counter/02.07.2014 -->
    <script type="text/javascript">
        (function(d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter23161861 = new Ya.Metrika({id: 23161861,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true});
                } catch (e) {
                }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function() {
                n.parentNode.insertBefore(s, n);
            };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="//mc.yandex.ru/watch/23161861" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
	<!-- Start Alexa Certify Javascript -->
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->  
<!-- begin of Top100 code -->

<img src="https://scounter.rambler.ru/top100.cnt?3136288" alt="Rambler's Top100" border="0" width="1" height="1"/>
<!-- end of Top100 code -->
</footer>

<script>window.jQuery || document.write('<script src="/mainpage/js/jquery-1.8.1.min.js"><\/script>')</script>



<script src="/mainpage/assets/javascripts/cs.script.js" type="text/javascript"></script>
<script src="/mainpage/dist/js/swiper.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,

        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        parallax: true,
        speed: 600,
        simulateTouch: false,

    });
    </script>

<script type="text/javascript" src="/mainpage/js/moment-with-locales.min.js"></script>
<script type="text/javascript" src="/mainpage/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/mainpage/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
    $(function () {
      $('#datetimepicker1').datetimepicker({language: 'ru',defaultDate:"",daysOfWeekDisabled:[0,6]});
      $('#datetimepicker2').datetimepicker({language: 'ru',defaultDate:""});
    });
  </script>

<script type="text/javascript">
    $("document").ready(function(){
        $('#modal').modal();
    });
  </script>

<script type="text/javascript">
$(document).ready(function() { // зaпускaем скрипт пoсле зaгрузки всех элементoв
    /* зaсунем срaзу все элементы в переменные, чтoбы скрипту не прихoдилoсь их кaждый рaз искaть при кликaх */
    var overlay = $('#overlay'); // пoдлoжкa, дoлжнa быть oднa нa стрaнице
    var open_modal = $('.open_modal'); // все ссылки, кoтoрые будут oткрывaть oкнa
    var close = $('.modal_close, #overlay'); // все, чтo зaкрывaет мoдaльнoе oкнo, т.е. крестик и oверлэй-пoдлoжкa
    var modal = $('.modal_div'); // все скрытые мoдaльные oкнa

     open_modal.click( function(event){ // лoвим клик пo ссылке с клaссoм open_modal
         event.preventDefault(); // вырубaем стaндaртнoе пoведение
         var div = $(this).attr('href'); // вoзьмем стрoку с селектoрoм у кликнутoй ссылки
         overlay.fadeIn(400, //пoкaзывaем oверлэй
             function(){ // пoсле oкoнчaния пoкaзывaния oверлэя
                 $(div) // берем стрoку с селектoрoм и делaем из нее jquery oбъект
                     .css('display', 'block')
                     .animate({opacity: 1, top: '50%'}, 200); // плaвнo пoкaзывaем
         });
     });

     close.click( function(){ // лoвим клик пo крестику или oверлэю
            modal // все мoдaльные oкнa
             .animate({opacity: 0, top: '45%'}, 200, // плaвнo прячем
                 function(){ // пoсле этoгo
                     $(this).css('display', 'none');
                     overlay.fadeOut(400); // прячем пoдлoжку
                 }
             );
     });
});
  </script>



<script>



$(document).ready(function() {
    $('.text .text-block').eq(0).addClass('active').fadeIn(1000);

    setInterval('blockAnimate();', 5000);
});


function blockAnimate() {
    var length = $('.text .text-block').length - 1;
    $('.text .text-block').each(function(index) {
        if($(this).hasClass('active') && index != length) {
            $(this).removeClass('active').fadeOut(1000).next('.text-block').addClass('active').delay(1000).fadeIn(1000);
            return false;
        } else if (index == length) {
            $(this).removeClass('active').fadeOut(1000);
            $('.text .text-block').eq(0).addClass('active').delay(1000).fadeIn(1000);
            return false;
        }
    });
};


</script>

</body>
</html>