<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!--<a class="link_payment2id" style="margin: 10px 0;" href="#" onclick="$('.payment2id').show(); $('.link_payment2id').hide();return false;">Пополнить партнеру...</a>-->
<div class="formRow payment2id" style="<?if($isUserUS_CA && 1==0){?>display: none;<?}?>">
    <label style="margin-top:16px;"><?=_e('block/data18')?></label>
    <div class="formRight">
        <input class="form_input" type="text" name="id_user" value="" onblur="checkId(this)" style="display: none">
        <select name="pament2id" id="pament2id_select" class="form_select" style="margin: 10px 0; width: 90%">
            <?  if( isset($use_cards) && $use_cards ) {?>
                
                    <? $partners = []; ?>
                    <? if (!empty($wtcards)){ foreach( $wtcards as $card ){
                        $partners['card_'.$card->id]['item'] = Card_model::display_card_name($card, TRUE).' - $'.price_format_double($card->last_balance);                                      
                    } } else {
                        $partners['card_create_virtual']['item'] = _e('Заказать  Webtransfer VISA Virtual');                                      
                        $partners['card_create_plastic']['item'] = _e('Заказать  Webtransfer VISA Black');                                      
                    }
                    ?>
                    <? renderSelectGroup($partners, $id_user);?>
            <? } else { ?>
                     <? renderSelectGroup($partners, $id_user);?>
            <? } ?>
        </select>
       
       
        <center>
            <img class="payment2id-loading-gif" style="display: none" src="/images/loading.gif">
        </center>
<!--    <script>
        function toAll(t){
            if ($(t).prop('checked')){
                $('[name="id_user"]').show();
                $('[name="pament2id"]').hide();
                $('[name="pament2id"]').prop('disabled','disabled');
                $('[name="id_user"]').prop('disabled','');
            } else{
                $('[name="id_user"]').hide();
                $('[name="id_user"]').prop('disabled','disabled');
                $('[name="pament2id"]').show();
                $('[name="pament2id"]').prop('disabled','');
            }
        }
        function checkId(el){
            $(el).parent().find('.loading-gif').show();
            $.get(site_url + '/login/checkUserId/'+$(el).val(), function(a){
                $(el).parent().find('.loading-gif').hide();
                if(!a.isExist) alert('Такого пользователя не существует!');
            }, 'json');
        }

    </script>-->
    </div>
</div>