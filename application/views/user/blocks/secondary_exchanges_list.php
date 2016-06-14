<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    #secondary-nav .secondary-nav_item{
        width: 25% !important;
    }
</style>
	<nav id="secondary-nav">
		<ul class="secondary-nav_list">
			<li class="secondary-nav_item <?= ($page_name=='exchanges_list'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('account/exchanges-list')?>"><?=_e('Обменники')?></a>
			</li>

			<li class="secondary-nav_item <?= ($page_name=='exchanges-terms'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('account/exchanges-terms')?>"><?=_e('Условия')?></a>
			</li>
			<li class="secondary-nav_item <?= ($page_name=='exchanges-features'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('account/exchanges-features')?>"><?=_e('Преимущества')?></a>
			</li>
            <li class="secondary-nav_item <?= ($page_name=='exchanges-registration'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('account/exchanges-registration')?>"><?=_e('Регистрация')?></a>
			</li>
		</ul>
	</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>