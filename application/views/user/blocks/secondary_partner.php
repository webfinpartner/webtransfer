<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	<nav id="secondary-nav">
		<ul class="secondary-nav_list">
					<li style="width:25%" class="secondary-nav_item <?= ($page_name=='my-link'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('partner/my-link')?>"><?=_e('Мой Сайт')?></a>
			</li>
			
			<li style="width:25%" class="secondary-nav_item <?= ($page_name=='banner'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('partner/banner')?>"><?=_e('Баннеры')?></a>
			</li>
			 <li style="width:25%" class="secondary-nav_item <?= ($page_name=='invite'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('partner/invite')?>"><?=_e('Пригласить')?></a>
			 </li> <? /*
			 <li style="width:25%" class="secondary-nav_item <?= ($page_name=='index'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('partner/my-link')?>"><?=_e('Ваш сайт')?></a>
			 </li>
                          */ ?>
			 <li style="width:25%" class="secondary-nav_item <?= ($page_name=='partner-conditions'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('page/partner-conditions')?>"><?=_e('Условия')?></a>
			 </li>
                          
                          
		</ul>
	</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>