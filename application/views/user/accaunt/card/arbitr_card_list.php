<? if ($this->lang->lang() == 'ru') { ?>
<p>Участники Webtransfer, имеющие временно короткие средства, могут выдавать краткосрочные займы «На Арбитраж» менее чем на 1 сутки.
<ol> 
<li> Вы можете выдать краткосрочный арбитраж нажав кнопку «Запустить» и в любой момент отозвать свои деньги нажатием кнопки «Остановить». </li>
<li>  Сумма списывается с карты, как <b>"Mutual fund top up (Arbitrage)"</b>. В личном кабинете данная сумма числится во вкладке Вложения как выданный кредит на Арбитраж под 0,5% без указания срока. </li>
<li>  Участнику начисляется прибыль из расчета 0.5% в сутки, минус 0.1% комиссионных. Если вы выдали Арбитраж на час - вам зачислится доход за час. </li>
<li>  Доход зачисляется на карту как «Вознаграждение по Займу на Арбитраж № ХХХХХХ». </li>
<li>  Вложения в Арбитраж на час отдельно учитываются для последующего увеличения лимита Арбитражных средств, которые может использовать сам участник.</li>
</ol>
<br/><br/>
</p>
<? } else { ?>

<p>Webtransfer members can issue “Arbitrage” loans to other participants.
<ol>
<li> You can send your money to the Mutual Fund by clicking "Start" and withdraw your money at any time by clicking the "Stop" button.</li>
<li> The amount will be written off the card as an "Mutual Fund top up."  In the Partner’s back office such amount will be reflected in the “Issued Loans” tab as an issued “Arbitrage” loan at 0.5% not specifying the term. </li>
<li> Participant acquires profit at the rate of 0.5% per day. If you lent for an hour - you will get profit for an hour.</li>
<li> Such amounts will be reflected on the card as “Reward for Arbitrage Loan # XXXXXX”.</li>
<li> Providing funds to the Mutual Fund in the form of points are taken into account separately for the subsequent increase in the limit of Arbitrage funds that can be used by the participant.</li>
 
</ol>
<br/><br/>
</p>
<? } ?>
<table cellspacing="0" class="payment_table">

<tbody>
<? foreach ($wtcards as $card ) { 
    $balance = Card_model::getBalance($card);
    $arbtr = Card_model::get_fast_arbitrage_by_card($card); 
    if ( $balance > 49 || !empty($arbtr)){ 
    ?>  
<tr>
	
	
	
	
<td class="images" style="width:160px;">
<? if($card->card_type==Card_model::CARD_TYPE_VIRTUAL) echo '<img title="Virtual Card" alt="Virtual Card" src="/images/vcard.png" style="width:150px;margin:10px 0px;">'; else echo '<img title="Plastic Card" alt="Plastic Card" src="/images/pcard.jpg" style="width:150px;margin:10px 0px;">'?>    
</td>
<td class="payment_limits" style="padding:10px;width:160px;">
<?=$card->name_on_card?><br/>
4665 **** **** <?=substr($card->pan,-4)?><br/>
<?=_e('Баланс:')?> <b>$<?=$balance?></b>
</td>



<td class="payment_description">
    
   
    <? if (empty($arbtr)) {?>
        <?if (1==1 || $balance >= 50 ){ ?>
        <form method="post" action="<?=site_url('account/card-start_arbitrage')?>">
	          <div class="summ-div">
            
        <?=_e('Сумма')?>:  
            <select name="summ">
            <? if ($balance>=50) for( $i=50; $i <=$balance; $i+=50){ ?>
                <option value="<?=$i?>">$<?=$i?></option>
            <? } ?>
            </select>
            </div>
	        
            <input type="hidden" name="card_id" value="<?=$card->id?>"><br>
          
        <button type="submit"><?=_e('Запустить')?></button>
        </form>
        <? } else { ?>
            <?=_e('Недостаточно средств на карте для запуска Арбитража')?>
        <? } ?>
    <? } else { ?>
    <form method="post" action="<?=site_url('account/card-stop_arbitrage')?>">
        <input type="hidden" name="card_id" value="<?=$card->id?>">
        <?=_e('Сумма')?>: <b>$<?=$arbtr->summa?></b><br>
        <?  $total_minutes = round((time() - strtotime($arbtr->date))/60,0); ?>
        <?=_e('Текущее время (мин)')?>: <b><?=$total_minutes?></b><br>
        <?=_e('Прибыль')?>: <b>$<?=Card_model::get_fast_arbitrage_income($arbtr->summa, $arbtr->date, $arbtr->percent)?></b><br>
    <button type="submit"><?=_e('Остановить')?></button>
    </form>
    
    <? } ?>
</td>

<td class="payment_limits" style="padding:10px;width:160px;">
	
</td>

</tr>
<? } ?>

<? } ?>

<style>
td.payment_description button {
    background: #007AFF;
    border: 0!important;
    color: #fff;
    padding: 7px 18px;
    margin-top: 9px;
    margin-bottom: 19px;
    border-radius: 4px;
    font-size: 15px;
    position: absolute;
    right: 280px;
    margin-top: -55px;
}

td.payment_description button:hover{
	background: #FF5100;
}
.summ-div {
    right: 286px;
    position: absolute;
    margin-top: 10px;
}

div#notice_box {
    position: relative;
    position: fixed;
    left: 50%;
    margin-left: -247px;
    top: 14%!important;
}
</style>

</tbody>
</table>