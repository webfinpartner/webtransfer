<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!isset($facebookConfig))
    $facebookConfig = config_item('facebookConfig');
if (!isset($vkontakteConfig))
    $vkontakteConfig = config_item('vkontakteConfig');
if (!isset($keywords))
    $keywords = '';
if (!isset($description))
    $description = '';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="<?= _e('lang') ?>" xmlns="http://www.w3.org/1999/xhtml" lang="<?= _e('lang') ?>">
<head>
    <script>
        var mn = {};
        mn.site_lang = "<?= $this->lang->lang() ?>";

        var userid = '<?php echo $this->user->id_user; ?>';
        document.cookie = "cc_data=" + userid;
        var site_url = "<?= site_url('/') ?>";
        var myip = '';

        var security = '<?= isset($security) ? $security : '' ?>';
        var page_hash = '<?= isset($page_hash) ? $page_hash : '' ?>';
    </script>
    <meta name="verify-admitad" content="da7eb9a412"/>
    <meta name="google-site-verification" content="jfQPhvGixQF0hU-L41d3aM7MFOs_Aoy4FmHCRHNn8kg"/>
    <title><?= (empty($title)) ? $GLOBALS["WHITELABEL_NAME"] : $GLOBALS["WHITELABEL_NAME"] . " - " . $title ?></title>
    <meta charset="utf-8"/>
    <base href="<?= base_url(); ?>"/>
    <? if (empty($cache_enable)) { ?>
        <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
        <meta http-equiv="Pragma" content="no-cache"/>
    <? } ?>

    <meta property="og:title" content="<?= _e('blocks/header_user_3') ?>"/>
    <meta property="og:description" content="<?= _e('blocks/header_user_4') ?>"/>
    <meta property="og:url" content="<?= site_url("?id_partner=" . $this->user->id_user) ?>"/>
    <meta property="og:image" content="http://webtransfer-finance.com/images/fb-banner.jpg"/>
    <link rel="image_src" href="http://webtransfer-finance.com/images/fb-banner.jpg"/>


    <meta name="keyword" content="<?= $keywords ?>" lang="<?= _e('lang') ?>"/>
    <meta name="google-translate-customization" content="d399f86dde9fae81-5dd97a3f7547b049-gf124a30d2f8cae1e-1b"></meta>
    <meta name="description" content="<?= $description ?>"/>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/user/styles1.css"/>
    <link rel="stylesheet" href="/css/user/fancybox.css"/>
    <link rel="stylesheet" href="/css/user/select.css"/>
    <link rel="stylesheet" href="/css/user/accaunt.css"/>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
    <script type="text/javascript" src="/msgs/src/js/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="/js/user/jquery.maskMoney.js"></script>
    <script type="text/javascript" src="/js/user/jquery-ui.js"></script>
    <script type="text/javascript" src="/js/user/select.js"></script>
    <script type="text/javascript" src="/js/user/fancybox.js"></script>
    <script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="/js/user/main.js"></script>
    <script type="text/javascript" src="/js/user/jquery.form.js"></script>
    <script type="text/javascript" src="/js/user/jquery.cookie.js"></script>
    <script type="text/javascript" src="https://l2.io/ip.js?var=myip"></script>
    <script type="text/javascript" src="/msgs/src/js/jQuery_UI_Touch_Punch_0.2.3.js"></script>

    <script type="text/javascript" src="/js/user/mistakes.js"></script>
    <link href="/css/user/mistakes.css" rel="stylesheet" type="text/css"/>

    <?php if (strpos(base_url(), 'wtest6') === false): ?>
        <script type="text/javascript" src="/msgs/src/tinymce/tinymce.min.js"></script>
    <?php endif; ?>

    <script type="text/javascript" src="/js/user/rus.js"></script>
    <script type="text/javascript" src="/js/user/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="/js/user/custom.js"></script>
    <script type="text/javascript" src="/js/lang/<?= _e('lang') ?>.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext'
          rel='stylesheet' type='text/css'/>
    <script type="text/javascript">var switchTo5x = true;</script>
    <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({
            publisher: "6d2a6d66-901d-4ef8-b8c3-29d06a8d3bf4",
            doNotHash: true,
            doNotCopy: true,
            hashAddressBar: false,
            shorten: false,
            lang: "ru"
        });</script>
    <script src="https://apis.google.com/js/plus.js"></script>

    <link rel="stylesheet" type="text/css" href="/css/simptip2.css"/>
    <style type="text/css">
        .goog-te-banner-frame {
            visibility: hidden !important;
        }

        body {
            top: 0px !important;
        }
    </style>
    <style type="text/css">
        .sms-content #code {
            width: 100px;
        }

        #google_language_translator a {
            display: none !important;
        }

        .goog-te-gadget {
            font-size: 0px !important;
        }

        .goog-tooltip {
            display: none !important;
        }

        .goog-tooltip:hover {
            display: none !important;
        }

        .goog-text-highlight {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        #google_translate_element {
            margin-right: 0px !important;
        }

        #google_translate_element .goog-logo-link {
            display: none !important;
        }

        .goog-te-gadget .goog-te-combo {
            width: 90% !important;
            margin: 4px 10px !important;
        }

        .button-help {
            padding: 0 25px;
            height: 48px;
            background: #FE7002;
            border-radius: 50px;
            position: fixed;
            margin-top: -1100px;
            display: block;
            right: 22px;
            bottom: 15px;
            z-index: 9999999;
        }

        .question-help {
            width: 25px;
            height: 25px;
            background: url(../images/question-help.png) 0 0 no-repeat;
            background-size: contain;
            display: inline-block;
            padding-top: 23px;
            margin-top: 10px;
            float: left;
        }

        .txt-help {
            color: #fff;
            font-weight: bold;
            padding-top: 12px;
            padding-left: 7px;
            float: left;
        }

        .button-help:hover {
            opacity: 0.9;
        }

        .all_reviews_link:hover {
            color: #FFF
        }

        .statistic_module {
            position: relative;
        }

        .stop {
            position: relative;
            z-index: 10;
        }

        aside1#div + div {
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

        aside1#div + div {
            background-color: none !important;
        }

        aside#left-side-fb > div {
            background-color: none !important;
            border-radius: 3px !important;
            padding: 0px;
        !important;
        }

        .swiper-container.nreviews-swiper-container3.swiper-container-horizontal {
            height: 605px;
        }

        .statistic_module {
            background: #fff;
        }

        aside#left-side-fb > div {
            background: rgb(234, 239, 244) !important;
        }

        .arbitrage_button_org {
            background: #ff5200 !important;
        }
    </style>

    <!-- Start Alexa Certify Javascript -->
    <script type="text/javascript">
        _atrk_opts = {atrk_acct: "9qQ7k1a0CM00oQ", domain: "webtransfer.com", dynamic: true};
        (function () {
            var as = document.createElement('script');
            as.type = 'text/javascript';
            as.async = true;
            as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js";
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(as, s);
        })();
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.cookie("IP", myip);
        });
    </script>
    <noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=9qQ7k1a0CM00oQ" style="display:none"
                   height="1" width="1" alt=""/></noscript>
    <!-- End Alexa Certify Javascript -->

    <link rel="stylesheet" href="/msgs/src/css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"/>
    <link rel='stylesheet' href='/msgs/src/css/font.css'/>
    <link rel="stylesheet" href="/msgs/src/css/jquery.mCustomScrollbar.css"/>
    <link rel="stylesheet" href="/msgs/src/css/nice-select.css"/>
