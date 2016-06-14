<div id="dop_ps_select_country_<?=$checkbox_name?>" class="popup_window_exchange" style="width: 630px; height: 315px">
    <div class="close" onclick="$(this).parent().hide();"></div>
    <div class="country_content_container selec_country_container_<?=$checkbox_name?>">
        <h2><?=_e('Страна')?></h2>
        <div class="selec_country_container">
            <select style="width: 400px;" onchange="show_country_content($('#dop_ps_select_country_<?=$checkbox_name?> .countent_country_overall_bank_select_<?=$checkbox_name?>'), $(this).val(), '<?=$checkbox_name?>', $('.content_<?=$checkbox_name?>_country_'+$(this).val()), $('.content_<?=$checkbox_name?>_country'));">
                <option value="0"></option>
                <?php foreach($countris as $country): ?>
                    <option value="<?=$country->id?>"><?=_e($country->country_name_ru);?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="bank_content_container">
        <div style="display: none;" class="countent_country_overall_bank_select countent_country_overall_bank_select_<?=$checkbox_name?>" style="display: none;">
            <h2><?=_e('Банк')?></h2>
            <center><img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;"></center>
            <div></div>
        </div>
    </div>
    
    <div style="display: none;">
        <h2><?=_e('Выбрать валюту')?></h2>
        <div class="currency_container_common_popup"></div>
    </div>
    
    <br/>
    
    <input type="hidden" class="hidden_input_selected_bank_<?=$checkbox_name?>" value="0" />
    <button class="button" type="button" onclick="$(this).parent().hide(); show_dop_ps_input_data($(this).parent().find('input[class^=hidden_input_selected_bank_]').val(), '<?=site_url('account/currency_exchange/ajax/get_dop_ps_data_multi_field')?>', '<?=site_url('account/currency_exchange/ajax/set_dop_ps_data_multi_field')?>');" style="display: none;" >
        <?=_e('Ok');?>
    </button>
    <br/>
    <div class="clear"></div>
    <center><img class='loading-gif' style="display: none" src="/images/loading.gif"/></center>
    <div class="clear"></div>
</div>
<script>
    $(document).ready(function(){
        $('#dop_ps_select_country_<?=$checkbox_name?> .selec_country_container select').select2();
    });
</script>