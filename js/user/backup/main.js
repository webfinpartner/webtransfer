$(document).ready(function(){
    Cufon.replace('h3, .left_menu li a ,.num, .advice span, .phones span, #tabs li a, .button a, .button , .login span, #main_menu ul li a,.review h3, .banner .title,.news li .title, .adv_title, .contacts .phone ');
    $('select').selectBox();
    //Слайдеры
	$("#priceSlider").slider(
	{
		min:0,
		max:50,
		value:7,
		create: function(event, ui)
		{
			//$('#slider_value span').text(ui.value);
			$('#slider_value').css('left', $('#priceSlider a').css('left'));
			$('#slider_value').css('margin-left', -$('#slider_value').outerWidth()/2);
			
		},
		start: function(event, ui)
		{
			$('#slider_value').fadeIn(200);
			
		},
		stop: function(event, ui)
		{
			$('#slider_value').fadeOut(200);
		},
		slide: function(event, ui)
		{
			$('#slider_value span').text(ui.value+' т.р.');
            $('.summ').text(ui.value+' 000 руб.');
            change();
			setTimeout(function(){
				$('#slider_value').css('left', $('#priceSlider a').css('left'));
				$('#slider_value').css('margin-left', -$('#slider_value').outerWidth()/2);
			},1);	
		}
	});
    $("#timeSlider").slider(
	{
		min:0,
		max:24,
		value:7,
		create: function(event, ui)
		{
			//$('#slider_value span').text(ui.value);
			$('#slider_value2').css('left', $('#timeSlider a').css('left'));
			$('#slider_value2').css('margin-left', -$('#slider_value2').outerWidth()/2);
			

		},
		start: function(event, ui)
		{
			$('#slider_value2').fadeIn(200);
		},
		stop: function(event, ui)
		{
			$('#slider_value2').fadeOut(200);
		},
		slide: function(event, ui)
		{
			$('#slider_value2 span').text(ui.value+' недель');
            $('.time').text(ui.value+' недель');
             change();
			setTimeout(function(){
				$('#slider_value2').css('left', $('#timeSlider a').css('left'));
				$('#slider_value2').css('margin-left', -$('#slider_value2').outerWidth()/2);
			},1);	
		}
	});
  $(function()
	{				
		$('#tabs li').click(function()
		{
			$('.tab_content').addClass('hidden');
			$($(this).find('a').attr('href')).removeClass('hidden');
			
			$('#tabs li').removeClass('active');
			$(this).addClass('active');
			
			return false;
		});
	});
    $('.login a').click(function(){
  
        $("#login").fadeIn();
    })
    $('.phones a').click(function(){
  
        $("#callback").fadeIn();
    })
    $('.close').click(function(){
        $(".popup").fadeOut();
    })
})




function change(){
 time=$('.time').text().replace(/ недель/gi ,"");
 summ=parseInt($('.summ').text().replace(/ руб./gi ,""));
 percent=$('.result').attr('name');

out_per= percent*time;
 per= percent/100*time;
out_sum= summ +  summ*per;

$("#your_sum").text(out_sum.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')+" руб.")
$("#your_per").text(out_per+"%")
 
}

$(function()
{
$('#creditor li').click(function(){
$('#creditor li').removeClass('active');
$(this).addClass('active');
$('.result').attr('name',$(this).attr('name'));
change();
})

})

