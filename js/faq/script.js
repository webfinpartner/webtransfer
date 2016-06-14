/*(function($) {
	$(document).ready(function(){
		// Initialise the scrollpanes
	   $('.scroll-pane').jScrollPane();

	});
})(jQuery);*/
function updateContent(obj) {
	$('input[alt]', obj).each(function() {
		if ($(this).attr('alt').length>0 && this.value=='') this.value = $(this).attr('alt');
	});
}
console.log('allo');
console.log($);
(function($) {
    
$(document).ready(function(){

	$('.ajax-content form.ajax .subm').live('click',function(){
	
	/*	var $form = $(this).closest('form');
		$.post($form.attr('action'), $form.serialize($form),function(data){
			alert(data);
			if(data.substr(0,1)=='>') {
				document.location = data.substr(1);
				return;
			}
			$form.closest('.ajax-content').html(data);
			updateContent($form);
		//	$.fancybox.update();
		});
		return false;
	*/	
		$(this).closest('form').submit(); return false;	
	
	});

	$(window).resize(function(){
		if(screenWidth()<1000){
			$('.content.open .cont .right_480').addClass('plansh');
			$('.content.pod .cont .right_480').addClass('plansh');
			$('.content.help .cont .right_480').addClass('plansh');
			$('body').css('border','0px');
		}else{
			$('.content.open .cont .right_480').removeClass('plansh');
			$('.content.pod .cont .right_480').removeClass('plansh');
			$('.content.help .cont .right_480').removeClass('plansh');
			$('body').css('border','23px solid #353536');
		}
	});
	
	if(screenWidth()<1000){
		$('.content.open .cont .right_480').addClass('plansh');
		$('.content.pod .cont .right_480').addClass('plansh');
		$('.content.help .cont .right_480').addClass('plansh');
		$('body').css('border','0px');
	}
	
	//ФУНКЦИЯ СКРОЛЛИНГА
	$(window).scroll(function(){/*скролл*/
		parallaxScroll($(window).scrollTop());
		if( (location.pathname == '/open') || (location.pathname == '/en/open') ||(location.pathname == '/open/') || (location.pathname == '/en/open/') ){
			if($(window).scrollTop() >= 900){
				$('.right_480').css({'margin-top':'750px'});
			}else{
				$('.right_480').css({'margin-top':'0px'});
			}
		}
	});
	
	//ПОЛЯ С ДАТОЙ НА СТРАНИЦЕ ОТКРЫТИЯ СЧЕТА
	if(location.pathname.indexOf('/open') + 1){
		if(location.pathname.indexOf('/en') + 1){
			$("#dat_roz").dateinput({
				lang: 'en',
				format: 'dd.mm.yyyy',
				selectors: true,
				min: '1940-01-01',
				yearRange: [-100, 17],             
				firstDay: 0  
			});	
		}else{	
			$.tools.dateinput.localize("ru",  {
			   months:        'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
			   shortMonths:   'Янв,Фев,Мар,Апр,Май,Июн,Июл,Авг,Сен,Окт,Ноя,Дек',
			   days:          'dimanche,lundi,mardi,mercredi,jeudi,vendredi,samedi',
			   shortDays:     'Вс,Пн,Вт,Ср,Чт,Пт,Сб'
			});
			$("#dat_roz").dateinput({
				lang: 'ru',					//язык
				format: 'dd.mm.yyyy',		//формат даты
				selectors: true,			//выпадалки с месяцами и годами
				min: '1940-01-01',			//минимальная дата
				yearRange: [-100, 17],		//диапазон значений года(1912-1995)     
				firstDay: 1  				//первый день - понедельник
			});
		}
	}
	//ПОЛЯ С ДАТОЙ НА СТРАНИЦЕ КАБИНЕТА
	// if(location.pathname.indexOf('/kabinet') + 1){
	// 	if(location.pathname.indexOf('/en') + 1){
	// 		$("#dat_roz").dateinput({
	// 			lang: 'en',
	// 			format: 'dd mmmm yyyy',
	// 			selectors: true,
	// 			min: '2008-01-01',
	// 			yearRange: [-5,5],             
	// 			firstDay: 0  
	// 		});	
	// 	}else{	
	// 		$.tools.dateinput.localize("ru",  {
	// 		   months:        'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь',
	// 		   shortMonths:   'Янв,Фев,Мар,Апр,Май,Июн,Июл,Авг,Сен,Окт,Ноя,Дек',
	// 		   days:          'dimanche,lundi,mardi,mercredi,jeudi,vendredi,samedi',
	// 		   shortDays:     'Вс,Пн,Вт,Ср,Чт,Пт,Сб'
	// 		});
	// 		$(":date").dateinput({
	// 			lang: 'ru',					//язык
	// 			format: 'dd mmmm yyyy',		//формат даты
	// 			selectors: true,			//выпадалки с месяцами и годами
	// 			min: '2008-01-01',			//минимальная дата
	// 			yearRange: [-5,5],		//диапазон значений года(1912-1995)     
	// 			firstDay: 1  				//первый день - понедельник
	// 		});
	// 	}
	// }
	
	//ПАРАЛАКС ЭФФЕКТ
	function parallaxScroll(scrolled){
	
		$('#shlem').css('top',(0-(scrolled*.08))+'px');
		$('#tr1ab').css('top',(0-(scrolled*.02))+'px');
		$('#tr2ab').css('top',(437-(scrolled*.02))+'px');
		$('#tr3ab').css('top',(465-(scrolled*.02))+'px');
		$('#tr4ab').css('top',(250-(scrolled*.14))+'px');
		
		$('#tr1kt').css('top',(250-(scrolled*.05))+'px');
		$('#tr2kt').css('top',(87-(scrolled*.02))+'px');
		$('#tr3kt').css('top',(95-(scrolled*.02))+'px');
		
		$('#noz').css('top',(105-(scrolled*.1))+'px');
		$('#tr1hp').css('top',(540-(scrolled*.06))+'px');
		$('#tr2hp').css('top',(200-(scrolled*.04))+'px');
		
		$('#medal').css('top',(90-(scrolled*.08))+'px');
		$('#tr1pd').css('top',(80-(scrolled*.12))+'px');
		$('#tr2pd').css('top',(130-(scrolled*.04))+'px');
		$('#tr3pd').css('top',(450-(scrolled*.12))+'px');
		$('#tr4pd').css('top',(520-(scrolled*.04))+'px');
		
		$('#shit').css('top',(80-(scrolled*.06))+'px');
		$('#tr1nw').css('top',(80-(scrolled*.04))+'px');
		$('#tr2nw').css('top',(105-(scrolled*.1))+'px');
		$('#tr3nw').css('top',(250-(scrolled*.12))+'px');
		$('#tr4nw').css('top',(390-(scrolled*.08))+'px');
		
		$('#dosp').css('top',(110-(scrolled*.06))+'px');
		$('#tr1op').css('top',(80-(scrolled*.1))+'px');
		$('#tr2op').css('top',(360-(scrolled*.08))+'px');
		$('#tr3op').css('top',(365-(scrolled*.04))+'px');
		$('#tr4op').css('top',(755-(scrolled*.04))+'px');
		
		$('#sl4').css('top',(50-(scrolled*.08))+'px');
		$('#tr14').css('top',(70-(scrolled*.04))+'px');
		$('#tr24').css('top',(120-(scrolled*.04))+'px');
		$('#tr34').css('top',(350-(scrolled*.04))+'px');
		
	}
	
	
	//ПЛЕЕР И ГРАФИК НА ГЛАВНОЙ
/*	
	if( ($("#osmplayer").size()>0) ){
		
		$("#osmplayer").osmplayer({
			width: '100%',
			height: '350px',
			vertical: true,
			showPlaylist: false,
			playlist: '/video/playlist.xml',
			autoHide: false,
			PlaylistSize: 150
		});
	}
	
		var d1 = [[1, 1], [2, 2], [3, 3], [4, 4], [5, 10], [6, 16], [7, 2], [8, 7], [9, 4], [10, 11], [11, 14], [12, 4]];
		// var d2 = [[1, 10], [2, 6], [3, 8], [4, 7], [5, 3], [6, 16], [7, 8], [8, 13], [9, 10], [10, 4], [11, 7], [12, 3]];
		var d2 = [[1, 9], [2, 1], [3, 8], [4, 7], [5, 3], [6, 16], [7, 8], [8, 13], [9, 10], [10, 4], [11, 7], [12, 3]];
		var d3 = [[1, 5], [2, 4], [3, 3], [4, 2], [5, 1], [6, 2], [7, 5], [8, 8], [9, 10], [10, 13], [11, 14], [12, 15]];
		$.plot($("#placeholder1"), [{ data:d1 }],{
			series:{lines:{ show: false}, bars:{ show: true, barWidth: .8, align: 'left'}, points:{ show: false, radius: 3.5 },color: "#bf1e2e"},
			selection: {mode: "x"},
			xaxis: {ticks: 0,min:0.5, max: 13,color: "#fff"},
			yaxis:{ticks: 8, min: 0, max: 17,show: true,color: "#fff"},
			grid: {show: true,aboveData: true,borderWidth: 0,autoHighlight: true, mouseActiveRadius: 10}
		});
		$.plot($("#placeholder2"), [{ data:d2 }],{
			series:{lines:{ show: false}, bars:{ show: true, barWidth: .8, align: 'left'}, points:{ show: false, radius: 3.5 },color: "#bf1e2e"},
			selection: {mode: "x"},
			xaxis: {ticks: 0,min:0.5, max: 13,color: "#fff"},
			yaxis:{ticks: 8, min: 0, max: 17,show: true,color: "#fff"},
			grid: {show: true,aboveData: true,borderWidth: 0,autoHighlight: true, mouseActiveRadius: 10}
		});
		$.plot($("#placeholder3"), [{ data:d3 }],{
			series:{lines:{ show: false}, bars:{ show: true, barWidth: .8, align: 'left'}, points:{ show: false, radius: 3.5 },color: "#bf1e2e"},
			selection: {mode: "x"},
			xaxis: {ticks: 0,min:0.5, max: 13,color: "#fff"},
			yaxis:{ticks: 8, min: 0, max: 17,show: true,color: "#fff"},
			grid: {show: true,aboveData: true,borderWidth: 0,autoHighlight: true, mouseActiveRadius: 10}
		});
	  
	}
	

	//ПЛЕЕР НА СТРАНИЦЕ ХЕЛПА
	if((location.pathname == '/help') || (location.pathname == '/en/help') || (location.pathname == '/help/') || (location.pathname == '/en/help/')){
		$("#osmplayer1").osmplayer({
			width: '100%',
			height: '300px',
			vertical: true,
			playlist: '/video/playlist_help.xml',
			autoHide: false,
			PlaylistSize: 150
		});
	}
	*/
	   
	//СКРЫТИЕ ПЛЕЙЛИСТА ПЛЕЕРА
	setTimeout(delayFunc,1000);
	function delayFunc() {
		$('.osmplayer-default div.osmplayer-default-hide-show-playlist').click();
		
		$('.osmplayer-default div.osmplayer-default-teaser-image').each(function(){
			if($(this).html()==''){
				$(this).css('height','20px');
			}
		});
	}
	
	//ВИРТУАЛЬНАЯ КЛАВИАТУРА
	$('#vh_pin').live('focus',function(){	
		$('#key').click();	
	});
	$('#pink').live('focus',function(){		
		$('#key1').click();	
	});
	
	// $('#key').live('click',function(){
	// 	if($('#klava').css('display')=='none'){
	// 		$('#klava').show();$('.pin_inp').val('');
	// 	}else{	$('#klava').hide();	}
	// });

	var pin_keyboard = $("<div class='okno min' id='klava'><div class='close1'></div><div class='knop'><div class='kn' name='1'>1</div><div class='kn' name='2'>2</div><div class='kn' name='3'>3</div><div class='kn' name='4'>4</div><div class='kn' name='5'>5</div><div class='kn' name='6'>6</div><div class='kn' name='7'>7</div><div class='kn' name='8'>8</div><div class='kn' name='9'>9</div><div class='kn' name='0'>0</div></div><div class='podt'><div id='bg_kn' class='bk kn'></div><div id='clear_kn' class='cl kn'>C</div><div id='sb_kn' class='sb kn'>"+pinLiterals['confirm']+"</div></div></div>");
	var key_wrapper = $('<div class="keybord_wrapper"></div>');
	var pin_cont = key_wrapper.append(pin_keyboard);

	$('img[alt*="pin"]').live('click',function(){

		key_wrapper.css('display','none');
		$('#vh_pin, #pink').attr('value','');
		if(key_wrapper.css('display')=='none'){
			$(this).parent().append(pin_cont);
			key_wrapper.css({'display':'block'});

			if(key_wrapper.parents('.fancybox-wrap').length){
				key_wrapper.children().css({'display':'block','position':'relative','top':'121px'});
			}
			else {
				key_wrapper.children().css({'display':'block','position':'relative','top':'0'});
			};			
			$('#sb_kn').click(function(){
				key_wrapper.hide();
				return false;
			});

		}else{	$('#klava').hide();	}
	});

		$('#klava .knop .kn').live('click',function(){
		var c = $(this).attr('name');
		var input = $(this).closest('.keybord_wrapper').siblings('input.pin');
		input.val(input.val()+c);
	});
	
	
	$('#bg_kn').live('click',function(){		

		if($('.okno').css('display')=='none'){
			$('#pink').val($('#pink').val().slice(0, -1));
		}else{

			$('.pin_inp').val($('.pin_inp').val().slice(0, -1));

		}
	});


	$('#clear_kn').live('click',function(){
		$('input#vh_pin').attr('value','');
	});

	//ВХОД ДЛЯ КЛИЕНТОВ
/*	$('#vh_klien').click(function(){
		$('.fonov').slideDown("fast");
		$('#okno1').load('/site/login',function(){updateContent($('#okno1'));});
		$('#okno1').slideDown("fast");
		return false;
	
	});
	*/
	//ЗАКАЗАТЬ ЗВОНОК
	$('#zak_zvon').click(function(){
		$('.fonov').slideDown("fast");
		$('#okno1').load('/site/callback',function(){updateContent($('#okno1'));});
		$('#okno1').slideDown("fast");
		return false;
	});
	
	//восстановить пароль
	$('#rempass').click(function(){
		alert('qwert');
	//	$('.fonov').slideDown("fast");
	//	$('#okno1').load('/site/PasswordRemind',function(){updateContent($('#okno1'));});
	//	$('#okno1').slideDown("fast");
		//return false;
	});
	
	
	
	//ЗАКРЫТЬ ПРИ КЛИКЕ НА ФОН
	$('.fonov').click(function(){
		$('.fonov').slideUp("fast");
		$('.okno').slideUp("fast");
		return false;
	});
	//КЛИК ПО КРЕСТИКУ
	$('.close').live('click',function(){
		$('.fonov').click();
	});
	$('.close1').live('click',function(){
		$('#klava').hide();
	});
	//ОТПРАВИТЬ "ВХОД"
	$('#subm1').click(function(){
		var mail=$('#vh_mail').val();
		var pass=$('#vh_pass').val();
		var pin=$('#vh_pin').val();
		var err=0;
		
		//ПРОВЕРКА E-MAIL-а
		if ((mail == $('#vh_mail').attr('alt')) || (mail == '')) {
			$('#vh_mail').css('border','1px solid #cb2337'); $('#vh_er1').show(); err+=1;
		} else {$('#vh_mail').css('border','1px solid #000'); $('#vh_er1').hide();}
		if (pass == '') {
			$('#vh_pass').css('border','1px solid #cb2337'); $('#vh_er2').show(); err+=1;
		} else {$('#vh_pass').css('border','1px solid #000'); $('#vh_er2').hide();}
		if ((pin == $('#vh_mail').attr('alt')) || (pin == '')) {
			$('#vh_pin').css('border','1px solid #cb2337'); $('#vh_er3').show(); err+=1;
		} else {$('#vh_pin').css('border','1px solid #000'); $('#vh_er3').hide();}
		
		if(err>0){
			$('#vh_err').slideDown("slow");
			$('#okno1').css('height','440px');
		}else{
			$('#vh_err').hide();
			$('#vh_cong').slideDown("slow");
			$('#okno1').css('height','360px');
			setTimeout(function() {
				$('#vh_cong').hide();
				$('#okno1').css('height','310px');
				$('.fonov').click();
				if(location.pathname.indexOf('/en') + 1){
					location.href='/en/kabinet';
				}else{
					location.href='/kabinet';
				}
			},2000);
		}
		
		return false;
	});
	//ОТПРАВИТЬ "ЗАКАЗ ЗВОНКА"
	$('#subm2').click(function(){
		var phone=$('#zak_phone').val();
		var err=0;
		
		//ПРОВЕРКА НОМЕРА ТЕЛЕФОНА
		if ((phone == $('#zak_phone').attr('alt')) || (phone == '')) {
			$('#zak_phone').css('border','1px solid #cb2337'); $('#zv_er1').show(); err+=1;
		} else {$('#zak_phone').css('border','1px solid #000'); $('#zv_er1').hide();}
		
		if(err>0){
			$('#zv_err').slideDown("slow");
			$('#okno2').css('height','315px');
		}else{
			$('#zv_err').hide();
			$('#zv_cong').slideDown("slow");
			$('#okno2').css('height','250px');
			setTimeout(function() {
				$('#zv_cong').hide();
				$('#okno2').css('height','200px');
				$('.fonov').click();
			},3000);
		}
		
		return false;
	});
	
	
	

	
	
	
	//ПОДДЕРЖКА
	$('.left_480 .txt .info-tit').click(function(){
		
		$('.left_480 .txt .info-txt').slideUp("slow");
		$('.left_480 .txt .info-txt').removeClass('act');
		$('.left_480 .txt .info-txt').addClass('deact');
		
		$('.left_480 .txt .info-tit').removeClass('act');
		
		if($(this).next().css('display')=='none'){
			$(this).next().slideDown("slow");
			$(this).next().removeClass('deact');
			$(this).next().addClass('act');
			$(this).addClass('act');
		}else{
			$(this).removeClass('act');
		}
	});
	
	
	//ВЫПАДАЮЩИЙ СПИСОК НА СТРАНИЦЕ ОТКРЫТИЯ СЧЕТА
	$(document).on('click','.sel>span, .sel>img, .sel span.opt', function(){
		
		var $this = $(this).closest('.sel');

		if($this.attr('id')!='in_god') {

			if($this.children('.vip').hasClass('hidden')){
				$('.vip.visible').removeClass('visible').addClass('hidden').fadeOut(200).siblings('img').attr('src','/img/cont/open/vn.png');				
				$this.children('.vip').removeClass('hidden').addClass('visible').fadeIn(200);
				$this.children('img').attr('src','/img/cont/open/vv.png');
			}
			else{
				// if($(this).attr('id')=='jspVerticalBar') return false;
				$this.children('.vip').removeClass('visible').addClass('hidden').fadeOut('200');
				$this.children('img').attr('src','/img/cont/open/vn.png');
			}
		}	
	});
	$(document).on('click','.sel .vip .opt',function(){
		var $this = $(this);
		var $sel = $(this).closest('.sel')
		$sel.find('input').val($(this).attr('value')).change();
		$sel.find('.prev').text($this.text());
		$sel.find('img').attr('id',''+$(this).attr('id'));
	});
	
	
	//ВЫПАДАЮЩИЙ СПИСОК В ЛИЧНОМ КАБИНЕТЕ
	$('.sel_set #open').click(function(){
		$('#vip_set').slideDown("slow");
	});
	$('.sel_set #close').click(function(){
		$('#vip_set').slideUp("slow");
	});
	
	//ЧЕКБОКС
	var updateCheckbox = function() {
		if ($('input', this).val()==1) {
			$(this).css('background','url(/img/cont/open/check.png) 0 0 no-repeat');	
		} else {
			$(this).css('background','url(/img/cont/open/check_not.png) 0 0 no-repeat');	
		}
	};

	$('.check_pol .check').click(function(){
		var $input=$('input', this);
		$input.val($input.val()==1 ?0 :1);  
		$(this).each(updateCheckbox);
	}).each(updateCheckbox);
	
	
	//СКРЫТОЕ ПОЛЕ
	$('#register-form').on('click','#pok_vop',function(){
		//$('.holder #standart_question').();
		if($('#div_vopr').css('display') == 'none'){	
			$('#div_vopr').slideDown(); 
			$(this).parent().next('div').children('div.sel').slideUp();	
			// $(this).parent().css('margin-top','12px');				
		}
		else{	
			$('#div_vopr').slideUp();
			$(this).parent().next('div').children('div.sel').slideDown();
			// $(this).parent().css('margin-top','-31px');
			//$('#in_vop#standart_question.sel').slideDown();	
		}		
	});


	
	//ПРОВЕРКА СЛОЖНОСТИ ПАРОЛЯ
	
	$('#pass').keyup(function(e) {
		var lvl = 0;
		var pswd = $(this).val();
		var passLength = pswd.length;
		
		if (passLength > 6) {
			lvl += 1;
			var seek = pswd.replace(/[^a-za-я]/g,'');
			if (seek.length > 0) lvl += 1; 
			seek = pswd.replace(/[^A-ZА-Я]/g,'');
			if (seek.length > 0) lvl += 1; 
			seek = pswd.replace(/[^0-9]/g,'');
			if (seek.length > 0) lvl += 1; 
			seek = pswd.replace(/[a-za-я0-9]/gi,'');
			if (seek.length > 0) lvl += 1;
		}
		if (lvl < 3) {
			$('#passstrength').addClass('bad');
			$('#passstrength').removeClass('norm');
			$('#passstrength').removeClass('good');
			console.log('bad');
		} else if (lvl < 5) {
			$('#passstrength').addClass('norm');
			$('#passstrength').removeClass('bad');
			$('#passstrength').removeClass('good');
			console.log('good');
		} else  {
			$('#passstrength').addClass('good');
			$('#passstrength').removeClass('bad');
			$('#passstrength').removeClass('norm');
			console.log('normal');
		}
		return true;
	});
	
//проверка на совпадение
	$('#set_pass_ag').keyup(function(e) {
		var pass=$('#pass').val();
		var pass_ag=$('#set_pass_ag').val();
		if(pass==pass_ag){
			$('#prov_pass').attr('src','/img/kabinet/yes.png');
		}else{
			$('#prov_pass').attr('src','/img/kabinet/no.png');
		}
	});
	

	//АВТОЗАПОЛНЕНИЕ ПОЛЕЙ
	$('input,textarea').live('focus',function(){
		if($(this).val() == $(this).attr('alt')){
			$(this).val('');
		}
	});
	$('input,textarea').not('[type="password"],.pin,#plink').live('blur', function (){
		if ($(this).is('[type="password"],.pin,#plink')) return;
		if($(this).val() == ''){
			$(this).val($(this).attr("alt"));
		}
	});

	
//выделение партнерской ссылки
	$('#plink').click(function(){
		$(this).select()
	});
	
//Вывести средства
	$('#vivesti').click(function(){
		var cumv = $('#cumv').val();
		var spos = $('#in_sp_viv .prev').text();
		var noms = $('#noms').val();
		var pink = $('#pink').val();
		var err = 0;
		
		//ПРОВЕРКА cumv
		if ((cumv == $('#cumv').attr('alt')) || (cumv == '')) {
			$('#cumv').css('border','1px solid #cb2337'); err+=1;
		} else {$('#cumv').css('border','1px solid #000');}
		//ПРОВЕРКА spos
		if ((spos == '-выбрать-')||(spos == '-select-')) {
			$('#in_sp_viv').css('border','1px solid #cb2337'); err+=1;
		} else {$('#in_sp_viv').css('border','1px solid #000');}
		//ПРОВЕРКА noms
		if (noms == '') {
			$('#noms').css('border','1px solid #cb2337'); err+=1;
		} else {$('#noms').css('border','1px solid #000'); }
		//ПРОВЕРКА pink
		if (pink == '') {
			$('#pink').css('border','1px solid #cb2337'); err+=1;
		} else {$('#pink').css('border','1px solid #000');}
		
		if(err > 0){
			$('#err_v').slideDown("slow");
		}else{
			$('#err_v').slideUp("fast");
			$('#podtv').slideDown("slow");
		}
	});
	
//Пополнить счет
	$('#popolnit').click(function(){
		var summ = $('#summ').val();
		var valut = $('#in_valut .prev').text();
		var err = 0;
		
		//ПРОВЕРКА summ
		if ((summ == $('#summ').attr('alt')) || (summ == '')) {
			$('#summ').css('border','1px solid #cb2337'); err+=1;
		} else {$('#summ').css('border','1px solid #000');}
		//ПРОВЕРКА valut
		if ((valut == '-выбрать-')||(valut == '-select-')) {
			$('#in_valut').css('border','1px solid #cb2337'); err+=1;
		} else {$('#in_valut').css('border','1px solid #000');}
		
		if(err > 0){
			$('#err_p').slideDown("slow");
		}else{
			$('#err_p').slideUp("fast");
			$('.fonov').slideDown("fast");
			var text='Успешно!';
			var text_en='Successfully!';
			if(location.pathname.indexOf('/en') + 1){
				$('#okno3 .txt span').text(text_en);
			}else{
				$('#okno3 .txt span').text(text);
			}
			$('#okno3').slideDown("fast");
		}
	});

//история пополнений
	$('#but_hist').click(function(){
		var per = $('#in_per .prev').text();
		var vid = $('#in_vid_p .prev').text();
		var err = 0;
		
		//ПРОВЕРКА per
		if ((per == '-выбрать-')||(per == '-select-')) {
			$('#in_per').css('border','1px solid #cb2337'); $('#in_per').click(); err+=1;
		} else {$('#in_per').css('border','1px solid #000');}
		//ПРОВЕРКА vid
		if ((vid == '-выбрать-') ||(vid == '-select-')) {
			$('#in_vid_p').css('border','1px solid #cb2337'); $('#in_vid_p').click(); err+=1;
		} else {$('#in_vid_p').css('border','1px solid #000');}
		
		if(err > 0){
		}else{
			$('.ist-popoln').slideDown(300);
			var haw=window.location.hash.slice(1, 999);
			setTimeout(function delayFunc() {
				$('#centr').height($('.kab_block[name="'+haw+'"]').height());
			},400);
		}
	});

	
//КАБИНЕТ РАЗДЕЛЫ
// Функция обработки изменения хеш-данных 	(кликаем по ссылке с хэшем)
var myHashchangeHandler = function(){ 
	var haw=window.location.hash.slice(1, 999);

	$('.kab_block.act').animate({'margin-left':'-500px','opacity':'0'},200);
		$('.kab_razd .r-one').removeClass('act');
		$('.vivest').removeClass('act'); 
		$('.popoln').removeClass('act');
		$('.sch_link').removeClass('act');
	$('.kab_razd .r-one[alt="'+haw+'"]').addClass('act');
	$('#lev_chast .'+haw).addClass('act');
	$('#tit_kab span').animate({'opacity':'0'},200);
		
	$('.kab_block[name="'+haw+'"]').css({'display':'block'});
	setTimeout(function delayFunc() {
			$('.kab_block.act').css({'margin-left':'200px','z-index':'3','opacity':'0'});
			$('.kab_block.act').removeClass('act');
	},250);
	setTimeout(function delayFunc() {
			
			if(haw=='ls') haw1='ticket/ticket/create';
//alert(haw);			
			$('.kab_block[name="'+haw+'"]').load('/'+haw1+'',function(){updateContent($('.kab_block[name="'+haw+'"]'));});
			
			   //   $('.kab_block[name="'+haw+'"]').load('/ticket/ticket/create',function(){updateContent($('.kab_block[name="'+haw+'"]'));});
		
			$('.kab_block[name="'+haw+'"]').animate({'margin-left':'0px','z-index':'9','opacity':'1'},200);
			$('.kab_block[name="'+haw+'"]').addClass('act');
			$('#centr').height($('.kab_block[name="'+haw+'"]').height());
			$('#tit_kab span[alt="'+haw+'"]').animate({'opacity':'1'},200);
	},500);
} 
// Навешиваем функцию обработки на событие смены хеша 
if("addEventListener" in window) { 		// для всех веб-браузеров кроме IE 
   window.addEventListener("hashchange", myHashchangeHandler, false); 
} else if ("attachEvent" in window) {   // для IE 
   window.attachEvent( "onhashchange", myHashchangeHandler); 
} 

//показываем выбраный блок при загрузке страницы
	if(window.location.hash){
		var haw=window.location.hash.slice(1, 999);
		$('.kab_block.act').animate({'margin-left':'-500px','opacity':'0'},200);
			$('.kab_razd .r-one').removeClass('act');
			$('.vivest').removeClass('act'); 
			$('.popoln').removeClass('act');
			$('.sch_link').removeClass('act');
		$('.kab_razd .r-one[alt="'+haw+'"]').addClass('act');
		$('#lev_chast .'+haw).addClass('act');
		$('#tit_kab span').animate({'opacity':'0'},200);
		
		$('.kab_block[name="'+haw+'"]').css({'display':'block'});
		setTimeout(function delayFunc() {
			$('.kab_block.act').css({'margin-left':'200px','z-index':'3','opacity':'0','block':'none'});
			$('.kab_block.act').removeClass('act');
		},250);
		setTimeout(function delayFunc() {
			$('.kab_block[name="'+haw+'"]').animate({'margin-left':'0px','z-index':'9','opacity':'1'},200);
			$('.kab_block[name="'+haw+'"]').addClass('act');
			$('#centr').height($('.kab_block[name="'+haw+'"]').height());
			$('#tit_kab span[alt="'+haw+'"]').animate({'opacity':'1'},200);
		},500);
		
	}

//СФОРМИРОВАТЬ ОТЧЕТ 
	$('#sform_hist').click(function(){
		var nach = $('#dat_nach').val();
		var kon = $('#dat_kon').val();
		if(nach!='' && kon!=''){
			$('.ist-schet').slideDown(300);
			var haw=window.location.hash.slice(1, 999);
			setTimeout(function delayFunc() {
				$('#centr').height($('.kab_block[name="'+haw+'"]').height());
			},400);
		}else{
			if(kon=='') $('#dat_kon').focus();
			if(nach=='') $('#dat_nach').focus();
		}
		return false;
	});
//вывести средства
	$('.vivest').click(function(){
		if($(this).hasClass('act')){}else{	
			$(this).addClass('act');
			$('.popoln').removeClass('act');
			$('.kab_razd .r-one').removeClass('act');
		}
	});
//пополнить счет
	$('.popoln').click(function(){
		if($(this).hasClass('act')){}else{	
			$(this).addClass('act');
			$('.vivest').removeClass('act'); 
			$('.kab_razd .r-one').removeClass('act');
		}
	});
	
	
//НАВИГАЦИЯ ПО ТАБЛИЦЕ
	$('.pages li').click(function(){
		var name=$(this).attr('name');//alert(name);
		var tabl=$(this).parent().attr('name');
		if(name==1) {
			$('.tabl[name="'+tabl+'"] .pages li[name="p"]').addClass('dis'); 
		}else{ 
			$('.tabl[name="'+tabl+'"] .pages li[name="p"]').removeClass('dis');
		}
		if(name==$('.tabl[name="'+tabl+'"] .pages li').eq(-2).attr('name')) $('.tabl[name="'+tabl+'"] .pages li[name="n"]').addClass('dis'); else $('.tabl[name="'+tabl+'"] .pages li[name="n"]').removeClass('dis');
		
		if(name){
			if(name=='p'){
				$('.tabl[name="'+tabl+'"] .pages li').each(function(i,elem) {
					if ($(this).hasClass('act')){
						var num=$(this).attr('name')-1;
						if(num==1 || num==0) $('.tabl[name="'+tabl+'"] .pages li[name="p"]').addClass('dis'); else $('.tabl[name="'+tabl+'"] .pages li[name="p"]').removeClass('dis');
						if(num!=0){ 
							$('.tabl[name="'+tabl+'"] .zay_tabl').slideUp("fast");
							$('.tabl[name="'+tabl+'"] .zay_tabl[name="p'+num+'"]').slideDown("fast");
							$('.tabl[name="'+tabl+'"] .pages li').removeClass('act');
							$('.tabl[name="'+tabl+'"] .pages li[name="'+num+'"]').addClass('act');
						}
					}
				});
			} else if(name=='n'){
				$('.tabl[name="'+tabl+'"] .pages li').each(function(i,elem) {
					if ($(this).hasClass('act')){
						var num=Number($(this).attr('name'))+1; 
						if(num>=$('.tabl[name="'+tabl+'"] .pages li').eq(-2).attr('name')) $('.tabl[name="'+tabl+'"] .pages li[name="n"]').addClass('dis'); else $('.tabl[name="'+tabl+'"] .pages li[name="n"]').removeClass('dis');
						if(num<=$('.tabl[name="'+tabl+'"] .pages li').eq(-2).attr('name')){ 
							$('.tabl[name="'+tabl+'"] .zay_tabl').slideUp("fast");
							$('.tabl[name="'+tabl+'"] .zay_tabl[name="p'+num+'"]').slideDown("fast");
							$('.tabl[name="'+tabl+'"] .pages li').removeClass('act');
							$('.tabl[name="'+tabl+'"] .pages li[name="'+num+'"]').addClass('act');
							return false;
						}
					}
				});
			} else{
				$('.tabl[name="'+tabl+'"] .zay_tabl').slideUp("fast");
				$('.tabl[name="'+tabl+'"] .zay_tabl[name="p'+name+'"]').slideDown("fast");
				$('.tabl[name="'+tabl+'"] .pages li').removeClass('act');
				$(this).addClass('act');
			}
		}
	});
	

//меню в личных сообщениях	
/*	$('#ls_menu span').click(function(){
		var nazv=$(this).attr('name');
		if($(this).hasClass('act')){
		}else{
			$('#ls_menu span').removeClass('act');
			$(this).addClass('act');
			$('.zayav-podr').slideUp(300);
			$('.zayav-podr#'+nazv).slideDown(300);
			setTimeout(function delayFunc() {
				$('#centr').height($('.zayav-podr#'+nazv).height()+$('#ls_menu').height());
			},400);
		}
	});
*/	
	$('#zay_o4is').click(function(){
		$('#zay_zag').val('');
		$('#zay_mes').val('');
		if(location.pathname.indexOf('/en') + 1){
			$('#in_zay_tem .prev').text('-select-');
		}else{
			$('#in_zay_tem .prev').text('-выбрать-');
		}
		$('#in_zay_tem img').attr('id','tem');
	});
	
	$('#zay_otpr').click(function(){
		var zag=$('#zay_zag').val();
		var mes=$('#zay_mes').val();
		var tem=$('#in_zay_tem .prev').text();
		var err = 0;
		
		if(zag == ''){		$('#zay_zag').css('border','1px solid #cb2337');$('li[name="er1"]').show(); err+=1;	}else{	$('#zay_zag').css('border','1px solid #000');$('li[name="er1"]').hide();	}
		if(mes == ''){		$('#zay_mes').css('border','1px solid #cb2337');$('li[name="er2"]').show(); err+=1;	}else{	$('#zay_mes').css('border','1px solid #000');$('li[name="er2"]').hide();	}
		if((tem == '-выбрать-')||(tem == '-select-')){	$('#in_zay_tem').css('border','1px solid #cb2337');$('li[name="er3"]').show(); err+=1;	}else{	$('#in_zay_tem').css('border','1px solid #000');$('li[name="er3"]').hide();	}
		if(err>0){
		}else{
		$('.fonov').slideDown("fast");
		var text='Заявка успешно отправлена. ';
		var text_en='Application submitted successfully. ';
		if(location.pathname.indexOf('/en') + 1){
			$('#okno3 .txt span').text(text_en);
		}else{
			$('#okno3 .txt span').text(text);
		}
		$('#okno3').slideDown("fast");
		}
	});

//кнопка "зарегистрироваться"

$('.submit').click(function(){
	var fname = $('#fname').val();
	var sname = $('#sname').val();
	var lname = $('#lname').val();
	var mail = $('#mail').val();
	var pass = $('#pass').val();
	var pass_ag = $('#pass_ag').val();
	if($('#div_vopr').css('display') == 'none'){
		var vopr = $('#in_vop .prev').text();
	}else{
		var vopr = $('#sv_vopr').val();
	}
	var otvet = $('#otvet').val();
	var pol = $('#in_pol .prev').text();
	var dat_roz = $('#dat_roz').val();
	var stran = $('#in_str .prev').text();
	var region = $('#in_reg .prev').text();
	var city = $('#city').val();
	var adres = $('#adres').val();
	var pind = $('#pind').val();
	var phone = $('#phone').val();
	var check = $('.check_pol .check').attr('name');
	var kapch = $('.div_kapch input').val();
	var err = 0;
	
	//ПРОВЕРКА ИМЕНИ
	if ((fname == $('#fname').attr('alt')) || (fname == '')) {
		$('#fname').css('border','1px solid #cb2337'); $('#er1').show(); $('li[name="er1"]').show(); err+=1;
	} else {$('#fname').css('border','1px solid #000'); $('#er1').hide(); $('li[name="er1"]').hide();}
	
	//ПРОВЕРКА ФАМИЛИИ
	if ((lname == $('#lname').attr('alt')) || (lname == '')) {
		$('#lname').css('border','1px solid #cb2337'); $('#er2').show(); $('li[name="er2"]').show(); err+=1;
	} else {$('#lname').css('border','1px solid #000'); $('#er2').hide(); $('li[name="er2"]').hide();}
	
	//ПРОВЕРКА E-MAIL-а
	if ((mail == $('#mail').attr('alt')) || (mail == '')) {
		$('#mail').css('border','1px solid #cb2337'); $('#er3').show(); $('li[name="er3"]').show(); err+=1;
	} else {$('#mail').css('border','1px solid #000'); $('#er3').hide(); $('li[name="er3"]').hide();}
	
	//ПРОВЕРКА ПАРОЛЯ
	if (($('#passstrength').hasClass('bad')) || (pass == '')) {
		$('#pass').css('border','1px solid #cb2337'); $('#er4').show(); $('li[name="er4"]').show(); err+=1;
	} else {$('#pass').css('border','1px solid #000'); $('#er4').hide(); $('li[name="er4"]').hide();}
	
	//ПРОВЕРКА ПОВТОРА ПАРОЛЯ
	if (pass != pass_ag) {
		$('#pass_ag').css('border','1px solid #cb2337'); $('#er5').show(); $('li[name="er5"]').show(); err+=1;
	} else {$('#pass_ag').css('border','1px solid #000'); $('#er5').hide(); $('li[name="er5"]').hide();}
	
	//ПРОВЕРКА ВОПРОСА
	if($('#div_vopr').css('display') == 'none'){
		if ($('#in_vop img').attr('id')=='vop') {
			$('#in_vop').css('border','1px solid #cb2337'); $('#er6').show(); $('li[name="er6"]').show(); err+=1;
		} else {$('#in_vop').css('border','1px solid #000'); $('#er6').hide(); $('li[name="er6"]').hide();}
	}else{
		if ((vopr == $('#sv_vopr').attr('alt')) || (vopr == '')) {
			$('#sv_vopr').css('border','1px solid #cb2337'); $('#er6').show(); $('li[name="er6"]').show(); err+=1;
		} else {$('#sv_vopr').css('border','1px solid #000'); $('#er6').hide(); $('li[name="er6"]').hide();}
	}
	
	//ПРОВЕРКА ОТВЕТА
	if ((otvet == $('#otvet').attr('alt')) || (otvet == '')) {
		$('#otvet').css('border','1px solid #cb2337');
		 $('#er7').show(); $('li[name="er7"]').show(); err+=1;
	} else {$('#otvet').css('border','1px solid #000'); $('#er7').hide(); $('li[name="er7"]').hide();}
	
	//ПРОВЕРКА ПОЛА
	if ($('#in_pol img').attr('id')=='pol') {
		$('#in_pol').css('border','1px solid #cb2337'); $('#er8').show(); $('li[name="er8"]').show(); err+=1;
	} else {$('#in_pol').css('border','1px solid #000'); $('#er8').hide(); $('li[name="er8"]').hide();}
	
	//ПРОВЕРКА СОГЛАШЕНИЯ
	if (check == 'no') {
		$('.content.open .check_pol').css('color','#cb2337'); $('#er9').show(); $('li[name="er9"]').show(); err+=1;
	} else {$('.content.open .check_pol').css('color','#cb2337'); $('#er9').hide(); $('li[name="er9"]').hide();}
	
	//ПРОВЕРКА КАПЧИ
	
	//ЕСТЬ/НЕТУ ОШИБОК
	if(err > 0){
		$('#err').slideDown("slow");
	}else{
		$('#err').slideUp("slow");
		$('.fonov').slideDown("fast");
		var text='Регистрация прошла успешно! На ваш электронный адрес поступило уведомление, подтвердив его, вы станите полноправным членом фонда ARBITRAGE TOP, со всеми привелегиями. ';
		var text_en='Registration is done! Message and link sent to your email. After clicking on the link, you will be member of the ARBITRAGE TOP found.';
		if(location.pathname.indexOf('/en') + 1){
			$('#okno3 .txt span').text(text_en);
		}else{
			$('#okno3 .txt span').text(text);
		}
		$('#okno3').slideDown("fast");
		//alert(fname+' '+sname+' '+lname+' '+mail+' '+pass+' '+pass_ag+' '+vopr+' '+otvet+' '+pol+' '+dat_roz+' '+stran+' '+region+' '+city+' '+adres+' '+pind+' '+phone+' '+check+' '+kapch);
	}	
});

//КНОПКА ПРОДОЛЖИТЬ В ИНФОРМАЦИОННОМ ОКНЕ
$('#subm33').live('click',function(){
	//	$('.fonov').click();
		location.href = '/';
	});
	
$('#subm13').live('click',function(){
	//	$('.fonov').click();
		location.href = '/site/login';
	});

/*отправка
	$('#vr_but').click(function(){
		var h=$('#obl img').attr('id').substring(1, 5);
		var m=$('#kat img').val();
		var t=$('#obr_tel').val();
		if((t=='')||(t=='+38...')){
			$('#obr_tel').focus();
		}else{
			var vrem=h+':'+m;
			$$a({
				type:'post',
				url:'/zv.php',
				data:{'phone':t,'vr':vrem},
				response:'text',
				success:function (data) {
					if(data=='123'){
						//window.location = '/';
						$('#usp1').text('Заявка успешно отправлена!');
						$('#usp1').css({'font-weight':'bold','color':'#944'});
					}else{
						alert('Ошибка');
					}
				}
			});
		}
	});	
*/
});
});
//РЕАЛЬНАЯ ВЫСОТА ОКНА
function screenHeight(){
	return $.browser.opera? window.innerHeight : $(window).height();
}

