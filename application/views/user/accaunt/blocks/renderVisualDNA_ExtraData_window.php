<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
    .popup_window_overlay {
        background-color: rgba(0, 0, 0, 0.6);
        position: fixed;
        left:  0;
        top:  0;
        width: 100%;
        height: 100%;
        z-index: 16000002;        
    }    
    
.form_select_vdna {
  height: 34px;
  text-align: center;
}
.form_select_vdna, .form_input {
  padding: 2px 6px 6px;
  border: 1px solid #DDD;
  font-family: Arial,Helvetica,sans-serif;
  color: #656565;
}
 </style>

<div class="popup_window_overlay">
    <div id="popup_VisualDNA_extra" class="popup_window" style="z-index:16000003;">
        <div class="close" onclick="$(this).parent().parent().hide();"></div>
        <!--h2><?=_e('block/data20')?></h2>
        <span class="standart"><?=_e('block/data21')?></span>
        <span class="garant" style="display: none"><?=_e('block/data22')?></span>
        <button class="button" style="margin-bottom: 5px;" onclick="window.open('<?=site_url('account/visualdna')?>', '_blank');$('#popup_VisualDNA').hide();"><?=_e('block/data23')?></button>
        <a class="garant" href="#" style="display: none" onclick="showNext(); return false;"><?=_e('block/data24')?></a-->
        <h2 style="font: 24px/40px 'Lucida Grande','Lucida Sans Unicode',Arial;  color: #FF5300;  margin-bottom: 7px;}"><?= _e('Вы проходили тест VisualDNA.') ?></h2><br/>
        <h3><?= _e('Введите, пожайлуста, дополнительную информацию:') ?></h3>
        <h5 style="margin: 0;  padding: 0;    font-size: 1.17em;  -webkit-margin-before: 1em;  -webkit-margin-after: 1em;  -webkit-margin-start: 0px;  -webkit-margin-end: 0px;  font-weight: bold;"><?= _e('(Ввод неверной информации может отразится на результатах теста)') ?></h5>
        <br/>
        <div id="vdna_msg"></div>
        <br/>    

        <!--form method="post" id="dnaExtraDataForm"-->
            <div class="formRow padding10-0" style="border: 0;">
                <label style="margin-top: 4px;"><?= _e('Дата рождения') ?><span class="req">*</span>:</label>
                <div class="formRight">
                    <!--input type="text" name="birthday" id="dna_birthday" style="float: left;margin: 0px 0px 0px 10px; width: 110px;" placeholder="mm/dd/yyyy" >
                    <div id="calend_vdna" style="cursor: pointer;width: 28px;height: 31px;border: 1px solid rgb(204, 204, 204);border-radius: 3px;padding: 1px;background-position: center center;float: left;margin-left: 7px; background-image: url(data:image/gif;base64,R0lGODlhHQAfAPcAAP////n5+aupn6WjmePi4MC+tqWjmL++tru5sqimnKOhlvHw76elm+rq6Li3rubl46akmubl4tzb17q4sLe3rb68tL28tMrJw+jm5KemnOXk4tTTzrm4sPb29dbV0Kmonufm5L+9tqGflKKhlqmnnaelmqyqoL27s/X19PHx78TDvKqnnsbFv/z8/K+upO7u7Pr6+qSil9HQyq6sos7NyKmnnuLi3qupoMG/uP7+/snHwff39q2rorKwqLSzqqqonqOhl6akmd3c2fT08/X087m3r6WimOHg2/z8+/39/bi3r+Hg3LW0q7SyqbSyqrGvpc/OyLWzq7a0rODf3KSimN3c2Lm3sKqnna6tpKuonq6so6mmnczLxa+tpN7d2uzs6uXk4fv7++bm4+Xl4fLy8Pb19bCupaKgle/u7Z+dkqGelJ+ckdva1vDv7qCek8bFvqimm6Shl+Hg3drZ1fj499vZ1eno5dvb1/v7+sPBurW0rPPz8rKvprKwprKwp7GvptHQy7a1reLh3rm4r/Dw7qakm6alm8vKxKajmqSilsfGv9zb2N/e2tbV0eno5+np5+np5rSxqNbVz9nY0wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACwAAAAAHQAfAAAI/wABCBxIsKDBgwgTKkwIJ44dhSHghFgIYBAcOGkSorkIB81Ciw01cnxY0Mugk2hAwqkYIsSgloN0gInT0MskmAInXYyDJiJGAGk4chwEgOMQnzwB6Lw4cRAWjzQ50owDgAYcGkV35hTqZaDQrxMFWr2YBo/Sr3CwBBUatWHar3ECnBWKBS3aunA96oyTJg3foH3j8P2LETBfsgK9wPESoEwZAI4bSy4ToDHly40t6l28UG7Culu7GsRCWiBpLFVJiw6RZrPogmsFcgSg46Jo0AAUvyb4VjYWqjTqrm59djdFg4My5uYMAI0X0V6cJ5aOxvnjih2Lm+7rsa9y7wBYp4cRHQmO3ElYbjfs/hPoz9rjBSZ/rBP688T3c993Hl1+XO3HITTfWSSFQJpHaaFWlFOppedfaPJdBFVIANCU0ViiJeeaaRK6R5V7K/mU4Yf1BYgQa65V9wgaK67IYnUvuhjdimpthdGNI9y4Vl86cuRXh2hMMolNRA5Z5HNGDrnBczZhZaKJAQEAOw==);"></div-->
                    <div style="float: left; margin: 0px 0px 0px 10px;">
                        <select id="birth_year" class="form_select_vdna" style="width:80px">
                            <option value=""><?= _e('Год') ?></option>
                            <? for ($i=date('Y')-100; $i<date('Y')-14; $i++) {?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <? } ?>
                        </select>

                        <select id="birth_month" class="form_select_vdna" style="width:110px">
                            <option value=""><?= _e('Месяц') ?></option>
                            <option value="1"><?= _e('Январь') ?></option>
                            <option value="2"><?= _e('Февраль') ?></option>
                            <option value="3"><?= _e('Март') ?></option>
                            <option value="4"><?= _e('Апрель') ?></option>
                            <option value="5"><?= _e('Май') ?></option>
                            <option value="6"><?= _e('Июнь') ?></option>
                            <option value="7"><?= _e('Июль') ?></option>
                            <option value="8"><?= _e('Август') ?></option>
                            <option value="9"><?= _e('Сентябрь') ?></option>
                            <option value="10"><?= _e('Октябрь') ?></option>
                            <option value="11"><?= _e('Ноябрь') ?></option>
                            <option value="12"><?= _e('Декабрь') ?></option>

                        </select>

                        <select id="birth_day" class="form_select_vdna" style="width:80px">
                            <option value=""><?= _e('День') ?></option>
                            <? for ($i=1; $i<=31; $i++) {?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <? } ?>
                        </select>
                    </div>



                </div>
            </div>       
            <div class="formRow padding10-0" style="border: 0;">
                <label style="margin-top: 4px;"><?= _e('Пол') ?><span class="req">*</span>:</label>
                <div class="formRight">
                    <div style="text-align: left; margin-left: 10px;">
                        <input type="radio" name="sex" value="1"><?= _e('Мужской') ?><br />
                        <input type="radio" name="sex" value="2"><?= _e('Женский') ?>
                    </div>

                </div>
            </div>       

            <div align="center"><br /><img src="/images/loading.gif" style="display: none" class="loading-gif" /></div>        
            <button name="submit" id="dna_bn_save" type="submit" class="button" ><?= _e( 'new_243' ) ?></button>       
        <!--/form-->

    </div>
