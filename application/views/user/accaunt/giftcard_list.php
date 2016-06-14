<style>

    .payment_table tr td { text-align: center; }
    
</style>  
<script>
    function show_present_form(id){
        $('#present').show();
        $('#to_user_id').val('');
        $('#gift_card_id').val(id);
    }
    
    function activate(){
        return confirm("<?=_e('Вы уверены, что хотите обналичить карту?')?>");

    }
    
    function on_present_submit(){
        location.replace('<?=site_url('giftcard/present')?>/'+$('#gift_card_id').val()+'/'+$('#to_user_id').val() );
    }
</script>    
    
<table class="payment_table">
    <thead>
    <tr>
        <th><?=_e('Дата')?></th>
        <th><?=_e('Код')?></th>
        <th><?=_e("Номинал")?></th>
        <th><?=_e("Статус")?></th>
        <th></th>
    </tr>
    </thead>

        <tbody>
            <? foreach( $giftcards as $gcard){ ?>
                
            <tr>
                <td><?=date('d-m-Y',strtotime($gcard->date_buy))?></td>
                <td><?=$gcard->id?></td>
                <td>$ <?=$gcard->nominal?></td>
                <td><?=$gcard->status_text?> <?=$gcard->to_user_id?></td>
                <td><?if( $gcard->status == 0){?>
                    <a href="#" onclick="show_present_form(<?=$gcard->id?>); return false;">Подарить</a><br>
                    <a href="<?=site_url('giftcard/activate').'/'.$gcard->id?>" onclick="return activate();">Обналичить</a></td>
                <? } ?>
            </tr>
            
            <? } ?>
        </tbody>
</table>


<div class="popup_window" id="present">
    <div class="close"></div>
    <h2><?=_e('Подарить пользователю')?></h2>
    <input type="text" id="to_user_id">
    <input type="hidden" id="gift_card_id">
    <a href="#"  onclick="on_present_submit(); return false;" class="but cancel bluebut"><?=_e('Подарить')?></a>
</div>
