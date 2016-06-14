<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div id="change_status">
    <textarea id="txt_change_status" style="height: 70px"></textarea>
    <center><button class="button" onclick="ajax_send($('#txt_change_status').data('id'));">Отправить</button></center>
    <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
        <img class='loading-gif' style="margin-top: 100px" src="/images/loading.gif"/>
    </div>
</div>
<script>
    $("#change_status").dialog({
        autoOpen: false,
        height: 200,
        width: 530,
        dialogClass: "alert",
        title:"Укажите причину по которой сменяеться статус пользователя."});
    function ajax_send(id){
        
        var cause = $("#txt_change_status").val();
        var id = $("#txt_change_status").data("id");
        var status = $("#txt_change_status").data("status");
        
        if ( cause == ''){
            alert('Введите причину');
            return false;
        }
        $("#change_status").dialog('close');
        
        if ( status == 2){
             $.get("/opera/users/active/" + id,
			function (data) {
				$('.user_open').removeClass('user_open').addClass('user_close').text('Заблокировать');
			}
		);
        }
        
        if ( status == 3){
            $.get(
                            "/opera/users/close/" + id,
                            function (data) {
                                    $('.user_close').removeClass('user_close').addClass('user_open').text('Активировать')
                            }
            );
        }
        
        $.post( "/opera/users/writeCause/"+id,
            {cause:cause, status:status},
            function(){
                if (typeof restartPage == 'function') {
                    restartPage();
                }
            },
            "json"
        );
        $("#txt_change_status").val('');
        return false;
    }
</script>