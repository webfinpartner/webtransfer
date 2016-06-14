$(function () {
	$('.delete_f').click(function () {
		var id = $(this).parents('.tabs').attr('id').replace(/tab/, '');
		if (!window.confirm(_e("Вы уверены что хотите удалить  документ?")))return;
		$.get(site_url + "/account/delete_doc/" + id,
			onAjaxSuccess
		);

		function onAjaxSuccess(data) {
			switch (data) {
				case "1":
					alert(_e('Изображение успешно удалено'));
					break;
				case "2":
					alert(_e('Вы не можете удалить изображение которое было  одобренно  модератором. Загрузите новое фото. И дождитесь  проверки  модератора.'));
                                        wrap.find('.second_doc').show();
					break;
			}
		}

		var wrap = $('#tab' + id)
		if ($(this).parent().hasClass('first_doc')) {
			wrap.find('.state_d').text(_e('Не загружено'))
			wrap.find('.first_doc').hide();
		} else {
			wrap.find('.second_doc').hide();
			wrap.find('.old_agree').hide();
		}
	});

	$('#form_type').change(function () {
		/*$('#tab1, #tab2').hide();
		 $('#tab'+$(this).val()).show();*/
		if ($(this).val() == 1)
			$('.pasport_help').show();
		else
			$('.pasport_help').hide();
	}).trigger('change');
	$(" input:file").uniform();
});

