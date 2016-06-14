<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!isset($accaunt_header)) $accaunt_header = viewData()->accaunt_header;
?>
<? if ($this->base_model->user_is_login) { ?>
    <? $this->load->view('user/blocks/renderHeaderMenu_part.php', array("accaunt_header" => $accaunt_header, "user" => $user));?>
    <nav id="user-main-menu">
        <ul class="user-main-menu_list" role="menubar" style="z-index: 999;">
            <li class="user-main-menu_item invest <?= (($page_name == 'invests_enqueries') ? 'active' : '') ?>">
                <a class="menu_item_link" href="<?=site_url('account/invests_enqueries')?>">
                    <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['invests']); ?></div>
                    <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_3')?></div>
                </a>
            </li>
            <li class="user-main-menu_item borrow <?= (($page_name == 'credits_enqueries') ? 'active' : '') ?>">
                <a class="menu_item_link" href="<?=site_url('account/credits_enqueries')?>">
                    <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['credits']); ?></div>
                    <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_4')?></div>
                </a>
            </li>
            <li class="user-main-menu_item wallet <?= (($page_name == 'transactions') ? 'active' : '') ?>">
                <a class="menu_item_link" href="<?=site_url('account/transactions')?>">
                    <div class="user-main-menu_item_amount">$ <?= price_format_double( $accaunt_header['payment_account']+$accaunt_header['bonuses']+$accaunt_header['partner_funds'], FALSE) ?></div>
                    <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_5')?></div>
                </a>
            </li>
            <li class="user-main-menu_item balans <?= (($page_name == 'my_balance') ? 'active' : '') ?>">
                <a class="menu_item_link" href="<?=site_url('account/my_balance')?>">
                    <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['balance'], FALSE); ?></div>
                    <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_6')?></div>
                </a>
            </li>
            <li class="user-main-menu_item rating <?= (($page_name == 'my_rating') ? 'active' : '') ?>">
                <a class="menu_item_link" href="<?=site_url('account/my_rating')?>">
                    <div class="user-main-menu_item_amount"><?= $accaunt_header['fsr']; ?></div>
                    <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_7')?></div>
                </a>
            </li>
        </ul>
    </nav>
<? } else { ?>
    <nav id="social-buttons" class="social-buttons" >
        <div class="social-buttons_title"><?=_e('blocks/baner_profile_login_3')?></div>
        <ul class="social-buttons_list" style="z-index: 999;">
            <li class="social-buttons_item twitter">
                <a href="#" class="social-twitter">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item odnoklassniki">
                <a target="_blank" class="social-odnoklassniki" href="#">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item moikrug">
                <a  href="#" class="social-mail_ru">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item googleplus">
                <a  href="#" class="social-google_plus">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item vk">
                <a  href="#" class="social-vkontakte">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item fb">
                <a href="#" class="social-facebook" style="cursor:pointer;">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
            <li class="social-buttons_item wt wt-login" id="wt-login">
                <a href="#" class="social-webtransfer bn_wt_login_form" onclick="return false;" style="cursor:pointer;">
                    <span class="social-buttons_item_img"></span>
                </a>
            </li>
        </ul>

    </nav>

<? $this->load->view('user/blocks/loginForm_window.php'); ?>
    <? }
?>
