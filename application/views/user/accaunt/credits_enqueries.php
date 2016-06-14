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

    <? if ($page_name == 'credits_enqueries') { ?>
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
<iframe id='a2108025' name='a2108025' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=35&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60' allowtransparency='true'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=aaba6de4&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=35&amp;cb={random}&amp;n=aaba6de4&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
<br/></center>
<?}?>

<?
if (!empty($credits)) {
    foreach ($credits as $item)
        $this->load->view('user/accaunt/blocks/renderCredit_part.php', compact("item"));
    ?>
    <?if(10 < $item->count){?>
    <div id="next_orders">

    </div>
    <br/>
    <center><a id="next" class="button narrow" href="#" onclick="return false" data-count="10"><?=_e('accaunt/credits_enqueries_1')?></a></center>
    <script>
        $("#next").click(function(){
            var count = $("#next").data("count");
            var all = <?=$item->count?>;
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
}else
    echo "<div class='message'>"._e('accaunt/credits_enqueries_2')."</div>";
?>
<a style="float:right" href="<?=site_url('account/archive_credits_enqueries')?>"><?=_e('blocks/renderHeaderMenu_part_9')?></a>
<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>

<div class="popup_window" id="report_send">
    <div class="close"  ></div>
    <div class="widget" style="margin-top:20px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_enqueries_3')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="report_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <div style="text-align:center;">
                        <?=_e('accaunt/credits_enqueries_4')?>
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_enqueries_5')?></label>
                    <div class="formRight">
                        <textarea name="message" rows="5" style="width: 100%;"></textarea>
                    </div>

                </div>
                <center>
                    <button class="button" type="submit" data-id="" class="confirm-button"  name="submit"><?=_e('accaunt/credits_enqueries_6')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
</div>



<div id="popup_confirm_return" class="popup_window" >
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_enqueries_7')?></h2>
    <?=_e('accaunt/credits_enqueries_8')?>
    <button class="button confirm-button" type="submit" name="submit"><?=_e('accaunt/credits_enqueries_9')?></button>
</div>

<div id="popup_alfa"  class="popup_window" style="width:800px;height:85%;top:5%;left:50%;margin-left:-400px">
    <div class="close"></div>
    <iframe src="https://3ds.payment.ru/P2P_PSBR/card_form.html" frameborder="0" width="95%" height="95%" align="center">
   <?=_e('accaunt/credits_enqueries_10')?>
    </iframe>
</div>



<div class="popup_window small" id="user_popup" style="z-index: 100;">
    <div class="close"></div>
    <div class="content" style="margin-top: 10px;"></div>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>

<div class="popup_window" id="popup_agree_confirm">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_enqueries_11')?></h2>
   <?=_e('accaunt/credits_enqueries_12')?>
    <div class="button" style="left: 20px; position: relative;">
        <input type="submit" id="confirm_invest" name="submit" value="" /><?=_e('accaunt/credits_enqueries_13')?>
    </div>
</div>

<div class="popup_window" id="popup_confirm_payment">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_enqueries_14')?></h2>
   <?=_e('accaunt/credits_enqueries_15')?>
    <div class="button" style="left: 20px; position: relative;">
        <input type="submit" class="confirm-button narrow" name="submit" value="" /><?=_e('accaunt/credits_enqueries_16')?>
    </div>
</div>

<div  class="popup_window" id="popup_load">
    <center><?=_e('accaunt/credits_enqueries_17')?><br/>
        <img src="/images/loaders/loader12.gif"></center>
</div>



<div class="popup" id="send_money">
    <div class="close"  onclick="$('#send_money').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_enqueries_18')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="mc_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_enqueries_19')?> </label>
                    <div class="formRight">
                        <input type="text" name="reciver" disabled="disabled" style="color: black"    value=""/>
                    </div>
                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_enqueries_20')?></label>
                    <div class="formRight">
                        <input type="text" name="amount"  disabled="disabled" style="color: black" value=""/>
                    </div>
                </div>
                <div class="formRow" id="extra_info_div" style="display: none;">
                    <label style="margin-top:20px;"></label>
                    <div class="formRight" id="extra_info">

                    </div>
                </div>

                <div class="formRow" id="cards_list_div" style="display: none;">
                    <label style="margin-top:7px;"><?=_e('С карты')?>:</label>
                    <div class="formRight">
                        <select name="card_id" id="cards_list" style="padding:7px;width:96%;">
                            <? $summ_b6 = max($rating_by_bonus['availiable_garant_by_bonus'][6],0) ?>
                           <?//<option value="bonus_6" data-type="bonus">WTDEBIT - $ <?=price_format_double($summ_b6)?></option>?>
                            <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>

                <center>
                    <div class="button narrow" style="position: relative;margin-bottom:10px;"><input  type="submit" data-id=""   id="out_send_return" name="submit" value=""><?=_e('accaunt/credits_enqueries_21')?></div>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
    <br/>
    <!-- <a href="#" class="but cancel other_methods"><?=_e('accaunt/credits_enqueries_22')?></a> -->
</div>

<div class="popup_window" id="other_methods">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_enqueries_23')?></h2>
  <?=_e('accaunt/credits_enqueries_24')?>
    <br/><br/>
    <a href="#" class="but cancel alfa"><?=_e('accaunt/credits_enqueries_25')?></a>
    <a href="#" id="open_rekviziti" class="but cancel bluebut"><?=_e('accaunt/credits_enqueries_26')?></a>
</div>

<div class="popup" id="rekviziti">
    <div class="close"  onclick="$('#rekviziti').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_enqueries_27')?></h6>
        </div>
        <fieldset>

        </fieldset>
    </div>
</div>

<div id="arbitrage_repayment" class="popup_window" >
    <div class="close"></div>
    <input type="hidden" class="id" name="id" value=""/>
    <h2><?=_e('accaunt/credits_enqueries_28')?></h2>
    <?=_e('accaunt/credits_enqueries_29')?>
    <div id="arbitrage_repayment_pa_block" style="display: none;">
    <br><br><?=_e('Выберите счет: ') ?><br>
    <select id="payment_account" name="payment_account" style="width: 200px;"><?
              /*$selected = '';
              echo "<option value='-'$selected>-</option>";
              $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
              echo "<option value='5'$selected>WTUSD1 - $ {$rating_bonus[5]['net_own_funds']}</option>";
              $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
              echo "<option value='2'$selected>WTUSD&#10084; - $ {$rating_bonus[2]['net_own_funds']}</option>";
               * 
               */
            if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
            <? }
               
    ?></select>
    </div>
    <center>
        <button type="submit" class="button confirm-button" name="submit"><?=_e('accaunt/credits_enqueries_30')?></button>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    </center>
</div>

<div class="popup fixed-float" id="arbitrage_prolongation">
    <div class="close"  onclick="$(this).parent().hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_enqueries_31')?></h6>
        </div>
        <fieldset>
                <input type="hidden" class="time" name="time" value=""/>
                <input type="hidden" class="summa" name="summa" value=""/>
                <input type="hidden" class="id" name="id" value=""/>
                <div class="formRow">
                    <label style="margin-top:5px;"><?=_e('accaunt/credits_enqueries_32')?></label>
                    <div class="formRight">
                        <select class="form_select period-add" name="period-add" style="width: 234px;">
                        </select>
                    </div>
                </div>
                <div class="formRow">
                    <label style="margin-top:10px;"><?=_e('accaunt/credits_enqueries_33')?></label>
                    <div class="formRight">
                        <input type="text" class="amount" name="amount"  disabled="disabled" style="color: black; width: 214px;" value="" />
                    </div>
                </div>
                <center>
                    <button type="submit" class="button confirm-button" name="submit"><?=_e('accaunt/credits_enqueries_34')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
        </fieldset>
    </div>

</div>
</div>
<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>

<?$this->load->view('user/accaunt/blocks/renderSendMessagePopup.php', compact("item"));?>
