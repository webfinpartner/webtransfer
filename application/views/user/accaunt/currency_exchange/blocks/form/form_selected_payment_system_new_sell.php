<div style="margin-bottom: 15px;" class="new_sell_payment_sums">
    <div class="left_container_payment_buy_text step_2" >
        <div class="homeobmentable quick_selected_payment_buy" style="margin:10px 0 10px 9px;">
            <div id='null_take_ps'>
                    <a class="onehrine" style="background-color: #f9f9f9; width: 45px; position: relative; float: left; border: 1px solid rgb(235, 235, 235); margin-right: 1px;">
                       <div class="onehrineico" style="font-size: 35px; padding: 10px; color: #ccc;">?</div>
                       <div class="clear"></div>
                    </a>
                    <div class="input_container_div">
                       <?= _e('Сумма') ?>
                       <select class="form_select">
                          <option value="0" selected="">0</option>                     
                       </select>
                    </div>
               </div>
            <div class="clear"></div>
            
        </div>
        
        <div class="clear"></div>
    </div>

    <div class="right_container_payment_buy_text step_1">
            <div class="homeobmentable quick_selected_payment_sell" style="margin:10px 0 10px 9px;">               
               
               <div id='null_give_ps'>
                   <a class="onehrine" title="<?= _e('Выберите платежную систему') ?>" style="background-color: #f9f9f9; width: 45px; position: relative; float: left; border: 1px solid rgb(235, 235, 235); margin-right: 1px;">
                       <div class="onehrineico" style="font-size: 35px; padding: 10px; color: #ccc;">?</div>
                       <div class="clear"></div>
                    </a>
                    <div class="input_container_div">
                       <?= _e('Сумма') ?>
                       <select class="form_select">
                          <option value="0" selected="">0</option>                     
                       </select>
                    </div>
               </div>
               <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div style="width: 100%; height: 20px;"></div>
            <div class="clear"></div>
         </div>
<?php $form_folder = 'user/accaunt/currency_exchange/blocks/form/'; ?>
<?php $this->load->view($form_folder.'form_inputs_sell_new_sell.php', $input_array); ?>
</div>
<div class="clear"></div>