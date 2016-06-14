<? define('PATH_TEMPLATE',base_url('/templates/marketing/').'/'); ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Webtransfer Advertising Network</title>
<link rel="stylesheet" href="<?=PATH_TEMPLATE?>css/libs.css">
<link rel="stylesheet" href="<?=PATH_TEMPLATE?>css/styles.css">
<link rel="stylesheet" href="<?=PATH_TEMPLATE?>css/style.css">
<link rel="stylesheet" href="<?=PATH_TEMPLATE?>css/swiper.min.css">
<link href="https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css" media="all">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,300italic,300,700italic,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link href="<?=PATH_TEMPLATE?>css/cs.animate.css" rel="stylesheet" type="text/css" media="all">
<link href="<?=PATH_TEMPLATE?>css/style.css" type="text/css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="<?=PATH_TEMPLATE?>js/jquery.knob.min.js"></script>
<?php    if($_SERVER['REQUEST_METHOD']=='POST'){  
    $front_email = 'advertising@webtransfer.com';
    require_once($_SERVER['DOCUMENT_ROOT'].'/send.php');    
} ?>
</head>
<body>


<!-- Preloader -->

<header class="header">
  <div class="top">

    <div class="container">
      <div class="row">
        <div class="col-lg-12">

          <nav class="main-nav">
            <a name="link" class="logo" href="#">
              <img src="<?=PATH_TEMPLATE?>images/logowt.gif" alt=""/>
            </a>

          </nav>
          <div class="fll btn-login feed" data-toggle="modal" data-target="#dialog2">
            <span class="hidden-xs"><?=_e('Заказать звонок')?></span>
          </div>
          <!-- <div class="flr btn-login tel">
            <a class="hidden-xs" href="tel:8-800-800-88-00"></a>
          </div> -->

        </div>
      </div>
    </div>

  </div>

</header>

  <div class="index-soc-sidebar not-legged">
    <div class="posap"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">


          <div class="users not-animated" data-animate="fadeIn" data-delay="100">
          
     

            <h4>
             <?=_e('Разместите Вашу рекламу и PR-материалы на сайте Webtransfer')?>
            </h4>

              <a class="nodec">
        <!-- <div class="pulse">
          <div class="button btn-primary btn-lg">Скачать предложение</div>
        </div> -->
      </a>


          </div>

        </div>
      </div>
  

    </div>
  </div>





<section class="sert clearfix">
  <div class="llf"></div><h2><?=_e('Webtransfer <span>сегодня это:')?></span></h2><div class="rrf"></div>
  <div class="container">
      <div class="row">
            <div class="col-lg-12 divers">
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="sv1 not-animated" data-animate="fadeInLeft" data-delay="100">
                  
                </div>
                <h5><?=_e('10 миллионов <br>пользователей')?></h5>
              
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="sv2 not-animated" data-animate="fadeInLeft" data-delay="300">
                 
                </div>
                <h5><?=_e('2,5 миллионов <br>просмотров <br>ежедневно')?></h5>
             
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="sv3 not-animated" data-animate="fadeInLeft" data-delay="700">
                 
                </div>
                <h5><?=_e('Средний <br>сеанс на сайте<br>18 минут')?></h5>
            
              </div>
            </div>
          </div>
        </div>

</section>




  <section class="fund-bg"></section>


<section class="oranges">
<div class="aa not-animated" data-animate="bounceIn" data-delay="100"></div>
  <h3><?=_e('По данным аналитического сервиса Alexa, Webtransfer входит в 400 крупнейших сайтов РФ')?></h3>
</section>





<section class="wwwtt">
<div class="aa2 not-animated" data-animate="bounceIn" data-delay="100"></div>
  <h3><?=_e('По темпам роста Webtransfer уже опередил социальную сеть Facebook за 1-й  год с момента создания')?></h3>

  
  <?if($this->lang->lang()=='ru'){?>
<p><?=_e('Подробнее о проекте читайте на странице')?> 
  <a href="https://webtransfer.com/ru/page/about">https://webtransfer.com/ru/page/about</a></p>
<?} else {?>
<p><?=_e('Подробнее о проекте читайте на странице')?> 
  <a href="https://webtransfer.com/en/page/about">https://webtransfer.com/en/page/about</a></p>
<?}?>
</section>




  
  <section class="fund-bg2"></section>
  
  

<section class="oranges2">

  <h3><?=_e('Вы можете продвигать Ваш бизнес, рекламируя его на нашу аудиторию')?></h3>
  <p><?=_e('По вопросам размещения рекламы  на Webtransfer обращайтесь на почту')?> <a href="mailto:advertising@webtransfer.com">advertising@webtransfer.com</a></p>
</section>




