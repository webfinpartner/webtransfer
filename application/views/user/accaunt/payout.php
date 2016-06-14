<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(!$notAjax){?>
<h1 class="title"><?= $title_accaunt ?></h1>
<?}?>

<?
    $defult_payout_limit =  $rating_bonus['net_own_funds_by_bonus'][$default_bonus_account];
    if ( $default_bonus_account == 2 ) $defult_payout_limit = $withdrawal_limit_2;
    $default_show_methods = empty($loan) && ($defult_payout_limit < 50 || $withdrawal_limit < 50 || $default_bonus_account == 5 );



?>

<style>
    .table{
        margin-bottom: 32px;
    }
    .rower{
        height: auto;
        width: 721px;
        min-height: 15px;
        padding: 9px;
        padding-right: 0;
        font-size: 16px;
        overflow: auto;
    }
    .bg {
        background-color:#eee;
    }
    .bold {
        font-weight:bold;
    }
    .td1 {
        padding-left:10px;
        width:70%;
        float:left;
    }
    .td2 {
        text-align:right;
        float:right;
    }

    .payment_table th {
        text-align:center;
    }
    #buttons{
        display: block;
        margin: 10px 0 20px;
        overflow: auto;
    }
    #buttons .button1{
        float: right;

    }
    .formRow{
        overflow: auto;

    }
    .formRow .formRight{
        padding-top: 9px;
        width:400px !important;
    }
    .small{
        font-size: 14px;
        line-height: 14px;
        color: #999;
        margin-bottom: 7px;
        width: 200px;
    }
    .col5{
        width: 20%;
        float: left;
        text-align: left;
    }

    #mc_amount{
        margin-top: 8px;
    }
	div#bank_card_real_payment .widget {
    background: none;
    border: 0;
}

div#bank_card_real_payment .widget .title {
    height: 37px;
    background: none !important;
    border-bottom: 0px solid #cdcdcd;
    margin-bottom: 21px !important;
}
.vk-repost-button {
margin-left: 68px;
}
.social-repost-success button {
    background: #D8D8D8 url(../images/sus-icon.png) no-repeat scroll right center / contain !important;
}
.vk-repost-button button {

    display: block;
    border: medium none;
    width: 125px;
    height: 34px;
    font: 500 12px/24px 'Open Sans';
    color: #FFF;
    text-align: left;
    padding-left: 8px;
    background: none repeat scroll 0% 0% #6180A4;
    background-image: url(../images/wt-vk-repost.png);
    background-repeat: no-repeat;
    background-position: center right;
    cursor: pointer;
    border-radius: 4px;
    outline: none;
    float: left;
    margin: 20px 0;
    margin-right: 12px;
    margin-bottom: 30px !important;
}

.fb-repost-button button {
    display: block;
    border: medium none;
    width: 125px;
    height: 34px;
    font: 500 12px/24px 'Open Sans';
    color: #FFF;
    text-align: left;
    padding-left: 8px;
    background: none repeat scroll 0% 0% #3D5599;
    cursor: pointer;
    background-image: url(../images/wt-fb-repost.png);
    background-repeat: no-repeat;
    background-position: center right;
    border-radius: 4px;
    outline: none;
    float: left;
    margin-right: 12px !important;
    margin: 20px 0;
    margin-bottom: 30px !important;
}

.tw-repost-button button {
    display: block;
    border: medium none;
    width: 125px;
    height: 34px;
    font: 500 12px/24px 'Open Sans';
    color: #FFF;
    text-align: left;
    padding-left: 8px;
    background: none repeat scroll 0% 0% #3AAAE3;
    background-image: url(../images/wt-tw-repost.png);
    background-repeat: no-repeat;
    background-position: center right;
    cursor: pointer;
    border-radius: 4px;
    outline: none;
    float: left;
    margin: 20px 0;
    margin-right: 0;
    margin-bottom: 30px !important;
}

div#submit-modal-button button {
    text-align: center;
    display: block;
    border: medium none;
    width: 237px;
    height: 44px;
    font: 500 18px/24px 'Open Sans';
    color: #FFF;
    background: none repeat scroll 0% 0% #3390EE;
    cursor: pointer;
    border-radius: 4px;
    outline: none;
    padding: 0;
    margin: 14px auto;
    margin-bottom: 37px !important;
    clear: both;
    overflow: hidden;
    margin-top: 95px;
}

.vk-repost-button button:hover, .fb-repost-button button:hover, .tw-repost-button button:hover{
  opacity: 0.8;

}

.text-repost-modal {
	text-align: center;
    padding: 4px 0 12px;
    word-wrap: break-word;
    line-height: 23px;
}


div#submit-modal-button button:hover {
    background: none repeat scroll 0% 0% #F75A1F;
    color: #fff;
}



.lava_payment .close, .vivod13 {
    position: absolute;
    background: url("../../img/close.png") no-repeat 0 0;
    width: 24px;
    height: 20px;
    background-size: contain;
    right: 10px;
    top: 12px;
    cursor: pointer;
}
div#social-repost-withdrawal {
    height: 150px;
    float: none;
}
.i_dont_wanna_social {
    float: none;
    text-align: center;
}
.i_dont_wanna_social a{
	text-decoration:underline;
}
.social-widget-success{
	height: 220px !important;
}
.i-want-card-link {
	text-align: center;
	margin-top: 10px;
}
</style>
<br/>

