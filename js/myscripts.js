function yes_no(msg) {
        if (msg == undefined)
            msg = "Вы уверены что хотите удалить?";
	if (window.confirm(msg))return true;
	else return false;
}
$(function () {
	$('.del').live('click', function () {
		if (window.confirm("Вы уверены что хотите удалить?"))return true;
		else return false;
	});
	$('#reset').click(function () {
		document.getElementById('validate').reset();
	});

	$('.feed_open').live("click", function () {
		var id = $(this).attr('name');
		$.get(
			"/opera/feedback/open/" + id,
			function (data) {
				$('.feed_open').removeClass('feed_open').addClass('feed_close').text('Закрыть')
			}
		);
	});

	$('.feed_close').live("click", function () {
		var id = $(this).attr('name');
		$.get(
			"/opera/feedback/close/" + id,

			function (data) {
				$('.feed_close').removeClass('feed_close').addClass('feed_open').text('Открыть')
			}
		);
	});


	$('.user_open').live("click", function () {
		var id = $(this).attr('name');
		$("#txt_change_status").data("id", id);
		$("#txt_change_status").data("status", 2);
		$("#change_status").dialog('open');
                /*$.get("/opera/users/active/" + id,
			function (data) {
				$('.user_open').removeClass('user_open').addClass('user_close').text('Заблокировать');
			}
		);*/
	});

	$('.user_close').live("click", function () {
		var id = $(this).attr('name');
		$("#txt_change_status").data("id", id);
		$("#txt_change_status").data("status", 3);
		$("#change_status").dialog('open');
		/*$.get(
			"/opera/users/close/" + id,
			function (data) {
				$('.user_close').removeClass('user_close').addClass('user_open').text('Активировать')
			}
		);*/
	});

	$('.credit_open').live("click", function () {
		id = $(this).attr('name');
		$.get("/opera/credit/setactive/" + id + "/2")
		$('.credit_open').removeClass('credit_open').addClass('credit_close').text('Отклонить')
	});

	$('.credit_close').live("click", function () {
		id = $(this).attr('name');
		$.get("/opera/credit/setactive/" + id + "/3");
		$('.credit_close').removeClass('credit_close').addClass('credit_open').text('Активировать')
	});

 
        $('#menu >  li > .sub a').click(function (){
         console.log($(this).parent().attr('id'));
         $.cookie('admin_menu_sub', $(this).parent().attr('id'),{path: '/'});


        })


        $('#menu >  li > a').click(function (){
         console.log($(this).parent().attr('id'));
         $.cookie('admin_menu', $(this).parent().attr("class"),{path: '/'});
        })

        if($.cookie('admin_menu')!=null){
                $('#menu >  li.'+$.cookie('admin_menu')+' > a').trigger('click');
        }

        if($.cookie('admin_menu_sub')!=null){
            $('#'+$.cookie('admin_menu_sub')).addClass('this');
        } 
});