</head>
<!--  -->
<!-- / -->
<body>


<div id="DraggableZone">
</div>
<div id="nwt-chat-im-modal-layerm" class="wt-chat-im main">
    <div class="wt-chat-im-header">
        <div class="wt-chat-im-ava-img" id="wt-chat-im-ava">
            <img src="https://webtransfer.com/assets/missing_avatar_thumb.jpeg" height="38" width="38" class="radius_50"
                 id="logo_Chat_user">

            <div class="wt-chat-im-status no-active-status"></div>
        </div>

        <div class="wt-chat-im-name" id="wt-chat-im-name">--------</div>

        <div class="wt-chat-im-buttons" style="width: 80px;">
            <div class="wt-chat-im-ban-btn  wt-chat-im-modal-showm4 hint-top-t-error hint-fade" simple-hint="<?= _e('Поместить в спам!') ?>" onclick="spamUser(true);"></div>
            <div class="wt-chat-im-close-btn  wt-chat-im-modal-showm3 hint-top-t-error hint-fade"  simple-hint="<?= _e('Закрыть') ?>" style="position: fixed;z-index: 999999999;margin-left: 60px;"></div>
            <div class="wt-chat-im-full-btn wt-chat-im-modal-showm2 hint-top-t-error hint-fade" simple-hint="<?= _e('Свернуть') ?>"></div>
        </div>

    </div>


    <div class="wrapper wt-chat-im">

        <div class="wt-chat-im contentmsg light mCustomScrollbar" id="mess">
            <center>
                <i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0px"
                   id="preloader_dialog"></i>
            </center>

        </div>
    </div>


    <div class="wt-chat-im-footer">
        <div style="height: 27px">
            <div class="wt-chat-im-write" id="WriteStatus"></div>
            <div class="wt-chat-im-icons-all">
                <div class="hint-top-t-error hint-fade wt-chat-im-icon-1"
                     simple-hint="<?= _e('Отправить деньги') ?>"></div>
                <div class="hint-top-t-error hint-fade wt-chat-im-icon-2"
                     simple-hint="<?= _e('Предложить кредит') ?>"></div>
            </div>
        </div>
                <span id="textArenaMCE">
                    <div class="mceText"></div>
                </span>
        <div id="wt-chat-im-modal-shown" class="wt-chat-im-textarea-icon2"></div>
        <div id="wt-chat-im-modal-show" class="wt-chat-im-textarea-icon1"></div>

    </div>

    <div id="wt-chat-im-modal-layer" style="display:none;" class="wt-chat-im-modal-select">
        <div class="wt-chat-im-modal-select-var1">Аудиозапись</div>
        <div class="wt-chat-im-modal-select-var2">Видеозапись</div>
        <div class="wt-chat-im-modal-select-var3">Документ</div>
        <div id="wt-chat-im-modal-show2"></div>
    </div>

    <div id="wt-chat-im-modal-layern" class="wt-chat-im-modal-smiles" style="display:none;">
        <div class="wt-chat-im-modal-smiles-padding">
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/asrcastic_hand.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/bigrin.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/blum3.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/blush.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/clapping.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/cray.gif"></div>


            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/crazy.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/dash2.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/diablo.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/fool.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/good.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/laugh1.gif"></div>
            <div class="wt-chat-im-smile1"><img src="https://webtransfer.com/assets/smiles/lazy.gif"></div>

        </div>
        <div class="wt-chat-im-modal-smiles-footer">

            <div class="wt-chat-im-smile-all">
                <div class="wt-chat-im-smile1 last"></div>
            </div>
            <div id="wt-chat-im-modal-shown2" class="wt-chat-im-smile"></div>
        </div>
    </div>
    <div id="wt-chat-im-modal-layern3" class="wt-chat-im-modal-sendMoney"
         style="display:none;color: #5D5D5D;text-align: left;">
        <center style="font-weight: 700;font-size: 12px;margin-top: -7px;"><?= _e('Отправить деньги') ?></center>
        <div id="sender_card" style="margin-top: 7px;"></div>
        <div id="recipient_card" style="margin-top: 40px;"></div>
        <div id="sumSendPay_chat" style="margin-top: 40px;"></div>
        <div id="codeAccount" style="margin-top: 10px;"></div>
        <center id="sendMoney" style="margin-top: 10px;"></center>
    </div>

    <div id="wt-chat-im-modal-layern_credit" class="wt-chat-im-modal-sendMoney"
         style="display:none;color: #5D5D5D;text-align: left;">
        <center style="font-weight: 700;font-size: 12px;margin-top: -7px;"><?= _e('Кредит') ?></center>
        <div id="credit_contrent" style="margin-top: 7px;"></div>
        <center id="credit_btn_center" style="margin-top: 10px;"></center>
    </div>

    <div id="wt-chat-im-modal-layern4" class="wt-chat-im-modal-sendMoney"
         style="display:none;color: #5D5D5D;text-align: left;">
        <center style="font-weight: 700;font-size: 13px;"><?= _e('Профиль') ?>
            <div id="profile_layern"></div>
        </center>
    </div>
