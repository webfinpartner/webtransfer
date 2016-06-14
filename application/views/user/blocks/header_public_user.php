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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="<?= _e('lang') ?>" xmlns="http://www.w3.org/1999/xhtml" lang="<?= _e('lang') ?>">
    <head>
        <script>
            var userid = '<?php echo $this->user->id_user; ?>';
            document.cookie = "cc_data=" + userid;
            var site_url = "<?= site_url('/') ?>";
            var myip = '';
            var security = '<?= isset($security) ? $security : '' ?>';
            var page_hash = '<?= isset($page_hash) ? $page_hash : '' ?>';
        </script>
        <link type="text/css" rel="stylesheet" media="all" href="/chat/cometchatcss.php" />
        <script type="text/javascript" src="/chat/cometchatjs.php" charset="utf-8"></script>
        <meta name="verify-admitad" content="da7eb9a412" />
        <meta name="verify-admitad" content="da7eb9a412" />
        <meta name="google-site-verification" content="jfQPhvGixQF0hU-L41d3aM7MFOs_Aoy4FmHCRHNn8kg" />
        <title><?= (empty($title)) ? $GLOBALS["WHITELABEL_NAME"] : $GLOBALS["WHITELABEL_NAME"]." - ".$title ?></title>
        <meta charset="utf-8" />
        <base href="<?= base_url(); ?>"  />
        <? if(empty($cache_enable)){ ?>
            <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
            <meta http-equiv="Pragma" content="no-cache"/>
        <? } ?>
        <? if(isset($_GET['id_partner'])){ ?>
            <meta property="og:title" content="<?= _e('blocks/header_user_3') ?>" />
            <meta property="og:description" content="<?= _e('blocks/header_user_4') ?>" />
            <meta property="og:url" content="https://<?= base_url_shot() ?>/?id_partner=<?= $_GET['id_partner'] ?>" />
            <meta property="og:image" content="http://webtransfer-finance.com/images/fb-banner.jpg" />
            <link rel="image_src" href="http://webtransfer-finance.com/images/fb-banner.jpg" />
        <? } ?>

        <meta name="keyword"   content="<?= $keywords ?>"  lang="<?= _e('lang') ?>" />
        <meta name="google-translate-customization" content="d399f86dde9fae81-5dd97a3f7547b049-gf124a30d2f8cae1e-1b"></meta>
        <meta name="description" content="<?= $description ?>" />
        <meta name="w1-verification" content="179842778302" />
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="/css/user/styles1.css" />
        <link rel="stylesheet" href="/css/user/fancybox.css" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/css/user/select.css" />
        <link rel="stylesheet" href="/css/user/accaunt.css" />
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
        <script type="text/javascript" src="/js/user/jquery.js"></script>
        <script type="text/javascript" src="/js/user/jquery.maskMoney.js"></script>
        <script type="text/javascript" src="/js/user/jquery-ui.js"></script>
        <script type="text/javascript" src="/js/user/select.js"></script>
        <script type="text/javascript" src="/js/user/fancybox.js"></script>
        <script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="/js/user/main.js"></script>
        <script type="text/javascript" src="/js/user/jquery.form.js"></script>
        <script type="text/javascript" src="/js/user/jquery.cookie.js"></script>
        <script type="text/javascript" src="https://l2.io/ip.js?var=myip"></script>
        <script type="text/javascript" src="/js/user/rus.js"></script>
        <script type="text/javascript" src="/js/user/jquery.validationEngine.js"></script>
        <script  type="text/javascript"  src="/js/user/custom.js"></script>
        <script  type="text/javascript"  src="/js/lang/<?= _e('lang') ?>.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'/>
        <script type="text/javascript">var switchTo5x = true;</script>
        <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "6d2a6d66-901d-4ef8-b8c3-29d06a8d3bf4", doNotHash: true, doNotCopy: true, hashAddressBar: false, shorten: false, lang: "<?= _e('lang') ?>"});</script>
        <script src="https://apis.google.com/js/plus.js"></script>
        <link href="http://stg.odnoklassniki.ru/share/odkl_share.css" rel="stylesheet"/>
        <script src="http://stg.odnoklassniki.ru/share/odkl_share.js" type="text/javascript" ></script>
        <style type="text/css">
            .goog-te-banner-frame{visibility:hidden !important;}
            body {top:0px !important;}
        </style>
        <style type="text/css">
            .sms-content #code {width:100px;}
            #google_language_translator a {display: none !important; }
            .goog-te-gadget { font-size:0px !important; }
            .goog-tooltip {display: none !important;}
            .goog-tooltip:hover {display: none !important;}
            .goog-text-highlight {background-color: transparent !important; border: none !important; box-shadow: none !important;}
            #google_translate_element { margin-right:0px !important;}
            #google_translate_element .goog-logo-link{ display: none !important;}
        </style>
        <!-- Start Alexa Certify Javascript -->
        <script type="text/javascript">
        _atrk_opts = {atrk_acct: "9qQ7k1a0CM00oQ", domain: "webtransfer-finance.com", dynamic: true};
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
        <!-- Start of <?= $GLOBALS["WHITELABEL_NAME"] ?> Zendesk Widget script -->
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
        <!-- End of <?= $GLOBALS["WHITELABEL_NAME"] ?> Zendesk Widget script -->
    </head>
    <!--  -->
    <!-- / -->
    <body>
        <? $this->load->view('user/blocks/system_messages'); ?>
        <script type="text/javascript">
            if (window.self != window.top) {
                window.top.location.href = window.location.href;
            }
        </script>

        <link href="/css/user/odnoklassniki.css" rel="stylesheet">
            <script src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>
            <script>
            $(function () {
                $(document).on('click', '.social-odnoklassniki', function () {
                    yaCounter23161861.reachGoal('odnoklassniki');
                    ODKL.Oauth2(this, '198322944', 'VALUABLE ACCESS', '<?= base_url() ?>login/odnoklassniki');
                    return false;
                });
                $(document).on('click', '.social-vkontakte', function (event) {
                    yaCounter23161861.reachGoal('facebook');
                    //window.location = 'http://webtransfer-test.co.uk/?action=redirect_to_vk&key=cI1u6W2$VW';
                    window.location = "http://oauth.vk.com/authorize?client_id=3926553&scope=notify,status,friends,wall,photos,questions&display=popup&v=5.2&redirect_uri=<?= urldecode(base_url("/login/vkontakte")) ?>&response_type=code";
                    return false;
                });
                $(document).on('click', '.social-mail_ru', function () {
                    yaCounter23161861.reachGoal('facebook');
                    window.location = "https://connect.mail.ru/oauth/authorize?client_id=713966&response_type=code&redirect_uri=<?= urldecode(base_url().'login/mail_ru') ?>";
                    return false;
                });
                $(document).on('click', '.social-google_plus', function () {
                    yaCounter23161861.reachGoal('facebook');
                    window.location = "<?= google_plus(true) ?>";
                    return false;
                });
                $(document).on('click', '.social-twitter', function () {
                    yaCounter23161861.reachGoal('facebook');
                    window.location = "/login/twitter";
                    return false;
                });
                $(document).on('click', '.social-partner', function () {
                    $.cookie('social-partner', '1', {path: '/'});
                });
            });
            </script>
            <!--  start facebook -->
            <div id="fb-root"></div>
            <script type="text/javascript">
                window.fbAsyncInit = function () {
                    //Initiallize the facebook using the facebook javascript sdk
                    FB.init({
                        appId: '<?php echo config_item('appID'); ?>', // App ID
                        cookie: true, // enable cookies to allow the server to access the session
                        status: true, // check login status
                        xfbml: true, // parse XFBML
                        oauth: true //enable Oauth
                    });
                };
                //Read the baseurl from the config.php file
                (function (d) {
                    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement('script');
                    js.id = id;
                    js.async = true;
                    js.src = "//connect.facebook.net/en_US/all.js";
                    ref.parentNode.insertBefore(js, ref);
                }(document));
                //Onclick for fb login
                $(function () {
                    $('.social-facebook').click(function (e) {
                        yaCounter23161861.reachGoal('facebook');
                        FB.login(function (response) {
                            if (response.authResponse) {
                                parent.location = '<?= base_url() ?>login/facebook'; //redirect uri after closing the facebook popup
                            }
                        }, {scope: 'email,publish_actions,publish_stream,user_birthday,user_location,user_work_history,user_hometown,user_photos'}); //permissions for facebook
                    });
                });
            </script>
            <!--  end facebook -->
            <!-- /Yandex.Metrika counter -->
            <!-- Шапка -->
            <? $this->load->view('user/blocks/banner_'.$banner_menu); ?>
            <!-- WRAPPER-->
            <!-- <div class="message error">Уважаемые участники, в связи с техническими работами доступ к сайту будет ограничен с 21:00 по 22:00 по Гринвичу. Спасибо!</br>
           Dear members, due to the scheduled maintenance the access to the web-site will be limited from 21:00 to 22:00 Greenwich time.</div> -->
            <div class="message error">Уважаемые пользователи, с 5.00 AM по Гринвичу, Р2Р-биржа будет закрыта для проведения планового обновления системы. Пожалуйста, завершите активные сделки и воздержитесь от заключения новых до окончания обновления.</br>
