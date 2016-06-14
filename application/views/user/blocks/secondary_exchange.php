<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
#secondary-nav .secondary-nav_item {
width:33% !important;
}
</style>
<nav id="secondary-nav">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name == 'applications_credits' ? 'active' : '') ?>">
            <a class="secondary-nav_link" href="<?= site_url('account/applications_credits') ?>"><?= _e('Получить Кредит') ?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name == 'applications_invest' ? 'active' : '') ?>">
            <a class="secondary-nav_link" href="<?= site_url('account/applications_invest') ?>"><?= _e('Дать Кредит') ?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name == 'exchange' ? 'active' : '') ?>">
            <a class="secondary-nav_link" href="<?= site_url('account/exchange') ?>"><?= _e('Сертификаты') ?></a>
        </li>
        <!--li class="secondary-nav_item <?= ($page_name == 'Giftguarant-market' ? 'active' : '') ?>">
            <a class="secondary-nav_link" href="<?= site_url('account/giftguarant/market') ?>"><?= _e('Gift') ?></a>
        </li-->
        
    </ul> 
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>