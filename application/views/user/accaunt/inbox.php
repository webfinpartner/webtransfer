<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="/js/user/inbox.js"></script>
<!--<script type="text/javascript" src="/js/user/credit.js"></script>-->

<script>
    var security = '<?=$security ?>';

    $(function(){

        $('#confirm_invest2').click(function(){

            var std = 0;
            $('#popup_debit').hide('slow');

            if( !security ){
                $('#popup_load').show('slow');
                return true;
            }else{
                if( $("#standart").attr("checked") )
                {
                    if( $(this).hasClass('active') ){
                        $('#popup_load').show('slow');
                        return true;
                    }else{
//                        $('#out_send_window').data('call-back','standart_calc').show('slow');
                            mn.security_module
                                .init()
                                .show_window('withdrawal_standart_credit')
                                .done(function(res){
                                    var code = res['code'];
                                    if( res['res'] != 'success' ) return false;

                                    mn.security_module.loader.show();
                                    $('#form_code').val( code );
                                    $('#confirm_invest2').addClass('active').click();
                                });
                        return false;
                    }
                }else{
                    $('#popup_load').show('slow');
                    return true;
                }
            }
        });


    });
</script>
<script type="text/javascript" src="/js/user/sms_module.js"></script>
<script type="text/javascript" src="/js/user/debits.js"></script>
<link rel="stylesheet" href="/css/user/inbox.css" />
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />

<h4 class="inbox-title"><?=_e('accaunt/inbox_1')?></h4>

<!--
<input type="checkbox" name="option1" value="all"> <?=_e('accaunt/inbox_2')?>
<button type="button" onclick="deleteAllSelected();"><?=_e('accaunt/inbox_3')?></button>
-->

<div id='inbox'>
    <? print_messages($messages_inbox, false); ?>
</div>

<h4 class="inbox-title"><?=_e('accaunt/inbox_4')?></h4>
<div id="outbox">
    <? print_messages($messages_outbox, true); ?>
</div>

<?

function print_messages($messages, $inbox = false) {
    foreach ($messages as $item) {
        ?>

        <div class="inbox" name="item<?= $item->id ?>" href="<?= $item->id ?>">
            <div class="header <?= checkNewMessage($item, $inbox) ?>"  >

                <!--
                <span class="izbrano"><input type="checkbox" class="checkbox" value="<?= $item->id ?>"></span>
                -->

                <span class="date"><?= date_formate_my($item->date, 'd/m/Y H:i') ?></span>
                <span class="title"><?= (("mess" == $item->cause) ? _e("Перевод денег") : viewMessage($item->message, $inbox)) ?></span>
                <span class="status" <?=(("news" == $item->cause || "mess" == $item->cause) ? "data-cause='mess'" : '')?>><?= (("news" == $item->cause || "mess" == $item->cause) ? viewStatusMess($item->status) : viewStatusMessage($item->status,$inbox)) ?></span>
                <a class="delete">&nbsp</a>
            </div>
        <? if ("news" == $item->cause) {?>
            <div class="content list_applications">
                <? get_instance()->load->view('user/accaunt/blocks/renderNewsInbox_part.php', compact("item")); ?>
            </div>
        <?} else if ("mess" == $item->cause) {?>
            <div class="content list_applications">
                <?=$item->message?>
            </div>
        <?} else {?>

            <div class="content list_applications">
                <? if (!empty($item->cause) && "news" != $item->cause) { ?>
                    <div class="cause-content">
                        <?
                        if ($item->cause == 'cancel') {
                            echo _e('accaunt/inbox_5');
                        } else {
                            ?><?= ($item->cause == 'agree') ? _e('accaunt/inbox_6') : nl2br($item->cause);
            }
                        ?>
                    </div>
        <? } ?>

        <? if (!empty($item->debit_info)) { ?>
            <div class="debit" rel="<?= $item->debit_info->type ?>">
                <?=_e('accaunt/inbox_6_1')?> <?= $item->debit ?><br/>
                <?=_e('accaunt/inbox_6_2')?> <?= ($item->debit_info->garant == Base_model::CREDIT_GARANT_ON? _e('accaunt/inbox_6_3'): _e('accaunt/inbox_6_4')) ?><br/>
                <?=_e('accaunt/inbox_7')?> <?= price_format_double($item->debit_info->summa) ?><br/>
                <?=_e('accaunt/inbox_8')?> <?= $item->debit_info->time ?> <br/>
                <?=_e('accaunt/inbox_9')?> <?= $item->debit_info->percent ?>%<br/>
                <?=_e('accaunt/inbox_10')?> <?= price_format_double($item->debit_info->out_summ) ?><br/>
            </div>
        <? } ?>

        <? if (!empty($item->user_from)) { ?>
            <a href="<?= ($inbox == false) ? $item->user_from : $item->user_to ?>"  rel="<?= ((isset($item->debit_info->type)) ? $item->debit_info->type : '') ?>" data-id="<?= ((isset($item->debit_info->id)) ? $item->debit_info->id : '') ?>" class="author-get"><?=_e('accaunt/inbox_11')?></a>
        <? } ?>
        <br/>
        <? if (!empty($item->user_from)) { ?>
        <div class="author-info" ></div>
        <div  class="action" data-id="<?=$item->id?>">
            <? if ($item->status == 1 or $item->status == 2) { ?>
                <a class="but cancel"><?=_e('accaunt/inbox_12')?></a>
                <? if ($inbox == false) { ?>
                    <a href='#' class='agree but conf2' style="margin-right:10px;"><?=_e('accaunt/inbox_13')?></a>
                <? } ?>
            <? } else { ?>
                <a class="delete" ><?=_e('accaunt/inbox_14')?></a>
            <? } ?>
        </div>
        <div class="do-cancel"></div>
        <? } else { ?>
                    <a class="delete" style="cursor: pointer"><?=_e('accaunt/inbox_14')?></a>
        <? } ?>
            </div>
        <?}?>
        </div>
    <?
    }
}
?>

<div class="popup_window" id="popup_agree_confirm0">
    <div class="close"></div>
    <h2><?=_e('accaunt/inbox_15')?></h2>
    <?=_e('accaunt/inbox_16')?>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/inbox_17')?></button>
</div>

<div class="popup_window" id="popup_agree_confirm2">
    <div class="close"></div>
    <h2><?=_e('Выдача займа')?></h2>
   <?=_e('Вы выдаете займ третьему лицу. Подтверждая, вы соглашаетесь с Правилами и Условиями использования, в т.ч. на передачу ваших персональных данных заемщику. ')?>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div id="popup_window" class="popup_window small">
    <div class="close"></div>
    <div class="content" style="padding-top: 15px;">
        <p class="center"><?=_e('accaunt/inbox_18')?></p>
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif" />
    </center>
</div>
<? get_instance()->load->view('user/accaunt/blocks/renderCodeProtection_window.php'); ?>
