<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
if(!isset($facebookConfig))
    $facebookConfig  = config_item('facebookConfig');
if(!isset($vkontakteConfig))
    $vkontakteConfig = config_item('vkontakteConfig');
if(!isset($keywords))
    $keywords        = '';
if(!isset($description))
    $description     = '';
    $cackle_user_info = get_cackle_user_info();
	$cur_url = uri_string();
	$cur_url = preg_replace("#/news_(?:[0-9]+)#", "", $cur_url);
	$cur_url = urldecode($cur_url);
	$cur_url = explode("/", $cur_url);
	$cur_url = @array_pop($cur_url);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="<?= _e('lang') ?>" xmlns="http://www.w3.org/1999/xhtml" lang="<?= _e('lang') ?>">
    <head>
        <meta name="globalsign-domain-verification" content="D_j0skh7gHkFIlAfIzhCdG-a2s06djlHg4BcqQOMlU" />
        <style type="text/css">
            .goog-te-banner-frame{visibility:hidden !important;}
            body {top:0px !important;}
            #google_language_translator a {display: none !important; }
            .goog-te-gadget { font-size:0px !important; }
            .goog-tooltip {display: none !important;}
            .goog-tooltip:hover {display: none !important;}
            .goog-text-highlight {background-color: transparent !important; border: none !important; box-shadow: none !important;}
            #google_translate_element { margin-right:0px !important;}
            #google_translate_element .goog-logo-link{ display: none !important;}
            .goog-te-gadget .goog-te-combo{ width: 90% !important;margin:4px 10px !important;}
            .all_reviews_link:hover {
                color:#FFF
            }
            .statistic_module {
                position: relative;
            }
            .stop {
                position: relative;
                z-index: 10;
            }

            aside1#div + div{
                background-color: none !important;
            }
            .sticky {
                position: fixed;
                z-index: 10;
                margin-top: 64px;
                background: none !important;
            }
            .narrow #wrapper {
                margin-top: 115px;
                height: auto;
                clear: both;
                overflow: hidden;
                padding-bottom: 0px;
            }

            aside1#div + div{
                background-color: none !important;
            }

            aside#left-side-fb>div {
                background-color: none !important;
                border-radius: 3px !important;
                padding: 0px; !important;
            }

            .swiper-container.nreviews-swiper-container3.swiper-container-horizontal {
                height: 605px;
            }
            .statistic_module {
                background: #fff;
            }

            aside#left-side-fb>div {
                background: rgb(234, 239, 244) !important;
            }
            .arbitrage_button_org {
                background: #ff5200 !important;
            }
            .nreviews-modal-body .nreviews-otz-full {
                text-align: justify !important;
            }
        </style>
        <script type="text/javascript">
            var mn = {};
            mn.site_lang = "<?= $this->lang->lang() ?>";

            var site_url = "<?= site_url('/') ?>";
            var myip = '';
            var security = '<?= isset($security) ? $security : '' ?>';
            var page_hash = '<?= isset($page_hash) ? $page_hash : '' ?>';

        </script>

        <meta name="wot-verification" content="ec8c5f4b3a24c1b74626"/>
        <meta name="google-site-verification" content="jfQPhvGixQF0hU-L41d3aM7MFOs_Aoy4FmHCRHNn8kg" />
        <meta name="verify-admitad" content="da7eb9a412" />
        <title><?= (empty($title)) ? $GLOBALS["WHITELABEL_NAME"] : $GLOBALS["WHITELABEL_NAME"]." - ".$title ?></title>
        <meta charset="utf-8" />
        <base href="<?= base_url(); ?>"  />
        <? if(empty($cache_enable)){ ?>
            <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
            <meta http-equiv="Pragma" content="no-cache"/>
        <? } ?>

        <meta property="og:title" content="<?= _e('blocks/header_3') ?>" />
        <meta property="og:description" content="<?= _e('blocks/header_4') ?>" />

        <? if(isset($this->user)): ?>
            <meta property="og:url" content="<?= site_url("?id_partner=".$this->user->id_user) ?>"/>
        <? endif; ?>
        <meta property="og:image" content="http://webtransfer-finance.com/images/fb-banner.jpg" />
        <link rel="image_src" href="http://webtransfer-finance.com/images/fb-banner.jpg" />

        <meta name="keyword"   content="<?= $keywords ?>,ec8c5f4b3a24c1b74626"  lang="<?= _e('lang') ?>" />
        <meta name="google-translate-customization" content="d399f86dde9fae81-5dd97a3f7547b049-gf124a30d2f8cae1e-1b"></meta>
        <meta name="description" content="<?= $description ?>" />
        <meta name="w1-verification" content="179842778302" />
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="https://webtransfer-finance.com/css/user/styles1.css" />
        <link rel="stylesheet" href="/css/user/accaunt.css" />
        <!--<link rel="stylesheet" href="https://webtransfer-finance.com/css/user/accaunt.css" />-->
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery.maskMoney.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery-ui.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/fancybox.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/main.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery.form.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery.cookie.js"></script>
        <script type="text/javascript" src="https://l2.io/ip.js?var=myip"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/rus.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/jquery.validationEngine.js"></script>
        <script  type="text/javascript"  src="https://webtransfer-finance.com/js/user/custom.js"></script>
        <script  type="text/javascript"  src="https://webtransfer-finance.com/js/lang/<?= _e('lang') ?>.js"></script>
        <script type="text/javascript" src="https://webtransfer-finance.com/js/user/mistakes.js"></script>
        <link href="https://webtransfer-finance.com/css/user/mistakes.css" rel="stylesheet" type="text/css" />
        
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'/>

        <!-- Start Alexa Certify Javascript -->
        <script type="text/javascript">
        _atrk_opts = {atrk_acct: "9qQ7k1a0CM00oQ", domain: "<?= $_SERVER["HTTP_HOST"]; ?>", dynamic: true};
        (function () {
            var as = document.createElement('script');
            as.type = 'text/javascript';
            as.async = true;
            as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js";
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(as, s);
        })();
        </script>
        <script type="text/javascript" >
            $(document).ready( function () {
                $.cookie("IP", myip);
            });
        </script>
        <noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none" height="1" width="1" alt="" /></noscript>
        <!-- End Alexa Certify Javascript -->


        <!-- Start of  Zendesk Widget script -->
        <script>/*<![CDATA[*/window.zEmbed || function (e, t) {
                var n, o, d, i, s, a = [], r = document.createElement("iframe");
                window.zEmbed = function () {
                    a.push(arguments)
                }, window.zE = window.zE || window.zEmbed, r.src = "javascript:false", r.title = "", r.role = "presentation", (r.frameElement || r).style.cssText = "display: none", d = document.getElementsByTagName("script"), d = d[d.length - 1], d.parentNode.insertBefore(r, d), i = r.contentWindow, s = i.document;
                try {
                    o = s
                } catch (c) {
                    n = document.domain, r.src = 'javascript:var d=document.open();d.domain="' + n + '";void(0);', o = s
                }
                o.open()._l = function () {
                    var o = this.createElement("script");
                    n && (this.domain = n), o.id = "js-iframe-async", o.src = e, this.t = +new Date, this.zendeskHost = t, this.zEQueue = a, this.body.appendChild(o)
                }, o.write('<body onload="document._l();">'), o.close()
            }("//assets.zendesk.com/embeddable_framework/main.js", "webtransfer.zendesk.com<?= (($this->lang->lang() == 'ru') ? "" : "/hc/en-us") ?>");/*]]>*/</script>
        <!-- End of  Zendesk Widget script -->
    </head>


    
    
    <body>
        <? $this->load->view('user/blocks/system_messages'); ?>
        <script type="text/javascript">
            if (window.self != window.top) {
                window.top.location.href = window.location.href;
            }
        </script>
        <div id="fb-root"></div>
        <script>
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=318186948264815";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <link href="/css/user/odnoklassniki.css" rel="stylesheet">
            <script src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>

            <? $this->load->view('user/blocks/banner_'.$banner_menu); ?>
            <!-- WRAPPER-->
            <? /* if($this->lang->lang()=='ru'){ */ ?>
            <?php

            function detect_current_page($request){
                if(strpos($_SERVER['REQUEST_URI'], $request) != false){
                    return true;
                }
                return false;
            };


            include 'reviews_block_big.php';
            ?>
            <div id="wrapper">
                <aside id="left-side-fb" style="width: <? if ($cur_url == 'stabfund') { ?>230px !important <? }; ?>;">
                    <div style="border: 1px solid rgb(204, 204, 204);width: <?	
						if ($cur_url == 'stabfund') { 
						?>210px<? } else { ?> 171px <? }; ?>;
						margin-bottom: 7px; background-color: #ff5200 !important; padding: 10px;text-align:center;height:32px;">
                        <a href="<?= site_url('account/arbitrage_credit') ?>" style='color:#ffffff;'><?= _e('blocks/header_user_5') ?></a></div>
                    <div id="aside1">
					<?	
						if ($cur_url == 'stabfund') { 
						?>
						<div id="mc-poll"></div>

<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Poll', id: <?=config_item('cackle_site_id')?>, pollId: <? if ($this->lang->lang() == 'ru') { ?>1939<? } else { ?>1940<? } ?>, <? if ($this->lang->lang() != 'ru') { ?> lang: 'en', <? }  ?> ssoAuth: '<?=$cackle_user_info?>'});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>


						<br>
						
						
						<?
						//echo($left_side) ;
						} else {  ?>
                        <?= $left_side  ?>

					<?php } ; ?>
                    </div>
                </aside>
                <aside id="right-side-fb">
                    <? $this->load->view('/user/accaunt/blocks/renderRightSocialBlock') ;
					
						
						if ($cur_url == 'stabfund') {?>
						<!--<div id="mc-poll-4"></div>
							<script type="text/javascript">
							cackle_widget = window.cackle_widget || [];
							cackle_widget.push({widget: 'Poll', id: <?=config_item('cackle_site_id')?>, pollId: <? if ($this->lang->lang() == 'ru') { ?>1907<? } else { ?>  1908 <? } ?>, container: 'mc-poll-4'<? if ($this->lang->lang() != 'ru') { ?>, lang: 'en' <? }  ?>, ssoAuth: "<?=$cackle_user_info?>"});
							(function() {
								var mc = document.createElement('script');
								mc.type = 'text/javascript';
								mc.async = true;
								mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
							})();
							</script>
							<br>-->
                            
                            
						
						
							<div id="mc-poll-1"></div>
							<script type="text/javascript">
							cackle_widget = window.cackle_widget || [];
							cackle_widget.push({widget: 'Poll', id: <?=config_item('cackle_site_id')?>, pollId: <? if ($this->lang->lang() == 'ru') { ?> 1938 <? } else { ?>  1937 <? } ?>, container: 'mc-poll-1'<? if ($this->lang->lang() != 'ru') { ?>, lang: 'en' <? }  ?>, ssoAuth: '<?=$cackle_user_info?>'});
							(function() {
								var mc = document.createElement('script');
								mc.type = 'text/javascript';
								mc.async = true;
								mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
							})();
							</script>
							<style type="text/css">
							.mcp-votes {
								width: 40px  !important;
								overflow: hidden  !important;
								height: 20px !important;
							}
							.mcp-percent {
								 width: 40px  !important;

							}
							#mc-poll a.mcp-vote.mcp-btn.mcp-btn-small {
    display: none !important;
}
							</style>
							<br>
							
							<div id="mc-poll-2"></div>
							<script type="text/javascript">
							cackle_widget = window.cackle_widget || [];
							cackle_widget.push({widget: 'Poll', id: <?=config_item('cackle_site_id')?>, pollId: <? if ($this->lang->lang() == 'ru') { ?> 1936 <? } else { ?>  1935 <? } ?>, container: 'mc-poll-2'<? if ($this->lang->lang() != 'ru') { ?>, lang: 'en' <? }  ?>, ssoAuth: '<?=$cackle_user_info?>'});
							(function() {
								var mc = document.createElement('script');
								mc.type = 'text/javascript';
								mc.async = true;
								mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
							})();
							</script>
							<br>
							<?= $left_side  ?>
							<script type="text/javascript">
							 /*window.onload = function() {
								var lang = '';
								<? if ($this->lang->lang() == 'ru') { ?>
								lang = 'ru';
								<? } else { ?>
								lang = 'en';<? } ?>
								}
								 
								//Конфиг
								var jsonStringMcPoll1 = "{"id": "mc-poll-1", "ru_topic": "Как вам идея Стабфонда", "en_topic": "How do you like the Stabilization Find idea?", "items": [ { "ru": "Супер", "en": "Super" }, {"ru": "Нейтрально", "en": "I don`t care" }, {"ru": "Не нравится", "en": "I don`t like it" }, {"ru": "Голосовать", "en": "Vote" }]}";

								var jsonStringMcPoll2 = "{"id": "mc-poll-2", "ru_topic": "Нравится ли вам то, что Комитет Стабфонда разделен на исполнительный и Наблюдательный советы?", "en_topic": "Do you like the idea that Stabilization Find Committee is delived into Executive and Supervisory boards?", "items": [ { "ru": "Да очень нравится", "en": "Yes, i like it very much" }, {"ru": "Вообще не нравится", "en": "I don`t like " }, {"ru": "Не обращал внимания", "en": "Did not pay attention" }, {"ru": "Лично я - против!", "en": "I personally against" }, {"ru": "Пусть как будет", "en": "Let a be" }, {"ru": "Голосовать", "en": "Vote" }]}";

								var jsonStringMcPoll = "{"id": "mc-poll", "ru_title": "Избирательная коммиссия", "en_title": "Electoral Commission", "ru_topic": "Я голосую за:", "en_topic": "I vote for:"}";

								locale(jsonStringMcPoll1);
								locale(jsonStringMcPoll2);
								locale(jsonStringMcPoll);

								function locale( jsonString ){
								 var locale = JSON.parse ( jsonString );
								 if(lang == 'ru'){
								  $(locale.id + ' .mcp-title').text(locale.ru_title);
								  $(locale.id + ' .mcp-vote mcp-btn mcp-btn-small').text(locale.ru_title);
								  
								  if(locale.ru_topic != undefined){
								   $(locale.id + ' .mcp-topic-text').text(locale.ru_topic);
								  }
								 }else{
								  $(locale.id + ' .mcp-title').text(locale.en_title);
								  $(locale.id + ' .mcp-vote mcp-btn mcp-btn-small').text(locale.en_title);
								  
								  if(locale.en_topic != undefined){
								   $(locale.id + ' .mcp-topic-text').text(locale.en_topic);
								  }
								 }
								 if(locale.contents != undefined){
								 var i = 0;
								  $(locale.id + ' .mcp-option-text').each(function(i,elem) { 
								   if(lang == 'ru'){
									$(elem).text(locale.contents[i].ru);
								   }else{
									$(elem).text(locale.contents[i].en);
								   }
								  i = i++;
								  });
								 }
								}
									*/							}
							 </script>
						<?php /*	<iframe id="frame-fb" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?= $GLOBALS["WHITELABEL_NAME"] ?>&amp;width=250&amp;height=650&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:650px;" allowTransparency="true"></iframe> */ ?>	
							
							
							
					<?php } else {  ?>
					
					<? if($this->lang->lang() == 'en'){ ?></b><? } ?>
                    <div style="border: 1px solid rgb(204, 204, 204); padding:9px;margin-bottom: 10px; background-color: #ff5200; text-align:center">
                        <a href="<?= site_url('account/invite') ?>" style="color:#ffffff;font-size: 13px;" class="bonusnow" onclick="return false"><?= _e('blocks/header_user_6') ?></a>
                    </div>

                    <iframe id="frame-fb" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?= $GLOBALS["WHITELABEL_NAME"] ?>&amp;width=250&amp;height=650&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:650px;" allowTransparency="true"></iframe>
					<?php }; ?>	
					
                </aside>
                <!-- CONTENT--><? if($this->lang->lang() == 'en'){ ?></b><? } ?>
                <div id="content"    <? if ($cur_url == 'stabfund') { ?> style=" width: 700px; margin-left: 240px;" <? } ?>>

                    <?
                    if(isset($secondary_menu) && !empty($secondary_menu)){
                        $this->load->view('user/blocks/secondary_'.$secondary_menu);
                    }

                    message_check($message);
                    if(!empty($message))
                        echo "<div class='message {$message["class"]}'>{$message["send"]}".
                        "<img  src='/images/w128h1281338911586cross.png' id='close_message' onclick='$(this).parent().fadeOut()'></div>";
                    ?>
