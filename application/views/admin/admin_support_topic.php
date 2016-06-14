<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url()?>js/user/custom.js"></script>

<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<script type="text/javascript" src="/js/user/messages_ru.js"></script>
<script type="text/javascript" src="/js/user/form_reg.js"></script>
<!--<script type="text/javascript" src="/js/user/credit.js"></script>-->
<script type="text/javascript" src="/js/user/debits.js"></script>
<style>
    .admin {
        color: #d30080;
    }
    .fromAdmin {
        color: #a71102; //FF5300
    }
    .me {
        color: #002166;
    }
    .other {
        color: #0064c8;
    }
    .textarea{
        width: 580px;
    }
    .bottom_right{
        position: absolute;
        bottom: 0px;
        right: 0px;
    }
    .formRight{
        position: relative;
    }
    .w200Hidden{
        height: 17px;
        width: 380px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .w200Hidden:hover {
        height: auto;
        white-space: normal;
        background-color: #efefef;
    }
    .formRow .textAndDate {
        width: 61%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .formRow .w_date {
        width: 9%;
    }
    .marginm45 {
        margin-top: -45px;
    }
    .margintab{
        margin-right: 215px;
    }
    .closeTopic{
        color: #cccccc;
    }
    .formRow2{
        padding: 5px 14px;
        /*border-bottom: 1px solid #E2E2E2;*/
        border-top: 1px solid white;
        position: relative;
        font-size: 12px;
    }
</style>

<script>
    $(function(){
      $(".toggle .body").slideToggle();
    });

    function sendMessage(txt, tpc, id){

        if (7 > txt.length) {
            alert("Слишком короткое сообщение");
            return;
        }
        var scs = function(d,t,x){
                $('.loading-gif').hide();
                if(d.e)
                    errorShow(d.e)
                else {
                    alert("Ваше сообщение отправлено.");
                    window.location.reload();
                }
            };
        $('.loading-gif').show();

        $.post("/opera/feedback/ajaxSendQuestion",
            {text: txt, topic: tpc, id: id, from_admin: true},
            scs ,
            "json");
    }
</script>
<? foreach ($topics as $num => $topic) {?>
<div class="toggle">
    <div class="title closed <?=((Volunteer_topic_model::STATUS_ACTIVE == $topic["status"]) ? "" : "closeTopic")?>">
        <img class="titleIcon" src="images/icons/dark/pencil.png" alt="" />
        <h6><?=$topic["id"]?>:</h6>
        <div class="date w200Hidden">"<?=$topic["name"]?>"</div>
        <div class="date">(<?=(('' != $topic["date"]) ? date_formate_my($topic["date"], 'd/m/Yг.') : "нет сообщений"); ?>)</div>
    </div>
    <div class="body">

        <? foreach ($topic["messages"] as $message) {
            $is4Admin = Volunteer_message_model::FOR_ADMIN_TRUE == $message->for_admin;
            $isAdmin = Volunteer_message_model::FROM_ADMIN_TRUE == $message->is_admin;
            $adminType = ($is4Admin or $isAdmin);?>
        <div class="formRow <?=(($adminType) ? (($is4Admin) ? "admin" : "fromAdmin") : (($me == $message->id_user) ? "me" : "other"));?>">
            <label><?=(($me == $message->id_user) ? "Я" : "$message->name $message->sername").(($is4Admin)? " - администратору" : "")?><br/><?=(($isAdmin)? "(Администратор)":$message->id_user)?></label>
            <div class="formRight w_date">
                <?=date_formate_my($message->date, 'd.m.y')?>
            </div>
            <div class="formRight textAndDate">
                <?=$message->text?>
            </div>
        </div>
        <?}?>

        <div id="row_admin_send_<?=$topic["id"]?>" class="formRow fromAdmin send_question">
            <label> (администратор) Я:<br/><img src="/images/loading.gif" class="loading-gif"  style="display: none"></label>
            <div class="formRight">
                <textarea id='admin_txtarea_<?=$num?>' class='textarea'></textarea>
                <script>
                    var admin_textarea<?=$num?> = document.getElementById("admin_txtarea_<?=$num?>"), admin_style<?=$num?> = admin_textarea<?=$num?>.style;
                    admin_style<?=$num?>.overflow = "hidden";
                    admin_style<?=$num?>.wordWrap = "break-word";
                    admin_textarea<?=$num?>.onkeyup = function () {
                            if (/*@cc_on!@*/1) {
                                    admin_style<?=$num?>.height = "auto";
                            }
                            admin_style<?=$num?>.height = this.scrollHeight + "px";
                    };
                    admin_textarea<?=$num?>.onscroll = function () {
                            this.scrollTop = 0;
                    };
                </script>
                <a id="send_admin_<?=$num?>" class="button redB send" style="margin: 4px 4px; float: right; padding: 6px;" href="#" onclick="return false;">Отправить</a>

                <script>
                    $("#send_admin_<?=$num?>").click(function(){
                    var txt = $("#admin_txtarea_<?=$num?>").val();
                    var tpc = "<?=$topic["name"]?>";
                    var id = "<?=$topic["id"]?>";
                    sendMessage(txt, tpc, id);
                    });
                </script>
            </div>
        </div>
        <div class="formRow2">
            <div class="buttons">
                <?if (Volunteer_topic_model::STATUS_ACTIVE == $topic["status"]){?>
                <a class="button greenB smaller_text" href="/opera/feedback/closeTopic/<?=$topic["id"]?>">
                        <span>Вопрос решен</span>
                </a>
                <?}?>
            </div>
        </div>
    </div>
</div>

<?}?>