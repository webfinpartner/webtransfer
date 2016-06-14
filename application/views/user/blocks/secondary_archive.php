<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    #secondary-nav .secondary-nav_item{
        width: 33% !important;
    }
    #secondary-nav li.secondary-nav_item:last-child{
        float: left;
    }
</style>
<nav id="secondary-nav">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='archive_invests_enqueries'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/archive_invests_enqueries')?>"><?=_e('blocks/secondary_archive_1')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='archive_credits_enqueries'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/archive_credits_enqueries')?>"><?=_e('blocks/secondary_archive_2')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='archive_transactions'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url('account/archive_transactions')?>"><?=_e('blocks/secondary_archive_3')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>

<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/user/filter.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/user/debits.js"></script>