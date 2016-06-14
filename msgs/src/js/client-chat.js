var myuserId = getCookie('user_id');
var pos_caret;
var socket = io.connect('https://msgs.webtransfer.com:3001?token=' + getCookie('chat_token') + '&userID=' + getCookie('user_id')),
    lastKeyup = 0,
    openChat_ls = null,
    dialog = [];
var dostup = 1;
var created_at = '';
var ValueForm;
var WriteTimer = setTimeout(function () {
    document.getElementById('WriteStatus').innerHTML = '';
}, 5000);
var ChatModal = $("#nwt-chat-im-modal-layerm"),
    FriendsModal = $("#nwt-chat-im-modal-layerm_1"),
    ListChat = $(".wt-chat-im-users-area"),
    DraggableZone = $("#DraggableZone"),
    GeneralChat = {
        block: $('#PostsGeneralChat'),
        open: function () {
            localStorage.setItem("o_chat", 1);
            GeneralChat.block.show('');
            $("#blockPostsGeneralChat").mCustomScrollbar("scrollTo", 'bottom');
            ChatModal.css('z-index', 99998);
            GeneralChat.block.css('z-index', 99999);
        },
        close: function () {
            localStorage.setItem("o_chat", 0);
            GeneralChat.block.hide('');
        },
        sendMessage: function (message) {
            socket.emit('generalNewMessage', message);
            tinyMCE.activeEditor.setContent('');
            localStorage.setItem("generalText", '');
        },
        addMessage: function (el) {
            var posClass = 'message_general_l';
            var aliginblock = 'left';
            var rightal = '<div class="time_l">' + el.time + '</div>';
            var leftal = '<img src="' + el.avatar_path + '" class="avatar_chat_global ' + aliginblock + ' radius_50">';
            var cltype = 'data-id="' + el.webtransfer_id + '" data-action="OpenDialog_is_general" class="msg_avtor"';
            if (el.webtransfer_id == myuserId) {
                posClass = 'message_general_r';
                aliginblock = 'right';
                cltype = '';
                rightal = '<img src="' + el.avatar_path + '" class="avatar_chat_global ' + aliginblock + ' radius_50">'; // avatar
                leftal = '<div class="time_l">' + el.time + '</div>'; // time
            }
            var useinit = '<strong ' + cltype + '>' + el.first_name + ' ' + el.last_name + ' (' + el.webtransfer_id + ')</strong>';
            var message = el.message;
            var result_message = '<div class="' + posClass + '">' + useinit + '<br> ' + message + '</div>';
            var msgCont = $('#mCSB_2_container');
            if (msgCont.children().length > 90) {
                msgCont.children().first().remove()
            }
            msgCont.append('<div class="wt-chat-im-area-post" style="text-align: ' + aliginblock + '">' + leftal + result_message + rightal + '</div>');
            $("#blockPostsGeneralChat").mCustomScrollbar("scrollTo", 'bottom');
        },
        onload: function () {
            var blockLoader = $('#mCSB_2_container center');
            blockLoader.hide('', blockLoader.remove);

        }
    };
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function sendMassage(to_id, text) {
    socket.emit('sendMassage', {to_id: to_id, text: text});
}
function openDialog_2(id) {

    openChat_ls = null;
    addCount(id, false);
    $('#nwt-chat-im-modal-layerm_1').slideUp('');
    openDialog(id);
}
function startWriter(open_chat) {
    if (dostup == 1) {
        setTimeout(function () {
            dostup = 1;
            socket.emit('partnerWriter', {to_id: open_chat});
        }, 1000);
        dostup = 0;
        socket.emit('partnerWriter', {to_id: open_chat});
    }
}
function openDialog(id) {
    GeneralChat.block.css('z-index', 99998);
    ChatModal.css('z-index', 99999);
    $('#textArenaMCE')[0].innerHTML = '<div class="mceText"></div>';
    var lastChatID = localStorage.getItem("lastChatID");
    tinymce.init({
        selector: '.mceText',
        inline: true,
        toolbar: false,
        plugins: "paste",
        paste_as_text: true,
        menubar: false,
        object_resizing: false,
        forced_root_block: false,
        force_br_newlines: true,
        force_p_newlines: false,
        convert_newlines_to_brs: false,
        setup: function (editor) {
            editor.on('init', function () {
                $('.mceText').blur(function () {
                    pos_caret = save_pos_caret();
                });
            });
            editor.on('keyup', function (e) {
                var thisValue = tinyMCE.activeEditor.getContent();
                localStorage.setItem("lastChatID", id);
                localStorage.setItem("lastText", thisValue);
                startWriter(openChat_ls)
            });
            editor.on('keydown', function (e) {
                var thisValue = '';
                if (e.shiftKey && e.keyCode == 13 || e.ctrlKey && e.keyCode == 13) {
                    thisValue = tinyMCE.activeEditor.getContent();
                    tinyMCE.activeEditor.setContent(thisValue + '<br>');

                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    thisValue = tinyMCE.activeEditor.getContent();
                    if (thisValue.length >= 2) {
                        sendMassage(openChat_ls, thisValue);
                        tinyMCE.activeEditor.setContent('');
                    }
                }
            });
        }
    });
    setTimeout(function () {
        console.log(lastChatID, id);
        if (lastChatID == id) {
            var lastText = localStorage.getItem("lastText");
            tinyMCE.activeEditor.setContent(lastText);
        }
    }, 100);

    $('#nwt-chat-im-modal-layerm').slideUp('', function () {
        localStorage.setItem("openDialog", id);
        document.getElementById('logo_Chat_user').src = 'https://webtransfer.com/assets/missing_avatar_thumb.jpeg';
        document.getElementById('wt-chat-im-name').innerText = 'Загрузка...';
        if (openChat_ls != id) {
            openChat_ls = id;
            socket.emit('getMessage', id);
            $("#mCSB_1_container")[0].innerHTML = '<center><i class="fa fa-refresh fa-5x fa-spin" id="preloader_dialog"></i></center>';
            $('#nwt-chat-im-modal-layerm').slideDown('');
        } else {
            openChat_ls = null;
        }
    });
}
function auth(id) {
    document.cookie = "user_id=" + id + "; path=/;";
    socket.emit('auth', {key: id});
}
function addMessage(item) {

    var sp_Date = item.created_at.split(' ');
    var Date = sp_Date[0].split('-');
    var Time = sp_Date[1].split(':');
    var created_message = Date[2] + '.' + Date[1] + '.' + Date[0];
    if (created_at != created_message) {
        created_at = created_message;
        $("#mCSB_1_container").append('<div class="dateBlock"><span class="dateText">' + created_message + '</span></div>');
    }

    var content = '<div class="wt-chat-im-area-post" data-id="' + item.id + '" style="text-align: left;">' +
        '<div class="message_l">' +
        item.message_body +
        '</div>' +
        '<div class="time_l">' +
        Time[0] + ':' + Time[1] +
        '</div>' +
        '</div>';
    if (item.webtransfer_id != openChat_ls)
        content = '<div class="wt-chat-im-area-post" data-id="' + item.id + '" style="text-align: right;">' +
            '<div class="time_r">' +
            Time[0] + ':' + Time[1] +
            '</div>' +
            '<div class="message_r">' +
            item.message_body +
            '</div>' +

            '</div>';

    $("#mCSB_1_container").append(content);
}
function sortId(sortA, sortB) {
    return sortA.message_id - sortB.message_id;
}
function count__add(lable) {
    lable[0].dataset.count++;
    if (lable[0].dataset.count < 100) {
        lable[0].innerHTML++;
    } else {
        lable[0].innerHTML = '+99'
    }
}
function addCount(id, data) {
    var block = $('[data-action="OpenDialog"][data-id="' + id + '"]');
    if (block.length != 0) {
        var lable = $('[data-type="label"][data-id="' + id + '"]');

        if (lable.css('display') == "none") {
            count__add(lable);
            lable.show('');
        } else {
            lable.animate({top: "15px"}, 100, function () {
                count__add(lable);
                lable.animate({top: "36px"}, 100, function () {
                    lable.animate({top: "30px"}, 100);
                });
            });
        }
    } else {
        //if (data)
        //    dialog[id] = data;
        //if (dialog[id].count === undefined)
        //    dialog[id].count = 1;
        //var countMessage = dialog[id].count;
        //if (countMessage > 99)
        //    countMessage = '+99';
        //document.getElementById('listDialog').innerHTML = '<div simple-hint="' + dialog[id].full_name + '" class="hint-left-t-notice hint-fade right-arrow" data-id="' + id + '" data-action="OpenDialog" style="display: none"><img src="' + dialog[id].avatar + '" height="38" width="37" class="radius_50"><div class="wt-chat-im-status"></div><span class="wt-chat-im-status2" style="display: none" data-type="label" data-count="' + countMessage + '" data-id="' + id + '">' + countMessage + '</span></div>' +
        //    document.getElementById('listDialog').innerHTML;
        //
        //setTimeout(function () {
        //    if (countMessage > 0)
        //        $('[data-type="label"][data-count!="0"]').show('');
        //    $('[data-action="OpenDialog"]').show('');
        //    var listDialog = $('[data-action="OpenDialog"]');
        //    if (listDialog.length > 6) {
        //        var last = listDialog.last();
        //        last.hide('', function () {
        //            last.remove()
        //        });
        //    }
        //}, 300);

    }
}
function openPopDialog(e) {
    var tUser;
    if (e.target.dataset.action == 'OpenDialog') {
        tUser = e.target.dataset.id;
    } else if (e.target.parentNode.dataset.action == 'OpenDialog') {
        tUser = e.target.parentNode.dataset.id;
    }
    if (tUser !== undefined) {

        openDialog(tUser);

    }
}
function statusFriend(status) {
    if (!status) {
        return '<span class="item_user-status"></span>';
    } else {
        return '<div class="wt-chat-im-status-left"></div><span class="item_user-status">online</span>';
    }
}
function addFriends(param) {
    dialog[param.webtransfer_id] = {
        webtransfer_id: param.webtransfer_id,
        avatar: param.avatar_path,
        count: 0,
        full_name: param.full_name,
        message_id: 0
    };
    var content = '<div class="item_user" onclick="openDialog_2(' + param.webtransfer_id + ');">' +
        '<img src="' + param.avatar_path + '" height="40" width="40" class="item_user-img"> ' +
        statusFriend(param.online) +
        '<i class="fa fa-commenting-o"></i>' +
        '<div class="item_user-name">' + param.full_name + '</div>' +
        '</div>';
    $("#mCSB_3_container").append(content);
}


