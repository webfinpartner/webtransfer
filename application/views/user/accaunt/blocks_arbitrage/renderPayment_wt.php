<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="wt_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>

            <form action="https://www.wt.com/merchant.jsp" method="post" class="form" id="wt_form">
                <input type="hidden" name="code" id="form_code" value="1"/>
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('block/data6')?></label>
                    <div class="formRight">
                        <input type="text" name="summa" class="maskMoneyCustom" value=""/>
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                
                <div class="formRow">
                    <label style="margin-top:20px;"><?=_e('Счет')?></label>
                    <div class="formRight">
                            <select id="payment_account" name="payment_account" style="padding: 7px 6px;width: 90%;margin-bottom:10px;color:#000000;"><? 
                            echo "<option data-type='none' data-summ='0' value='-'>"._e('Выберите счет')."</option>";
                            /*if ( $rating_bonus['payout_limit_by_bonus'][2] > 0)
                            echo "<option style='color:red' data-type='pa' value='2'$selected>WTUSD<span style='color:red'>&#10084;</span> - $ ".price_format_double($rating_bonus['payout_limit_by_bonus'][2])."</option>"; */                                   
                            if ( $rating_bonus['max_garant_vklad_real_available_by_bonus'][6] > 0)
                            echo "<option data-type='pa' value='6'$selected>WTDEBIT- $ ".price_format_double($rating_bonus['payout_limit_by_bonus'][6])."</option>";                                    
                            ?>
                            </select>    
                    </div>
                    <div class="error" style="display:none" ><?=_e('block/data16')?></div>
                </div>
                
            <? make(Content::BLOCK_ARBITRAGE_PART, "offert");?>
                <input  type="submit" style="display:none" class="send" />

                <button class="button" onclick="return false;"  id="wt_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            </form>
        </fieldset>
    </div>
</div>
<script>
    var security = '<?=$security ?>';

    function standart_calc(){
        $('#form_code').val( $('#code').val() );
        $('#wt_send').addClass('submit').click();
    }
</script>
<script>
    $('.wt_trigger').click(function() {
        resetWindowArbitrage();
        $('p.error').hide();
        get_payment_permission(function(){
           $("#wt_payment").fadeIn();
        });
    }).css('cursor', 'pointer');


    function precess_wt_send( res )
    {
        var code = 0;
        var pa = $('#payment_account').val();

        if( res !== undefined )
        {
            if( res['res'] === undefined || res['res'] != 'success' ) return false;
//            $('#form_code').val( res['code'] );
            code = res['code'];
        }
        
        mn.security_module.loader.show();
        
        $.post(site_url + '/arbitrage/arbitrage_invest_from_send', {
            summa: $('#wt_form [name="summa"]').val(),
            metod: 'wt',
            code: code,
            payment_account: pa,
            agree: $('#wt_form [name="agree"]').prop('checked')
        },
        function(data) {
            $('#wt_send').removeClass('submit')
            if(data.error){$('p.error').text(data.error);$('p.error').show();$('.loading-gif').hide();return;}
//            $('.loading-gif').hide();
            $('p.error').text(data.ok);
            $('p.error').show();
            window.location.href = site_url + '/account/transactions';
        }, 'json');
    }

    $('#wt_send').click(function() {
        $('p.error').hide();
        
        var pa = $('#payment_account').val();
        if ( pa == '-'){
            $('p.error').text('<?=_e('Выберите счет')?>');
            $('p.error').show();            
            return;
        }        
        
        if(!cahckSumm("wt_form", '[name="summa"]'))
            return false;
        if( !$(this).hasClass('submit')){
            
            mn.security_module
                .init()
                .show_window('save_p2p_payment_data')
                .done( precess_wt_send );
        
            return false;
        }
        
        precess_wt_send();
        
        
        return false;
    });
</script>