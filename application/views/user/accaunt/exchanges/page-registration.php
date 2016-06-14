<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen"/>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

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
<h1 class="title"><?=_e('Регистрация')?></h1>

 <div class="widget" style="margin-top:10px;">
            <div class="title">
                <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
                <h6><?=_e('Подать заявку на регистрацию обменника')?></h6>
            </div>


            <form class="form" id="card" method="POST" action="<?=site_url('account/exchanges_registration')?>" accept-charset="utf-8">
                <fieldset>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('Название <br/>Обменника')?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="title" data-filter="latin" value=""/>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('Адрес сайта')?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="site" data-filter="latin" value=""/>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                   <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('ФИО')?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="holder_name" data-filter="latin" data-register="upper" data-limiter="21" value="<?=$user->name?> <?=$user->sername?>"/>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('№ Кошелька')?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="wallet" data-filter="latin" value="<?=$user->id_user?>"/>
                            <div class="error" style="display:none" id="error_id"></div>
                        </div>
                    </div>
                 
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('Телефон')?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="phone_mobile" data-filter="num" value="<?=$user->phone?>"/>
                            <br>
                            <span style="font-size:11px;"><?= _e('new_275') ?></span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
     
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;">Skype *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="skype" data-filter="num" value="<?=$user->skype?>"/>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>

            
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?= _e('regist_email') ?> *:</label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="email" value="<?=$user->email?>"/>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>
                  
                    <div class="formRow padding10-40">
                        <label style="margin-top:10px;"></label>
                        <div class="formRight">
                            <input class="form_input" style="width: 15px;" type="checkbox" name="dogovor" value="true" required="required"/> <span>
							<?if($this->lang->lang()=='ru'){?>
							Я согласен с <a href="<?=site_url('account/exchanges-terms')?>" target="_blank">условиями регистрации</a>
							<?} else {?>
							I agree with <a href="<?=site_url('account/exchanges-terms')?>" target="_blank">terms of registration</a>
							<?}?>
							</span>
                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>
                    </div>

                    <button class="button" type="submit" name="submit"><?=_e('Регистрация')?></button>
                </fieldset>
            </form>
        </div>
<br/><Br/>
<?=$page_item->content?>