function testBlock() {
    ListChat.css({left: "auto"});
    var posY = ListChat[0].getBoundingClientRect() ['top'];
    var bottom = (DraggableZone.height() - (posY + 30 + ListChat.height()));
    if (bottom < 0 && posY > 0) {
        console.log('bottom');

        ListChat.animate({bottom: '0'}, 400, function () {
            ListChat.css('top', '');
        });
    } else if (posY < 0) {
        console.log('top');
        ListChat.animate({top: '0'}, 400, function () {
            ListChat.css('bottom', '');
        });
    }
}
function save_pos_caret() {
    if (window.getSelection)
        var sel = window.getSelection();
    if (sel.getRangeAt && sel.rangeCount)
        return sel.getRangeAt(0);

    else if (document.selection && document.selection.createRange)
        return document.selection.createRange();
}
socket.on('clear_global', function () {
    var msgCont = $('#mCSB_2_container');
    var clearing = msgCont.children();
    clearing.slideUp('', function () {
        clearing.remove()
    });
});
socket.on('partnerWriter', function (data) {
    var writeAnimation = '<div id="circleG"><div id="circleG_1" class="circleG"></div><div id="circleG_2" class="circleG"></div> <div id="circleG_3" class="circleG"></div></div>';
    document.getElementById('WriteStatus').innerHTML = data.to + ' пишет вам ' + writeAnimation + ' <i class="fa fa-pencil"></i>';
    clearTimeout(WriteTimer);
    WriteTimer = setTimeout(function () {
        document.getElementById('WriteStatus').innerHTML = '';
    }, 10000);
});
socket.on('ArrayMessage', function (result) {
    document.getElementById('logo_Chat_user').src = result.avatar_path;
    document.getElementById('wt-chat-im-name').innerText = result.first_name + ' ' + result.last_name;
    if (result.online == true)
        document.getElementsByClassName('wt-chat-im-status')[0].className = 'wt-chat-im-status';
    else
        document.getElementsByClassName('wt-chat-im-status')[0].className = 'wt-chat-im-status no-active-status';

    created_at = '';
    $('#preloader_dialog').slideUp('', function () {
        $('#preloader_dialog').remove();
        for (var i = (result.messages).length - 1; i >= 0; i--) {
            addMessage(result.messages[i]);
        }


        var lable = $('[data-type="label"][data-id="' + openChat_ls + '"]');
        if (lable[0] !== undefined) {
            lable[0].dataset.count = 0;
            if (lable.css('display') != "none") {
                lable.hide('', function () {
                    lable[0].innerHTML = 0;
                });
            }
        }
        setTimeout(function () {
            $("#mess").mCustomScrollbar("scrollTo", 'bottom');
        }, 300);
    });
});
socket.on('pushMessage', function (data, type) {

    if (type == '1') {
        var audio = new Audio();
        audio.src = '/msgs/src/song/newMessage.mp3';
        audio.autoplay = true;
        clearTimeout(WriteTimer);
        document.getElementById('WriteStatus').innerHTML = '';
        if (data.webtransfer_id == openChat_ls)
            addMessage(data);
        else
            addCount(data.webtransfer_id, data);


    } else {
        if (data.recipient_id == openChat_ls)
            addMessage(data);

    }
    $("#mess").mCustomScrollbar("scrollTo", 'bottom');
});
socket.on('unreadDialog', function (data) {
    dialog = data.User.unreadDialog;
//    console.log(data.User);

    document.getElementById('newDialog_count').innerText = ''; // количество   онлайн  друзей


    dialog.forEach(function (item) {
        var listDialog = $('[data-action="OpenDialog"]');
        if (listDialog.length >= 5) {
            listDialog.last().slideUp('', function () {
                listDialog.last().remove()
            });
        }
        var countMessage = item.unread_count;
        if (countMessage > 99)
            countMessage = '+99';
        document.getElementById('listDialog').innerHTML = '<div simple-hint="' + item.full_name + '" class="hint-left-t-notice hint-fade right-arrow" data-id="' + item.webtransfer_id + '" data-action="OpenDialog" style="display: none">' +
            '<img src="' + item.avatar_path + '" height="38" width="37" class="radius_50">' +
                //'<div class="wt-chat-im-status"></div>' +
            '<span class="wt-chat-im-status2" style="display: none" data-type="label" data-count="' + item.unread_count + '" data-id="' + item.webtransfer_id + '">' + countMessage + '</span>' +
            '</div>' + document.getElementById('listDialog').innerHTML;
        if (countMessage > 0)
            setTimeout(function () {
                $('[data-type="label"][data-count!="0"]').show('');
            }, 1000);
    });

    var loadDialoglist = $('.loadDialoglist');
    if (loadDialoglist.css('display') != "none") {
        loadDialoglist.hide('');
    }
    setTimeout(function () {
        $('[data-action="OpenDialog"]').show('');
    }, 300);
    var listDialog = $('[data-action="OpenDialog"]');
    if (listDialog.length > 5) {
        var delres = listDialog.length - 5;
        listDialog.last().slideUp('', function () {
            listDialog.last().remove()
        });
    }
});
socket.on('lastChatMessage', function (data) {
    var selector = $("#mCSB_2_container center");
    selector.slideUp('', function () {
        selector.remove();
    });
    data.forEach(function (el) {
        GeneralChat.addMessage(el);
    });
});
socket.on('YouFriendsIndex', function (data) {
    $("#mCSB_3_container")[0].innerHTML = '';
    (data.friends).forEach(function (el) {
        addFriends(el)
    });
    document.getElementById('CountFr').innerText = data.friends_count;
});
socket.on('newMsg', function (msg) {
    GeneralChat.addMessage(msg);
    var status_open_general_chat = localStorage.getItem("o_chat");
    if (status_open_general_chat != 1) {
        var o_chat = $('.o_chat');
        o_chat.animate({top: '-10px'}, 200, function () {
            o_chat.animate({top: '6px'}, 120, function () {
                o_chat.animate({top: '0'}, 200);
            });
        });
    }
});

