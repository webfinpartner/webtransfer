<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('/')?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?=base_url()?>js/admin/jeasyui/jquery.easyui.min.js"></script>
<style>
    .formDicableUser{
        color: red;
    }
</style>
<div class="easyui-dialog" id="trans_error_dialog" collapsible="true" resizable="false" closed="true"  style="padding:5px;width:450px;height:150px;" title="Указать ошибку" data-options="buttons:[{
				text:'Применить',
				handler:function(){
                                    var error_text = $('#error_text').combobox('getText');
                                    if ( error_text == '') return;
                                    $('#note').val( $('#note').val() + 'Ошибка: '+ error_text);
                                    $('select[name=status] option').removeAttr('selected');
                                    $('select[name=status] option[value=6]').attr('selected','selected');
                                    $('#validate').submit();
                                }
			},{
				text:'Отмена',
				handler:function(){  $('#trans_error_dialog').dialog('close');}
			}]">
    Выберите ошибку или введите свой текст:<br>
    <input name="error_text" id="error_text" class="easyui-combobox" style="width: 100%" data-options="valueField: 'id',textField: 'label',data: [
                <? foreach( $transaction_errors as $te) { ?>
                    {'id': <?=$te->id?>,'label': '<?=$te->error_name?>'},
                <? } ?>
           ],keyHandler: {
                up: function(e){},
                down: function(e){},
                left: function(e){},
                right: function(e){},
                enter: function(e){ var text = $('#error_text').combobox('getText'); console.log(text);  $('#error_text').combobox('hidePanel'); return false; },
                query: function(q,e){}
        }">
</div>

<div class="easyui-dialog" id="warn_dialog" collapsible="true" resizable="false" closed="true" title="Найдены одинаковые кошельки"  style="padding:5px;width:450px;height:250px;">
    <table class="easyui-datagrid" style="width:100%;height:200px" data-options="fitColumns:true,singleSelect:true">
    <thead>
        <tr>
            <th data-options="field:'id_user',width:80">USERID</th>
            <th data-options="field:'fio',width:200">ФИО</th>
            <th data-options="field:'email',width:100">EMAIL</th>
            <th data-options="field:'phone',width:100">Телефон</th>
        </tr>
    </thead>
    <?if ( !empty($wallet_warns) ){ ?>
    <tbody>
        <? foreach ($wallet_warns as $user ){ ?>
        <tr>
            <td><?=$user->id_user?></td>
            <td><a href="/opera/users/<?=$user->id_user?>" target="_blank"><?=$user->sername?> <?=$user->name?> <?=$user->patronymic?></a></td>
            <td><?=$user->email?></td>
            <td><?=$user->phone?></td>
        </tr>
         <? }?>
    </tbody>
    <? }?>
    </table>


</div>

