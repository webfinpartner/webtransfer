<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>Панель Управления</title>
<base href="<?=base_url();?>"  />
<link href="<?=base_url('/')?>css/admin/main.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url('/')?>css/admin/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/spinner/ui.spinner.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/spinner/jquery.mousewheel.js"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/lang/ru.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/uniform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.validationEngine-ru.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/forms/chosen.jquery.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.form.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/wizard/jquery.form.wizard.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/uploader/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/datatable.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/tablesort.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/tables/resizable.min.js"></script>

<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/ui/jquery.sourcerer.js"></script>



<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/calendar.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/plugins/elfinder.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/user/jquery.maskMoney.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/user/jquery.cookie.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/custom.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/jquery.form.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/admin/admin.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/myscripts.js"></script>


<script type="text/javascript" src="<?=base_url()?>msgs/src/tinymce/tinymce.min.js"></script>


</head>
<body>

<!-- Left side content -->
<div id="leftSide">
    <div class="logo"><a href="/opera/page/all"><img src="images/logo.png" alt="" /></a></div>

    <div class="sidebarSep mt0"></div>

    <!-- Left navigation -->
    <ul id="menu" class="nav" style="" >

        <li id="main_admin_settings" class="charts"><a href="#" class="exp" ><span>Управление Сайтом</span></a>
            <ul class="sub">
                <li id="main_admin_settings_settings"><a href="/opera/settings/1" >Настройки</a></li>

                <li id="main_admin_settings_page_all"><a href="/opera/page/all" >Страницы Сайта</a></li>
                <li id="main_admin_settings_partnerBanner"><a href="/opera/partner_banner/all" >Баннеры для партнеров</a></li>
                <li id="main_admin_settings_news_all"><a href="/opera/news/all" >Новости</a></li>
                <li id="main_admin_settings_video_all"><a href="/opera/video/all" >Видео</a></li>
                <li id="main_admin_settings_sender_all"><a href="/opera/sender/all" >Уведомления</a></li>
                <li id="main_admin_settings_shablon_all"><a href="/opera/shablon/all" >Шаблоны</a></li>
                <li id="main_admin_settings_admin_all"><a href="/opera/admins/all" >Модераторы</a></li>
                <!--<li id="main_admin_settings_log"><a href="/opera/log" >История изминений</a></li>-->
                <!--<li id="main_admin_settings_statistic"><a href="/opera/statistic" >Статистика</a></li>-->
                <li id="main_admin_settings_statistic_messenger"><a href="/opera/statistic_messenger" >Статистика сообщений</a></li>
                <li id="main_admin_sysmsgs"><a href="/opera/system_messages/all" >Системные сообщения</a></li>
                <li id="main_admin_sysvars"><a href="/opera/system_vars/all" >Системные переменные</a></li>
                <li id="main_admin_sysevents"><a href="/opera/system_events/all" >Системные события</a></li>
                <li id="main_admin_exchanges_list"><a href="/opera/exchanges/all" >Список Обменников</a></li>
                <li id="main_admin_vdnareports"><a href="/opera/vdna_reports/all" >Отчеты VDNA</a></li>
                <li id="main_admin_sysvars"><a href="/opera/translate/all" >Перевод</a></li>

            </ul>
        </li>



        <li id="main_admin_users" class="ui"><a href="#"  class="exp"><span>Клиенты</span><strong></strong></a>
            <ul class="sub">
                <li id="main_admin_users_all"><a href="/opera/users/all" >База Клиентов</a></li>
                <li id="admin_users_all_profiles"><a href="/opera/users/all_profiles" >Заявки на изменение данных</a> <?news_menu($menu_num['new_user_change'])?></li>
                <li id="main_admin_users_doc_change"><a href="/opera/users/doc_change" >Изменение документов</a> <?news_menu($menu_num['new_doc_change'])?></li>
                <li id="main_admin_payment_all"><a href="/opera/payment/all" >Транзакции</a></li>
                <li id="main_admin_payment_paymentWtcard"><a href="/opera/payment/payment_wtcard" >Вывод WTCard</a><?news_menu($menu_num['new_wt_card'])?><?news_menu($menu_num['new_wt_card_amt'])?></li>
              <li id="main_admin_payment_payoutWtcard"><a href="/opera/payment/payout_wtcard" >Выплаченные WTCard</a></li>
                <li id="main_admin_payment_sendmoney"><a href="/opera/payment/sendmoney" >Переводы</a><?news_menu($menu_num['new_send_money'])?> <?news_menu($menu_num['new_send_money_amt'])?><?//news_menu($menu_num['new_send_money_summ'])?><?//news_menu($menu_num['new_send_money_count'])?></li>
                <li id="main_admin_payment_paymentBankArbitrage"><a href="/opera/payment/payment_bank_arbitrage" >Пополнение на арбитраж</a></li>
                <li id="main_admin_payment_paymentBank"><a href="/opera/payment/payment_bank" >Пополнение банком</a></li>
                <li id="main_admin_payment_payout"><a href="/opera/payment/payout" >На вывод</a> <?news_menu($menu_num['new_payout'])?> <?news_menu($menu_num['new_payout_amt'])?></li>
                <li id="main_admin_payment_payoutbankprocess"><a href="/opera/payment/payoutbankprocess" >На выводе (Банк)</a> </li>
                <li id="main_admin_payment_merchant"><a href="/opera/payment/payment_merchant">Мерчанты</a><?news_menu($menu_num['new_merchant'])?> </li>
                <li id="main_admin_payment_payoutnew"><a href="/opera/payment/payoutnew" >Вывод new webfin</a> <?news_menu($menu_num['new_payoutnew'])?></li>
                <!--li id="main_admin_payment_payoutnew"><a href="/opera/payment/payoutnewno" >Вывод new webfin без обеспечения</a> <?news_menu($menu_num['new_payoutnew_no'])?></li-->
                <li id="main_admin_loan_payouts"><a href="/opera/payment/loan_payouts" >Вывод займов</a> <?news_menu($menu_num['new_loan_payouts'])?></li>
                <li id="main_admin_payout_wtdebit"><a href="/opera/payment/payout_wtdebit" >Вывод WTDEBIT</a> <?news_menu($menu_num['new_payout_wtdebit'])?></li>
                <li id="main_admin_payout_pcreds"><a href="/opera/payment/payout_pcreds" >Вывод PCREDS</a> <?news_menu($menu_num['new_payout_pcreds'])?></li>
                <li id="main_admin_payment_payout"><a href="/opera/payment/payout_payed" >Выплаченные заявки </a> <?news_menu($menu_num['new_payout_payed'])?></li>
                <li id="main_admin_payment_verifySS"><a href="/opera/payment/verify_ss" >Проверка СБ </a> <?news_menu($menu_num['new_verify_ss'])?></li>
                 <li id="main_admin_payment_autoCheckPayout"><a href="/opera/payment/auto_check_payout" >Автомат проверки </a> <?//news_menu($menu_num['new_verify_ss'])?></li>
                <li id="main_admin_payment_bonussb"><a href="/opera/payment/payment_bonus_sb" >Проверка бонусов </a> <?news_menu($menu_num['new_bonus_sb'])?></li>