socket.on('allMsg', function (allmsg) {
    GeneralChat.onload();
    allmsg.forEach(function (item) {
        GeneralChat.addMessage(item);
    });
});

$(window).load(function () {

    var ListChatPos = JSON.parse(localStorage.getItem("ListChat"));
    ListChat.animate(ListChatPos, 400, testBlock);
    ListChat.css('height', 'auto');
    ListChat.css('display', 'block');
    ChatModal.mousedown(function () {
        GeneralChat.block.css('z-index', 99998);
        ChatModal.css('z-index', 99999);
    });

    GeneralChat.block.mousedown(function () {
        ChatModal.css('z-index', 99998);
        GeneralChat.block.css('z-index', 99999);
    });
    $(window).resize(testBlock);
    ChatModal.draggable({
        handle: ".wt-chat-im-header",
        cursor: "move",
        containment: '#DraggableZone',
        stop: function () {
            var pos = ChatModal[0].getBoundingClientRect();
            if (pos.top < 0) {
                pos.top = 0;
                ChatModal.animate({top: 0}, 400);
            }
            localStorage.setItem("ChatModal", '{"top":' + pos.top + ',"left":' + pos.left + '}');
        }
    });
    FriendsModal.draggable({
        handle: ".wt-chat-im-header",
        cursor: "move",
        containment: '#DraggableZone',
        stop: function () {
            var pos = FriendsModal[0].getBoundingClientRect();
            if (pos.top < 0) {
                pos.top = 0;
                FriendsModal.animate({top: 0}, 400);
                localStorage.setItem("FriendsModal", '{"top":0,"left":' + pos.left + '}');
            } else
                localStorage.setItem("FriendsModal", '{"top":' + pos.top + ',"left":' + pos.left + '}');
        }
    });
    GeneralChat.block.draggable({
        handle: ".wt-chat-o-header",
        cursor: "move",
        containment: '#DraggableZone',
        stop: function () {
            var pos = GeneralChat.block[0].getBoundingClientRect();
            if (pos.top <= 0) {
                GeneralChat.block.animate({top: 0}, 400);
                localStorage.setItem("GeneralChatPosition", '{"top":0,"left":' + pos.left + '}');
            } else
                localStorage.setItem("GeneralChatPosition", '{"top":' + pos.top + ',"left":' + pos.left + '}');
            console.log(pos);
        }
    });
    ListChat.draggable({
        axis: "y",
        cursor: "move",
        containment: '#DraggableZone',
        start: function () {
            ListChat.css({bottom: 'auto'});
        },
        stop: function () {
            var pos = ListChat[0].getBoundingClientRect();
            console.log(pos);
            if (pos.top <= 0) {
                console.log('pos.top <= 0');
                ListChat.animate({top: 0}, 400);
                localStorage.setItem("ListChat", '{"top":0}');
            } else {
                console.log('else (pos.top <= 0)');
                var top = pos.top;
                var bottom = DraggableZone.height() - pos.bottom;
                if (top >= bottom) {
                    ListChat.css({bottom: bottom, top: 'auto'}, 400);
                    localStorage.setItem("ListChat", '{"bottom":' + bottom + '}');
                } else {
                    localStorage.setItem("ListChat", '{"top":' + top + '}');
                }
            }
        }
    });


    var openDialogLocalSaves = localStorage.getItem("openDialog");
    if (openDialogLocalSaves != null)
        openDialog(openDialogLocalSaves);
    var ChatModalPos = JSON.parse(localStorage.getItem("ChatModal"));
    ChatModal.animate(ChatModalPos, 400);
    var FriendsModalPos = JSON.parse(localStorage.getItem("FriendsModal"));
    FriendsModal.animate(FriendsModalPos, 400);
    var GeneralChatPos = JSON.parse(localStorage.getItem("GeneralChatPosition"));
    GeneralChat.block.animate(GeneralChatPos, 400);
    $('#wt-chat-im-modal-shown').click(function () {
        $('#wt-chat-im-modal-layern').show('');
    });
    $('#wt-chat-im-modal-shown2').click(function () {
        $('#wt-chat-im-modal-layern').hide('');
    });
    $('#wt-chat-im-modal-show').click(function () {
        $('#wt-chat-im-modal-layer').show('');
    });
    $('#wt-chat-im-modal-show2').click(function () {
        $('#wt-chat-im-modal-layer').hide('');
    });
    $('.wt-chat-im-modal-showm2').click(function () {
        // свернуть  текуший  чат
        openChat_ls = null;
        localStorage.removeItem("openDialog");
        $('#nwt-chat-im-modal-layerm').slideUp('');
    });
    $('.closeGeneralChat_btn').click(GeneralChat.close);


    $('.o_chat').click(function () {
        // Открыть закрыть  обший  чат

        if (GeneralChat.block.css('display') == "none") {
            GeneralChat.open();
        } else {
            GeneralChat.close();
        }
    });
    $('.wt-chat-im-modal-showm3').click(function () {

        // Удалить  текуший  чат
        ListChat.css('height', 'auto');
        localStorage.removeItem("openDialog");
        $('#nwt-chat-im-modal-layerm').slideUp('', function () {
            $('[data-action="OpenDialog"][data-id="' + openChat_ls + '"]').hide('');
            document.getElementById('logo_Chat_user').src = 'https://webtransfer.com/assets/missing_avatar_thumb.jpeg';
            document.getElementById('wt-chat-im-name').innerText = 'Загрузка...';
            openChat_ls = null;
        });
    });
    $('div#listDialog').click(function (event) {
        var popwin = $('#nwt-chat-im-modal-layerm_1');
        if (popwin.css('display') != "none") {
            popwin.slideUp('', function () {
                openChat_ls = null;
                openPopDialog(event)
            });
        } else {
            openPopDialog(event)
        }
    });
    $('#mCSB_2_container').click(function (event) {
        if (event.target.dataset.action == 'OpenDialog_is_general') {
            var popwin = $('#nwt-chat-im-modal-layerm_1');
            if (popwin.css('display') != "none") {
                popwin.slideUp('', function () {
                    openChat_ls = null;
                    openDialog(event.target.dataset.id);
                });
            } else
                openDialog(event.target.dataset.id);
        }
    });


    $('.wt-chat-im-smile1').click(function (event) {

        if (window.getSelection) {
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(pos_caret);
        } else if (document.selection && range.select)
            range.select();
        tinymce.activeEditor.execCommand('mceInsertContent', false, event.target.outerHTML);

        $('#wt-chat-im-modal-layern').slideUp('');

    });
    $('[data-action="OpenListUser"]').click(function () {
        var popwin = $('#nwt-chat-im-modal-layerm_1');
        if (popwin.css('display') == "none") {
            var popDialog = $('#nwt-chat-im-modal-layerm');
            if (popDialog.css('display') != "none") {
                openChat_ls = null;
                popDialog.slideUp('', function () {
                    document.getElementById('logo_Chat_user').src = 'https://webtransfer.com/assets/missing_avatar_thumb.jpeg';
                    document.getElementById('wt-chat-im-name').innerText = 'Загрузка...';
                    popwin.show('');
                    socket.emit('FriendsIndex');
                });
            } else {
                popwin.show('');
                socket.emit('FriendsIndex');
            }
        } else {
            popwin.slideUp('');
            openChat_ls = null;
        }
    });
    $('.wt-chat-im-modal-showm3_1').click(function () {
        // свернуть список  друзей
        $('#nwt-chat-im-modal-layerm_1').hide('');
    });
    $("a[rel='load-content']").click(function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        $.get(url, function (data) {
            $(".contentmsg .mCSB_container").append(data); //load new contentmsg inside .mCSB_container
            //scroll-to appended content
            $(".contentmsg").mCustomScrollbar("scrollTo", "h2:last");
        });
    });
    $(".content").delegate("a[href='top']", "click", function (e) {
        e.preventDefault();
        $(".content").mCustomScrollbar("scrollTo", $(this).attr("href"));
    });

    tinymce.init({
        selector: '.edText',
        inline: true,
        toolbar: false,
        plugins: "paste",
        paste_as_text: true,
        menubar: false,
        object_resizing: false,
        forced_root_block: false,
        force_br_newlines: true,
        force_p_newlines: false,
        convert_newlines_to_brs: false,
        setup: function (editor) {
            editor.on('init', function () {
                if (localStorage.getItem("generalText") != null)
                    tinyMCE.activeEditor.setContent(localStorage.getItem("generalText"));
            });
            editor.on('keyup', function (e) {
                var thisValue = tinyMCE.activeEditor.getContent();
                localStorage.setItem("generalText", thisValue);

            });
            editor.on('keydown', function (e) {
                var thisValue = '';
                if (e.shiftKey && e.keyCode == 13 || e.ctrlKey && e.keyCode == 13) {
                    thisValue = tinyMCE.activeEditor.getContent();
                    tinyMCE.activeEditor.setContent(thisValue + '<br>');

                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    thisValue = tinyMCE.activeEditor.getContent();
                    GeneralChat.sendMessage(thisValue);
                }
            });
        }
    });


    if (myuserId === undefined) {
        $('.wt-chat-im-users-area').hide('');
    } else {
        if (localStorage.getItem("o_chat") == 1)
            GeneralChat.open();
    }
    setTimeout(function () {
        socket.emit('getMessageGeneral');
    }, 300);


    $('.btnsend_o').on('click', function () {
        var thisValue = tinyMCE.activeEditor.getContent();
        GeneralChat.sendMessage(thisValue);
    });

});