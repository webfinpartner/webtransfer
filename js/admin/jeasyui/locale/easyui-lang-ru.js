if ($.fn.pagination){
	$.fn.pagination.defaults.beforePageText = 'Страница';
	$.fn.pagination.defaults.afterPageText = 'из {pages}';
	$.fn.pagination.defaults.displayMsg = 'Просмотр {from} до {to} из {total} записей';
}
if ($.fn.datagrid){
	$.fn.datagrid.defaults.loadMsg = 'Обрабатывается, пожалуйста ждите ...';
}
if ($.fn.treegrid && $.fn.datagrid){
	$.fn.treegrid.defaults.loadMsg = $.fn.datagrid.defaults.loadMsg;
}
if ($.messager){
	$.messager.defaults.ok = 'Ок';
	$.messager.defaults.cancel = 'Закрыть';
}
if ($.fn.validatebox){
	$.fn.validatebox.defaults.missingMessage = 'Это поле необходимо.';
	$.fn.validatebox.defaults.rules.email.message = 'Пожалуйста введите корректный e-mail адрес.';
	$.fn.validatebox.defaults.rules.url.message = 'Пожалуйста введите корректный URL.';
	$.fn.validatebox.defaults.rules.length.message = 'Пожалуйста введите зачение между {0} и {1}.';
	$.fn.validatebox.defaults.rules.remote.message = 'Пожалуйста исправте это поле.';
}
if ($.fn.numberbox){
	$.fn.numberbox.defaults.missingMessage = 'Это поле необходимо.';
}
if ($.fn.combobox){
	$.fn.combobox.defaults.missingMessage = 'Это поле необходимо.';
}
if ($.fn.combotree){
	$.fn.combotree.defaults.missingMessage = 'Это поле необходимо.';
}
if ($.fn.combogrid){
	$.fn.combogrid.defaults.missingMessage = 'Это поле необходимо.';
}
if ($.fn.calendar){
	$.fn.calendar.defaults.firstDay = 1;
	$.fn.calendar.defaults.weeks  = ['В','П','В','С','Ч','П','С'];
	$.fn.calendar.defaults.months = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
}
if ($.fn.datebox){
	$.fn.datebox.defaults.currentText = 'Сегодня';
	$.fn.datebox.defaults.closeText = 'Закрыть';
	$.fn.datebox.defaults.okText = 'Ок';
	$.fn.datebox.defaults.missingMessage = 'Это поле необходимо.';
}
 
if ($.fn.datetimebox && $.fn.datebox){
	$.extend($.fn.datetimebox.defaults,{
		currentText: $.fn.datebox.defaults.currentText,
		closeText: $.fn.datebox.defaults.closeText,
		okText: $.fn.datebox.defaults.okText,
		missingMessage: $.fn.datebox.defaults.missingMessage
	});
    
   
    $.fn.datebox.defaults.formatter = function(date){
        console.log('datebox formatter ' + date);
        if (!date)
            return '';
        var y = date.getFullYear();
        
        var m = date.getMonth()+1;
        if ( m < 10 ) m = '0' + m;
        var d = date.getDate();
        if ( d < 10 ) d = '0' + d;
        return y+'-'+m+'-'+d;
    }    
    
    $.fn.datebox.defaults.parser = function(s){
        console.log('datebox parser ' + s);
        if (!s) return new Date();
        var dt = s.split(' ');
        var ss = dt[0].split('-');
        var y = parseInt(ss[0],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[2],10);
        console.log('datebox parser res: ' + d + '.' + m + '.' + y);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }

    }        
    
    
    $.fn.datetimebox.defaults.formatter = function(date){
        console.log('formatter ' + date);
        if (!date)
            return '';
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        if ( m < 10 ) m = '0' + m;
        var d = date.getDate();
        if ( d < 10 ) d = '0' + d;
        
        var h = date.getHours();
         if ( h < 10 ) h = '0' + h;
        var mi = date.getMinutes();
        if ( mi < 10 ) mi = '0' + mi;
        var s = date.getSeconds();
        if ( s < 10 ) s = '0' + s;
        
        
        
        console.log('formatter res ' + d+'.'+m+'.'+y+" "+h+":"+mi+":"+s);
        return d+'-'+m+'-'+y+" "+h+":"+mi+":"+s;
    }    
    
    $.fn.datetimebox.defaults.parser = function(s){
        console.log('parser ' + s);
        if (!s) return new Date();
        var dt = s.split(' ');
        
        var ss = dt[0].split('-');
        var y = parseInt(ss[2],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[0],10);

        var ss = dt[1].split(':');
        var h = parseInt(ss[0],10);
        var mi = parseInt(ss[1],10);
        var s = parseInt(ss[2],10);

         console.log('parser params ' + y);
         
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d,h,mi,s);
        } else {
            return new Date();
        }

    }    
    
}