<script>
	<?if(!empty($loan)){
            $is_loan_out == true; ?>
            var loan_to_out = true;
         <? } else {
            $is_loan_out == false; ?>
            var loan_to_out = false;
        <? } ?>

    function succesCallback(social_type, post_id) {
		$('#social-repost-withdrawal').addClass('social-widget-success');
		if (!$("#social-repost-withdrawal").children('div').hasClass('submit-modal-button-class')){
        $( "#social-repost-withdrawal" ).append( "<div id='submit-modal-button' class='submit-modal-button-class'><button id='social_out_submit' type='submit' onclick='social_out_submit()'><?= _e('Отправить') ?></button></div>" );
		}
		$(".i_dont_wanna_social").hide();
        $('.'+social_type+'-repost-button').addClass('social-repost-success');
        $('#post_'+social_type).val(post_id);

    }

    function shareToWall(data){
        window[$(data).data('social')].wallpost(succesCallback)
    }

    $('#submit-modal-button').click(function(){
        if(security){
            if( $(this).hasClass('submit') ) return true;
            else if(form_submit()){
                //$('#out_send_window').data('call-back',standart_calc).show('slow');
                mn.security_module
                    .init()
                    .show_window('withdrawal_standart_credit')
                    .done(function(res){
                        if( res['res'] != 'success' ) return false;
                        var code = res['code'];

                        $('#form_code').val( code );
                        $('#form_purpose').val( mn.security_module.purpose );
                        $('#mc_amount').val( get_cur_summ() );
                        $('#out_send_payout').addClass('submit').trigger("click");
                    });
                return false;
            }
        }else if(form_submit()){

            return true;
        }
        return false;
    });

	function social_out_submit(){
	console.log("social_submit");
        if(security){
            if( $(this).hasClass('submit') ) return true;

            else if(form_submit()){
                //$('#out_send_window').data('call-back',standart_calc).show('slow');
                mn.security_module
                    .init()
                    .show_window('withdrawal_standart_credit')
                    .done(function(res){
                        console.log('security done');
                        if( res['res'] != 'success' ) return false;
                        var code = res['code'];

                        $('#form_code').val( code );
                        $('#form_purpose').val( mn.security_module.purpose );
                        $('#mc_amount').val( get_cur_summ() );
                        $('#out_send_payout').addClass('submit').trigger("click");
						$('#bank_card_real_payment').hide();
                    });
                return false;
            }
        }else if(form_submit()){

            return true;
        }
        return false;
    }
</script>

<!--VK Lib-->

<!--script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script-->
<script>
    var vk = {

        _instance: false,

        name: 'vk',
        wallpost: function(successCallback){

            VK.Auth.login(
                function(response) {
                    if (response.session) {
                        console.log(1);
                        VK.api('wall.post', {
                            message: '<?if(!empty($loan)){ ?><?=_e('Когда мне нужно я занимаю деньги в Cоциальной кредитной сети и получаю их на Webtransfer VISA Card в течении часа. \n \n Жми на ссылку и получи  бонус $50 >>\n') ?><? } else { ?><?=_e('Выдаю займы участникам соц сетей на срок до 30 дней под 45% и зарабатываю до $5000 в месяц. \n Всем моим партнерам бонус $50! \n \n Жми ссылку >>\n ') ?><? } ?><?=site_url("partner/id-".$this->user->id_user) ?>',
                            attachments : '<?if($this->lang->lang()=='ru'){?>photo-55660968_388457285 <? } else { ?>photo-55660968_388457274<? } ?>, <?=site_url("partner/id-".$this->user->id_user) ?>' // <type><owner_id>_<media_id>
                        }, function (data) {
							if(data)
								if(data.response)
									if(data.response.post_id)
										successCallback(vk.name, data.response.post_id);
									console.log(data.response.post_id);
                        });
                        /* Пользователь успешно авторизовался */
                        if (response.settings) {
                            console.log(2);
                            /* Выбранные настройки доступа пользователя, если они были запрошены */
                        }
                    } else {
                        console.log(3);
                        /* Пользователь нажал кнопку Отмена в окне авторизации */
                    }
                },
                VK.access.WALL
            );

            console.log('wallpost vk');
        },

        init: function(){
            if(this.instance)
                return;

            VK.init({
                apiId: 4352044 // id созданного вами приложения вконтакте
            });

            this._instance = true;
        }
    };
    vk.init();
</script>


<!--FB Lib-->
<script>
    var fb = {

        _instance: false,
        name: 'fb',
        wallpost: function(successCallback){

            var obj =
            {
                method: 'feed',
                name: '<?=_e('Взаимное кредитование Webtransfer!') ?>',
                caption: '',
                description: (
                    '<?if(!empty($loan)){ ?><?=_e('Когда мне нужно я занимаю деньги в Cоциальной кредитной сети и получаю их на Webtransfer VISA Card в течении часа. Бонус $50 всем участникам.') ?><? } else { ?><?=_e('Выдаю займы участникам соц сетей на срок до 30 дней под 45% и зарабатываю до $5000 в месяц. Всем моим партнерам бонус $50!') ?><? } ?>'
                ),
                link: '<?=site_url("partner/id-".$this->user->id_user) ?>',
                picture: '<?if($this->lang->lang()=='ru'){?>https://webtransfer.com/images/banner_new_ru.jpg<?} else {?>https://webtransfer.com/images/banner_new_en.jpg<?}?>'
            };

            function callback(response) {
                if(response && response.post_id) {
                    successCallback(fb.name, response.post_id);
                    console.log('Post was published.');
                    console.log(response);
                    console.log(response.post_id);
                } else {
                    console.log('post not published');
                }
            }
            FB.ui(obj, callback);
            console.log('wallpost fb');
        },

        init: function(){
            if(this.instance)
                return;
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '526472357444368',
                    xfbml      : true,
                    version    : 'v2.5'
                });
            };


            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            this._instance = true;
        }
    };
    fb.init();
