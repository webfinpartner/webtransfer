<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>
<?}?>

<style>
    .pmt {
        cursor: pointer !important;
        float: none !important;
        margin-right: 0px !important;
        padding: 0 !important;
    }

    .zayavka_ind:hover {background-color:#fff;}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('change', '#garant', function() {
            if ($(this).prop("checked"))
                $('.payment_types').css('opacity', '0.15')
        }).trigger('change')

        $(document).on('change', '#standart', function() {
            if ($(this).prop("checked"))
                $('.payment_types').css('opacity', '1')
        }).trigger('change')
    });

</script>

    <style>



    </style>
    <? if($page_name == 'invests_enqueries'){ ?>
    <div id="form_type">
        <form method="POST">
            <select name="type" onchange="if (this.selectedIndex) this.form.submit ()">
                <option disabled="disabled"><?=_e('accaunt/credits_enqueries_35')?></option>
                <option value="t0" <?=(($type != 't0') ? NULL : 'selected="selected"')?>><?=_e('accaunt/credits_enqueries_36')?></option>
                <option value="t1" <?=(($type != 't1') ? NULL : 'selected="selected"')?>><?=_e('accaunt/credits_enqueries_37')?></option>
                <option value="t3" <?=(($type != 't3') ? NULL : 'selected="selected"')?>><?=_e('accaunt/credits_enqueries_38')?></option>
                <option value="t4" <?=(($type != 't4') ? NULL : 'selected="selected"')?>><?=_e('accaunt/credits_enqueries_39')?></option>
            </select>
        </form>
    </div>
    <? } ?>
<?if($notAjax){?>
<br/><center>
<iframe id='ad2ea7e4' name='ad2ea7e4' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=34&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60' allowtransparency='true'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=aa5c0ce3&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=34&amp;cb={random}&amp;n=aa5c0ce3&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
<br/><br/></center>
<?}?>
<?
if (!empty($credits)){
    foreach ($credits as $item)
        $this->load->view('user/accaunt/blocks/renderInvests_part.php', compact("item"));?>

    <?if(10 < $item->count){?>
    <div id="next_orders">

    </div>
    <br/>
    <center><a id="next" class="button narrow" href="#" onclick="return false" data-count="10"><?=_e('accaunt/credits_enqueries_1')?></a></center>

    <script>
        $("#next").click(function(){
            var count = $("#next").data("count");
            var all = <?=$item->count?>;
            console.log(all);
            console.log('next_<?=$page_name?>');
            if(Math.ceil(all/10) <= Math.ceil((count+10)/10))
                $("#next").hide();
            $.get(site_url + "/account/next_<?=$page_name?>/"+count,function(d){
                    $("#next_orders").append(d);
                }
            );
            $("#next").data("count", count + 10);
            return false;
        });


    </script>

<?}
} else
    echo "<div class='message'>"._e('accaunt/invests_21')."</div>";
?>
<a style="float:right" href="<?=site_url('account/archive_invests_enqueries')?>"><?=_e('blocks/renderHeaderMenu_part_9')?></a>

<!-- POPUP WINDOWS -->
<? $this->load->view('user/blocks/sellCertificat_window.php', compact($security));?>
<? $this->load->view( 'user/blocks/exchangeCertificat_window', compact($security) ); ?>
<? $this->load->view( 'user/blocks/delExchangeCertificat_window' ); ?>
<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>
<script type="text/javascript" src="/js/user/sms_module.js"></script>
<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
</div>
<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>
<div class="popup_window procent">
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_22')?></h2>
<?=_e('accaunt/invests_23')?>

</div>

<div class="popup_window otchislenie">
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_26')?></h2>
<?=_e('accaunt/invests_27')?>
</div>


<div class="popup_window small" id="user_popup">
    <div class="close"></div>
    <div class="content" style="padding-top: 15px;"></div>
</div>

<div class="popup_window" id="popup_agree_confirm7">
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_28')?></h2>
   <?=_e('accaunt/invests_29')?>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div class="popup_window" id="popup_agree_confirm2">
    <div class="close"></div>
    <h2><?=_e('Выдача займа')?></h2>
   <?=_e('Вы выдаете займ третьему лицу. Подтверждая, вы соглашаетесь с Правилами и Условиями использования, в т.ч. на передачу ваших персональных данных заемщику. ')?>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div class="popup_window" id="popup_agree_confirm0">
    <div class="close"></div>
    <h2><?=_e('accaunt/applications_23a')?></h2>
   <?=_e('accaunt/applications_24a')?><br/><br/>
    <?=_e('При совершении данной операции взымается дополнительная комиссия в размере 0.5% + $0.10 за транзакцию.')?><br/><br/>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>



