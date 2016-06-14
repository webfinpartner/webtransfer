<script>
function receiveWaitingInvest(){
      var id =  $('#recive_waiting_invest_dialog').data('id');
      var card_id =  $('#recive_waiting_invest_dialog select[name=card_id]').val();
      $('#recive_waiting_invest_dialog .loading-gif').show();
      $('#recive_waiting_invest_dialog_error').hide();
      
      $.post(site_url +  "/account/ajax_user/receiveWaitingInvest", { card_id: card_id, id: id},  function(data){
          $('#recive_waiting_invest_dialog .loading-gif').hide();
          $('#recive_waiting_invest_dialog_error').show();
          $('#recive_waiting_invest_dialog_error').html(data.message);
          if ( data.status == "success"){
              window.setTimeout(function() {  
                  $('#recive_waiting_invest_dialog').hide();  
                  window.location.reload(); 
              }, 2000);
             
             
          } 
              
      }, "json");
            
}

</script>

<div class="popup lava_payment" id="recive_waiting_invest_dialog">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget">
        <div class="title"><img class="titleIcon" src="images/icons/dark/pencil.png" alt="">
            <h6><?=_e('Получить прибыль по вкладу на карту')?></h6>
        </div>
            <div class="formRow">
                <label style="padding-top:10px"><?=_e('Выберите карту')?>:</label>
                <div class="formRight">
                    <select name="card_id">
                        <? if (!empty($wtcards)) foreach( $wtcards as $card ){ ?>
                                <option value="<?=$card->id?>" data-image="<?=  Card_model::get_card_icon($card) ?>" data-summ="<?=$rating_by_bonus['max_loan_available_by_bonus'][6]?>" data-type="card"><?=Card_model::display_card_name($card, TRUE)?> - $ <?=price_format_double($card->last_balance)?></option>                            
                        <? } ?>
                    </select>
                </div>
            </div>
    </div>
    <center>
        <button class="button" onclick="receiveWaitingInvest()" type="button" name="submit"><?=_e('Получить')?></button>
        <p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
        <p class="center error" id="recive_waiting_invest_dialog_error" style="display: none;"></p>
    </center>
</div>