</script>
<?if($notAjax){?>
<center>
<iframe id='a028dde9' name='a028dde9' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=18&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=afcbb341&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=18&amp;cb={random}&amp;n=afcbb341&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</center>
<br/>
<?}?>

<? if(isset($need_doc) && $need_doc) {
    echo _e('new_223') . "support@webtransfer.com<br/>";
    return;
}
if(isset($limit) && 50000 == $limit){
    echo _e("Перевод заблокирован на 2 недели в связи со сменой номера телефона. Пожалуйста, дождитесь окончания срока блокировки.");
    return;
}
if(isset($little_incame) && $little_incame) {
    echo _e("У вас пока недостаточно заработанных средств, чтобы оформить заявку на перевод. Ваш минимальный заработок должен быть не менее -") . " $limit. " . _e("Сейчас он составляет") . " $incame.";
    return;
} ?>
    <form class="form" id="payout" method="POST" action="<?=site_url('account/payout')?>" accept-charset="utf-8">
        <input type="hidden" name="submit" value="1"/>
        <input type="hidden" name="code" id="form_code" value="1"/>
        <input type="hidden" name="purpose" id="form_purpose" value="1"/>
        <input type="hidden" name="post_vk" id="post_vk" value="0"/>
        <input type="hidden" name="post_fb" id="post_fb" value="0"/>
        <div id="vivod_sredstv" class="widget" style="margin-top:10px;">
            <div class="title">
                <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
                <h6><?=_e('accaunt/payout_1')?> <? if (!empty($loan)) echo _e('по заявке №').$loan->id ?> </h6>
            </div>


            <fieldset>


                   <!-- <div class="formRow padding10-40">
                        <div class="col5">
                            <div class="small"><?=_e('accaunt/payout_2')?><span class="phone_format" onclick="$('#popup_limit').show('slow');  return  false;" style="cursor:pointer;">(?)</span>: $<?= price_format_double($withdrawal_limit, TRUE, TRUE)?></div>
                            <label style="margin-top:10px;"><?=_e('accaunt/payout_3')?></label>
                        </div>
                        <div class="formRight">
									<center>Использовать <a href="<?=site_url('account/currency_exchange')?>">P2P</a> или
									<a href="<?=site_url('account/exchanges-list')?>"> Обменные Пункты</a></center>
	                     </div>
                    </div>
                    -->
                    <? if(empty($loan))	{ ?>
                     <div class="formRow padding10-40">
                        <label style="margin-top:10px;"><?=_e('Счет')?></label>
                        <div class="formRight">

                            <select <?=($disable_account_select)?' disabled="disabled"':''?> onchange="generate_amount($('#payment_account option:selected').data('summa'))" id="payment_account" name="payment_account" class="form_select" style="width:375px;"><?
                                          //$selected = ( $default_bonus_account==5 )?' selected="selected" ':'';
                                          $disabled = ($disable_account_select)?' disabled="disabled"':'';
                                        //  echo "<option value='5' $selected $disabled data-summa='{$withdrawal_limit_5}'>WTUSD1 - $ ".price_format_double($withdrawal_limit_5, TRUE,TRUE)."</option>";
                                          $selected = ( $default_bonus_account==2 )?' selected="selected" ':'';
                                          if ($withdrawal_limit_2 > 0)
                                          echo "<option style='color:red !important;' value='2' $selected $disabled data-summa='{$withdrawal_limit_2}'>Webtransfer USD&#10084; - $ ".price_format_double($withdrawal_limit_2, TRUE,TRUE)."</option>";
                                          
                                          $selected = ( $default_bonus_account==6 )?' selected="selected" ':'';
                                          if ($withdrawal_limit_6 > 0)
                                          echo "<option  value='6' $selected $disabled data-summa='{$withdrawal_limit_6}'>Webtransfer DEBIT - $ ".price_format_double($withdrawal_limit_6, TRUE,TRUE)."</option>";
                                          $selected = ( $default_bonus_account==3 )?' selected="selected" ':'';
                                          if ($withdrawal_limit_3 > 0)
                                          echo "<option  value='3' $selected $disabled data-summa='{$withdrawal_limit_3}'>"._e('Партнерский счет')." - $ ".price_format_double($withdrawal_limit_3, TRUE,TRUE)."</option>";

                                          if ( !empty($credits_b6)) { ?>
                                             <option  value='loan_out' ><?=_e('Вывести займ')?></option>
                                          <? } ?>
                            </select><br/>



                        </div>

                    </div>


                    <div class="formRow padding10-40" id="credits_list" style="display: none">
                        <label style="margin-top:10px;"><?=_e('Займы')?></label>

                        <div class="formRight">
                            <select class="form_select" id="credits_list" name="loan" style="width:375px;">
                               <option data-summ="0" value="0"><?=_e('Выберите займ')?></option>
                                <? foreach ( $credits_b6 as $credit){ ?>
                                    <option data-summ="<?=$credit->summa?>" value="<?=$credit->id?>">#<?=$credit->id?> <?=_e('от')?> <?=$credit->date?> - $<?=$credit->summa?></option>
                                <? } ?>
                            </select>
                        </div>
                    </div>



                    <div class="formRow padding10-40" id="summ_list">

                        <label style="margin-top:10px;"><?=_e('accaunt/payout_3')?></label>


                        <div class="formRight">
                            <? if ( $withdrawal_limit >= 50 ) { ?>
                            <select name="amount" id="mc_amount" class="form_select" style="width:375px;">
                                <option value="-"><?=_e('Выберите сумму')?></option>
                                <?
                                $max = ($default_bonus_account==5)?2500:1000;
                                for ($s = 50; $s <= min($withdrawal_limit,$max); $s += 50 ){ ?>
                                <option value="<?=$s?>">$ <?=$s?></option>
                                <? } ?>

                            </select>
                            <? } else { ?>
                            <select name="amount" id="mc_amount" class="form_select" style="width:375px;" disabled="disabled">
                                <option value="<?= max($withdrawal_limit, 0)?>">$ <?= price_format_double($withdrawal_limit, TRUE, TRUE)?></option>
                            </select>
                           <? } ?>
                            <!--input class="form_input" type="text" name="amount" id="mc_amount" value="$ 50.0"/-->

                            <div class="error" style="display:none" id="error_amount"></div>
                        </div>

                    </div>
                    <? } else { ?>
                    <div class="formRow padding10-40" id="summ_list">
                        <label style="margin-top:10px;"><?=_e('Сумма')?></label>

                        <div class="formRight">
                            <input type="hidden" name="amount" id="mc_amount" value="<?=$loan->summa ?>">
                            <input type="hidden" name="loan" id="loan" value="<?=$loan->id ?>">
                            <?= $loan->summa ?>
                        </div>

                    </div>

                    <? }  ?>

                    <div class="formRow padding10-40" id="ps_list" style="display: <?=((!$default_show_methods)?'block':'none')?>">
                        <label style="margin-top:10px;"><?=_e('accaunt/payout_4')?></label>
                        <div class="formRight">
                            <select name="payout_system" id="pay_system" class="form_select" style="width:375px;">
                                <? renderSelectKey($payout_systems, "select");?>
                                <?if (empty($wtcards)) { ?>
                                    <option value="create_visa_plastic"><?=_e('Заказать  Webtransfer VISA Black')?></option>
                                    <? if ($all_moneyin_by_bonus_2 > 0 || $all_moneyin_by_bonus_6 > 0 ) {?>
                                        <option value="create_visa_virtual"><?=_e('Заказать  Webtransfer VISA Virtual бесплатно')?></option>
                                    <? } else { ?>
                                        <option value="create_visa_virtual"><?=_e('Заказать  Webtransfer VISA Virtual за $1')?></option>
                                    <? } ?>
                                <? } ?>
                                <!--option value="p2p">P2P</option-->

                                <option style="display: <?=($default_bonus_account==2)?'block':'hide'?>" data-type='exchg' value="p2p"><?=_e('P2P Биржа')?></option>
                                <?/*foreach ( $exchanges_list as $ex){ ?>
                                      <option <?=($default_bonus_account==2)?'block':'hide'?> data-type='exchg' value="<?=$ex->source_url?>"><?=$ex->title?></option>
                                <? } */?>
                            </select>

                        </div>
                    </div>

                    <div class="formRow padding10-40" id="exchg_list" style="display: <?=(($default_show_methods)?'block':'none')?>">
                        <label style="margin-top:10px;"><?=_e('accaunt/payout_4')?></label>
                        <div class="formRight">
                            <select class="form_select" id="form_select_exchange" onchange="on_method_change()" style="width:375px;">
                               <option value="-"><?=_e('Выберите метод')?></option>
                               <option value="p2p"><?=_e('P2P Биржа')?></option>
                                <? /* foreach ( $exchanges_list as $ex){ ?>
                                    <option value="<?=$ex->source_url?>"><?=$ex->title?></option>
                                <? } */?>
                            </select>
                        </div>
                    </div>


                    <input type="hidden" value="1" id="other_pay_systems" name="other_pay_systems"/>
                    <!--div class="formRow padding10-40" id="fastcheck_block" style="display: <?=(!$default_show_methods?'block':'none')?>">
                        <label style="margin-top:10px;"><?=_e('accaunt/payout_6')?></label>
                        <div class="formRight">
                            <input type="checkbox" value="" id="other_pay_systems" name="other_pay_systems" checked="checked"/>

                            <script>
                                $("#other_pay_systems").click(function(){
                                     if ($(this).prop("checked")) alert("<?=_e('accaunt/payout_7')?>");
                                });
                            </script>
                        </div>
                    </div-->

                    <div class="formRow padding10-40" id="charge_block" style="display: <?=(!$default_show_methods?'block':'none')?>">
                        <label style="margin-top:10px;"><?=_e('accaunt/payout_8')?></label>
                        <div class="formRight">
                            <input type="text" value="" id="persent" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="formRow padding10-40">
                        <div style="text-align:center;">
                            <p><?=_e('accaunt/payout_9')?></p>
                            <p><?=_e('accaunt/payout_10')?></p>
                        </div>
                    </div>

                <button class="button" type="submit" id="out_send_payout" name="submit"><?=(!$default_show_methods)?_e('accaunt/payout_11'):_e('Перейти')?></button>

            </fieldset>
        </div>
    </form>

    <div class="widget" style="display:none;">
        <div class="title">
            <img class="titleIcon" src="/images/icons/dark/pencil.png" alt="">
            <h6><?=_e('accaunt/payout_12')?></h6>
        </div>
        <fieldset>
            <?  foreach ($pay_fields as $key => $value) {
                if(!isset($payuot_systems_psnt[$key])) continue;
                ?>
               <div class="formRow padding10-40">
                <label><?=$value?></label>
                <div class="formRight">
                    <? echo $payuot_systems_psnt[$key]["psnt"]."%";
                     //  if ( !empty($payuot_systems_psnt[$key]["psnt_max"])) echo ' - '.$payuot_systems_psnt[$key]["psnt_max"].'%';
                       if (!empty($payuot_systems_psnt[$key]["add"])) echo " + $".$payuot_systems_psnt[$key]["add"];
                       if (!empty($payuot_systems_psnt[$key]["min"])) echo ", мин. $".$payuot_systems_psnt[$key]["min"];
                    ?>
                    <? if("wire_bank" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -12px; font-size: 12px;"><?=_e('accaunt/payout_14')?> </span>
                    <?}?>
                    <? if("wtcard1" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -4px; font-size: 12px;"><?=_e('в зависимости от метода пополнения *')?> </span>
                    <?}?>

					 <? if("bank_lava" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -4px; font-size: 12px;"><?=_e('accaunt/payout_15')?> </span>
                    <?}?>
					<? if("bank_perfectmoney" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -4px; font-size: 12px;"><?=_e('accaunt/payout_15')?> </span>
                    <?}?>
					<? if("bank_okpay" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -4px; font-size: 12px;"><?=_e('accaunt/payout_15')?> </span>
                    <?}?>
					<? if("webmoney" == $key){?>
                    <span style="float: right; display: inline-block; width: 284px; margin-top: -4px; font-size: 12px;">** </span>
                    <?}?>
                </div>
            </div>
           <?}?>


        </fieldset>
    </div>
 <span style="font-size:11px;"></span>
