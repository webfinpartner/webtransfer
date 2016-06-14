 <html>
 <head><title></title>
 
 
 <link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/user/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="/js/user/ui.datepicker-ru.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/user/accounting.min.js"></script>
<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.js"></script>
<script type="text/javascript" src="/js/user/jquery.validate.js"></script>
<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<script type="text/javascript" src="/js/user/messages_ru.js"></script>
<script type="text/javascript" src="/js/user/form_reg.js"></script>
<script type="text/javascript">


	$(function(){
$("#form_login").validationEngine();

$('select[name=vklad]').change(function(){count_res()})


$('input[name=n_vklad]').keyup(function(){count_res()})
});

function count_res()
{

var summ=$('input[name=n_vklad]').val()
var plan=$('select[name=vklad]').val()
switch(plan)
{
<?=get_vklad()?>
}
if(nal==1)nal="Ежемесячно"
else nal="В конце срока"
sum_bon =summ * ((bonus/100)+1);
out_summ=sum_bon *( ( (percent/100) /12) * month);
out_summ_m=Math.round(sum_bon *( (percent/100) /12)* 100) / 100

out_summ=Math.round(out_summ* 100) / 100
$('input[name=month]').val(month+" мес.")
$('input[name=percent]').val(percent+" %")
$('input[name=nachislenie]').val(nal)
$('input[name=vmesyaz]').val(out_summ_m)
$('input[name=pribil]').val(out_summ+" руб.")


}


$(function()
{
$('#sv_fact_dres').change(function()
	{
		if($(this).val()==1)
		{
		$('input[name=r_house]').val($('input[name=f_house]').val());
		$('input[name=r_index]').val($('input[name=f_index]').val());
		$('input[name=r_town]').val($('input[name=f_town]').val());
		$('input[name=r_street]').val($('input[name=f_street]').val());
		$('input[name=r_flat]').val($('input[name=f_flat]').val());
		$('input[name=r_kc]').val($('input[name=f_kc]').val());
		
		
		}
	});
	
	$('input[name=form_check]').change(function(){
	
	val=$(this).val();
	if(val==1)$('input[name=form_check].form_check1').attr('checked','checked');
	else $('input[name=form_check].form_check2').attr('checked','checked');
	
	})
	
	/*$('#wizard2').submit(function(){
	var val=$('input[name=phone]').val();
	var regexp = /\(([0-9]{3})\)\s([0-9]{3})-([0-9]{3})/g;

var newtext = val.replace(regexp, '$1$2$3');

	$('input[name=phone]').val(newtext); })*/
});

</script>
</head>
<body>
<!-- Wizard with custom fields validation -->
<div class="widget">
<div class="title"><img class="titleIcon" src="images/icons/dark/pencil.png" alt="" />
<h6>Регистрация</h6>
</div>
<form id="form_login" class="form_login form" style="display: none;" action="" method="post" name="login" onsubmit="return false">
<div id="status_answer"  style="display:  none"></div>
<div class="formRow">Вы впервые вкладываете деньги в нашей компании?<input class="form_check1" type="radio" name="form_check" value="1" checked="checked" />Да <input class="form_check2" type="radio" name="form_check" value="2" />Нет
<div class="formRight"></div>
<div class="clear"></div>
</div>
<div class="formRow">
<h3 style="text-align: center;">Вход для клиентов</h3>
<div class="clear"></div>
</div>
<div class="formRow"><label>Логин:</label>
<div class="formRight"><input id="123" class="validate[required]" type="text" name="login" value="" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Пароль:</label>
<div class="formRight"><input id="1234" class="validate[required]" type="password" name="password" value="" /></div>
<div class="clear"></div>
</div>
<div id="login_answer" style="color: red; font-style: italic; bold: 700; font-size: 14px; text-align: center;"></div>
<div style="text-align: right; height: 120px;"><a class="forgot" style="margin-right: 20px;" href="/ask/forget">Забыли пароль?</a>
<div class="button" style="right: 20px; position: absolute;"><input type="submit" value="" />Войти</div>
</div>
</form><form id="wizard2" class="form" action="/registration" method="post"><fieldset id="w2first" class="step">
<input type="hidden" name="type" value="2" />
<div class="formRow">Вы впервые вкладываете деньги в нашей компании?<input class="form_check1" type="radio" name="form_check" value="1" checked="checked" />Да <input class="form_check2" type="radio" name="form_check" value="2" />Нет
<div class="formRight"></div>
<div class="clear"></div>
</div>

          <div class="formRow"><label>Сумма Вклада<span class="req">*</span></label>
<div class="formRight"><input type="text" name="n_vklad" /></div>
<div class="clear"></div>
</div>
                        <div class="formRow"><label>Тип вклада</label>
<div class="formRight"><select name="vklad">
<?=get_menu_vklad()?>
</select></div>
                <div class="clear"></div>
            </div>
