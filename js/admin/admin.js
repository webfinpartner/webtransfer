$(function(){

$('#download').click(function(){
$('#validate').validationEngine('detach');
$('#foto').wrap('<form id="foto_form" class="form" enctype="multipart/form-data" action="/opera/'+controller+'/add_foto/'+state+'"  method="post"></form>');

$("#preview").html(''); // чистим preview
$("#preview").html('<img src="/images/ajax-loader-small.gif" />'); //показываем картинку загрузки
$("#foto_form").ajaxForm( //отправляем аякс запрос (тут уже действует jquery.fomr
{
    dataType: "json" ,

success: function (data){

if(data.info=="ok") {
$('#news_foto').attr('src','/opera/'+controller+'/get_foto')
}
else if(data.error=='no') { $('#news_foto').attr('src','/upload/imager.php?src='+image_folder+'/'+data.info+'&w=100'  ) }
$("#preview").html(''); // чистим preview



if(data.error=='no')
    {
$('#news_foto').fadeIn(400); 
$('#foto_error').fadeOut(400);
$('#foto_delete').fadeIn(400); 
$('#info_delete').fadeOut(400); 

}
else if(data.error=="yes")
    {
        $('#news_foto').fadeOut(400); 
$('#foto_delete').fadeOut(400); 
$('#foto_error').fadeIn(400);
$('#info_delete').fadeOut(400);
$('#foto_error').text(data.info);
    }
}
}).submit();
$(".ui-datepicker").css("display","none")
$('#foto').unwrap();
$('#validate').validationEngine('attach');
})

$("#foto_delete").click(function () {
if(!window.confirm('Вы уверены что хотите удалить?'))return false;
else {
    
    if(state!=0)
    $.get( '/opera/'+controller+'/delete_foto/'+state); 
else
    $.get( '/opera/'+controller+'/del_ses_foto'); 

$('#news_foto').fadeOut(400); 
$('#foto_delete').fadeOut(400); 
$('#foto_error').fadeOut(400);
$('#info_delete').fadeIn(400); 
 }})
});
