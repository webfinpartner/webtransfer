<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="answer_contnet">
    <div class="popup-header">
        <div class="round_icon">
            <img src="<?= $user->user_avatar ?>" width="50px"/>
        </div>
        <?= $user->social_list ?>
        <? if (!empty($user->skype)) { ?>
            <a class="user_social" href="skype:<?= $user->skype ?>?call">
                <img width="32" src="/images/icons/sp.png" />
            </a>
        <? } ?>
		<? $social_id = get_social_id($user->id_user);
        if ( !empty($social_id) ){ ?>
            <a class="user_social" href="https://webtransfer.com/social/profile/<?=$social_id?>?lang=<?=_e('lang')?>"  target="_blank">
                <img width="32" src="/images/wti.png">
            </a>
        <? } ?>
        <? if(isset($user->visualdnaStatus) && $user->visualdnaStatus){ ?>
        <a class="user_social" onclick="return false;"> <!--href="https://fs.visualdna.com/quiz/web_transfer?puid=<?=$user->id_user?>#/feedback" target="_blank"-->
            <img width="32" src="/images/icons/visualdna_icon.png">
        </a>
        <?}?>
    </div>
    <p><?= $user->name ?></p>

    <p><?=_e('accaunt/applicationsCredit_1')?> <?= $user->id_user ?></p>
    <!--<p><?=_e('accaunt/applicationsCredit_2')?> <?= $user->place ?></p>-->
    <p><?=_e('accaunt/applicationsCredit_3')?> <?= $user->reg_date ?></p>

<!--    <p><?=_e('accaunt/applicationsCredit_4')?> <? //= $user->summaCredit    ?></p> //сумма
<p><?=_e('accaunt/applicationsCredit_5')?> <? //= $user->summaInvest    ?></p>-->

    <p><?=_e('accaunt/applicationsCredit_6')?> <?= ($user->fsr < 0 ? 0 : $user->fsr) ?></p>
        <div class="rating-stars-block"><span><?=_e('accaunt/applicationsCredit_8')?></span>
        <div class="rating-stars" data-rate="<?=$user->dd_rate_start?>">
           <i class="rating-stars__star" data-rate-name=""></i>
           <i class="rating-stars__star" data-rate-name=""></i>
           <i class="rating-stars__star" data-rate-name=""></i>
           <i class="rating-stars__star" data-rate-name=""></i>
           <i class="rating-stars__star" data-rate-name=""></i>
           <i class="rating-stars__star" data-rate-name=""></i>
       </div>
    </div>

    <!--<p><img src="images/garant_stat1.png" alt="garant" />: $<?//= $user->max_loan_available ?></p>-->
    <? if (!empty($user->id)) { ?>

        <? if ( $user->id_user == get_current_user_id() ){ ?>
            <p style="color: orange; text-align: center;"><?=_e('Это ваша заявка')?></p>
        <? } else { ?>

        <button class="button" type="submit"  onclick="
                <?
                $borrow = ($user->debit->type == 2 ? '.borrow' : '.credit');
                ?>
                $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=none]').attr('selected', 'selected');
                $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=none]').attr('selected', 'selected');
                $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type!=none]').hide();
                $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type!=none]').hide();
                console.log('!1');
                <? if ($user->debit->garant == 1) { ?>
                    <? if ( $user->debit->direct == 1 && $user->debit->bonus == 7 ) {?>
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').show();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> .card_notice').show();


                    <? } elseif($user->debit->direct == 1 && $user->debit->bonus == 2 ) { ?>

                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=<?=$user->debit->account_type?>]').show();


                    <? } else { ?>

                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').show();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> .card_notice').hide();

			$('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=2]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=5]').hide();
                        $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=-]').attr('selected', 'selected');
                        <? if ( in_array($user->debit->bonus, [2]) ){ ?>
                            $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=2][data-type=account]').show();
                        <? } ?>
                        <? if ( in_array($user->debit->bonus, [5]) ) {?>
                            $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=1][data-type=account]').show();
                            $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=4][data-type=account]').show();
                            $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=5][data-type=account]').show();
                        <? } ?>
                        <? if ( in_array($user->debit->bonus, [6]) ){ ?>
                            $('.popoup_debit_credit.garant<?= $borrow; ?> select[name=payment_account]').find('option[value=6][data-type=account]').show();
                        <? } ?>


                    <? } ?>

                    $('.popoup_debit_credit.garant<?= $borrow; ?>')
                            .attr('data-type', 'credit')
                            .attr('data-id', '<?= $user->id ?>')
                            .attr('data-bonus', '<?=$user->debit->bonus?>')
                            .attr('data-garant', 1)
                            .show();

                <? } else { ?>

                    <? if ( $user->debit->direct == 1 &&  $user->debit->bonus == 7 ) {?>
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').show();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> .card_notice').show();

                    <? } elseif($user->debit->direct == 1 && $user->debit->bonus == 2 ){ ?>

                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=<?=$user->debit->account_type?>]').show();


                    <? } else { ?>

                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=card]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[data-type=account]').show();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> .card_notice').hide();

			$('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=2]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=5]').hide();
                        $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=-]').attr('selected', 'selected');
                        <? if ( in_array($user->debit->bonus, [2]) ){ ?>
                            $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=2][data-type=account]').show();
                        <? } ?>
                        <? if ( in_array($user->debit->bonus, [5]) ) {?>
                            $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=1][data-type=account]').show();
                            $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=4][data-type=account]').show();
                            $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=5][data-type=account]').show();
                        <? } ?>
                        <? if ( in_array($user->debit->bonus, [6]) ){ ?>
                            $('.popoup_debit_credit.standart<?= $borrow; ?> select[name=payment_account]').find('option[value=6][data-type=account]').show();
                        <? } ?>



                    <? } ?>

                    $('.popoup_debit_credit.standart<?= $borrow; ?>')
                            .attr('data-type', 'credit')
                            .attr('data-id', '<?= $user->id ?>')
                            .attr('data-bonus', '<?=$user->debit->bonus?>')
                            .attr('data-garant', 0)
                            .show();
                <? } ?>
                $('#popup_credit').addClass('show');
                " name="submit" value=""><?=_e('accaunt/applicationsCredit_7')?></button>
        <? } ?>

    </div><? } ?>
</div>