<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    var current_protect_type = '<?= ( !isset( $security )?'none': $security ) ?>';
    var current_protect_type_show = '<?= ( !isset( $security_show )?'none': (int)$security_show ) ?>';
    var show_set_force_phone = "<?=(int)$show_set_force_phone?>";
   
    function forcing_one_pass_setup(res)
    {
        if (typeof res['res'] === undefined || res['res'] != 'success') {
            if (res['res'] == 'closed')
                location.reload();
            return false;
        }

        var data = {"old": {"type": current_protect_type, "code": "0000000"}, 'new': {type: 'one_pass', code: res['code']} };
        console.log(data);

        mn.get_ajax('/' + mn.site_lang + '/security/ajax/save_security_type', {data: JSON.stringify(data)},
        function (res) {
            console.log('save_security_call_back', res);

            if( res['action'] == 'refesh_page' ) location.reload();
            if (res['error'])
            {
                mn.security_module.loader.show(res['error'], 20000);

            }

            if (res['success'])
            {
                mn.security_module.loader.show(res['success'], 10000);
            }

            //location.reload();
        });
        return false;

    }

    function forcing_phone(res)
    {
        console.log('------------------start 1');
        console.log(res);
    }

    $(function(){
        if ( current_protect_type_show == 1 ) {
            mn.security_module
            .init()
            .show_window('set_security_type', 'one_pass')
            .done(forcing_one_pass_setup);        
        } else if (show_set_force_phone == 1 ) { //1 is true
            mn.security_module
            .init()
            .show_window('show_set_phone', 'show_set_phone')
            .done(forcing_phone);
            
        }
    });
</script>
</div>
<!-- Конец CONTENT-->
</div>
<!-- Конец WRAPPER-->
<!-- FOOTER -->

<div id="social_loginss" class="popup" style="float:none;">
    <div class="close"></div>
    <h2><?=_e('blocks/footer_1')?></h2>
    <center>
        <nav id="social-buttons" class="social-buttons" style="float:none;padding-top:10px;">

            <ul class="social-buttons_list" style="padding-bottom:10px;">
                <li class="social-buttons_item twitter">
                    <a href="#" class="social-twitter">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
                <li class="social-buttons_item odnoklassniki">
                    <a target="_blank" class="social-odnoklassniki" href="#">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
                <li class="social-buttons_item moikrug">
                    <a  href="#" class="social-mail_ru">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li> 
                <li class="social-buttons_item googleplus">
                    <a  href="#" class="social-google_plus">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
                <li class="social-buttons_item vk">
                    <a  href="#" class="social-vkontakte">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
                <li class="social-buttons_item fb">
                    <a href="#" class="social-facebook" style="cursor:pointer;">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
                <li class="social-buttons_item wt wt-login" id="wt-login">
                    <a href="#" class="social-webtransfer bn_wt_login_form" onclick="return false;" style="cursor:pointer;">
                        <span class="social-buttons_item_img"></span>
                    </a>
                </li>
            </ul>

        </nav></center>
</div>
<script type="text/javascript">

    $(function(){
        var time = $('#user-profile .datetime .time');

        if( time.length > 0 ){

            setInterval(function(){
                var val = time.html(),
                    time_ar = val.split(':'),
                    h = parseInt(time_ar[0]), m = parseInt(time_ar[1]);
                m++;
                if( m > 59 ){
                    m = 0;
                    h++;
                    if ( h > 23)
                        h = 0;
                }
                m = m+'';
                h = h+'';
                if( m.length == 1 ) m = '0'+m;
                if( h.length == 1 ) h = '0'+h;
                time.html( h+':'+m );
            }, 60*1000);
        }
    });

