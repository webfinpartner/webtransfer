<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    #wtcard_payment select {
        border: 1px solid #999999;
        padding: 0px;
        margin-left: 3px;
        color: #999999;
        width: 90%;
        height: 30px;
        text-align: center;
    }
</style>

<div class="popup lava_payment" id="wtcard_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
            <form method="GET" action="#" class="form" id="wtcard_form">
                <input type="hidden" name="m_desc" value="<?= "Wallet top up from $name $f_name ($id_user) $email" ?>">

                    <? $this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', ['partners'=>$partners, 'id_user'=>$id_user, 'use_cards'=>FALSE]  ); ?>
            <div class="formRow">
                <label style="padding-top:20px"><?=_e('Карта')?></label>
                <div class="formRight">
                    <select name="card" class="form_select"  style="padding: 5px;" onchange="get_card_balance( $('#wtcard_payment select[name=card] option:selected').val(), 1, '#wtcard_payment #balance_loading-gif', '#wtcard_payment #card_balance' );">
                     <?php if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                        <option value="<?=$card->id?>"><?=Card_model::display_card_name($card)?></option>
                     <?php } ?>
                    </select>
                    <?php if (!empty($wtcards)) { ?>
                    <span style="font-size:12px;"><?=_e('Сумма на карте')?>: $<span id='card_balance'><?= $wtcards[0]->last_balance ?></span>
                        <a href='#' onclick="get_card_balance( $('#wtcard_payment select[name=card] option:selected').val(), 0, '#wtcard_payment #balance_loading-gif', '#wtcard_payment #card_balance' );
                            return false;"><img src="/images/reload.png"></a>
                        <p class="center"><img id='balance_loading-gif' style="display: none" src="/images/loading.gif"/></p>

                    </span>
                    <?php } ?>
                </div>
            </div>

            <div class="formRow">
                <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                <div class="formRight">
                    <input type="text" name="m_amount" class="maskMoneyCustom" value=""/>
                </div>
                <div class="error" style="display:none" ><?=_e('block/data16')?></div>
            </div>

            <input  type="submit" style="display:none" class="send" />
            <button class="button" onclick="return false;"  id="wtcard_send" name="submit" value=""><?=_e('block/data11')?></button>





                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>

    function get_card_balance(card_id, useCache, loader, target){

        $(loader).show();
         $.post(site_url +  "/account/ajax_user/get_card_balance", { card_id: card_id, useCache: useCache},  function(data){
                $(loader).hide();
                $(target).html(data.balance);
          }, "json");

    }

    $('.wtcard_trigger').click(function() {
        $('p.error').hide();
        get_payment_permission(function(){
           $("#wtcard_payment").fadeIn();
        });
    }).css('cursor', 'pointer');

    $('#wtcard_send').click(function() {
        var form =  $('#wtcard_form');

        $('p.error').hide();
        if(!cahckSumm("wtcard_form", '[name="m_amount"]'))
            return false;

//        var user_id = form.find('[name="pament2id"] .select3-single-selected-item').data('item-id');
        var user_id = form.find('[name="pament2id"]').val();
        var all = 'false';
        if(form.find('.payment2id [name="to_all"]').prop('checked')){
            user_id = form.find('.payment2id [name="id_user"]').val();
            all = 'true';
        }

        $('#wtcard_payment div.error').hide();
        $('.loading-gif').show();
        $.post(site_url + '/account/pay', {
            summa: form.find('[name="m_amount"]').val(),
            description: form.find('[name="m_desc"]').val(),
            metod: 'wtcard',
            user_id: user_id,
            card_id: $('#wtcard_payment select[name=card] option:selected').val(),
            all: all
        },
        function(data) {
            if(data.error){
                $('p.error').text(data.error);
                $('p.error').show();
                $('.loading-gif').hide();
                return;
            }
            window.location.reload();
        }, 'json');
        return false;
    });
</script>

