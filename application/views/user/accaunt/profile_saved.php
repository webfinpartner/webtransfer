<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6017401974666&amp;cd[value]=0.00&amp;cd[currency]=RUB&amp;noscript=1"/></noscript>

<div class="widget" style="border: none;">

		<p style="padding-left: 0;"><?=_e('accaunt/profile_saved_1')?></p>
		<h3><?=_e('accaunt/profile_saved_2')?></h3>
                
                 <? if ( isset($_SESSION['profile_card_create_now'])){?>
                <br>
                    <? if ( $_SESSION['profile_card_create_now'] == TRUE){?>    
                        <?=sprintf(_e('Карта успешно создана. <a href="%s">Перейти</a> к списку карт.'), site_url('account/card-lst'))?>
                    <? } else { ?>
                        <?=sprintf(_e('Не удалось создать карту. Попробуйте еще раз на <a href="%s">этой</a> странице.'), site_url('account/card/virtual'))?>
                    <? } ?>
                 <? unset($_SESSION['profile_card_create_now']); } ?>
                 
</div>