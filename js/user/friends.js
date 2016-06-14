$(function() {

    $("#fill_navigation .tab_list_item").each(function() {
        if ($(this).hasClass('active')) {
            var tabName = $(this).data('page'),
                    el = '.' + tabName + '_tab';

            if ($(el).length != 0) {
                $(el).css('display', 'block');
            }
        }
    });

    $("#fill_navigation .tab_list_item").click(function() {
        var tabName = $(this).data('page'),
                el = '.' + tabName + '_tab';

        if ($(el).length != 0) {
            $("#fill_navigation .tab_list_item.active")
                    .removeClass('active');
            $("#fill_tab .tab.active")
                    .hide()
                    .removeClass('active');

            $(this).addClass('active');
            $(el).addClass('active').css('display', 'block');
        }
    });

    var obj = $('#user_social_friends_popup');
    var htmlObj = obj.clone();

    $('html').append(htmlObj);
    obj.remove();
    obj = null;

    $('#social_friends a.invite_friend').click(function() {

        var obj = $('#user_social_friends_popup'),
            cBox = centerBox(obj.width()),
            $this = $(this),
            fio = $this.parent().parent().find('.friend_fio').html();

        obj.find('.friend_fio').html(fio);

        var url = $(this).data( 'url'),
            social = $(this).data( 'social' ),
            uid = $(this).data( 'uid' );

        obj.data( 'url' , url);
        obj.data( 'social' , social);
        obj.data( 'uid' , uid);
        $('#user_social_friends_popup .loader').show();
        $('#user_social_friends_popup .hybrid_send_message').hide();
        $('#user_social_friends_popup .user_message').hide();

        obj.css({'width': cBox.boxWidth + 'px', 'left': cBox.disWidth + 'px', 'top': cBox.disHeight + 'px'}).show();

        $.post(url, {action: 'auth',social_network:social, social_account_id: uid})
            .done(function(data) {
                var res = $.parseJSON(data);

                obj.find(".loader").hide();

                if (res.error == 1) {
                    window.location = site_url + '/account/friends/'+social;
                } else {
                    obj.find(".hybrid_send_message").show();
                }
            });

        return false;
    });

    $('#user_social_friends_popup .close').click(function() {
        $('#user_social_friends_popup').hide();
    });

    $('#user_social_friends_popup .send_mess').click(function() {
        var obj = $('#user_social_friends_popup');
        var url = obj.data('url'),
            social = obj.data('social'),
            uid = obj.data('uid');

        $('#user_social_friends_popup .hybrid_send_message').hide();
        $('#user_social_friends_popup .error_auth').hide();
        $('#user_social_friends_popup .loader').show();

        $.post(url, {action: 'send_message',social_network:social, social_account_id: uid}).done(function(data) {
                var res = $.parseJSON(data);

                $('#user_social_friends_popup .loader').hide();

                $('#user_social_friends_popup .user_message').html(res.message).show();
            });
    });
	
    function centerBox(boxWidth) {
//      var boxWidth = 400;
        /* определяем нужные данные */
        var winWidth = $(window).width();
        var winHeight = $(document).height();
        var scrollPos = $(window).scrollTop();

        /* Вычисляем позицию */

        var disWidth = (winWidth - boxWidth) / 2
        var disHeight = scrollPos + 250;

        /* Добавляем стили к блокам */
//    console.log({'width' : boxWidth+'px', 'left' : disWidth+'px', 'top' : disHeight+'px'});
//    console.log({'width' : winWidth+'px', 'height' : winHeight+'px'});
//    $('.popup-box').css({'width' : boxWidth+'px', 'left' : disWidth+'px', 'top' : disHeight+'px'});
//    $('#blackout').css({'width' : winWidth+'px', 'height' : winHeight+'px'});

        return {
            'boxWidth': boxWidth,
            'disWidth': disWidth,
            'disHeight': disHeight,
            'winWidth': winWidth,
            'winHeight': winHeight
        };
//    return false;       
    }
});




