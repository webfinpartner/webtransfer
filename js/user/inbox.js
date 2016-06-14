$(function () {
	$('.inbox .header').click(function () {
		var item = $(this);
		if (item.hasClass('new')) {
			item.removeClass('new');

			if (item.children('.status').text() == _e('Новое сообщение') && "mess" != item.children('.status').data("cause"))
				item.children('.status').text(_e('На рассмотрении'));
			if (item.children('.status').text() == _e('Новое сообщение') && "mess" == item.children('.status').data("cause"))
				item.children('.status').text(_e('Прочитано'));

			$.get(site_url + '/account/inbox_read/' + item.parents('.inbox').attr('href'))
			if ($('#new_messages').text() == 1)$('#new_messages').text(' ')
			else $('#new_messages').text($('#new_messages').text() - 1)
		}
		item.siblings('.content').slideToggle('slow', 'linear')
	})

//	$('.inbox .action .cancel').click(function(){
//		$(this).parents('.inbox').find('.do-cancel').append("Причина отклонения <br /><input type='text' class='cause' name='cause' /><button>Отправить</button>")
//	})
	$(document).on('click', '.do-cancel button', function () {
		var wrap = $(this).parents('.inbox');
		$.post(site_url + '/account/inbox_cancel/' + wrap.attr('href'), {cause: wrap.find('input.cause').val()})
		wrap.find('.do-cancel').toggle('slow').html('')
		wrap.find('.action').html('<a class="delete">'+_e('Удалить')+'</a>')
		wrap.find('.status').text(_e('Отклонен'))
	})

	$(document).on('click', ' .delete', function () {
		var wrap = $(this).parents('.inbox');
		wrap.slideUp('slow', function () {
			$(this).remove();
			check_list()
		})
		$.post(site_url + '/account/inbox_delete/' + wrap.attr('href'))
	})

//	$('#confirm_invest').click(function(){
//			all=0
//			wrap = $('.inbox[href='+$(this).attr('rel')+']');
//		type = wrap.find('.debit').attr('rel');
//		if(type==1)
//		{
//			if (confirm("Отменить все другие сделаенные предложения на кредит")) {
//				all=1
//			}
//		}
//		else  if(type==2)
//		{
//			if (confirm("Отменить все другие сделаенные предложения на вклады")) {
//				all=2
//			}
//		}
//
//			wrap.find('.status').text('Одобрен')
//			wrap.find('.action').html('<a class="delete">Удалить</a>')
//			$.post('/account/inbox_agree/'+wrap.attr('href'),{all:all},function(){ window.location.reload(); });
//			$('#popup_debit').hide('slow');
//		return false
//	})


//	$('.action .agree').click(function(){
//		$('#popup_debit').show('slow');
//		$('#popup_debit #confirm_invest').attr('rel',$(this).parents('.inbox').attr('href'));
//
//	})

	$('.author-get').click(function () {
		var wrap = $(this).siblings('.author-info');
		if (wrap.html() == '') {
			var type = $(this).attr('rel'),
                id = $(this).data('id');

            if( id === undefined ) id = 0;
			if (type == '') type = 1;

			$.get(site_url + '/account/ajax_user/get_user_data/' + type + '/' + $(this).attr('href') + '/'+ id,
				function (data) {
					wrap.html(data)
					wrap.find('.button').remove()
					wrap.show('slow');
				}
			)
		} else {
			wrap.toggle('slow');
		}
		return false
	});
	check_list();
})
function check_list() {
	if ($('#inbox .inbox').length == 0) {
		$('#inbox').html('<div class="empty-inbox">'+_e('Входящие уведомления не обнаружены')+'</div>')
	}
	if ($('#outbox .inbox').length == 0) {
		$('#outbox').html('<div class="empty-inbox">'+_e('Исходящие уведомления не обнаружены')+'</div>')
	}
}