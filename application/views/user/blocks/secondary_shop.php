<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="secondary-nav" data-name="profile">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='index'?'active':'') ?>">
            <!-- Документация API -->
            <a class="secondary-nav_link" href="<?=site_url('account/merchant/')?>"><?=_e('Магазины')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='transactions'?'active':'') ?>">
            <!-- Документация API -->
            <a class="secondary-nav_link" href="<?=site_url('account/merchant/transactions/')?>"><?=_e('Транзакции')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='api'?'active':'') ?>">
            <!-- Документация API -->
            <a class="secondary-nav_link" href="<?=site_url('account/merchant/api/')?>"><?=_e('Документация')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='add'?'active':'') ?>">
           <!-- Добавление магазина -->
           <a class="secondary-nav_link" href="<?=site_url('account/merchant/add/')?>"><?=_e('Добавить')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
