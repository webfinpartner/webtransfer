<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/>

<form id="validate" class="form" action="/opera/<?= $controller ?>/annul"  method="post">
    <input type="hidden" id="submited" name="submited" value="1"/>
    <fieldset>
        <div class="widget">
            <div class="title">
                <img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Аннулирование Транзакций и Заявок</h6>
            </div>
            <div class="formRow">
                <input id="showalltransactions" type="checkbox" value="1" name="showalltransactions" checked="true"/>
                <label for="showalltransactions">Отображать все транзакции</label>
            </div>
            <div class="formRow">

                <label>Транзакции:</label>

                <div class="formRight" style="margin-bottom: 7px;">
                    <textarea name="transactions"><?= $entery_transactions; ?></textarea>
                </div>
                <div class="clear"></div>
                <?php if( is_array($transactions_table) && count($transactions_table) > 0 ){ ?>
                <input type="hidden" name="annul" value="true">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td colspan="10" style="text-align: center">Заявка</td>
                            <td colspan="7" style="text-align: center">Транзакция</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="transactions_i" checked=""/>
                            </td>
                            <td>id</td>
                            <td>debit</td>
                            <td>Польз.</td>
                            <td>Сумма</td>
                            <td>Статус</td>
                            <td>Тип</td>
                            <td>Гар.</td>
                            <td>Овердр.</td>
                            <td>Бон.</td>

                            <td>id</td>
                            <td>Метод</td>
                            <td>Коммент</td>
                            <td>Бон.</td>
                            <td>Сум.</td>
                            <td>Статус</td>
                            <td>
                                <input type="checkbox" id="transactions_t" checked=""/>
                            </td>
                        </tr>
                    </thead>

                    <?php
                    foreach( $transactions_table as $group ){?>
                        <tbody>
                            <?php if( is_array( $group[2] ) ){
                                  $i = 0; ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="i<?=$group[1]->id;?>" class="transactions_i" checked=""/>
                                    </td>
                                    <td><?= $group[1]->id ?></td>
                                    <td><?= $group[1]->debit ?></td>
                                    <td><?= $group[1]->id_user ?></td>
                                    <td><?= $group[1]->summa ?></td>
                                    <td><?= accaunt_debit_status($group[1]->state) ?></td>
                                    <td><?= $group[1]->type ?></td>
                                    <td><?= $group[1]->garant ?></td>
                                    <td><?= $group[1]->overdraft ?></td>
                                    <td><?= ($group[1]->bonus == 1?'да': ($group[1]->bonus == 0?'нет':$group[1]->bonus) ) ?></td>
                                    <td><?= $group[2][$i]->id ?></td>
                                    <td><?= $group[2][$i]->metod ?></td>
                                    <td><?= $group[2][$i]->note ?></td>
                                    <td><?= ($group[2][$i]->bonus == 1?'да': ($group[2][$i]->bonus == 2?'нет':$group[2][$i]->bonus) ) ?></td>
                                    <td><?= $group[2][$i]->summa ?></td>
                                    <td><?= getTransactionLabelStatus($group[2][$i]->status) ?></td>
                                    <td>
                                        <input type="checkbox" name="t<?=$group[2][$i]->id;?>" class="transactions_t" checked="" />
                                    </td>
                                </tr>
                            <?php for( $i = 1; $i < count( $group[2] ); $t = $group[2][$i++] ){ ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?= $group[2][$i]->id ?></td>
                                        <td><?= $group[2][$i]->metod ?></td>
                                        <td><?= $group[2][$i]->note ?></td>
                                        <td><?= ($group[2][$i]->bonus == 1?'да': ($group[2][$i]->bonus == 2?'нет':$group[2][$i]->bonus) ) ?></td>
                                        <td><?= $group[2][$i]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[2][$i]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[2][$i]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                                <?php }?>
                            <?php }else{?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="i<?=$group[1]->id;?>" class="transactions_i" checked=""/>
                                        </td>
                                        <td><?= $group[1]->id ?></td>
                                        <td><?= $group[1]->debit ?></td>
                                        <td><?= $group[1]->id_user ?></td>
                                        <td><?= $group[1]->summa ?></td>
                                        <td><?= accaunt_debit_status($group[1]->state) ?></td>
                                        <td><?= $group[1]->type ?></td>
                                        <td><?= $group[1]->garant ?></td>
                                        <td><?= $group[1]->overdraft ?></td>
                                        <td><?= ($group[1]->bonus == 1?'да': ($group[1]->bonus == 0?'нет':$group[1]->bonus) ) ?></td>
                                        <td><?= $group[2]->id ?></td>
                                        <td><?= $group[2]->metod ?></td>
                                        <td><?= $group[2]->note ?></td>
                                        <td><?= ($group[2]->bonus == 1?'да': ($group[2]->bonus == 2?'нет':$group[2]->bonus) ) ?></td>
                                        <td><?= $group[2]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[2]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[2]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                            <?php }?>

                            <?php if( is_array( $group[4] ) ){
                                  $i = 0; ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="i<?=$group[3]->id;?>" class="transactions_i" checked=""/>
                                    </td>
                                    <td><?= $group[3]->id ?></td>
                                    <td><?= $group[3]->debit ?></td>
                                    <td><?= $group[3]->id_user ?></td>
                                    <td><?= $group[3]->summa ?></td>
                                    <td><?= accaunt_debit_status($group[3]->state) ?></td>
                                    <td><?= $group[3]->type ?></td>
                                    <td><?= $group[3]->garant ?></td>
                                    <td><?= $group[3]->overdraft ?></td>
                                    <td><?= ($group[3]->bonus == 3?'да': ($group[3]->bonus == 0?'нет':$group[3]->bonus) ) ?></td>
                                    <td><?= $group[4][$i]->id ?></td>
                                    <td><?= $group[4][$i]->metod ?></td>
                                    <td><?= $group[4][$i]->note ?></td>
                                    <td><?= ($group[4][$i]->bonus == 1?'да': ($group[4][$i]->bonus == 4?'нет':$group[4][$i]->bonus) ) ?></td>
                                    <td><?= $group[4][$i]->summa ?></td>
                                    <td><?= getTransactionLabelStatus($group[4][$i]->status) ?></td>
                                    <td>
                                        <input type="checkbox" name="t<?=$group[4][$i]->id;?>" class="transactions_t" checked="" />
                                    </td>
                                </tr>
                            <?php for( $i = 1; $i < count( $group[4] ); $t = $group[4][$i++] ){ ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?= $group[4][$i]->id ?></td>
                                        <td><?= $group[4][$i]->metod ?></td>
                                        <td><?= $group[4][$i]->note ?></td>
                                        <td><?= ($group[4][$i]->bonus == 1?'да': ($group[4][$i]->bonus == 4?'нет':$group[4][$i]->bonus) ) ?></td>
                                        <td><?= $group[4][$i]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[4][$i]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[4][$i]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                                <?php }?>
                            <?php }else{?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="i<?=$group[3]->id;?>" class="transactions_i" checked=""/>
                                        </td>
                                        <td><?= $group[3]->id ?></td>
                                        <td><?= $group[3]->debit ?></td>
                                        <td><?= $group[3]->id_user ?></td>
                                        <td><?= $group[3]->summa ?></td>
                                        <td><?= accaunt_debit_status($group[3]->state) ?></td>
                                        <td><?= $group[3]->type ?></td>
                                        <td><?= $group[3]->garant ?></td>
                                        <td><?= $group[3]->overdraft ?></td>
                                        <td><?= ($group[3]->bonus == 1?'да': ($group[3]->bonus == 0?'нет':$group[3]->bonus) ) ?></td>
                                        <td><?= $group[4]->id ?></td>
                                        <td><?= $group[4]->metod ?></td>
                                        <td><?= $group[4]->note ?></td>
                                        <td><?= ($group[4]->bonus == 1?'да': ($group[4]->bonus == 4?'нет':$group[4]->bonus) ) ?></td>
                                        <td><?= $group[4]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[4]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[4]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                            <?php }?>
                        </tbody>
                    <?php }?>

                </table>
                <center style="clear:both;">
                    <a class="wButton redwB ml15 m10 submit annul" id="reset" title="" href="#" >
                        <span>Аннулировать</span>
                    </a>
                    <a class="wButton greenwB ml15 m10 submit" title="" href="#"><span>Найти</span></a>
                </center>
                <?php }else{
                    echo "<center>".(empty($transactions_table)?'':$transactions_table)."</center>";
                ?>
                    <center>
                        <a class="wButton greenwB ml15 m10 submit" title="" href="#"><span>Найти</span></a>
                    </center>
                <?php
                };?>

            </div>
            <div class="formRow">
                <input id="showallcredits" type="checkbox" value="1" name="showallcredits" checked="true"/>
                <label for="showallcredits">Отображать все транзакции</label>
            </div>
            <div class="formRow">
                <label>Заявки:</span></label>
                <div class="formRight" style="margin-bottom: 7px;">
                    <textarea name="inqueries"><?= $entery_inqueries; ?></textarea>
                </div>
                <div class="clear"></div>
                <?php if( is_array($inqueries_table) && count($inqueries_table) > 0 ){ ?>
                <input type="hidden" name="annul" value="true">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td colspan="10" style="text-align: center">Заявка</td>
                            <td colspan="7" style="text-align: center">Транзакция</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="inqueries_i" checked=""/>
                            </td>
                            <td>id</td>
                            <td>debit</td>
                            <td>Польз.</td>
                            <td>Сум.</td>
                            <td>Статус</td>
                            <td>Тип</td>
                            <td>Гар.</td>
                            <td>Овердр.</td>
                            <td>Бон.</td>

                            <td>id</td>
                            <td>Метод</td>
                            <td>Коммент</td>
                            <td>Бон.</td>
                            <td>Сум.</td>
                            <td>Статус</td>
                            <td>
                                <input type="checkbox" id="inqueries_t" checked=""/>
                            </td>
                        </tr>
                    </thead>
                    <?php foreach( $inqueries_table as $group ){?>
                        <tbody>
                            <?php if( is_array( $group[2] ) ){
                                      $i = 0; ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="i<?=$group[1]->id;?>" class="transactions_i" checked=""/>
                                        </td>
                                        <td><?= $group[1]->id ?></td>
                                        <td><?= $group[1]->debit ?></td>
                                        <td><?= $group[1]->id_user ?></td>
                                        <td><?= $group[1]->summa ?></td>
                                        <td><?= accaunt_debit_status($group[1]->state) ?></td>
                                        <td><?= $group[1]->type ?></td>
                                        <td><?= $group[1]->garant ?></td>
                                        <td><?= $group[1]->overdraft ?></td>
                                        <td><?= ($group[1]->bonus == 1?'да': ($group[1]->bonus == 0?'нет':$group[1]->bonus) ) ?></td>
                                        <td><?= $group[2][$i]->id ?></td>
                                        <td><?= $group[2][$i]->metod ?></td>
                                        <td><?= $group[2][$i]->note ?></td>
                                        <td><?= ($group[2][$i]->bonus == 1?'да': ($group[2][$i]->bonus == 2?'нет':$group[2][$i]->bonus) ) ?></td>
                                        <td><?= $group[2][$i]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[2][$i]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[2][$i]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                                <?php for( $i = 1; $i < count( $group[2] ); $t = $group[2][$i++] ){ ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= $group[2][$i]->id ?></td>
                                            <td><?= $group[2][$i]->metod ?></td>
                                            <td><?= $group[2][$i]->note ?></td>
                                            <td><?= ($group[2][$i]->bonus == 1?'да': ($group[2][$i]->bonus == 2?'нет':$group[2][$i]->bonus) ) ?></td>
                                            <td><?= $group[2][$i]->summa ?></td>
                                            <td><?= getTransactionLabelStatus($group[2][$i]->status) ?></td>
                                            <td>
                                                <input type="checkbox" name="t<?=$group[2][$i]->id;?>" class="transactions_t" checked="" />
                                            </td>
                                        </tr>
                                    <?php }?>
                                <?php }else{?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="i<?=$group[1]->id;?>" class="transactions_i" checked=""/>
                                            </td>
                                            <td><?= $group[1]->id ?></td>
                                            <td><?= $group[1]->debit ?></td>
                                            <td><?= $group[1]->id_user ?></td>
                                            <td><?= $group[1]->summa ?></td>
                                            <td><?= accaunt_debit_status($group[1]->state) ?></td>
                                            <td><?= $group[1]->type ?></td>
                                            <td><?= $group[1]->garant ?></td>
                                            <td><?= $group[1]->overdraft ?></td>
                                            <td><?= ($group[1]->bonus == 1?'да': ($group[1]->bonus == 0?'нет':$group[1]->bonus) ) ?></td>
                                            <td><?= $group[2]->id ?></td>
                                            <td><?= $group[2]->metod ?></td>
                                            <td><?= $group[2]->note ?></td>
                                            <td><?= ($group[2]->bonus == 1?'да': ($group[2]->bonus == 2?'нет':$group[2]->bonus) ) ?></td>
                                            <td><?= $group[2]->summa ?></td>
                                            <td><?= getTransactionLabelStatus($group[2]->status) ?></td>
                                            <td>
                                                <input type="checkbox" name="t<?=$group[2]->id;?>" class="transactions_t" checked="" />
                                            </td>
                                        </tr>
                                <?php }?>

                                <?php if( is_array( $group[4] ) ){
                                      $i = 0; ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="i<?=$group[3]->id;?>" class="transactions_i" checked=""/>
                                        </td>
                                        <td><?= $group[3]->id ?></td>
                                        <td><?= $group[3]->debit ?></td>
                                        <td><?= $group[3]->id_user ?></td>
                                        <td><?= $group[3]->summa ?></td>
                                        <td><?= accaunt_debit_status($group[3]->state) ?></td>
                                        <td><?= $group[3]->type ?></td>
                                        <td><?= $group[3]->garant ?></td>
                                        <td><?= $group[3]->overdraft ?></td>
                                        <td><?= ($group[3]->bonus == 3?'да': ($group[3]->bonus == 0?'нет':$group[3]->bonus) ) ?></td>
                                        <td><?= $group[4][$i]->id ?></td>
                                        <td><?= $group[4][$i]->metod ?></td>
                                        <td><?= $group[4][$i]->note ?></td>
                                        <td><?= ($group[4][$i]->bonus == 1?'да': ($group[4][$i]->bonus == 4?'нет':$group[4][$i]->bonus) ) ?></td>
                                        <td><?= $group[4][$i]->summa ?></td>
                                        <td><?= getTransactionLabelStatus($group[4][$i]->status) ?></td>
                                        <td>
                                            <input type="checkbox" name="t<?=$group[4][$i]->id;?>" class="transactions_t" checked="" />
                                        </td>
                                    </tr>
                                <?php for( $i = 1; $i < count( $group[4] ); $t = $group[4][$i++] ){ ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= $group[4][$i]->id ?></td>
                                            <td><?= $group[4][$i]->metod ?></td>
                                            <td><?= $group[4][$i]->note ?></td>
                                            <td><?= ($group[4][$i]->bonus == 1?'да': ($group[4][$i]->bonus == 4?'нет':$group[4][$i]->bonus) ) ?></td>
                                            <td><?= $group[4][$i]->summa ?></td>
                                            <td><?= getTransactionLabelStatus($group[4][$i]->status) ?></td>
                                            <td>
                                                <input type="checkbox" name="t<?=$group[4][$i]->id;?>" class="transactions_t" checked="" />
                                            </td>
                                        </tr>
                                    <?php }?>
                                <?php }else{?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="i<?=$group[3]->id;?>" class="transactions_i" checked=""/>
                                            </td>
                                            <td><?= $group[3]->id ?></td>
                                            <td><?= $group[3]->debit ?></td>
                                            <td><?= $group[3]->id_user ?></td>
                                            <td><?= $group[3]->summa ?></td>
                                            <td><?= accaunt_debit_status($group[3]->state) ?></td>
                                            <td><?= $group[3]->type ?></td>
                                            <td><?= $group[3]->garant ?></td>
                                            <td><?= $group[3]->overdraft ?></td>
                                            <td><?= ($group[3]->bonus == 1?'да': ($group[3]->bonus == 0?'нет':$group[3]->bonus) ) ?></td>
                                            <td><?= $group[4]->id ?></td>
                                            <td><?= $group[4]->metod ?></td>
                                            <td><?= $group[4]->note ?></td>
                                            <td><?= ($group[4]->bonus == 1?'да': ($group[4]->bonus == 4?'нет':$group[4]->bonus) ) ?></td>
                                            <td><?= $group[4]->summa ?></td>
                                            <td><?= getTransactionLabelStatus($group[4]->status) ?></td>
                                            <td>
                                                <input type="checkbox" name="t<?=$group[4]->id;?>" class="transactions_t" checked="" />
                                            </td>
                                        </tr>
                                <?php }?>
                        </tbody>
                    <?php }?>

                </table>
                <center style="clear:both;">
                    <a class="wButton redwB ml15 m10 submit annul" class="reset" title="" href="#">
                        <span>Аннулировать</span>
                    </a>
                    <a class="wButton greenwB ml15 m10 submit"  title="" href="#"><span>Найти</span></a>
                </center>
                <?php }else{
                    echo "<center>".(empty($inqueries_table)?'':$inqueries_table)."</center>";
                ?>
                    <center>
                        <a class="wButton greenwB ml15 m10 submit" title="" href="#"><span>Найти</span></a>
                    </center>
                <?php
                };?>
            </div>
    </fieldset>