<div id="popup_limit" class="popup_window" style="text-align:justify;padding:10px;">
    <div onclick="$('#popup_limit').hide('slow');" class="close"></div>
    <center><h2><?=_e('accaunt/payout_16')?></h2></center>
    <?=_e('accaunt/payout_17')?>
    <a class="button narrow" onclick="$(this).parent().hide('slow');" name="submit"><?=_e('accaunt/payout_18')?></a>
</div>

<? $this->load->view( 'user/accaunt/blocks/renderPayoutLimits_window' );?>

<script>
    var security = '<?=$security ?>';
    <? //$limit_b6 = max(0,min($withdrawal_limit_6,round($rating_bonus['money_sum_add_funds_by_bonus'][6]+$rating_bonus['money_own_from_2_to_6']-$rating_bonus['money_sum_withdrawal_by_bonus'][6],2)));
    //$limit_b6 = max(0,min($withdrawal_limit_6,round($rating_bonus['money_sum_add_funds_by_bonus'][6]+$rating_bonus['money_sum_add_card_by_bonus'][6]*0.5-$rating_bonus['money_sum_withdrawal_by_bonus'][6],2)));
    if ( in_array(get_current_user_id(), config_item('payout.use_payout_limit_for_users') ) )
        $limit_b6 = max(0,$withdrawal_limit_6);
     ?>
   var payout_limits = {2: <?=$real_payout_limit_2?>, 3:<?=$withdrawal_limit_3?>, 5: <?=0/*$rating_bonus['withdrawal_limit_by_bonus'][5]*/?>, 6: <?=$limit_b6?>};
    var autopayouts_limits = {2: 0, 3:0,  5: 0, 6: <?=round(max(0,$rating_bonus['money_sum_add_card_by_bonus'][6]
	//+ $rating_bonus['money_own_from_2_to_6']
	- $rating_bonus['money_sum_withdrawal_by_bonus'][6]-$rating_bonus['active_cards_credits_outsumm']), 2) ?>};

    function on_method_change(){
        if ($('#form_select_exchange').val()=="https://trust-exchange.org/"){
            $('#bank_card_real_payment').show();return false;
        }
      var url = $("#exchg_list select option:selected").val();
      if( url == 'p2p' )
      {
          go_to_p2p();
          return false;
      }
      if ( url != '-')
        window.open( url, '_blank' );

    }
    function get_cur_summ(){

        if ( $('#payment_account').val()=='loan_out' )
            return $('#credits_list option:selected').data('summ');

       if ( <?=($is_loan_out?1:0)?>==1 || loan_to_out)
          return $('#mc_amount').val();
      else
          return $('#mc_amount option:selected').val();

    }


    $('#out_send_payout').click(function(){

	if ( ($('#payment_account').val()=='6' || $('#payment_account').val()=='3' || $('#payment_account').val()=='loan_out' || loan_to_out == true )  && !$(this).hasClass('submit') ){
            $('#bank_card_real_payment').show();
                console.log('bank_card_real_payment show');
                //succesCallback();
                return false;
        }
        /*if ($('#payment_account').val()==6){
		$('#bank_card_real_payment').show();return false;}*/
        if(security){
            if( $(this).hasClass('submit') ) return true;
            else if(form_submit()){
                //$('#out_send_window').data('call-back',standart_calc).show('slow');
                mn.security_module
                        .init()
                        .show_window('withdrawal_standart_credit')
                        .done(function(res){
                                if( res['res'] != 'success' ) return false;
                                var code = res['code'];

                                $('#form_code').val( code );
                                $('#form_purpose').val( mn.security_module.purpose );
                                $('#mc_amount').val( get_cur_summ() );
                                $('#out_send_payout').addClass('submit').trigger("click");
                        });
                return false;
            }
        }else if(form_submit()){

            return true;
        }
        return false;
    });

    function show_out_block(amount, check_limit){

        //if( amount.indexOf( '.' )  ) amount = amount.replace('.', ',');
        if ( $('#mc_amount option:selected' ).length )
        amount = $('#mc_amount option:selected' ).val();
         var real_amount = amount;
        if ( amount == '-')
            amount = 0;


        var pa = $('#payment_account option:selected').val();
        var payout_limit =  payout_limits[ pa ];

        var real_pa = $('#payment_account option:selected').val();
        var show_methods = true;
        if ( check_limit ){
            show_methods =  payout_limit < 50 || (payout_limit - amount) < 0;
            console.log( payout_limit + ' < ' + 50 + ' ' + payout_limit + ' - ' + amount + ' < 0 ' + " => " + show_methods);
        } else {
            show_methods =  payout_limit < 50 || amount < 50 ;
            console.log( payout_limit + ' < 50 ' + amount + ' < 50 ' + " => " + show_methods );
        }

        if ( pa == 'loan_out'){
            pa = 6;
            show_methods = false;
            loan_to_out = true;
        } else {
            loan_to_out = <?if (empty($loan)){?>false<?}else{?>true<?}?>;
        }


        var max_autopaypout = Math.min(  payout_limit, autopayouts_limits[  pa ] );


	$('#auto_pay').hide();
        if ( pa == 6 )
            $('#auto_pay').show();
        $('#max_autopaypout').html(max_autopaypout);
        //$("#pay_system option").show();
        //alert(pa + ' ' + amount + ' ' +real_amount );
        if ( (pa == 2  || pa==3 ||  pa == 6)  && real_amount== '-' ){
            show_methods = false;
        }
        if (pa == 5 ){
            if ( <?=$bank_inout_by_bonus_5?>  == 0 || amount > <?=$bank_inout_by_bonus_5?> ){
                console.log( 'WTUSD1: summ('+amount+') > bank inout(<?=$bank_inout_by_bonus_5?>); show methods' );
                show_methods = true;
            } else {
                console.log( 'WTUSD1: summ('+amount+') <= bank inout(<?=$bank_inout_by_bonus_5?>); show wirebank' );
                show_methods = false;
                $("#pay_system option[value!=wire_bank]").hide();
                $("#pay_system option[value=wire_bank]").attr('selected', 'selected').show();
                //$("#pay_system option[value=select]").show();
            }
        }


        if ( pa == 6 || pa ==3){
            $('#pay_system option[value=bank_okpay').show();
            $('#pay_system option[value=bank_lava').show();
            $('#pay_system option[value=bank_perfectmoney').show();
            if ( pa==3){
                  $('#pay_system option[value^=wtcard]').hide();
                  //$('#pay_system option[value=select]').prop('selected', true);
            }

            if (  pa == 6 && amount > payout_limit ){
                $('#pay_system option[value^=wtcard]').hide();
                $('#pay_system option[value^=bank_]').hide();
                $('#pay_system option[value=select]').prop('selected', true);
            }
        } else {
            $('#pay_system option[value=bank_okpay').hide();
            $('#pay_system option[value=bank_lava').hide();
            $('#pay_system option[value=bank_perfectmoney').hide();
            $('#pay_system option[value=select]').prop('selected', true);

        }



        console.log( amount + ' ' + payout_limit + ' ' + check_limit);
        <?if(!empty($loan)){ ?>
            show_methods = false;
        <? } ?>

        if( show_methods) {
            $("#exchg_list").show();
            $("#ps_list").hide();
            //if ( amount < 50 )
              //  $("#summ_list").hide();
            $("#fastcheck_block").hide();
            $('#credits_list').hide();
            $("#charge_block").hide();
            $("#out_send_payout").html('<?=_e('Перейти')?>');

          //  $('#out_select_dialog').show();
        } else {
            $("#ps_list").show();
            $("#summ_list").show();
            $("#fastcheck_block").show();
            $("#charge_block").show();
            $("#exchg_list").hide();
            $("#out_send_payout").html('<?=_e('accaunt/payout_11')?>');
            if ( real_pa == 'loan_out'){
                $('#summ_list').hide();
                $('#credits_list').show();
            } else {
                $('#credits_list').hide();
            }

        }
    }
