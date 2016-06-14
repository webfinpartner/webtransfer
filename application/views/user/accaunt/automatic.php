<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />

<div class="widget">
    <div class="title"><img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('accaunt/automatic_1')?><?=(($save)? _e('accaunt/automatic_2') : "")?></h6>
    </div>
    <form class="form" id="automatic_form" action="<?=site_url('account/automatic')?>" method="post">
        <fieldset >
            <div class="formRow padding10-40">
                    <label style="margin-top:10px;"><input type="checkbox" value="1" id="zajm" name="zajm" <? if($obj->zajm) echo 'checked="checked"';?>/> <?=_e('accaunt/automatic_3')?></label>
                    <script>
                        $("#zajm").click(function(){
                             if ($(this).prop("checked")) $("#zajm_prop").show();
                             else $("#zajm_prop").hide();
                        });
                    </script>
            </div>
            <div class="formRow" id="zajm_prop" <? if(!$obj->zajm) echo 'style="display: none;"';?>>
                <div class="col">
                    <?if(false){?><label><?=_e('accaunt/automatic_4')?></label>
                    <input type="checkbox" value="1" id="zajm_sum" name="zajm_sum" <? if($obj->zajm_sum) echo 'checked="checked"';?>/>
                    <script>
                        $("#zajm_sum").click(function(){
                             if (!$(this).prop("checked")) $(".select_sum").show();
                             else $(".select_sum").hide();
                        });
                    </script><?}?>
                    <label class="select_sum" <? if($obj->zajm_sum) echo 'style="display: none;"';?>><?=_e('accaunt/automatic_5')?></label>
                    <select id="select_sum" name="summ"  class="form_select select_sum" <? if($obj->zajm_sum) echo 'style="display: none;"';?>><?
                        for ($i = 50; $i <= 1000; $i +=50) {
                            $def = '';
                            if ($obj->summ == $i) $def = "selected='selected'";
                            echo "<option value=\"$i\" $def>$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <?if(false){?><label><?=_e('accaunt/automatic_6')?></label>
                    <input type="checkbox" value="1" id="zajm_time" name="zajm_time" <? if($obj->zajm_time) echo 'checked="checked"';?>/>
                    <script>
                        $("#zajm_time").click(function(){
                             if (!$(this).prop("checked")) $(".select_period").show();
                             else $(".select_period").hide();
                        });
                    </script><?}?>
                    <label class="select_period" <? if($obj->zajm_time) echo 'style="display: none;"';?>><?=_e('accaunt/automatic_7')?></label>
                    <select id="select_period" name="time" class="form_select select_period" <? if($obj->zajm_time) echo 'style="display: none;"';?>><?
                        for ($i = 3; $i <= 30; $i++) {
                            $def = '';
                            if ($obj->time == $i) $def = "selected='selected'";
                            echo "<option value=\"$i\" $def>$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <?if(false){?><label><?=_e('accaunt/automatic_8')?></label>
                    <input type="checkbox" value="1" id="zajm_psnt" name="zajm_psnt" <? if($obj->zajm_psnt) echo 'checked="checked"';?>/>
                    <script>
                        $("#zajm_psnt").click(function(){
                             if (!$(this).prop("checked")) $(".select_percent").show();
                             else $(".select_percent").hide();
                        });
                    </script><?}?>
                    <label class="select_percent" <? if($obj->zajm_psnt) echo 'style="display: none;"';?>><span><?=_e('accaunt/automatic_9')?></span></label>
                    <select name="percent" id="select_percent" class="form_select select_percent" <? if($obj->zajm_psnt) echo 'style="display: none;"';?>><? getPsntSelect($obj->percent) ?></select>
                </div>
            </div>
            <?if(false){?><div class="formRow padding10-40">
                <label style="margin-top:10px;"><?=_e('accaunt/automatic_10')?></label>
                <div class="formRight">
                    <input type="checkbox" value="1" id="credit" name="credit" <? if($obj->credit) echo 'checked="checked"';?>/>
                    <script>
                        $("#credit").click(function(){
                             if ($(this).prop("checked")) $("#credit_prop").show();
                             else $("#credit_prop").hide();
                        });
                    </script>
                </div>
            </div>

            <div class="formRow" id="credit_prop" <? if(!$obj->credit) echo 'style="display: none;"';?>>
                <div class="col">
                    <label><?=_e('accaunt/automatic_11')?></label>
                    <?if(false){?><input type="checkbox" value="1" id="credit_max_start_psnt_auto" name="credit_max_start_psnt_auto" <? if($obj->credit_max_start_psnt_auto) echo 'checked="checked"';?>/><?}?>
                    <script>
                        $("#credit_max_start_psnt_auto").click(function(){
                             if (!$(this).prop("checked")) $("#credit_max_start_psnt").show();
                             else $("#credit_max_start_psnt").hide();
                        });
                    </script>
                    <select id="credit_max_start_psnt" name="credit_max_start_psnt"  class="form_select" <? if($obj->credit_max_start_psnt_auto) echo 'style="display: none;"';?>><?
                        for ($i = 0.5; $i <= 2.1; $i +=0.1) {
                            $def = '';
                            if ($obj->credit_max_start_psnt == $i) $def = "selected='selected'";
                            echo "<option value=\"$i\" $def>". sprintf("%01.1f%%", $i) ."</option>";
                        }
                        ?></select>
                </div>
                <?if(false){?><div class="col">
                    <label><?=_e('accaunt/automatic_12')?></label>
                    <input type="input" value="<?=($max_psnt-$obj->credit_max_start_psnt)?>" id="credit_add_psnt" name="credit_add_psnt" disabled="disabled"/>
                    <script>
                        $("#credit_max_start_psnt").change(function(){
                             var psnt = $("#credit_max_start_psnt").val();
                             var res_psnt = <?=$max_psnt?>;
                             $("#credit_add_psnt").val(res_psnt - psnt);
                        });
                    </script>
                </div>
                <div class="col">
                    <label><?=_e('accaunt/automatic_13')?></label>
                    <input type="input" value="<?=$max_psnt?>" id="credit_res_psnt" name="credit_res_psnt" disabled="disabled"/>
                </div><?}?>
            </div><?}?>
            <button class="button" type="submit"  id="send" name="submit" value="1"><?=_e('accaunt/automatic_14')?></button>

        </fieldset>
    </form>
</div>