Dear users, from 5.00 AM GMT, P2P exchange will be closed for scheduled system update. Please, complete matced orders  and refrain from new deals until the upgrade is complete.</div>

            <? if(isset($need_vdna_extra_dialog) && !empty($need_vdna_extra_dialog)){ ?>
                <? $this->load->view('user/accaunt/blocks/renderVisualDNA_extra'); ?>
            <? } ?>


            <div id="wrapper">
                <aside id="left-side-fb" style="width:193px;">
                    <div style="width:193px;border-bottom:10px solid #ff5200;">
                        <i class="fa fa-circle" style="font-size:16px;color:green;position:absolute;left:5px;top:5px"></i>
                        <img src="/images/profile/profimage.jpg" style="width:100%">
                    </div>
                    <div style="padding: 10px 0;text-align: left;"><h1 class="title"><?= $user_data->name ?> <?= $user_data->sername ?> <a href="#" onclick="jqcc.cometchat.chatWith('<?= $user_data->id_user ?>');return false;">
                                <i  class="fa fa-comment"></i>
                            </a></h1>
                        <span style="font-size:12px;line-height:30px"><?= _e('Киев, Украина') ?></span>
                    </div>

                    <br/>
                    <style>.zifra{font-size:32px;line-height:30px;color:rgb(99,124,151);}.odd{padding:10px 5px;margin-bottom:5px;}.left{float:left;font-size:12px;}.right{float:right;font-size:12px;}</style>
                    <div style="width:191px;border: 1px solid rgb(204, 204, 204);padding-bottom:10px;">
                        <div style="padding:10px;font-weight:bold;text-align:center;"><?= _e('Моя активность') ?></div>
                        <div class="odd">
                            <span class="left"><?= _e('Дата регистрации') ?></span>
                            <span class="right">12.01.2015</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Дата в сети') ?></span>
                            <span class="right">12.03.2015</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Верифицирован') ?></span>
                            <span class="right"><?= _e('Проголосовать') ?>Да</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Активные вклады') ?></span>
                            <span class="right"><?= _e('Есть') ?>Есть</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Активные займы') ?></span>
                            <span class="right"><?= _e('Нет') ?></span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Просроченные займы') ?></span>
                            <span class="right"><?= _e('Есть') ?></span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Пунктуальность') ?></span>
                            <span class="right">95%</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Рейтинг в системе') ?></span>
                            <span class="right">9</span>
                        </div>
                        <div class="odd">
                            <span class="left"><?= _e('Партнеры в системе') ?></span>
                            <span class="right">1,349</span>
                        </div>
                    </div><br/>
                    <a href=""><img src="/images/icons/vk.png"></a>
                    <a href=""><img src="/images/icons/fb.png"></a>
                    <a href=""><img src="/images/icons/ok.png"></a>
                    <a href=""><img src="/images/icons/tw.png"></a>
                    <a href=""><img src="/images/icons/gp.png"></a>
                    <a href=""><img src="/images/icons/mr.png"></a>

                </aside>
                <aside id="right-side-fb">
                    <div style="border: 1px solid #; padding:9px;margin-bottom: 10px; background-color: #ff5200; text-align:center">
                        <a href="<?= site_url('account/invite') ?>" style="color:#ffffff;font-size: 13px;"><?= _e('blocks/header_user_6') ?></a>
                    </div>
                    <? if(!empty($parentUser) and (int) $parentUser->id_user){ ?>
                        <div class="parent-info">
                            <div style="border: 1px solid rgb(204, 204, 204); margin-bottom: 10px; padding: 10px;">
                                <div style="display: inline-block; vertical-align: top; width: 60px; float: left;margin-bottom:12px;" class="user-profile_img_wrapper">
                                    <img width="50" style="border-radius:30px" src="<?= $accaunt_header['parent_photo_50'] ?>" alt="User image" pagespeed_url_hash="4195711572">
                                </div>
                                <div style="display: block; font-weight: 500; margin-bottom: 5px; text-align: left; color: rgb(54, 154, 152);">
                                    <?= $parentUser->name.' '.$parentUser->sername ?> <br />
                                    <span style="font-size:12px;font-weight:400"><?= _e('blocks/header_user_7') ?></span>
                                </div>
                                <span style="margin-top:5px">
                                    <a href="mailto:<?= $parentUser->email ?>" /><img width="16" src="/images/icons/em.png" /></a>
                                    <?
                                    $soc_icon = array(
                                        "vkontakte"     => "/images/icons/vk.png",
                                        "mail_ru"       => "/images/icons/mr.png",
                                        "facebook"      => "/images/icons/fb.png",
                                        "twitter"       => "/images/icons/tw.png",
                                        "odnoklassniki" => "/images/icons/ok.png",
                                        "google_plus"   => "/images/icons/gp.png",
                                        "link_edin"     => "/images/icons/in.png"
                                    );

                                    if($accaunt_header['parent_social']){
                                        foreach($accaunt_header['parent_social'] as $social){
                                            echo "<a href='".$social->url."' target='_blank'>"
                                            ."<img width='16' src='".$soc_icon[$social->name]."' />"
                                            ."</a>".PHP_EOL;
                                        }
                                    }
                                    ?>
                                    <? if(!empty($parentUser->skype)){ ?>
                                        <a href="skype:<?= $parentUser->skype ?>?chat">
                                            <img width="16" src="/images/icons/sp.png">
                                        </a>
                                    <? } ?>
                                    <a href="#" onclick="jqcc.cometchat.chatWith('<?= $parentUser->id_user ?>');
                                                return false;">
                                        <img width="16" src="/images/icons/chat_icon.png">
                                    </a>
                                    <a class="qestion table_green_button" href="#popup" style="text-align: center; margin: 6px 0 0 50px;"><?= _e('blocks/header_user_9') ?></a>
                                </span>
                            </div>
                        </div>
                    <? } ?>
                    <!--chat chaild window-->
                    <? $this->load->view('user/blocks/chatChield_window'); ?>
                    <!--/chat-->
                    <iframe id="frame-fb" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?= $GLOBALS["WHITELABEL_NAME"] ?>&amp;width=250&amp;height=650&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:650px;" allowTransparency="true"></iframe>
                </aside>
                <!-- CONTENT-->
                <div id="content">

                    <?
                    if(isset($secondary_menu) && !empty($secondary_menu)){
                        $this->load->view('user/blocks/secondary_'.$secondary_menu);
                    }
                    message_check($message);
                    if(!empty($message)):
                        ?>
                        <div class="" id="notice_box" style="display: block;top:20%;">
                            <div class="message <?= $message['class'] ?>"><?= $message['send'] ?></div>
                            <?php /* ?>
                              <center><?=_e('blocks/header_user_12')?><br/>
                              <table width="100%">
                              <tr><td width="50%">
                              <div class="fb-like-box" data-href="https://www.facebook.com/webtransfer" data-width="150" data-colorscheme="light" data-show-faces="false" data-header="true" data-stream="false" data-show-border="true"></div>
                              </td><td width="50%">
                              <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

                              <!-- VK Widget -->
                              <div id="vk_groups"></div>
                              <script type="text/javascript">
                              VK.Widgets.Group("vk_groups", {mode: 1, width: "180", height: "200", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 55660968);
                              </script>
                              </td>
                              </tr>
                              <tr>
                              <td>
                              <a href="https://twitter.com/webtransfercom"><img src="/images/twitter-read.png"></a>

                              </td>
                              <td>
                              <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="large" data-yashareQuickServices="odnoklassniki,moimir,gplus" data-yashareTheme="counter"></div>
                              </td>

                              </tr>
                              </table><br/>

                              <a href="" onclick='$("#notice_box").hide();return false;' style="text-decoration:underline"><?=_e('blocks/header_user_13')?></a>
                              </center>

                              <?php */ ?>
                        </div>
                    <? endif ?>
                    <?
                    if(!empty($arbitration_message))
                        echo '<div class="message arbitration">'._e('blocks/header_user_10').' <span id="arbitration-timer" data-time="'.$arbitration_message['time'].'">'.$arbitration_message['time'].'</span> '._e('blocks/header_user_11').'
                <img  src="/images/w128h1281338911586cross.png" id="close_message" onclick="$(this).parent().fadeOut()"></div>';
                    ?>
