     <link rel="stylesheet" href="/msgs/src/css/style.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"/>
    <link rel='stylesheet' href='/msgs/src/css/font.css'/>
    <link rel="stylesheet" href="/msgs/src/css/jquery.mCustomScrollbar.css"/>


<div id="DraggableZone" style="width: 100%;height: 100%;position: fixed;z-index: -1;top: 0;right: 0;">
</div>
<div id="nwt-chat-im-modal-layerm" class="wt-chat-im main" style="z-index:100;">
    <div class="wt-chat-im-header">
        <div class="wt-chat-im-ava-img">
            <img src="https://webtransfer.com/assets/missing_avatar_thumb.jpeg" height="38" width="38" class="radius_50"
                 id="logo_Chat_user">

            <div class="wt-chat-im-status no-active-status"></div>
        </div>

        <div class="wt-chat-im-name" id="wt-chat-im-name">--------</div>

        <div class="wt-chat-im-buttons">
            <div class="wt-chat-im-close-btn  wt-chat-im-modal-showm3"></div>
            <div class="wt-chat-im-full-btn wt-chat-im-modal-showm2"></div>
        </div>

    </div>


    <div class="wrapper wt-chat-im">

        <div class="wt-chat-im content light mCustomScrollbar" id="mess">
            <center>
                <i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0px"
                   id="preloader_dialog"></i>
            </center>

        </div>
    </div>


    <div class="wt-chat-im-footer">
        <div style="height: 27px">
            <div class="wt-chat-im-write" id="WriteStatus"></div>
            <div class="wt-chat-im-icons-all">
                <div class="wt-chat-im-icon-1"></div>
                <div class="wt-chat-im-icon-2"></div>
            </div>
        </div>
        <!--<textarea class="wt-chat-im-textarea" onkeypress="validSend(this, event)"></textarea>-->
        <span id="textArenaMCE">
        <div class="mceText"></div>
            </span>
        <div id="wt-chat-im-modal-shown" class="wt-chat-im-textarea-icon2"></div>
        <div id="wt-chat-im-modal-show" class="wt-chat-im-textarea-icon1"></div>

    </div>

    <div id="wt-chat-im-modal-layer" style="display:none;" class="wt-chat-im-modal-select">
        <div class="wt-chat-im-modal-select-var1">Аудиозапись</div>
        <div class="wt-chat-im-modal-select-var2">Видеозапись</div>
        <div class="wt-chat-im-modal-select-var3">Документ</div>
        <div id="wt-chat-im-modal-show2"></div>
    </div>

    <div id="wt-chat-im-modal-layern" class="wt-chat-im-modal-smiles" style="display:none;">
        <div class="wt-chat-im-modal-smiles-padding">
            <div class="wt-chat-im-smile1"></div>
            <div class="wt-chat-im-smile2"></div>
            <div class="wt-chat-im-smile3"></div>
            <div class="wt-chat-im-smile4"></div>
            <div class="wt-chat-im-smile5"></div>
            <div class="wt-chat-im-smile6"></div>
            <div class="wt-chat-im-smile7"></div>
            <div class="wt-chat-im-smile8"></div>
            <div class="wt-chat-im-smile9"></div>
            <div class="wt-chat-im-smile10"></div>
            <div class="wt-chat-im-smile11"></div>
            <div class="wt-chat-im-smile12"></div>
            <div class="wt-chat-im-smile13"></div>
            <div class="wt-chat-im-smile14"></div>
            <div class="wt-chat-im-smile15"></div>
            <div class="wt-chat-im-smile16"></div>
            <div class="wt-chat-im-smile17"></div>
            <div class="wt-chat-im-smile18"></div>
            <div class="wt-chat-im-smile19"></div>
            <div class="wt-chat-im-smile20"></div>
            <div class="wt-chat-im-smile21"></div>
            <div class="wt-chat-im-smile22"></div>
            <div class="wt-chat-im-smile23"></div>
            <div class="wt-chat-im-smile24"></div>
            <div class="wt-chat-im-smile25"></div>
            <div class="wt-chat-im-smile26"></div>
            <div class="wt-chat-im-smile27"></div>
            <div class="wt-chat-im-smile28"></div>
            <div class="wt-chat-im-smile29"></div>
            <div class="wt-chat-im-smile30"></div>
            <div class="wt-chat-im-smile31"></div>
            <div class="wt-chat-im-smile32"></div>
        </div>
        <div class="wt-chat-im-modal-smiles-footer">
            <div id="wt-chat-im-modal-shown2" class="wt-chat-im-smile"></div>
            <div class="wt-chat-im-smile-all">
                <div class="wt-chat-im-smile-1"></div>
                <div class="wt-chat-im-smile-2"></div>
                <div class="wt-chat-im-smile-3"></div>
            </div>
            <div class="wt-chat-im-plus"></div>
        </div>
    </div>
