<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
    <?php // vred($page_name); ?>
<style>
    .show_not_read_messages_span{
        position: absolute;
        top: 0;
        right: 0;
        font-size: 14px;
        line-height: 14px;
        text-align: center;
        color: rgb(255, 255, 255);
        border-radius: 15px;
        background-color: rgb(242, 72, 65);
        overflow: auto;
        height: 18px;
        width: 22px;
        padding-top: 4px;
        display: block;
    }
    #secondary-nav .secondary-nav_item{
        width: 16% !important;
    }
</style>
<nav id="secondary-nav" data-name="profile">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='sell'?'active':'') ?>"  >
            <a class="secondary-nav_link" href="<?=site_url('account/currency_exchange/sell')?>">
                <?=_e('currency_exchange/sell')?>
            </a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='my_sell_list'?'active':'') ?>" style="position:relative;">
            <a class="secondary-nav_link" href="<?=site_url('account/currency_exchange/my_sell_list')?>"><?=_e('currency_exchange/my_sell_list_2')?>
             <!--  <span class="show_not_read_messages_span" <?php echo $count_chat>0?'':'style="display: none;"'?>>                    (<span><?php // echo $count_chat;?></span>)                </span>-->
                <span class="show_not_read_messages_span show_not_read_messages_span_curr_ex" <?php echo $count_order_chat_conf>0?'':'style="display: none;"'?>>
                    <?php echo $count_order_chat_conf;?>
                </span>
            </a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='sell_search'?'active':'') ?>" >
            <a class="secondary-nav_link" href="<?=site_url('account/currency_exchange/sell_search?show_start=20')?>">
                <?//=_e('currency_exchange/my_sell_list')?>
                <?=_e('Биржа P2P')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name == 'Giftguarant-market' ? 'active' : '') ?>">
            <a class="secondary-nav_link" href="<?= site_url('account/currency_exchange/giftguarant_market') ?>"><?= _e('GIFT CARDS') ?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='my_sell_list_arhive'?'active':'') ?>" >
            <a class="secondary-nav_link" href="<?=site_url('account/currency_exchange/my_sell_list_arhive')?>">
                <?=_e('currency_exchange/my_sell_list_arhive')?>
                <span class="show_not_read_messages_span show_not_read_messages_span_curr_ex" <?php echo $count_chat>0?'style="margin-left: 94px; margin-top: -21px; position: inherit;"':'style="display: none;"'?>>
                    <?php echo $count_chat;?>
                </span>
            </a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='payment_preferences'?'active':'') ?>" >
            <a class="secondary-nav_link" href="<?=site_url('account/currency_exchange/payment_preferences')?>">
                <?=_e('currency_exchange/payment_preferences')?>
            </a>
        </li>
    </ul>
</nav><!-- Edn sec nav -->