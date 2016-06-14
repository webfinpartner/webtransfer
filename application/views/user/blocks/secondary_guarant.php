<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="secondary-nav" data-name="profile">
    <ul class="secondary-nav_list">
        <li style="width: 30%" class="secondary-nav_item <?= ($page_name=='create'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/guarant/create')?>"><?=_e('Выпустить Гарантию')?></a>
        </li>

	<li style="width: 23.33%" class="secondary-nav_item <?= ($page_name=='sended'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/guarant/sended')?>"><?=_e('Выданные')?></a>
        </li>
        <li style="width: 23.33%" class="secondary-nav_item <?= ($page_name=='received'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/guarant/received/')?>"><?=_e('Полученные')?></a>
        </li>
		        <li style="width: 23.33%" class="secondary-nav_item <?= ($page_name=='terms'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/guarant/terms/')?>"><?=_e('Условия')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script type="text/javascript" src="/js/user/sms_module.js"></script>
<script type="text/javascript" src="/js/user/credit.js"></script>
<script type="text/javascript" src="/js/user/debits.js"></script>