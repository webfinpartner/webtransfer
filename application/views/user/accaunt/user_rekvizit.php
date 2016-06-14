<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_1')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->name?> <?=$user->sername?> (<?=$user->id_user?>)
					</div>
					<div class="clear"></div>
				</div>


				<? if(!empty($user->bank_cc)): ?>
<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_2')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_cc?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_qiwi)): ?>
				<div class="formRow">

					<label><?=_e('accaunt/user_rekvizit_3')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_qiwi?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_paypal)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_4')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_paypal?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_tinkoff)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_5')?>  </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_tinkoff?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
					<? if(!empty($user->webmoney)): ?>
					<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_6')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->webmoney?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>

				<? if(!empty($user->bank_liqpay)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_7')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_liqpay?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>

				<? if(!empty($user->bank_yandex)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_8')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_yandex?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>




				<? if(!empty($user->bank_name)): ?>
<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_9')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_name?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_schet)): ?>
				<div class="formRow">

					<label><?=_e('accaunt/user_rekvizit_10')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_schet?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_bik)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_11')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_bik?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
				<? if(!empty($user->bank_kor)): ?>
								<div class="formRow">
					<label><?=_e('accaunt/user_rekvizit_12')?> </label>
					<div class="formRight" style="width:auto;">
						<?=$user->bank_kor?>
					</div>
					<div class="clear"></div>
				</div>
				<?endif?>
