<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
    <meta charset="utf-8"/>

    <meta property="og:title" content="<?= _e('blocks/header_user_3') ?>"/>
    <meta property="og:description" content="<?= _e('blocks/header_user_4') ?>"/>
    <meta property="og:url" content="<?= site_url("?id_partner=" . $this->user->id_user) ?>"/>
    <meta property="og:image" content="http://webtransfer-finance.com/images/fb-banner.jpg"/>
    <link rel="image_src" href="http://webtransfer-finance.com/images/fb-banner.jpg"/>
    <link rel="stylesheet" href="/css/user/security_module.css?v=20160121">


    <meta name="keyword" content="<?= $keywords ?>" lang="<?= _e('lang') ?>"/>
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



    <link rel="stylesheet" href="/msgs/src/css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"/>
    <link rel='stylesheet' href='/msgs/src/css/font.css'/>
    <link rel="stylesheet" href="/msgs/src/css/jquery.mCustomScrollbar.css"/>
    <link rel="stylesheet" href="/msgs/src/css/nice-select.css"/>
</head>

<body>

<?
$message_check_off = false;

if(!empty($_SESSION['p2p_redirect_url'])) {
    $p2p_redirect_url = $_SESSION['p2p_redirect_url'];
    $_SESSION['p2p_redirect_url'] = '';

    // Если выполняется редирект фрейма то на этой странице нужно отключить проверку message что бы на следующей все сработало.

//    $message_check_off = true;
    ?>
    <script>


//        window.parent.location.href ='<?//=$p2p_redirect_url; ?>//';
//        window.location.href =' <?//=$p2p_redirect_url; ?>//';
//        window.location.href ='https://webtransfer.com/ru/account/currency_exchange/iframe_my_sell_list';
    </script>

    <?
//    die();
}

if($message_check_off === false) {
    message_check($message);
}

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