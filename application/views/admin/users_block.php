<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/>

<form id="validate" class="form" action="/opera/users/users_block"  method="post">
    <input type="hidden" id="submited" name="submited" value="1"/>
    <fieldset>
        <div class="widget">
            <div class="title">
                <img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>IP адреса:</h6>
            </div>            
            <div class="formRow">
                <label>IP:</label>
                <div class="formRight" style="margin-bottom: 7px;">
                    <textarea name="users"><?= $entery_users; ?></textarea>
                </div>
                <div class="clear"></div>
                <?php if( is_array($users_table) && count($users_table) > 0 ){ ?>
                <input type="hidden" name="block" value="true">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td colspan="6" style="text-align: center">Клиент</td>
                            <td colspan="8" style="text-align: center">Транзакция</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="users_i" checked=""/>
                            </td>
                            <td>id</td>
                            <td>ФИО</td>
                            <td>Email</td>
                            <td>Статус</td>                                                        
                            <td>IP reg/ IP address</td>                                                        

                            <td>id</td>
                            <td>Метод</td>
                            <td>IP</td>
                            <td>Коммент</td>
                            <td>Бон.</td>
                            <td>Сум.</td>
                            <td>Статус</td>
                            <td>
                                <!--<input type="checkbox" id="transactions_t"/>-->
                            </td>
                        </tr>
                    </thead>

                    <?php foreach( $users_table as $group ){?>
                        <tbody>
                            
                            <? $i = 0;
                            foreach( $group[2] as $val ){ 
                                $i++;
                                ?>
                                <tr>
                                    <? if( $i < 2 ){ ?>
                                        <td>
                                            <? if( $group[1]->null == 0 ){ ?>
                                            <input type="checkbox" name="u<?=$group[1]->id_user;?>" class="users_i" checked=""/>
                                            <?}?>
                                        </td>
                                        <td><?= $group[1]->id_user ?></td>
                                        <td><?= $group[1]->full_name ?></td>
                                        <td><?= $group[1]->email ?></td>
                                        <td><img src="/images/icons/<?= $group[1]->state_img ?>.png" /></td>
                                        <td><?= $group[1]->ips ?></td>                                        
                                    <? }else{ ?>
                                        <td colspan="6"></td>
                                    <? } ?>

                                    <? if( !empty( $val ) ){  ?>
                                        <td><?= $val->id ?></td>
                                        <td><?= $val->metod ?></td>
                                        <td><?= $val->user_ip ?></td>
                                        <td><?= $val->note ?></td>
                                        <td><?= ($val->bonus == 1?'да': ($val->bonus == 2?'нет':$val->bonus) ) ?></td>
                                        <td><?= $val->summa ?></td>
                                        <td><?= getTransactionLabelStatus($val->status) ?></td>
                                        <td>
                                            <!--<input type="checkbox" name="t<?=$group[2]->id;?>" class="transactions_t"/>-->
                                        </td>
                                    <? }else{?>
                                        <td colspan="8"></td>
                                    <? }?>
                                </tr>
                            <? } ?>
                            
                        </tbody>
                    <?php }?>

                </table>                
                <?php } ?>

            </div>            
            <div class="formRow">
                <label><input type="checkbox" name="only_one" value="true"/>Отображать 1 транзакцию пользователя</label>                
            </div>
            <div class="formRow">
                <label>Причина блокировки:</label>
                <div class="formRight" style="margin-bottom: 7px;">
                    <textarea name="block_reason"><?= $entery_block_reason; ?></textarea>
                </div>
                <div class="clear"></div>                
                <?php if( is_array($users_table) && count($users_table) > 0 ){ ?>
                    <center style="clear:both;">
                        <a class="wButton redwB ml15 m10 submit annul" class="reset" title="" href="#">
                            <span>Заблокировать</span>
                        </a>
                        <a class="wButton greenwB ml15 m10 submit"  title="" href="#"><span>Найти</span></a>
                    </center>
                <?php }else{
                    echo "<center>".(empty($users_table)?'':$users_table)."</center>";
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

            if( $(this).hasClass('annul') ) submit( '/opera/users/users_block/block' );
            else
                submit( '/opera/users/users_block' );
            return false;
        });

        function submit( href ){
            $('#validate').attr('action',href)
                        .submit();

        };

        
        $('#users_i').click(function(){
            if( $(this).parent().hasClass('checked') ){
                $('.users_i').each(function(){
                    $(this).prop('checked', true).val('on');
                    $(this).parent().addClass('checked');
                 });
            }else{
                $('.users_i').each(function(){
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
        
    });
</script>
