$(function() {

    //===== Tabs =====//

    $.fn.contentTabs = function(){

            $(this).find(".tab_content").hide(); //Hide all content
            $(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
            $(this).find(".tab_content:first").show(); //Show first tab content

            $("ul.tabs li").click(function() {
                    $(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
                    $(this).addClass("activeTab"); //Add "active" class to selected tab
                    $(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
                    var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
                    $(activeTab).show(); //Fade in the active content
                    return false;
            });

    };
    $("div[class^='widget']").contentTabs(); //Run function on any div with class name of "Content Tabs"



    //header narrow
    $(document).scroll(function() {
        var window_top = $(window).scrollTop(),
            header_h = 50;

        if (window_top > header_h){
            $('body').addClass('narrow');
        }

        if (window_top == 0){
            $('#header').animate({height:100},100,function(){
                $(this).css('height','');
                $('body').removeClass('narrow');
            });
        }

    });
    $(document).scroll();
    //float fb side
    if ($('#frame-fb').lenght != 0) {

        $.fn.scrollingWidget = function() {
            var window_top = $(window).scrollTop(),
                widget = new Object(),
                right_side = new Object(),
                work = true;


            function setup() {
                widget.obj = $('#frame-fb');
                widget.h = widget.obj.height();

                right_side.obj = $('#right-side-fb');
                right_side.top = right_side.obj.offset().top;
                right_side.h = right_side.obj.height();

                if (right_side.h <= widget.h)
                    work = false;
            }
            function setEvents() {
                if (work)
                    $(document).scroll(function() {

                        if (!work)
                            return;

                        right_side.h = right_side.obj.height();
                        if (right_side.h <= widget.h)
                            work = false;

                        window_top = $(window).scrollTop();
                        //console.log( '--'+$('#right-side-fb').offset().top );
                        if (window_top >= right_side.top)
                            widget.obj.addClass('top-fix');

                        if (widget.obj.hasClass('bottom') && window_top >= window_top)
                            widget.obj.removeClass('bottom').addClass('top-fix');

                        if (window_top < right_side.top)
                            widget.obj.removeClass('top-fix');

                        if (window_top + widget.h >= right_side.top + right_side.h + 45)
                            widget.obj.addClass('bottom');
                    });
                $(document).scroll();
            }
            function constructor() {
                setup();
                setEvents();

            }
            constructor();
        };

        //$('#frame-fb').scrollingWidget();
    }

    $("#recovery_password").validationEngine('attach', {scroll: false});
    $("#validate").validationEngine('attach', {
        onValidationComplete: function(form, status) {
            if (status == true) {
                $("#validate").ajaxSubmit({
                    url: site_url + "/ask/telephone",
                    success: function() {
                        $("div.advice").show()
                    }
                });
                setTimeout(function() {
                    $(".popup").fadeOut()
                }, 5000);
            }
        }, scroll: false
    });


    $("#password_change").validationEngine('attach', {
        onValidationComplete: function(form, status) {
            if (status == true) {
                $("#password_change").ajaxSubmit({
                    url: site_url + "/account/password",
                    type: 'post',
                    dataType: "json",
                    success: function(data) {
                        if (data.error != '' && data.password != '')
                        {
                            if (data.error == 'no' && data.password == 'yes')
                            {
                                $(".password_change").html(_e('Пароль успешно изменен.'));
                                setTimeout(function() {
                                    $(".password_change").fadeOut()
                                }, 3000);
                            }
                            else if (data.error == 'no' && data.password == 'no')
                            {
                                $('.password_return').html(_e('Старый пароль не совпадает с текущим'));
                            }
                            else if (data.error != 'no')
                                $('.password_return').html(data.error);
                        }
                    }
                });

            }
        }, scroll: false
    });

    $("#form_login").validationEngine('attach', {
        onValidationComplete: function(form, status) {
            if (status == true) {
                $("#form_login").ajaxSubmit({
                    url: site_url + "/login",
                    success: function(data) {
                        data = $.trim(data)
                        if (data == 'block')
                            $("#login_answer").text(_e('Ваш  аккаунт  заблокирован'));
                        else if (data == 'yes')
                            window.location = site_url + '/account/';
                        else if (data == 'partner')
                            $("#login_answer").text(_e('Ваш  аккаунт на рассмотрении'));
                        else if (data == 'no')
                            $("#login_answer").text(_e('Логин или пароль ошибочны'));

                    }
                });

            }
        }, scroll: false
    });

    $("#request").validationEngine('attach', {
        onValidationComplete: function(form, status) {
            if (status == true) {
                $("#request").ajaxSubmit({
                    target: "#terget1",
                    url: site_url + "/ask/feedback"
                });

            }
        }, scroll: false
    });

    $(".form_login").validationEngine('attach', {
        onValidationComplete: function(form, status) {
            if (status == true) {
                $(".form_login").ajaxSubmit({
                    url: site_url + "/login",
                    success: function(data) {
                        data = $.trim(data)
                        if (data == 'block')
                            $(".login_answer").text(_e('Ваш аккаунт заблокирован'));
                        else if (data == 'yes')
                            window.location = site_url + '/account/';
                        else if (data == 'partner')
                            $(".login_answer").text(_e('Ваш аккаунт на рассмотрении'));
                        else if (data == 'no')
                            $(".login_answer").text(_e('Логин или пароль ошибочны'));
                    }
                });

            }
        }, scroll: false
    });


    $('#glav_kalc').click(function() {
        $.cookie('form_summ', $(".summ").text());
        $.cookie('form_time', $(".time").text());
    })


    if ($.cookie('login') != null || $.cookie('password') != null) {
        $('.popup#login').remove();
        $('.loginbut').html('<a href="'+ site_url +'/account" class="loginenter"><div style="" class="loginbutton13">Личный кабинет</div></a>');
        $('.popup#social_loginss').remove();
        $('.investnow').html('<a href="'+ site_url +'/account/my_invest">'+_e('Вложить деньги')+'</a>');
        $('.creditnow').html('<a href="'+ site_url +'/account/my_credits">'+_e('Получить деньги')+'</a>');
        $('.aboutnow').html('<a href="'+ site_url +'/account/my_invest">'+_e('Стать участником')+'</a>');
        $('#home_credit').html('<a href="'+ site_url +'/account/my_invest" style="font-size:22px;">'+_e('Вложить деньги')+'</a>');
        $('#home_credit2').html('<a href="'+ site_url +'/account/my_credits" style="font-size:22px;">'+_e('Получить деньги')+'</a>');
        $('.investnow').attr('onclick', '');
        $('.creditnow').attr('onclick', '');
        $('.aboutnow').attr('onclick', '');
        $('#home_credit').attr('onclick', '');
        $('#home_credit2').attr('onclick', '');
    }

    $('input[name="form_check"]').change(function() {

        val = $(this).val();
        if (val == 1)
            $('input[name=form_check].form_check1').attr('checked', 'checked');
        else
            $('input[name=form_check].form_check2').attr('checked', 'checked');

        if ($(this).val() == 2) {
            $('form#wizard2').fadeOut();
            $('form.form_login').fadeIn();
        } else {
            $('form#wizard2').fadeIn();
            $('form.form_login').fadeOut();
        }
    });



    $('input[name="face"]').change(function() {

        if ($(this).val() == 1) {

            $('.yur-no.move input').attr('name', $(this).attr('id'));
            $('.yur.move input').attr('name', '');
            $('.yur').hide();
            $('.yur-no').show();
            $('.yur_name').html(_e('Имя<span class="req">*</span>'));
            $('.yur_family').html(_e('Фамилия<span class="req">*</span>'));
            $('.yur_place').html(_e('Регион проживания<span class="req">*</span>'));
            $('.yur_header').html(_e('Место работы'));
        } else {
            $('.yur.move input').attr('name', $(this).attr('id'));
            $('.yur-no.move input').attr('name', '');
            $('.yur_name').html(_e('Имя Руководителя<span class="req">*</span>'));
            $('.yur_family').html(_e('Фамилия Руководителя<span class="req">*</span>'));
            $('.yur_place').html(_e('Регион регистрации<span class="req">*</span>'));
            $('.yur_header').html(_e('Детали организации'));
            $('.yur').show();
            $('.yur-no').hide();
        }


    })
    $('.yur-no.move input').attr('name', $(this).attr('id'))
    $('.yur.move input').attr('name', '')
    $('.yur').hide()
    $('.yur-no').show()



});

function yes_no_debit() {
    if (window.confirm(_e("Вы уверены что хотите отменить заявку?")))
        return true;
    else
        return false;
}

function yes_no_contract() {
    if (window.confirm(_e("Вы уверены что хотите вернуть заявку на рынок и расторгнуть пре сделку?")))
        return true;
    else
        return false;
}

function confirm_sertificate() {
    if (window.confirm(_e("Продав сертификат Вы получаете гарантированный минимальный доход \n и права по выданному кредиту переходят гарантийному фонду.\n\nВы уверены что хотите продать  кредитный  сертификат?")))
        return true;
    else
        return false;
}

function  login_info(data)
{

}




function postMsgWall(text) {
    VK.Auth.login(null, VK.access.FRIENDS, VK.access.WALL);
    VK.Observer.subscribe('auth.login', function(response) {
        if (response.session) {
            VK.Api.call('wall.post', {message: text}, function(r) {
            });
        } else {
            alert('not auth');
        }
    });
}
/*
$("html").on('click','.balancer' ,function(){
    $(this).text("Loading");
    get_balance();
});
*/


function get_card_balance(card_id, useCache, loader, target){

    $(loader).show();
     $.post(site_url +  "/account/ajax_user/get_card_balance", { card_id: card_id, useCache: useCache},  function(data){
            $(loader).hide();
            $(target).html(data.balance);
      }, "json");

}


    var createCookie = function(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }


/*
function get_balance(){
    $.post(site_url+"/account/ajax_user/get_card_balance", {card_id: card_id, useCache: useCache}, function(data){
        if(data.errors != undefined){
            $(".balancer").each(function(){
                $(this).text("0.00");
            });
        }else{
            $(".balancer").each(function(){
                $(this).text(data.answer);
            });
        }
    },"json");
}
*/
        function sendMessage(id){

            $('#sendMessage').show();
            $('#sendMessage_msg').data('to_user_id', id);

        }
        function sendMessagePost(){

            var to_user_id = $('#sendMessage_msg').data('to_user_id');
            var msg = $('#sendMessage_msg').val();
            if ( msg == ''){
                alert(_e("Нельзя отправить пустое сообщение"));
                return;
            }
            $.post(site_url + "/account/send_message",{msg: msg, to_user_id: to_user_id },function(data){

                    alert(data.message);

                }, "JSON"
            );

        }