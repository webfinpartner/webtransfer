<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($facebookConfig)) $facebookConfig = config_item('facebookConfig');
if(!isset($vkontakteConfig)) $vkontakteConfig = config_item('vkontakteConfig');
?>
<link href="https://stg.odnoklassniki.ru/share/odkl_share.css" rel="stylesheet">
<script data-cfasync='true' src="https://stg.odnoklassniki.ru/share/odkl_share.js" type="text/javascript"></script>
<script>
    $(function() {
//                console.log('bind all buttons');
        if('undefined' != typeof soc) return;
        console.log('soc');
        // Get Number of Facebook Shares
        $.getJSON('https://graph.facebook.com/https://www.facebook.com/188476411189271',
                function(data) {
                    $('.page-social_counters-bar_social-counter .fb .value').html(data.likes);
                    $('.list-counters').animate({'opacity': '1'}, 300);
                });

//                console.log('bind 1');
        $(document).on('click','.social-odnoklassniki',function() {
            try{yaCounter23161861.reachGoal('odnoklassniki');}catch(a){console.log(a);}
            if( ODKL === undefined )
            {
                console.log('ODKL fail');
                return FALSE;
            }

            ODKL.Oauth2(this, '1152937216', 'VALUABLE ACCESS', '<?= base_url() ?>login/odnoklassniki');
            return false;
        });
//                console.log('bind 2');
        $(document).on('click','.social-vkontakte',function(event) {
            try{yaCounter23161861.reachGoal('vkontakte');}catch(a){console.log(a);}
            //window.location = 'http://webtransfer-test.co.uk/?action=redirect_to_vk&key=cI1u6W2$VW';
            window.location = "http://oauth.vk.com/authorize?client_id=<?= $vkontakteConfig['id']; ?>&scope=notify,status,friends,wall,photos,questions&display=popup&v=5.2&redirect_uri=<?= base_url() ?>login/vkontakte&response_type=code";
            return false;
        });
//                console.log('bind 3');
        $(document).on('click','.social-mail_ru',function() {
            try{yaCounter23161861.reachGoal('mail-ru');}catch(a){console.log(a);}
            window.location = "https://connect.mail.ru/oauth/authorize?client_id=737165&response_type=code&redirect_uri=<?= urldecode(base_url() . 'login/mail_ru') ?>";
            return false;
        });
//                console.log('bind 4');
        $(document).on('click','.social-google_plus',function() {
            try{yaCounter23161861.reachGoal('google');}catch(a){console.log(a);}
            window.location = "<?= google_plus(true) ?>";
            return false;
        });
//                console.log('bind 5');
        $(document).on('click','.social-twitter',function() {
            console.log('click tw');
            try{yaCounter23161861.reachGoal('twitter');}catch(a){console.log(a);}
            window.location = "/login/twitter";
            return false;
        });
        
        $(document).on('click','.social-facebook',function() {
            console.log('click fb');
            try{yaCounter23161861.reachGoal('facebook');}catch(a){console.log(a);}
            
            window.location = "https://www.facebook.com/dialog/oauth?client_id=<?= $facebookConfig['id']; ?>&scope=email,publish_actions,user_birthday,user_location,user_work_history,user_hometown,user_photos&redirect_uri=<?=site_url('/login/facebook2')?>&response_type=code";
            return false;
        });        
        

        <?
               $renrenConfig = config_item('renrenConfig');
               require (APPPATH . 'libraries/renren/renrenlib.php');
               Renren_lib::setConfig($renrenConfig);
        ?>

        $(document).on('click','.social-renren',function() {
            try{yaCounter23161861.reachGoal('renren');}catch(a){console.log(a);}
            window.location = "<?=Renren_lib::getAuthLink()?>";
            return false;
        });

        $(document).on('click','.social-partner',function() {
            $.cookie('social-partner', '1', {path: '/'});
        });
        soc = true;
    });
</script>
<script type="text/javascript">

    function checkForm()
    {
//        console.log($('#form-login').val(), $('#form-pass').val());
        if( $('#form-login').val() !== '' && $('#form-pass').val() !== '')
            $('#form-submit').removeAttr('disabled');
        else
            $('#form-submit').attr("disabled", true);
    }

    $(function() {
    $("#login-form-popup").submit(function(e) {
            $('#login_popup_error').hide();
            var url = "<?=base_url()._e('lang')?>/login/index";
            $.ajax({
                   type: "POST",
                   url: url,
                   data:  $(this).serializeArray(), // serializes the form's elements.
                   success: function(data)
                   {
                       if ( data.error ){
                           $('#login_popup_error').show();
                           $('#login_popup_error').html(data.error);
                       } else if (data.redirect) {
                           location.replace( data.redirect );
                       }

                   },
                   dataType: "json"
                 });

            e.preventDefault();


        });
        
         

         /*
        if('function' == typeof window.fbAsyncInit) return;
        console.log('fbAsyncInit');
        window.fbAsyncInit = function() {
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
          //  $(function() {
                $(document).on('click','.social-facebook',function(e) {
                    try{yaCounter23161861.reachGoal('facebook');}catch(a){console.log(a);}
                    FB.login(function(response) {
                        if (response.authResponse) {
                            parent.location = '<?=site_url('/login/facebook')?>'; //redirect uri after closing the facebook popup
                        }
                    }, {scope: 'email,publish_actions,user_birthday,user_location,user_work_history,user_hometown,user_photos'}); //permissions for facebook
                });
           // });*/
    });
    
    
</script>
<script  data-cfasync='true' src="/js/user/odnoklassniki.js" type="text/javascript" charset="utf-8"></script>
<!--broken for Safari-->
<!--<script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>-->
<script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
<script data-cfasync='true' type="text/javascript">
    $(function() {
        if('undefined' != typeof soc_3) return;
        console.log('soc_3');
        VK.init({apiId: <?= $vkontakteConfig['id'] ?>, onlyWidgets: true}, '5.24');
        VK.Api.call('groups.getById', {gid: 55660968, fields: 'members_count'}, function(r) {
            if (r.response) {
                $('.page-social_counters-bar_social-counter .vk .value').html(r.response[0].members_count);
                $('.list-counters .vk').animate({'opacity': '1'}, 300);
            }
        });
        soc_3 = true;
    });
</script>