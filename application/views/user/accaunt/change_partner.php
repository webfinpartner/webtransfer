<br><br>
<p>
<?if($this->lang->lang()=='ru'){?>
Старший партнер - это участник, который по условиям партнерской программы добровольно принимает на себя обязательство консультировать своих младших партнеров по всем вопросам работы в системе и оказывать посильную помощь в решении возникающих у них проблем технического характера. Именно к старшему партнеру вы должны обратиться, если вам понадобится помощь: старшие партнеры - первое звено службы поддержки в Webtransfer. 
<br/><br/>
Чтобы обратиться к старшему партнеру из личного кабинета, нажмите кнопку "Задать вопрос" в правом верхнем углу личного кабинета под аватаром старшего партнера. 
<br/><br/>
Старший партнер поможет вам научиться работать в личном кабинете, ответит на вопросы по использованию наших финансовых инструментов и поможет написать обращение в службу технической поддержки, если возникнут проблемы технического характера.
<br/><br/>
Условия смены старшего партнера:
<ol>
<li> Первый переход к другому старшему партнеру осуществляется бесплатно, но не ранее, чем через месяц с даты регистрации;</li>
<li> Все последующие переходы к другому старшему партнеру возможны не ранее, чем через 3 месяца после предыдущего перехода (услуга платная, стоимость 50 USD);</li>
<li> В течение первого месяца после перехода младшего партнера к старшему последний не получает отчислений от прибыли младшего в течение одного месяца</li>
</ol>
<br/><br/>
<?} else {?>
Senior partner is a partner who takes charge to advise his junior partners on all issues of work in the system and gives all possible assistance in solving their technical problems under the terms of partnership program. 
<br/><br/>
It is a senior partner , who you should contact if you need help : senior partner is the first link in the Webtransfer support. 
<br/><br/>
To refer to a senior partner using a personal account , click on " Ask a Question " in the upper right corner under the user picture in the senior partner's account. 
<br/><br/>
Senior partner will help you to learn how to work in a personal account, he will answer your questions on the use of our financial instruments and will help to write  an appeal to technical support if technical problems occur. 
<br/><br/>
Conditions of changing of senior partner: 
<OL>
<LI> The first transfer to another senior partner is free, but not earlier than after one month from the date of registration; </li>
<li> All the next transfers to other senior partners are possible not earlier than in 3 months after the last transfer (paid service, the rate is USD 50).</li>
<li> During the first month after the transfer of junior partner to the senior partner, the last one does not receive any payments from the profit of junior partner for one month.
</li></ol>

<?}?>
</p><br/><br/>
<center>
<? if($available) {?><?}?> 
<form method="post">
<?=_e('Введите ID нового партнера:')?><br/><br/>
<input style="height:23spx;font-size:16px;" type="text" name="new_partner" value="<?=@$new_partner?>">
<button  class="table_green_button" style="height:26px;"><?=_e('Найти')?></button>
</form>


    <!-- Link Swiper's CSS -->
   
</form>

<?if (!empty($found)) {?>
<table style="width: 70%">
<tr>
    <td>
       <img width="50" style="border-radius:30px" src="<?= $found->ava[0] ?>" alt="User image">
    </td>
    <td><?=@$found->sername?> <?=@$found->name?></td>
	   
    <td>
        <form id="form_request" method="post">
            <input type="hidden" name="send_request" value="<?=@$found->id_user?>"/>
            <input type="hidden" id="form_code" name="code" value=""/>
            <input type="hidden" id="form_purpose" name="purpose" value="change_partner"/>
            <div style="text-align: center">
                <button class="table_green_button" style="height:26px;" onclick="return send_request_change_partner();"><?=_e('Отправить запрос')?></button>
            </div>
        </form>
    </td> </tr>
   
</table>

<script type="text/javascript">
    function send_request_change_partner()
    {
        mn.security_module
            .init()
            .show_window('change_partner')
            .done(function(res){
                if( res['res'] != 'success' ) return false;
                var code = res['code'];

                $('#form_code').val( code );
                
                $('#form_request').submit();
            });
                
        return false;
    }
</script>
    
<?}?> 


<? if (!empty($error)){ ?>
  <?=$error?>
<? } ?>

</center>