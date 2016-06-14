<? define('PATH_TEMPLATE','/templates/2/'); ?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?if($this->lang->lang()=='ru'){?>
<meta property="og:image" content="https://webtransfer.com/images/banner_new_ru.jpg" />
<link rel="image_src" href="https://webtransfer.com/images/banner_new_ru.jpg" />
<?} else {?>
<meta property="og:image" content="https://webtransfer.com/images/banner_new_en.jpg" />
<link rel="image_src" href="https://webtransfer.com/images/banner_new_en.jpg" />
<?}?>
<meta property="og:title" content="<?=_e('Взаимное кредитование Webtransfer')?>">
<meta property="og:description" content="<?=_e('Выдаю быстрые займы участникам соц сетей на срок до 30 дней. Всем моим партнерам бонус $50!')?>">
<title>Webtransfer Social Network</title>
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

<?php    if($_SERVER['REQUEST_METHOD']=='POST')  require_once($_SERVER['DOCUMENT_ROOT'].'/send.php');     ?>
<? $this->load->view('user/blocks/banner_profile_login_home'); ?>
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"9qQ7k1a0CM00oQ", domain:"webtransfer-finance.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->  
<style type="text/css">
.soc-icoon{
	    position: absolute;
    width: 70px !important;
    height: 70px !important;
    background-size: contain !important;
    left: 50%;
    z-index: 9999;
    border-radius: 59px;
}
.bght{

    background: url(../images/com-2.png) 0 0 no-repeat !important;
}
.trty .trer {
    margin-left: 22px;
}
.nreviews-icons-sws {
    position: absolute !important;
    width: 70px!important;
    height: 70px!important;
    background-size: contain!important;
    left: 55%;
    margin-top: -87px!important;
    z-index: 99;
    border-radius: 59px;
}
</style>
  <script src='https://www.google.com/recaptcha/api.js' async defer></script>
</head>
<body>
<!-- Preloader -->
<div id="video_startup" class="modal fade in" role="dialog" aria-hidden="false" style="display: hide;">
		<div class="modal-dialog" style="width: 595px; padding-top:80px">
		
		<div class="modal-content">
		<div class="modal-body" style="padding:15px 15px 0 15px">
         <iframe width="560" height="315" src="https://www.youtube.com/embed/<?if($this->lang->lang()=='ru'){?>6CjDVBgKweA<? } else {?>Tw8IGc8DhxI<? } ?>?list=PLabzKoYrgEVjC4OrXGw-NGb6_QB6HDwDp" frameborder="0" allowfullscreen=""></iframe>
        <label><input type="checkbox" onchange="set_show_video()" name="dismiss" id='dismiss'><?=_e("Don't show again!")?></label><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div>
 </div></div>
		</div>

<header class="header">
  <div class="top">

    <div class="container">
      <div class="row">
        <div class="col-lg-12">

          <nav class="main-nav">
            <a class="logo" href="#">
              <img src="<?=PATH_TEMPLATE?>images/logowt.gif" alt=""/>
            </a>

          </nav>
          <div class="fll btn-login feed" data-toggle="modal" data-target="#dialog2">
            <span class="hidden-xs"><?=_e('Заказать звонок')?></span>
          </div>
		  <?php if ($showPhone == 1){ ?>
          <div class="flr btn-login tel">
            <a class="hidden-xs" href="tel:+<?=$phone?>">+<?=$phone?></a>
          </div> <?php }; ?>
		  <?php if ($showSkype == 1){ ?>
          <div class="flr btn-login skype">
            <a class="hidden-xs" href="skype: <?=$skype?>"><?=$skype?></a>
          </div>            
		  <?php }; ?>
        </div>
      </div>
    </div>

  </div>

</header>
<div class="wrapper">
  <div class="index-soc-sidebar not-legged">
    <div class="posap"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
        
          <h1><?=_e('Социальная кредитная сеть WEBTRANSFER')?></h1>