<!--                 <li id="main_admin_payment_cardExchangePay"><a href="/opera/payment/card_exchange_pay" >Перевод с карты </a> <?//news_menu($menu_num['new_verify_ss'])?></li>-->

            </ul>
        </li>
        <li id="main_admin_p2p" class="ui"><a href="#"  class="exp"><span>P2P переводы</span><strong></strong></a>
            <ul class="sub">
                <li id="main_admin_currency_exchange_message"><a href="/opera/currency_exchange_chat/all" >Чат обменника</a> <?news_menu($menu_num['new_chat_message'])?></li>
                <li id="main_admin_currency_exchange_all"><a href="/opera/currency_exchange/all">Все заявки</a>  </li>
                <li id="main_admin_currency_exchange_all_unlinked"><a href="/opera/currency_exchange/all_unlinked">Несведенные заявки</a> <?news_menu($menu_num['unlinked_order_to_check'])?> </li>
                <li id="main_admin_currency_exchange_all_linked"><a href="/opera/currency_exchange/all_linked">Сведенные заявки</a> <?news_menu($menu_num['linked_order_to_check'])?> </li>
                <li id="main_admin_currency_exchange_all_sb"><a href="/opera/currency_exchange/all_sb">Проверка СБ</a> <?news_menu($menu_num['sb_order_to_check'])?> </li>
                <li id="main_admin_currency_exchange_all_other"><a href="/opera/currency_exchange/all_other">Остальные заявки</a>  </li>
                <li id="main_admin_currency_exchange_block_users_all"><a href="/opera/currency_exchange_block_users/all" >Заблокированные пользователи</a> </li>
 <li id="main_admin_payment_paysys"><a href="/opera/currency_exchange_paysys/all" >Платежные системы</a> </li>
 <li id="main_admin_payment_paysys"><a href="/opera/currency_exchange/completed_orders" >Завершенные сделки</a> </li>

            </ul>
        </li>
        <!-- <li id="main_admin_partner" class="files"><a href="#"  class="exp"><span>Партнеры</span><strong> </strong></a>
            <ul class="sub">
		<li id="main_admin_partner_news_2"><a href="/opera/users/news/2" >Заявки на Партнерство</a> </li>
                <li id="main_admin_partner_all_2"><a href="/opera/users/all/2" >База Партнеров</a></li>
            </ul>
        </li>-->
        <li id="main_admin_support" class="files"><a href="file_manager.html"  class="exp" ><span>Служба Поддержки</span><strong> </strong></a>
            <ul class="sub">
                <li id="main_admin_support_feedback_admin_support"><a href="/opera/feedback/admin_support" >База чатов</a> </li>
                <li id="main_admin_support_feedback_admin_support_ask"><a href="/opera/feedback/admin_support_ask" >Запросы из чатов на поддержку</a> </li>
                <li id="main_admin_support_feedback_admin_vip_chats"><a href="/opera/feedback/admin_vip_chats" >VIP Чат</a> </li>
                <li id="main_admin_support_feedback_admin_vip_users"><a href="/opera/users/vip_users" >VIP Пользователи</a> </li>
                <li id="main_admin_support_feedback_new_feeds"><a href="/opera/feedback/new_feeds" >Новая Заявка на поддержку</a> </li>
                <li id="main_admin_support_feedback_all_feeds"><a href="/opera/feedback/all_feeds" >База запросов</a></li>
                <li id="main_admin_faq_all"><a href="/opera/faq/all" >FAQ</a></li>
                <li id="main_admin_send_notices_all"><a href="/opera/send_notices/all" >Отправка уведомлений</a></li>
            </ul>
	</li>

