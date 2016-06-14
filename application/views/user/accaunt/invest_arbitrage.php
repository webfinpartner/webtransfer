<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(!$notAjax){?>
<h1 class="title"><?=_e('Выдать Арбитраж')?></h1>
<?}?>

<p>


<?=_e('Партнеры Webtransfer, желающие оперировать суммой более 10 000 USD, МФО и прочие компании могут это делать только в форме выдачи учасникам займов «На Арбитраж».')?> <br/><Br/>
<ol>
<li> <p><?=_e('Партнеру Webtransfer, желающему выдавать другим участникам кредиты «На Арбитраж» выставляется инвойс на сумму, которой партнер желает оперировать. Эта сумма может перечисляться частями в связи с ограничениями банка, обслуживающего партнера.')?> <br/></p></li>
<li> <p><?=_e('Указанная сумма отображается в Кошельке партнера, как сумма пополнения и следующей записью списывается, как сумма выданного кредита на Арбитраж. В личном кабинете данная сумма числится во вкладке Вложения как выданный кредит на Арбитраж под 0,5% без указания срока.')?> <br/></p></li>
<li> <p><?=_e('Согласно договору оферты каждый день партнеру начисляется прибыль.')?><br/></p></li>
<li> <p><?=_e('Данная сумма зачисляется партнеру на Кошелек как «Вознаграждение по Займу на Арбитраж № ХХХХХХ ».')?><br/></p></li>
<li> <p><?=_e('С зачисленной суммы списывается комиссия Webtransfer в размере 20%.')?><br/></p></li>
<li> <p><?=_e('Партнер имеет право распоряжаться суммой, находящейся на кошельке, по условиям, определенным для участников Webtransfer.')?><br/></p></li>
<li> <p><?=_e('Старший партнер, участника, выдающего займы «На Арбитраж», получает вознаграждение из комиссии Webtransfer в соответствии со своим статусом (50%, 60 % и т.д).')?><br/></p> </li>
<li> <p><?=_e('Ниже размещена Оферта, регламентирующая отношения партнеров и компании Webtransfer.')?> <br/></p></li>
<li> <p><?=_e('Участники оперирующие суммами свыше 10 000 USD должны либо вывести свои средства, либо перевести их в Арбитражный фонд. После 10/06/2015 все «лишние» средства будут автоматически переведенны в Арбитражный фонд.')?><br/></p> </li>
</ol>
</p>

<table cellspacing="0" class="payment_table">
    <thead>
        <tr>
            <th class="payment_partner"><?=_e('new_75')?></th>
            <th class="payments_period"><?=_e('new_76')?></th>
            <th class="payments_limitations"><?=_e('new_77')?></th>
            <th class="payments_description"><?=_e('new_78')?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="images"><img title="<?=_e('new_79')?>" alt="<?=_e('new_79')?>" src="/img/bankb.png" style="height:45px;margin:10px 0px;"></td>
            <td class="payment_limits"><p><?=_e('new_81')?></p></td>
            <td class="payment_restrictions"><p><?=_e('new_80')?><br/> $ 10,000</p></td>
            <td class="payment_description">
                <a href="#" id="bank_trigger" onclick="return false"><img src="/images/but_add.png" border="0"></a>
                <!--<a href="#" class="button smallest" id="bank_trigger" onclick="return false">Пополнить баланс</a>-->
            </td>
        </tr>
<? if ($id_user == '500150') { ?>
        <tr>
            <td class="images"><img title="<?=_e('new_79')?>" alt="<?=_e('new_79')?>" src="/img/bankb.png" style="height:45px;margin:10px 0px;"></td>
            <td class="payment_limits"><p><?=_e('new_81')?><?=_e('new_82')?> Norvik</p></td>
            <td class="payment_restrictions"><p><?=_e('new_80')?><br/> $ 10,000</p></td>
            <td class="payment_description">
                <a href="#" id="bank_norvik_trigger" onclick="return false"><img src="/images/but_add.png" border="0"></a>
                <!--<a href="#" class="button smallest" id="bank_trigger" onclick="return false">Пополнить баланс</a>-->
            </td>
        </tr>
<? } ?>
    </tbody>
</table>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_arbitr', compact($bank_fee, $paymenyBank, $id_user) ); ?>
<? $this->load->view('user/accaunt/blocks/renderPayment_bank_norvik_arbitr', compact($bank_fee, $paymenyBank, $id_user) ); ?>

<script>
function get_payment_permission( call_back ){
    call_back();
    return;
}
function cahckSumm(f, e){
    var val = $('#'+f+' '+e).val();
    if (isNaN(parseInt(val))){
        $('#'+f+' .error').show();
        return false;
    }
    if (parseInt(val) < 1){
        $('#'+f+' .error').show();
        return false;
    }
    if (parseInt(val) != val){
        $('#'+f+' .error').show();
        return false;
    }
    return true;
}
</script>