</script>
<footer id="footer">
    <section id="page-media" class="">
        <div class="page-wrapper">
		<?if($this->lang->lang()=='ru'){?>

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
            </div>
			<?}?>
        </div>
    </section>
    <div class="footer-help-wrapper" role="contentinfo">
        <nav class="footer-help-menu  wrapper">
            <div class="col4">
                <h3 class="footer-help-menu_h3"><?=_e('blocks/footer_3')?></h3>
                <ul class="col-list">
                    <li class="col-list_item">
                        <a href="<?=site_url('page/about')?>" target="_blank"><?=_e('blocks/footer_4')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/lend')?>" target="_blank"><?=_e('blocks/footer_5')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/borrow')?>" target="_blank"><?=_e('blocks/footer_6')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/partnership')?>" target="_blank"><?=_e('blocks/footer_7')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/about/questions')?>" target="_blank"><?=_e('blocks/footer_8')?></a>
                    </li>
                </ul>
            </div>
            <div class="col4">
                <h3 class="footer-help-menu_h3"><?=_e('blocks/footer_9')?></h3>
                <ul class="col-list">
                    <li class="col-list_item">
                        <a href="<?=site_url('page/about/terms')?>" target="_blank"><?=_e('blocks/footer_10')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/privacy-policy')?>" target="_blank"><?=_e('blocks/footer_11')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/terms-of-service')?>" target="_blank"><?=_e('blocks/footer_12')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('page/about/guarantee')?>" target="_blank"><?=_e('blocks/footer_13')?></a>
                    </li>
                    <!-- <li class="col-list_item">
                        <a href="<?=site_url('page/lend/redemption')?>" target="_blank"><?=_e('blocks/footer_14')?></a>
                    </li>-->
                </ul>
            </div>
            <div class="col4">
                <h3 class="footer-help-menu_h3"><?=_e('blocks/footer_15')?></h3>
                <ul class="col-list">
                    <li class="col-list_item">
                        <a href="http://webtransfer.london/articles/termsofuse" target="_blank"><?=_e('blocks/footer_16')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="http://webtransfer.london/articles/privacy" target="_blank"><?=_e('blocks/footer_17')?></a>
                    </li>
                    <li class="col-list_item">
                        <a href="<?=site_url('marketing')?>" target="_blank"><?=_e('Реклама на сайте')?></a>
                    </li>

                    <li class="col-list_item">
                        <a href="<?=site_url('video')?>" target="_blank"><?=_e('blocks/footer_19')?></a>
                    </li>
                    <?if($this->lang->lang()=='ru'){?>
                    <li class="col-list_item">
                        <a href="http://webtransfer-business.com" target="_blank"><?=_e('blocks/footer_20')?></a>
                    </li>
                    <?}?>
                </ul>
            </div>
            <div class="col4 social-buttons">
                <h3 class="footer-help-menu_h3"><?=_e('blocks/footer_21')?></h3>
                <ul class="social-buttons_list">
                    <li class="social-buttons_item twitter">
                        <a target="_blank" href="http://www.twitter.com/webtransfercom">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                   <!-- <li class="social-buttons_item odnoklassniki">
                        <a target="_blank" href="http://www.odnoklassniki.ru/group/51729362518158">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item moikrug">
                        <a target="_blank" href="http://my.mail.ru/community/webtransfer/">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li> -->
                    <li class="social-buttons_item googleplus">
                        <a target="_blank" href="https://plus.google.com/u/0/118412304892954038780/posts">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item vk">
                        <a target="_blank" href="http://vk.com/club55660968">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item fb">
                        <a target="_blank" href="http://www.facebook.com/webtransfer">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item yt">
                        <a target="_blank" href="https://www.youtube.com/channel/UCg3SLdMR7oGe2T_JWQWurQw">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                </ul>
                <div class="col-link-wrapper" style="height:55px;">
                    <a class="col-link" href="https://webtransfer.zendesk.com/hc/ru" target="_blank"><?=_e('home_old_support')?></a><br/>
                    <?if($this->lang->lang()=='ru'){?><a class="col-link" href="http://www.webtransfer.guru/online.html" style="margin-top:8px;display:inline-block" target="_blank"><?=_e('blocks/footer_23')?></a><?}?>
                </div>
            </div>
        </nav>
    </div>
    <div class="wrapper" role="contentinfo">
        <div class="about-us">
                <div class="about-us_info" style="padding:20px 0px 15px;">
                  
                    <a href="<?=site_url('page/webtransfer')?>" target="_blank" style="color:#fff;"><?=_e('Социальная кредитная сеть '.$GLOBALS["WHITELABEL_NAME"])?></a> <span style="font-size:11px;position:relative;top:-8px;color:#fff">&reg;</span>
                    <br />

                </div>
        </div>
    </div>
    <div class="credentials">
         <ul class="credentials_list" role="menubar" style="width:1250px !important;">
                <li class="credentials_list_item">
                    <a href="http://www.hmrc.gov.uk/ " target="_blank">
                        <img src="/img/hm-rc.jpg" alt="" style="height: 53px; margin-top: 4px;">
                    </a>
                </li>

                <li class="credentials_list_item">
                    <a href="http://www.mastercard.com/uk/merchant/en/security/what_can_do/SecureCode/index.html" target="_blank">
                        <img src="/img/master-card.jpg" alt="">
                    </a>
                </li>
				 <li class="credentials_list_item mc-sc">
                   <a href="http://www.mastercard.com/uk/merchant/en/security/what_can_do/SecureCode/index.html" target="_blank">
                        <img src="/img/mc-sc.jpg" alt="">
                    </a>


                </li>
				 <li class="credentials_list_item maestro">
                    <a href="http://www.visa.com" target="_blank">
                        <img src="/images/payways/visacard.png" alt="" style="width:105px;">
                    </a>
                </li>
                <li class="credentials_list_item">
                    <a href="http://www.visa.co.uk/products/protection-benefits/verified-by-visa/" target="_blank">
                        <img src="/img/visa.jpg" alt="">
                    </a>
                </li>


				<li class="credentials_list_item paypal">
                    <a href="http://payeer.com"><img src="/images/logopayeer.png" style="margin-top:20px;"></a>
                </li>
				 <li class="credentials_list_item wtgf">
                <a href="http://www.okpay.com" target="_blank">
                    <img src="/images/okpay2.png" alt="" style="margin-top: 6px;height:53px;">
                </a>
            </li>
                <li class="credentials_list_item ssl">
                    <img src="/img/rapid-ssl.gif" alt="" style="margin-top: 5px;"/>
                </li>
								 <li class="credentials_list_item alexa">
                    <a href="http://www.alexa.com/siteinfo/webtransfer.com" target="_blank">
                        <img src="images/alexa.png" alt="" style="margin-top: 6px;">
                    </a>
                </li>
                <li class="credentials_list_item wtgf">
                    <a href="<?=site_url('page/about/guarantee')?>" target="_blank">
                        <img src="/img/wtf-garantee.png" alt="" style="margin-top: 6px;">
                    </a>
                </li>

            </ul>
    </div>
    <div class="copyrights_wrapper account">
        <div class="copyrights">
            <div class="copyrights_text"><?=_e('blocks/footer_30')?></div>
            <div id="about-us_lang" class="upside-down">

            </div>
        </div>
    </div>
