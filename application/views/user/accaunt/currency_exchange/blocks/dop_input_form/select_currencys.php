<div class="container_currency_href">
    <!--<a href="" onclick="show_currencys_list($('input[name=select_curency_<? //=$checkbox_name?>\[<? //= $payment_system->machine_name ?>\]]'), $('.selec_currency_container_<?//=$payment_system->id?>')); return false;" >-->
    <a href="" onclick="show_currencys_list($('.selec_currency_container_<?=$checkbox_name?>_<?=$payment_system->id?>'), $('.popup_window_currencys_list_container')); return false;" >
        <?=_e('Валюта')?> <span class="select_currency_span">(<?=_e('Не выбранна')?>)</span>
    </a>
    <input type="hidden" name="select_curency_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]" value="0" data-show-currencys-list="show_currencys_list($('.selec_currency_container_<?=$checkbox_name?>_<?=$payment_system->id?>'), $('.popup_window_currencys_list_container'));" />
</div>

<?php /*  country_id ?>
<div class="selec_currency_container selec_currency_container_<?=$checkbox_name?>_<?=$payment_system->id?>" style="display: none;">
    <?=_('Вылюта')?>:
    <select onchange="select_currency($(this), '<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]');" data-input-name="select_curency_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]">
        <option value="0"></option>
        <?php foreach($currencys as $cur): ?>
        <!--<option value="<?//=$cur->id?>">-->
        <option value="<?=$cur->num?>" data-currency="<?=_e($cur->code);?>">
            <?=_e($cur->location);?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?=_e($cur->code);?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<?php */ ?>
<?php
//    95
//228
$currency_select = false;
$currencys_select = $currencys;

if(!empty($payment_system->country_id) && ($currencys_country_id[$payment_system->country_id]->code == 'USD' || $currencys_country_id[$payment_system->country_id]->code == 'EUR'))
{
    $currency_select = [];
    $currency_select[] = $currencys_id[228];
//    $currency_select[] = $currencys_id[95];
}
elseif(!empty($payment_system->country_id))
{
    $currency_select = [];
    $currency_select[] = $currencys_country_id[$payment_system->country_id];
    $currency_select[] = $currencys_id[228];
//    $currency_select[] = $currencys_id[95];
}
?>
<div class="selec_currency_container selec_currency_container_<?=$checkbox_name?>_<?=$payment_system->id?>" style="display: none;">
    <div class="control-group currency_select_box_container">
        <div class="controls">
         <select id="single" class="select2 input-default" style="width: 400px;" onchange="select_currency($(this), '<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]');" data-input-name="select_curency_<?=$checkbox_name?>[<?= $payment_system->machine_name ?>]">
            <option value="0"></option>
            <?php foreach($currency_select as $cur): ?>
            <option value="<?=$cur->num?>" data-currency="<?=_e($cur->code);?>">
                <?=_e($cur->location);?>        
                {<?=_e($cur->code);?>}
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
</div>
<?php  ?>
