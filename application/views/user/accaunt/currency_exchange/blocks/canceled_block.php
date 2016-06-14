<div id="popup_canceled" class="popup_window_exchange" style="width: 24%; left:35%;">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <h2><?= _e('currency_exchange/canceled_block/cancel')?></h2>
    <?= _e('currency_exchange/canceled_block/you_shore')?>
    

    <form class="add_foto" method="post" action="" enctype="multipart/form-data">
        <div class="result" style="margin-top: 15px;"></div>
        <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
    
        <button class="button" type="submit" class="close" style="display: none;" >Ok</button>
    
        <input type="hidden" name="exchange_id" value="" />
        <!--Выберите документ: <input id="foto" type="file" name="foto" size="25" >-->
        <input type="hidden" name="cancel_action" value="1" />
        <center>
        <button class="button" type="submit" id="confirmation_block" onclick="$(this).parent().find('.loading-gif');" style="display: inline-block;float:none;margin-left: 0;"><?= _e('currency_exchange/canceled_block/yes')?></button>
        <button class="button" type="button" onclick="$(this).parent().parent().parent().hide();" style="display: inline-block;float:none;margin-left: 0;"><?= _e('currency_exchange/canceled_block/no')?></button>
        </center>
    </form>
</div>