</div>
<div id="PostsGeneralChat" class="wt-chat-o main">
    <div class="wt-chat-o-header">
        <div class="wt-chat-im-ava-img">
            <img src="https://webtransfer.com/mainpage/images/logowt.png" height="38" width="67"
                 class="radius_50">
        </div>
        <div class="wt-chat-o-name"><?= _e('Общий чат') ?></div>
        <div class="wt-chat-im-buttons">
            <div class="wt-chat-im-close-btn closeGeneralChat_btn"></div>
        </div>
    </div>


    <div class="wrapper wt-chat-o">
        <div class="wt-chat-im content_generalChat light mCustomScrollbar" id="blockPostsGeneralChat">
            <center>
                <i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0"></i></center>
        </div>
    </div>


    <div class="wt-chat-o-footer">
        <div class="edText" contenteditable='true'></div>
        <a class="btnsend_o">
            <i class="fa fa-paper-plane"></i>
        </a>
    </div>
</div>
<div id="nwt-chat-im-modal-layerm_1" class="wt-chat-im main wt-user-list">
    <div class="wt-chat-im-header">


        <div class="wt-chat-im-name wt-chat-im-name_1" id="wt-chat-im-name1"><i
                class="fa fa-users"></i> <?= _e('Мои друзья') ?>
            (<span id="CountFr">-</span>)
        </div>

        <div class="wt-chat-im-buttons">
            <div class="wt-chat-im-full-btn wt-chat-im-modal-showm3_1"></div>
        </div>

    </div>


    <div class="wrapper wrapper_1 wt-chat-im">

        <div class="wt-chat-im contentmsg content_1 light mCustomScrollbar">

            <!--<i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0px" id="preloader_user"></i>-->
        </div>
    </div>

