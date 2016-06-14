<? $this->load->view('user/accaunt/card/blocks/tools') ?>

    
   <br/><br/>
   
<h3><?=_e('Активные карты')?></h3>
<table cellspacing="0" class="payment_table">

<tbody>
<? foreach ($wtcards_list['ACTIVE'] as $card ) { ?>  
<tr>
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
</td>
<td class="payment_limits" style="padding:10px;width:250px;">
NAME ON CARD: <?=$card->name_on_card?><br/>
4665 **** **** <?=substr($card->pan,-4)?> EXP: <?=$card->details['cardDetail']['expiryDate']?><br/>
TYPE: <?=$card->details['cardDetail']['cardType']?> (<?=$card->details['kyc']?>)<br/>
STATUS: <?=$card->details['cardDetail']['cardStatus']?><br/>
<?if ($card->details['kyc']=='LEVEL_1'){?>LIMIT: $2,500 (<a href="https://webtransfercard.com/en/login?forcewt=true" target="_blank"><?=_e('убрать лимит')?></a>)<? } ?>
</td>
<td class="payment_restrictions" style="text-align:center;width:100px;"><p>$ <span><?=$card->last_balance?></span>
    <a href='#' onclick="get_card_balance( <?=$card->id?> , 0, $(this).next('#balance_loading-gif'), $(this).prev() );return false;"><img src="/images/reload.png"></a>
    <img id='balance_loading-gif' style="display: none" src="/images/loading.gif"/>
    </p></td>
<td class="payment_description">
<select data-id="<?=$card->id?>" onchange="on_menu_select( this, $(this).data('id'), $(this).find(':selected').val() )">
<option value="menu"><?=_e('Меню')?></option>
<option value="pay"><?=_e('Пополнить карту')?></option>
<?if (count($wtcards_list['ACTIVE']) > 1 ) { ?>
<option value="send"><?=_e('Перевести на свою карту')?></option>
<? } ?>
<!-- <option value="outsend"><?=_e('Перевести')?></option> -->
<option value="block"><?=_e('Заблокировать')?></option>
<option value="updateData"><?=_e('Обновить данные')?></option>
<? if($card->card_type==Card_model::CARD_TYPE_PLASTIC) { ?>
    <option value="pin"><?=_e('Посмотреть пин код')?></option>
<? } ?>
<? /* if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) { ?>
<option value="info"><?=_e('Посмотреть детали карты')?></option>
<? } */ ?>

<option value="last_transactions"><?=_e('Последние транзакции')?></option>
</select>
</td>
</tr>
<? } ?>

</tbody>
<a style="display: block;
    position: absolute;
    top: 90px;
    margin-left: 622px;" target="_blank" href="http://help.webtransfercard.com<?if($this->lang->lang()!='ru'){ ?>/en/<? } ?>"><span style="    color: #FF4F00;
    padding-right: 0;
    background: #FF5100;
    border-radius: 50px;
    color: #fff;
    text-align: center;
    width: 18px;
    margin-right: 4px;
    display: inline-block;
"><?=_e('?')?></span><?=_e('помощь')?></a>

</table>
 <br><br>
 <? /*
 <center><a href="#" onclick="$('#import_window').show(); return false;" style="text-decoration:underline;"><?=_e('Импорт из Webtransfercard.com')?></a></center>
  */ ?>
<br/><br/>


