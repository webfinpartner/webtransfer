<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<form method="POST">
    <div class="formRow">
        <label>Лимит заявок</label>
        <div class="formRight">
            <input type="text" name="limit_trans"  value="<?= $limit_trans ?>" placeholder="2000"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Период</label>
        <div class="formRight">
            <input type="text" name="date_start"  value="<?= $date_start ?>" placeholder="<?= date("Y-m-d") ?>"/>
            <input type="text" name="date_end"  value="<?= $date_end ?>" placeholder="<?= date("Y-m-d") ?>"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Бонус</label>
        <div class="formRight">
                <? $bonusLabels = getBonusLabel(); unset($bonusLabels[1]) ?>
                <?=form_dropdown('bonus', $bonusLabels, @$bonus)?>
        </div>
        <div class="clear"></div>
    </div>        
    <div class="formRow">
        <label>Макс. сумма</label>
        <div class="formRight">
            <input type="text" name="summ_max"  value="<?= $summ_max ?>" placeholder="0"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Коеф. прибыли</label>
        <div class="formRight">
            <input type="text" name="coef_pribil"  value="<?= $coef_pribil ?>" placeholder="1 - только собств."/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Внутрение переводы</label>
        <div class="formRight">
            <select name="send_money">
                <option value="0" <?=((0==$send_money)? "selected='selected'" : '')?>>Откл.</option>
                <option value="1" <?=((1==$send_money)? "selected='selected'" : '')?>>Вкл.</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Партнерская прибыль</label>
        <div class="formRight">
            <select name="partner_money">
                <option value="0" <?=((0==$partner_money)? "selected='selected'" : '')?>>Откл.</option>
                <option value="1" <?=((1==$partner_money)? "selected='selected'" : '')?>>Вкл.</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Если &sum;вывода больше ограничения</label>
        <div class="formRight">
            <select name="payout_limit">
                <!--<option value="0" <?=((0==$payout_limit)? "selected='selected'" : '')?>>Выводить все</option>-->
                <option value="1" <?=((1==$payout_limit)? "selected='selected'" : '')?>>Выводить только одну*</option>
                <option value="2" <?=((2==$payout_limit)? "selected='selected'" : '')?>>Выводить в рамках лимита</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Раздел для проверки</label>
        <div class="formRight">
            <select name="type">
                <option value="out" <?=(('out'==$type)? "selected='selected'" : '')?>>На вывод</option>
                <option value="wtcard" <?=(('wtcard'==$type)? "selected='selected'" : '')?>>Вывод WTCard</option>
                <option value="send_money" <?=(('send_money'==$type)? "selected='selected'" : '')?>>Переводы</option>
                <option value="verify_ss" <?=(('verify_ss'==$type)? "selected='selected'" : '')?>>Проверка СБ</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Документы от $</label>
        <div class="formRight">
            <input type="text" name="doc_summ"  value="<?= $doc_summ ?>" placeholder="0 - проверять у всех"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Присоединить сообщение</label>
        <div class="formRight">
            <input type="text" name="mess"  value="<?= $mess ?>" placeholder="свои + 0% - коэф = 1"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Платежка по умолчанию</label>
        <div class="formRight">
            <select name="def_payout">
                <option value="312" <?=((312==$def_payout)? "selected='selected'" : '')?>>Payeer</option>
                <option value="319" <?=((319==$def_payout)? "selected='selected'" : '')?>>Okpay</option>
                <option value="318" <?=((318==$def_payout)? "selected='selected'" : '')?>>Perfectmoney</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Использовать системы</label>
        <div class="formRight">
            <select name="sys_payout[]"  multiple>
                <option value="312" <?=((in_array(312, $sys_payout))? "selected='selected'" : '')?>>Payeer</option>
                <option value="319" <?=((in_array(319, $sys_payout))? "selected='selected'" : '')?>>Okpay</option>
                <option value="318" <?=((in_array(318, $sys_payout))? "selected='selected'" : '')?>>Perfectmoney</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Лимиты на системы</label>
        <div class="formRight">
            <p><input type='number' name='p_312' value="<?= $p_312 ?>" /> Payeer</p>
            <p><input type='number' name='p_319' value="<?= $p_319 ?>" /> Okpay</p>
            <p><input type='number' name='p_318' value="<?= $p_318 ?>" /> Perfectmoney</p>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Переводить на выплаты</label>
        <div class="formRight">
            <select name="payout">
                <option value="0" <?=((0==$payout)? "selected='selected'" : '')?>>Откл.</option>
                <option value="1" <?=((1==$payout)? "selected='selected'" : '')?>>Вкл.</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <label>Пароль</label>
        <div class="formRight">
            <input type="password" name="pas" value="" id="pass">
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
        <input type="submit" name='go' value="Проверить">
    </center>
</form>
<div class="clear"></div>
<div>
    <?=(isset($res) ? $res : '');?>
</div>