<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
    $sum = get_item('sum');
    $time = get_item('time');
    $persent = get_item('persent');
?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>
<?}?>

<script src="/js/user/jquery.dd.32.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/css/user/msdropdown/dd.css" />

<style>
    .pmt {
        cursor: pointer !important;
        float: none !important;
        margin-right: 0px !important;
        padding: 0 !important;
    }

    .zayavka_ind:hover {background-color:#fff;}

.formRow:before {
    border-top: none;
    position: relative;
    width: 100%;
    height: auto !important;
    z-index: 9;
    padding-left: 0px;
    padding-right: 0;
}


.formRow.rowBonus {
    border-top: none;
    position: absolute;
    width: 370px;
    height: 218px !important;
    z-index: 18;
    margin-bottom: -76px;
    border-bottom: 0;
}

.formRow {
    border-top: none;
    position: relative;
    width: 100%;
    height: auto !important;
    z-index: 9;
    padding-left: 0px;
    padding-right: 0;
}


.formRow .rowWrapper {
    margin-left: 23px;
}


</style>


<? $this->load->view(  'user/accaunt/blocks/renderSocialWallpost', [
            'form_id' => 'credit_form'
        ]); ?>

<div class="widget">
    <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('accaunt/credits_1')?></h6>
    </div>
    <form class="form" id="credit_form" action="<?=site_url('account/my_credits')?>" method="post">
        <input type="hidden" name="code" id="form_code" value="1"/>
        <input type="hidden" name="account_type" id="account_type" value="account"/>
        <fieldset >
            <div class="formRow rowBonus">
                <div class="rowWrapper">
                        <?//  if (  $rating_bonus[2]['net_own_funds'] > 0 && $rating_bonus[5]['net_own_funds'] > 0  ) { ?>
                            <label><?=_e('Счет: ')?></label>&nbsp;
                            <select id="payment_account" onchange="on_payment_account_change()" name="payment_account" class="form_select" style="width: 300px"><?

                            echo "<option data-type='none' data-summ='0' value='-'>-</option>";

                            if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-summ="<?=$rating_by_bonus['max_loan_available_by_bonus'][6]?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>                            
                            <? } ?>
                                
                            <?
                          //  $selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                          //  echo "<option data-type='account'  data-summ='{$rating_by_bonus['net_loan_available_by_bonus'][5]}'  value='5'$selected>WTUSD1 - $ {$rating_by_bonus['net_loan_available_by_bonus'][5]}</option>";
                          //  
                            //$selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                            echo "<option data-type='account'  data-summ='{$rating_by_bonus['max_loan_available_by_bonus'][2]}'  value='2'$selected>WTUSD&#10084; - $ {$rating_by_bonus['max_loan_available_by_bonus'][2]}</option>";
                            //$selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                            echo "<option data-type='account'  data-summ='{$rating_by_bonus['max_loan_available_by_bonus'][6]}'  value='6'$selected>WTDEBIT - $ {$rating_by_bonus['max_loan_available_by_bonus'][6]}</option>";
                            ?>
                            <?  if (!empty($own_accounts)) foreach( $own_accounts as $own_acc ){ ?>
                                <option value="<?=$own_acc->id?>" data-image="<?=Card_model::get_own_account_icon($own_acc)?>" data-summ="<?=$rating_by_bonus['max_loan_available_by_bonus'][6]?>" data-type="own_account"><?=Card_model::display_own_account_name($own_acc)?> - $ <?=price_format_double($own_acc->summa)?> </option>
                            <? } ?>

                            </select>
                        <? //} ?>

                </div>
            </div>
              <div style="z-index: 100;    clear: both;    overflow: hidden;    margin-top: 61px;">
            <div class="formRow">
                <div class="col">
                    <label><?=_e('accaunt/credits_2')?></label>
                    <select id="select-sum" name="summ"  class="form_select" onchange="on_sum_change()"><?
                        for ($i = 50; $i <= 1000; $i +=50) {
                            $s = ($sum == $i) ? "selected='selected'" : "";
                            echo "<option $s value=\"$i\">$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <label><?=_e('accaunt/credits_3')?></label>
                    <select id="select-period" name="time" class="form_select"><?
                        for ($i = 3; $i <= 30; $i++) {
                            $s = ($time == $i) ? "selected='selected'" : "";
                            echo "<option $s value=\"$i\">$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <label><span><?=_e('accaunt/credits_4')?></span></label>
                    <select name="percent" id="select-percent" class="form_select"><? getPsntSelect($persent) ?></select>
                </div>
            </div>
            <div class="formRow">
                <div class="rowWrapper">
                    <!--span id="standart_span" style="display: block;"-->
                        <input type="radio"  name="garant" id="standart" onclick="$('#infoblock').hide();" value="0" checked/>
                        <label class="pmt" for="standart" id="label_standart"> <?=_e('accaunt/invests_6')?></label>
                    <!--/span-->
                    <span id="garant_span" style="display: none;">
                        <input type="radio"  name="garant" id="garant" onclick="$('#infoblock').show();"  value="1" />
                        <label class="pmt" for="garant"> <?=_e('accaunt/invests_5')?></label>
                    </span>
                </div>
            </div>




            <div class="formRow" id="infoblock">
                <div class="col">
                    <label><?=_e('accaunt/credits_5')?></label>
                    <input class="form_input" id="summ_to_return" type="text" value="" disabled="disabled" />
                </div>
                <div class="col">
                    <label><?=_e('accaunt/credits_6')?><span class="phone_format" onclick="$('#popup_limit').show('slow');
                            return  false;" style="cursor:pointer;">(?)</span></label>
                    <input class="form_input" id="max_loan_avaliable" type="text" disabled="disabled" value="$ <?= price_format_double($accaunt_header['max_garant_loan_available'], TRUE, TRUE) ?>"/>
                </div>
            </div>

            <div style=" height: 60px;">
                <button class="button"  onclick=" isVisualDNA();<?= (!empty($curSocial["vk"]["access_token"]) ? "postMsgWall('"._e('accaunt/credits_8')."" . base_url() . "');" : "") ?>return false; " type="submit" name="submit" ><?=_e('accaunt/credits_9')?></button>
<a href="<?=site_url('account/applications_credits')?>"  class="but bluebut sendmoney_card_trigger" style="margin-top: -60px; cursor: pointer; float:right; margin-right:10px" ><?=_e('Взять на бирже')?></a>
            </div>

              </div>

            <div id="popup_debit">
                <div class="close"></div>
                <h2><?=_e('accaunt/credits_10')?></h2>
                <?=_e('accaunt/credits_11')?>
                <button class="button" type="submit" id="out_send_payout"  name="submit"><?=_e('accaunt/credits_12')?></button>
            </div>
        </fieldset>
    </form>
</div>
<script>
    var security = '<?=$security ?>';
    var avail_card_credits = <?=json_encode($card_credit_params)?>;

    function get_account_type(){
        return $('#payment_account').find('option:selected').data('type');
    }
    function get_account_val(){
        return $('#payment_account').find('option:selected').val();
    }

    function get_account_summ(){
        return $('#payment_account').find('option:selected').data('summ');
    }

    function on_payment_account_change(){
       var type = get_account_type();
       $('#account_type').val( type );
       /*
       if ( type=='account' && get_account_val() == 2)
            $('#standart_span').show();
       else
        $('#standart_span').hide();
       */
       recalculateRate();
       refreshFields();
    }

    function standart_calc(res){

        if( res !== undefined )
        {
            if( res['res'] === undefined || res['res'] != 'success' ) return false;
            $('#form_code').val( res['code'] );
        }
        mn.security_module.loader.show();

        $('#out_send_payout').addClass('submit').trigger("click");

    }

    function isVisualDNA(){
        $('#popup_VisualDNA .garant').hide();
        $('#popup_VisualDNA .standart').show();
        $.post(
            "<?=site_url('account/isVisulDNATestReady')?>",
            $("#credit_form").serialize(),
            function(a){
                if("on" == a.status && 'false' == a.res){ // 'false' - должен быть именно в кавычках!!!! не менять!!!
                    $('#popup_VisualDNA').show('slow');
                    if($("#garant").is(":checked")){
                        $('#popup_VisualDNA .standart').hide();
                        $('#popup_VisualDNA .garant').show();
                    }
                } else $('#popup_debit').show('slow');
            },
            "json"
        );
    }




        $('#out_send_payout').click(function(){
            <?if ( $social_bonuses_today == 0) {?>
            if ( WT_Social_WallPost.showDialog(function(){ $('#out_send_payout').click(); } ) == true )
                return false;
            <? } ?>


            $('#popup_debit').hide('slow');
            if( $('#out_send_payout').hasClass('submit') ) return true;


            if( !$("#garant").is(":checked")  ){
                 mn.security_module
                    .init()
                    .show_window('withdrawal_standart_credit')
                    .done(standart_calc);

            }else {
                standart_calc();
                return true;
            }
            return false;
    });


    function showNext(){
        $('#popup_debit').show('slow');
        $('#popup_VisualDNA').hide();
    }
    
    function isCardSum(sm){
          
        for ( p in avail_card_credits )
            if ( p == sm)
                return true;
        return false;
    }
    
    function getCardTimes(sm){
          
        for ( p in avail_card_credits )
            if ( p == sm)
                return avail_card_credits[p];
        return [];
    }
    
    
    function on_sum_change(){
        if (get_account_type() == 'card' && $("#garant").is(":checked") ){
            var days = '';
            var sm = parseInt($('#select-sum').val());
            var times = getCardTimes(sm);
            console.log(times);
            for (t in  times ){
                 days += '<option value="' + times[t] + '">' + times[t] + '</option>';              
             }
            $('#select-period').html(days);    
            
        } else {
               
                for (i = 3; i <= 30; i++) {
                    var s = ($('#select-period').val() == i) ? " selected='selected'" : '';
                    days += '<option '+ s +' value="' + i + '">' + i + '</option>';              
                }
                $('#select-period').html(days);    
        }
        
    }

    function refreshFields(){
        var amount = '';
        
        var summ =  get_account_summ();
        for (var i = 50; i <= summ && i <= 1000; i += 50){
            var s = ('<?=$sum?>' == i) ? " selected='selected'" : '';
            if (  get_account_type()  != 'card' || !$("#garant").is(":checked")  || (get_account_type() == 'card' &&  isCardSum(i) ) )            
                amount += '<option' + s + ' value="' + i + '">' + i + '</option>';
        }
        
        
        $('#select-sum').html(amount);
        on_sum_change();
        
    }


    function recalculateRate(){
        var sum = parseInt($("#select-sum").val());
        if (isNaN(sum))
            sum = 0;
        var account_type = get_account_val();
        if ( get_account_type() == 'card' || get_account_type() == 'own_account')
            account_type = 6;

        console.log( 'account_type '+account_type );
        var max_loan_available_by_bonus = JSON.parse( '<?= json_encode( $accaunt_header['max_loan_available_by_bonus'] ) ?>' );
        var all_advanced_loans_out_summ_by_bonus = JSON.parse( '<?= json_encode( $accaunt_header['all_advanced_loans_out_summ_by_bonus'] ) ?>' );
        var all_active_garant_loans_out_summ_by_bonus = JSON.parse( '<?= json_encode( $accaunt_header['all_active_garant_loans_out_summ_by_bonus'] ) ?>' );

        $('#max_loan_avaliable').val( max_loan_available_by_bonus[account_type] );
        if((all_advanced_loans_out_summ_by_bonus[account_type] + sum < max_loan_available_by_bonus[account_type] ) /*&&
           (all_advanced_loans_out_summ_by_bonus[account_type] + sum + all_active_garant_loans_out_summ_by_bonus[account_type] <= net_loan_available_by_bonus[account_type]*3 )*/)
        {
            console.log('garant_span-show');
            $("#garant_span").show();
        } else{
            console.log('garant_span-hide');
			console.log('netloan'+max_loan_available_by_bonus[account_type]);
			console.log('advnced loan'+ all_advanced_loans_out_summ_by_bonus[account_type]);
			console.log('active loan'+ all_active_garant_loans_out_summ_by_bonus[account_type]);
            $("#garant_span").hide();
            $('#infoblock').hide();
        }

        if($("#garant").is(":checked"))
            $('#infoblock').show();
        else
            $('#infoblock').hide();
    }
    $().ready(recalculateRate);
    $("#select-sum").change(recalculateRate);
    $("#garant").change(refreshFields);
    $("#standart").change(refreshFields);
    $(function(){
            on_payment_account_change();
            try {
                  $('#payment_account').msDropDown(
                          {   animStyle: '',
                              on: {create: function(){$('.formRow.rowBonus').css('z-index', 9)},
                                open: function(){$('.formRow.rowBonus').css('z-index', 10)},
                                close: function(){$('.formRow.rowBonus').css('z-index', 9)}

                           }}
                              );
              } catch(e) {
                  alert(e.message);
              }
    });
</script>

<? $this->load->view(  'user/accaunt/blocks/renderVisualDNA_window', compact($id_user)); ?>

<!--
<center>
<a class="but bluebut" href="/page/creditreport" target="_blank"><?=_e('accaunt/credits_13')?></a>
</center> -->
<div id="popup_limit" class="popup_window">
    <div onclick="$('#popup_limit').hide('slow');" class="close"></div>
    <h2><?=_e('accaunt/credits_14')?></h2>
    <?=_e('accaunt/credits_15')?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit"><?=_e('accaunt/credits_16')?></a>
</div>


<div class="popup_window" id="report_send">
    <div class="close"  ></div>
    <div class="widget" style="margin-top:20px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_17')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="report_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <div style="text-align:center;">
                        <?=_e('accaunt/credits_18')?>
                    </div>

                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_19')?></label>
                    <div class="formRight">
                        <textarea name="message" rows="5" style="width: 100%;"></textarea>
                    </div>

                </div>
                <center>
                    <button class="button" type="submit" data-id="" class="confirm-button"  name="submit"><?=_e('accaunt/credits_20')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
</div>

<?if($notAjax){?>
   <br/>
   <center>
<iframe id='a02bae7c' name='a02bae7c' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=14&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a787cc6d&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=14&amp;cb={random}&amp;n=a787cc6d&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </center>
   <br/>
<?}?>


<?
if (!empty($credits)) {
    $i = 0;
    foreach ($credits as $item){
        $i++;
        if ($i > 3)
            break;

        $this->load->view('user/accaunt/blocks/renderCredit_part.php', compact("item"));
    } ?>
    <? if (count($credits) > 3): ?>
        <a class="button narrow see_else" href="<?=site_url('account/credits_enqueries')?>" style="margin-top:32px"><?=_e('accaunt/credits_21')?></a>
    <? endif; ?>
    <?
}
else
    echo "<div class='message'>"._e('accaunt/credits_22')."</div>";
?>

<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>

<div id="arbitrage_repayment" class="popup_window" >
    <div class="close"></div>
    <input type="hidden" class="id" name="id" value=""/>
    <h2><?=_e('accaunt/credits_23')?></h2>
    <?=_e('accaunt/credits_24')?>
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
        <button type="submit" class="button confirm-button" name="submit"><?=_e('accaunt/credits_25')?></button>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    </center>
</div>

<div class="popup fixed-float" id="arbitrage_prolongation">
    <div class="close"  onclick="$(this).parent().hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
            <h6><?=_e('accaunt/credits_26')?></h6>
        </div>
        <fieldset>
                <input type="hidden" class="time" name="time" value=""/>
                <input type="hidden" class="summa" name="summa" value=""/>
                <input type="hidden" class="id" name="id" value=""/>
                <div class="formRow">
                    <label style="margin-top:5px;"><?=_e('accaunt/credits_27')?></label>
                    <div class="formRight">
                        <select class="form_select period-add" name="period-add" style="width: 234px;">
                        </select>
                    </div>
                </div>
                <div class="formRow">
                    <label style="margin-top:10px;"><?=_e('accaunt/credits_28')?></label>
                    <div class="formRight">
                        <input type="text" class="amount" name="amount"  disabled="disabled" style="color: black; width: 214px;" value="" />
                    </div>
                </div>
                <center>
                    <button type="submit" class="button confirm-button" name="submit"><?=_e('accaunt/credits_29')?></button>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
        </fieldset>
    </div>

</div>
<div id="popup_confirm_return" class="popup_window" >
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_30')?></h2>
    <?=_e('accaunt/credits_31')?>
    <button class="button confirm-button" type="submit" name="submit"><?=_e('accaunt/credits_32')?></button>
</div>

<div id="popup_alfa"  class="popup_window" style="width:800px;height:85%;top:5%;left:50%;margin-left:-400px">
    <div class="close"></div>
    <!--<iframe src="https://3ds.payment.ru/P2P_PSBR/card_form.html" frameborder="0" width="95%" height="95%" align="center">-->
    <?=_e('accaunt/credits_33')?>
    </iframe>
</div>



<div class="popup_window small" id="user_popup" style="z-index: 100;">
    <div class="close"></div>
    <div class="content" style="margin-top: 10px;"></div>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
</div>

<div class="popup_window" id="popup_agree_confirm">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_34')?></h2>
    <?=_e('accaunt/credits_35')?>
    <div class="button" style="left: 20px; position: relative;">
        <input type="submit" id="confirm_invest" name="submit" value="" /><?=_e('accaunt/credits_36')?>
    </div>
</div>

<div class="popup_window" id="popup_confirm_payment">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_37')?></h2>
    <?=_e('accaunt/credits_38')?>
    <div class="button" style="left: 20px; position: relative;">
        <input type="submit" class="confirm-button" name="submit" value="" /><?=_e('accaunt/credits_39')?>
    </div>
</div>

<div  class="popup_window" id="popup_load">
    <center><?=_e('accaunt/credits_40')?><br/>
        <img src="/images/loaders/loader12.gif" /></center>
</div>



<div class="popup" id="send_money">
    <div class="close"  onclick="$('#send_money').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
            <h6><?=_e('accaunt/credits_41')?></h6>
        </div>
        <fieldset>
            <form method="POST" id="mc_form" action="" accept-charset="utf-8">
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_42')?></label>
                    <div class="formRight">
                        <input type="text" name="reciver" disabled="disabled" style="color: black"    value=""/>
                    </div>
                </div>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('accaunt/credits_43')?></label>
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
                            <?/*option value="bonus_6" data-type="bonus">WTDEBIT - $ <?=price_format_double($summ_b6)?></option>*/ ?>
                            <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>
                <center>
                    <div class="button narrow" style="position: relative;margin-bottom:10px;"><input  type="submit" data-id=""   id="out_send_return" name="submit" value="" /><?=_e('accaunt/credits_44')?></div>
                    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                </center>
            </form>
        </fieldset>
    </div>
    <br/>
    <!-- <a href="#" class="but cancel other_methods"><?=_e('accaunt/credits_45')?></a> -->
</div>

<div class="popup_window" id="other_methods">
    <div class="close"></div>
    <h2><?=_e('accaunt/credits_46')?></h2>
    <?=_e('accaunt/credits_47')?>
    <br/><br/>
    <a href="#" class="but cancel alfa"><?=_e('accaunt/credits_48')?></a>
    <a href="#" id="open_rekviziti" class="but cancel bluebut"><?=_e('accaunt/credits_49')?></a>
</div>

<div class="popup" id="rekviziti">
    <div class="close"  onclick="$('#rekviziti').hide('slow');" ></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/credits_50')?></h6>
        </div>
        <fieldset>

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

<? //$this->load->view('user/accaunt/adv/adv') ?>