<div class="popup_window" id="popup_agree_confirm_own_direct">
    <div class="close"></div>
    <h2><?=_e('Выдача займа на сторонний счет')?></h2>
   <?=_e('Вы выдаете займ на счет третьего лица. Подтверждая, вы соглашаетесь с
    <a href="'.site_url('/page/about/terms').'" target="_new">Правилами и Условиями использования</a>,
    в т.ч. на передачу ваших персональных данных заемщику.')?><br/><br/>
    <?=_e('При совершении данной операции ваи необходимо перевести средства на счет контрагента, а затем подтвердить отправку средств.')?><br/><br/>
    <div id="extra_info"></div>
    <button class="button" type="submit" id="confirm_invest" name="submit"><?=_e('accaunt/invests_30')?></button>
</div>

<div id="popup_window" class="popup_window small">
    <div class="close"></div>
    <div class="content" >
        <p class="center"><?=_e('accaunt/invests_48')?></p>
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif">
    </center>
</div>
<div id="popup_alfa"  class="popup_window" style="width:800px;height:85%;top:5%;left:50%;margin-left:-400px">
	<div class="close"></div>
	<iframe src="https://3ds.payment.ru/P2P_PSBR/card_form.html" frameborder="0" width="95%" height="95%" align="center">
	<?=_e('accaunt/invests_40')?>
	</iframe>
</div>

<div class="popup_window"  id="popup_agree_confirm1" >
    <div  class="close"></div>
    <h2><?=_e('accaunt/invests_31')?></h2>
    <?=_e('accaunt/invests_32')?>
    <button class="button" type="submit" id="confirm_invest" name="submit" value=""><?=_e('accaunt/invests_33')?></button>
</div>
<div class="popup_window garant">
    <div onclick="$('.popup_window').hide('slow');" class="close"></div>
    <h2><?=_e('accaunt/invests_34')?></h2>
    <?=_e('accaunt/invests_35')?>
</div>



<div  class="popup_window" id="popup_load">
    <center><?=_e('accaunt/invests_41')?><br/>
        <img src="/images/loaders/loader12.gif"></center>
</div>

<div class="popup_window" id="popup_confirm_payment">
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_42')?></h2>
	<?=_e('accaunt/renderNewsInbox_part_19')?><br/>
   <input type="checkbox" id="pay_conf"/> <?=_e('accaunt/renderNewsInbox_part_20')?>
    <button class="button" type="submit" class="confirm-button" name="submit"><?=_e('accaunt/invests_47')?></button>
</div>


<div id="popup_confirm_return" class="popup_window" >
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_45')?></h2>
    <?=_e('accaunt/invests_46')?>
    <button class="button confirm-button" type="submit" name="submit"><?=_e('accaunt/invests_47')?></button>
</div>

<div class="popup" id="send_money">
    <div class="close"  onclick="$('#send_money').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/invests_49')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="mc_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/invests_50')?> </label>
                    <div class="formRight">
                        <input type="text" name="reciver" disabled="disabled" style="color: black"    value=""/>
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/invests_51')?></label>
                    <div class="formRight">
                        <input type="text" name="amount" disabled="disabled" style="color: black"   value=""/>
                    </div>

                </div>
                <center>
                    <button class="button narrow"  type="submit" data-id=""   id="out_send" name="submit"><?=_e('accaunt/invests_52')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
    <br/>
    <a href="#" class="but cancel other_methods"><?=_e('accaunt/invests_53')?></a>
</div>





<div class="popup" id="rekviziti">
    <div class="close"  onclick="$('#rekviziti').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/invests_54')?></h6>
        </div>
        <fieldset>

        </fieldset>
    </div>
</div>

<div class="popup_window" id="other_methods">
    <div class="close"></div>
    <h2><?=_e('accaunt/invests_55')?></h2>
    <?=_e('accaunt/invests_56')?>
    <br/><br/>
    <a href="#" class="but cancel alfa"><?=_e('accaunt/invests_57')?></a>
    <a href="#" id="open_rekviziti" class="but cancel bluebut"><?=_e('accaunt/invests_58')?></a>
</div>


<div class="popup_window" id="return_arbitration_part_dialog">
    <div class="close"></div>
    <h2><?=_e('Возврат арбитражной части')?></h2>
    <?=_e('')?>
    <br/><br/>
    <a href="#" id="return_arbitration_part_but" class="but cancel bluebut"><?=_e('Подтвердить')?></a>
</div>

<? ci()->load->view('user/accaunt/blocks/renderReceiveWaitingInvestDialog') ?>

<script>
    /*$("#bonus").click(function(e) {
     if ($("#bonus").is(':checked')) {
     $("#timeSlider").ionRangeSlider("update", {from: 10, max: 10});
     $("#priceSlider").ionRangeSlider("update", {from: 50, max: <?= (1000 < $accaunt_header['wallet_bonus']) ? 1000 : $accaunt_header['wallet_bonus'] ?>});
     $("#garant").attr("checked", '');
     $("#standart").removeAttr("checked");
     $("#standart").attr("disabled", "disabled");
     $("#standart").hide();
     $("#label_standart").hide();
     }
     else {
     $("#priceSlider").ionRangeSlider("update", {max: 1000});
     $("#timeSlider").ionRangeSlider("update", {max: 30});
     $("#standart").removeAttr("disabled");
     $("#standart").show();
     $("#label_standart").show();
     }

     })*/
<? if (50 <= $accaunt_header['wallet_bonus']) { ?>
        $(document).ready(function() {
            $("#bonus").attr("checked", "checked");
            $("#bonus").click();
            $("#bonus").attr("checked", "checked");
        })
<? } ?>


</script>


<?$this->load->view('user/accaunt/blocks/renderSendMessagePopup.php', compact("item"));?>