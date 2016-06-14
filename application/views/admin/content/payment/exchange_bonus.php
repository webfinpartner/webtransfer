<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<form method="POST">
    <div class="formRow">
        <label>Счет для списания</label>
        <div class="formRight">
                <?=form_dropdown('bonus_from', getBonusLabel(), '')?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Сумма для списания</label>
        <div class="formRight">
            <input type="text" name="summ_from"  value="" placeholder="-xxx.xx"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Счет для пополнения</label>
        <div class="formRight">
                <? $bonusLabels = getBonusLabel(); $bonusLabels[0] = '-Не выбрано-' ?>
                <?=form_dropdown('bonus_to', $bonusLabels, 0)?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Сумма для пополнения</label>
        <div class="formRight">
            <input type="text" name="summ_to"  value="" placeholder="+xxx.xx"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Описание</label>
        <div class="formRight">
            <input type="text" name="note"  value="" placeholder="Note" autocomplete="new-password" />
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Пароль</label>
        <div class="formRight">
            <input type="password" name="pas" value="" id="pass" autocomplete="new-password" />
        </div>
        <script type="text/javascript" src="js/jshash-2.2/md5-min.js"></script>
        <script>
            $("#pass").focusout(function(){
                $("#pass").val(hex_md5($("#pass").val()));
            })
        </script>
        <div class="clear"></div>
    </div>
    <center>
        <input type="submit" name='go' value="Перевести">
    </center>
</form>
<div class="clear"></div>
<div>
    <?=(isset($res) ? $res : '');?>
</div>