</script>

<script>
    function go_to_p2p()
    {

        var pa = $('#payment_account option:selected').val();
        var summa = $('#mc_amount').val();
        console.log( summa, pa );
        console.log( $('#payment_account option:selected') );
        if( parseFloat(summa) <= 0 ) return false;

        var currency = 'wt';
        switch( parseInt(pa) )
        {
            case 2: currency = 'wt_heart'; break;//usd-h
            case 3: currency = 'wt_p_creds'; break;
            case 4: currency = 'wt_c_creds'; break;
            case 5: currency = 'wt'; console.log('wt');break;//usd1
            case 6: currency = 'wt_debit_usd'; console.log('wt_debit_usd');break;//usd1

            default: currency = 'wt'; console.log('wt');
        }
console.log(currency);
        window.open("<?=site_url('account/currency_exchange/sell_search')?>?action=withdrawal&currency="+currency+'&summa='+summa,'_blank');
    }

    $(document).on('change','#pay_system',function(e){
        if($(this).val()=='bank_yandex'){
            window.open("https://ex-wt.com/xchange_WTUSD_to_YAMRUB",'_blank');
			return false;
        } else if($(this).val()=='bank_qiwi'){
             window.open("https://ex-wt.com/xchange_WTUSD_to_QWRUB",'_blank');
			 return false;
        } else if($(this).val()=='create_visa_plastic'){
            window.open("<?=$create_card_url?>",'_blank');
            return false;
        } else if($(this).val()=='p2p'){

            go_to_p2p();
            return false;
        } else if($(this).val()=='create_visa_virtual'){
            window.open("<?=site_url('account/card/virtual')?>",'_blank');
            return false;
        }

         var pa_type = $('#ps_list option:selected').data('type');
         if ( pa_type == 'exchg'){
            var url = $("#ps_list select option:selected").val();
            if ( url != '-')
                window.open( url, '_blank' );
            return false;
          }


          /*if ( $('#pay_system').val().indexOf('wtcard')>=0){
              popup_card_adv_show();
          }*/


    });

    $("#mc_amount").change(function(){
        var amount = get_cur_summ();
        /*
        if(50 > $("#mc_amount").val()) {
            $("#error_amount").show();
            $("#error_amount").html("<?=_e('accaunt/payout_30')?>")
        } else $("#error_amount").hide();
        */
        if ( amount != '-' )
           show_out_block( amount, true );
        persent();
    });
    $("#pay_system").change(function(){
        persent();
        var amount = <?=$withdrawal_limit?>;
        if ( $('#mc_amount option:selected' ).length )
                amount = $('#mc_amount option:selected' ).val();

        if( $("#pay_system").val() == 'wire_bank' ) {
            //generate_amount( amount );
        }

        if( $("#pay_system").val() == 'bank_cc' )
            $('.inner').before( '<div class="message error payoutOffer"><?=_e("accaunt/payout_31")?> <img src="/images/w128h1281338911586cross.png" id="close_message" onclick="$(this).parent().fadeOut()"></div>' );
        else {
            $(".payoutOffer").fadeOut();
        }
        generate_amount( amount );

    });
    $(function(){
        //generate_amount();
        generate_amount($('#payment_account option:selected').data('summa'));
        <? if (( $default_bonus_account == 2) && (empty($loan))) {?>
        $('#pay_system option[value^=wtcard]').hide();
        <? } ?>
        <? if ( !$isSocialAvailable && !$isVKAvailable ) {?>
               /* succesCallback(false, 0); */
        <? } ?>
          <? if (( $default_bonus_account != 6) ) {?>
            $('#pay_system option[value=bank_okpay').hide();
            $('#pay_system option[value=bank_lava').hide();
            $('#pay_system option[value=bank_perfectmoney').hide();
        <? } ?>

    });


    function generate_amount(amount, act)
    {
        var pa = $('#payment_account option:selected').val();
        var pay_sys = $("#pay_system").val().replace(/\[.*\]/g,'');

        if ( pa == 2 ){
            $('#pay_system option[value^=wtcard]').hide();
            $('#pay_system option[value=bank_okpay').hide();
            $('#pay_system option[value=bank_lava').hide();
            $('#pay_system option[value=bank_perfectmoney').hide();
        }

        <?if (!empty($loan)){ ?> return; <? } ?>

        $('#pay_system option').show();
        if ( pa == 2 ){
            $('#pay_system option[value^=wtcard]').hide();
            $('#pay_system option[value=bank_okpay').hide();
            $('#pay_system option[value=bank_lava').hide();
            $('#pay_system option[value=bank_perfectmoney').hide();
        } 

        show_out_block( amount, false );
        var sel_value = get_cur_summ();
        if ( amount == undefined || amount == '-'){
            amount = '<?= price_format_double($withdrawal_limit, TRUE, TRUE); ?>';
            if( amount.indexOf( '.' )  ) amount = amount.replace('.', ',');
            if( amount.indexOf( ' ' )  ) amount = amount.replace(' ', '');
        }
        $("#mc_amount").removeAttr('disabled');

        var select = '<option value="-"><?=_e('Выберите сумму')?></option>';
        amount = parseFloat( amount );
        if( amount <= 0 )
        {

            $('#mc_amount' ).html( select + '<option value="0">$ 0</option>' );
            return;
        }
        if( $("#pay_system").val() == 'wire_bank' ){
            if(amount > 2500) amount = 2500;
        } else
            if( amount > 1000 ) amount = 1000;




        var str = select;
        var i = 50;
        for( i = 50; i <= Math.floor(amount/50)*50; i+= 50 )
        {
            if( sel_value == i )
            {
                str += '<option value="'+i+'" selected="selected">$ '+i+'</option>';
                continue;
            }
            str += '<option value="'+i+'">$ '+i+'</option>';
        }

        if ( amount < 50)
            str += '<option value="'+amount+'">$ '+amount+'</option>';

        //if( !isNaN( sel_value ) && sel_value > 0 )
          //  $('#mc_amount option[value='+sel_value+']' ).attr('selected', 'selected');
        $('#mc_amount' ).html( str );

    }
   function persent(){
        if("select" == $("#pay_system").val() || "no_data" == $("#pay_system").val() || $("#pay_system").val() == undefined) return;
        var s = <?=json_encode($payuot_systems_psnt);?>;
        var pay_sys = $("#pay_system").val().replace(/\[.*\]/g,'');
        var cur = s[pay_sys];
        var val = get_cur_summ().replace(/[^0-9,.]/g,'');

        var psnt = val*cur.psnt/100;
        if (psnt < cur.min) psnt = cur.min;



        var pa = $('#payment_account option:selected').val();


        if ( pa == 2 && pay_sys.indexOf('wtcard') >= 0 ){
            if ( val > <?=$total_income_money_banks ?> ){
                psnt = val*cur.psnt_max/100;
                if (psnt < cur.min) psnt = cur.min;
            }
        }

        psnt = psnt + cur.add;
        $("#persent").val("$ "+psnt);
    }

    function checkField(_link, _wrongs, _pattern) {
        var elem = $(_link),
                res = 0;

        if (elem.length == 0)
            return -1;

        if (elem.val() == '') {
            elem.addClass('wrong');
            res = 1;
        }
        else
            elem.removeClass('wrong');

        return _wrongs + res;
    }

    function form_submit(){

        if ( $("#exchg_list").is(":visible")  ){

            if( $("#exchg_list select option:selected").html() == 'P2P')
            {
                go_to_p2p();
                return;
            }

            location.replace( $("#exchg_list select option:selected").val());
            return;
        }

        var error = $('.error');// $(this).find('.error');
        var s = parseFloat(get_cur_summ().replace(",", ".").replace(/[^0-9,.]/g,''));
        console.log(s);
        if (50 > s || isNaN(s)) {
            error.html("<?=_e('new_221')?> $50");
            error.fadeIn();
            return false;
        }
        if (s.toFixed(2) + "0" != s.toFixed(3)) {
            error.html("<?=_e('new_220')?>");
            error.fadeIn();
            return false;
        }

        if( $('#pay_system').val() == 'select' ){
            $('#pay_system').addClass('wrong');
            return false;
        }else $('#pay_system').removeClass('wrong');


        //$('#mc_amount').val(s);
        return true;
    };


