<div class="popup_window" id="sendMessage">
    <div class="close" onclick="$('.popup_window').hide('slow');"></div>
    <h2><?=_e('Отправить сообщение контрагенту')?></h2>
    <textarea id="sendMessage_msg" style="width: 300px; height: 70px"></textarea>
    <button class="button" type="submit" onclick="$(this).parent().hide('slow'); sendMessagePost()"><?=_e('Отправить')?></button>
</div>