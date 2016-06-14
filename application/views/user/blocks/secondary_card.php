<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="secondary-nav" data-name="profile">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='card'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/card-create')?>"><?=_e('Заказать')?></a>
        </li>

		<li class="secondary-nav_item <?= ($page_name=='lst'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/card-lst')?>"><?=_e('Мои карты')?></a>
        </li>
		
		
        <li class="secondary-nav_item <?= ($page_name=='transactions'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/card-transactions/')?>"><?=_e('Транзакции')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='tariff'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/card-tariff/')?>"><?=_e('Тарифы')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='terms'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/card-terms/')?>"><?=_e('Условия')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<script type="text/javascript" src="/js/user/sms_module.js"></script>