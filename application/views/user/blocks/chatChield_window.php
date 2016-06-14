<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .chat_open{
        border: 6px solid transparent;
        border-top: 8px solid black;
        cursor: pointer;
        float: right;
        height: 0px;
        width: 1px;
        opacity: 0.5;
        margin-top: 4px;
        margin-bottom: -6px;
    }
    .chat_close{
        border-top: 0;
        border-bottom: 8px solid black;
    }

    .chet_title_close{
        border-radius: 3px;
        border: 1px solid #10798F;
    }

    #cometchat_userstab_popup_{
        margin-bottom: 10px;
    }

</style>
<script>
    function chat_change(){
        if('Отключен' == $("#cometchat_userstab_text").html()) return false;
        else {
            $('#chat_frends').toggle();
            $('.chat_open').toggleClass('chat_close');
            $('.cometchat_userstabtitle').toggleClass('chet_title_close');
        }
    }
</script>

<div id="cometchat_userstab_popup_" class="cometchat_tabpopup cometchat_tabopen" style="position: initial; width: 100%;">
    <div class="cometchat_userstabtitle chet_title_close" onclick="chat_change()">
        <span id="cometchat_userstab_icon" class="cometchat_user_available2"></span>
        <!--<span id="cometchat_userstab_text"></span>-->
        <div class="cometchat_userstabtitletext"><?=_e('blocks/chatChield_window_2')?> (<?=count($chat_frands)?>)</div>
        <div class="chat_open"></div>
        <br clear="all">
    </div>
    <div id="chat_frends" style="display: none">
        <div class="cometchat_tabsubtitle" id="cometchat_searchbar_" style="display: block;">
            <input type="text" name="cometchat_search" class="cometchat_search cometchat_search_light" id="cometchat_search" value="<?=_e('blocks/chatChield_window_3')?>">
        </div>
        <div class="cometchat_tabcontent" style="border-bottom: 1px solid #666666;border-bottom-left-radius: 3px;border-bottom-right-radius: 3px; background: inherit;">
        <div id="cometchat_userscontent" unselectable="on">
            <div id="cometchat_userslist_" style="display: block;">
                <div>
                <? if(0 == count($chat_frands)) echo "<center><?=_e('blocks/chatChield_window_4')?></center>";
                     foreach ($chat_frands as $row) {
                         $chat = (array)$row;
                        if ((((time()-$chat['lastactivity']) < 1200) || $chat['isdevice'] == 1)
                            && $chat['status'] != 'invisible' && $chat['status'] != 'offline')
                        {
                            if (($chat['status'] != 'busy' && $chat['status'] != 'away') || $chat['isdevice'] == 1) {
                                    $chat['status'] = 'available';
                            }
                        } else {
                                $chat['status'] = 'offline';
                        }
                        $row = (object)$chat;
                        echo "<div id=\"cometchat_userlist_$row->link\" class=\"cometchat_userlist\" onclick=\"javascript:jqcc.cometchat.chatWith('$row->userid')\" onmouseover=\"jqcc(this).addClass('cometchat_userlist_hover');\" onmouseout=\"jqcc(this).removeClass('cometchat_userlist_hover');\">
                       <span class=\"cometchat_userscontentname\">$row->name $row->sername</span>
                       <span class=\"cometchat_userscontentdot cometchat_$row->status\"></span>
                    </div>";
                     }

                ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