<a href="#" name="link"></a>
          <h2>
            <?=_e('Мы объединяем участников социальных сетей с целью получения и выдачи друг другу краткосрочных займов')?>
          </h2>

          <div class="users not-animated" data-animate="fadeIn" data-delay="100">
            <div class="usass"></div>
            <div class="enter-site">
              <ul class="list-unstyled list-inline social">
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big od not-animated fa fa-twitter social-twitter fsv" data-animate="bounceIn" data-delay="100"></a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big vk not-animated fa fa-odnoklassniki social-odnoklassniki fsv" data-animate="bounceIn" data-delay="300"></a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big tw not-animated fa fa-vk social-vkontakte fsv" data-animate="bounceIn" data-delay="500"></a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big in not-animated fa fa-linkedin nodin fsv" data-animate="bounceIn" data-toggle="modal" data-target="#dialog" data-delay="800"><div class="frtys"></div></a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big ml not-animated fsv-sb social-mail_ru" data-animate="bounceIn" data-delay="1100">@</a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big fb not-animated fa fa-facebook social-facebook fsv" data-animate="bounceIn" data-delay="1300"></a>
                </li>
                <li class="buzz-out">
                  <a href="#" class="icon-soc-big gl not-animated fa fa-google-plus  social-google_plus fsv" data-animate="bounceIn" data-delay="1500"></a>
                </li>
              </ul>
            </div>

            <h3><?=_e('p2p кредитование')?></h3>
            <h4>
              <?=_e('Высокодоходный рынок микрофинансирования теперь доступен')?>
              <br class="brr">
              <?=_e('для Вас. Мы убрали посредников, позволив всем желающим инвестировать деньги в микрокредиты, объединив их с людьми, которым эти деньги нужны.')?>
            </h4>

            <div class="col-lg-12 divers">
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="circle">
                  <div class="sv1"></div>
                </div>
                <h5>
                  <?=_e('Вы всегда
                  <br>при наличных')?></h5>
                <p class="bwh text-center">
                  <?=_e('Получайте наличные и бонусы, переводите деньги другим участникам сети моментально.')?>
                </p>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="circle">
                  <div class="sv2"></div>
                </div>
                <h5>
                  <?=_e('Гарантированные
                  <br>обязательства')?></h5>
                <p class="bwh text-center">
                  <?=_e('Выдача займа в кредитной социальной сети подтверждается кредитным сертификатом.')?>
                </p>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="circle">
                  <div class="sv3"></div>
                </div>
                <h5>
                  <?=_e('на расстоянии
                  <br>пары кликов')?></h5>
                <p class="bwh dfd text-center">
                  <?=_e('Получить заём или самому дать кредит — никогда не было так удобно!')?>
                </p>
              </div>
            </div>

            <div class="user-1">
              <img class="grow" src="<?=PATH_TEMPLATE?>images/temp-24.png" alt=""/>
            </div>
            <div class="user-2">
              <img class="grow"  src="<?=PATH_TEMPLATE?>images/temp-22.png" alt=""/>
            </div>
            <div class="user-3">
              <img class="grow"  src="<?=PATH_TEMPLATE?>images/temp-23.png" alt=""/>
            </div>
            <div class="user-4">
              <img class="grow"  src="<?=PATH_TEMPLATE?>images/temp-26.png" alt=""/>
            </div>
            <div class="user-5">
              <img class="grow"  src="<?=PATH_TEMPLATE?>images/temp-25.png" alt=""/>
            </div>
            <div class="user-6">
              <img class="grow"  src="<?=PATH_TEMPLATE?>images/temp-27.png" alt=""/>
            </div>

          </div>

        </div>
      </div>
      <a class="nodec" href="javascript: displ('var')">
        <div class="pulse">
          <div class="button btn-primary btn-lg" ><?=_e('узнать больше')?></div>
        </div>
      </a>

    </div>
  </div>

  <div id="var" style="display: none;">

    <section class="bgct orang">
      <div class="container index-three nopds">
        <div class="separators"></div>

        <h4 class="text-center wh wwh">
          <?=_e('Почему Р2Р кредитование
          <br class="brr">так выгодно?')?></h4>
        <div class="mvvz">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="bgts not-animated hidden-xq" data-animate="fadeIn" data-delay="100">
              <?=_e('Просто потому, что P2P кредитование дает вам возможность получать прибыль без посредников, а значит – в полном объеме. Таким образом, те, кто хочет кредитовать – работают напрямую с теми, кому нужны кредиты. Наша методика P2P (people to people) – это принцип работы человека с человеком, без промежуточных государственных или частных институций. В Социальной Сети Взаимного Микрокредитования Вебтрансфер вы самостоятельно принимаете решения о выдаче кредита заемщику, контролируете процентную ставку и время 
возвращения кредита, получая непосредственную прибыль на свой счет.')?>
            </p>

            <!-- use jssor.slider.min.js instead for release -->
            <!-- jssor.slider.min.js = (jssor.js + jssor.slider.js) -->
            <script type="text/javascript" src="<?=PATH_TEMPLATE?>js/jssor.js"></script>
            <script type="text/javascript" src="<?=PATH_TEMPLATE?>js/jssor.slider.js"></script>
            <script>
        jssor_slider1_starter = function (containerId) {
            var options = {
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)
                $BulletNavigatorOptions: {
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2                                //[Required] 0 Never, 1 Mouse Over, 2 Always
                },

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Required] Class to create arrow navigator instance
                    $ChanceToShow: 2                                //[Required] 0 Never, 1 Mouse Over, 2 Always
                }
            };

            var jssor_slider1 = new $JssorSlider$(containerId, options);
        }
    </script>
            <!-- Jssor Slider Begin -->
            <!-- To move inline styles to css file/block, please specify a class name for each element. -->
            <div id="slider1_container" class=slider1 style="position: relative; height: 340px;     margin: 0 auto;">

              <!-- Loading Screen -->
              <div u="loading" style="position: absolute; top: 0px; left: 0px;">
                <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                 top: 0px; left: 0px;width: 100%;height:100%;"></div>
                <div style="position: absolute; display: block; 
                top: 0px; left: 0px;width: 100%;height:100%;"></div>
              </div>

              <!-- Slides Container -->
              <div u="slides" class="hitm">
                <!-- Slide -->
                <div class="fonter">
                  <?=_e('Просто потому, что мы даем вам возможность получать прибыль без посредников, а значит – в полном объеме. Таким образом, те, кто хочет кредитовать – работают напрямую с теми, кому нужны кредиты. Наша методика P2P (people to people) – это принцип работы человека с человеком, без промежуточных государственных или частных')?>
                </div>
                <!-- Slide -->
                <div class="fonter">
                  <?=_e('институций, которым вы передаете все свои деньги и перестаете быть их владельцем.
               В Социальной Сети Взаимного Микрокредитования Вебтрансфер вы самостоятельно принимаете решения о выдаче кредита заемщику, контролируете процентную ставку и время 
возвращения кредита, получая непосредственную прибыль на свой счет.')?>
                </div>

              </div>
              <div u="navigator" class="jssorb03" style="    bottom: 4px;
    left: 50%;
    width: 42px;
    height: 21px;">
                <!-- bullet navigator item prototype -->
                <div u="prototype">
                  <div u="numbertemplate"></div>
                </div>
              </div>
              <script>jssor_slider1_starter('slider1_container');</script>
            </div>
            <!-- Jssor Slider End --> </div>
        </div>
      </div>
    </section>

  </div>
  <section class="fund-bg"></section>
<section class="white-bgs">
<h3>Webtransfer VISA</h3>
      <h4>             
<?=_e('Вам важно максимально эффективно использовать свои деньги, прилагая минимум усилий? Мы создали именно такую систему: удобную, быструю и приносящую прибыль. С картами Webtransfer VISA вы можете осуществлять все привычные операции и получать приятные бонусы.')?>
            </h4>
</section>
  

  
  <div class="wrapper">
  <div class="index-soc-sidebar  ireww not-legged dblue-bg">

    <div class="container">
      <div class="row">
        <div class="col-lg-12">
     

  
            <div class="row npds">
            
            <div class="cardy"></div>
            
            
            <div class="col-lg-12 col-lu">
              <div class="fll nsi not-animated" data-animate="fadeInLeft" data-delay="100">
     <?=_e('ЗАЙМЫ И Р2Р ПЕРЕВОДЫ 
МЕЖДУ КАРТАМИ 
С СОХРАНЕНИЕМ 
КОНФИДЕНЦИАЛЬНОСТИ')?>
              </div>
         
         
         
                 <div class="flr nsi2 not-animated" data-animate="fadeInRight" data-delay="100">
     <?=_e('МГНОВЕННОЕ 
ПОПОЛНЕНИЕ И полная 
ОТЧЕТНОСТЬ ПО
ДВИЖЕНИЮ СРЕДСТВ')?>


              </div>
              
              
            </div>
  
  
  
  
            <div class="col-lg-12">
              <div class="fll nsi not-animated" data-animate="fadeInLeft" data-delay="100">
   <?=_e('ВОЗМОЖНОСТЬ СНИМАТЬ 
НАЛИЧНЫЕ БОЛЕЕ 
ЧЕМ В 2 МИЛЛИОНАХ 
БАНКОМАТОВ')?>


              </div>
         
         
         
                 <div class="flr nsi2 not-animated" data-animate="fadeInRight" data-delay="100">
 <?=_e('ПОКУПКИ ПО ВСЕМУ
 МИРУ: ОНЛАЙН-ШОППИНГ
 И ОПЛАТА ТОВАРОВ И 
УСЛУГ НА МЕСТЕ')?>



              </div>
              
              
            </div>
  
  
            </div>
            

          </div>

        </div>
      </div>
      <a class="nodec"  href="https://webtransfercard.com">
      <div class="container">
         <div class="pulse">
        <div class=" button orangebt codd" data-toggle="modal" data-target="#dialog"><?=_e('получить карту')?></div>
      </div>
      </div>
      <div class="bght">
	  <div class="col-md-4 col-sm-4">
         <div class="txts-cf">
		 <div class="trer">1.5<span>%</span></div>
        <?=_e('Комиссия <br>за пополнение')?>
      </div>
	  </div>
	  <div class="col-md-4 col-sm-4">
        <div class="txts-cf trty">
		<div class="trer">0<span>%</span></div>
        <?=_e('Комиссия <br>за покупки')?>
      </div></div>
	  <div class="col-md-4 col-sm-4">
       <div class="txts-cf">
	   <div class="trer">1.95<span>$</span></div>
        <?=_e('Комиссия за  <br>снятие наличных')?>
      </div></div>
      </div>
     
      </a>

    </div>
  </div>


  
  <div class="wrapper">








  <section class="sitec">
    <div class="container index-three">
      <div class="separators"></div>

      <h4 class="text-center alis"><?=_e('Зарабатывай, помогая другим!')?></h4>
      <h5 class="text-center alis2">
        <?=_e('Участие в кредитной сети Webtransfer выходит за рамки выдачи<br class="brr">и получения займов')?></h5>

    </div>

    <div class="boxesize row not-animated" data-animate="fadeIn" data-delay="100">
      <div class="box dummy">
        <input type="text" value="50" class="dial" data-readOnly="true"  data-font="'Open Sans'" data-fgColor="#FF6600" data-bgColor="#fff" data-width="260"  data-height="260" data-thickness="0.15">
        <span>%</span>
        <p class="circlear text-center">
          <?=_e('от прибыли webtransfer<br>получают наши<br>партнеры')?></p>
      </div>
      <div class="box dummy"> <strong>+</strong>
        <input type="text" value="80" class="dial1" data-readOnly="true" data-font="'Open Sans'" data-fgColor="#3390EF" data-bgColor="#fff" data-width="260"  data-height="260" data-thickness="0.15">
        <span>%</span>
        <p class="circlear text-center">
          <?=_e('от прибыли webtransfer<br>получают наши<br> <i>vip партнеры')?></i>
        </p>
      </div>
    </div>
    <div class="container">
	

	
      <div class="pulse">
        <div class=" button newbt" data-toggle="modal" data-target="#dialog"><?=_e('Стать партнером')?></div>
      </div>
    </div>

  </section>
  
<?if($this->lang->lang()=='ru'){?>
  <section class="otz-bg" style="height: 940px;background-attachment: inherit;background-position-y: -20px;">
    <div class="container">
          <div style="margin-top: 75px;  height:780px; background-color:#FFF" class="row">
        <!-- Swiper -->
        <div style="margin-top:0px" class="swiper-con">
          <div class="swiper-container">
            <div class="swiper-wrapper">
			<div class="swiper-slide">
    
                <p>
                    
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face4.png" alt="">
               <a class="soc-icoon" href="https://twitter.com/Fader27"><img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-tw.png" alt=""></a>
                 <?=_e('
				Говорят, айтишники - люди с другой планеты. Но несмотря на это, Вебтрансфер объединил под своей крышей людей самых разных профессий. Зато мы, специалисты IT-сферы, как никто другой видим потенциал системы. Webtransfer - это как живой организм: весьма непрост в управлении, зато очень увлекательный. Такого еще никто не делал. Это стартап года, безусловно!')?> <strong> <?=_e('Дмитрий Левин')?></strong>
              
                </p>

              </div>
			   <div class="swiper-slide">
			   
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face5.png" alt="">
                 <a class="soc-icoon" href="https://vk.com/arg50"> <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-vk.png" alt=""></a>
                 <?=_e('Webtransfer изменил мою жизнь и свое представление о будущем в финансовом отношении . Здесь очень интересно работать и предстоит еще много всего интересного. А команда, которая собралась, еще больше настраивает на позитивное мышление и уверенность в заработке!!! Помогу всем зарабатывать в Webtransfer достойно. Спасибо всем !!! ')?>
                  <strong><?=_e('Рафаел Агамян')?></strong>
            
                </p>
              </div>
			  <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face10.png" alt="">
                 <a class="soc-icoon" href="https://www.facebook.com/wt.kirovohrad"> <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-fb.png" alt=""></a>
                 <?=_e('Для меня компания Webtransfer это не просто бизнес, это стиль жизни! 
Если вы Активные, амбициозные,образованные, жаждущие реализации в глобальной,международной инновационной компании- WEBTRANSFER это для ВАС! С нашей командой координаторов и Президентом А. Колесниченко, мы создадим одну из богатейших компаний в мире. Достойные деньги за достойный труд!
 Присоединяйтесь к нашей команде! Обучение, поддержка и дружественный коллектив гарантируются!')?>
                  <strong><?=_e('Дмитрий Крикун')?></strong>
             
                </p>
              </div>
			  
			  <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face6.png" alt="">
                  <a class="soc-icoon" href="https://www.facebook.com/dainius.liveika"> <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-fb.png" alt=""></a>
                 <?=_e('За один год WEBTRANSFER достиг очень многого,он вышел за рамки просто проекта. WEBTRANSFER это уже явление,мимо которого не пройдет ни один лидер, кто-то раньше,а кто-то позже присоединится к нашей большой семье. И каждому Мы все будем рады ! 
 WEBTRANSFER это повседневность,общение,работа,новые знания,знакомства ну и конечно заработок.<br>
 Так же много других возможностей,с которыми ВАС с удовольствием познакомлю и помогу разобраться в них! 
 Больших вам профитов!')?>
                  <strong><?=_e('Данииус Ливейко')?></strong>
            
                </p>
              </div>
			  
			  <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face7.png" alt="">
                  <a class="soc-icoon" href="https://www.vk.com/avantis"> <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-vk.png" alt=""></a>
                 <?=_e('Больших вам профитов! Лучший проект для пассивного заработка 2014-2015 
Надежный и легальный бизнес. Компания WEBTRANSFER изменит весь мир! Все будет Р2Р! Каждый сможет отлично заработать ,реализоваться в нашей команде, среди целеустремленных,образованных,амбициозных партнеров! Присоединяйтесь смело!')?>
                  <strong><?=_e('Алена Авантис')?></strong>
            
                </p>
              </div>
			  <div class="swiper-slide">
                <p>
                 <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face8.png" alt="">
                   <a class="soc-icoon" href="https://www.ok.ru/profile/530772880877"><img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-o.png" alt=""></a>
                 <?=_e('Страна Вебтрансфер - это страна, в которой хочется жить и работать...благодаря ей у меня осуществились уже две мечты - побывала в Питере, да еще и в период белых ночей...и не сама, а с с друзьями- партнерами, единомышленниками!!!!
а так же - с друзьями - партнерами - путешествие в Черногорию....Здорово!!!!
Очень рада новым знакомствам в период организации и проведения двух региональных семинаров - в Днепропетровске и в Бердянске.. <br>
Мой девиз - быть полезной людям и получать удовлетворение от работы - это есть в Вебтрансфере!!!! <br>
Друзья!!! Желаю всем нам - зарулить к счастью, не тормозить перед препятствиями, переехать все свои проблемы, не сбиться со своей дороги и пусть всегда горит зеленый!!!!')?>
                  <strong><?=_e('Евгения Поникарова')?></strong>
                 
                </p>
              </div>
              <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face.png" alt="">
				  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-vk.png" alt="">
                  <?=_e('Присоединилась к WEBTRANSFER в октябре 2014 года, Восхищает размах, уникальность, инновации - все самое передовое из достижений человечества и 
				  с прицелом на 100 лет вперед! Сотрудничаю с большим интересом и удовольствием! ')?>
				  <strong><?=_e('Ольга Салтанова')?></strong>
                </p>
              </div>
              <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face2.png" alt="">
				  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-mail.png" alt="">
                  <?=_e('Люблю Webtransfer за честность, надежность и возможности. Работаю с большим удовольствием. Обучаю партнеров работе и стратегиям.')?>
                  <strong><?=_e('Наталия Леонтьева')?></strong>
                  
                </p>
              </div>
              <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face3.png" alt="">
				  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-fb.png" alt="">
                  <?=_e('Webtransfer стал настоящим феноменом в интернете! В начале никто, в том числе и я, не ожидал такого размаха! 
				  Идея Р2Р, которую воплощает Webtransfer - это действительно настоящий прорыв в отношениях между людьми. 
')?>
                  <strong><?=_e('Сергей Подлесский ')?></strong>
                  
                </p>
              </div>
               
              <!-- <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face8.png" alt="">
                  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-fb.png" alt="">
                   <?=_e('Страна Вебтрансфер - это страна, в которой хочется жить и работать...благодаря ей у меня осуществились уже две мечты - побывала в Питере, да еще и в период белых ночей...и не сама, а с с друзьями- партнерами, единомышленниками!!!!
а так же - с друзьями - партнерами - путешествие в Черногорию....Здорово!!!!
Очень рада новым знакомствам в период организации и проведения двух региональных семинаров - в Днепропетровске и в Бердянске.. <br>
Мой девиз - быть полезной людям и получать удовлетворение от работы - это есть в Вебтрансфере!!!! <br>
Друзья!!! Желаю всем нам - зарулить к счастью, не тормозить перед препятствиями, переехать все свои проблемы, не сбиться со своей дороги и пусть всегда горит зеленый!!!!')?>
                  <strong><?=_e('Евгения Поникарова')?></strong>
                 
                </p>
              </div>-->
             <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face9.png" alt="">
                  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-mail.png" alt="">
                <?=_e('В Webtransfer пришел не так давно. Но, сразу понял, что это мое. 
Широкий размах на весь мир, инновационный подход к решению задач, возможность работать в профессиональной команде, многомиллионная аудитория, возможность быстрого и легального заработка - это та площадка на которой мечтал бы работать любой продвинутый бизнесмен.
Webtransfer дал мне возможность в полной мере раскрыть мои потенциальные возможности, и это сейчас - в условиях мирового кризиса. <br>
Я живу в Испании, где этот кризис ощущается особенно остро, поэтому работа в Webtransfer не только дает мне возможность заработать нужное мне количество денег, но и (я создал агентство по подбору персонала) помочь многим людям с помощью Webtransfer выйти из серьезных финансовых осложнений. <br>
Моя оценка настоящей ситуации в работе Webtransfer – цитируя фразу Александра Колесниченко – «Это только начало»!')?>
                  <strong><?=_e('Григорий Евстигнеев')?></strong>
               
                </p>
				
				
              </div>
			 
			  
                 <!-- <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face4.png" alt="">
                  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-o.png" alt="">
                  Мы убрали посредников, позволив всем желающим инвестировать деньги в микрокредиты, объединив 
их с людьми, которым эти деньги нужны.
                  <strong>Имя Фамилия</strong>
                 
                </p>
              </div>
              
               
              
           <!--    <div class="swiper-slide">
                <p>
                  <img class="img-responsive facer" src="<?=PATH_TEMPLATE?>images/face6.png" alt="">
                  <img class="img-responsive icons-sws" src="<?=PATH_TEMPLATE?>images/i-vk.png" alt="">
                 Для меня компания Webtransfer это не просто бизнес, это стиль жизни! 
Если вы Активные, амбициозные,образованные, жаждущие реализации в глобальной,международной инновационной компании- WEBTRANSFER это для ВАС! С нашей командой координаторов и Президентом А. Колесниченко, мы создадим одну из богатейших компаний в мире. Достойные деньги за достойный труд!
 Присоединяйтесь к нашей команде! Обучение, поддержка и дружественный коллектив гарантируются!
                  <strong>Имя Фамилия</strong>
             
                </p>
              </div>-->
			  
		<div class="swiper-slide"> <p>
		<img class="img-responsive facer" src="/img/newface1.png" alt="User image">
        <a class="soc-icoon" target="blank" href="https://plus.google.com/105544948883994326102">
		<img class="img-responsive icons-sws" src="/img/i-go.png" alt=""></a> 
Всем привет! Webtransfer - самая приятная моя работа, я сам себе начальник, работаю сколько хочу <a href="http://wt2015.wix.com/evgen">http://wt2015.wix.com/evgen</a>  , такой же сайт для привлечения партнеров в подарок, есть чаты с поддержкой новичков и обучение использования WT в полную силу! скайп- mawr174-5, присоединяйтесь!
	<strong class="main-sx-vertical9"> Евгений Захаров</strong>
	</p>
</div>




    <div class="swiper-slide"> <p>
       <img class="img-responsive facer" src="/img/newface2.png" alt="User image">
      <a class="soc-icoon" target="blank" href="http://my.mail.ru/my/welcome">
	  <img class="img-responsive nreviews-icons-sws" src="/img/i-mail.png" alt=""> </a>
С Webtransfer сотрудничаю в сфере инвестиций уже достаточно давно, что бы рекомендовать как надежного партнера! Простой интуитивный интерфейс позволяет достаточно быстро освоиться и начать свое полноценное дело. А для более успешного старта советую присоединиться к опытному наставнику и работать в команде!
<strong class="main-sx-vertical10"> Игорь Андреев</strong>
</p>
    </div>





<div class="swiper-slide"> <p>
       <img class="img-responsive facer" src="/img/newface3.png" alt="User image">
       <a class="soc-icoon" target="blank" href="https://vk.com/id79446805">   <img class="img-responsive nreviews-icons-sws" src="/img/i-vk.png" alt=""> </a>
         
Проект отличный и я здесь уже давно,но деньги после 25 числа все на USD1 и не могу снять...У жены карточка лежат 1600 $ и тоже не может вывести,даже заняла под гаранд 500 $ и тоже нет вывода.Хотя пишут вывод с сердечком и акция гаранд за 30 минут на счёт 1000 $,я всего этого не вижу((( <strong class="main-sx-vertical10"> Игорь Андреев</strong> </p>
    
</div>


<div class="swiper-slide"> <p>
         <img class="img-responsive facer" src="/img/newface4.png" alt="User image">
        <a class="soc-icoon" target="blank" href="https://vk.com/id251612527">   <img class="img-responsive nreviews-icons-sws" src="/img/i-vk.png" alt=""> </a>
         
Отличный проект. Начинается повышение курсов на р2р бирже. Призываю всех, вернее не всех, а самых не терпеливых. Не меняйте свои деньги по мизеру. Видно же по последним сделкам на бирже, что люди выводят USD1 один к одному на паер и на перфект, остальные пока спекулируют, но это пока!!! И еще момент- один к одному, это тоже пока!!! Посмотрим, как дальше будут развиваться события (битва за клиентов среди обменников) до конца месяца. Думаю до нового года курс на вывод будет как минимум 1/1,5. Смотрите сами, чтобы потом локти не пришлось кусать... <strong class="main-sx-vertical12">Василий Павлов</strong></p>
    </div>





   <div class="swiper-slide" > <p>

          <img class="img-responsive facer" src="/img/newface5.png" alt="User image">
        <a class="soc-icoon" target="blank" href="https://vk.com/id220513844">  <img class="img-responsive nreviews-icons-sws" src="/img/i-vk.png" alt=""> </a>
        
Работаю с данной компанией больше года.Имея опыт, более 20 лет в нетворк- маркетинг, могу сказать , 
что работая в такой компании , каждый получит бесценный опыт и знания.
Компания очень живая. Скучно не бывает. Каждый может найти для себя то- чего ему не хватало: 
дополнительный доход, обучение финансовой грамотности и саморазвитие в этом направлении ,
серьезны подход к своим денежным активам, новых друзей , партнеров готовых прийти на помощь, 
да и просто интересное общение.<strong class="main-sx-vertical13"> Илана Лившиц</strong></p>
    </div>




   <div class="swiper-slide" > <p>

         <img class="img-responsive facer" src="/img/newface6.png" alt="User image">
        <a class="soc-icoon" target="blank" href="https://profile.hypercomments.com/3579968">     <img class="img-responsive nreviews-icons-sws" src="/img/i-o.png" alt=""> </a>
      
Я в Проекте Webtransfer с мая месяца 2015 года! Уже имею неплохие профиты. Очень доволен, что участвую в этом замечательном Проекте! Я люблю Webtransfer!!! Новый вход в WT - просто потрясающий своим великолепием!!! Суперский софт соцканала! У меня замечательный Старший - это Дмитрий Сергеевич Агарков!!! Большое ему Спасибо!!! А так же благодарю создателей и руководителей Проекта! И всем вам желаю здоровья и успехов на нашем финансовом поприще!!! Удач иПобед!!! И никакого уныния!!! Я четко улавливаю высокоположительную динамику в Проекте и верю, что всё будут ОК!!!   <strong class="main-sx-vertical14">Евгений Булыгин</strong></p>
    </div>




   <div class="swiper-slide" > <p>

         <img class="img-responsive facer" src="/img/newface7.png" alt="User image">
        <a class="soc-icoon" target="blank" href="https://www.facebook.com/Jmmi777">  <img class="img-responsive nreviews-icons-sws" src="/img/i-fb.png" alt=""> </a>
     
Отличный сайт для заработка через интернет! И так как проект технически сложный для новичков, то основная нагрузка ложится на старших партнеров и от того как они будут обучать своих младших и помогать им в освоении всех нововведений, зависит работа всей команды. При грамотной и своевременной помощи, проблем с работой у вас не будет.<strong class="main-sx-vertical15"> Sergey Popov</strong> </p>
    </div>



   <div class="swiper-slide"> <p>

         <img class="img-responsive facer" src="/img/newface8.png" alt="User image">
           <a class="soc-icoon" target="blank" href="https://profile.hypercomments.com/3467589">  <img class="img-responsive nreviews-icons-sws" src="/img/i-o.png" alt=""> </a>
      Наталья Москвина-Непомнящих</strong>
Уважаемые партнеры! Хочу поделится с Вами своим мнением об этом прекрасном проекте.Webtransfer-это платформа, которая предоставляет возможность не только хорошего заработка,но также финансового благополучия, финансовой грамотности и развития бизнеса.Вы становитесь не только банкиром, но и бизнесменом. Вы можете достичь больших успехов в этом проекте, каких достигают люди, в том числе и я. Я говорю спасибо всем создателям этого удивительного проекта, и всех благ. Мы тебя любим наш WT. Рекомендую всем тем людям, которые хотят быть финансово-благополучны.С большим Уважением, Наталья Москвина. Админ-WT.</p>
    </div>



   <div class="swiper-slide" > <p>

        <img class="img-responsive facer" src="/img/newface9.png" alt="User image">
        <a class="soc-icoon" target="blank" href="http://my.mail.ru/mail/ilkailka/">   <img class="img-responsive nreviews-icons-sws" src="/img/i-mail.png" alt=""> </a>
         
Cпасибо вебтрансферу за то, что постоянно приходится вникать в такие интересные вещи, как кредс-сы,партнерс-сы и баксы-1 и баксы-сердечные и еще разбираться с увлекательными обменниками и загадочными биржами...
и хотя я ничего не понял до конца, но все же надеюсь, что вебтрансфер скоро создаст отдельную школу для таких как я-абсолютно не понимающих все это дело!!!<strong class="main-sx-vertical17"> Илья !</strong>
</p>
</div>


   <div class="swiper-slide" > <p>

  <img class="img-responsive facer" src="/img/newface10.png" alt="User image">
           <a class="soc-icoon" target="blank" href="https://www.facebook.com/dos777dos">   <img class="img-responsive nreviews-icons-sws" src="/img/i-fb.png" alt=""> </a>
       
Я, Водитель профессионал с 25-ти летним стажем. В апреле 2013 года посетил по приглашению совсем не знакомого мне человека бесплатное онлайн мероприятие в прямом эфире... МОЯ ЖИЗНЬ ИЗМЕНИЛАСЬ и теперь моя цель помочь Вам настроить собственный денежный поток из интернет и вырваться из "замкнутого круга".
Нельзя вернуться в прошлое и изменить свой старт - Но можно стартовать сейчас и изменить свой финиш!
<a href="https://webtransfer.com/partner/dos777dos">https://webtransfer.com/partner/dos777dos</a><strong class="main-sx-vertical18"> Виталий Двикалюк</strong>
</p>
    </div>



   <div class="swiper-slide" > <p>

          <img class="img-responsive facer" src="/img/newface11.png" alt="User image">
             <a class="soc-icoon" target="blank" href="https://www.facebook.com/leonteva">  <img class="img-responsive nreviews-icons-sws" src="/img/i-fb.png" alt=""> </a>
       
Год работаю в компании и не перестаю влюбляться! С каждым днем Вебтрансфер все сильней, все устойчивей стоит на ногах и это не может понять только глупец :) Будущее за нами! Присоединяйтесь, друзья!!! Трудность - временные моменты, но именно тогда команда становится сплоченней и сильней. Мы - лучшие!
    В моей команде только бизнесмены! Только адекватные люди, которые смогут вас научить и помочь начать бизнес!! <a href="https://webtransfer.com/ru/partner/lopes">https://webtransfer.com/ru/partner/lopes</a><strong class="main-sx-vertical19"> Наталия Замир</strong></p>
    
</div></div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            </div>
            
      
          </div></div><div style="margin-top:-150px">
<a class="nodec" href="https://webtransfer.com/reviews.html">
<div class="pulse">
<div class="button newbt">Все отзывы</div>
</div>
</a></div>
  </section>
<? } ?>
  <section class="sect-foot <?if($this->lang->lang()=='ru'){?>sits<? } ?>" style="margin-top: -20px;">
    <div class="container index-three nopadb">
      <div class="separators"></div>

      <h4 class="text-center alis2 swef"><?=_e('Моментальный бонус на счет')?></h4>
      <h5 class="alis2">
        <?=_e('Регистрация позволяет автоматически получить на счет бонус в размере $50. ')?>
        <br class="brr">
        <?=_e('Вы сразу можете кредитовать другого участника сети и получать <br class="brr"> взаимоприемлемый процент - вашу прибыль!')?></h5>
      <div class="oblbg">
        <div class="stl"></div>
        <div class="str"></div>
        <h6>
          <div class="dummy dummys"> <span>$</span>
            <input class="dial2" data-readOnly="true" disabled>
           
            <strong class="hidden-lg">50$</strong>
          </div>
        </h6>

      </div>
      <div class="pulse">
        <a href="#link" onclick="return anchorScroller(this)"><div class="btn button newbt btn-primary btn-lg"><?=_e('присоединиться')?></div></a>
      </div>

    </div>
  </section>

  <section class="sect-foot">
    <div class="liners"></div>
    <div class="container index-three nopadall hidden-xs" style="background:#FFF; width:100%">
      <div class="separators"></div>
	  
<?if($this->lang->lang()=='ru'){?>
<section id="page-media" >
<div class="page-wrapper">
            <h2 class="page-media_h2"><?=_e('blocks/footer_2')?></h2>
            <div id="slider">
                <div class="controls left" data-name="prev">
                    <div></div>
                </div>
                <div class="wrapper">
                    <ul class="slides">
                        <li class="slide-item-mm">
                            <a href="http://top.rbc.ru/technology_and_media/15/09/2015/55f7421d9a7947eff6273be7" target="_blank">
                                <img src="/img/img-04_19.png" alt="" style="height: 40px; margin-top: 10px;">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://izvestia.ru/news/587665" target="_blank">
                                <img src="/img/downline_news/izvestya.png" alt="" style="max-height: 40px; margin-top: 9px;"/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://lenta.ru/articles/2015/05/13/webgobseck/" target="_blank">
                                <img src="/img/lenta_ru.png" alt="" style="max-height: 60px;"/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://nbj.ru/publs/ot-redaktsii/2015/03/27/ozhidanija-2015-goda-rost-p2p-mikrokreditovanija-v-rossii/index.html" target="_blank">
                                <img src="/images/nbj.gif" alt="" style="max-height: 41px; margin-top: 10px;"/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://moneynews.ru/Interview/20320/" target="_blank">
                                <img src="/img/img-04_07.jpg" alt="" />
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://bankir.ru/novosti/s/webtransfer-podvel-itogi-p2p-mikrokreditovaniya-za-i-kvartal-2015-goda-10100573/" target="_blank">
                                <img src="/img/img-04_06.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://b-mag.ru/2014/trend/mister-i-missis-shering/" target="_blank">
                                <img src="/img/BM-Logo_01.png" alt="" style="padding-top: 13px;"/>
                            </a>
                        </li>

                        <li class="slide-item-mm">
                            <a href="http://top.rbc.ru/technology_and_media/02/10/2014/542bfae4cbb20f09c6b37d50" target="_blank">
                                <img src="/img/img-04_19.png" alt="" style="max-height: 40px; margin-top: 8px;"/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.cnews.ru/news/2013/07/30/vebtransfer_zapuskaet_v_rossii_svoy_novyy_investicionnyy_produkt_kreditnye_sertifikaty_537143" target="_blank">
                                <img src="/img/img-04_01.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.burocrats.ru/investcomp/130729131816.html" target="_blank">
                                <img src="/img/img-04_16.gif" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://news.rambler.ru/20319017/" target="_blank">
                                <img src="/img/img-04_17.png" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://moneynews.ru/News/15815/" target="_blank">
                                <img src="/img/img-04_07.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.banki.ru/news/lenta/?id=2913914" target="_blank">
                                <img src="/img/img-04_03.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.e-moneynews.ru/webtransfer-plany-i-lecenziya/" target="_blank">
                                <img src="/img/img-04_04.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://bankir.ru/novosti/s/novie-vozmojnosti-onlain-platejei-ot-kompanii-webtransfer-9567762/#ixzz1oKuFCslV" target="_blank">
                                <img src="/img/img-04_06.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://moneynews.ru/News/15235/" target="_blank">
                                <img src="/img/img-04_07.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.ifin.ru/news/read/8971.stm" target="_blank">
                                <img src="/img/img-04_08.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.cnews.ru/news/line/index.shtml?2011/12/13/468774" target="_blank">
                                <img src="/img/img-04_01.jpg" alt=""/>
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.dk.ru/firms/98688236/news/236745507" target="_blank">
                                <img src="/img/img-04_02.jpg" alt="">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://www.banki.ru/news/lenta/?id=2913914" target="_blank">
                                <img src="/img/img-04_03.jpg" alt="">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://club.cnews.ru/blogs/entry/analiz_investitsionnoj_aktivnosti_i_novye_instrumenty_vysokodohodnogo_investirovaniya_v_rossii" target="_blank">
                                <img src="/img/img-04_11.jpg" alt="">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://alfabank.ru/retail/2012/2/10/27768.html" target="_blank">
                                <img src="/img/img-04_13.jpg" alt="">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://pay2.ru/2012/02/webtransfer-alfa-click/" target="_blank">
                                <img src="/img/img-04_15.jpg" alt="">
                            </a>
                        </li>
                        <li class="slide-item-mm">
                            <a href="http://moneynews.ru/News/15041/" target="_blank">
                                <img src="/img/img-04_07.jpg" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="controls right" data-name="next">
                    <div></div>
                </div>
            </div></div></section>
			<?} else {?>
      <div class="parnrers">
        <ul class="list-unstyled list-inline social">
          <li class="fris">
            <a href="#" class="icon-soc-big par1 hover"></a>
          </li>
          <li>
            <a href="#" class="icon-soc-big par2 hover"></a>
          </li>
          <li>
            <a href="#" class="icon-soc-big par3 hover"></a>
          </li>
          <li>
            <a href="#" class="icon-soc-big par4 hover"></a>
          </li>

        </ul>
      </div><?};?>

    </div>
  </section>

  <section class="bgct">
    <div class="container index-three nopds">
      <div class="separators"></div>

      <h4 class="text-center wh"><?=_e('будьте всегда в курсе')?></h4>
      <h5 class="text-center wh">
        <?=_e('Укажите свой E-mail, чтобы оставаться всегда в курсе событий<br class="brr">и обновлений  в Webtransfer')?></h5>
      <div class="form-area">
        <form action="" method="post">
            <input type="hidden" name="subscribe" value="1">
          <div class="input-area">
            <input type="text" name="mails" class="dffgsss" placeholder="E-mail" class="form-text"/>
            <button type="submit" value="<?=_e('Отправить')?>"><?=_e('Подписаться')?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="sect-footim">
  <div class="container index-three">
    <div class="separators"></div>

    <h4 class="text-center wh footst"><?=_e('Остались вопросы?')?></h4>
    <div class="footer-form">
      <form action="" method="post">

        <input type="tel" name="phone"  class="dffgsss" placeholder="<?=_e('Ваш телефон')?>" class="form-text not-animated" data-animate="fadeInLeft" data-delay="100"/>

        <input type="text" name="mails" class="dffgsss" placeholder="<?=_e('E-mail')?>" class="form-text not-animated" data-animate="fadeInLeft" data-delay="300"/>

        <textarea class="areatext not-animated" data-animate="fadeInLeft" data-delay="500" name="questions" rows="3" cols="5"
                            placeholder="<?=_e('Ваш вопрос')?>"></textarea>

        <div class="pulse">
          <button class="button" type="submit" value="Отправить"><?=_e('Отправить')?></button>
        </div>
      </form>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="fll lg1">
          <img src="<?=PATH_TEMPLATE?>images/logof.png" width="72" height="38" alt=""></div>
        <a class="logo">2015<span class="nonon"> / <?=_e('все права защищены')?></span></a>
        <div class="flr lg2">
          <img src="<?=PATH_TEMPLATE?>images/vf.png" alt=""></div>
      </div>
    </div>
  </div>
</footer>



<div class="modal fade" id="dialog" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="dialogLabel"><?=_e('Вход в систему / Регистрация')?></h4>
</div>
<form id="login-form-popup">
<input type="hidden" name="lang" value="<?=_e('lang')?>">      
<div class="modal-body">
<div class="form-group">
<input type="text" id="form-login" name="login" onkeyup="checkForm()" onchange="checkForm()" class="form-control" placeholder="<?=_e('Эл.адрес:')?>">
</div>
<div class="form-group">
<input type="password" id="form-pass" onkeyup="checkForm()" onchange="checkForm()" name="password" class="form-control" placeholder="<?=_e('Пароль:')?>">
</div>
 <div class="g-recaptcha" data-sitekey="<?=  config_item('publickeyCapcha')?>"></div>
</div>
<div class="text-center" style="display: none;color:red;" id="login_popup_error"></div>
<div class="modal-footer">
<div class="text-center">
<a href="https://webtransfer.com/ask/forget"><?=_e('Получить пароль')?></a>
</div>
<br>
<div class="text-center">
<button type="submit" id="form-submit" class="btn btn-primary" disabled=""><?=_e('Войти')?></button>
</form>
</div>
</div>
</div>
  
  </div>
</div>

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
<script type="text/javascript">
            
            $(document).ready(function () {
                checkCookie();
            });
            

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; " + expires;
            }

            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i=0; i<ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1);
                    if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
                }
                return "";
            }

            function checkCookie() {
                var show_video = getCookie("show_video");
                console.log( show_video );
                if (show_video == '' || show_video == 'true') 
                    $('#video_startup').modal();
                
            }
            
            function set_show_video(){
                checked = !$("input[name=dismiss]").is(":checked");
                setCookie("show_video", checked,30);
            }
            
            
            

        </script>
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
        prevButton: '.swiper-button-prev',
		loop: true
    });
    </script>

<script>
$(document).ready(function(){
    $("#div1").fadeOut(5000);
});
</script>
<!--<script src="<?=PATH_TEMPLATE?>js/jquery.maskedinput.min.js"></script>-->

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

<script data-cfasync="false" type="text/javascript" src="/js/slider-module.js"></script>
<script type="text/javascript">
    (function($){
        $('#slider .slides').htmSlider();
    })(jQuery);
</script>
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
</body>
</html>