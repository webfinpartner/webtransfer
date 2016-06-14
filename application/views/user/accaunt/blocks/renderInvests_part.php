<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?
    $out_summ = $item->out_summ;
    $income = $item->income;
    if ( $item->state == 2 && $item->direct == 1 && strtotime($item->out_time) <= strtotime(date('Y-m-d')) && strtotime($item->out_time) > 0  ){
        $time_days = calculate_debit_time_now( $item->date_kontract );
        $out_summ = credit_summ($item->percent, $item->summa, $time_days);
        $income = $out_summ - $item->summa;
    }

?>


<div class="toggle">
    <div class="title closed">
	<? if ($item->garant == 1) { ?>
    <?if($item->garant_percent == 2){?>
        <img class="titleIcon" src="/images/creds.png" alt="" style="height:14px; width:14px;"/>
	<?} elseif ($item->garant == 1 && $item->bonus == 4 && $item->direct == 0)  { ?>
                    <img class="titleIcon" src="/images/ccreds.png" style="height:14px; width:14px;" alt=""/>
    <?} elseif ($item->garant == 1 && $item->garant_percent == 0 && $item->direct == 0)  { ?>
                    <img class="titleIcon" src="/images/garant_stat1.png" style="height:14px; width:14px;" alt=""/>

    <? }elseif ($item->garant == 1 && $item->direct == 1) { ?>
                    <img class="titleIcon" src="/images/garant-direct.png" style="height:14px; width:14px;" alt=""/>
    <? }elseif ($item->garant == 1 && $item->garant_percent == 1) { ?>
                    <img class="titleIcon" src="/images/arbitration.png" style="height:14px; width:14px;" alt=""/>
    <? }?>
<? } elseif ($item->garant == 0) { ?>
    <? if ($item->arbitration == 0 && $item->direct == 0){ ?>
                    <img class="titleIcon" src="/images/garant_stat0.png" />
    <? }elseif ($item->direct == 1){ ?>
                    <img class="titleIcon" src="/images/standart-direct.png" style="height:14px; width:14px;" alt=""/>
    <? }elseif ($item->arbitration == 1){ ?>
                    <img class="titleIcon" src="/images/arbitration.png" style="height:14px; width:14px;" alt=""/>
    <? } ?>
<? } elseif ($item->garant == 2) { ?>
                    <img class="titleIcon" src="/images/arbitration.png" style="height:14px; width:14px;" alt=""/>
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
$certificate = $offert_borrower = $offert_investor = $payment_to_borrower = $return_to_investor = $borrower_id = $borrower_another = '';
if ($item->garant == Base_model::CREDIT_GARANT_ON && $item->state == 2){
    $certificate = "data-certificate='".site_url('account/getcertificate')."/".$item->id."'";
}
if(!isset($archive_page) && $item->bonus != 9){
    $url = ($item->garant_percent == 1) ? "/upload/offer-arbitrage.pdf" : site_url('account')."/ofert-{$item->id}.pdf";
    $offert_investor = $payment_to_borrower = $return_to_investor = $borrower_id = $borrower_another = '';
    $offert_borrower = "data-ofert_borrower='$url'";

    //passport
    if( $item->garant == Base_model::CREDIT_GARANT_OFF &&
        $item->arbitration == Base_model::CREDIT_ARBITRATION_OFF  && $item->state > 1  && $item->bonus != 9){
            $borrower_id = "data-borrower_id='".site_url('account')."/application_doc/".$item->debit_id_user."'";
    }

    if( isset($another_doc) && $another_doc === TRUE  && $item->bonus != 9 ){
        $borrower_another = "data-borrower_another='".site_url('account')."/application_doc/".$item->debit_id_user."/5'";
    }

    //Вклад - убрать заявку заемщика
    if( $item->garant == Base_model::CREDIT_GARANT_OFF &&
            $item->type == Base_model::CREDIT_TYPE_INVEST  && $item->state > 1  && $item->bonus != 9){
        $offert_investor = "data-ofert_investor='".site_url('account')."/ofert-{$item->debit}.pdf'";
    }

    //cheques
    if( $item->state > 1 && $item->confirm_payment == 1  && $item->bonus != 9 ){
        $payment_to_borrower = "data-payment_to_borrower='".site_url('account')."/paymentdoc-{$item->id}.pdf'";
    }
    if( ( $item->garant == Base_model::CREDIT_GARANT_ON && $item->state == Base_model::CREDIT_STATUS_PAID ) ||
        ( $item->garant == Base_model::CREDIT_GARANT_OFF && $item->state == Base_model::CREDIT_STATUS_PAID && $item->confirm_return == 1  && $item->bonus != 9)){
            $return_to_investor = "data-return_to_investor='".site_url('account')."/paymentdoc-{$item->debit}.pdf'";
    }
}?>
            <a class="detailed-link" href="javascript" <?= $offert_borrower . ' ' . $offert_investor . ' ' . $payment_to_borrower . ' ' . $return_to_investor . ' ' . $borrower_id . ' ' . $certificate . ' ' . $borrower_another; ?> >
                <img class="pdf_image"  src="/images/note.png" title="Datailed docs" style="margin-top: 6px;margin-left: 10px;"/>
            </a>

        <div class="buttons">
            <span><?= accaunt_debit_status($item->state) ?></span>
<? if ($item->state == 1) { ?>
                <a class="delete_button" href="<?=site_url('account/delete_invest')?>/<?= $item->id ?>"  onclick="return yes_no_debit()">
                    <span><?=_e('delete')?></span>
                </a>
<? } ?>
<? if (false and $item->state == 3 and $item->confirm_payment == 2 and $item->confirm_return == 2) { ?>
                <a class="delete_button" href="<?=site_url('account/delete_contract')?>/<?= $item->id ?>"  onclick="return yes_no_contract()">
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
                    <span class="twoFour out-summa" style="width:130px;font-size:15px;text-align:center;float: left;height: 15px;">$ <?= price_format_double($item->summa) ?>
                    <?if ($item->bonus != 10) { ?>
                        <?if ($item->bonus == 1 && 1==0) { ?>
                            <span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Новые бонусные: ')?><?=$item->sum_own?> <?=_e('Старые бонусные: ')?><?=$item->sum_loan?> ">(?)</span>                   
                        <? } else { ?>         
                            <span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Свои: ')?><?=$item->sum_own?> <?=_e('Заемные: ')?><?=$item->sum_loan?> <?=_e('Арбитраж: ')?><?=$item->sum_arbitration?> ">(?)</span>                   
                        <? } ?>     
                    <? } ?>     
<?
if ($item->bonus==1) echo "<br/><span style='font-size:11px'>("._e('bonus_acct').")</span>";
else if ($item->overdraft) echo "<br/><span style='font-size:11px'>("._e('overdraft').")</span>";
else if($item->bonus == 3) echo "<br/><span style='font-size:11px'>(Партнерскиe)</span>";
else if($item->garant_percent == 2) echo "<br/><span style='font-size:11px'>(P CREDS - Партнерскиe)</span>";
else if ($item->sum_arbitration > 0) echo "<br/><span style='font-size:11px'>(" ._e('zaemniye').")</span>";
else if ($item->garant_percent) echo "<br/><span style='font-size:11px'>(" ._e('arbitrage').")</span>";
else if (Base_model::CREDIT_EXCHANGE_STATUS_BAY == $item->active) echo "<br/><span style='font-size:11px'>(" ._e('birja').")</span>";
?>
                    </span>

                    <span class="twoFour" style="width:130px;font-size:15px;text-align:center; display: inline-block">
                        <? if ( $item->direct == 1 && $item->bonus==6) {?>
                            <img style="vertical-align: middle" src="<?= Card_model::get_own_account_icon($item->account_id) ?>"><span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Счет: ')?> <?=Card_model::display_own_account_name($item->account_id)?>">(?)</span><br/>
                            <span style='font-size:11px'>(<?= _e('счет') ?>)</span>
                        <? } else { ?>
                            <? if ( $item->bonus==6) {?>
                                <img style="vertical-align: middle" src="/images/debit.png"><br/>
                            <? }  else { ?>
                                <?= _e('wt_currency_'.$item->bonus) ?><br/>
                            <? } ?>
                            <span style='font-size:11px'>(<?= _e('currency') ?>)</span>
                            <? if ( ($item->direct == 1 && $item->bonus==7) || $item->bonus==10) {?>
                                <span  style="margin-left: 4px;position: relative;margin-top: -18px;font-weight: bold;" class="simptip-position-right" data-tooltip="<?=_e('Карта')?>:  <?=Card_model::display_card_name($item->card_id, TRUE)?>">(?)</span>
                            <? } ?>

                        <? } ?>
                    </span>
                    <span class="oneFour" style="width:auto;"></span>
                </div><!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;< ?= creditImgPayment($item->payment); ?>-->
            </div>

            
<? if ($item->garant_percent != 1) { ?>
            <div class="formRow last">
                <? if ($item->bonus == 9){ ?>
		<label><?=_e('Детали')?></label>
		<? }  else { ?>
		<label><?=_e('application_detail')?></label>
		<? } ?>

                <div class="formRight biger">
                    <? if ($item->bonus != 10) { ?>
                    <span class="oneFour" style="width:130px;">
                        <? if ($item->garant_percent == 1) { ?>
                                <span><?=_e('arbitrage')?></span>
                        <? } elseif ($item->garant_percent != 1) { ?>
                            <span><?= $item->time ?></span><br/><?=_e('application_date')?>
                        <? } ?>
                    </span>
                    <? } ?>
                            
                    <? if ($item->bonus == 10) { ?>
                    <span class="oneFour" style="width:130px;">
                    
                        <? if ( $item->state==Base_model::CREDIT_STATUS_PAID ) { ?>
                            <?  $total_minutes = round((strtotime($item->date_active) - strtotime($item->date))/60,0); ?>
                        <? } else { ?>
                            <?  $total_minutes = round((time() - strtotime($item->date))/60,0); ?>
                        <? } ?>                        
                        <span><?= $total_minutes ?></span>
                        <? if ( $item->state==Base_model::CREDIT_STATUS_PAID ) { ?>
                             <br/><?=_e('Время (мин)')?>
                        <? } else { ?>
                             <br/><?=_e('Текущее время (мин)')?>
                        <? } ?>
                    </span>
                    <? } ?>
                            
                    <span class="oneFour" style="width:135px;"><span><?= $item->percent ?>%</span><br/>
					<? if ($item->bonus == 9){ ?>
					<?=_e('Ставка всего')?>
					<? }  else { ?>
					<?=_e('application_day_pcnt')?>
					<? } ?>
					</span>
                    <span class="oneFour" style="margin-right:0px;width:130px;">
                        <? if ( $item->bonus == 10 ) { ?>
                              <? $income = Card_model::get_fast_arbitrage_income($item->summa, $item->date, $item->percent); ?>
                              <span>$ <?= price_format_double($income) ?></span>
                              <br/><?= ( $item->state==Base_model::CREDIT_STATUS_PAID )?_e('Прибыль'):_e('Текущая прибыль')?></span>
                        <? } else { ?>
                            <? if ($item->garant_percent != 2) { ?>
                              <span>$ <?= price_format_double($out_summ) ?></span>
                              <br/><?=_e('application_outsum')?></span>
                            <? } else  { ?>
                              <span>$ <?= price_format_double($income*40) ?></span>
                              <br/><?=_e('всего доход')?></span>
                           <? } ?>
                       <? } ?>
                </div>
            </div>
<? } ?>
<? if ((Base_model::CREDIT_STATUS_ACTIVE == $item->state or Base_model::CREDIT_STATUS_OVERDUE == $item->state) and $item->garant_percent != 2 and !in_array($item->bonus,[9,10])){ ?>
                <div class="formRow last">
                    <label><?=_e('application_contract_detail')?></label>
                    <div class="formRight biger ">
                        <span class="oneFour" style="width:130px;"><span><?= $item->kontract ?></span><br/><?=_e('application_contract_no')?></span>
                        <span class="oneFour" style="width:130px;"><span><?= date_formate_my($item->date_kontract, '«d» m Yг.') ?></span><br/><?=_e('application_contract_date')?></span>
                        <span class="oneFour" style="width:130px;">
                        <? if ($item->garant_percent != 1) { ?>
                        <span><?= date_formate_my($item->out_time, '«d» m Yг.') ?></span>
                        <br/><?=_e('application_contract_exp')?>
                        </span>
                        <? }elseif ($item->garant_percent == 1) { ?>
                        <span><?= $item->percent ?>%</span>
                        <br/><?=_e('application_day_pcnt')?>
                        </span>
                        <? } ?>
                    </div>
                </div>
<? }?>

<? if (Base_model::CREDIT_STATUS_PAID == $item->state and !in_array($item->bonus,[9,10])){ ?>
                <div class="formRow last">
                    <label><?=_e('application_contract_detail')?></label>
                    <div class="formRight biger ">
                        <span class="oneFour" style="width:130px;"><span><?= $item->kontract ?></span><br/><?=_e('application_contract_no')?></span>
                        <span class="oneFour" style="width:130px;"><span><?= date_formate_my($item->date_kontract, '«d» m Yг.') ?></span><br/><?=_e('application_contract_date')?></span>
                        <span class="oneFour" style="width:130px;"><span><?= date_formate_my($item->out_time, '«d» m Yг.') ?></span><br/><?=_e('application_contract_exp')?></span>
                    </div>
                </div>
<? } ?>

<? if ($item->garant == 1 and  !in_array($item->bonus,[9,10])){ ?>
                <div class="formRow last">
                    <label><?=_e('garant_on')?> </label>
                    <div class="formRight biger" >
                        <span class="oneFour" style="width:130px;"><span>$ <?= price_format_double($income) ?></span><br/>
    <?  if ($item->garant_percent == 1 || $item->garant_percent == 2)  { ?> <?=_e('ежедневное начисление')?>

    <? }  elseif ($item->garant_percent == 0) { ?> <?= _e('income')?>
    <? } ?>
                        </span>
                        <span class="oneFour" style="width:135px;">
    <? if ($item->garant_percent == 1 || $item->garant_percent == 2) { ?>
                            <span>$ <?= price_format_double($income*0.2) . ' (-'. 0.2*100 .'%)' ?></span><br/><?=_e('dayly_fee')?>
    <? }elseif ($item->garant_percent == 0) { ?>
                               <? if ( $item->blocked_money == 44) {
                                $trans = get_transaction_by_value($item->id, ['type'=> Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST]);
                                ?>
                                <span>$ <?= price_format_double($trans->summa) . ' (-' . price_format_double($trans->summa / $item->income * 100) . '%)' ?></span><br/>				<?=_e('otchislenie')?> CREDS
                            <? } else { ?>
                                <span>$ <?= price_format_double($income * (garantPercent($item->time) / 100)) . ' (-' . garantPercent($item->time) . '%)' ?></span><br/>				<?=_e('otchislenie')?> <? if ($item->blocked_money == 22) echo "<span style='color:red'>&#10084;</span>";?>
                            <? } ?>
    <? } ?>
                        </span>
                        <span class="oneFour" style="margin-right:0px;width:130px;">
                            <span>
    <? if ($item->garant_percent == 1 || $item->garant_percent == 2) { ?>
                                $ <?= price_format_double($out_summ - $income*0.2) ?>
                            </span>
                            <br/><?=_e('dayly_receive')?>
                        </span>
    <? }elseif ($item->garant_percent == 0) { ?>
                            <? if ( $item->blocked_money == 44) {
                                $trans = get_transaction_by_value($item->id, ['type'=> Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST]);
                                ?>
                                $ <?= price_format_double($out_summ) ?>
                              <? } else if ( $item->blocked_money == 22) { ?>
							   $ <?= price_format_double($out_summ) ?>
							  <? } else { ?>
                                $ <?= price_format_double(($out_summ - $income * (garantPercent($item->time) / 100))) ?>
                              <? } ?>
                            </span>
                            <br/><?=_e('you_receive')?>
                        </span>
    <? } ?>
                    </div>
                </div>

<? // работа с сертификатами
    if ($item->state == 2 && $item->garant_percent != 1){    ?>
                    <div class="formRow last" style="background-color:#f4fcff">
                        <label><?=(($item->garant_percent == 2) ? 'P CREDS' : _e('credit_certificate'))?></label>
                        <div class="formRight biger" >
                            <span class="oneFour" style="width:130px;"><span>СС<?= $item->id; ?></span><br/><?=_e('number')?></span>
                            <span class="oneFour" style="width:130px;">
<? if ($item->garant_percent == 2) { ?>
<span><?= date_formate_my($item->date_kontract, '«d» m Yг.') ?></span><br/><?=_e('application_contract_date')?></span>

<span class="oneFour" style="margin-right:0px;width:125px;"><span><?= date_formate_my($item->out_time, '«d» m Yг.') ?></span><br/><?=_e('application_contract_exp')?></span>

<? }else{ ?>
<span>$ <?= price_format_double(((countCertificate($item->summa, $item->date_kontract)) - $item->summa)) ?></span><br/><?=_e('min_income')?> <?= date('d.m.y') ?><a href="<?=site_url('page/vlojit-dengi/kurs')?>#today"></a></span>
<span class="oneFour" style="margin-right:0px;width:125px;"><span>$ <?= price_format_double((countCertificate($item->summa, $item->date_kontract))); ?></span><br/><?=_e('cost')?></span>
 <? } ?>

                            <span class="oneFour" style="margin-right:0px;width:50px;">
      <? if($item->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER){?>

                                <? if (Base_model::CREDIT_EXCHANGE_OFF == $item->exchange) { ?><a class="green_button bn_exchangeCertificat" href="" onclick="return false;" data-id="<?= $item->id ?>" data-summ="10" data-out_summ="<?=$item->summa;?>"><?=_e('Продать<br/>P CREDS')?><?//=_e('exchange_certificate')?></a>
                                <? } elseif(Base_model::CREDIT_EXCHANGE_ON == $item->exchange) {?><a class="green_button delete_button bn_delExchangeCertificat" style="background-color: #FF5300;"  href="" data-id="<?= $item->id ?>"><?=_e('Отменить')?></a>
                                <?}?>

    <?} elseif ($item->bonus != 4 && $item->certificate == 1 && ( $item->bonus != 7 && (1*24*60*60 < (time() - strtotime($item->date_kontract))) || ($item->bonus == 7 && strtotime($item->out_time.' 23:59:59') < strtotime(date('Y-m-d H:i:s')) ))   ) { ?>
         <? if( ($item->bonus == 2 || $item->bonus == 5|| $item->bonus == 6) && $item->arbitration != 2 && $item->arbitration != 3 ){?>
                <? if (Base_model::CREDIT_EXCHANGE_OFF == $item->exchange) { ?>
                                <a class="green_button bn_exchangeCertificat" href="" onclick="return false;" data-id="<?= $item->id ?>" data-summ="<?= $item->summa ?>" data-out_summ="<?= ($item->out_summ - $item->income * (garantPercent($item->time) / 100)) ?>"><?=_e('sell_certificate')?><?//=_e('exchange_certificate')?></a>
                <? } elseif(Base_model::CREDIT_EXCHANGE_ON == $item->exchange) {?>
                    <?if('0000-00-00 00:00:00' == $item->date_active || (time() > (60*60 + strtotime($item->date_active)))){?>
                                <a class="green_button bn_sellCertificat"  onclick="return false;" href="<?=site_url('account/sellCertificate')?>/<?= $item->id ?>"><?=_e('sell_certificate')?></a>
                            </span>
                        </div>
                        <div class="formRight biger" >
                            <span class="oneFour" style="margin-left: 555px;margin-top: 5px;width:50px;">
                    <?}?>
                                <a class="green_button delete_button bn_delExchangeCertificat" style="background-color: #FF5300;"  href="" data-id="<?= $item->id ?>"><?=_e('remove_certificate')?></a>
                <?}?>
         <? } else {?>
                                <a class="green_button bn_sellCertificat"  onclick="return false;" href="<?=site_url('account/sellCertificate')?>/<?= $item->id ?>"><?=_e('sell_certificate')?></a>
         <? } ?>

      <?}?>
                            </span>
                        </div>
                    </div>
    <? } ?>

<? } elseif (!in_array($item->bonus,[9,10])) { ?>
                <div class="formRow last">
                    <label><?=_e('standart_on')?> </label>
                    <div class="formRight biger" >
                        <span class="oneFour" style="width:130px;"><span>$ <?= price_format_double($income) ?></span><br/><?=_e('income')?></span>
                        <span class="oneFour" style="width:135px;"><span>$ <?= price_format_double(($income * (10 / 100))) . ' (-10%)' ?></span><br/><?=_e('otchislenie')?></span>
                        <span class="oneFour" style="margin-right:0px;width:130px;"><span>$ <?= price_format_double(($out_summ - $income * (10 / 100))) ?></span><br/><?=_e('you_receive')?></span>
                    </div>

                </div>
<? } ?>
            <div class="drop_down">
<? if (!empty($item->debits)){  ?>




                <div class="view_all"><?=_e('application_contract_list')?></div><?} ?>
                <div class="list_applications">
<? foreach ($item->debits as $debit){ ?>
<?
    $type = 'bonus';
    $extra_info = '';
    if ( $item->direct == 1 && $item->bonus==6){
        $type = 'own_direct';
        $extra_info = "Метод платежа: <img style='vertical-align: middle' src='".Card_model::get_own_account_icon($debit->debit_account_id, $debit->user_from)."'><br>Счет(кошелек) заемщика: ".Card_model::display_own_account_name($debit->debit_account_id, $debit->user_from);
    }
?>
                        <div data-id='<?= $debit->id ?>' style="padding:5px 0px;border-bottom:1px dotted #ccc; height: 30px;margin-bottom: 5px;" class="zayavka_ind">
                            <?= date_formate_my($debit->date, 'd/m/Yг.') ?>
                            <a style="margin-left:20px;" rel='<?= $debit->id_user ?>' data-id="<?= $debit->debit ?>" class='load_user'><?= $debit->name ?> <?= $debit->sername ?></a>
    <? if (1==1||!($debit->bot == 2 and $item->garant == 1)) { ?>
                                <span class='action' style="float:right;margin-right:10px">
                                    <a href='#' class='agree but conf0' data-type="<?=$type?>" data-extra="<?=$extra_info?>" style="margin-right:10px;"><?=_e('application_confirm')?></a>
                                    <a href='#' class='cancel but'><?=_e('application_deny')?></a>
                                </span>
    <? } ?>
                        </div>
<? } ?>
                </div>
<? if (!empty($item->user)){ ?>
                    <div class="view_all"><?=_e('application_contragent')?></div>
                        <span <?if ( !in_array($item->offer->id_user, [400400,90831137])) { ?>rel="<?= $item->offer->id_user ?>" class="load_user"<?}?>><?= $item->user->name ?> <?= $item->user->sername ?></span>
                        <?if ( !in_array($item->offer->id_user, [400400,90831137])) { ?>
                        <span style="cursor: pointer;" onclick="openDialog( <?= $item->offer->id_user ?> )"><img src="/images/send-message.png"></span>
                        <? } ?>

    <? // подтверждение отправки средств    ?>
    <? if ($item->confirm_payment == 2  and $item->bonus != 9){ ?>
                        <div style="float:right;position:relative;">
                            <? if ($item->direct == 1 && $item->bonus == 6 ){ ?>
                            <a data-id="<?= $item->id ?>" href="#" class="confirm_payment but agree"><?=_e('application_confirm_send')?></a>
                            <? } else { ?>
                                <a data-id="<?= $item->id ?>" href="#" class="confirm_send but bluebut"><?=_e('application_send_money')?></a>
                                <a data-id="<?= $item->id ?>" href="#" class="confirm_payment but agree"><?=_e('application_confirm_send')?></a>
                            <? } ?>

                        </div>
    <? } elseif ($item->state == 3 and $item->bonus != 9){ ?>
                        <div style="float:right;position:relative;">
                            <span><?=_e('application_transfer_money')?></span>
                        </div>
    <? } ?>
    <? /* if($item->state==3):?>
      <div style="float:right;position:relative;">
      <a data-id="<?=$item->id?>" href="#" class="report_send but cancel"><?=_e('report')?></a>
      </div>
      <?endif */ ?>
    <? // подтверждение возвращения средств  ?>
    <? if (strtotime($item->out_time) <= strtotime(date('Y-m-d')) and strtotime($item->out_time) > 0 and 7 != $item->bonus and 5 != $item->state and ( 1 != $item->garant || $item->direct==1) ): ?>
        <? if ($item->confirm_return == 2 and $item->bonus != 9): ?>
                            <div style="float:right;position:relative;">
                                <a data-id="<?= $item->id ?>" href="#" class="confirm_return but agree"><?=_e('application_return_send')?></a>
                            </div>
        <? elseif ($item->state != 5 and $item->bonus != 9): ?>
                            <div style="float:right;position:relative;">
                                <span><?=_e('application_receive_money')?></span>
                            </div>
        <? endif ?>
    <? endif ?>
      
<? } ?>
                        
    <? if ($item->state==Base_model::CREDIT_STATUS_ACTIVE and $item->bonus == 10){ ?>                        
                <div style="float:right;position:relative;">
                    <a data-id="<?= $item->id ?>" href="<?=site_url('account/card-stop_arbitrage')?>?card_id=<?=$item->card_id?>" class="stop_arbitrage but agree"><?=_e('Завершить')?></a>
                </div>
                        
    <? } ?>           
                        
    <? if ($item->state==Base_model::CREDIT_STATUS_PAID and $item->bonus == 7){ ?>                        
            <? $transaction = getTransactionByParams(['value'=>$item->id, 'type'=>24, 'status'=>2], TRUE); ?>
                <? if (!empty($transaction)){ ?>
                <div style="float:right;position:relative;">
                    <a onclick='$("#recive_waiting_invest_dialog").data("id", <?=$transaction->id?>); $("#recive_waiting_invest_dialog").show(); return false;' class="but agree"><?=_e('Получить на карту')?></a>
                </div>
                <? } ?>                                                
    <? } ?>                            
                        
    <? /*if ( $item->state == 2 && $item->arbitration == 2 && $item->sum_arbitration > 0){ ?>                        
                <div style="float:right;position:relative;">
                    <a data-id="<?= $item->id ?>" href="#" class="return_arbitrage_part but agree"><?=_e('Погасить Арбитражную часть')?></a>
                </div>
                        
    <? } */?>                       
                        
    <? if ( in_array($item->state, [2]) && $item->bonus == 7 && (int)(strtotime($item->out_time) - time())/60/60/24 > 0 ){ ?>                        
                <?  ci()->load->model('Giftguarant_model'); if ( !Giftguarant_model::is_invest_used($item->id) ) { ?>
                    <div style="float:right;position:relative;">
                        <a data-id="<?= $item->id ?>" href="<?=site_url('account/giftguarant/create_from_invest/').'/'.$item->id?>" class="create_gift but agree"><?=_e('Выпустить Gift')?></a>
                    </div>
                <? } ?>                                                       
                        
    <? } ?>                               
                        
            </div>
        </fieldset>
    </div>
</div>
