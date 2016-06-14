<!--popup-->
<style>
    #ui-datepicker-div{
        z-index: 17 !important;
    }

</style>
<div class="excel_export" style="display: none; z-index: 16; background-color: #000000; position: fixed; top: 0px; bottom: 0px; left: 0px; right: 0px; opacity: 0.7;"> </div>
<div class="popup_window excel_export" style="display: none; z-index: 17 !important; line-height: 27px; transition: 3s;">
	<div class="close" onclick="$('.excel_export').hide();" style="z-index: 7"></div>
	<div style="position: relative; height: 100%; width: 100%; z-index: 6">
	    <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
	        <img class="loading-gif" style="margin-top: 100px" src="/images/loading.gif">
	    </div>
	    <h2><?=_e('accaunt/transaction_11')?></h2>
		<p></p>
		<?=_e('accaunt/transaction_12')?>
                <form method="GET" action="<?=site_url("/account/export/$type")?>" style="text-align:left; padding: 9px;">
                    <?if(isset($arhive)){?><input type="hidden" name="arhive" value="arhive" /><?}?>
                    <div id="filter" style="text-align: center;">
                        <? if ($type == 'order_archive') {
                            ?>
                            <line><?=_e('accaunt/transaction_16')?></line>  <input type="text" name="date_1" id="date_1" value="06/01/2014" /> <line><?=_e('accaunt/transaction_17')?></line> <input type="text" name="date_2" id="date_2" value="<?=date('d/m/Y')?>" />  <br />

                            <label for="pop_1"><input id="pop_1" type="radio" name="type" value="1" checked="checked"  /> <?=_e('export_excel/all')?>  </label>
                            <label for="pop_2"><input id="pop_2" type="radio" name="type" value="2" /> <?=_e('export_excel/canceled')?></label>
                           
                            <?
                        } else {
                            ?>
                            <line><?=_e('accaunt/transaction_16')?></line>  <input type="text" name="date_1" id="date_1" value="<?=$date['first'][0]?>" /> <line><?=_e('accaunt/transaction_17')?></line> <input type="text" name="date_2" id="date_2" value="<?=$date['last'][0]?>" />  <br />
                            <label for="pop_6"><input id="pop_6" type="radio" name="type" value="6" <?=(($type == 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('DEBIT')?></label>
                            <label for="pop_1"><input id="pop_1" type="radio" name="type" value="5" <?=(($type != 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('WTUSD1')?></label>
                            <label for="pop_2"><input id="pop_2" type="radio" name="type" value="2" <?=(($type == 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('WTUSD&#10084;')?></label>
                            <label for="pop_3"><input id="pop_3" type="radio" name="type" value="3" <?=(($type == 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('P-CREDS')?></label>
                            <label for="pop_4"><input id="pop_4" type="radio" name="type" value="4" <?=(($type == 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('C-CREDS')?></label>
                            <label for="pop_5"><input id="pop_5" type="radio" name="type" value="1" <?=(($type == 1) ? 'checked="checked" ' : NULL)?> /> <?=_e('BONUS')?></label>
                            <?
                        }
                        ?>
                    </div>
                    <p></p>
                    <button class="button" type="submit"><?=_e('accaunt/transaction_15')?></button>
	    	</form>

	</div>
</div>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link href="/css/calendar-tt.css" rel="stylesheet">
<script src="/js/jquery-ui-1.10.4.custom.js"></script>
<script>

    $(document).ready(function(){
        $("#date_1").datepicker({
            maxDate: "+0"
        });

        $("#date_2").datepicker({
            maxDate: "+0"
        });
    })
</script>
<!--popup-->