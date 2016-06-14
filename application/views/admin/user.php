<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<? function table_value_format($value){

    if ( $value == 0)
        return '';

    return '$ '.price_format_double($value);
}
?>

<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- required for number formatter -->
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>
<script src="js/admin/go.js"></script>

<style>

    .dateFromTo {
        padding: 5px;
    }
    .dateFromTo input[type=text] {
        width: 150px;
        font-size: 12px;
        height: 20px;
    }


    .ui-dropdownchecklist-dropcontainer
    {
     width: 155px !important;
     height: 175px !important;
      z-index: 100000;
    }

    .ui-datepicker {
      z-index: 100000;
    }


    .loading {
        color: #000000;
    }

    .order-by a {
        color: #222222;
    }

    .order-by a:hover, .order-by a:active, .order-by a:focus {
        outline: 0;
        text-decoration: none;
    }

    .order-by .active {
        color: #0099FF;
        text-decoration: none;
    }
    .glyphicon {
        /*display: none;*/
    }
    .active {
        /*display: block;*/
    }
    .order-by {
        display: block;
    }
    #table th {
        text-align: center;
    }
    .col_id_debit_id_user, .col_id_id_user{
        cursor: pointer;
    }
    .col_id_state {
        text-align: center;
    }
    .body .parent_id_input{
        width: 100px;
        margin: 0 10px;
    }
    .dd-select {
        border: 1px solid #CCCCCC;
        border-radius: 2px;
        cursor: pointer;
        position: relative;
        float: left;
        margin-right: 10px;

        width: 67px;
        background: none repeat scroll 0% 0% #fff;
    }
    .dd-desc {
        color: #AAAAAA;
        display: block;
        font-weight: normal;
        line-height: 1.4em;
        overflow: hidden;
    }
    .dd-selected {
        display: block;
        font-weight: bold;
        overflow: hidden;
        padding: 2px;

    }
    .dd-pointer {
        height: 0;
        margin-top: -3px;
        position: absolute;
        right: 10px;
        top: 50%;
        width: 0;
    }
    .dd-pointer-down {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #000000 rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
        border-image: none;
        border-right: 5px solid rgba(0, 0, 0, 0);
        border-style: solid;
        border-width: 5px;
    }
    .dd-pointer-up {
        -moz-border-bottom-colors: none !important;
        -moz-border-left-colors: none !important;
        -moz-border-right-colors: none !important;
        -moz-border-top-colors: none !important;
        border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #000000 !important;
        border-image: none !important;
        border-style: solid !important;
        border-width: 5px !important;
        margin-top: -8px;
    }
    .dd-options {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background: none repeat scroll 0 0 #FFFFFF;
        border-color: -moz-use-text-color #CCCCCC #CCCCCC;
        border-image: none;
        border-right: 1px solid #CCCCCC;
        border-style: none solid solid;
        border-width: medium 1px 1px;
        box-shadow: 0 1px 5px #DDDDDD;
        display: none;
        list-style: none outside none;
        margin: 0;
        overflow: auto;
        padding: 0;
        position: absolute;
        z-index: 2000;
        top: 33px;

        max-height: 250px;
    }
    .dd-opened{
        display: block;
    }
    .dd-option {
        border-bottom: 1px solid #DDDDDD;
        color: #333333;
        cursor: pointer;
        display: block;
        overflow: hidden;
        padding: 4px;
        text-decoration: none;
        transition: all 0.25s ease-in-out 0s;
    }
    .dd-options > li:last-child > .dd-option {
        border-bottom: medium none;
    }
    .dd-option:hover {
        background: none repeat scroll 0 0 #F3F3F3;
        color: #000000;
    }
    .dd-selected-description-truncated {
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .dd-option-selected {
        background: none repeat scroll 0 0 #F6F6F6;
    }
    .dd-option-image, .dd-selected-image {
        float: left;
        margin-right: 5px;
        max-width: 64px;
        vertical-align: middle;
    }
    .dd-image{
        float: left;
        margin: 6px;
    }
    .dd-container {
        position: relative;
    }

    .formRight label.dd-selected-text{
        float: right;
        color: #333;
        font-weight: 400;
        font-size: 12px;
    }
    .phone-code{
        float: left;
    }

    .user_balance .formRight{
        margin-top: 5px;
        width: 70%;
    }
    .user_balance .balance_paragraf label{
        font-weight: bold;
        font-size: 14px;
    }
    .link{
        color: #0064c8;
        cursor: pointer;
    }
    .my span{
        height: 25px;
    }


    .table-striped > tbody > tr.blue:nth-child(2n+1) > td,
    tr.blue td, blue{
        background-color: rgba(0,0,255,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.red:nth-child(2n+1) > td,
    tr.red td, red{
        background-color: rgba(255,0,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.green:nth-child(2n+1) > td,
    tr.green td, green{
        background-color: rgba(0,255,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.have_in:nth-child(2n+1) > td,
    tr.have_in td, have_in{
        /*background-color: #cccccc;*/
        color: #009900;
    }
    .table-striped > tbody > tr.user_deactive:nth-child(2n+1) > td,
    tr.user_deactive td, user_deactive{
        /*background-color: #595959;*/
        color: #ddd;
    }
    .table-striped > tbody > tr.std_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.std_credits td.col_id_user_ip, std_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.trans_send:nth-child(2n+1) > td.col_id_user_ip,
    tr.trans_send td.col_id_user_ip, trans_send{
        color: red;
    }
    .table-striped > tbody > tr.diff_acc_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_credits td.col_id_user_ip, diff_acc_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.diff_acc_ip:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_ip td.col_id_user_ip, diff_acc_ip{
        color: red;
    }

    td.black{
        color: black !important;
    }
</style>

<?
if (!empty($user)) {

    if (!empty($error)) {
        ?><div class="nNote nInformation hideit">

<p><strong>Сообщение: </strong><?= $error ?></p>
        </div><? } ?>
    <style>.center{text-align:center} .center h5{font-size:15px}</style>
	<?php if ($user->bot == 2 or $user->bot == 3) { ?>
    <div class="widget" id="main_window_widget" style="display: none;">
        <div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" /><h6>Пользователь<?=($user->state == 3)?" заблокирован":"";?> </h6>
            <a id="content_admin_users_user_userId_chengeStatus" style="margin: 4px 4px; float: right; " class="button greenB my" title="">
                <?= ($user->state == 3 or $user->state == 1) ? "<span name='$user->id_user' class='user_open'>Активировать" : "<span name='$user->id_user' class='user_close'>Заблокировать" ?>
                </span>
            </a>
            <a id="content_admin_users_user_userId_del" style="margin: 4px 4px; float: right; " class="button redB del my" title="" href="<?php base_url() ?>opera/users/delete/<?= $user->id_user ?>"><span>Удалить</span></a>
            <a id="content_admin_users_user_userId_blockMoney" style="margin: 4px 4px; float: right; " class="button redB my" title="" href="" onclick="$('#dialog_block_money' ).dialog('open'); return false;"><span>Ограничить вывод</span></a>
            <a id="content_admin_users_user_userId_docRequest" style="margin: 4px 4px; float: right; " class="button redB my" title="" href="" onclick="$.get('<?php base_url() ?>opera/users/doc_request/<?= $user->id_user ?>',function(a){if('ok' == a)alert('вывод заблокирован');else alert('вывод разблокирован');}); return false;">
                <?= ($user->doc_request == Users_model::DOC_REQUEST_ENABLE) ? "<span onclick='this.innerHTML = \"Запросить документы\"'>Документы подтвердить" : "<span onclick='this.innerHTML = \"Документы подтвердить\"'>Запросить документы" ?></span>
            </a>
        </div>
        <div class="body">

            <h3> </h3>
            <div style="background-color: red; color: white;"><?=($user->state == 3)?"Причина блокировки пользователя: $user->status_cause. <span class='link' onclick='$(\"#dialog_status_log\").dialog(\"open\");'>Показать историю...</span>":"";?></div>
            <div ><?=($user->state != 3 and !empty($user->status_cause))?"Причина разблакировки пользователя: $user->status_cause. <span class='link' onclick='$(\"#dialog_status_log\").dialog(\"open\");'>Показать историю...</span>":"";?></div>
            <div id="dialog_status_log" style="display: none">
                <?
                if(!empty($user->causes)){
                    foreach ($user->causes as $row) {
                        echo "<p style='".((3 == $row->status) ? "background-color: red; color: white;" : "")."'>Пользователь ". ((3 == $row->status) ? "заблакирован" : "разблокирован");
                        echo " ($row->date). Причина: $row->note. (Админ ID - $row->id_admin[$row->admin_login])</p>";
                    }
                }
                ?>
            </div>
            <script>
                $("#dialog_status_log").dialog({
                    autoOpen: false,
                    height: 200,
                    width: 700,
                    dialogClass: "alert",
                    title:"Причины по которым сменялся статус пользователя."});
            </script>
            <div id="dialog_block_money" style="display: none">
                <input type="text" id="money_for_block" value="<?=$user->paout_limit?>"/>
            </div>
            <script>
                $("#dialog_block_money").dialog({
                    autoOpen: false,
                    height: 200,
                    width: 700,
                    dialogClass: "alert",
                    title:"Введите ограничение заработаных средств для вывода",
                    buttons: [{
                        text: "Установить лимит",
                        click: function() {
                            $.post('<?php base_url() ?>opera/users/blockManey',
                                {id: <?= $user->id_user ?>, val:$("#money_for_block").val()},
                                function(a){
                                    alert("Ограничение установлено");
                                })
                            $("#dialog_block_money").dialog( "close" )
                        }

                        // Uncommenting the following line would hide the text,
                        // .resulting in the label being used as a tooltip
                        //showText: false
                    }]
                });

            </script>
                <!--script type="text/javascript" src="/js/user/jquery.validate.min.js"></script>
                <script type="text/javascript" src="/js/user/additional-methods.js"></script>
                <script type="text/javascript" src="/js/user/messages_ru.js"></script>
                             <script type="text/javascript" src="/js/user/form_reg.js"></script-->

                <!-- Wizard with custom fields validation -->

            <div class="widget">
                <ul class="tabs">
                    <li id="content_admin_users_user_userId_contactsInfo"><a id="tb1" href="#tab1">Инфо</a></li>
                    <li id="content_admin_users_user_userId_doc"><a id="tb2" href="#tab2">Док.</a></li>
                    <li id="content_admin_users_user_userId_balance"><a id="tb3" href="#tab3">Блнс</a></li>
                    <li id="content_admin_users_user_userId_summ"><a id="tb31" href="#tab31">Сум</a></li>
                    <li id="content_admin_users_user_userId_limit"><a id="tb32" href="#tab32">Лмт</a></li>
                    <li id="content_admin_users_user_userId_social"><a id="tb4" href="#tab4">СоцС.</a></li>
                    <li id="content_admin_users_user_userId_getCredits"><a id="tb5" href="#tab5">Крдт</a></li>
                    <li id="content_admin_users_user_userId_getCreditsArchive"><a id="tb8" href="#tab8">А.Крдт</a></li>
                    <li id="content_admin_users_user_userId_getInvests"><a id="tb51" href="#tab51">Инвст</a></li>
                    <li id="content_admin_users_user_userId_getInvestsArchive"><a id="tb81" href="#tab81">А.Инвст</a></li>
                    <li id="content_admin_users_user_userId_getTransactions"><a id="tb52" href="#tab52">Транз</a></li>
                    <li id="content_admin_users_user_userId_getTransactionsArchive"><a id="tb82" href="#tab82">А.Транз</a></li>
                    <li id="content_admin_users_user_userId_getPartners"><a id="tb521" href="#tab521">Пртнр</a></li>
                    <li id="content_admin_users_user_userId_suppurt"><a id="tb53" href="#tab53">Змтк</a></li>
                    <li id="content_admin_users_user_userId_messege"><a id="tb6" href="#tab6">Напсть</a></li>
                    <li id="content_admin_users_user_graph"><a id="tbgraph" href="#graph">Гр</a></li>
                </ul>
                <div class="tab_container">
                    <div id="content_admin_users_user_userId_contactsInfo_tab">
                    <div id="tab1" class="tab_content">

                        <form id="wizard2" method="post" action="opera/users/<?= $user->id_user ?>" class="form">
                            <input type="hidden" name="submited" value="1" />
                            <fieldset class="step" id="w2first">
                                <div class="formRow" style="overflow: auto;">
                                    <span class="wNavButtons" style="float:right;">
                                        <input class="basic" id="back2" value="Back" type="reset" />
                                        <input class="blueB ml10" id="next2" value="Next" type="submit" />
                                    </span>
                                </div>

                                <div class="formRow">



                                    <label>Партнер</label>
                                    <div class="formRight">
                                        <? if (!empty($user->parent) && $user->parent != 0) { ?>
                                            <a href="/opera/users/<?= $user->parent ?>"><?= $parent->sername . ' ' . $parent->name ?></a>
                                            <input id="content_admin_users_user_userId_contactsInfo_parent" style="width: 100px; margin: 0 10px;" type="text" name="parent_id"  value="<?= $user->parent ?>" />
                                        <? } else { ?>
                                            <span>Не задан</span>
                                            <input id="content_admin_users_user_userId_contactsInfo_parent" style="width: 100px; margin: 0 10px;" type="text" name="parent_id"  value="0" />
                                        <? } ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>Никнейм</label>
                                    <div class="formRight">
                                        <input type="text" name="nickname"  value="<?= $user->nickname ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Имя</label>
                                    <div class="formRight"><input type="text" name="n_name"  value="<?= $user->name ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Фамилия</label>
                                    <div class="formRight"><input type="text" name="f_name"  value="<?= $user->sername ?>" /></div>
                                    <div class="clear"></div>
                                </div>


                                <div class="formRow" style="overflow: visible;">
                                    <label>Телефон</label>
                                    <div class="formRight">
                                        <div class="phone-code">
                                            <div class="dd-select ">
                                                <input value="" class="dd-selected-code-value ui-wizard-content" name="phone_code" type="hidden">
                                                <input value="" class="dd-selected-short-name-value ui-wizard-content" name="phone_short_name" type="hidden">
                                                <a class="dd-selected">
                                                    <label class="dd-selected-text">КОД</label>
                                                </a>
                                                <span class="dd-pointer dd-pointer-down"></span>
                                                <ul style="width: 260px;" class="dd-options">
                                                    <?= getPhoneSelect( $user->short_name ) ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <input type="text" name="phone"  class="maskPhone_new" value="<?= $user->phone ?>" />
                                        <?= ($user->phone_verified ? ' подтверждён' : '') ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>




                                <div class="formRow">
                                    <label>Страна</label>
                                    <div class="formRight">


                                        <?= form_dropdown('place', get_country(), $user->place) ?>

                                    </div>
                                    <div class="clear"></div>
                                </div>



                                <div class="formRow">
                                    <label>Дата рождения</label>
                                    <div class="formRight"><input class="maskDate" type="text" name="born_date" value="<?= $user->born ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div id="content_admin_users_user_userId_contactsInfo_skype" class="formRow">
                                    <label>Skype</label>
                                    <div class="formRight"><input type="text" name="skype"  value="<?= $user->skype ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div id="content_admin_users_user_userId_contactsInfo_email" class="formRow">
                                    <label>E-mail</label>
                                    <div class="formRight"><input type="text" name="email"  value="<?= $user->email ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <?php if ($this->admin_info->permission == 'admin'): ?>
                                    <div id="content_admin_users_user_userId_contactsInfo_pass" class="formRow">
                                        <label>Пароль</label>
                                        <div class="formRight"><input type="text" name="password"  value="<?= $user->user_pass ?>" /></div>
                                        <div class="clear"></div>
                                    </div>
                                <?php endif; ?>
                                <div class="formRow">
                                    <label>Регистрация</label>
                                    <div class="formRight"><?= view_online($user->reg_date) ?> с IP: <?= $user->ip_address ?></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Онлайн</label>
                                    <div class="formRight"><?= view_online($user->online_date) ?></div>
                                    <div class="clear"></div>
                                </div>
<?php if ($this->admin_info->permission == 'admin' || $this->admin_info->login == 'Yana'|| $this->admin_info->permission == 'root') { ?>

                                    <div id="content_admin_users_user_userId_contactsInfo_do" class="formRow">
                                        <label>Действия</label>
                                        <div class="formRight">
                                            <a target="_blank" href="<?=base_url('login/index')?>?login=<?= $user->email ?>&password=<?= $user->user_pass ?>&no_check_capcha=1">Перейти в личный кабинет</a>
                                            <br/>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
    <?php if ($this->admin_info->permission == 'admin' || $this->admin_info->permission == 'root') { ?>
                                    <div id="content_admin_users_user_userId_contactsInfo_rate" class="formRow">
                                        <label> Рейтинг</label>
                                        <div class="formRight">
                                            <span style="margin-left: 20px;">Кредит. лимит DEBIT: $<?= $user->balance['max_loan_available_by_bonus'][6] ?></span>
                                            <span style="margin-left: 20px;">Кредит. лимит USD&#10084;: $<?= $user->balance['max_loan_available_by_bonus'][2] ?></span>
                                            <span style="margin-left: 20px;">Доступно DEBIT: $<?= $user->balance['payout_limit_by_bonus'][6] ?></span>
                                            <span style="margin-left: 20px;">USD&#10084;: $<?= $user->balance['payout_limit_by_bonus'][2] ?></span>
                                            <span style="margin-left: 20px;">Активный Р2Р: $<?= ( $user->currency_exchange_scores['total_processing_p2p_by_bonus'][6]+$user->currency_exchange_scores['total_processing_p2p_by_bonus'][2]) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
    <? } ?>
<? } ?>
                            </fieldset>
                            <fieldset id="w2confirmation" class="step">
                                <div class="formRow" style="overflow: auto;">
                                    <span class="wNavButtons" style="float:right;">
                                        <input class="basic" id="back2" value="Back" type="reset" />
                                        <input class="blueB ml10" id="next2" value="Next" type="submit" />
                                    </span>
                                </div>

                                <div class="formRow">
                                    <label> Тип положения</label>
                                    <div class="formRight">    <input type="radio"  id="smth1"  <?= ($user->face == 1) ? "checked='checked'" : "" ?> name="face" value="1" />Физ. лицо <br>
                                        <input type="radio"   id="smth1" <?= ($user->face == 2) ? "checked='checked'" : "" ?> name="face" value="2" />Юр. лицо<br></div>
                                    <div class="clear"></div>
                                </div>
<?/*?>
                                <div class="formRow">
                                    <label> Тип  клиента</label>
                                    <div class="formRight">    <input type="radio"  id="smth"  <?= ($user->vip == 1) ? "checked='checked'" : "" ?> name="vip" value="1" />Стандартный <br>
                                        <input type="radio"   id="smth" <?= ($user->vip == 2) ? "checked='checked'" : "" ?> name="vip" value="2" />Vip<br></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>ИНН</label>
                                    <div class="formRight"><input type="text" name="inn"  value="<?= $user->inn ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Серия</label>
                                    <div class="formRight"><input type="text" name="p_seria"  value="<?= $user->pasport_seria ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Номер</label>
                                    <div class="formRight"><input type="text" name="p_number"  value="<?= $user->pasport_number ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Дата выдачи</label>
                                    <div class="formRight"><input type="text" name="p_date"  value="<?= $user->pasport_date ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Код подразделения</label>
                                    <div class="formRight"><input type="text" name="p_kpd"  value="<?= $user->pasport_kpd ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>Кем выдан</label>
                                    <div class="formRight"><input type="text" name="p_kvn"  value="<?= $user->pasport_kvn ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Место рождения</label>
                                    <div class="formRight"><input type="text" name="p_born"  value="<?= $user->pasport_born ?>" /></div>
                                    <div class="clear"></div>
                                </div>
<?*/?>

                                <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Платежные системы</h6></div>
                                <div class="formRow">
                                    <label>Номер карты</label>
                                    <div class="formRight"><input type="text" name="bank_cc"  value="<?= $user->bank_cc ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Срок карты</label>
                                    <div class="formRight"><input type="text" name="bank_cc_date_off"  value="<?= $user->bank_cc_date_off ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>W1 (USD)</label>
                                    <div class="formRight">
                                        <input type="text" name="bank_w1"  value="<?= $user->bank_w1 ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>W1 (RUB)</label>
                                    <div class="formRight">
                                        <input type="text" name="bank_w1_rub"  value="<?= $user->bank_w1_rub ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>Payeer</label>
                                    <div class="formRight">
                                        <input type="text" name="bank_lava"  value="<?= $user->bank_lava ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>PerfectMoney</label>
                                    <div class="formRight">
                                        <input type="text" name="bank_perfectmoney"  value="<?= $user->bank_perfectmoney ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>OKpay</label>
                                    <div class="formRight">
                                        <input type="text" name="bank_okpay"  value="<?= $user->bank_okpay ?>" />
                                    </div>
                                    <div class="clear"></div>
                                </div>




                                <div class="formRow">
                                    <label> Осн. платежная система</label>
                                    <div class="formRight">

                                        <!-- <? //form_dropdown('place', get_region(), $user->place)  ?>  -->
                                        <?= form_dropdown('payment_default', get_payment_default(), $user->payment_default) ?>

                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Банковские данные</h6></div>
                                <div class="formRow">
                                    <label>Наименование банка</label>
                                    <div class="formRight"><input type="text" name="bank_name"  value="<?= $user->bank_name ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Номер счета</label>
                                    <div class="formRight"><input type="text" name="bank_schet"  value="<?= $user->bank_schet ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>БИК</label>
                                    <div class="formRight"><input type="text" name="bank_bik"  value="<?= $user->bank_bik ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Корр Счет</label>
                                    <div class="formRight"><input type="text" name="bank_kor"  value="<?= $user->bank_kor ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow center"> <h5>  Фактический адрес</h5>  <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Страна</label>
                                    <div class="formRight">
                                        <?= form_dropdown('place', get_country(), $user->place, '') ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">

                                    <label>Индекс</label>
                                    <div class="formRight"><input type="text" name="r_index"  value="<?= $adr_r->index ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Город</label>
                                    <div class="formRight"><input type="text" name="r_town"  value="<?= $adr_r->town ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Улица</label>
                                    <div class="formRight"><input type="text" name="r_street"  value="<?= $adr_r->street ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Дом</label>
                                    <div class="formRight"><input type="text" name="r_house"  value="<?= $adr_r->house ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>Корпус/строение</label>
                                    <div class="formRight"><input type="text" name="r_kc"  value="<?= $adr_r->kc ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Квартира</label>
                                    <div class="formRight"><input type="text" name="r_flat"  value="<?= $adr_r->flat ?>" /></div>
                                    <div class="clear"></div>
                                </div>

<?/*?>
                                <div class="formRow center"> <h5>  Адрес регистрации</h5>  <div class="clear"></div>
                                </div>

                                <div class="formRow">

                                    <label>Индекс</label>
                                    <div class="formRight"><input type="text" name="r_index"  value="<?= $adr_r->index ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Город</label>
                                    <div class="formRight"><input type="text" name="r_town"   value="<?= $adr_r->town ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Улица</label>
                                    <div class="formRight"><input type="text" name="r_street"  value="<?= $adr_r->street ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Дом</label>
                                    <div class="formRight"><input type="text" name="r_house"  value="<?= $adr_r->house ?>" /></div>
                                    <div class="clear"></div>
                                </div>

                                <div class="formRow">
                                    <label>Корпус/строение</label>
                                    <div class="formRight"><input type="text" name="r_kc"  value="<?= $adr_r->kc ?>" /></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="formRow">
                                    <label>Квартира</label>
                                    <div class="formRight"><input type="text" name="r_flat"  value="<?= $adr_r->flat ?>" /></div>
                                    <div class="clear"></div>
                                </div>
<?*/?>


                            </fieldset>


                            <div class="wizButtons">
                                <div class="status" id="status2"></div>
                                <span class="wNavButtons">
                                    <input class="basic" id="back2" value="Back" type="reset" />
                                    <span id="content_admin_users_user_userId_contactsInfo_submit"><input class="blueB ml10" id="next2" value="Next" type="submit" /></span>
                                </span>
                            </div>
                            <div class="clear"></div>
                        </form>
                        <div class="data" id="w2"></div>
                    </div>
                    <div class="clearfix"></div>
                    </div>

                    <div id="tab2" class="tab_content">
                        <div class="formRow">
                            <label>ФИО</label>
                            <div class="formRight"><?= $user->sername ?> <?= $user->name ?> <?= $user->patronymic ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Паспорт</label>
                            <div class="formRight">"<?= $user->pasport_seria ?><?= $user->pasport_number ?>", выдан "<?= $user->pasport_date ?>" в "<?= $user->pasport_kvn ?>" код подразд. "<?= $user->pasport_kpd ?>"</div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Адрес</label>
                            <div class="formRight"><? $country = get_country(); echo $country[$user->place]?>, <?= $adr_f->index ?>, <?= $adr_f->town ?>, <?= $adr_f->street ?>, <?= $adr_f->house ?>, <?= $adr_f->kc ?>, <?= $adr_f->flat ?></div>
                            <div class="clear"></div>
                        </div>

                        <? if (!empty($documents)) { ?>

                            <script src="/js/admin/document.js"></script>
                            <style>
                                .image{width: 350px}
                                .error_foto {text-align: center; width:400px}
                                .setting_panel{ display:inline-block; margin-bottom:5px}
                                .setting_panel > div.type {float: left; margin-top:4px; margin-right: 5px}
                                .tab .status_change{ display:inline-block; margin-bottom:5px}
                                .tab .status_change .type{float: left; margin-top:4px; margin-right: 5px}
                                .first_doc, .second_doc{width:400px}
                                .second_doc{margin-top: 20px}
                                .delete_foto a{margin-top: 5px}
                                .tab {margin-top: 20px}
                            </style>
                            <? if (!empty($sms)) echo $sms; ?>


                            <form   enctype='multipart/form-data' action="<?php base_url() ?>account/documents"  method="post">
                                <input  type="hidden"  value="<?= $user->id_user ?>" name="user"  id="user_id" />
                                <div class="setting_panel" >
                                    <div class="type" >Тип:</div>

                                    <?= form_dropdown('type', config_item('documents_admin_label'), array('1'), 'id="form_type"') ?>

                                </div>

                                <div>Загрузка документов:<br /><input type="file"  class="" id="foto"  name="foto"  value="33"></div>
                                <input type="submit" name="submit" value="Загрузить" />
                            </form>


                            <?
                            for ($i = 0; $i <= count(config_item('documents_admin_count')) - 1; $i++) {
                                $index = $i + 1;
                                $item = $documents[$i];
                                ?>

                                <div id="tub<?= $index ?>" class="tab" style="display:none">


                                    <? if (!empty($item->state)) { ?>
                                        <div class="first_doc" >
                                            <div class="status_change">
                                                <div class="type">Статус:</div>
                                                <span>
                                                    <select class="status" name="status">
                                                        <?=renderSelect(getDocumentsStatus(),$item->state)?>
                                                    </select>
                                                </span>
                                                <span class="save">
                                                    <a style="margin-left:4px;" class="button greenB">
                                                        <span >Сохранить</span>
                                                    </a>
                                                </span>
                                                <span class="load_status"></span>
                                            </div>
                                            <div id="delete_foto"></div>
                                            <div class="error_foto"></div>
                                            <br />
                                            <?
                                            if (!empty($item->img2)) {
                                                echo'<span class="old_agree">Старая одобренная<br /></span>';
                                            }
                                            ?>
                                            <? if (!empty($item->img)) echo'<div class="document"><a href="https://webtransfer.com/opera/users/doc/' . $index . '/' . $user->id_user . '" target="_blank"><img  class="image" src="https://webtransfer.com/opera/users/doc/' . $index . '/' . $user->id_user . '" /></a></div>'; ?>
                                            <? if ($item->state != 0) echo"<div class='delete_foto'><a class=\"button redB \"><span>Удалить</span></a></div>" ?>
                                        </div>

                                        <? if (!empty($item->img2)) { ?>
                                            <div class="second_doc" >
                                                <div>Новая для рассмотрения</div>
                                                <div class="status_change">
                                                    <div class="type">Действия:</div>
                                                    <span>
                                                        <select class="status" name="status">
                                                            <option value="2">Принять</option>
                                                            <option value="3">Отклонить</option>
                                                        </select>
                                                    </span>
                                                    <span class="save">
                                                        <a style="margin-left:4px;" class="button greenB">
                                                            <span >Сохранить</span>
                                                        </a>
                                                    </span>
                                                    <span class="load_status"></span>
                                                </div>


                                                <a href="https://webtransfer-finance.com/opera/users/doc/'.$index.'/'.$user->id_user.'" target="_blank"><img  class="image" src="https://webtransfer.com/opera/users/doc/<?= $index ?>/<?= $user->id_user ?>/2" /></a>
                                                <div class="delete_foto"><a class="button redB" title=""><span>Удалить</span></a></div>
                                            </div>
                                        <? } ?>
                                        <?
                                    }
                                    else
                                        echo"Документ не загружен";
                                    ?>
                                </div>
                            <? } ?>
                        </div>


                    <? } ?>     </div>


                <div class="clearfix"></div>
                            <div id="tab31" class="tab_content">
                                <div class="user_balance">

                                    <table width="100%" cellpadding="5" cellspacing="5" style="">
                                    <thead><td></td><td>WT DEBIT</td><td>WTUSD1</td><td>WTUSD-H</td><td>C-CREDS</td><td>P-CREDS</td><td>B-CREDS</td><td>TOTAL</td></thead>
                                    <tbody>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>A. Полученные гарантии</td>
                                        <td><?=$user->balance['garant_received']?></td>
                                                                                                            <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>B. Выданные гарантии</td>
                                        <td><?=$user->balance['garant_issued']?></td>
                                                                                                            <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>1. Сумма пополнений</td>
                                        <td><?=$user->balance['money_sum_add_funds_by_bonus'][6]?>&nbsp;[CRD: <?=$user->balance['money_sum_add_card_by_bonus'][6]?>]</td>
                                                                                                            <td><?=$user->balance['money_sum_add_funds_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_add_funds_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_add_funds_by_bonus'][4]?></td>
                                        <td><?=$user->balance['money_sum_add_funds_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_add_funds_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_add_funds']?></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>2. Сумма входящих (вкл. P2P)</td>
                                        <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][6]?></td>
                                                                                                            <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][4]?></td>
                                        <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_transfer_from_users_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_transfer_from_users']?></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>3. Прием P2P (Вкл. в п.2):</td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][6]?></td>
                                                                                                             <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][5]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][2]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][4]?></td>
                                       <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][3]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_from_users_by_bonus'][1]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_from_users']?></td>
                                    </tr>

                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>4. Прием мерчант / обменник:</td>
                                        <td><?=$user->balance['income_merchant_send_by_bonus'][6]?></td>
                                                                                                              <td><?=$user->balance['income_merchant_send_by_bonus'][5]?></td>
                                        <td><?=$user->balance['income_merchant_send_by_bonus'][2]?></td>
                                        <td><?=$user->balance['income_merchant_send_by_bonus'][4]?></td>
                                        <td><?=$user->balance['income_merchant_send_by_bonus'][3]?></td>
                                        <td><?=$user->balance['income_merchant_send_by_bonus'][1]?></td>
                                        <td><?=$user->balance['income_merchant_send']?></td>
                                    </tr>


                                    <tr id="sum_5" style="padding:10px;border-bottom:1px dotted #ddd;"><td>5. Сумма партнерскиx вознаграждений:</td>
                                    <td><?=table_value_format($bonus_rating['sum_partner_reword_by_bonus'][6])?></td>
                                        <td><?=table_value_format($bonus_rating['sum_partner_reword_by_bonus'][5])?></td>
                                        <td><?=table_value_format($bonus_rating['sum_partner_reword_by_bonus'][2])?></td>
                                        <td><?=table_value_format($bonus_rating['sum_partner_reword_by_bonus'][4])?></td>
                                        <td><?=$user->balance['pcreds_income_after_0112']?> (P) + <?=$user->balance['bonus_earned_by_bonus'][3]?> (B)</td>
                                        <td><?=table_value_format($bonus_rating['sum_partner_reword_by_bonus'][1])?></td>
                                        <td><?=table_value_format($bonus_rating['sum_partner_reword'])?></td>
                                    </tr>
                                    <tr id="sum_6" style="padding:10px;border-bottom:1px dotted #ddd;"><td>6. Прибыль от вкладов</td>
                                        <td colspan="7"> загрузка .... (~2мин)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
<!--                                        <td><?//table_value_format($bonus_rating[6]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating[5]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating[2]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating[4]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating[3]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating[1]['credit_income'])?></td>
                                        <td><?//table_value_format($bonus_rating['total']['credit_income'])?></td>-->
                                    </tr>
                                    <tr id="sum_7" style="padding:10px;border-bottom:1px dotted #ddd;display:none"><td>7. Прибыль из архива:</td>
                                        <td colspan="7"> загрузка .... (~2мин)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
<!--                                        <td></td>
                                        <td><?//table_value_format($bonus_rating[5]['my_inoutcome_money_acrhive'])?></td>
                                        <td><?//table_value_format($bonus_rating[2]['my_inoutcome_money_acrhive'])?></td>
                                        <td><?//table_value_format($bonus_rating[4]['my_inoutcome_money_acrhive'])?></td>
                                        <td><?//table_value_format($bonus_rating[3]['my_inoutcome_money_acrhive'])?></td>
                                        <td><?//table_value_format($bonus_rating[1]['my_inoutcome_money_acrhive'])?></td>
                                        <td><?//table_value_format($bonus_rating['total']['my_inoutcome_money_acrhive'])?></td>-->
                                    </tr>

                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>8. Вывод через мерчант / обменник:</td>
                                        <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][6]?></td>
                                                                                                            <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][4]?></td>
                                       <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_merchant_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_merchant']?></td>
                                    </tr>


                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>9. Сумма исходящих переводов (вкл.P2P):</td>
                                        <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][6]?></td>
                                                                                                              <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][4]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_users_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_transfer_to_users']?></td>
                                    </tr>
                                                                                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>10. Вывод P2P:</td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][6]?></td>
                                                                                                             <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][5]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][2]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][4]?></td>
                                       <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][3]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_to_users_by_bonus'][1]?></td>
                                        <td><?=$user->balance['p2p_money_sum_transfer_to_users']?></td>
                                    </tr>

                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>11. Сумма произведенных выводов:</td>
                                        <td><?=$user->balance['money_sum_withdrawal_by_bonus'][6]?></td>
                                                                                                                <td><?=$user->balance['money_sum_withdrawal_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_withdrawal_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_withdrawal_by_bonus'][4]?></td>
                                        <td><?=$user->balance['money_sum_withdrawal_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_withdrawal_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_withdrawal']?></td>
                                    </tr>
                                            <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>12. Комиссии / штрафы:</td>
                                        <td></td> <td></td>
                                        <td></td>
                                        <td></td>
                                       <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                            <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>13. Внутренние корректировки:</td>
                                        <td></td> <td></td>
                                        <td></td>
                                        <td></td>
                                       <td></td>
                                        <td></td>
                                        <td></td>
        </tr>
                                            <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>14. Текущий остаток на счетах:</td>
                                        <td><?=$user->balance['payment_account_by_bonus'][6]?></td>
                                                                                                            <td><?=$user->balance['payment_account_by_bonus'][5]?></td>
                                        <td><?=$user->balance['payment_account_by_bonus'][2]?></td>
                                        <td><?=$user->balance['payment_account_by_bonus'][4]?></td>
                                       <td><?=$user->balance['payment_account_by_bonus'][3]?></td>
                                        <td><?=$user->balance['payment_account_by_bonus'][1]?></td>
                                        <td></td>
         </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>15. Сумма на выводе</td>
                                        <td><?=$user->balance['total_processing_payout_sum_by_bonus'][6]?></td>
                                                                                                              <td><?=$user->balance['total_processing_payout_sum_by_bonus'][5]?></td>
                                        <td><?=$user->balance['total_processing_payout_sum_by_bonus'][2]?></td>
                                        <td><?=$user->balance['total_processing_payout_sum_by_bonus'][4]?></td>
                                        <td><?=$user->balance['total_processing_payout_sum_by_bonus'][3]?></td>
                                        <td><?=$user->balance['total_processing_payout_sum_by_bonus'][1]?></td>
                                        <td><?=$user->balance['total_processing_payout']?></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>16. Сумма на выводе (БАНК)</td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][6]?></td>
                                                                                                             <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][5]?></td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][2]?></td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][4]?></td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][3]?></td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank_by_bonus'][1]?></td>
                                        <td><?=$user->balance['money_sum_process_withdrawal_bank']?></td>
                                    </tr>
                                    <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>17. Заявки на вывод P2P:</td>
                                        <td><?=$user->balance['total_processing_p2p_by_bonus'][6]?></td>
                                                                                                            <td><?=$user->balance['total_processing_p2p_by_bonus'][5]?></td>
                                        <td><?=$user->balance['total_processing_p2p_by_bonus'][2]?></td>
                                        <td><?=$user->balance['total_processing_p2p_by_bonus'][4]?></td>
                                       <td><?=$user->balance['total_processing_p2p_by_bonus'][3]?></td>
                                        <td><?=$user->balance['total_processing_p2p_by_bonus'][1]?></td>
                                        <td><?=$user->balance['total_processing_p2p']?></td>
                                    </tr>
                                            <tr style="padding:10px;border-bottom:1px dotted #ddd;"><td>18. Credit Limit:</td>
                                        <td><?=$user->balance['payout_limit_by_bonus'][6]?></td> <td></td>
                                        <td></td>
                                        <td></td>
                                       <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                    </table>


                                    <div class="formRow"></div>
