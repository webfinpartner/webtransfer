<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>

<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript"  src="/js/lang/<?= _e('lang') ?>.js"></script>
    <link rel="stylesheet" href="/css/user/security_module.css" />
    <link rel="stylesheet" href="/css/user/styles1.css" />
    <link rel="stylesheet" href="/css/user/accaunt.css" />
    <link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
    <title><?=_e('accaunt/token_1')?></title>
</head>

<body>
    <div id="security_module_loading" class="security_module no-close" style="display: none;">
        <div class="close" onclick="mn.security_module.loader.hide();"></div>
        <div id="loadingImg"><img src="/images/security_module/loader/ajax-loader64.gif"></div>
        <span class="msg_text">Пожалуйста, подождите,<br> Ваш запрос обрабатывается.</span>
    </div>
    <script>
        var mn = {};
        mn.site_lang = "<?= $this->lang->lang() ?>";
    </script>
    <script type="text/javascript" src="/js/user/security_module.js"></script>
    <script>
        mn.security_module
        .init()
        .show_window('withdrawal_standart_credit')
        .done(function(res){
            if(res.res == 'closed') window.location.href = '<?=site_url('account/social')?>';
            if( res.res != 'success' ) return false;
            var code = res['code'];
            window.location.href = '<?=site_url('account/social_add')?>/<?=$id_user?>/'+code;
        });
    </script>

</body>

</html>
