<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    .form-card {
        top: 60px !important;
        left: 50%;
        margin-left: -314px;
        padding: 25px;
        position: fixed;
        width: 525px;
    }
    .formRow{
        overflow: auto;
    }
    .formRow .formRight{
        padding-top: 9px;
    }
    .popup input[type=text], .popup input[type=password]{
        height: 11px;
        margin: 0;
    }
    #pin_wrap{
        cursor: pointer;
    }
    #pin_wrap:after {
        margin-left: 3px;
        background: url(/images/reload.png) no-repeat;
        content: " ";
        padding: 0 20px;
    }
</style>


        <div class="widget" style="margin-top:10px;">
            <div class="title">
                <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
                <h6><?= _e('new_272') ?></h6>
            </div>

			
            <form class="form" id="card" method="POST"  accept-charset="utf-8">
                <input type="hidden" name="card_type" value="<?=$card->card_type?>">
                <fieldset>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('Имя на карте') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="holder_name" data-filter="latin" data-register="upper" data-limiter="21" value="<?= $card->holder_name ?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_274') ?> / <?= _e('card/data6') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('regist_yur_n_name') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="name" data-limiter="20" data-filter="latin" value="<?= $card->name ?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_274') ?> / <?= _e('Лимит 20 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('regist_yur_f_name') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="surname" data-limiter="20" data-filter="latin" value="<?= $card->surname ?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_274') ?> / <?= _e('Лимит 20 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('new_42') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="birthday" value="<?= $card->birthday ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Старше 18 лет') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>

                    <div class="title">
                        <h6><?= _e('Адрес регистрации (прописки)') ?></h6>
                    </div>

                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('Адрес 1') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="prop_adress" data-limiter="35"  data-filter="latin_num_symbols" value="<?= $card->prop_adress ?>"/><br>
                            <span style="font-size:11px;"><?= _e('new_274') ?> / <?= _e('Лимит 35 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('profile/personal_info47') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="city" data-filter="latin" data-limiter="20"  value="<?= $card->city ?>"/><br>
                            <span style="font-size:11px;"><?= _e('new_274') ?> / <?= _e('Лимит 20 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('profile/personal_info46') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="zip_code" data-filter="latin_num" data-limiter="8"  value="<?= $card->zip_code ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Лимит 2-8 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>


                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('new_44') ?>:</label>
                        <div class="formRight">
                            <? $this->load->helper('form'); ?>
                            <?= form_dropdown('country', get_country(false, true), $card->country); ?>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>

                    <div class="title">
                        <h6><?= _e('Контакты') ?></h6>
                    </div>

                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('Телефон') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="phone_mobile" data-limiter="15"  data-filter="num" value="<?= $card->phone_mobile ?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_275') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <!--div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('card/data10') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="phone_home" data-limiter="15"  data-filter="num" value="<?= $card->phone_home ?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_275') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div-->
                    <? if ( !$is_already_card_received ) { ?>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('regist_email') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" data-limiter="64"  name="email" value="<?= $card->email ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Лимит 5-64 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <? } ?>
                    <? if ( $card->card_type == Card_model::CARD_TYPE_PLASTIC) { ?>
                    <div class="title">
                        <h6><?= _e('card/data11') ?></h6>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('card/data12') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="delivery_address" data-limiter="35"  data-filter="latin_num_symbols" value="<?= $card->delivery_address ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Лимит 35 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('card/data13') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="delivery_city" data-limiter="20"  data-filter="latin" value="<?= $card->delivery_city ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Лимит 20 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('card/data14') ?>:</label>
                        <div class="formRight">
                            <input class="form_input validate[required]" required="required" type="text" name="delivery_zip_code" data-limiter="8"  data-filter="latin_num" value="<?= $card->delivery_zip_code ?>"/><br>
                            <span style="font-size:11px;"><?= _e('Лимит 2-8 символов') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('card/data15') ?>:</label>
                        <div class="formRight">
                            <?= form_dropdown('delivery_country', get_country(false, true), $card->delivery_country); ?>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <? } ?>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"></label>
                        <div class="formRight">
                            <input class="form_input" style="width: 15px;" type="checkbox" name="dogovor" value="true" required="required"/> <span><?=_e('Я согласен с условиями и тарифами предоставления услуг')?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>

                    <button class="button" type="submit" name="submit"><?= _e('new_48') ?></button>
                </fieldset>
            </form>
        </div>
		<? /* if ($verified) { ?>
<? } else { ?>
    <p><?=_e('Для подачи заявки необходимо загрузить документы.')?></p>
<? } */ ?>

<!-- Календарь -->
<script type="text/javascript">

    var valid_regex = {"latin": /[^a-z \-]/i, "latin_num_symbols": /[^a-z0-9 ,./\\-]/i, "latin_num": /[^a-z0-9 ]/i, "num": /[^0-9]/i};
    $("#card input[data-filter]").each(function () {
        var filter = $(this).data('filter');
        var register = $(this).data('register');
        if (filter != undefined && valid_regex[filter] != undefined) {
            $(this).on('keyup', function (e) {
                this.value = this.value.replace(valid_regex[filter], "", "");
                switch (register) {
                    case "upper":
                        this.value = this.value.toUpperCase();
                        break;
                    default:
                        break;
                }

            });
            $(this).on('keydown', function (e) {
                var key = event.which || event.keyCode || event.charCode;
                var limiter = $(this).data('limiter');
                if (limiter != undefined && parseInt(limiter) <= this.value.length && key != 8 && !e.ctrlKey) {
                    e.preventDefault();
                }
            });
        }
    });






    $.datepicker.setDefaults($.extend(
            $.datepicker.regional["ru"])
            );

    $("[name='birthday']").datepicker({
        maxDate: "+0",
        minDate: "-100Y",
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        firstDay: 1,
        monthNamesShort: ["<?= _e('new_49') ?>", "<?= _e('new_50') ?>", "<?= _e('new_51') ?>", "<?= _e('new_52') ?>", "<?= _e('new_53') ?>", "<?= _e('new_54') ?>", "<?= _e('new_55') ?>", "<?= _e('new_56') ?>", "<?= _e('new_57') ?>", "<?= _e('new_58') ?>", "<?= _e('new_59') ?>", "<?= _e('new_60') ?>"],
        monthNames: ["<?= _e('new_61') ?>", "<?= _e('new_62') ?>", "<?= _e('new_63') ?>", "<?= _e('new_64') ?>", "<?= _e('new_65') ?>", "<?= _e('new_66') ?>", "<?= _e('new_67') ?>", "<?= _e('new_68') ?>", "<?= _e('new_69') ?>", "<?= _e('new_70') ?>", "<?= _e('new_71') ?>", "<?= _e('new_72') ?>"],
//            shortYearCutoff: "-99",
        showMonthAfterYear: true,
        yearRange: "-100:-5"
    });

</script>
<!-- Календарь -->