</div>
<div class="wt-chat-im-users-area">

    <div simple-hint="<?= _e('Общий чат') ?>" class="hint-left-t-error hint-fade right-arrow">
        <div class="o_chat">
            <i class="fa fa-comments-o fa-3x"></i>
        </div>
    </div>
    <!--<div class="dragH" style="margin: 5px -4px 10px;height: 1px;padding: 0 4px;"></div>-->
    <div class="sc_listUser">
        <div id="listDialog">
            <i class="fa fa-refresh fa-3x fa-spin loadDialoglist" style="color: #969696;padding: 10px 0px"></i>
        </div>
    </div>
    <!--<div class="dragH" style="margin: 3px -4px 3px;height: 1px;padding: 0 4px;"></div>-->
    <!---->
    <a href="https://webtransfer.com/social/profile/93930?lang=<?= _e('lang') ?>" target="_blank">
        <div simple-hint="<?= _e('Поддержка') ?>" class="hint-left-t-info hint-fade right-arrow">
            <div class="btnask">
                <i class="fa fa-question-circle fa-3x"></i>
            </div>
        </div>
    </a>
    <div simple-hint="<?= _e('Мои друзья') ?>" class="hint-left-t-error hint-fade right-arrow"
         style="text-align: center;" data-action="OpenListUser">
        <div class="all-users-info">
            <span id="newDialog_count"></span>
            <i class="fa fa-users"></i>
        </div>
    </div>


</div>