<!--                                    <div class="formRow">
                                        <label>1. Сумма пополнений</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_add_funds']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2. Сумма входящих переводов:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_transfer_from_users']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3. Сумма партнерскиx вознаграждений:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['sum_partner_reword']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="formRow">
                                        <label>4. Прибыль от вкладов:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->credit_income) ?></span>
                                            <span style="font-size: 10px"> (<?=$user->credit_income_3?>)</span>
                                            <? //print_r($user->credit_income_2);?>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5. Прибыль из архива:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->archive_sum) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="formRow">
                                        <label>6. Сумма исходящих переводов:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_transfer_to_users']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>7. Сумма произведенных выводов</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_withdrawal_minus_type65']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>8. Сумма на выводе</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_process_withdrawal']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>10. Сумма на выводе по банку</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['money_sum_process_withdrawal_bank']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>11. Коэффициент соц. интеграции</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['social_integration_value']?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>12. Сумма зачислений от обменников</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['money_sum_transfer_from_exch']?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>13. Внутренние перечисления</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['income_internal_sends']-$user->balance['outcome_internal_sends'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>14. Покупки через мерчант(расход)</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['outcome_merchant_send']-$user->balance['income_merchant_return'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>15. Продажа через мерчант(прибыль)</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['income_merchant_send']-$user->balance['outcome_merchant_return'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Итого:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double(($user->balance['money_sum_add_funds']
                                                          + $user->balance['money_sum_transfer_from_users']
                                                          + $user->balance['sum_partner_reword']
                                                          + $user->credit_income
                                                          + $user->archive_sum)
                                                        - ($user->balance['money_sum_transfer_to_users']
                                                          + $user->balance['money_sum_withdrawal']
                                                          + $user->balance['money_sum_process_withdrawal'])) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Комиссии, штрафы(мелкие расходы)</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['fee_or_fine']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Стандарные займы</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->credit_standart_income) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>-->





                                </div>
                            </div>
                <div id="tab32" class="tab_content">
                                <div class="user_balance">
                                    <div class="formRow">
                                        <label>1. Количество партнерских с разных ID:</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['partner_unic_id_count'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2. Коэфицент диверсификации:</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['diversification_coeff'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3. Сумма партнерских за неделю:</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['partner_week_contribution'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>4. Количество партнерских отчислений:</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['partner_contribution_count'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5. Средняя сумма партнерского отчисления:</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['average_partner_contribution'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>6. Степень Диверсификации:</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['diversification_degree'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>7. Коэфицент оценки партнерской сети:</label>
                                        <div class="formRight">
                                            <span><?= $user->balance['partner_network_valuation_coeff'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>8. Новый кредитный лимит:</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['new_max_loan_available'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>9. Максимально-доступный арбитраж:</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['max_arbitrage_calc'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>10. Арбитраж к списанию:</label>
                                        <div class="formRight">
                                            <span>$ <?= $user->balance['pay_off_arbitration'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab3" class="tab_content">
                                <div class="user_balance">
                                    <div class="formRow"></div>

                                    <div class="formRow balance_paragraf">
                                        <label>1. Активы</label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>1.1 Платежный счет</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance[payment_account], TRUE)?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>1.2 Бонусный счет</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['bonuses'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Итого кошелек:</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['payment_account']+$user->balance['bonuses'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="formRow">
                                        <label>1.3 Вложения "Гарант"</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['investments_garant']+$user->balance['investments_garant_bonuses'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>1.4 Прибыль на дату "Гарант"</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['my_investments_garant_percent'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>1.5 Вложения "Стандарт"</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['investments_standart'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Итого вложения:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['investments_standart']+$user->balance['investments_garant']+$user->balance['investments_garant_bonuses'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>Итого Активы:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['total_assets']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow balance_paragraf">
                                        <label>2. Обязательства</label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2.1 Займы полученные</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['loans']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2.2 Проценты на выплату</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['loans_percentage'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2.3 Бонусы полученные</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['bonuses']+$user->balance['investments_garant_bonuses']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2.4 Кредит на Арбитраж</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['total_arbitrage'] )?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>2.5 Проценты по просроченным займам Гарант</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['overdue_garant_interest'], TRUE )?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>


                                    <div class="formRow">
                                        <label>Итого обязательства:</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['all_liabilities']+$user->balance['bonuses']+$user->balance['investments_garant_bonuses'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow balance_paragraf">
                                        <label>3. Прибыль</label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3.1 Ожидаемая прибыль по вкладам</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['total_future_income'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3.2 Ожидаемая партнерская прибыль</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['soon'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3.3 Ожидаемые отчисления</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double($user->balance['future_interest_payout'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>3.4 Ожидаемая чистая прибыль</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['future_income']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow balance_paragraf">
                                        <label>4. Мой баланс (перспективный)</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['balance'] )?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow balance_paragraf">
                                        <label>5. Счета обеспечения и гарантий</label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5.1 Поручительства Гарантийного фонда</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double($user->balance['total_garant_loans']) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5.2 Качество партнерской сети</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['partner_network_value'] )?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5.3 Обеспечение по полученным кредитам на Арбитраж</label>
                                        <div class="formRight">
                                            <span>$ <?= price_format_double( $user->balance['arbitrage_collateral'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>5.4 Кредитный лимит Гарант</label>
                                        <div class="formRight">
                                            <span>$ <?=price_format_double( $user->balance['max_loan_available'], TRUE )?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                    <div class="formRow balance_paragraf">
                                        <label>6. Займы</label>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>6.1 Просроченные стандартные займы</label>
                                        <div class="formRight">
                                            <span><?= price_format_double($user->balance['overdue_standart_count'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="formRow">
                                        <label>6.2 Просроченные гарантированные займы</label>
                                        <div class="formRight">
                                            <span><?= price_format_double($user->balance['overdue_garant_count'])?></span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                        <div class="data" id="w2"></div>
                    </div>
                <div id="tab4" class="tab_content">
                    <h3>социальные  сети</h3>
                    <? foreach ($social as $item) { ?>
                        <div style="margin:10px;  border:1px solid grey; border-radius:6px; padding:5px;  display:  inline-block">
                            <p><img src="/images/icons/<?= socialList($item->name) ?>"></p>
                            <img width='50px' src="<?= $item->foto ?>" />
                            <p><a href="<?= $item->url ?>" onclick="return !window.open(this.href)">Посмотреть профиль</a></p>
                        </div>
                    <? } ?>
                </div>


                <div id="tab5" class="tab_content">
                    <div id="table_credits" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab51" class="tab_content">
                    <div id="table_invests" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab52" class="tab_content">
                    <a href="#" onclick="$('#dialog_cards_transes').dialog( 'open' );return false;">Карточные транзакции</a>

                    <style>
                        .only-here #popup_preference, #popup_payment, #popup_debit, .password_change, .popup_window {
                            text-align: center;
                            display: none;
                            position: fixed;
                            padding-top: 25px;
                            top: 25%;
                            background: none repeat scroll 0% 0% white;
                            z-index: 1000;
                            left: 30%;
                            width: 40%;
                            padding: 10px;
                            border-radius: 5px 5px 5px 5px;
                            box-shadow: 0px 0px 10px rgba(0,0,0,0.5);
                        }


                        .only-here .close, .vivod13 {
                            position: absolute;
                            background: url("../../img/close.png") no-repeat 0 0;
                            width: 24px;
                            height: 20px;
                            background-size: contain;
                            right: 5px;
                            top: 10px;
                            cursor: pointer;
                        }



                         .only-here .popup_window h2 {
                            border-bottom: 1px solid #EEEEEE;
                            padding-bottom: 13px !important;
                            margin-bottom: 17px;
                            font-size: 22px;
                            color: #343739;
                            font-weight: 600;
                            font-family: 'Open Sans',"Arial","Helvetica",sans-serif !important;
                        }
                         .only-here h2 {
                            font: 24px/40px 'Lucida Grande','Lucida Sans Unicode',Arial;
                            color: #FF5300;
                            margin-bottom: 7px;
                        }

                         .only-here #filter {
                            display: block;
                            background-color: #FCFCFC;
                            border-bottom: 1px solid #FFFFFF;
                            padding: 7px;
                        }
                        .only-here  #filter {
                            height: 60px;
                        }

                        .only-here label {
                            margin-right: 14px;
                        }

                        .only-here  line {
                            padding-left: 40px;
                            padding-right: 10px;
                            display: inline-block;
                            text-align: left;
                        }
                        .only-here  #filter input[type="text"] {
                            border: 1px solid #999999;
                            padding: 4px;
                            margin-left: 3px;
                            color: #999999;
                            width: 112px;
                            text-align: center;
                        }
                        .only-here  #filter input[type="radio"] {
                            border: 1px solid #999999;
                            margin-left: 10px;
                        }
                        .only-here  .button {
                                display: block;
                                border: medium none;
                                width: 250px;
                                height: 53px;
                                color: #FFF;
                                background: none repeat scroll 0% 0% #3390EE;
                                cursor: pointer;
                                border-radius: 4px;
                                outline: none;
                                margin: 14px auto 5px;
                                margin-bottom: 32px !important;
                                font-size: 18px;
                                font-family: sans-serif;
                                line-height: 24px;
                                padding: 15px;
                                text-transform: none !important;
                                max-height: 100px;
                        }

                        .ui-datepicker{
                            z-index: 17 !important;
                        }


                        .only-here  #popup_preference .button, #popup_payment .button, #popup_debit .button, .button, #popup_invest .button, .password_change .button, .popup_window .button {
                            margin: 20px auto;
                            left: 0!important;
                        }
                    </style>
                    <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
                    <link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
                    <link href="/css/calendar-tt.css" rel="stylesheet">
                    <script src="/js/jquery-ui-1.10.4.custom.js"></script>


                    <!--popup-->
                    <div class="excel_export" style="display: none; z-index: 16; background-color: #000000; position: fixed; top: 0px; bottom: 0px; left: 0px; right: 0px; opacity: 0.7;"> </div>
                    <div class="popup_window excel_export only-here" style="display: none; z-index: 17 !important; line-height: 27px; transition: 3s;">
                        <div class="close" onclick="$('.excel_export').hide();" style="z-index: 7;    opacity: 1;"></div>
                        <div style="position: relative; height: 100%; width: 100%; z-index: 6">
                            <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
                                <img class="loading-gif" style="margin-top: 100px" src="/images/loading.gif">
                            </div>
                            <h2><?=_e('accaunt/transaction_11')?></h2>
                            <p></p>


                            <?=_e('accaunt/transaction_12')?>
                                    <form method="POST" action="/opera/users/export_transactions_user" style="text-align:left; padding: 9px;">
                                        <?if(isset($arhive)){?><input type="hidden" name="arhive" value="arhive" /><?}?>
                                        <div id="filter" style="text-align: center;">
                                                <line><?=_e('accaunt/transaction_16')?></line>
                                                <input type="text" name="date_1" id="date_1"  autocomplete="off" value="06/01/2014"  />
                                                <line><?=_e('accaunt/transaction_17')?></line>
                                                <input type="text" name="date_2" id="date_2" autocomplete="off" value="<?=date('d/m/Y')?>"  />
                                                <br />

                                                <label for="pop_6"><input id="pop_6" type="radio" name="type" value="6" /> <?=_e('DEBIT')?></label>
                                                <label for="pop_1"><input id="pop_1" type="radio" name="type" value="5" /> <?=_e('WTUSD1')?></label>
                                                <label for="pop_2"><input id="pop_2" type="radio" name="type" value="2" /> <?=_e('WTUSD&#10084;')?></label>
                                                <label for="pop_3"><input id="pop_3" type="radio" name="type" value="3" /> <?=_e('P-CREDS')?></label>
                                                <label for="pop_4"><input id="pop_4" type="radio" name="type" value="4" /> <?=_e('C-CREDS')?></label>
                                                <label for="pop_5"><input id="pop_5" type="radio" name="type" value="1" /> <?=_e('BONUS')?></label>
                                        </div>
                                        <p></p>
                                        <button class="button" type="submit"><?=_e('accaunt/transaction_15')?></button>
                                        <input type="hidden" name="export_user_id" value=" <?=$user->id_user;?>" >
                                </form>
 <script>
                        $("#date_1").datepicker();
                        $("#date_2").datepicker();
                    </script>
                        </div>
                    </div>
                    <!--popup-->




















                    <a href="#" onclick="$('.excel_export').show(); return  false;" style="float: right; margin-right: 5px; margin-bottom: 7px;">
                        <img src="/images/excel.png" style="vertical-align : middle;">
                    </a>

                    <div id="table_transactions" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab521" class="tab_content">
                    <a class="button redB my" href="<? base_url() ?>opera/users/blockChilds/<?=$user->id_user;?>" onclick="return yes_no('Вы уверены что хотите заблокировать младших партнеров?');">Заблокировать младших</a>
                    <div id="table_partners" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab8" class="tab_content">
                    <div id="table_credits_archive" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab81" class="tab_content">
                    <div id="table_invests_archive" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab82" class="tab_content">
                    <div id="table_transactions_archive" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Загрузка...
                        </div>
                    </div>
                </div>
                <div id="tab53" class="tab_content">
                    <div style="clear: both;">
                        <? foreach ($admin_note as $note) {?>
                        <p><?=date("d.m.Y", strtotime($note->date))?> (<?=$note->id_admin?>): <?=$note->note?></p>
                        <?}?>
                        <form method="post">
                            <textarea name="note" style="width:100%"></textarea><br/><br/>
                            <center><input type="submit" name="submit_admin_coment" value="Отправить"/></center>
                        </form>
                    </div>
                </div>
                <div id="tab6" class="tab_content">

                    <form id="validate2" class="form" action="<?php base_url() ?>opera/users/send"  method="post">
                        <input type="hidden" id="submited" name="submited" value="1"/>
                        <fieldset>
                            <div class="widget">
                                <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Написать клиенту</h6></div>
                                <div class="formRow">
                                    <label>Тема:<span class="req">*</span></label>
                                    <div class="formRight"><input type="text" id="title" class="validate[required]" name="subject" value=""></div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                            <div class="widget" id="WYSIWYG">
                                <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Тект</h6></div>
                                <textarea  style="width:100%; height: 300px;" name="text" rows="" cols="">
                Best regards,
                <b>".<?=$GLOBALS["WHITELABEL_NAME"] ?>." Team</b>


                <a href="mailto:support@webtransfer.com">support@webtransfer.com</a>
                <a href="http://<?= base_url_shot() ?>">http://www.<?= base_url_shot() ?></a>

                <img src="https://<?= base_url_shot() ?>/img/logo13.gif">
                                </textarea>
                            </div>
                            <center id="answer2" style="display:none">Письмо отправлено</center>

                            <center>	<a class="wButton greenwB ml15 m10" id="submit_users_email" title="" href="#"><span>Отправить</span></a>

                            </center>
                        </fieldset>
                    </form>
                    <script>
                                $('#submit_users_email').click(function() {
                                    $("#validate2").ajaxSubmit({
                                        url: "/opera/users/send/<?= $user->id_user ?>",
                                        success: function() {
                                            $('#answer2').fadeIn();
                                            setTimeout(function() {
                                                $("#answer").fadeOut()
                                            }, 500);
                                        }
                                    });
                                    return false;
                                })</script>
                </div>
                <div id="graph" class="tab_content">
                    <div class="dateFromTo">
                        Начало переода <input type="text"  name="dateFrom"> <!--по <input type="text" name="dateTo"-->&nbsp;
                        Максимальный уровень: <input type="text" name="graphLevelLimit" value="<?=get_sys_var('MAX_USER_TRANS_GRAPH_LEVEL')?>" style="width: 100px">
                        &nbsp;<button class="button greenB" onclick="loadGraph()">Показать</button>&nbsp;<span style="display:none" id="graphProgressLoad"><b>Идет загрузка...</b></a>
                    </div>

                     <div id="myDiagram" style="background-color: whitesmoke; z-index: 1; border: solid 1px black; width: 100%; height: 600px"></div>
                </div>
            </div>
<? } ?>
        </div>
    </div>
    <?
}?>


    <div id="dialog_cards_transes" style="display: none">
        <select id="cards_list" class="noUniform" onchange="load_card_trans()" style='width: 250px'>
            <option value='-'>-</option>
            <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
               <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-summ="<?=($card->last_balance)?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
            <? } ?>
        </select><br>
        <div style="width:660px; height: 500px" id='cards_log'>


        </div>

    </div>
    <script>
        $("#dialog_cards_transes").dialog({
            autoOpen: false,
            height: 600,
            width: 700,
            dialogClass: "alert",
            title:"Карточные транзакции",
            buttons: [{
                text: "Закрыть",
                click: function() {
                    $("#dialog_cards_transes").dialog( "close" )
                }
            }]
        });

        function load_card_trans(){
            var card_id= $('#cards_list').val();
            if ( card_id == '-')
                return;

              $('#cards_log').html('Загрузка...');
            //Строка адреса
            $.ajax({
                    type: "POST",
                    url: "/opera/users/<?=$user->id_user?>",
                    data: {action:'load_card_history', card_id:card_id },
                    success: function(data){
                            $('#cards_log').html(data);
                    }
            });
        }

    </script>

    <script type="text/javascript" src="/js/user/security_module.js?v=20160121"></script>

<? $this->load->view('admin/blocks/user_block_window.php'); ?>

<script>

    function save_p2p_vip_orders_call_back( result )
    {
        if( result['error'] )
        {
            alert( result['error'] );
            return;
        }

        if( result['success'] )
        {
            alert( result['success'] );
            return;
        }
        alert( _e('Сервер вернул неверный ответ. Обратитесь в отдел разработки ПО.') );
    }

    function save_p2p_vip_orders( el )
    {
        var h_el = $(el);

        var user_id = $('#user_id').val();

        if( !h_el || !user_id )
        {
            alert( _e('Ошибка в форме сохранения данных. Обратитесь в отдел разработки ПО.'), 7000 );
            return false;
        }

        setTimeout(function(){
            var value = ( h_el.parent().hasClass('checked') ? 1 : 0 );

            var data = { user_id : user_id, p2p_vip_orders : value };


            mn.get_ajax('/opera/users/save_p2p_vip_orders' ,data, save_p2p_vip_orders_call_back);
        }, 500);
    }
    function save_max_count_p2p_wt_orders_call_back( result )
    {
        if( result['error'] )
        {
            alert( result['error'] );
            return;
        }

        if( result['success'] )
        {
            alert( result['success'] );
            return;
        }
        alert( _e('Сервер вернул неверный ответ. Обратитесь в отдел разработки ПО.') );
    }

    function save_max_count_p2p_wt_orders( el )
    {
        var h_el = $(el);

        var user_id = $('#user_id').val();

        if( !h_el || !user_id )
        {
            alert( _e('Ошибка в форме сохранения данных. Обратитесь в отдел разработки ПО.'), 7000 );
            return false;
        }

        setTimeout(function(){
            var value = ( h_el.parent().hasClass('checked') ? 1 : 0 );

            var data = { user_id : user_id, save_max_count_p2p_wt_orders : value };


            mn.get_ajax('/opera/users/save_max_count_p2p_wt_orders' ,data, save_max_count_p2p_wt_orders_call_back);
        }, 500);
    }

    function restartPage(){
        window.location.reload();
    }
    $('.dd-selected').click(function() {
        if ($('.dd-select').hasClass('blocked'))
            return false;
        $('.dd-options').toggleClass('dd-opened');
    });

    $('.dd-option').click(function() {
        if (!$('.dd-select').hasClass('blocked'))
            setCode(this);
        $('.dd-options').toggleClass('dd-opened');
    });

    function setCode(el) {
        var res_code = $(el).data('code') + '',
                short_name = $(el).data('short') + '',
                code = res_code.trim();

        $('.dd-options .dd-option-selected').removeClass('dd-option-selected');
        $(el).addClass('dd-option-selected');

        $('.dd-selected-text').html('+' + code);
        $('.dd-selected-code-value').val(code);
        $('.dd-selected-short-name-value').val(short_name);
    }

    if ($('.dd-options .dd-option-selected').length == 1) {
        setCode('.dd-options .dd-option-selected');
    }

    $().ready(function () {
        $("#main_window_widget").show();
        $('.dateFromTo input[name=dateFrom]').datetimepicker({dateFormat:'dd.mm.yy', format: 'd.m.Y H:i', lang:'ru', mask: true});
        $('.dateFromTo input[name=dateTo]').datetimepicker({dateFormat:'dd.mm.yy', format: 'd.m.Y H:i', lang:'ru', mask: true});

        $('.dateFromTo input[name=dateFrom]').val('<?=date('d.m.Y 00:00')?>');
        $('.dateFromTo input[name=dateTo]').val('<?=date('d.m.Y 23:59')?>');

        $("#tb31").click(function(){
            var count = 0;
            var bonus_rating;
            function addRes(data){
                bonus_rating = $.extend(true, {},bonus_rating, data);
                count--;
                if(0 == count){
                    render(bonus_rating);
                }
            };
            var i = 1;
            function getData(i){
                count++;
                $.get('/opera/users/user_sum_by_bonus_get/<?=$user->id_user?>/'+i, function(d){
                    i++;
                    if(6 >= i) getData(i);
                    addRes(d.bonus_rating);
                    console.log(i);
                }, 'json');
            }
            getData(i);

            var moneyFormat = "0,0.00";
            function render(bonus_rating){
                delete(bonus_rating.total);
                var td6 = "<td>6. Прибыль от вкладов</td>";
                var total = 0;
                for(var i = 6; i >= 1; i--){
                    td6 += "<td data-bonus='"+i+"'>"+numeral(bonus_rating[i].credit_income).format(moneyFormat)+"</td>";
                    total += bonus_rating[i].credit_income;
                }
                td6 += "<td>"+numeral(total).format(moneyFormat)+"</td>";
                total = 0;
                var td7 = "<td>7. Прибыль из архива:</td>";
                for(var i = 6; i >= 1; i--){
                    td7 += "<td data-bonus='"+i+"'>"+numeral(bonus_rating[i].my_inoutcome_money_acrhive).format(moneyFormat)+"</td>";
                    total += bonus_rating[i].my_inoutcome_money_acrhive;
                }
                td7 += "<td>"+numeral(total).format(moneyFormat)+"</td>";
                $('#sum_6').html(td6);
                $('#sum_7').html(td7);
            }
        });
        $("#tb5").click(function(){$("#table_credits").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_cretits?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_cretits?>",
                    method: "post",
                    onLoad: function(){rowClick("#table_credits ");}
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb51").click(function(){$("#table_invests").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_invests?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_invests?>",
                    method: "post",
                    onLoad: function(){rowClick("#table_invests ");}
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb52").click(function(){$("#table_transactions").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_transactions?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_transactions?>",
                    method: "post",
                    onLoad: function(){renderRows()}
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb521").click(function(){$("#table_partners").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_partners?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    reg_date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_partners?>",
                    method: "post",
                    onLoad: function(){rowClickPartners();}
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb8").click(function(){$("#table_credits_archive").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_cretits_archive?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_cretits_archive?>",
                    method: "post",
                    onLoad: function(){rowClick("#table_credits_archive ");}
                }
            },
    //            logger: {
    //                options: {
    //                    debug: true
    //                }
    //            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb81").click(function(){$("#table_invests_archive").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url_invests_archive?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true,
                    search: false
                }
            },
            order: {
                options: {
                    date: "desc"
                }
            },
            source: {
                options: {
                    url: "<?=$url_invests_archive?>",
                    method: "post",
                    onLoad: function(){rowClick("#table_invests_archive ");}
                }
            },
    //            logger: {
    //                options: {
    //                    debug: true
    //                }
    //            },
            formatter: {
                name: "advanced",
                // this is the default config, we can override it in definition config
                options: {
                    widget: 'string',
                    widget_options: {
                        escape: true
                    }
                }
            }
        });});
        $("#tb82").click(function(){$("#table_transactions_archive").dtable({
        template: {
            options: {
                view_dir: '/js/DTable/views',
                table_template: 'table.html',
                rows_template: 'rows.html',
                pagination_template: 'pagination.html'
            }
        },
        definition: {
            options: {
                url: "<?=$url_transactions_archive?>",
                method: "post",
                data: {def: "1"},
                timestamp: true,
                search: false
            }
        },
        order: {
            options: {
                date: "desc"
            }
        },
        source: {
            options: {
                url: "<?=$url_transactions_archive?>",
                method: "post",
                onLoad: function(){renderRows()}
            }
        },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
        formatter: {
            name: "advanced",
            // this is the default config, we can override it in definition config
            options: {
                widget: 'string',
                widget_options: {
                    escape: true
                }
            }
        }
    });
    });

    $("#tbgraph").click(initGraph());


    });


    function initGraph(){
        var $go = go.GraphObject.make;  // for conciseness in defining templates

        myDiagram =
          $go(go.Diagram, "myDiagram",  // must name or refer to the DIV HTML element
            {
              // start everything in the middle of the viewport
              initialContentAlignment: go.Spot.Center,
              // have mouse wheel events zoom in and out instead of scroll up and down
              "toolManager.mouseWheelBehavior": go.ToolManager.WheelZoom,
              // support double-click in background creating a new node
              //"clickCreatingTool.archetypeNodeData": { text: "new node" },
              // enable undo & redo
              //"undoManager.isEnabled": true
              "relinkingTool.isEnabled": false,
              "panningTool.isEnabled": false,
              "clickSelectingTool.isEnabled": false,
              "linkingTool.isEnabled": false
            });


    // To simplify this code we define a function for creating a context menu button:
    function makeButton(text, action, visiblePredicate) {
      return $go("ContextMenuButton",
               $go(go.TextBlock, text),
               { click: action },
               // don't bother with binding GraphObject.visible if there's no predicate
               visiblePredicate ? new go.Binding("visible", "", visiblePredicate).ofObject() : {});
    }
    // a context menu is an Adornment with a bunch of buttons in them
    var partContextMenu =
      $go(go.Adornment, "Vertical",
          makeButton("Профиль",
                     function(e, obj) {  // OBJ is this Button
                       var contextmenu = obj.part;  // the Button is in the context menu Adornment
                       var part = contextmenu.adornedPart;  // the adornedPart is the Part that the context menu adorns
                       // now can do something with PART, or with its data, or with the Adornment (the context menu)
                       if (part instanceof go.Link) ;/*alert(linkInfo(part.data));*/
                       else if (part instanceof go.Group) ;/*alert(groupInfo(contextmenu));*/
                       else window.open('/opera/users/'+part.data.id, '_blank');
                     })
      );

        // when the document is modified, add a "*" to the title and enable the "Save" button
        myDiagram.addDiagramListener("Modified", function(e) {
          var button = document.getElementById("SaveButton");
          if (button) button.disabled = !myDiagram.isModified;
          var idx = document.title.indexOf("*");
          if (myDiagram.isModified) {
            if (idx < 0) document.title += "*";
          } else {
            if (idx >= 0) document.title = document.title.substr(0, idx);
          }
        });



        /*
        function showMessage(s) {
           alert(s);
         }
        myDiagram.addDiagramListener("ObjectSingleClicked",
            function(e) {
                var part = e.subject.part;
            if (!(part instanceof go.Link)) showMessage("Clicked on " + part.data.key);
        });        */

        function nodeInfo(d) {  // Tooltip info for a node data object
          var str = "Node " + d.key + ": " + d.text + ' id='+ d.id +"\n";
          if (d.group)
            str += "member of " + d.group;
          else
            str += "top-level node";
          return str;
        }

        // define the Node template
        myDiagram.nodeTemplate =
          $go(go.Node, "Auto",
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            // define the node's outer shape, which will surround the TextBlock
            $go(go.Shape, "RoundedRectangle",
              {
                parameter1: 20,  // the corner has a large radius
                //fill: $(go.Brush, "Linear", { 0: "rgb(254, 201, 0)", 1: "rgb(254, 162, 0)" }),
                stroke: "black",
                portId: "",
                fromLinkable: true,
                fromLinkableSelfNode: true,
                fromLinkableDuplicates: true,
                toLinkable: true,
                toLinkableSelfNode: true,
                toLinkableDuplicates: true,
                cursor: "pointer"
              },
              new go.Binding("fill", "color")),
            $go(go.TextBlock,
              {
                font: "bold 11pt helvetica, bold arial, sans-serif",
                editable: true  // editing the text automatically updates the model data
              },
              new go.Binding("text", "text").makeTwoWay()),
            { // this tooltip Adornment is shared by all nodes
              toolTip:
                $go(go.Adornment, "Auto",
                  $go(go.Shape, { fill: "#FFFFCC" }),
                  $go(go.TextBlock, { margin: 4 },  // the tooltip shows the result of calling nodeInfo(data)
                    new go.Binding("text", "", nodeInfo))
                ),
              // this context menu Adornment is shared by all nodes
              contextMenu: partContextMenu
            }
          );



        // replace the default Link template in the linkTemplateMap
        myDiagram.linkTemplate =
          $go(go.Link,  // the whole link panel
            { curve: go.Link.Bezier, adjusting: go.Link.Stretch, reshapable: true },
            new go.Binding("curviness", "curviness"),
            new go.Binding("points").makeTwoWay(),
            $go(go.Shape,  // the link shape
              { strokeWidth: 1.5 }),
            $go(go.Shape,  // the arrowhead
              { toArrow: "standard", stroke: null }),
            $go(go.Panel, "Auto",
              $go(go.Shape,  // the link shape
                {
                  fill: $go(go.Brush, "Radial",
                          { 0: "rgb(240, 240, 240)", 0.3: "rgb(240, 240, 240)", 1: "rgba(240, 240, 240, 0)" }),
                  stroke: null
                }),
              $go(go.TextBlock, "transition",  // the label
                {
                  textAlign: "center",
                  font: "10pt helvetica, arial, sans-serif",
                  stroke: "black",
                  margin: 4,
                  editable: false  // editing the text automatically updates the model data
                },
                new go.Binding("text", "text").makeTwoWay())
            )
          );

        // read in the JSON-format data from the "mySavedModel" element
        loadGraph();

    }

     // This is the general menu command handler, parameterized by the name of the command.
    function cxcommand(val) {
          var diagram = myDiagram;
          console.log('!');
          if (!(diagram.currentTool instanceof go.ContextMenuTool)) return;

          switch (val) {
            case "cmd_profile":
                //window.open('<?=base_url()?>/opera/users/', '_blank');
                break;
          }
          diagram.currentTool.stopTool();
    }

    // Show the diagram's model in JSON format
    function saveGraph() {
      document.getElementById("mySavedModel").value = myDiagram.model.toJson();
      myDiagram.isModified = false;
    }
    function loadGraph() {
        var from = $('.dateFromTo input[name=dateFrom]').val();
        var to = $('.dateFromTo input[name=dateTo]').val();
        var maxLevel = $('.dateFromTo input[name=graphLevelLimit]').val();

        $('#graphProgressLoad').show();
        myDiagram.model=go.Model.fromJson([]);
      //myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
        $.post('<?php base_url() ?>opera/users/trans_graph/<?= $user->id_user ?>', { from: from, to: to, maxLevel: maxLevel }, function(data){
            $('#graphProgressLoad').hide();
            myDiagram.model=go.Model.fromJson(data);
        }, 'json');
    }


    function rowClick(par){
        $(par + ".col_id_debit_id_user").click(function(){
            var field = "debit_id_user";
            var id = $(this).attr(field);
            if(0 < id) window.open("/opera/users/"+id,'_blank');
            return false;
        });
    };
    function rowClickPartners(){
        $("#table_partners .col_id_id_user").click(function(){
            var field = "id_user";
            var id = $(this).attr(field);
            if(0 < id) window.open("/opera/users/"+id,'_blank');
            return false;
        });
    };
    function renderRows(){

        var methods = <?= "['".implode("', '", getNameofPaymentSys())."']";?>,
            col = null, ids = {}, ips = {}, type = 0, val = 0, status = '',
            ip = 0,
            user_id = 0,
            ids = {}, ips = {}, col = 0, id = 0, ip_str = '', ip_ar;

        $('#table_transactions .table tbody tr').each(function(){
            col = $(this).find('.col_id_metod').attr('metod');
            user_id = $(this).find('.col_id_id_user').attr('id_user');
            ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
            id = $(this).find('.col_id_id_user').attr('id_user');
            type = $(this).find('.col_id_type').attr('type');
            val = $(this).find('.col_id_value').attr('value');
            status = $(this).find('.col_id_status').attr('status');

            ip_ar = ip_str.split(',');
            ip = ip_ar[0];

            if((74 == type || 75 == type)){
                $(this).find('.col_id_value').html("<a href='/opera/payment/"+val+"' target='_blank'>"+val+"</a>");
                if('Списано' == status)
                    $(this).addClass('red')
                if('Получено' == status)
                    $(this).addClass('green');
            }

            if( $.inArray(col, methods) > -1 )
                $(this).addClass('green');
            else
            if( col == 'Вывод средств'){
                $(this).addClass('red');

                if( ip != undefined && ip != 'null' && ip != 0 && col == 'Вывод средств' )
                {
                    ips[ip] = id;
                }

            }else
                if( col == '<?=$GLOBALS["WHITELABEL_NAME"] ?>' && $(this).find('.col_id_note').attr('note').indexOf('Снятие средств для отправки пользователю №') > -1 )
                    $(this).addClass('blue');

            ids[ user_id ] = user_id;
        });
//        $.post("opera/payment/getIdsProp", {ids: ids, ips: ips}, function(a){
//            var id, col, ip;
//
//            $('#table_transactions .table tbody tr').each(function(){
//                var id = $(this).find('.col_id_id_user').attr('id_user'),
//                col = $(this).find('.col_id_metod').attr('metod');
//
//                ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
//                ip_ar = ip_str.split(',');
//                ip = ip_ar[0];
//
//                if (col == 'Вывод средств' )
//                {
//                    if(50 <= a.money_in[id]) $(this).addClass('have_in');
//
//                    if ( ip != undefined && ip != 'null' && ip != 0 )
//                    {
//                        if( a.money_out_trans[ip] && a.money_out_trans[ip] == id ) $(this).addClass('trans_send');
//                        if( a.diff_acc[ip]) $(this).addClass('diff_acc_ip');
//                        if( a.money_out_credits[ip] && a.money_out_credits[ip] == id ) $(this).addClass('std_credits');
////                        if( a.diff_acc_credits[ip]) $(this).addClass('diff_acc_credits');
//                    }
//                }
//
//                if (!a.users_active[id]) $(this).addClass('user_deactive');
//            });
//        },
//        "json");


    }
</script>