<!--        <li id="main_admin_prefunds" class="files"><a href="#"  class="exp" ><span>VISA префанд</span><strong> </strong></a>
            <ul class="sub">
                <li id="main_admin_support_feedback_admin_support"><a href="/opera/prefund/all" >Все записи</a> </li>

            </ul>
	    </li>
    </ul>
    -->
</div>


<!-- Right side -->
<div id="rightSide">

    <!-- Top fixed navigation -->
    <div class="topNav">
        <div class="wrapper">
            <div class="welcome"><a href="#" ><img src="images/userPic.png" alt="" /></a><span>Здравствуйте,  <?=$this->admin_info->login?>!</span></div>
            <div class="userNav">
                <ul>
                    <li id="top_admin_profile"><a href="/opera/profile" ><img src="images/icons/topnav/profile.png" alt="" /><span>Профайл</span></a></li>
                    <li><a href="/opera/auth/logout" ><img src="images/icons/topnav/logout.png" alt="" /><span>Выйти</span></a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!-- Title area -->
    <div class="titleArea">
        <div class="wrapper">
            <div class="pageTitle" style="padding:20px 0px;">
                <h5><?=($title) ? $title : "Админ  панель"?></h5>
                <span>Управление настройками сайта и системы</span>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div class="line"></div>

    <!-- Main content wrapper -->
    <div class="wrapper">
<script>
    var ids = <?=json_encode(Permissions::getInstance()->getIDs())?>;
    for (var i in ids){
        var id = '#'+ids[i];
        $(id).remove();
    }
</script>
         <?
if($info_state){
$message=$this->session->userdata('error');
if($message!="empty" and !empty($message)){?><div class="nNote nInformation hideit">
            <p><strong>Сообщение: </strong><?=$this->info->get_info();?></p>
        </div>
		<script>setTimeout(function(){$(".hideit").trigger('click');},  7000)</script>

 <?}
}?>
<?=$content?>
 </div>   <!-- Footer line -->
    <div id="footer">

    </div>

</div>

<div class="clear"></div>
<script>
    var ids = <?=json_encode(Permissions::getInstance()->getIDs())?>;
    for (var i in ids){
        var id = '#'+ids[i];
        $(id).remove();
    }
</script>
</body>

</html>
