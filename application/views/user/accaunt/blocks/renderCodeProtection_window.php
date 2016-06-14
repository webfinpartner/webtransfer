<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .popup {
        top: 100px !important;
        left: 50%;
        margin-left: -200px;
        padding: 25px;
        position: fixed;
        /*top: 200px;*/
        width: 400px;
    }
</style>
<div class="popup" id="sendmoney">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" style="margin-top:10px;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('block/data1')?></h6>
        </div>
        <form id="send_form_user" method="POST" action="" accept-charset="utf-8">
            <input type="hidden" name="submit" value="1"/>
            <input type="hidden" name="code" id="form_code" value="1"/>
            <div class="loading-div" style="display: none; position: absolute; top: 0; height: 100%; width: 100%; background-color: #fff; text-align: center;">
                <img class='loading-gif' style="margin-top: 130px" src="/images/loading.gif"/>
            </div>
                <fieldset>
                    <div class="formRow">
                        <label style="margin-top:20px;"><?=_e('block/data2')?></label>
                        <div class="formRight">
                            <input class="form_input" type="text" name="code" id="code" value="" maxlength="5" placeholder="<?=_e('block/data3')?>"/>
                        </div>
                        <div class="error formRight" style="display:none; clear: both; color: red; width:60%" id="error_amount"></div>
                    </div>
                    <button class="button" type="submit" name="submit"><?=_e('accaunt/send_money_9')?></button>
                </fieldset>
        </form>
    </div>
</div>