//РЕАЛЬНАЯ ШИРИНА ОКНА
function screenWidth(){
	return $.browser.opera? window.innerWidth : $(window).width();
}


//ФУНКЦИЯ УДАЛЕНИЯ ПЛЕЙСХОЛДЕРА ПРИ ПОПАДАНИИ ИНПУТА В ФОКУС
$(document).ready(function(){
	if ($('input').hasClass('ref-link')) {
		return false;
	}
	else {
		$('input').live('focusin',function(){
		    $(this).data('holder',$(this).attr('placeholder'));
	        $(this).attr('placeholder','');
	    });
	    $('input').live('focusout',function(){
	        $(this).attr('placeholder',$(this).data('holder'));
	    });
	};    
})

$(document).ready(function(){
	prettyTable();
});

function prettyTable(){		
	$('.kabinet table').wrap('<div class="table-wrapper"></div>');   
    $('.kabinet table th').wrapInner('<p></p>');
    $('.kabinet table th').prepend('<span class="ticket-grid-border"></span>');    
    $('.kabinet table tr td').wrapInner('<p></p>');  
    $('.kabinet table tr:not(:last-child) td:first-child').prepend('<span class="ticket-grid-border"></span>');
    $('.kabinet table tr:not(:last-child) td:last-child').prepend('<span class="ticket-grid-border"></span>');        
    $('.kabinet table tr:last-child td').prepend('<span class="ticket-grid-border2"></span>');
    $('.kabinet table tr:last-child td:first-child').prepend('<span class="ticket-grid-border1"></span>');
    $('.kabinet table tr:last-child td:last-child').prepend('<span class="ticket-grid-border1"></span>');
    if ($('.kabinet table tr td span.empty')) {
    	$('.kabinet table tr td span.empty').parent('p');
    	$('.kabinet table tr td p').parent('td').addClass('no-result');
    	$('.kabinet table tr td.no-result').prepend('<span class="ticket-grid-border3"></span>');
    };  	  
};


  
function activateMenuItem(item)
{	
	$(item).addClass('active-link');
	$('.ac-container .active-link').parent('span').addClass('active-span');
	$('.ac-container .active-span').parent('article').addClass('active-article');
	$('.ac-container .active-article').parent('div').addClass('active-div');
	$('.ac-container .active-div input').attr('checked','true');
	$('.ac-container .active-span').css("background", "url(/img/cont/open/fon_input.png)");  
	$('.ac-container .active-div label').click(function() {
		 $('.ac-container .active-div article').css({
			 transition: 'height 0.3s ease-in-out, box-shadow 0.6s linear',
			 '-webkit-transition': 'height 0.3s ease-in-out, box-shadow 0.6s linear',
			 '-moz-transition': 'height 0.3s ease-in-out, box-shadow 0.6s linear',
			 '-o-transition': 'height 0.3s ease-in-out, box-shadow 0.6s linear',
			 '-ms-transition': 'height 0.3s ease-in-out, box-shadow 0.6s linear'
		 });
	 }); 
}


