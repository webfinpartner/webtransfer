<script>
    
    
function calc(){
    var summ = parseInt($('#select-sum').val());
    var time = parseInt($('#select-period').val());
    var perc = parseFloat($('#select-percent').val());
    console.log( summ + " " + time + " " + perc );
    
    if ( isNaN(summ) ) summ = 0;
    
    var calc = parseFloat(summ + summ*time*perc/100);
    
    $('#summ_to_return').val(calc.toFixed(2));
    
}    

function on_summ_change(){
    calc();    
}

function on_time_change(){
    calc();    
}

function on_perc_change(){
    calc();        
}


$(function(){
   calc(); 
    
});

</script>
<style>
.formRow .col {
    float: left;
    width: 25%; }

.col label { font-size:12px;}
	
</style>
<div class="widget">
    <div class="title">
        <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="" />
        <h6><?=_e('Заявка на выдачу Гарантии')?></h6>
    </div>
    <form class="form" id="form_guarant" method="post">
        <fieldset >
            <div class="formRow rowBonus">
                    <div class="rowWrapper">
                        
                            <label><?=_e('Пользователь: ')?> </label>&nbsp;
                            <input type="text" name="to_user_id" style="padding:5px;">

                    </div>
                </div>
            
            <div style="z-index: 100;    clear: both;    overflow: hidden; ">
            <div class="formRow">
                <div class="col">
                    <label><?=_e('Сумма')?></label>
                    <select id="select-sum" name="summ"  class="form_select" onchange="on_summ_change()">
                        <? for( $i = 50; $i <= min($accaunt_header['max_loan_available_by_bonus'][6]-$accaunt_header['arbitrage_collateral_by_bonus'][6]-$accaunt_header['garant_received']-$accaunt_header['garant_issued_in_wait'], 100); $i+=50 )
                             echo "<option value=\"$i\">$i</option>";
                        ?>                           
                        </select>
                </div>
                <div class="col">
                    <label><?=_e('Срок (дней)')?></label>
                    <select id="select-period" name="time"  onchange="on_time_change()" class="form_select"><?
                        for( $i = 3; $i <= 30; $i++ ){
                            if ( (empty($time) && 15 == $i) || $time==$i ) echo "<option value=\"$i\" selected=\"selected\">$i</option>";
                            else echo "<option value=\"$i\">$i</option>";
                        }
                        ?></select>
                </div>
                <div class="col">
                    <label>
                        <?=_e('Процент (сутки)')?>
                    </label>
                    <select id="select-percent" name="percent" class="form_select" onchange="on_perc_change()">
                        <? for( $i = 0.0; $i <= 0.5; $i+=0.1 ){
                            if ( (empty($percent) && $i == 0.0) || $percent==$i ) echo "<option value=\"$i\" selected=\"selected\">$i</option>";
                            else echo "<option value=\"$i\">$i</option>";
                        }
                         ?>
                    </select>
                </div>
				  <div class="col">
                    <label>
                       <?=_e('Вы получаете')?>
                    </label>
                   <input id="summ_to_return" type="text" value="" disabled="disabled" />
                </div>
				
            </div>

            
            </div>


            <div style=" height:60px;">
                <input type="hidden" name='submited' value='1' />

                <button class="button" onclick="return  true;return  false;"  type="submit" name="submit" ><?=_e('Выдать')?></button>

            </div>

      
            
        </fieldset>
    </form>
</div>
<br/><br/>
<p>
<?if($this->lang->lang()=='ru'){?>
В период Beta-тестирования сервиса, Гарантии предоставляются только на счет Webtransfer DEBIT. Окончательной версия, будет предусматривать выпуск Гарантий будет только на получение займов-гарант на Webtransfer VISA Card.<br/><br/>
Максимальная сумма одной Гарантии не более $100.<br/><br/>
Общее количество выданных Гарантий с одного ID не может превышать $1000.<br/><br/>
Лимит Гаратий на Webtransfer VISA Card будет ограничен только размером кредитного лимита участника.
<?} else {?>
During Beta-testing service:<br/><br/>
- Guarantees are provided only to Webtransfer DEBIT account. <br/>
-Deductions for loans issued using funds secured by Guarantee are accepted in the form of a Webtransfer DEBIT only. <br/><br/>
Maximum amount of one issued guarantee cannot exceed $ 100. <br/><br/>
The total amount of issued guarantees with one ID cannot exceed $ 1000 <br/><br/>
Final version will include issuing Guarantees only for the purpose of obtaining loans “Garant” on Webtransfer VISA Card. <br/><br/>
The total number and amounts of Guarantees issued for the purpose of obtaining loans “Garant” on Webtransfer VISA Card will be limited only by the size of credit limit of the participant.
<?}?>
</p>