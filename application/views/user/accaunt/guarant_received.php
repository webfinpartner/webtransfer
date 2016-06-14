
<? if (!empty($credits)) {
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
    echo "<div class='message'>"._e('Не найдено')."</div>";
?>

    <div class="popup_window" id="popup_confirm_payment">
    <div class="close"></div>
    <h2><?=_e('Подтверждение получения Гарантии')?></h2>
   <?=_e('Вы действительно подтверждаете, что хотите Получить гарантию?')?>
    <div class="button" style="left: 20px; position: relative;">
        <input type="submit" class="confirm-button narrow" name="submit" value="" /><?=_e('accaunt/credits_enqueries_16')?>
    </div>
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
                            <? $summ_b6 = max($rating_by_bonus['payout_limit_by_bonus'][6],0) ?>
                           <option value="bonus_6" data-type="bonus">WTDEBIT - $ <?=price_format_double($summ_b6)?></option> 
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
<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>


<div class="popup_window small" id="user_popup">
    <div class="close"></div>
    <div class="content" style="padding-top: 15px;"></div>
</div>