</div>

<script>
    function daysInMonth(iMonth, iYear)
    {
         return new Date(iYear, iMonth, 0).getDate();
    }

    // показывает сообщения
    function vdna_showMessage(message, type) {
        $('#vdna_msg')
                .html(message)
                .removeClass('error_dna')
                .removeClass('success_dna')
                .addClass(type)
                .css('display', 'block');
    }


    $(function() { 
        $('#popup_VisualDNA_extra').show();
        //$('#vdna_data_request_dialog').modal();

        //$('.simplemodal-overlay').css('background-color', 'black');
        
        $('#popup_VisualDNA_extra #dna_bn_save').click(function()
        {
            //var birthday = $('#dna_birthday').datepicker("getDate");
            var sex = $('#popup_VisualDNA_extra input[name=sex]:checked').val();
            if (sex === undefined) {

                vdna_showMessage('<?= _e('Выберите пол') ?>', 'error_dna');
                return false;
            }

            
            if ( $('#birth_day option:selected').val() == '') { 
                vdna_showMessage('<?= _e('Выберите день рождения') ?>', 'error_dna');
                return false;
            }
            if ( $('#birth_month option:selected').val() == '') {
                vdna_showMessage('<?= _e('Выберите месяц рождения') ?>', 'error_dna');
                return false;
            }
            if ( $('#birth_year option:selected').val() == '') {
                vdna_showMessage('<?= _e('Выберите год рождения') ?>', 'error_dna');
                return false;
            }
            
            birth_day = $('#birth_day option:selected').val();
            birth_month = $('#birth_month option:selected').val();
            birth_year = $('#birth_year option:selected').val();
            
            if ( birth_day > daysInMonth(birth_month, birth_year )){
                vdna_showMessage('<?= _e('Неверный день рождения') ?>', 'error_dna');
                return false;                
            }

            //var birthdate = birthday.getFullYear() + '-' + ('0' + (birthday.getMonth() + 1)).slice(-2) + '-' + ('0' + birthday.getDate()).slice(-2)
            var birthdate = birth_year + '-' + ('0' + birth_month).slice(-2) + '-' + ('0' + birth_day).slice(-2)


            $('.loading-gif').show();
            $.post(site_url + "/account/ajax_user/save_vdna_extra", {sex: sex, birthday: birthdate})
                    .done(function(data) {
                try {
                    message = JSON.parse(data);
                } catch (e) {
                    vdna_showMessage('<?= _e('Неверный ответ от сервера. Перезагрузите страницу.') ?>', 'error_dna');
                    $('.loading-gif').hide();
                }

                if (message.result == 'OK') {
                    vdna_showMessage(message.message, 'success_dna');
                    $('#popup_VisualDNA_extra #dna_bn_save').hide();
                    setTimeout(function() {
                        //$.modal.close();
                         $('#popup_VisualDNA_extra').hide();
                         $('.popup_window_overlay').hide();
                    }, 3000);

                } else {
                    vdna_showMessage(message.error_message, 'error_dna');
                    $('.loading-gif').hide();

                }
            });

            return false;


        });
    });     
 </script>
