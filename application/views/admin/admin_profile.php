<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<form id="validate" class="form" action=""  method="post">
<input type="hidden" id="submited" name="submited" value="1"/>
            <fieldset>
                <div class="widget">
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Профайл</h6>

                    </div>
                    <div class="formRow">
                        <label>Имя:<span class="req">*</span></label>
                        <div class="formRight"><?=@$this->admin_info->name?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <label>Фамилия:<span class="req">*</span></label>
                        <div class="formRight"><?=@$this->admin_info->family?></div>
                        <div class="clear"></div>
                    </div>
	                    <div class="formRow">
                        <label>Должность:<span class="req">*</span></label>
                        <div class="formRight"><?=@$this->admin_info->doljnost?></div>
                        <div class="clear"></div>
                    </div>
                      <div class="formRow">
                        <label>Отдел:</label>
                        <div class="formRight"><?=@$this->admin_info->otdel?></div>
                        <div class="clear"></div>
                    </div>
		   <div class="formRow">
                        <label>Тип пользователя:</label>
                        <div class="formRight"><?=(@$this->admin_info->manager==1)?"Администратор" :"Модератор"?></div>
                        <div class="clear"></div>
                    </div>
                   <div class="formRow">
                        <label>Email:</label>
                        <div class="formRight"><?=@$this->admin_info->email?></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Телефон:</label>
                        <div class="formRight"><?=@$this->admin_info->telephone?></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Логин:</label>
                        <div class="formRight"><?=@$this->admin_info->login?></div>
                        <div class="clear"></div>
                    </div>
                    
		   <div class="formRow">
                        <label>Авторизация через:</label>
                        <div class="formRight">
                            <select id="sec_auth"  name="sec_auth" onchange="on_auth_change()">
                                <option value="sms" <?=(@$this->admin_info->sec_auth=='sms'?'selected="selected"':'') ?>>СМС</option>
                                <option value="app" <?=(@$this->admin_info->sec_auth=='app'?'selected="selected"':'') ?>>Приложение WT</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>                    

</div>

            </fieldset>


                <div class="widget" >
                    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Изменение  пароля</h6></div>
		<div class="formRow">
                        <label>Новый Пароль:</label>
                        <div class="formRight"><input type="password"  class="validate[required, minSize[6],maxSize[30]]" id="password"  name="password"  value=""></div>
                        <div class="clear"></div>
                    </div>
		<div class="formRow">
                        <label>Подтвердите Пароль:</label>
                        <div class="formRight"><input type="password"  class="validate[required,equals[password]]" id="password2"  name="password2"  value=""></div>
                        <div class="clear"></div>
                    </div>

	</div>







		<center>

			<a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>

			</center>
			</form>
    </div>
    
    <div id="ch_secutity_window" style="display: none">
    </div>
    
<script>
    $("#ch_secutity_window").dialog({
           autoOpen: false,
           height: 200,
           width: 500,
           dialogClass: "alert",
           title:"Смена типа авторизации"});
    
    function on_auth_change(){
        var now_sec_auth = '<?=@$this->admin_info->sec_auth?>';
        var  sec_auth = $('#sec_auth').val();
        if ( now_sec_auth == sec_auth )
            return;
        $.post('/opera/profile', {method: 'get_auth_window',  sec_auth: sec_auth}, function(data){
             $("#ch_secutity_window").html(data);
             $("#ch_secutity_window").dialog('open');
            
        });
        
    }
</script>        
        
        