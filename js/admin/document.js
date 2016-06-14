$(function(){


$('.tabs > li > a ').bind("click",function(){
window.location.hash='hash'+$(this).attr('id'); 

})


hash = window.location.hash.replace(/hash/,'')
if(hash!="")
{

		$(hash).parent().trigger('click');
	
}
	
	$('#form_type').change(function (){
		$('#tab2 .tab').fadeOut();
		$('#tub'+$(this).val()).fadeIn();
	})	
		
	select_form_main(1)

	$('.delete_foto').bind('click', function (){

		if(!window.confirm("Подтвердите  удаление документа"))return;
		var type = $('#form_type').val();
		var document = $('#tub'+type);
		var user = $('#user_id').val();
		if($(this).parents('.first_doc').length>0)
			foto = 0
		else  
			foto = 1
	
		start_load(document,  foto);
		
		$.get("opera/users/take/"+user+"/"+type+"/del/"+foto,
			function()
			{
				
				second= document.find('.second_doc')
				end_load(document,  foto);
				document.find('.error_foto').text('Изображение успешно удалено').show();
				
				if(foto==1 && second[0]) //удаление второй фотографии
				{
					second_remove(document)
				}
				else if(foto==0  && second[0]) //удаление первой  и  замещение  второй
				{
					document.find('.first_doc .image').remove()
					document.find('.first_doc .document').prepend(document.find('.second_doc .image'))
					select_form(document, 1)
					second_remove(document)
				}
				else if(foto==0)
				{
				document.html('Документ не загружен');
				}
			})
	});
	

	//изменение статуса
	$('.status_change .save').bind('click',function()
	{
	
		var user= $('#user_id').val();
		var type=$('#form_type').val();
		var document = $('#tub'+type);
		var state=$(this).parents('.status_change').find('.status').val()
		if($(this).parents('.first_doc').length>0)
			foto = 0
		else  
			foto = 1

		if(state==1)state='ithink';
		else if(state==2) state="yes";
		else if(state==3) state="no";
		start_load(document,  foto);
		$.get("opera/users/take/"+user+"/"+type+"/"+state+"/"+foto,
			function()
			{
				end_load(document,  foto);
				document.find('.error_foto').text('Изменения сохранены').show();
				second= document.find('.second_doc')
				
				if(state=="ithink"){
					if(foto==0){
						second_remove(document)
					}
				}
				else  if(state=="yes"){
					if(foto==1){
						document.find('.first_doc .image').remove()
						document.find('.first_doc .document').prepend(document.find('.second_doc .image'))
						second_remove(document)
						select_form(document, 2)
					}
				}
				else if(state=="no"){
					second_remove(document)
				}
			});
		});
	})

	function  second_remove(document)
	{
	 	document.find('.second_doc').empty().remove();
	 	document.find('.first_doc .old_agree ').remove();
	}
	function  start_load(document, foto)
	{
		if(foto==0)  document.find('.first_doc .status_change .load_status ').html('<img src="/images/ajax-loader-small.gif" >'); 
		else document.find('.second_doc .status_change .load_status ').html('<img src="/images/ajax-loader-small.gif" >')
	}
	
	function  end_load(document, foto)
	{
		if(foto==0)  document.find('.first_doc .status_change .load_status ').html('')
		else document.find('.second_doc .status_change .load_status ').html('');
		
		document.find('.error_foto').delay(5000).fadeOut( 400 )
	}
	
	function  select_form(document, val)
	{
		document.find('.first_doc .status_change .status option:selected').each(function(){
			$(this).attr("selected", "");
		});
		document.find('.first_doc .status_change .status [value="'+val+'"]').attr("selected", "selected").trigger('change');
	}

	function  select_form_main(val)
	{
		$('#form_type option:selected').each(function(){
			$(this).attr("selected", "");
		});
		$('#form_type [value="'+val+'"]').attr("selected", "selected").trigger('change');
	}

