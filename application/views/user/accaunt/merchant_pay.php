<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE HTML>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
        <link href="/css/user/odnoklassniki.css" rel="stylesheet"/>
        <link href="/css/style.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/css/user/styles1.css" />
        <link rel="stylesheet" href="/css/user/accaunt.css" />
        <link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="/css/user/security_module.css" type="text/css" media="screen" />
        <script type="text/javascript" src="/js/user/jquery.js"></script>
        <title>"<?=$GLOBALS["WHITELABEL_NAME"] ?> - <?=base64_decode($shop->title)?>"</title>
        <script  type="text/javascript"  src="/js/lang/<?=_e('lang')?>.js"></script>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic&subset=latin,cyrillic-ext);

            h1{
                margin: 18.76px 0;
            }
            html {
                background-color: #F5F5F5;

            }
            body
            {
                display: block;
                padding: 0px;
                margin: 0px;
                font-family: "Open Sans","Arial","Helvetica",sans-serif;
                background-color: #F5F5F5;
                font-size: 14px;
                line-height: 17px;
                font-weight: 300;
                color: #38383e;
            }
            .block {
            border: 1px solid #ddd;
            margin: 20px auto;
            background-color: #ffffff;
            width:800px;
            }
            .merchant-logo {
            float:right;
            position:relative;
            display:inline-block;
            }
            .logo {
            position: relative;
            display:inline-block;
            }

            .table .rower {
              height: auto;
              padding: 10px;
              min-height: 15px;
              font-size: 16px;
              overflow: auto;
            }
            .table .td1 {
                width: 150px !important;
                float: left;
            }
            .table .bg {
              background-color: #eee;
            }
            .table .td1 {
              width: 40%;
              float: left;
            }
            .table .td2 {
              text-align: right;
              float: right;
            }
            .table .half:last-child{
                padding-right: 0;
            }
            .button {
             outline: none;
             display: block;
             border: medium none;
             width: 250px;
             height: 44px;
             font: 300 24px/24px 'Open Sans';
			 font-family: "Open Sans","Arial","Helvetica",sans-serif;
             color: #FFF;
             background: none repeat scroll 0% 0% #7FBFBF;
             cursor: pointer;
             border-radius: 25px;
             outline: none;
             margin: 14px auto 5px;
            }
            #social-buttons, .social-buttons {
              padding: 30px 0;
            }

            #buttons{
                display: block;
                margin: 10px 0 20px;
                overflow: auto;
            }
            #buttons .button1{
                float: right;

            }
            .formRow{
                overflow: auto;

            }
            .formRow .formRight{
                padding-top: 9px;
                width: 100% !important;
            }
            .small{
                font-size: 14px;
                line-height: 14px;
                color: #999;
                margin-bottom: 7px;
                width: 200px;
            }
            .error {
                width: auto;
                padding:10px;
                background-color: #ff5200;
                color: #ffffff;
                font-weight:bold;
            }
            .sms-content input{
                width: 100px;
            }
        </style>
    </head>
    <body>
        <div class="block">
        <div class="header" style="border-bottom:1px solid #ddd;text-align:left;">
        <div class="logo">
            <img src="https://webtransfer.com/img/logo.png" style="height:60px; margin:10px 20px;">
        </div>
        <div class="merchant-logo">
            <img style="height:60px; margin:10px 20px;" src="/upload/images/merchant/<?=(isset($shop->image)) ? md5($shop->shop_id) . "." . $shop->image : "no.png"; ?>" />
        </div>
        </div>
        <div class="" style="padding:10px 20px;text-align:left;">
        <div style="width:100%;text-align:right;">
        <?=base64_decode($shop->url)?><br/>
        <?=_e('Тел')?>: +<?=base64_decode($shop->tel)?><br/>
        <a href="mailto: <?=base64_decode($shop->email)?>"> <?=base64_decode($shop->email)?></a><br/>
        </div>
        <div style="width:100%;text-align:left;">
        <h1><?=_e('Счет на оплату № ')?><?=$vars->order_id; ?></h1>
        <?=_e('Вам выставлен счет на оплату от ')?><a href="<?=base64_decode($shop->url)?>"><?=base64_decode($shop->title)?></a>
        </div>
        <br/><br/>
        <div class="table">
        <div class="tbody">
            <? if($id_user){?>
                <div class="rower bg"><div class="td1"><?=_e('Плательщик')?>:</div><div class="td2"><?=$user_name?> (<?=$id_user?>) </div></div>
                <div class="rower"><div class="td1"><?=_e('Получатель')?>:</div><div class="td2"><a href="<?=base64_decode($shop->url)?>"><?=base64_decode($shop->title)?></a> (<?=$shop->user_id?>) </div></div>

            <? }else{?>
            <div class="rower bg"><div class="td1"><?=_e('Получатель')?>:</div><div class="td2"><a href="<?=base64_decode($shop->url)?>"><?=base64_decode($shop->title)?></a> (<?=$shop->user_id?>) </div></div>
            <?}?>
            <div class="rower bg"><div class="td1"><?=_e('Описание')?>:</div><div class="td2"><?=$vars->description?></div></div>
            <div class="rower"><div class="td1"><?=_e('Сумма на оплату')?>:</div><div class="td2"><?=sprintf($vars->summ_view, (string)price_format_double($vars->amount))?></div></div>
            <?php if ($shop->commission == 1){?><div class="rower bg"><div class="td1"><?=_e('Комиссия ')?>(0.5%):</div><div class="td2"><?=sprintf($vars->summ_view, (string)price_format_double($vars->commission))?></div></div><?}?>
            <div class="rower" style="border-top:2px solid #38383e;font-size:24px;font-weight:bold;"><div class="td1"><?=_e('Всего')?>:</div><div class="td2"><?=sprintf($vars->summ_view, (string)price_format_double($vars->summary))?></div></div>
            </div>
        </div>
        <?if(!$id_user){?>
            <script src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>
            <script>
                $(function() {
                    $(document).on('click', '.social-odnoklassniki', function() {
                        ODKL.Oauth2(this, '1152937216', 'VALUABLE ACCESS', '<?= base_url() ?>login/odnoklassniki');
                        return false;
                    });
                    $(document).on('click', '.social-vkontakte', function(event) {
                        //window.location = 'http://webtransfer-test.co.uk/?action=redirect_to_vk&key=cI1u6W2$VW';
                        window.location = "http://oauth.vk.com/authorize?client_id=4352044&scope=notify,status,friends,wall,photos,questions&display=popup&v=5.2&redirect_uri=<?= urldecode(base_url("/login/vkontakte")) ?>&response_type=code";
                        return false;
                    });
                    $(document).on('click', '.social-mail_ru', function() {
                        window.location = "https://connect.mail.ru/oauth/authorize?client_id=737165&response_type=code&redirect_uri=<?= urldecode(base_url() . 'login/mail_ru') ?>";
                        return false;
                    });
                    $(document).on('click', '.social-google_plus', function() {
                        window.location = "<?= google_plus(true) ?>";
                        return false;
                    });
                    $(document).on('click', '.social-twitter', function() {
                        window.location = "/login/twitter";
                        return false;
                    });
                    $(document).on('click', '.social-partner', function() {
                        $.cookie('social-partner', '1', {path: '/'});
                    });
                });
            </script>
            <script  data-cfasync='true' src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>
            <!--  start facebook -->
            <div id="fb-root"></div>
            <script type="text/javascript">
                window.fbAsyncInit = function() {
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
                (function(d) {
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
                $(function() {
                    $('.social-facebook').click(function(e) {											FB.login(function(response) {
                                    if (response.authResponse) {
                                            parent.location = '<?= base_url() ?>login/facebook'; //redirect uri after closing the facebook popup
                                    }
                            }, {scope: 'email,publish_actions,publish_stream,user_birthday,user_location,user_work_history,user_hometown,user_photos'}); //permissions for facebook
                    });
                });
            </script>
            <script type="text/javascript">
                var site_url = "<?=site_url('/')?>";
            </script>
            <center>
                <nav id="social-buttons" class="social-buttons" >
                    <div class="social-buttons_title"><?=_e('Для того чтобы оплатить необходимо войти')?></div>
                    <ul class="social-buttons_list">
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
                        <li class="social-buttons_item wt" id="wt-login">
                            <a href="#" class="social-webtransfer bn_wt_login_form" onclick="return false;" style="cursor:pointer;">
                                <span class="social-buttons_item_img"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
               <? $this->load->view('user/blocks/loginForm_window.php'); ?>
            </center>
        <?} else if($isCanPay){?>
            <?=form_open(NULL); ?>
                <?=form_hidden('shop_id', $shop->shop_slash); ?>
                <?=form_hidden('amount', $vars->amount); ?>
                <?=form_hidden('purpose', ''); ?>
                <?=form_hidden('code', ''); ?>
                <?=form_hidden('order_id', $vars->order_id); ?>
                <?=form_hidden('description', $vars->description); ?>
                <?=form_hidden('other', $vars->other); ?>
                <?=form_hidden('step', 2); ?>
                <?=form_hidden('hash', $vars->hash); ?>
                <?=form_hidden('csrf', $vars->csrf); ?>
                <?=form_hidden('currency', $vars->currency); ?>

                <input type="checkbox" name="agriment" value="true"/> <?=sprintf(_e('Согласен с %sусловиями предоставления услуг%s'),'<a href="https://webtransfer-finance.com/" target="new">','</a>')?>.               <br/><Br/>
                    <? if(isset($_POST['step']) && !isset($_POST['agriment'])){?><center><div class="error"><?=_e('Для оплаты данного счета Вы должны согласиться с условиями предоставления услуг')?></div></center><?}?>
                    <? if(isset($_POST['step']) && $security_res){?><center><div class="error"><?=getSmsError($security_res)?></div></center><?}?>
                <center><button class="button" type="submit" id="out_send_payout" name="submit"><?=_e('Оплатить')?></button></center><br/><br/>


            <?=form_close(); ?>
            <div id="container"></div>
            <script>
                var site_url = "<?=site_url('/')?>";


                $('#out_send_payout').click(function(){
                    if( $(this).hasClass('submit') ) return true;
                    else {
//                            $('#out_send_window').data('call-back',standart_calc).show('slow');
                        mn.security_module
                            .init()
                            .show_window('merchant_pay')
                            .done(function(res){
                                var code = res['code'];
                                if( res['res'] != 'success' ) return false;

                                mn.security_module.loader.show();
                                $('[name="code"]').val( code );
                                $('[name="purpose"]').val( 'merchant_pay' );
                                $('#out_send_payout').addClass('submit').trigger("click");
                            });
                        return false;
                    }

                    return false;
                });
            </script>

        <?} else {?>
            <center><div class="error"><?=_e('Сейчас вы не можете оплатить данный счет.')?> <?=$error; ?>. <?=_e('По вопросам обращаться на ')?><a href="mailto:merchant@webtransfer.com">merchant@webtransfer.com</a></div></center>
        <?}?>
        </div>
        <div class="footer" style="background-color:#38383e;text-align:center;padding:10px;color:#ddd;font-size:12px;clear:both;">
        &copy; <?=$GLOBALS["WHITELABEL_NAME"] ?> 2006-<?=date("Y")?> <?=_e('Все права зашищены')?>
        </div>

        </div>

        <? $this->load->view('user/accaunt/security_module/loader');?>
        <script>
            var mn = {};
                mn.site_lang = "<?= $this->lang->lang() ?>";
        </script>
        <script type="text/javascript" src="/js/user/security_module.js"></script>
    </body>
</html>