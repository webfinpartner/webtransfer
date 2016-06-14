<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?  if(!empty($item)){?>
	<div class="widget">
		<div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" /><h6>Кредит</h6>
			<a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/credit/delete/<?=$item->id?>">
				<span>Удалить</span>
			</a>
		</div>
        <div class="body">

        <form method="post" action="/opera/credit/state/<?=$item->id?>" class="form" id="form_state" onSubmit="return (false)">
        	<fieldset>
                <div class="widget">
                    <div class="title"><img src="images/icons/dark/alert.png"  class="titleIcon" />
						<h6><?=($item->type==1)?"Заявка на кредит":"Предложения на кредит"?></h6>
					</div>
<script>

var active= '<div class="formRow"><label>Исполнитель:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required]" name="performer" disabled="disabled" value="<?=performer($this, $sees, 4)?>"  id="req1"/></div><div class="clear"></div></div><div class="formRow"><label>Примечание:</label><div class="formRight"><input type="text" class="" name="note" id="req2"/></div><div class="clear"></div></div><div class="formRow"><label>Дата контракт:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required,custom[date] dateMaskMy" name="kontract_date" id="req5"/></div><div class="clear"></div></div><div class="formRow"><label>Дата погащения:</label><div class="formRight"><input disabled="disabled" type="text" class="" name="date_p" id="req7"/></div><div class="clear"></div></div><input type="hidden" name="active" value="1" />';

var pre3= '<div class="formRow"><label>Исполнитель:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required]" name="performer"  disabled="disabled" value="<?=performer($this, $sees, 3)?>" id="req1"/></div><div class="clear"></div></div>';

var pre1= '<div class="formRow"><label>Исполнитель:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required]" name="performer"   disabled="disabled" value="<?=performer($this,$sees,  1)?>" id="req1"/></div><div class="clear"></div></div>';
var pre2='<div class="formRow"><label>Исполнитель:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required]" name="performer" disabled="disabled" value="<?=performer($this, $sees, 2)?>" id="req1"/></div><div class="clear"></div></div>';
var pri1= '<div class="formRow"><label>Примечание:</label><div class="formRight"><input type="text" class="" name="note" value="<?=@$sees->state1->note?>" id="req2"/></div><div class="clear"></div></div>';
var pri2='<div class="formRow"><label>Примечание:</label><div class="formRight"><input type="text" class="" name="note" value="<?=@$sees->state2->note?>" id="req2"/></div><div class="clear"></div></div>';
var cause= '<div class="formRow"><label>Причина:<span class="req">*</span></label><div class="formRight"><input type="text" class="validate[required]" name="cause" value="<?=@$sees->state2->cause?>" id="req3"/></div><div class="clear"></div></div>';

</script><script>
$(function(){

	$('#req5[name="kontract_date"]').on("change",function () {

		$.get('/opera/credit/time',{time: $(this).val(), count: $('#time2').val()  },  function  (date) {
		$('#req7[name="date_p"]').val(date);
		})
	})

	$('#select_state').change(function(){
		form_change($(this).val())
	});

	val=$('#state').text();
	$('#state_'+val).attr("selected", "selected");
	$('.selector span').text($('#state_'+val).text());
	form_change(val)

	$("#form_credit").validationEngine();
})

function  form_change(val){
	form=$('#form');
	$("#form_state").validationEngine('detach');
	switch(val){
	case '1':form.html(pre3); break;
	case '2':form.html(pre1+''+pri1); break;
	case '3':form.html(active);break;
	case '4':form.html(cause+''+pre2+''+pri2);break;
	}
	$("#form_state").validationEngine('attach');
	$(".dateMaskMy").mask("99/99/9999");
}


function send_form(){
$("#form_state").ajaxSubmit({
	  url: "/opera/credit/state/<?=$item->id?>",
	  success: function(){$('#answer').fadeIn(); setTimeout(function(){$("#answer").fadeOut()
	  if($('#select_state').val()==3)
		  window.location = "/opera/credit/view/<?=$item->id?>"

  },500);}});
}

</script>
<div><? if($state==7)echo"Пользователь отклонил заявку";?></div>
<span style="display: none" id="state"><?=$state?></span>

<select name="state" id="select_state">
<option class="state" id="state_1"  value="1">Новый</option>
<option class="state" id="state_2"  value="2">На рассмотрении</option>
<option class="state" id="state_3"  value="3">Активный</option>
<option class="state" id="state_4"  value="4">Отклонен</option>
</select><br />
<div style="margin-top:10px" id="form"></div>
<center id="answer" style="display:none">Изменения были сохранены</center>
	<center>
		<a class="wButton greenwB ml15 m10" onclick="$('#form_state').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
	</center>
</form>
  	</fieldset>
<h6><?=($item->type==1)?"Кредитор":"Вкладчик"?></h6>
Имя: <?=$user1->name?><br/>
Фамилия: <?=$user1->sername?><br/>
Отчество: <?=$user1->patronymic?><br/>
Телефон: <?=$user1->phone?><br/>
<a href="/opera/users/<?=$user1->id_user?>">Профиль пользователя</a>
<br/>
<h6><?=($item->type==1)?"Вкладчик":"Кредитор"?></h6>
Имя: <?=$user2->name?><br/>
Фамилия: <?=$user2->sername?><br/>
Отчество: <?=$user2->patronymic?><br/>
Телефон: <?=$user2->phone?><br/>
<a href="/opera/users/<?=$user2->id_user?>">Профиль пользователя</a>
<br/>
<?$this->load->view('admin/blocks/credit')?>
            </div>
                </div>

 <?}

function performer($admin, $sees, $var)
{
	$var = "state{$var}";
	return (!empty($sees->{$var}->name))? $sees->{$var}->performer .",  ".$sees->{$var}->name.", ".$sees->{$var}->date
		:"{$admin->admin_info->login}, {$admin->admin_info->name}, ".  $admin->input->ip_address();
}