</div>
<div id="PostsGeneralChat" class="wt-chat-o main">
    <div class="wt-chat-o-header">
        <div class="wt-chat-im-ava-img">
            <img src="https://webtransfer.com/mainpage/images/logowt.png" height="38" width="67"
                 class="radius_50">
        </div>
        <div class="wt-chat-o-name">Общий чат.</div>
        <div class="wt-chat-im-buttons">
            <div class="wt-chat-im-close-btn closeGeneralChat_btn"></div>
        </div>
    </div>


    <div class="wrapper wt-chat-o">
        <div class="wt-chat-im content_generalChat light mCustomScrollbar" id="blockPostsGeneralChat">
            <center>
                <i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0"></i></center>
        </div>
    </div>


    <div class="wt-chat-o-footer">
        <div class="edText"></div>
        <a class="btnsend_o">
        <i class="fa fa-paper-plane"></i>
        </a>
    </div>
</div>
<div id="nwt-chat-im-modal-layerm_1" class="wt-chat-im main wt-user-list">
    <div class="wt-chat-im-header">


        <div class="wt-chat-im-name wt-chat-im-name_1" id="wt-chat-im-name1"><i class="fa fa-users"></i> Мои друзья
            (<span id="CountFr">-</span>)
        </div>

        <div class="wt-chat-im-buttons">
            <div class="wt-chat-im-full-btn wt-chat-im-modal-showm3_1"></div>
        </div>

    </div>


    <div class="wrapper wrapper_1 wt-chat-im">

        <div class="wt-chat-im content content_1 light mCustomScrollbar">

            <!--<i class="fa fa-refresh fa-5x fa-spin" style="color: #969696;padding: 70px 0px" id="preloader_user"></i>-->
        </div>
    </div>

</div>
<div class="wt-chat-im-users-area">

    <div simple-hint="Общий Чат" class="hint-left-t-error hint-fade right-arrow">
        <div class="o_chat">
            <i class="fa fa-comments-o fa-3x"></i>
        </div>
    </div>
    <!--<div class="dragH" style="margin: 5px -4px 10px;height: 1px;padding: 0 4px;"></div>-->
    <div class="sc_listUser">
        <div id="listDialog">
            <i class="fa fa-refresh fa-3x fa-spin loadDialoglist" style="color: #969696;padding: 10px 0px"></i>
        </div>
    </div>
    <!--<div class="dragH" style="margin: 3px -4px 3px;height: 1px;padding: 0 4px;"></div>-->
    <!---->
    <a href="https://webtransfer.com/social/profile/93930">
        <div simple-hint="Поддержка" class="hint-left-t-info hint-fade right-arrow">
            <div class="btnask">
                <i class="fa fa-question-circle fa-3x"></i>
            </div>
        </div>
    </a>
    <div simple-hint="Онлайн" class="hint-left-t-error hint-fade right-arrow" style="text-align: center;" data-action="OpenListUser">
        <div class="all-users-info">
            <span id="newDialog_count"></span>
            <i class="fa fa-users"></i>
        </div>
    </div>


</div>

<script src="/msgs/src/js/socket.io-1.4.4.js"></script>
<script src="/msgs/src/js/jquery-2.2.0.min.js"></script>
<script src="/msgs/src/js/jq_ui.js"></script>
<script src="/msgs/src/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/msgs/src/tinymce/tinymce.min.js"></script>
<script src="/msgs/src/js/client-chat.js"></script>