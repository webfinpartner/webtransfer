<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
if (empty($topics))
    echo ""._e('accaunt/blocks/renderSuportDialog_part_1')."".(($help4me) ? ' <a class="qestion" href="#popup">'._e('accaunt/blocks/renderSuportDialog_part_2').'</a>' : '');
else {
//var_dump($topics);die;
foreach ($topics as $num => $topic) {?>
<div class="toggle">
    <div class="title closed <?=((Volunteer_topic_model::STATUS_ACTIVE == $topic["status"]) ? "" : "closeTopic")?>">
        <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=$topic["id"]?>:</h6>
        <div class="date w200Hidden">"<?=$topic["name"]?>"</div>
        <div class="date">(<?=(('' != $topic["date"]) ? date_formate_my($topic["date"], 'd/m/Yг.') : ""._e('accaunt/blocks/renderSuportDialog_part_3').""); ?>)</div>
    </div>
    <div class="body">
        <? foreach ($topic["messages"] as $message) {
            $is4Admin = Volunteer_message_model::FOR_ADMIN_TRUE == $message->for_admin;
            $isAdmin = Volunteer_message_model::FROM_ADMIN_TRUE == $message->is_admin;
            $adminType = ($is4Admin or $isAdmin);?>
        <div class="formRow <?=(($adminType) ? (($is4Admin) ? "admin" : "fromAdmin") : (($me == $message->id_user) ? "me" : "other"));?>">
            <label><?=(($me == $message->id_user) ? ""._e('accaunt/blocks/renderSuportDialog_part_4')."" : "$message->name $message->sername")?><br/><?=(($isAdmin)? "("._e('accaunt/blocks/renderSuportDialog_part_5').")":$message->id_user)?></label>
            <div class="formRight w_date">
                <?=date_formate_my($message->date, 'd.m.y')?>
            </div>
            <div class="formRight textAndDate">
                <?=$message->text?>
            </div>
        </div>
        <?}?>
        <?if ($help4me or Volunteer_topic_model::STATUS_ACTIVE == $topic["status"]){?>
        <div id="row_send_<?=$topic["id"]?>" class="formRow me send_question">
            <label>(<?=$me?>) <?=_e('accaunt/blocks/renderSuportDialog_part_6')?>:<br/><img src="/images/loading.gif" style="display: none" class="loading-gif" /></label>
            <div class="formRight">
                <textarea id='txtarea_<?=$num?>' class='textarea'></textarea>
                <script>
                    var textarea<?=$num?> = document.getElementById("txtarea_<?=$num?>"), style<?=$num?> = textarea<?=$num?>.style;
                    style<?=$num?>.overflow = "hidden";
                    style<?=$num?>.wordWrap = "break-word";
                    textarea<?=$num?>.onkeyup = function () {
                            if (/*@cc_on!@*/1) {
                                    style<?=$num?>.height = "auto";
                            }
                            style<?=$num?>.height = this.scrollHeight + "px";
                    };
                    textarea<?=$num?>.onscroll = function () {
                            this.scrollTop = 0;
                    };
                </script>
                <a id="send_<?=$num?>" class="but agree right bottom_right send" href="#" onclick="return false;"><?=_e('accaunt/blocks/renderSuportDialog_part_7')?></a>

                <script>
                    $("#send_<?=$num?>").click(function(){
                    var txt = $("#txtarea_<?=$num?>").val();
                    var tpc = "<?=$topic["name"]?>";
                    var id = "<?=$topic["id"]?>";
                    sendMessage(txt, tpc, id);
                    });
                </script>
            </div>
        </div>
        <div id="row_admin_send_<?=$topic["id"]?>" class="formRow me send_question">
            <label>(<?=$me?>) <?=_e('accaunt/blocks/renderSuportDialog_part_8')?><br/><img src="/images/loading.gif" style="display: none" class="loading-gif"></label>
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
                <a id="send_admin_<?=$num?>" class="but cancel right bottom_right send" href="#" onclick="return false;"><?=_e('accaunt/blocks/renderSuportDialog_part_9')?></a>

                <script>
                    $("#send_admin_<?=$num?>").click(function(){
                    var txt = $("#admin_txtarea_<?=$num?>").val();
                    var tpc = "<?=$topic["name"]?>";
                    var id = "<?=$topic["id"]?>";
                    sendMessage(txt, tpc, id, true);
                    });
                </script>
            </div>
        </div>
        <div class="formRow2">
            <div class="buttons">
                <?if ($help4me and Volunteer_topic_model::STATUS_ACTIVE == $topic["status"]){?>
                <a class="but agree smaller_text" href="<?=site_url('partner/closeTopic')?>/<?=$topic["id"]?>">
                        <span><?=_e('accaunt/blocks/renderSuportDialog_part_10')?></span>
                </a>
                <?}?>
                <a id="btn_send_else_<?=$topic["id"]?>" class="but agree smaller_text" href="#" onclick="$('#row_send_<?=$topic["id"]?>, #btn_send_admin_<?=$topic["id"]?>').show(); $('#btn_send_else_<?=$topic["id"]?>, #row_admin_send_<?=$topic["id"]?>').hide(); return false;">
                        <span><?=(($help4me) ? ""._e('accaunt/blocks/renderSuportDialog_part_11')."" : ""._e('accaunt/blocks/renderSuportDialog_part_12')."")?></span>
                </a>
                <a id="btn_send_admin_<?=$topic["id"]?>" class="but cancel smaller_text" href="#" onclick="$('#row_admin_send_<?=$topic["id"]?>, #btn_send_else_<?=$topic["id"]?>').show(); $('#btn_send_admin_<?=$topic["id"]?>, #row_send_<?=$topic["id"]?>').hide(); return false;">
                        <span><?=_e('accaunt/blocks/renderSuportDialog_part_13')?></span>
                </a>
            </div>
        </div>
        <?}?>
    </div>
</div>

<?}}?>