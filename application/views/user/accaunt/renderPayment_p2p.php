<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="popup lava_payment" id="p2p_payment">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data15')?></h6>
        </div>
        <fieldset>
                
                <? //$this->load->view(  'user/accaunt/blocks/renderPayment_selectBlock', compact($partners, $id_user) ); ?>
                <div class="formRow payment2id" style="<?if($isUserUS_CA){?>display: none;<?}?>">
                    <label style="margin-top:16px;"><?=_e('block/data18')?></label>
                    <div class="formRight">
                        <select  name="pament2id" class="form_select" style="margin: 10px 0; width: 90%">                            
                            <option value="5">WTUSD1</option>
                            <option style="color:red !important;" value="2" selected="selected" >WTUSD❤</option>
                            <option value="4">C-CREDS</option>
                        </select>             
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

                <button class="button" onclick="return false;"  id="p2p_send" name="submit" value=""><?=_e('block/data11')?></button>
                <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
                <p class="center error" style="display: none;"><?=_e('block/data12')?></p>
            
        </fieldset>
    </div>
</div>
<script>
    $(document).on('.p2p_trigger','click',function() {
        $('p.error').hide();        
        $("#p2p_payment").fadeIn();        
    }).css('cursor', 'pointer');

    $('#p2p_send').click(function() {
        $('p.error').hide();
        
        $('#p2p_form div.error').hide();
        $('.loading-gif').show();
        
        var currency = $('input[name="pament2id"]').val(); 
        var amount =  $('input[name="m_amount"]').val();
        
        location.replace("<?=site_url('account/currency_exchange/sell_search')?>?action=withdrawal&currency="+currency+'&summa='+amount);
        
        return false;
    });
</script>

