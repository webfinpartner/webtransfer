<div id="standart_code_validation" style="display: block">
<center>
<span>Введите код <?=Permissions::$SEC_TYPES[$cur_sec_auth]?>: <input type="text" name="code" id="code" style="width: 65px; margin: 0"></span>
<? if ( $sec_auth == 'app' && empty($this->admin_info->users_user_id) ){ ?>
<span>Введите ваш ID пользователя на основном сайте: <input type="text" name="link_user_id" id="link_user_id" style="width: 65px; margin: 0"></span>
<? } ?>
<br>
<button onclick="check_code()" id="check_btn">Проверить</button>
<br>
<span id='message' style='display: none'></span>
</center>
</div>

<script>
    
   function check_code(){
       $('#message').hide();
       var post_data = {method: 'check_code', code: $('#code').val(), 'set_to_auth': '<?=$sec_auth?>' };
        <? if ( $sec_auth == 'app' && empty($this->admin_info->users_user_id) ){ ?>
             post_data['link_user_id'] = $('#link_user_id').val();   
        <? } ?>
       $.post('/opera/profile', post_data, function(data){
           if ( data.status ){
               document.location.reload();
           } else {
              $('#message').html(data.status_text); 
              $('#message').css('color', 'red');
              $('#message').show();
           }
       }, 'json');
       
   }    
    
</script>