</footer>
<script data-cfasync='false' type="text/javascript" src="/js/slider-module.js"></script>
<script type="text/javascript">
    (function($){
        $('#slider .slides').htmSlider();
    })(jQuery);
</script>
<style type="text/css">
    #google_language_translator a {display: none !important; }
    .goog-te-gadget { font-size:0px !important; }
    .goog-tooltip {display: none !important;}
    .goog-tooltip:hover {display: none !important;}
    .goog-text-highlight {background-color: transparent !important; border: none !important; box-shadow: none !important;}
    #google_translate_element { margin-right:0px !important;}
    #google_translate_element .goog-logo-link{ display: none !important;}
</style>


<? add_universal_popup( 'text', 'close-p2p-value__title', 'close-p2p-value__body', 'close-p2p-value' ); ?>
<? add_universal_popup( 'text', 'social-integration-value__title', 'social-integration-value__body', 'social-integration-value' ); ?>
<? add_universal_popup( 'text', 'rating-stars-block-help__title', 'rating-stars-block-help__body', 'rating-stars-block-help' ); ?>
<? add_universal_popup( 'text', 'accaunt/popup_title_balans_38_2', 'accaunt/popup_body_balans_38_2', 'popup_top_up' ); ?>

<? add_universal_popup( 'text', 'help_status_title', 'help_status', 'currency_exchenge_help_status' ); ?>