</form>
<script>
    $(function(){
        $('.submit').click(function(){

            if( $(this).hasClass('annul') ) submit( 'opera/<?= $controller ?>/annul/annul' );
            else
                submit( 'opera/<?= $controller ?>/annul' );
            return false;
        });

        function submit( href ){
            $('#validate').attr('action',href)
                        .submit();

        };

        $('input[type=checkbox]').click(function(){
            if( $(this).hasClass('checked') ){
                $(this).prop('checked', true).val('on');
            }else{
                $(this).prop('checked', false).val('');
            }
        });
        $('#inqueries_t').click(function(){
            if( $(this).parent().hasClass('checked') ){
                $('.inqueries_t').each(function(){
                    $(this).prop('checked', true).val('on');
                    $(this).parent().addClass('checked');
                 });
            }else{
                $('.inqueries_t').each(function(){
                    $(this).prop('checked', false).val('');
                    $(this).parent().removeClass('checked');
                 });
            }
        });
        $('#inqueries_i').click(function(){
            if( $(this).parent().hasClass('checked') ){
                $('.inqueries_i').each(function(){
                    $(this).prop('checked', true).val('on');
                    $(this).parent().addClass('checked');
                 });
            }else{
                $('.inqueries_i').each(function(){
                    $(this).prop('checked', false).val('');
                    $(this).parent().removeClass('checked');
                 });
            }
        });
        $('#transactions_t').change(function(){
             if( $(this).parent().hasClass('checked') ){
                $('.transactions_t').each(function(){
                    $(this).prop('checked', true).val('on');
                    $(this).parent().addClass('checked');
                 });
            }else{
                $('.transactions_t').each(function(){
                    $(this).prop('checked', false).val('');
                    $(this).parent().removeClass('checked');
                 });
            }
        });
        $('#transactions_i').change(function(){
             if( $(this).parent().hasClass('checked') ){
                $('.transactions_i').each(function(){
                    $(this).prop('checked', true).val('on');
                    $(this).parent().addClass('checked');
                 });
            }else{
                $('.transactions_i').each(function(){
                    $(this).prop('checked', false).val('');
                    $(this).parent().removeClass('checked');
                 });
            }
        });
    });
</script>