//ЗАПОМИНАНИЕ АКТИВНОГО ПУНКТА МЕНЮ АККОРДЕОНА
$(document).ready(function() {
	var location = window.location.pathname;
	
	if (/^(\/en|\/ru)?\/(.*?)(\.html|\/)?$/.test(location)) location = RegExp.$2;
	
	var filterUrl = function() {
		if (links==null) return false;
		var link = this.href;
		link = link.split(/#|\?/).shift();
//		if (/\.\w+\/(en\/|ru\/)?(.*?)(\.html|\/)?$/.test(link)) link = RegExp.$2;
		if (/\.\w+\/(en\/|ru\/)?(.*?)(\.html|\/)?$/.test(link)) link = RegExp.$2;
		links[link] = this;
		if (location == link) {
			// links = null;
			return true;
		};
		return false;
	};
	var activateLinks = function(links, activate) {
		if (!links) return;
		while(/^(.+?)\/[^\/]+$/.test(location)) {
			location = RegExp.$1;
			if(location in links) {
				activate(links[location]);
			}
		}
	};
	var actBtmMenu = function(link)  { $(link).closest('.r-one').addClass('act');}
	
    var links={};
	$('.ac-container span a').filter(filterUrl).each(function(){  activateMenuItem(this);});
	$('.header .razd a, .foot .razd a').filter(filterUrl).each(function(){  activateMenuItem(this);});
	$('.foot .razd a').filter(filterUrl).each(function(){  activateMenuItem(this);});
	activateLinks(links, activateMenuItem);	
	links={};
	$('.kab_razd .r-one a').filter(filterUrl).each(function(){ actBtmMenu(this); });
	activateLinks(links, actBtmMenu);
	
});



function selectBanner(){	
	$('.partner-banners .banners input').next().removeClass('checked-banner');
	$('.partner-banners .banners input:checked').next().addClass('checked-banner');	
};


$(document).ready(function(){
	$('.partner-banners .banners input:first').next().addClass('checked-banner');
});



$(document).ready(function(){
	if ($('input#sv_vopr').val() != '') {		
		$('#div_vopr').show();
		$('input#sv_vopr').parent().prev().hide();
	};			
});


$(document).ready(function() {
    if( navigator.plugins["Shockwave Flash"] ){
        return false;
    } else {
        $('.banner-block.choose-banner object').hide();
        $('.banner-block.choose-banner object').parent().parent().hide();
    }
});

$(document).ready(function(){
	$('.keybord_wrapper, .sb.kn').live('mousedown',function(){ 
		return false; 
	});
});

$(document).ready(function(){
	$('.holder').focus(function(){
		$(this).find('.sel>span').click();
	});
	$('.holder').blur(function(){
		$(this).find('.sel>span').click();
	});
});

$(document).ready(function(){	
	tooltipPosition('.tooltip-certificate');	
	$('.about a.certificate').on('mouseover',function(){			
		$('.tooltip-certificate').fadeIn();				
	});			
	$('.about a.certificate').on('mouseout',function(){
		$('.tooltip-certificate').fadeOut();
	});	
});

function tooltipPosition(id) {
	var top = (($(window).height()) - $(id).outerHeight()) / 2  + "px";
	$(id).css('top', top);
	var left = (($(window).width()) - $(id).outerWidth()) / 2 + $(window).scrollLeft() + "px";
	$(id).css('left', left);	
};

$(document).ready(function(){
	if($('.situations .item').length){
		$('.situations .item').each(function(){
			var rows = $(this).find('.table').height();
			console.log(rows);
			$(this).find('.tr:first').find('.td:nth-child(1)').css('height',rows);
			$(this).find('.tr:first').find('.td:nth-child(2)').css('height',rows);
			$(this).find('.tr:first').find('.td:nth-child(3)').css('height',rows);
		});
	}
	$('.submenu ul li span').click(function(){
		console.log('FUCK!');
		if($(this).parent().hasClass('active')){
			
			return false;
		}else{
			var cl = $(this).attr('class');
			console.log(cl);
			$('.submenu ul li').removeClass('active');
			$(this).parent().addClass('active');
			$('.situations > div').fadeOut(400);
			setTimeout(function(){
				$('.situations').find('#'+cl).fadeIn('fast');
			},300)
		}
		return false;
	});
});