<div class="formRow"><label>Детали вклада</label>
<div class="formRight">
<span class="oneFour" style="font-size:11px;margin-right:10px;width:38px;">срок:<br/><input type="text" name='month' value="" /></span>
<span class="oneFour" style="font-size:11px;margin-right:10px;width:71px;">% ставка/год:<br/><input type="text" name='percent' value="" /></span>
<span class="oneFour" style="font-size:11px;margin-right:10px;width:72px;">начисление:<br/><input type="text" name="nachislenie" value="" /></span>
<span class="oneFour" style="font-size: 11px; margin-right: 10px; width: 57px;">прибыль/мес:<br><input type="text" class="ui-wizard-content" name="vmesyaz" value=""></span>
<span class="oneFour" style="font-size:11px;margin-right:0px;width:126px;">прибыль на конец срока:<br/><input type="text" name="pribil" value="" /></span>
</div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Имя<span class="req">*</span></label>
<div class="formRight"><input type="text" name="n_name" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Фамилия<span class="req">*</span></label>
<div class="formRight"><input type="text" name="f_name" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Отчество<span class="req">*</span></label>
<div class="formRight"><input type="text" name="o_name" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Телефон<span class="req">*</span></label>
<div class="formRight"><input class="maskPhone" type="text" name="phone" value="" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Регион проживания</label>
<div class="formRight" style="margin-top:-10px;"><select class="required" data-name="Регион проживания" id="REGION_SELECT" name="place" tabindex="14">
                                              <option value="" selected> </option>
                  <option value="1" >Москва</option>
                  <option value="2" >Mосковская область</option>
                  <option value="439" >Архангельск и область</option>
                  <option value="425" >Астрахань и область</option>
                  <option value="381" >Барнаул и Алтайский край</option>
                  <option value="423" >Белгород и область</option>
                  <option value="20" >Брянск и область</option>
                  <option value="133" >Владимир и область</option>
                  <option value="424" >Волгоград и область</option>
                  <option value="435" >Вологда и область</option>
                  <option value="141" >Воронеж и область</option>
                  <option value="430" >Екатеринбург и область</option>
                  <option value="21" >Иваново и область</option>
                  <option value="151" >Ижевск и Удмуртская республика</option>
                  <option value="24" >Йошкар-Ола и Республика Марий Эл</option>
                  <option value="429" >Иркутск и область</option>
                  <option value="27" >Казань и Республика Татарстан</option>
                  <option value="3" >Калуга и область</option>
                  <option value="23" >Киров и область</option>
                  <option value="440" >Кострома и область</option>
                  <option value="142" >Краснодар и край</option>
                  <option value="383" >Красноярск и край</option>
                  <option value="143" >Курск и область</option>
                  <option value="4" >Липецк и область</option>
                  <option value="216" >Мурманск и область</option>
                  <option value="144" >Нижний Новгород и область</option>
                  <option value="432" >Новосибирск и область</option>
                  <option value="384" >Омск и область</option>
                  <option value="35" >Орел и область</option>
                  <option value="134" >Оренбург и область</option>
                  <option value="135" >Пенза и область</option>
                  <option value="145" >Пермь и край</option>
                  <option value="146" >Петрозаводск и республика Карелия</option>
                  <option value="34" >Ростов на Дону и область</option>
                  <option value="16" >Рязань и область</option>
                  <option value="33" >Самара и область</option>
                  <option value="170" >Санкт-Петербург и Ленинградская область</option>
                  <option value="25" >Саранск и Республика Мордовия</option>
                  <option value="36" >Саратов и область</option>
                  <option value="17" >Смоленск и область</option>
                  <option value="136" >Ставрополь и край</option>
                  <option value="148" >Тамбов и область</option>
                  <option value="18" >Тверь и область</option>
                  <option value="428" >Томск и область</option>
                  <option value="19" >Тула и область</option>
                  <option value="431" >Тюмень и область</option>
                  <option value="137" >Ульяновск и область</option>
                  <option value="26" >Уфа и Республика Башкортостан</option>
                  <option value="433" >Хабаровск</option>
                  <option value="149" >Чебоксары и Чувашская республика</option>
                  <option value="434" >Челябинск и область</option>
                  <option value="22" >Ярославль и область</option>
                  <option value="999999" >Другой регион</option>
                                           </select></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>E-mail<span class="req">*</span></label>
<div class="formRight"><input id="form_email" type="text" name="email" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Пароль<span class="req">*</span></label>
<div class="formRight"><input id="form_password" type="password" name="password" value="" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label>Повторите пароль<span class="req">*</span></label>
<div class="formRight"><input type="password" name="password2" value="" /></div>
<div class="clear"></div>
</div>
<div class="formRow"><label><input type="checkbox" name="agree" value="1" /> Согласие на передачу и обработку персональных данных</label>
<div class="formRight"></div>
<div class="clear"></div>
</div>
</fieldset>
<div class="wizButtons">
<div id="status2" class="status"></div>
<span class="wNavButtons"> <input id="back2" class="basic" type="reset" value="Назад" /> <input id="next2" class="blueB ml10" type="submit" value="Далее" /> </span></div>
<div class="clear"></div>
</form>
<div id="w2" class="data"></div>
</div>
<div class="clearfix"></div>
</body></html>