<? show_universal_popups(); ?>

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-33029110-3']);
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

<img src="https://scounter.rambler.ru/top100.cnt?3136288" style="display: none" alt="Rambler's Top100" border="0" width="1" height="1"/>
<script  type="text/javascript"  src="/js/user/security_module.js?v=201603143"></script>
<link rel="stylesheet"  href="/css/user/security_module.css?v=20160121"></link>
<? // $this->load->view('user/accaunt/security_module/universal_window'); ?>
<?php
include 'reviews_block_small.php';
?>


<!-- wt-informer-viget -->
<div class="wt-informer-viget animsss">
    <div class="inforer-area"></div>
    <div class="wt-informer-viget-p-show animsss2">
<?=_e('Помогите нам!')?>
    </div>

    <script>
       $(function() {
         $(".wt-informer-viget").hover(function() {
             $(".wt-informer-viget").toggleClass("wt-informer-viget-full");            
              $(".wt-informer-viget-p-show").toggleClass("dispblk-class");    
           
         })
    }); 
    $(function() {
         $(".wt-informer-viget-p-show").click(function() {
             $(".abs-pd-viget").addClass("dispblk-class");                      
         })
    });
    
    $(function() {
         $(".close-wt-vg").click(function() {   
              $(".abs-pd-viget").removeClass("dispblk-class");
              $(".animsss").addClass("dispblk-class");                   
         })
    }); 
    </script>
    
<style>
    .animsss2{
        width: 200px !important;
        overflow: hidden;
    }
    
    .wt-informer-viget{
        overflow: hidden;
    }

    
    @-webkit-keyframes bounce {
    0%  { width:48px;
        height: 39px; }
    100%    { width:136px;
        height: 39px;
         }
    }
        
        .animsss:hover {
    -webkit-animation: bounce 1s ;
    }
    
    
    .icon-cs3 {
    background-position: -99px 5px !important;
}


.statistic_window {
    width: 176px !important;
}


.odd .left {
    font-size: 11px;
}



div#notice_box table{
	width: 100% !important;
}


div#credit_invest_details_window table {
    width: 100% !important;
}


.popup_window table {
    text-align: left;
    width: 100%;
}







#card_window_message div#modal-visa-card .modal-dialog.slick-slide.slick-current.slick-active {
    float: none !important;
    height: auto !important;
    width: 398px !important;
}


#card_window_message div#modal-visa-card {
    margin: 0 auto;
    display: block;
    width: auto;
    background: none!important;
    margin-top: 214px;
    box-shadow: none!important;
    position: fixed;
    left: 50% !important;
    right: 0!important;
    margin: 0 auto;
    display: block;
    float: none!important;
    width: 300px !important;
    margin-left: -232px!important;
    margin-top: 51px;
    height: 1px;
}

</style>




   <script type="text/javascript">
$(function() {
    $( ".popup_window" ).draggable({ helper: "original" });
  });
    </script> 


</div>
    
<div class="abs-pd-viget">
        <span class="close-wt-vg">×</span>
<?=_e('Нашли ошибку? Выделите текст, нажмите')?> <strong>Ctrl+Enter</strong>
</div>  
<!-- wt-informer-viget -->

</body>
</html>
