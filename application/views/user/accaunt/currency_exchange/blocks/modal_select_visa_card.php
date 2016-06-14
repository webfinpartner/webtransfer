<div id="select_payment_visa_card" class="popup_window_exchange valid_sendconfirmation">
<script>
    var get_cards_number = "<?=site_url('account/currency_exchange/ajax_get_cards_user')?>";

    $.post( get_cards_number,'',
        function(resTxt){
            var res = $.parseJSON(resTxt);
            container =  $('.see-cards');
            if(res['success'] == 1) {
                text = '<div class="col visa_card" style="margin: 0 auto; width: 366px;" id="form_type" ><select class="form_select " style="width: 100%; margin:0">';


                for (var i = 0 ; i < res['result'].length; i++) {
                    text+='<option value="'+res['result'][i]['id']+'">'+res['result'][i]['name']+'</option>';
                };
                text += '</select></div>';

                container.after(text);
                container.after('<div class="clear"></div>');
            } else {
                // Если у нас нету карты то выводим сообщение
                $('#select_payment_visa_card h2').html(res['text']);
            }
        }
    );
</script>
    <?
   // print_r($this->_ci_cached_vars['payment_systems']);
    ?>
    

    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2> <?=_e('Выберите карту для оплаты'); ?>
    <div class="price">
        <p style="text-align:center">
        <?=_e('Сумма к перечислению')?>:
        <span></span> USD
        </p>
    </div> 
    </h2>
    <div class="content_container" style="text-align: left; display: none;"></div>
        <div class="origin-id-data" style="display:none !important"></div>
        <div class="see-cards"></div>
        <button class="button" type="button" id="confirmation_block" onclick="validate_and_send_confirmation_form_pay_visa_card($(this).parent(), this); return false;" style="width: 366px; background-color: #ccc;" >
            <?= _e('Перевести средства')?>
        </button>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
        <div class="info-text-error" style="display:none; color:red; text-align:center"></div>

</div>