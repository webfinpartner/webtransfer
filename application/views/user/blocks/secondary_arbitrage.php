<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    #secondary-nav .secondary-nav_item{
        width: 50% !important;
    }
</style>
<nav id="secondary-nav">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='arbitrage_credit'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/arbitrage_credit')?>"><?=_e('Получить Арбитраж')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='arbitrage_invest'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/arbitrage_invest')?>"><?=_e('Выдать Арбитраж')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>

<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/js/select3/select3.css" type="text/css" media="screen" />
<script src="/js/select3/select3-full.min.js"></script>

<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/user/jquery.validate.js"></script>
<script type="text/javascript" src="/js/user/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<? if('ru' == _e('lang')){ ?><script type="text/javascript" src="/js/user/messages_ru.js"></script><?}?>
<script type="text/javascript" src="/js/user/form_reg.js"></script>
<script type="text/javascript" src="/js/user/credit.js"></script>
<script type="text/javascript" src="/js/user/debits.js"></script>

<script type="text/javascript" src="/js/user/sms_module.js"></script>

<!--<link rel="stylesheet" href="/css/user/filter.css" type="text/css" media="screen" />

<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link href="/css/calendar-tt.css" rel="stylesheet">
<script src="/js/jquery-ui-1.10.4.custom.js"></script>-->