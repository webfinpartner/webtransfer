<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if (!empty($user->id_user) and !empty($user_p->id_user)) {


    if (!empty($error)) {
        ?><div class="nNote nInformation hideit">
            <p><strong>Сообщение: </strong><?= $error ?></p>
        </div><? } ?>
    <style>.center{text-align:center} .center h5{font-size:15px}</style>
    <div class="widget">
        <div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" /><h6>Пользователь </h6>
            <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url() ?>opera/users/failure/<?= $user->id_user ?>"><span>Отказ</span></a>
        </div>
        <div class="body">

            <h3> </h3>
            <p>


                <!-- Wizard with custom fields validation -->



            <style>
                .formRight input{ width: 50% !important; margin-right:10%}
            </style>
            <div class="widget">



                <form id="wizard2" method="post" action="opera/users/profile/<?= $user->id_user ?>" class="form">
                    <input type="hidden" name="submited" value="1" />
                    <fieldset class="step" id="w2first">



                        <div class="formRow">
                            <label>Имя</label>
                            <div class="formRight"><input type="text" name="n_name"  value="<?= $user_p->name ?>" /><?= $user->name ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Фамилия</label>
                            <div class="formRight"><input type="text" name="f_name"  value="<?= $user_p->sername ?>" /><?= $user->sername ?></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Отчество</label>
                            <div class="formRight"><input type="text" name="o_name"  value="<?= $user_p->patronymic ?>" /><?= $user->patronymic ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Регион проживания</label>
                            <div class="formRight">
                    <?= form_dropdown('place', get_region(), $user_p->place) ?>
                        <? $region = get_region(); echo @$region[$user->place] ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow"><label>Дата рождения</label>
                            <div class="formRight"><input class="maskDate" type="text" name="born_date" value="<?= $user_p->born ?>" /><?= $user->born ?></div>
                            <div class="clear">&nbsp;</div>
                        </div>

                    </fieldset>
                    <fieldset id="w2confirmation" class="step">
                        <h1>Персональная информация </h1>
                        <div class="formRow center"> <h5> Документ - Общегражданский паспорт РФ</h5>  <div class="clear"></div>
                        </div>
                        <!--div class="formRow">
                             <label> Тип</label>

                                       <div class="formRight"> <input type="radio"  <?= ($user_p->face == 1) ? "checked='checked'" : "" ?> name="face" value="1" />Физ. лицо <br />
                                           <input type="radio"  <?= ($user_p->face == 2) ? "checked='checked'" : "" ?>  name="face" value="2" />Юр. лицо<br />
    <?= ($user->face == 1) ? "Физ. лицо" : (($user->face == 2) ? "Юр. лицо " : "Не указано") ?></div>
                                       <div class="clear"></div>
                                   </div-->
                        <div class="formRow">
                            <label>ИНН</label>
                            <div class="formRight"><input type="text" name="inn"  value="<?= $user_p->inn ?>" /><?= $user->inn ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Серия</label>
                            <div class="formRight"><input type="text" name="p_seria"  value="<?= $user_p->pasport_seria ?>" /><?= $user->pasport_seria ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Номер</label>
                            <div class="formRight"><input type="text" name="p_number"  value="<?= $user_p->pasport_number ?>" /><?= $user->pasport_number ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Дата выдачи</label>
                            <div class="formRight"><input type="text" name="p_date"  value="<?= $user_p->pasport_date ?>" /><?= $user->pasport_date ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Код подразделения</label>
                            <div class="formRight"><input type="text" name="p_kpd"  value="<?= $user_p->pasport_kpd ?>" /><?= $user->pasport_kpd ?></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Кем выдан</label>
                            <div class="formRight"><input type="text" name="p_kvn"  value="<?= $user_p->pasport_kvn ?>" /><?= $user->pasport_kvn ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Место рождения</label>
                            <div class="formRight"><input type="text" name="p_born"  value="<?= $user_p->pasport_born ?>" /><?= $user->pasport_born ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Банковские данные</h6></div>
                    <div class="formRow padding10-0" >
                        <label>Номер карты</label>
                        <div class="formRight"><input type="text" name="bank_cc" value="<?= $user_p->bank_cc ?>"  /><?= $user->bank_cc ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Срок действия карты</label>
                        <div class="formRight"><input type="text" name="bank_cc_date_off" value="<?= $user_p->bank_cc_date_off ?>"  class="w10" placeholder='MM/YY' /><?= $user->bank_cc_date_off ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>W1 (USD)</label>
                        <div class="formRight"><input type="text" name="bank_w1" value="<?= $user_p->bank_w1 ?>"  /><?= $user->bank_w1 ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>W1 (RUB)</label>
                        <div class="formRight"><input type="text" name="bank_w1_rub" value="<?= $user_p->bank_w1_rub ?>"  /><?= $user->bank_w1_rub ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>PerfectMoney</label>
                        <div class="formRight"><input type="text" name="bank_perfectmoney" value="<?= $user_p->bank_perfectmoney ?>"  /><?= $user->bank_perfectmoney ?></div>

                    </div>
                    <div class="formRow padding10-0" >
                        <label>OKpay</label>
                        <div class="formRight"><input type="text" name="bank_okpay" value="<?= $user_p->bank_okpay ?>"  /><?= $user->bank_okpay ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>EGOpay</label>
                        <div class="formRight"><input type="text" name="bank_egopay" value="<?= $user_p->bank_egopay ?>"  /><?= $user->bank_egopay ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label><div>QIWI</div><div class="small">Пример: 79993334411</div></label>
                        <div class="formRight"><input type="text" name="bank_qiwi" value="<?= $user_p->bank_qiwi ?>"  /><?= $user->bank_qiwi ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Paypal</label>
                        <div class="formRight"><input type="text" name="bank_paypal" value="<?= $user_p->bank_paypal ?>"  /><?= $user->bank_paypal ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Tinkoff Wallet</label>
                        <div class="formRight"><input type="text" name="bank_tinkoff" value="<?= $user_p->bank_tinkoff ?>"  /><?= $user->bank_tinkoff ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Webmoney</label>
                        <div class="formRight"><input type="text" name="webmoney" value="<?= $user_p->webmoney ?>"  /><?= $user->webmoney ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>RBK Money</label>
                        <div class="formRight"><input type="text" name="bank_rbk" value="<?= $user_p->bank_rbk ?>"  /><?= $user->bank_rbk ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Деньги@Mail.Ru</label>
                        <div class="formRight"><input type="text" name="bank_mail" value="<?= $user_p->bank_mail ?>"  /><?= $user->bank_mail ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Payeer</label>
                        <div class="formRight"><input type="text" name="bank_lava" value="<?= $user_p->bank_lava ?>"  /><?= $user->bank_lava ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Yandex</label>
                        <div class="formRight"><input type="text" name="bank_yandex" value="<?= $user_p->bank_yandex ?>"  /><?= $user->bank_yandex ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Wire transfer (Bank) <span class="phone_format" onclick="$('#popup_bank').show('slow');  return  false;">(?)</span></label>
                        <div class="formRight"><input type="text" name="bank_name" value="<?= $user_p->bank_name ?>"  /><?= $user->bank_name ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>Номер счета</label>
                        <div class="formRight"><input type="text" name="bank_schet" value="<?= $user_p->bank_schet ?>"  /><?= $user->bank_schet ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow padding10-0" >
                        <label>ABA/SWIFT</label>
                        <div class="formRight"><input type="text" name="bank_bik" value="<?= $user_p->bank_bik ?>"  /><?= $user->bank_bik ?></div>
                        <div class="clear"></div>
                    </div>
                        <div class="formRow center"> <h5>  Фактический адрес</h5>  <div class="clear"></div>
                        </div>
                        <div class="formRow">

                            <label>Индекс</label>
                            <div class="formRight"><input type="text" name="f_index"  value="<?= $user_p->findex ?>" /><?= $adr_f->index ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Город</label>
                            <div class="formRight"><input type="text" name="f_town"  value="<?= $user_p->ftown ?>" /><?= $adr_f->town ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Улица</label>
                            <div class="formRight"><input type="text" name="f_street"  value="<?= $user_p->fstreet ?>" /><?= $adr_f->street ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Дом</label>
                            <div class="formRight"><input type="text" name="f_house"  value="<?= $user_p->fhouse ?>" /><?= $adr_f->house ?></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Корпус/строение</label>
                            <div class="formRight"><input type="text" name="f_kc"  value="<?= $user_p->fkc ?>" /><?= $adr_f->kc ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Квартира</label>
                            <div class="formRight"><input type="text" name="f_flat"  value="<?= $user_p->fflat ?>" /><?= $adr_f->flat ?></div>
                            <div class="clear"></div>
                        </div>


                        <div class="formRow center"> <h5>  Адрес регистрации</h5>  <div class="clear"></div>
                        </div>
                        <div class="formRow">

                            <label>Индекс</label>
                            <div class="formRight"><input type="text" name="r_index"  value="<?= $user_p->rindex ?>" /><?= $adr_r->index ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Город</label>
                            <div class="formRight"><input type="text" name="r_town"   value="<?= $user_p->rtown ?>" /><?= $adr_r->town ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Улица</label>
                            <div class="formRight"><input type="text" name="r_street"  value="<?= $user_p->rstreet ?>" /><?= $adr_r->street ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Дом</label>
                            <div class="formRight"><input type="text" name="r_house"  value="<?= $user_p->rhouse ?>" /><?= $adr_r->house ?></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Корпус/строение</label>
                            <div class="formRight"><input type="text" name="r_kc"  value="<?= $user_p->rkc ?>" /><?= $adr_r->kc ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Квартира</label>
                            <div class="formRight"><input type="text" name="r_flat"  value="<?= $user_p->rflat ?>" /><?= $adr_r->flat ?></div>
                            <div class="clear"></div>
                        </div>


                        <div class="formRow">
                            <label>Пол</label>
                            <div class="formRight">
                                <input type="radio" name="sex" id="smth1" <?= ($user_p->sex == 1) ? "checked='checked'" : "" ?> value="1" />Мужщина <br />
                                <input type="radio" name="sex" <?= ($user_p->sex == 2) ? "checked='checked'" : "" ?> id="smth1" value="2" />Женщина<br />
                                <span><?= ($user->sex == 1) ? "Мужской" : (($user->sex == 2) ? "Женский" : "Не указано") ?></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Семейное положение</label>
                            <div class="formRight"><input type="radio" name="family_state" <?= ($user_p->family_state == 1) ? "checked='checked'" : "" ?> id="smth1" value="1"/>Женат <br />
                                <input type="radio" name="family_state"  <?= ($user_p->family_state == 2) ? "checked='checked'" : "" ?>  id="smth1" value="2" />Замужем<br />
                                <input type="radio" name="family_state"  <?= ($user_p->family_state == 3) ? "checked='checked'" : "" ?>  value="3" id="smth1" />Холост / Незамужем<br />
                                <span><?= ($user->family_state == 1) ? "Женат" : (($user->family_state == 2) ? "Замужем" : (($user->family_state == 3) ? "Холост / Незамужем" : "Не указано")) ?></span>
                            </div>
                            <div class="clear"></div>
                        </div>


                    </fieldset>
                    <fieldset id="w3confirmation" class="step">
                        <h1>Финансовая информация</h1>
                        <div class="formRow">
                            <label>Название организации</label>
                            <div class="formRight"><input type="text" name="w_name"  value="<?= $user_p->work_name ?>" /><?= $user->work_name ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Правовая Форма Организации</label>
                            <div class="formRight"><input type="text" name="legal_form"  value="<?= $user_p->legal_form ?>" /><?= $user->legal_form ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>ОГРН</label>
                            <div class="formRight"><input type="text" name="ogrn"  value="<?= $user_p->ogrn ?>" /><?= $user->ogrn ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>КПП</label>
                            <div class="formRight"><input type="text" name="kpp"  value="<?= $user_p->kpp ?>" /><?= $user->kpp ?></div>
                            <div class="clear"></div>
                        </div>


                        <div class="formRow">
                            <label>Рабочий телефон</label>
                            <div class="formRight"><input type="text" name="w_phone"  class="maskPhone" value="<?= $user_p->work_phone ?>" /><?= $user->work_phone ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Бизнес  центр</label>
                            <div class="formRight"><?= form_dropdown('w_place', get_bisness(), $user->work_place) ?></div>
                            <div class="clear"></div>
                        </div>
                        <div class="formRow">
                            <label>Должность</label>
                            <div class="formRight"><input type="text" name="w_who"  value="<?= $user_p->work_who ?>" /><?= $user->work_who ?></div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Стаж</label>
                            <div class="formRight"><input type="text" name="w_time"  value="<?= $user_p->work_time ?>" /><?= $user->work_time ?></div>
                            <div class="clear"></div>
                        </div>                                                                                <div class="formRow">
                            <label>Среднемесячный доход</label>
                            <div class="formRight"><input type="text" name="w_money"  value="<?= $user_p->work_money ?>" /><?= $user->work_money ?></div>
                            <div class="clear"></div>
                        </div>





                    </fieldset>
                    <div class="wizButtons">
                        <div class="status" id="status2"></div>
                        <span class="wNavButtons">
                            <input class="basic" id="back2" value="Back" type="reset" />
                            <input class="blueB ml10" id="next2" value="Next" type="submit" />
                        </span>
                    </div>
                    <div class="clear"></div>
                </form>
                <div class="data" id="w2"></div>
            </div>


            <div class="clearfix"></div>



        </div>
    </div>

<? } ?>