<? if (!empty($wtcards_list['READY_TO_ACTIVE']) || !empty($wtcards_list['NOT_PAID'])) { ?>
<h3><?=_e('Ожидает выпуска / активации')?></h3>
<br/>
<table cellspacing="0" class="payment_table">

<tbody>
<? if (!empty($wtcards_list['READY_TO_ACTIVE'] )) foreach ($wtcards_list['READY_TO_ACTIVE'] as $card ) { ?>  
<tr>
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
<td class="payment_limits" style="padding:10px;width:250px;">
NAME ON CARD: <?=$card->name_on_card?><br/>
4665 **** **** <?=substr($card->pan,-4)?> EXP: <?=$card->details['cardDetail']['expiryDate']?><br/>
TYPE: <?=$card->details['cardDetail']['cardType']?> (<?=$card->details['kyc']?>)<br/>
STATUS: <?=$card->details['cardDetail']['cardStatus']?>

</td>
<td class="payment_restrictions" style="text-align:center;width:100px;"></td>

<td class="payment_description" style="text-align:center;font-size:12px;line-height:14px;">
 <? if($card->card_type==Card_model::CARD_TYPE_PLASTIC) { ?><?=_e('Введите номер карты в формате XXXXXXXXXXXXXXXX')?><br/><? } ?>
    
 <form method="post">
<input name="card_id" type="hidden" value="<?=$card->id?>">
<input name="action" type="hidden" value="activate">
<? if($card->card_type==Card_model::CARD_TYPE_PLASTIC) { ?><input name="card_pan" value="" placeholder="" style="width:150px;margin:5px 0px;"> <br/><? } ?>
<a href="#" id="bank_trigger" class="but bluebut" onclick="$(this).parent('form').submit(); return false" style="cursor: pointer;margin-left:0px;"><?=_e('подтвердить активацию')?></a>
</form>
</td>
</tr>


<? } ?>
<? $codes = get_coutry_codes();?>
<? if( !empty($wtcards_list['NOT_PAID'])) foreach ($wtcards_list['NOT_PAID'] as $card ) { ?>  
<tr>
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
<td class="payment_limits" style="padding:10px;width:250px;">
<?=$card->holder_name?><br/>
<b><?=_e('Адрес доставки:')?></b><br/>
<?=$card->delivery_address?><br/>
<?=_e($codes[$card->delivery_country])?>, <?=$card->delivery_city?> <?=$card->delivery_zip_code?><br/>
</td>
<form method="post" id="r_<?=$card->id?>">
<input name="request_id" type="hidden" value="<?=$card->id?>">
<input name="action" type="hidden" value="pay">
<td class="payment_restrictions" style="text-align:center;width:100px;">
   <!-- <a href="#" class="but bluebut" onclick="$('#r_<?=$card->id?>').submit();return false;" style="cursor: pointer;margin-left:0px;"><?=_e('оплатить $')?><?=$card->cost?></a>--></td>
</form>
<td class="payment_description" style="text-align:center;font-size:12px;line-height:14px;">
    <a href="<?=site_url('account/card')?>/<?=$card->id?>" id="bank_trigger" class="but bluebut" style="cursor: pointer;margin-left:0px;"><?=_e('редактировать')?></a><br/><br/>
<form method="post">
<input name="request_id" type="hidden" value="<?=$card->id?>">
<input name="action" type="hidden" value="cancel">
<a href="#" id="bank_trigger" class="but bluebut" onclick="if (window.confirm('Вы уверены что хотите отменить  заявку?')) $(this).parent('form').submit();return false;" style="cursor: pointer;margin-left:0px;"><?=_e('отменить заказ')?></a>
</form>
</td>
</tr>
<? } ?>
</tbody>
</table>
<? } ?>

<?  foreach( $wtcards_list['BLOCKED'] as $b) $wtcards_list['SUSPENDED'][] = $b; ?>
<?  foreach( $wtcards_list['FRAUD'] as $b) $wtcards_list['SUSPENDED'][] = $b; ?>
<? if (!empty($wtcards_list['SUSPENDED'])) { ?>
<h3><?=_e('Заблокированные карты')?></h3>
<table cellspacing="0" class="payment_table">

<tbody>
<? foreach ($wtcards_list['SUSPENDED'] as $card ) { ?>  
<tr>
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
</td>
<td class="payment_limits" style="padding:10px;width:250px;">
NAME ON CARD: <?=$card->name_on_card?><br/>
4665 **** **** <?=substr($card->pan,-4)?> EXP: <?=$card->details['cardDetail']['expiryDate']?><br/>
TYPE: <?=$card->details['cardDetail']['cardType']?> (<?=$card->details['kyc']?>)<br/>
STATUS: <?=$card->details['cardDetail']['cardStatus']?>
</td>
<td class="payment_restrictions" style="text-align:center;width:100px;"><p>$ <span><?=$card->last_balance?></span>
    <a href='#' onclick="get_card_balance( <?=$card->id?> , 0, $(this).next('#balance_loading-gif'), $(this).prev() );return false;"><img src="/images/reload.png"></a>
    <img id='balance_loading-gif' style="display: none" src="/images/loading.gif"/></p></td>
<td class="payment_description">
<select data-id="<?=$card->id?>" onchange="on_menu_select( this, $(this).data('id'), $(this).find(':selected').val() )">
<option value="menu"><?=_e('Меню')?></option>
<? if ($card->details['cardDetail']['cardStatus'] != 'FRAUD') {?>
<option value="unblock"><?=_e('Разблокировать')?></option>
<? }?>
<option value="last_transactions"><?=_e('Последние транзакции')?></option>
</select>
</td>
</tr>
<? } ?>

</tbody>
</table>
<? } ?>

