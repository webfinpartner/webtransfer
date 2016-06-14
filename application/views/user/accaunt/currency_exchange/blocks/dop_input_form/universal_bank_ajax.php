<!--<div class="dop_ps_input_data_continer dop_ps_input_data_<?//=$payment_system->id?>">-->
     <br/>
     <br/>
     <input type="hidden" name="id" value="<?=$payment_system->id?>" />
     <div class="cont_requizites cont_requizites_universal_bank" <?//=@$ps_user_data['selector']=='card'?'style="display:none;"':'';?>>

         <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields.php'); ?>

         <button class="button" type="button" onclick="send_form_dop_ps_input_data($(this).parent().parent(), $(this).parent().find('input[name=wire_beneficiary_account]').val())" style="width: 366px;" >
             <?= _e('Сохранить')?>
         </button>
     </div>

     <img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;">
<!--</div>-->
