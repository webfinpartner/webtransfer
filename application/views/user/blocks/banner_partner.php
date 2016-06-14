<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<header id="header">
    <div class="wrapper" role="banner">
        <h1 id="logo">
            <a href="<?=site_url('/')?>">
                <img class="logo-img" src="/img/logowt.gif" alt="<?=_e('blocks/baner_partner_1')?>">
                <img class="logo-img-mini" src="/img/logowt.gif" alt="<?=_e('blocks/baner_partner_2')?>">
            </a>
        </h1>
        <? if ($this->base_model->user_is_login ){ ?>
            <? $this->load->view('user/blocks/renderHeaderMenu_part.php', array("accaunt_header" => $accaunt_header, "user" => $user));?>
            <nav id="user-main-menu">
                <ul class="user-main-menu_list" role="menubar">
                    <li class="user-main-menu_item partner <?= (($page_name == 'my_users') ? 'active' : '') ?>">
                        <a class="menu_item_link" href="<?=site_url('partner/my_users')?>">
                            <div class="user-main-menu_item_amount"><?= number_format($accaunt_header['myChildren'], 0, ',', ' '); ?> <?=_e('blocks/baner_partner_9')?></div>
                            <div class="user-main-menu_item_name"><?=_e('blocks/baner_partner_3')?></div>
                        </a>
                    </li>
                    <li class="user-main-menu_item borrow <?= (($page_name == 'expected_income') ? 'active' : '') ?>">
                        <a class="menu_item_link" href="<?=site_url('partner/expected_income')?>">
                            <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['soon']); ?></div>
                            <div class="user-main-menu_item_name"><?=_e('blocks/baner_partner_4')?></div>
                        </a>
                    </li>
                    <li class="user-main-menu_item wallet <?= (($page_name == 'transactions') ? 'active' : '') ?>">
                        <a class="menu_item_link" href="<?=site_url('account/transactions')?>">
                            <div class="user-main-menu_item_amount">$ <?= price_format_double( $accaunt_header['payment_account']+$accaunt_header['bonuses'], FALSE) ?></div>
                            <div class="user-main-menu_item_name"><?=_e('blocks/baner_partner_5')?></div>
                        </a>
                    </li>
                    <li class="user-main-menu_item balans <?= (($page_name == 'my_balance') ? 'active' : '') ?>">
                        <a class="menu_item_link" href="<?=site_url('partner/my_balance')?>">
                            <div class="user-main-menu_item_amount">$ <?=price_format_double(round($bal[(int)date("d", strtotime("yesterday"))],2));?></div>
                            <div class="user-main-menu_item_name"><?=_e('blocks/baner_partner_6')?></div>
                        </a>
                    </li>
                    <li class="user-main-menu_item rating <?= (($page_name == 'my_rating') ? 'active' : '') ?>">
                        <a class="menu_item_link" href="<?=site_url('account/my_rating')?>">
                            <div class="user-main-menu_item_amount"><?= $accaunt_header['fsr']; ?></div>
                            <div class="user-main-menu_item_name"><?=_e('blocks/baner_partner_7')?></div>
                        </a>
                    </li>
                </ul>
            </nav>
        <? } else { ?>
            <nav id="social-buttons" class="social-buttons" >
                <div class="social-buttons_title"><?=_e('blocks/baner_partner_8')?></div>
                <ul class="social-buttons_list">
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
                </ul>

            </nav>
        <? }
        ?>
    </div>
</header>