</script>
<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
</div>
<?if($notAjax){?>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
<?}?>



<div class="popup lava_payment" id="bank_card_real_payment" style="display: none;">
    <div class="close" onclick="$(this).parent().hide()"></div>
    <div class="widget" id="social-repost-withdrawal">

        <div class="text-repost-modal"><?=_e('Поделись в соц сетях и получи за каждый пост бонус <b>$20</b>')?><br><span style="font-size:12px"><?=_e('(Вы не должны удалять пост пока не получите прибыль от бонуса)')?></span></div>
        <div class="repost-button">
            <div class="vk-repost-button"><button data-social="vk" onclick="shareToWall(this);return false;"><?=_e('Поделиться VK') ?></button></div>
            <div class="fb-repost-button"><button data-social="fb" onclick="shareToWall(this);return false;"><?=_e('Поделиться FB') ?></button></div>
        </div>
    </div>
	<div class="i_dont_wanna_social"><a href="javascript:succesCallback(false,0);"><?=_e('Я не хочу делиться') ?></a></div>
</div>

    <div class="popup modal fade in modal-visa-card" id="popup_card_adv" role="dialog" aria-hidden="false" style="left: 0px; display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="on_popup_card_adv_close()"></div>
                    <h4 class="modal-title"><?=_e('Бонус при переводе')?></h4>
                </div>
                <div class="modal-body">
                    <div class="card-pm"></div>
                    <div class="i-want-card">
                        <?=_e('Перевeди с Webtransfer VISA Card на Perfectmoney и получи <strong>5%</strong> бонус')?>

                    </div>
					<a href="https://webtransfercard.com/personal/transactions/withdraw" target="_blank"><div class="i-want-card-link"><?=_e('Перейти на')?> webtransfercard.com</div></a>
                    <form class="form-style-modal">

                        <label>
                            <input type="checkbox" name="nomore" onclick="on_popup_card_adv_close()">
                            <?=_e('Больше не показывать')?>
                        </label>

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="res-message"></div>
                    <center>
                        <img class="loader" style="display: none" src="/images/loading.gif">
                        <center></center>
                    </center>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

<!--div class="popup lava_payment" id="popup_card_adv">
    <div class="close" onclick="on_popup_card_adv_close()"></div>
    <div class="widget">
                    <h4 class="modal-title">Webtransfer VISA Card и PM</h4>
                <div class="modal-body">
                    <div class="card-pm"></div>
                    <div class="i-want-card">
                        бонус при выводе с карты  webtransfer visa card
                        <br>
                        на perfect money - <strong>10%</strong>
                    </div>
                    <form class="form-style-modal">

                        <label>
                            <input type="checkbox" name="nomore" onclick="on_popup_card_adv_close()">
                            <?=_e('Больше не показывать')?>
                        </label>

                    </form>
                </div>
    </div>
</div-->


<script>
    function on_popup_card_adv_close(){
        console.log("on_popup_card_adv_close");
        $('#popup_card_adv').hide();
        if ( $('#popup_card_adv [name=nomore]:checked').length ){
             createCookie("reg_buy_card_payout_dialog", "1", 365);
        }

    }

    function popup_card_adv_show(){
        console.log("popup_card_adv_show");
        <? if (1==1){ ?>
            if ( getCookie("reg_buy_card_payout_dialog") != "1"  )
                $('#popup_card_adv').show();
        <? } ?>
        }

</script>