<form id="validate" class="form" action="<?php base_url()?>opera/<?=$controller?>/<?=($state=='create')?"create":"$state"?>"  method="post">
    <input type="hidden" id="submited" name="submited" value="1"/>
    <input type="hidden" id="submited" name="back_url" value="<?=$_SERVER['HTTP_REFERER']?>"/>
    <fieldset>
        <div class="widget<?=(($is_user_active) ? "" : " formDicableUser")?>">
            <div class="title">
                <img class="titleIcon" alt="" src="images/icons/dark/list.png">
                <h6>Транзакция №<?=$item->id?> <? if('wt' == $item->metod && 75 == $item->type){?> (Проверено, ожидает ввод кода)<?}?></h6>
                <a style="margin: 4px 4px; float: right; " target="_blank" class="button blueB" title="" href="/opera/users/<?=$item->id_user?>"><span>Профиль</span></a>
                
                <?php if( $item->status == 3 && (74 == $item->type) && $item->bonus == 2 ){?>
                <a id="content_admin_payment_doc" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="<?= "/opera/$controller/get_payment_doc/$item->id" ?>" target="_balanc">
                        <span>Платежное поручение</span>
                    </a>
                <?php }?>
                
                <?php if($state!='create'){?>                
		<? if ($this->admin_info->permission == 'admin') { ?>
                    <a id="content_admin_payment_delete" style="margin: 4px 4px; float: right; " class="button redB del" title="" href="/opera/<?=$controller?>/delete/<?=$state?>"><span>Удалить</span></a><? } ?>
                <?php }?>
                <?php if( is_numeric($state) && in_array($status, array(4, 11) ) && (74 != $item->type)){?>
                    <a id="content_admin_payment_payoutConferm" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="" onclick="return false;">
                        <span>Оплатить</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payoutConferm").click(function(){
                                $("#validate").attr("action", "/opera/<?=$controller?>/payoutConferm/<?=$state?>");
                                $("#validate").submit();
                            });
                        });

                    </script>
                <?php }?>                

                <?php if( is_numeric($state) && in_array($status, array(4, 11) ) && strpos( $item->note, 'Bank' ) !== FALSE ){?>
                    <a style="margin: 4px 4px; float: right; " class="button greenB" title="" href="/opera/payment/wire_bank_pay/<?= $item->id ?>">
                        <span>Отправить емайлы Wire</span>
                    </a>
                    <a style="margin: 4px 4px; float: right; " class="button greenB" title="" target="_blank" href="/opera/payment/wire_bank_print/<?= $item->id ?>">
                        <span>Печать</span>
                    </a>
                    <a style="margin: 4px 4px; float: right; " class="button redB" title="" onclick="$('#trans_error_dialog').dialog('open');return false;" href="#">
                        <span>Ошибка</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payoutConferm").click(function(){
                                $("#validate").attr("action", "/opera/<?=$controller?>/payoutConferm/<?=$state?>");
                                $("#validate").submit();
                            });
                        });

                    </script>
                <?php }?>
                <? if($status == 2 && 'bank' == $item->metod && 15 == $item->type){?>
                    <a id="content_admin_payment_payout_сonfermInvestArbitrage" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="" onclick="return false;">
                        <span>Зачислить арбитр. вклад</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payout_сonfermInvestArbitrage").click(function(){
                                window.location = "/opera/<?=$controller?>/confermInvestArbitrage/<?=$item->id?>";
                            });
                        });

                    </script>
                <?}?>
                <? if('wt' == $item->metod && (74 == $item->type)){?>
                    <a id="content_admin_payment_payout_discardSendMoney" style="margin: 4px 4px; float: right; " class="button redB" title="" href="" onclick="return false;">
                        <span>Отклонить перевод</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payout_discardSendMoney").click(function(){
                                window.location = "/opera/<?=$controller?>/discardSendMoney/<?=$item->id?>";
                            });
                        });

                    </script>
                    <a id="content_admin_payment_payout_сonfermSendMoney" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="" onclick="return false;">
                        <span>Подтвердить перевод</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payout_сonfermSendMoney").click(function(){
                                window.location = "/opera/<?=$controller?>/confermSendMoney/<?=$item->id?>";
                            });
                        });

                    </script>
                <?}?>
                <? if('wt' == $item->metod && (40 == $item->type)){?>
                    <a id="content_admin_payment_payout_discardMerchant" style="margin: 4px 4px; float: right; " class="button redB" title="" href="" onclick="return false;">
                        <span>Отклонить перевод</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payout_discardMerchant").click(function(){
                                window.location = "/opera/<?=$controller?>/discardMerchant/<?=$item->id?>";
                            });
                        });

                    </script>
                    <a id="content_admin_payment_payout_confermMerchant" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="" onclick="return false;">
                        <span>Подтвердить перевод</span>
                    </a>
                    <script>
                        $(document).ready(function(){
                            $("#content_admin_payment_payout_confermMerchant").click(function(){
                                window.location = "/opera/<?=$controller?>/confermMerchant/<?=$item->id?>";
                            });
                        });

                    </script>
                <?}?>
                    
                <?if ( !empty($wallet_warns) ){ ?>
                    <a style="margin: 4px 4px; float: right; " class="button redB" title="" onclick="$('#warn_dialog').dialog('open');return false;" href="#">
                        <span>!</span>
                    </a>
                <? } ?>
            </div>
            <div class="formRow">
                <label>Пользователь<?=(($is_user_active) ? "": " (заблокирован)")?>:<span class="req">*</span></label>
                <div class="formRight"><input type="text" id="title" class="validate[required]" name="id_user" value="<?=@$item->id_user?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Сумма:<span class="req">*</span></label>
                <div class="formRight"><input type="text"  class="validate[required]" id="mc_amount"  name="summa"  value="<?=@$item->summa?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Метод пополнения:</label>
                <div class="formRight"> <?=  form_dropdown('metod', getTransactionsMethodLabel(false, true), @$item->metod)?> <?if(!empty($item->metod) and ($item->metod =='bank' or $item->metod == 'bank_norvik' or $item->metod == 'bank_raiffeisen')){?>
                        <a class="wButton greenwB ml15"
                           target="_blank" href="/opera/payment/get_bank_check/<?=@$item->id_user?>/<?=@$item->id?>"><span>Распечатать</span></a>
                    <?}?></div>

                <div class="clear"></div>
            </div>
            <div class="formRow" id="form_mail">
                <label>Транзакция:<span class="req">*</span></label>
                <div class="formRight"><input type="text" id="note" class=""  name="note" value="<?=@$item->note?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow" id="form_mail">
                <label>Заметка админа:<!--<span class="req">*</span>--></label>
                <div class="formRight"><input type="text"  class=""  name="note_admin" value="<?=@$item->note_admin?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow" id="form_mail">
                <label>Значение:</label>
                <div class="formRight"><input type="text"  id="tran_val" class=""  name="value" value="<?=@$item->value?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow" id="form_mail">
                <label>Тип:</label>
                <div class="formRight"><input type="text" id="tran_type"  class=""  name="type" value="<?=@$item->type?>"></div>
                <div class="clear"></div>
            </div>

            <? if (strripos($item->note, "Быстрый платеж.")){ ?>
            <div class="formRow" id="form_mail">
                <label>Оплатить через:<span class="req"></span></label>
                <div class="formRight">
                    <select name="payout_system" id="pay_system" class="form_select" style="width:375px;">
                        <? renderSelectKey($payout_systems, $pay_type);?>
                    </select>
                </div>
                <div class="clear"></div>
                <script>
                    $("#pay_system").change(function(){
                        persent();
                    });
                    function persent(){
                        if("select" == $("#pay_system").val() || "no_data" == $("#pay_system").val()) return;
                        var st = <?=json_encode($payment_types);?>;
                        var s = <?=json_encode($payuot_systems_psnt);?>;
                        var u = <?=json_encode($user_data);?>;
                        var cur = s[$("#pay_system").val()];
                        var val = $("#mc_amount").val();
                        var ntype = st[$("#pay_system").val()];

                        var psnt = val*cur.psnt/100;
                        if (psnt < cur.min) psnt = cur.min;
                        psnt = psnt + cur.add;
                        $("#tran_type").val(ntype);
                        $("#tran_val").val(ntype);

                        var payment_info = '';
                        if('bank_cc' == $("#pay_system").val() && <?=((isset($user_data->bank_cc_date_off)) ? "true": "false")?>){
                            <?$countris = get_country();?>
                            payment_info = $("[value = "+$("#pay_system").val()+"]").html()+" "
                                    + u[$("#pay_system").val()]
                                    + ' EXP ' + u.bank_cc_date_off + ' <?=@$countris[$user_data->place]?> '
                                    + " (комисия $ "+psnt+")"
                                    + ". Быстрый платеж. (Платежная система изменена для ускорения вывода средств)";
                        } else {
                            payment_info = $("[value = "+$("#pay_system").val()+"]").html()+" "
                                    + u[$("#pay_system").val()]
                                    + " (комисия $ "+psnt+")"
                                    + ". Быстрый платеж. (Платежная система изменена для ускорения вывода средств)";
                        }

                        $("#note").val('Заявка на вывод средств: ' + payment_info);
                    }
                </script>
            </div>
            <?}?>
            <div class="formRow" id="form_mail">
                <label>Бонус:<span class="req">*</span></label>
                <div class="formRight"> <?=form_dropdown('bonus', getBonusLabel(), @$item->bonus)?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow" id="form_mail">
                <label>Дата (гггг-мм-дд):<span class="req">*</span></label>
                <div class="formRight"><input type="text"  class=""  name="date" value="<?=((@$item->date) ? @$item->date : date("Y-m-d H:i:s"))?>"></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Статус:</label>
                <div class="formRight">
                    <?=  form_dropdown('status', getTransactionLabelStatus(), @$item->status)?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Собственные средства: </label>
                <div class="formRight">$<?= $user_data->balance['net_funds'] ?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Денег на счету пользователя:</label>
                <div class="formRight">Платежный: $ <?=$money?> | Платежный 5: $ <?=$money5?>  | C-CREDS: <?= $user_data->balance['c_creds_total'] ?> | P-CREDS: <?= $user_data->balance['partner_funds'] ?> (Остаток после проведения данной транзакции. Не проводить если минус</div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Введено денег пользователем:</label>
                <div class="formRight">$ <?=(($in)?$in:"0")?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Поучено по переводам:</label>
                <div class="formRight">$ <?=(($sendmoney)?$sendmoney:"0")?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <label>Выведено денег пользователем:</label>
                <div class="formRight">$ <?=(($out)?$out:"0")?></div>
                <div class="clear"></div>
            </div>

            <div class="formRow">
                    <label>Введено денег :</label>
                    <div class="formRight">
                            <table width="100%">
                                    <tbody>
                                    <tr>
                                            <td>Liqpay</td>
                                            <td>Payeer (кошелек)</td>
                                            <td>Payeer (все)</td>
                                            <td>Egopay</td>
                                            <td>OkPay</td>
                                            <td>Perfect</td>
                                            <td>Paypal</td>
                                            <td>Lava</td>
                                            <td>Bank</td>
                                    </tr>
                                    <tr>
                                            <td>$ <?= (($in_liqpay) ? $in_liqpay : "0") ?></td>
                                            <td>$ <?= (($in_payeer) ? $in_payeer : "0") ?></td>
                                            <td>$ <?= (($in_payeerall) ? $in_payeerall : "0") ?></td>
                                            <td>$ <?= (($in_egonet) ? $in_egonet : "0") ?></td>
                                            <td>$ <?= (($in_okpay) ? $in_okpay : "0") ?></td>
                                            <td>$ <?= (($in_perfectmoney) ? $in_perfectmoney : "0") ?></td>
                                            <td>$ <?= (($in_paypal) ? $in_paypal : "0") ?></td>
                                            <td>$ <?= (($in_lava) ? $in_lava : "0") ?></td>
                                            <td>$ <?= (($in_bank) ? $in_bank : "0") ?></td>
                                    </tr>
                                    </tbody>
                            </table>
                            <br/><br/></div>
                    <div class="clear"></div>
            </div>
            <div class="formRow">
                    <label>Выведено денег :</label>
                    <div class="formRight">
                            <table width="100%">
                                    <tbody>
                                    <tr>
                                            <td>Liqpay out</td>
                                            <td>Payeer out (кошелек)</td>
                                            <td>Payeer out (все)</td>
                                            <td>Egopay out</td>
                                            <td>OkPay out</td>
                                            <td>Perfect out</td>
                                            <td>Paypal out</td>
                                            <td>Lava out</td>
                                            <td>Bank out</td>
                                    </tr>
                                    <tr>
                                            <td>$ <?= (($out_liqpay) ? $out_liqpay : "0") ?></td>
                                            <td>$ <?= (($out_payeer) ? $out_payeer : "0") ?></td>
                                            <td>$ <?= (($out_payeerall) ? $out_payeerall : "0") ?></td>
                                            <td>$ <?= (($out_egonet) ? $out_egonet : "0") ?></td>
                                            <td>$ <?= (($out_okpay) ? $out_okpay : "0") ?></td>
                                            <td>$ <?= (($out_perfectmoney) ? $out_perfectmoney : "0") ?></td>
                                            <td>$ <?= (($out_paypal) ? $out_paypal : "0") ?></td>
                                            <td>$ <?= (($out_lava) ? $out_lava : "0") ?></td>
                                            <td>$ <?= (($out_bank) ? $out_bank : "0") ?></td>
                                    </tr>
                                    </tbody>
                            </table>
                            <br/><br/></div>
                    <div class="clear"></div>
            </div>
            <div class="formRow">
                    <label>Транзакции USD2:</label>
                    <div class="formRight">
                            <table width="100%">
                                    <tbody>
                                    <tr>
                                            <td>+ Пополн.</td>
                                            <td>+ Вход (вкл. P2P)</td>
											 <td>USD1-USD2</td>
											 <td>+ Мерчант</td>
                                            <td>+ Partner</td>
											<td>+ Bonus</td>
											<td>+ Arbik</td>
                                            <td>- Вывод</td>
                                            <td>- Отправил: (вкл. P2P)</td>
                                            <td>Balans</td>
											<td>OUT_BAL</td>
                                    </tr>
                                    <tr>
									
									        <td>$ <?=$user_data->balance['money_sum_add_funds_by_bonus'][2]?></td>
                                            <td>$ <?=$user_data->balance['money_sum_transfer_from_users_by_bonus'][2]?></td>
											<td>$ <?= $user_data->balance['transfered_summ_from_bonus_5_to_2'] ?></td>
											 <td>$ <?=$user_data->balance['income_merchant_send_by_bonus'][2]?></td>
                                            <td>$ <?=$user_data->balance['sum_partner_reword_by_bonus'][2]?></td>
											<td>$ <?= $user_data->balance['bonus_earned_by_bonus'][2] ?></td>
											<td>$ <?= $user_data->balance['arbitr_inout_by_bonus'][2] ?></td>
                                            <td>$ <?=$user_data->balance['money_sum_withdrawal_by_bonus'][2]?></td>
                                            <td>$ <?=$user_data->balance['money_sum_transfer_to_users_by_bonus'][2]?></td>
                                            <td>$ <?=$user_data->balance['payment_account_by_bonus'][2]?></td>
											<td>$ <?=( 
											($user_data->balance['money_sum_add_funds_by_bonus'][2] - $user_data->balance['transfered_summ_from_bonus_2_to_6'])* 1.5
											- $user_data->balance['transfered_summ_from_bonus_2_to_6']
											+ $user_data->balance['income_merchant_send_by_bonus'][2]
											+ $user_data->balance['sum_partner_reword_by_bonus'][2]
											+ $user_data->balance['bonus_earned_by_bonus'][2]
											+ $user_data->balance['arbitr_inout_by_bonus'][2]
											- $user_data->balance['money_sum_withdrawal_by_bonus'][2]
											- $user_data->balance['money_sum_transfer_to_users_by_bonus'][2]
											)?></td>
											
											
                                    </tr>
                                    </tbody>
                            </table>
                            <br/><br/></div>
                    <div class="clear"></div>
            </div>

			<div class="formRow">
                    <label>Транзакции WT DEBIT:</label>
                    <div class="formRight">
                            <table width="100%">
                                    <tbody>
                                    <tr>
                                            <td>+ Пополн.</td>
                                            <td>+ Вход (вкл. P2P)</td>
											 <td>USD2-USD6</td>
											 <td>+ Мерчант</td>
                                            <td>+ Partner</td>
											<td>+ Bonus</td>
											<td>+ Arbik</td>
                                            <td>- Вывод</td>
                                            <td>- Отправил: (вкл. P2P)</td>
                                            <td>Balans</td>
											<td>OUT_BAL</td>
                                    </tr>
                                    <tr>
									
									        <td>$ <?= $debit_rating->net_inn + $user_data->balance['money_sum_add_funds_by_bonus'][6] ?></td>
                                            <td>$ <?=$user_data->balance['money_sum_transfer_from_users_by_bonus'][6]?></td>
											<td>$ <?= $user_data->balance['transfered_summ_from_bonus_2_to_6'] - $debit_rating->net_inn ?></td>
											 <td>$ <?=$user_data->balance['income_merchant_send_by_bonus'][6]?></td>
                                            <td>$ <?=$user_data->balance['sum_partner_reword_by_bonus'][6]?></td>
											<td>$ <?= $user_data->balance['bonus_earned_by_bonus'][6] ?></td>
											<td>$ <?= $user_data->balance['arbitr_inout_by_bonus'][6] ?></td>
                                            <td>$ <?=$user_data->balance['money_sum_withdrawal_by_bonus'][6]?></td>
                                            <td>$ <?=$user_data->balance['money_sum_transfer_to_users_by_bonus'][6]?></td>
                                            <td>$ <?=$user_data->balance['payment_account_by_bonus'][6]?></td>
											<td>$ <?=( 
											($user_data->balance['money_sum_add_funds_by_bonus'][6]+$user_data->balance['money_sum_add_funds_by_bonus'][2]) * 1.5 
											+ $user_data->balance['income_merchant_send_by_bonus'][6] + $user_data->balance['income_merchant_send_by_bonus'][2]
											//+ $user_data->balance['sum_partner_reword_by_bonus'][6] + $user_data->balance['sum_partner_reword_by_bonus'][2]
											//+ $user_data->balance['bonus_earned_by_bonus'][6] + $user_data->balance['bonus_earned_by_bonus'][2]
											+ $user_data->balance['arbitr_inout_by_bonus'][6] + $user_data->balance['arbitr_inout_by_bonus'][2]
											- $user_data->balance['money_sum_withdrawal_by_bonus'][6] - $user_data->balance['money_sum_withdrawal_by_bonus'][2]
											- $user_data->balance['money_sum_transfer_to_users_by_bonus'][6] - $user_data->balance['money_sum_transfer_to_users_by_bonus'][2]
											)?></td>
											
											
                                    </tr>
                                    </tbody>
                            </table>
                            <br/><br/></div>
                    <div class="clear"></div>
            </div>
			
            <? if($double) {?>
            <div class="formRow">
                    <label>Повторяющиеся транзакции:</label>
                    <div class="formRight">
                            <table width="100%">
                                    <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td>Заметка</td>
                                        <td>Сумма</td>
                                        <td>Повторений</td>
                                    </tr>
                                    <? foreach ($double as $row) {?>
                                    <tr>
                                        <td><?=$row->id?></td>
                                        <td><?=$row->note?></td>
                                        <td><?=$row->summa?></td>
                                        <td><?=$row->c?></td>
                                    </tr>
                                        <?}?>
                                    </tbody>
                            </table>
                            <br/><br/>
                    </div>
                    <div class="clear"></div>
            </div>
            <?}?>
            <?php if( is_numeric($state) && 4 == $status &&  (74 != $item->type and 75 != $item->type)){?>
            <div class="formRow">
                <label>Выбирите систему для вывода:<br/>
                <a id="content_admin_payment_payout_payoutVerify" style="margin: 4px 4px; float: right; " class="button greenB" title="" href="" onclick="return false;">
                    <span>Отправить на оплату</span>
                </a>
                <script>
                    $(document).ready(function(){
                        $("#content_admin_payment_payout_payoutVerify").click(function(){
                            if(!$("[name='payout_type']").is(":checked")){
                                alert("Выберете систему для оплаты!");
                                return false;
                            }
                            $("#validate").attr("action", "/opera/<?=$controller?>/payoutVerify/<?=$state?>");
                            $("#validate").submit();
                        });
                    });

                </script>
                </label>
                <div class="formRight">

                    <input type="radio" name="payout_type" value="312"><label for="payout_type">Payeer</label>
                    <input type="radio" name="payout_type" value="319"><label for="payout_type">OK Pay</label>
                    <input type="radio" name="payout_type" value="318"><label for="payout_type">Perfectmoney</label>
                    <input type="radio" name="payout_type" value="328"><label for="payout_type">WTCard</label>
                    <input type="radio" name="payout_type" value="75"><label for="payout_type">EX-WT</label>

                </div>
<!--                <div class="formRight">
                    <table class="display">
                        <tr>
                            <th>IP</th>
                            <th>Пользователь (родитель) /<br/> Заметка /<br/> Займ</th>
                            <th>Пользователи с этого же IP</th>
                            <th>Кол-во польз.</th>
                            <th>Тип</th>
                        </tr>
                <? // foreach ($ips as $row) {
//                    $ip = str_replace('.', "_", $row->ips);
//                    $users_id = explode(',', $row->id_user);
//                    $all_users = count($users_id);
//                        if(1 == $all_users && 0 == $users_id[0]) $all_users = '-';
//                    $users_ = ipWork($row,$ip_white,$ip_firewall);
//                    $users = $users_[0];
//                    $shot_users = $users_[1];
                    ?>
                        <tr>
                            <td><a href='/opera/users/users_block/< ?=$row->ips? >' target='_blank'>< ?=$row->ips?></a></td>
                            <td>< ?=$row->note? ></td>
                            <td>< ?=((empty($shot_users)) ? $users : $shot_users)?>
                                < ?if(!empty($shot_users)){?>
                                <div id="< ?=$ip?>" style="display: none">
                                < ?= $users?>
                                </div>
                                <script>
                                    $("#< ?=$ip?>").dialog({
                                        autoOpen: false,
                                        height: 400,
                                        width: 300,
                                        dialogClass: "alert",
                                        title:"Все пользователи с IP < ?=$row->ips?>."});
                            </script>< ?}?>
                            </td>
                            <td>< ?=$all_users?></td>
                            <td>< ?=$row->info?></td>
                        </tr>
                < ?}?>
                    </table>
                </div>-->
                <div class="clear"></div>
            </div>
            <?}?>
            <div class="formRow">
                <label>Заметки админа: </label>
                <div class="formRight"><? foreach ($admin_note as $note) {?>
                        <p><?=date("d.m.Y", strtotime($note->date))?> (<?=$note->id_admin?>): <?=$note->note?></p>
                        <?}?></div>
                <div class="clear"></div>
            </div>
        </div>
    </fieldset>
    <center>
        <?php if($state!='create'){?><a class="wButton redwB ml15 m10" id="reset" title="" href="/opera/page/all"><span>Отменить</span></a><?php }?>
		<? //if ($this->admin_info->permission == 'admin') { ?>
        <a class="wButton greenwB ml15 m10" onclick="$('#validate').submit(); return (false)" title="" href="#"><span>Сохранить</span></a>
		<? //} ?>
    </center>
</form>