<?php if (strpos(base_url(), 'wtest6') === false): ?>
    <script type="text/javascript" src="/msgs/src/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="/msgs/src/js/jquery.nice-select.js"></script>
    <script type="text/javascript" src="/msgs/src/js/jquery.label_better.js"></script>
    <script type="text/javascript" src="/msgs/src/js/moment.min.js"></script>
    <script type="text/javascript" src="/msgs/src/js/chat.js?version=1.0.0"></script>
<!--    <script type="text/javascript" src="/msgs/src/js/webSocket.js"></script>-->
<?php endif; ?>

<? $this->load->view('user/blocks/system_messages'); ?>
<div id="fb-root"></div>
<script type="text/javascript">
    window.fbAsyncInit = function () {
        //Initiallize the facebook using the facebook javascript sdk
        FB.init({
            appId: '<?= $facebookConfig['id']; ?>', // App ID
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
</script>
<script type="text/javascript">
    if (window.self != window.top) {
        window.top.location.href = window.location.href;
    }
</script>
<link href="/css/user/odnoklassniki.css" rel="stylesheet"/>
<script async src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>

<!-- /Yandex.Metrika counter -->
<!-- Шапка -->
<? $this->load->view('user/blocks/banner_' . $banner_menu); ?>
<!-- WRAPPER-->


<!-- <div class="message error"><? if ($this->lang->lang() == 'ru') { ?>Уважаемые участники, в связи с техническими работами возможны проблемы с работой сайта.  Приносим извинения за неудобства!<? } else { ?>
                     Dear members, due to the scheduled maintenance works there might be some problems in accessing the website. Sorry for inconvinience!<? } ?></div>
<div class="message error"><? if ($this->lang->lang() == 'ru') { ?>Уважаемые пользователи, с 5.00 AM по Гринвичу, Р2Р-биржа будет закрыта для проведения планового обновления системы. Пожалуйста, завершите активные сделки и воздержитесь от заключения новых до окончания обновления.<? } else { ?>Dear users, from 5.00 AM GMT, P2P exchange will be closed for scheduled system update. Please, complete matced orders  and refrain from new deals until the upgrade is complete.<? } ?></div>-->


<?php

function detect_current_page($request)
{
    if (strpos($_SERVER['REQUEST_URI'], $request) != false) {
        return true;
    }
    return false;
}

;
include 'reviews_block_big.php';
?>
<div id="wrapper">
    <? /* message_check($message);
              if(!empty($message)):  ?>
              <div class="" id="notice_box" style="display: block;top:20%;">
              <div class="message <?= $message['class'] ?>"><?= $message['send'] ?></div>
              </div>
              <? endif */ ?>

    <aside id="left-side-fb">

        <div id="aside1">
            <?= $left_side ?>


        </div>


    </aside>

    <aside id="right-side-fb">


        <? $this->load->view('/user/accaunt/blocks/renderRightSocialBlock') ?>
		
		<?	$cur_url = uri_string();
    $cur_url = preg_replace("#/news_(?:[0-9]+)#", "", $cur_url);
    $cur_url = urldecode($cur_url);
    $cur_url = explode("/", $cur_url);
    $cur_url = @array_pop($cur_url);
	if ($cur_url == 'stabfund') {?>
	<div id="mc-poll"></div>
	
	<div id="mc-poll"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Poll', id: 43883, pollId: <? if ($this->lang->lang() == 'ru') { ?>1907<? } else { ?>  1908 <? } ?>});
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
</style>
<br>
<?php }; ?>
		
		
		<? if ($this->lang->lang() == 'en'){ ?></b><? } ?>



        <? if (!empty($parentUser) and (int)$parentUser->id_user) { ?>
            <div class="parent-info">
                <div style="border: 1px solid rgb(204, 204, 204); margin-bottom: 10px; padding: 10px;">
                    <div
                        style="display: inline-block; vertical-align: top; width: 60px; float: left;margin-bottom:12px;"
                        class="user-profile_img_wrapper">
                        <img width="50" style="border-radius:30px" src="<?= $accaunt_header['parent_photo_50'] ?>"
                             alt="User image" pagespeed_url_hash="4195711572">
                    </div>
                    <div
                        style="display: block; font-weight: 500; margin-bottom: 5px; text-align: left; color: rgb(54, 154, 152);">
                        <?= $parentUser->name . ' ' . $parentUser->sername ?> <br/>
                        <span style="font-size:12px;font-weight:400"><A
                                href="<?= site_url('account/change_partner') ?>"><?= _e('blocks/header_user_7') ?></a></span>
                    </div>
                            <span style="margin-top:5px">
                                <a href="mailto:<?= $parentUser->email ?>"/><img width="16"
                                                                                 src="/images/icons/em.png"/></a>
                                <?
                                $soc_icon = array(
                                    "vkontakte" => "/images/icons/vk.png",
                                    "mail_ru" => "/images/icons/mr.png",
                                    "facebook" => "/images/icons/fb.png",
                                    "twitter" => "/images/icons/tw.png",
                                    "odnoklassniki" => "/images/icons/ok.png",
                                    "google_plus" => "/images/icons/gp.png",
                                    "link_edin" => "/images/icons/in.png"
                                );

                                if ($accaunt_header['parent_social']) {
                                    foreach ($accaunt_header['parent_social'] as $social) {
                                        echo "<a href='" . $social->url . "' target='_blank'>"
                                            . "<img width='16' src='" . $soc_icon[$social->name] . "' />"
                                            . "</a>" . PHP_EOL;
                                    }
                                }
                                ?>
                                <? if (!empty($parentUser->skype)) { ?>
                                    <a href="skype:<?= $parentUser->skype ?>?chat">
                                        <img width="16" src="/images/icons/sp.png">
                                    </a>
                                <? } ?>

                                <? $social_id = get_social_id($parentUser->id_user);
                                if (!empty($social_id)) {
                                    ?>
                                    <a href="https://webtransfer.com/social/profile/<?= $social_id ?>?lang=<?= _e('lang') ?>">
                                        <img width="16" src="/images/icons/wt_social.png">
                                    </a>
                                <? } ?>

                                <a class="table_green_button" href="#"
                                   onclick="openDialog('<?= $parentUser->id_user ?>');
                                       return false;"
                                   style="text-align: center; margin: 6px 0 0 50px;"><?= _e('blocks/header_user_9') ?></a>
                            </span>
                </div>
            </div>
        <? } else { ?>
            <div class="parent-info">
                <div style="border: 1px solid rgb(204, 204, 204); margin-bottom: 10px; padding: 10px;">
                    <div
                        style="display: inline-block; vertical-align: top; width: 60px; float: left;margin-bottom:12px;"
                        class="user-profile_img_wrapper">
                        <img width="50" style="border-radius:30px"
                             src="https://s3-eu-west-1.amazonaws.com/production-webtransfer/users/avatar1s/17/cropped/e1d0896fa67d991efc5714ba2b476c8130d5a230.jpg"
                             alt="User image">
                    </div>
                    <div
                        style="display: block; font-weight: 500; margin-bottom: 5px; text-align: left; color: rgb(54, 154, 152);">
                        <A href="<?= site_url('account/change_partner') ?>"><?= _e('Добавить старшего партнера') ?></a>
                        <br/>
                        <span style="font-size:12px;font-weight:400"><A
                                href="<?= site_url('account/change_partner') ?>"><?= _e('Ваш главный консультант') ?></a></span>
                    </div>

                </div>
            </div>
        <? } ?>

        <div
            style="border: 1px solid rgb(204, 204, 204); padding:9px;margin-bottom: 10px; background-color: #ff5200; text-align:center">
            <a href="<?= site_url('partner/invite') ?>"
               style="color:#ffffff;font-size: 13px;"><?= _e('blocks/header_user_6') ?></a>
        </div>
        <? if ($this->lang->lang() == 'ru') { ?>
           
        <? } else { ?>
            <a href="http://camelot24.com/news.html?iID=1" target="_blank"><img src="img/cam-eng.jpg"></a>
        <? } ?>
        <div style="">

            <div style="">
                <center>
                    <a href="<?= $create_card_url ?>">
                        <img src="/images/card-2.jpg" border="0" style="width:250px;">
                        <?= _e('Получить карту') ?>
                    </a>
            </div>

            <a href="<?= $create_card_url ?>"></a>

            <br/>
        </div>
        <? /* if($this->lang->lang()=='ru' ){ */ ?>
        <iframe id="frame-fb"
                src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?= $GLOBALS["WHITELABEL_NAME"] ?>&amp;width=250&amp;height=650&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true"
                scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:650px;"
                allowTransparency="true"></iframe>
        <? /* } else { ?>
                  <div class="fb-like-box" data-href="https://www.facebook.com/webtransfer" data-width="250" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
                  <a href="https://twitter.com/webtransfercom" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @webtransfercom</a>
                  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                  <? } */ ?>
    </aside>
    <? if ($this->lang->lang() == 'en'){ ?></b><? } ?>
    <!-- CONTENT-->
    <div id="content">

        <?
        if (isset($secondary_menu) && !empty($secondary_menu))
            $this->load->view('user/blocks/secondary_' . $secondary_menu);
        ?>
        <?
        message_check($message);
        if (!empty($message)):
            ?>
            <div class="popup_window" id="notice_box" style="display: block;top:20%; width: 400px;">
                <div class="message <?= $message['class'] ?>"><?= $message['send'] ?></div>
                <?php ?>
                <center><?= _e('blocks/header_user_12') ?><br/>
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <div class="fb-like-box" data-href="https://www.facebook.com/webtransfer"
                                     data-width="150" data-colorscheme="light" data-show-faces="false"
                                     data-header="true" data-stream="false" data-show-border="true"></div>
                            </td>
                            <td width="50%">
                                <script async type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

                                <!-- VK Widget -->
                                <div id="vk_groups"></div>
                                <script type="text/javascript">
                                    VK.Widgets.Group("vk_groups", {
                                        mode: 1,
                                        width: "180",
                                        height: "200",
                                        color1: 'FFFFFF',
                                        color2: '2B587A',
                                        color3: '5B7FA6'
                                    }, 55660968);
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="https://twitter.com/webtransfercom"><img
                                        src="/images/twitter-read_<?= _e('lang') ?>.png"></a>

                            </td>
                            <td>
                                <script async type="text/javascript" src="//yastatic.net/share/share.js"
                                        charset="utf-8"></script>
                                <div class="yashare-auto-init" data-yashareL10n="<?= _e('lang') ?>"
                                     data-yashareType="large" data-yashareQuickServices="odnoklassniki,moimir,gplus"
                                     data-yashareTheme="counter"></div>
                            </td>

                        </tr>
                    </table>
                    <br/>

                    <a href="" onclick='$("#notice_box").hide();
                    return false;' style="text-decoration:underline"><?= _e('blocks/header_user_13') ?></a>
                </center>
                <?php ?>
            </div>
        <? endif ?>
        <?
        if (!empty($arbitration_message))
            echo '<div class="message arbitration">' . _e('blocks/header_user_10') . ' <span id="arbitration-timer" data-time="' . $arbitration_message['time'] . '">' . $arbitration_message['time'] . '</span> ' . _e('blocks/header_user_11') . '
                <img  src="/images/w128h1281338911586cross.png" id="close_message" onclick="$(this).parent().fadeOut()"></div>';
        ?>

        <? if ($is_need_view_dna_extra_dialog) { ?>
            <? $this->load->view('user/accaunt/blocks/renderVisualDNA_ExtraData_window', compact($this->user->id_user)); ?>
        <? } ?>

        <!--div style="margin-top:5px; padding-left:15px;"><em><b><a class="tooltipz" href="/">Нашли ошибку в тексте сайта?<span class="custom help"><img width="48" height="48" alt="помощь" src="/templates/_default_/images/helpz.png" /><em>Нашли ошибку?</em>Выделите ошибочный текст мышкой и нажмите <b>Ctrl</b> + <b>Enter</b></span></a></b></em></div-->
