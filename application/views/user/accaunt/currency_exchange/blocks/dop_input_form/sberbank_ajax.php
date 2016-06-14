<!--<div class="dop_ps_input_data_<?//=$payment_system->id?>">-->
    <!--<h2><?//=_e('Сбербанк');?></h2>-->
    <br/>
    <br/>
    <!--<form method="post" action="<? //=site_url('account/currency_exchange/ajax/set_dop_ps_data_multi_field')?>" enctype="multipart/form-data">-->
    <input type="hidden" name="id" value="<?=$payment_system->id?>" />
    <span style="display: inline-block; width: 110px; text-align: right; padding-right: 5px"><?= _e('Рублёвый счёт')?></span>
    <input type="radio" name="selector" value="requizites" onclick="$(this).parent().find('div.cont_card').hide(); $(this).parent().find('div.cont_requizites_universal').hide(); $(this).parent().find('div.cont_requizites').show();" <?=empty(@$ps_user_data['selector']) || @$ps_user_data['selector']=='requizites'?'checked':'';?>>

    <span style="display: inline-block; width: 65px; text-align: right; padding-right: 5px"><?= _e('Карта')?></span>
    <input type="radio" name="selector" value="card" onclick="$(this).parent().find('div.cont_card').show();$(this).parent().find('div.cont_requizites').hide();$(this).parent().find('div.cont_requizites_universal').hide();" <?=@$ps_user_data['selector']=='card'?'checked':'';?>>

    <span style="display: inline-block; width: 130px; text-align: right; padding-right: 5px"><?= _e('Валютный счёт')?></span>
    <input type="radio" name="selector" value="requizites_univesal" onclick="$(this).parent().find('div.cont_requizites_universal').show(); $(this).parent().find('div.cont_card').hide(); $(this).parent().find('div.cont_requizites').hide();" <?=@$ps_user_data['selector']=='requizites_univesal'?'checked':'';?>>
    <br/>
    <br/>

        <div class="cont_requizites" <?=(!empty($ps_user_data['selector']) && @$ps_user_data['selector']!='requizites')?'style="display:none;"':'';?>>
            <div class="dop_ps_input_field_container">
                <span class="label_text" ><?= _e('Получатель')?></span> 
                <input type="text" name="recipient" value="<?= @$ps_user_data['recipient'] ?>" style="border: 1px solid #ddd;"/><br/>
            </div>
            <div class="dop_ps_input_field_container">
                <span class="label_text" ><?= _e('Расчетный счет')?></span> 
                <input type="text" name="rs" value="<?= @$ps_user_data['rs'] ?>" style="border: 1px solid #ddd;"/><br/>
            </div>
            <div class="dop_ps_input_field_container">
                <span class="label_text" ><?= _e('БИК')?></span> 
                <input type="text" name="bik" value="<?= @$ps_user_data['bik'] ?>" style="border: 1px solid #ddd;"/><br/>
            </div>
            <div class="dop_ps_input_field_container">
                <span class="label_text" ><?= _e('Наименование Банка')?></span> 
                <?php /* ?>
                <input type="text" name="name" value="<?= @$ps_user_data['name']?$ps_user_data['name']:_e('ОАО СБЕРБАНК РОССИИ (Г. МОСКВА)'); ?>" style="border: 1px solid #ddd;"/><br/>
                <?php */ ?>
                <input type="text" name="name" value="<?= !empty($ps_user_data['name'])?$ps_user_data['name']:Currency_exchange_model::get_ps($payment_system->id)->humen_name?>" style="border: 1px solid #ddd;"/><br/>
            </div>

            <div class="dop_ps_input_field_container">
                <span class="label_text"><?= _e('Кор. счет')?></span> 
                <input type="text" name="ks" value="<?= @$ps_user_data['ks'] ?>" style="border: 1px solid #ddd;"/><br/>
            </div>
            <!--<span style="display: inline-block; width: 170px;"><?//= _e('ИНН')?></span> <input type="text" name="inn" value="<?//= @$ps_user_data['inn'] ?>" /><br/>-->
            <?php /* ?>
            <span style="display: inline-block; width: 170px; text-align: right; padding-right: 5px"><?= _e('Назначение')?></span> 
            <input type="text" name="destination" value="<?= @$ps_user_data['destination'] ?>" placeholder="<?//=_e('В счет оплаты покупки ценных бумаг по договору WMID # от . НДС не облагается.');?>" style="border: 1px solid #ddd;"/><br/>
            <?php */ ?>
            <button class="button" type="button" onclick="send_form_dop_ps_input_data($(this).parent().parent(), $(this).parent().find('input[name=recipient]').val())" style="width: 366px;" >
                <?= _e('Сохранить')?>
            </button>
        </div>

        <div class="cont_card" <?= empty(@$ps_user_data['selector']) || @$ps_user_data['selector']!='card' ?'style="display:none;"':'';?>>
            <span class="label_text"  ><?= _e('Номер карты')?></span>
            <!--<input type="text" name="cart_number[1]" value="<?= @$ps_user_data['cart_number'][1] ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/> --->
            <input type="text" name="cart_number[1]" value="<?= !empty($ps_user_data['cart_number'][1])?'****':''; ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/> -
            <input type="text" name="cart_number[2]" value="<?= !empty($ps_user_data['cart_number'][2])?'****':''; ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/> -
            <input type="text" name="cart_number[3]" value="<?= !empty($ps_user_data['cart_number'][3])?'****':''; ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/> -
            <!--<input type="text" name="cart_number[4]" value="<?= !empty($ps_user_data['cart_number'][4])?'****':''; ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/>-->
            <input type="text" name="cart_number[4]" value="<?= @$ps_user_data['cart_number'][4] ?>" style="border: 1px solid #ddd; width: 34px;" maxlength="4"/>
            <br/>
            <br/>
            <span class="label_text" ><?= _e('Получатель')?></span> 
            <input type="text" name="card_recipient" value="<?= @$ps_user_data['card_recipient'] ?>" style="border: 1px solid #ddd; width: 182px;"/><br/>
            <button class="button" type="button" onclick="send_form_dop_ps_input_data($(this).parent().parent(), '***-****-****-'+$(this).parent().find('input[name=\'cart_number[4]\']').val())" style="width: 366px;" >
                <?= _e('Сохранить')?>
            </button>
        </div>

        <div class="cont_requizites_universal cont_requizites_universal_bank" <?=@$ps_user_data['selector']!='requizites_univesal'?'style="display:none;"':'';?>>

            <?php $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/_universal_bank_fields.php'); ?>

            <button class="button" type="button" onclick="send_form_dop_ps_input_data($(this).parent().parent(), $(this).parent().find('input[name=wire_beneficiary_account]').val())" style="width: 366px;" >
                <?= _e('Сохранить')?>
            </button>
        </div>
        <center><img src="/images/loading.gif" class="loading-gif"  style="display: none; width:15px;"></center>


    <!--</form>-->
<!--</div>-->
