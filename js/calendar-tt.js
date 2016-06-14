$(function() {
$.datepicker.setDefaults($.extend(
  $.datepicker.regional["ru"])
);
$("#datepicker").datepicker({
maxDate: "+0",  													// Запрет выбора завтрашней даты

beforeShow: function(input) {
    $(input).css("background-color","#ff9");
},
onSelect: function(dateText, inst) {
$(this).css("background-color","");
// alert("Выбрано: " + dateText + "\n\nid: " + inst.id + "\nselectedDay: " + inst.selectedDay + "\nselectedMonth: " + inst.selectedMonth + "\nselectedYear: " + inst.selectedYear);
data_vklada=Date.parse(dateText);
var data_curent = Date.parse(new Date());
// document.getElementById('data_vklada').value = dateText ;		// Вывод избранной даты в input в обычном формате
// document.getElementById('data_vklada_mks').value = data_vklada ;	// Вывод избранной даты в мкс
// document.getElementById('dney').value = data_curent ;			// Текущая дата в мкс
 document.getElementById('razn_mks').value = ((data_curent - data_vklada)/86400000).toFixed(0)-1; // Сколько дней прошло со вклада
 change_form();
},
onClose: function(dateText, inst) {
$(this).css("background-color","");
}  
});



});