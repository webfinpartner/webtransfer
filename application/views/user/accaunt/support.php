<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=site_url('js/user/custom.js')?>"></script>

<style>
    .admin {
        color: #d30080;
    }
    .fromAdmin {
        color: #a71102; /*FF5300*/
    }
    .me {
        color: #002166;
    }
    .other {
        color: #0064c8;
    }
    .textarea{
        width: 350px;
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
    .send_question{
        display: none;
    }
    .formRow2{
        padding: 5px 14px;
        /*border-bottom: 1px solid #E2E2E2;*/
        border-top: 1px solid white;
        position: relative;
        font-size: 12px;
    }
</style>
<?
if ($hasParent) echo '<a href="" onclick="return false;" class="but agree marginm45 right qestion">'._e('accaunt/support_1').'</a>';
if (!$is_volanteer) {
    echo '<a id="volunteer" href="" onclick="return false;" class="but agree marginm45 right margintab">'._e('accaunt/support_2').'</a>';
    $this->load->view('user/blocks/volunteer_dialog.php', array("volunteer" => $is_volanteer, "backPage" => "support"));
}
if (!($is_volanteer or $hasParent))
    echo ""._e('accaunt/support_3')."";
else if ($is_volanteer and !$hasParent)
    $this->load->view('user/accaunt/blocks/renderSuportDialog_part.php', array("topics" => $volanteer_topics, "me" => $id_user, "help4me" => false));
else if (!$is_volanteer and $hasParent)
    $this->load->view('user/accaunt/blocks/renderSuportDialog_part.php', array("topics" => $user_topics, "me" => $id_user, "help4me" => true));
else if ($is_volanteer and $hasParent){?>
<div class="widget">
    <ul class="tabs">
        <li id="content_admin_users_user_userId_contactsInfo" class="activeTab"><a id="tb1" href="#tab1"><?=_e('accaunt/support_6')?></a></li>
        <li id="content_admin_users_user_userId_doc" class=""><a id="tb2" href="#tab2"><?=_e('accaunt/support_7')?></a></li>
    </ul>

    <div class="tab_container">
        <div id="tab1" class="tab_content" style="display: block;">
            <?$this->load->view('user/accaunt/blocks/renderSuportDialog_part.php', array("topics" => $volanteer_topics, "me" => $id_user, "help4me" => false));?>
        </div>
        <div id="tab2" class="tab_content" style="display: block;">
            <?$this->load->view('user/accaunt/blocks/renderSuportDialog_part.php', array("topics" => $user_topics, "me" => $id_user, "help4me" => true));?>
        </div>
    </div>
</div>
<?}?>
<script>
function sendMessage(txt, tpc, id, admin){
    admin = admin || false;

    if (1 > txt.length) {
        alert("<?=_e('accaunt/support_4')?>");
        return;
    }
    var scs = function(d,t,x){
            $('.loading-gif').hide();
            if(d.e)
                errorShow(d.e)
            else {
                alert("<?=_e('accaunt/support_5')?>");
                window.location.reload();
            }
        };
    $('.loading-gif').show();

    $.post(site_url + "/account/ajaxSendQuestion",
        {text: txt, topic: tpc, id: id, admin: admin},
        scs ,
        "json");
}
</script>
