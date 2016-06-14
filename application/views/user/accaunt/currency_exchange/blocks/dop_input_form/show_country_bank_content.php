<div class="container_select_bank">
    <?php /*?><span class="label"><?=_e('Банк')?>:</span><?php */ ?>
    <select class="select_country_banks"  onchange="add_bank_in_payment_system($(this).val(), $(this), $('.countent_country_overall_<?=$checkbox_name?>'), '<?=$checkbox_name?>');">
        <option value="0" disabled selected><?=_e('Выбрать банк')?></option>
        <?php foreach($pay_sys as $ps): ?>
            <option value="<?=$ps->id?>" data-checkbox-name="<?=$checkbox_name?>[<?= $ps->machine_name ?>]">
                <?php/* [<?=Currency_exchange_model::get_ps($ps->id)->humen_name;?>]{<?=Currency_exchange_model::get_ps($ps->id)->swift_code;?>}  */ ?>
                [<?=Currency_exchange_model::get_ps($ps->id)->humen_name;?>]
            </option>
        <?php endforeach; ?>
    </select>
</div>