<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
       <div class="row">
         <div class="tre">
           <a href="https://www.facebook.com/Webtransfer/"><div class="tr1"></div></a>
            <a href="https://twitter.com/webtransfercom"><div class="tr2"></div></a>
             <a href="https://plus.google.com/u/0/118412304892954038780/"><div class="tr3"></div></a>
              <a href="https://vk.com/club55660968"><div class="tr4"></div></a>
			  
         </div>
       </div>

      </div>
    </div>
  </div>
</footer>





<div class="modal fade" id="dialog2" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <form action="" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span
          aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="dialogLabel"><?=_e('Оставьте заявку и мы Вам перезвоним!')?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">

            <input type="text" name="namer"  class="dfs" placeholder="<?=_e('Ваше имя')?>" class="form-text"/>
            <input type="tel" name="phone"  class="dfs" placeholder="<?=_e('Ваш телефон')?>" class="form-text"/>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="text-center">
          <button type="submit" value="Отправить" class="btn btn-primary"><?=_e('ЗАКАЗАТЬ ЗВОНОК')?></button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<script src="<?=PATH_TEMPLATE?>js/libs.js"></script>
<script src="<?=PATH_TEMPLATE?>js/scripts.js"></script>

<!-- Swiper JS -->
<script src="<?=PATH_TEMPLATE?>js/swiper.jquery.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
         autoplay: 4500,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev'
    });
    </script>

<script>
$(document).ready(function(){
    $("#div1").fadeOut(5000);
});
</script>

<script>
            $(document).ready(function(){
                $('.dummy').viewportChecker({
                    callbackFunction: function(elem, action){
                        setTimeout(function(){
                        
                               $(".dial").knob();
     $({animatedVal: 0}).animate({animatedVal: 20}, {
        duration: 2000,
        easing: "swing",
        step: function() { 
            $(".dial").val(Math.ceil(this.animatedVal)).trigger("change").css('font-size', '90px').css('color', '#37597D').css('margin-top', '74px').css('height', '118px'); 
        }   
     }); 
            

               $(".dial1").knob();
     $({animatedVal: 0}).animate({animatedVal: 80}, {
        duration: 2000,
        easing: "swing",
        step: function() { 
            $(".dial1").val(Math.ceil(this.animatedVal)).trigger("change").css('font-size', '90px').css('color', '#37597D').css('margin-top', '74px').css('height', '118px'); 
        }   
     }); 
     
     
                        $(".dial2");
     $({animatedVal: 0}).animate({animatedVal: 50}, {
        duration: 2000,
     
        step: function() { 
            $(".dial2").val(Math.ceil(this.animatedVal)); 
        }   
     }); 
            
                        });
                    }
                });
            });
        </script>
<script src="<?=PATH_TEMPLATE?>js/cs.script.js" type="text/javascript"></script>
<script src="<?=PATH_TEMPLATE?>js/viewportchecker.js"></script>

<script type="text/javascript"> function displ(ddd) { if (document.getElementById(ddd).style.display == 'none') {document.getElementById(ddd).style.display = 'block'} else {document.getElementById(ddd).style.display = 'none'} } </script>


<script>
	function anchorScroller(el, duration) {
if (this.criticalSection) {
return false;
}
 
if ((typeof el != 'object') || (typeof el.href != 'string'))
return true;
 
var address = el.href.split('#');
if (address.length < 2)
return true;
 
address = address[address.length-1];
el = 0;
 
for (var i=0; i<document.anchors.length; i++) {
if (document.anchors[i].name == address) {
el = document.anchors[i];
break;
}
}
if (el === 0)
return true;
 
this.stopX = 0;
this.stopY = 0;
do {
this.stopX += el.offsetLeft;
this.stopY += el.offsetTop;
} while (el = el.offsetParent);
 
this.startX = document.documentElement.scrollLeft || window.pageXOffset || document.body.scrollLeft;
this.startY = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
 
this. stopX = this.stopX - this.startX;
this.stopY = this.stopY - this.startY;
 
if ( (this.stopX == 0) && (this.stopY == 0) )
return false;
 
this.criticalSection = true;
if (typeof duration == 'undefined')
this.duration = 1500;
else
this.duration = duration;
 
var date = new Date();
this.start = date.getTime();
this.timer = setInterval(function () {
var date = new Date();
var X = (date.getTime() - this.start) / this.duration;
if (X > 1)
X = 1;
var Y = ((-Math.cos(X*Math.PI)/2) + 0.5);
 
cX = Math.round(this.startX + this.stopX*Y);
cY = Math.round(this.startY + this.stopY*Y);
 
document.documentElement.scrollLeft = cX;
document.documentElement.scrollTop = cY;
document.body.scrollLeft = cX;
document.body.scrollTop = cY;
 
if (X == 1) {
clearInterval( this.timer);
this.criticalSection = false;
}
}, 10);
return false;
}
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
</body>
</html>