<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
    <? $this->load->view('user/blocks/iframe_header_user');?>
<div class="inner">


</div>

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

<script type="text/javascript" src="/msgs/src/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="/js/user/rus.js"></script>
<script type="text/javascript" src="/js/user/jquery.validationEngine.js"></script>
<script type="text/javascript" src="/js/user/custom.js"></script>
<script type="text/javascript" src="/js/lang/ru.js"></script>

<script type="text/javascript" src="/js/user/security_module.js?v=201603143"></script>


<link rel="stylesheet" href="/css/user/security_module.css?v=20160121">
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
<link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange_sms.css"/>


<style>
    body {
        background-color: #FFFFFF !important;
        text-align: left !important;
    }
    .search_fields_container {
        padding-top: 50px !important;

    }

    #modal_visa_prefund {
        width: 60%;
        left: 20%;
        right: 20%;
    }
    div#popup_debit.popup_window_exchange {
        top: 75px !important;
    }

    #sm .modal-content {
        width: 360px;
        margin: 0 auto;
    }


    .popup_window table {
        width: 310px !important;
    }

    #modal_visa_prefund {
        top: 15% !important;
    }

    div#popup_exchange_wt, div#popup_exchange {
        width: 600px !important;
        left: 24px !important;
    }

    #select_payment_visa_card {
        top: 246px !important;
        left: 17.5px !important;
    }

    #last_user_confirm_block_money_send {
        left: 20px !important;
    }
    #real_last_user_confirm_block {
        left: 120px !important;
    }

    #popup_select_payment_system {
        left:15px !important;
    }

    .lefthomecolvn.scrol_500, .righthomecolvn.scrol_500, .homeobmentable .scrol_500  {
        max-height: 672px !important;
    }

    <?
    $uri_parts = explode('/', $_SERVER['REQUEST_URI']);
    if(end($uri_parts) == 'iframe_giftguarant_market') {
        ?>
        table.dataTable thead th, table.dataTable thead td {
            padding: 10px 0px !important;
            width: 11% !important;
        }
        span.bn_buyGift.but.agree {
            width: 40px;
        }
        <?
    }
    ?>
</style>
<link rel="stylesheet" href="/css/user/accaunt.css" />
<div id="container" class="content">

    <?= $content; ?>
    <? $this->load->view('user/accaunt/security_module/loader');?>
</div>


<?
$this->load->view('user/blocks/iframe_footer');



