<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?
    $out_summ = $item->out_summ;
    $income = $item->income;
    if ( $item->direct == 1 && strtotime($item->out_time) <= strtotime(date('Y-m-d')) && strtotime($item->out_time) > 0  ){
        $time_days = calculate_debit_time_now( $item->date_kontract );
        $out_summ = credit_summ($item->percent, $item->summa, $time_days);
        $income = $out_summ - $item->summa;
    }
?>
<div class="toggle">
    <div class="title" <? if (  strtotime($item->out_time) > 0 && $item->state == 2 && strtotime($item->out_time) < strtotime(date('Y-m-d')) ) {?>style="background-color: #ffcdcd;"<?}?>>
	<? if ($item->garant == 1) { ?>
                <? if ($item->direct == 0) { ?>
                    <img class="titleIcon" src="/images/garant_stat1.png"  style="height:14px; width:14px;"alt=""/>
                <? }elseif ($item->garant == 1 && $item->direct == 1) { ?>
                    <img class="titleIcon" src="/images/garant-direct.png"  style="height:14px; width:14px;"alt=""/>
                <? }?>
            <? }elseif ($item->garant == 0) { ?>
                    <? if ($item->arbitration == 0 && $item->direct == 0){ ?>
                        <img class="titleIcon" src="/images/garant_stat0.png" style="height:14px; width:14px;"/>
                    <? }elseif ($item->direct == 1){ ?>
                        <img class="titleIcon" src="/images/standart-direct.png" style="height:14px; width:14px;" alt=""/>
                    <? }elseif ($item->arbitration == 1){ ?>
                        <img class="titleIcon" src="/images/arbitration.png"  style="height:14px; width:14px;" alt=""/>
                    <? } ?>
            <? } ?>
                <h6>
		<? if ($item->bonus == 9){ ?>
		<?=_e('Информация о Гарантии')?>
		<? }  else { ?>
		<?=_e('application_info')?>
		<? } ?>
		</h6>

        <div class="garant_state">
        </div>

        <div class="date"><?= "#" . $item->id . " " . date_formate_my($item->date, 'd/m/Yг.'); ?></div>
        <?
        $offert_borrower = ''; $offert_investor  = ''; $payment_to_borrower = ''; $return_to_investor  = ''; $borrower_id  = '';
        $borrower_another = ''; $certificate = '';
        //credit
		if ($item->garant == Base_model::CREDIT_GARANT_ON && $item->state == 2 && $item->bonus == 9){
    $certificate = "data-certificate='".site_url('account/getcertificate')."/".$item->id."'";
}
        if ($item->state > 1 &&
                $item->arbitration == Base_model::CREDIT_ARBITRATION_OFF &&
                $item->garant == Base_model::CREDIT_GARANT_OFF  && $item->bonus != 9) {
            $offert_borrower = "data-ofert_borrower=".site_url('account')."/credit-doc-".$item->id .".pdf";
        } ?>
        <? if(!isset($archive_page) && $item->bonus != 9){

            //ofert
            $offert_investor = "data-ofert_investor='".site_url('account')."/ofert-{$item->id}.pdf'";

            //cheques
            if( $item->state > 1 && $item->confirm_payment == 1 && $item->bonus != 9 )
            {
                $payment_to_borrower = "data-payment_to_borrower='".site_url('account')."/paymentdoc-{$item->id}.pdf'";
            }
            if( $item->state == Base_model::CREDIT_STATUS_PAID && $item->confirm_return == 1  && $item->bonus != 9)
            {
                $return_to_investor = "data-return_to_investor='".site_url('account')."/paymentdoc-{$item->debit}.pdf'";
            }
            ?>


        <?}?>
        <a class="detailed-link" href="javascript" <?= $offert_borrower.' '. $offert_investor .' '.$payment_to_borrower.' '. $return_to_investor .' '.$borrower_id.' ' . $certificate . ' '.$borrower_another; ?> >
            <img class="pdf_image"  src="/images/note.png" title="Datailed docs" style="margin-top: 6px;margin-left: 10px;"/>
        </a>

        <div class="buttons">
            <?= accaunt_debit_status($item->state) ?>
            <? if ($item->state == 1) { ?>
                <a class="delete_button" href="<?=site_url('account/delete_credit')?>/<?= $item->id ?>" onclick="return yes_no_debit()">
                    <span><?=_e('delete')?></span>
                </a>
            <? } ?>
        </div>
    </div>
    <div class="body">
        <fieldset style="width:100%" class="blank_info" >
            <div class="formRow">
                <label><?=_e('amount')?> </label>
                <div class="formRight">
                    <span class="twoFour" style="width:130px; float: left;height: 15px;">
                        <div>$ <?= price_format_double($item->summa) ?></div>
                        <?= ($item->arbitration ? "<div style='font-size:11px'>(". _e('arbitrage').")</div>" : '') ?>
                    </span>
                    <span class="twoFour" style="width:130px;font-size:15px;text-align:center; display: inline-block">
                        <? if ( $item->direct == 1 && $item->bonus==6) {?>
                        <img style="vertical-align: middle" src="<?= Card_model::get_own_account_icon($item->account_id) ?>"><span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Счет: ')?> <?=Card_model::display_own_account_name($item->account_id)?>">(?)</span><br/>
                            <span style='font-size:11px'>(<?= _e('счет') ?>)</span>

                        <? } else { ?>
                           <? if ( $item->bonus==6) {?>
                                <? if ($item->blocked_money == '9') { ?>

                                    <img style="vertical-align: middle" src="/images/giftico.png"><br/>
                                <? } else { ?>
                                    <img style="vertical-align: middle" src="/images/debit.png"><br/>
                                <? } ?>
                            <? }  else { ?>
                                <?= _e('wt_currency_'.$item->bonus) ?><br/>
                            <? } ?>
                            <span style='font-size:11px'>(<?= _e('currency') ?>)</span>
                            <? if ( $item->direct == 1 && $item->bonus==7) {?>
                                <span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Карта: ')?> <?=Card_model::display_card_name($item->card_id, TRUE)?>">(?)</span>
                            <? } ?>
                        <? } ?>

                    </span>
                    <span class="oneFour" style="width:auto;"></span>
                    <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= creditPayment($item->payment); ?> -->
                </div>
            </div>

            <div class="formRow last">
               <? if ($item->bonus == 9){ ?>
		<label><?=_e('Детали')?></label>
		<? }  else { ?>
		<label><?=_e('application_detail')?></label>
		<? } ?>
                <div class="formRight biger">
                    <span class="oneFour" style="width:130px;"><span><?= $item->time ?></span><br/><?=_e('application_date')?></span>
                    <span class="oneFour" style="width:135px;"><span><?= $item->percent ?>%</span><br/><? if ($item->bonus == 9){ ?>
					<?=_e('Ставка всего')?>
					<? }  else { ?>
					<?=_e('application_percent')?>
					<? } ?></span>
                    <span class="oneFour out-summa" style="margin-right:0px;width:130px;"><span>$ <?= price_format_double($out_summ) ?></span><br/> <?=_e('application_outsum')?></span>
                </div>
            </div>

            <? if (($item->state == 2 or $item->state == 4 or $item->state == 5) and $item->bonus != 9 ): ?>
                <div class="formRow last">
                    <label><?=_e('application_contract_detail')?></label>
                    <div class="formRight biger ">
                        <span class="oneFour" style="width:130px;"><span><?= $item->kontract ?></span><br/><?=_e('application_contract_no')?></span>
                        <span class="oneFour" style="width:130px;"><span><?= date_formate_my($item->date_kontract, '«d» m Yг.') ?></span><br/><?=_e('application_contract_date')?></span>
                        <span class="oneFour" style="width:130px;"><span><?= date_formate_my($item->out_time, '«d» m Yг.') ?></span><br/><?=_e('application_contract_exp')?></span>
                    </div>
                </div>
            <? endif ?>

            <div class="drop_down">
                <? if (!empty($item->debits)): ?><div class="view_all"><?=_e('application_contract_list')?></div><? endif ?>
                <div class="content_drop">
                    <? /* if(!empty($item->transaction)){?>
                      <table  style="margin-left: -15px">
                      <thead>
                      <tr>
                      <th><?=_e('Дата')?></th><th><?=_e('Сумма')?></th><th><?=_e('Метод  платежа')?></th>
                      </tr>
                      </thead>
                      <tbody>

                      <?foreach($item->transaction as  $val){
                      echo "<tr><td>$val->date</td> <td>$val->summa</td><td>$val->payment</td></tr>";
                      }?>
                      </tbody>
                      </table>
                      <?} else {?> <?=_e('Список транзакций пуст')?><?} */ ?>
                </div>

                <div class="list_applications">
                    <? foreach ($item->debits as $debit): ?>
                        <div data-id='<?= $debit->id ?>' style="padding:5px 0px;border-bottom:1px dotted #ccc;" class="zayavka_ind">
                            <?= date_formate_my($debit->date, 'd/m/Yг.') ?>
                            <a style="margin-left:20px;" rel='<?= $debit->id_user ?>' class='load_user'><?= $debit->name ?> <?= $debit->sername ?></a>
                            <span class='action' style='float:right;margin-right:10px'>
                                <a href='#' class='agree but conf_credit'><?=_e('application_confirm')?></a>
                                <a href='#' class='cancel but'><?=_e('application_deny')?></a>
                            </span>
                        </div>
                    <? endforeach ?>
                </div>

                <? if (!empty($item->user)){ ?>
                    <div class="view_all"><?=_e('application_contragent')?>:</div>
                    <span class="load_user" <?if ( !in_array($item->offer->id_user, [400400,90831137])) { ?>rel="<?= $item->offer->id_user ?>" <?}?>><?= _e($item->user->name." ".$item->user->sername) ?></span>
                    <?if ( !in_array($item->offer->id_user, [400400,90831137])) { ?>
                    <span style="cursor: pointer;" onclick="openDialog( <?= $item->offer->id_user ?> )"><img src="/images/send-message.png"></span>
                    <? } ?>


                    <? if ($item->confirm_payment == 2 && $item->state != 7): ?>
                        <div style="float:right;position:relative;">
                            <? if ( $item->bonus == 9 ){ ?>
                            <a data-id="<?= $item->id ?>" href="#" class="confirm_payment but greenbut"><?=_e('Получить Гарантию')?></a>
                            <a data-id="<?= $item->id ?>" href="#" class="confirm_payment_cancel but greenbut"><?=_e('Отклонить Гарантию')?></a>
                            <? } else { ?>
                            <a data-id="<?= $item->id ?>" href="#" class="confirm_payment but greenbut"><?=_e('application_confirm_money')?></a>
                            <? } ?>
                        </div>
                    <? elseif ($item->state == 3 and $item->bonus != 9): ?>
                        <div style="float:right;position:relative;">
                            <span> <?=_e('application_receive_money')?></span>
                            <!--
                            <a data-id="<?= $item->id ?>" href="#" class="confirm_payment but agree"><?=_e('application_confirm_settle')?></a>-->
                        </div>
                    <? endif ?>

                    <? if ($item->state == 3 and $item->bonus != 9): ?>
                        <div style="float:right;position:relative;">
                            <a data-id="<?= $item->id ?>" href="#"  class="report_send but cancel"><?=_e('application_report')?></a>
                        </div>
                    <? endif ?>
                    <? if ( $item->state == 2 && $item->arbitration == Base_model::CREDIT_ARBITRATION_OFF && $item->bonus == 6 && $item->garant == 1 && $item->type == 1 &&
                            $accaunt_header['payout_limit_by_bonus'][6] > $item->summa && strtotime(date('Y-m-d'),' 00:00:00') < strtotime($item->out_time) && empty($this->transactions->get_processing_loan_transactions($item->id_user, $item->id))): ?>
                        <div style="float:right;position:relative;">
                            <a data-id="<?= $item->id ?>" href="<?=site_url('account/payout').'?loan='.$item->id?>"  class="return_load_payout but cancel"><?=_e('Вывести займ')?></a>
                        </div>
                    <? endif ?>
                    <? // подтверждение возвращения средств  ?>
                    <?
                    if ( ( strtotime($item->out_time) <= strtotime(date('Y-m-d')) || $item->bonus==9) && strtotime($item->out_time) > 0 && $item->state != 5 && Base_model::CREDIT_ARBITRATION_OFF == $item->arbitration) { ?>
                        <?
                            $type = 'bonus';
                            $extra_info = '';
                            if ( $item->direct == 1 && $item->bonus==6){
                                $type = 'own_direct';
                                $extra_info = "Метод платежа: <img style='vertical-align: middle' src='".Card_model::get_own_account_icon($item->offer->account_id, $item->offer->id_user)."'><br>Счет(кошелек) заемщика: ".Card_model::display_own_account_name($item->offer->account_id, $item->offer->id_user);
                            }  else if ( $item->direct == 1 && $item->bonus==7 ){
                                $type = 'card';
                            }
                        ?>
                        <? if ($item->confirm_return == 2 && $item->state == 2 ): ?>
                            <div style="float:right;position:relative;">
                                <a data-id="<?= $item->id ?>"  data-type="<?=$type?>" data-card="<?=$item->card_id?>" data-extra="<?=$extra_info?>" href="#" class="confirm_send  but bluebut"><?=_e('application_return_loan')?></a>
                               <!--  <a href="#" class="but cancel alfa"><?=_e('application_transfer_card')?></a>
                                <a data-id="<?= $item->id ?>" href="#" class="confirm_return but agree"><?=_e('application_confirm_settle')?></a>-->
                            </div>
                        <? elseif ($item->state != 5): ?>
                            <div style="float:right;position:relative;">
                                <span><?=_e('application_transfer_money')?></span>
                            </div>
                        <? endif ?>
                    <? }elseif (Base_model::CREDIT_ARBITRATION_ON == $item->arbitration && Base_model::CREDIT_STATUS_ACTIVE == $item->state) {
                        ?>
                        <div style="float:right;position:relative;">
                            <a data-id="<?= $item->id ?>" data-type="standart" href="#" class="arbitrage_repayment but bluebut"><?=_e('application_return_credit')?></a>
                            <a data-id="<?= $item->id ?>" data-time="<?= $item->time ?>" data-maxtime="<?=$accaunt_header['max_arbitrage_days_by_bonus'][$item->bonus]?>" data-summa="<?= $item->summa ?>" href="#" class="arbitrage_prolongation but agree"><?=_e('application_extend_credit')?></a>
                        </div>
                    <? } ?>
                <? } else { ?>
                <? if (Base_model::CREDIT_ARBITRATION_ON == $item->arbitration && Base_model::CREDIT_STATUS_ACTIVE == $item->state && $item->blocked_money == '9') {
                        ?>
                        <div style="float:right;position:relative;">
                            <a data-id="<?= $item->id ?>" data-type="<?=($item->bonus==7)?'gift':'old'?>" href="#" class="arbitrage_repayment but bluebut"><?=_e('application_return_credit')?></a>
                        </div>
                    <? } ?>

                <? }  ?>
            </div>
        </fieldset>
    </div>
</div>
