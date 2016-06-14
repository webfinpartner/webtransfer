<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
	<?=_e('accaunt/partner_expected_income_1')?>
</div>

<style>
	.payment_table th, .user_reg, .user_soc, td {
		text-align: center;
	}

	.user_photo img {
		padding: 5px;
	}

	td {
		font-size: 12px;
		padding: 3px;
	}

	.valunteer {
		color: green;
	}
</style>
<table width="100%" class="payment_table">
	<thead>
	<tr>
		<th colspan="2">№</th>

		<th><?=_e('accaunt/partner_expected_income_2')?></th>

		<th><?=_e('accaunt/partner_expected_income_3')?></th>
		<th><?=_e('accaunt/partner_expected_income_4')?></th>
		<th><?=_e('accaunt/partner_expected_income_5')?></th>
		<th><?=_e('accaunt/partner_expected_income_6')?></th>
		<th><?=_e('accaunt/partner_expected_income_7')?></th>
		<th><?=_e('accaunt/partner_expected_income_8')?></th>

	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($list as $item) {
		?>
		<tr>
			<td>
				<?= $item->id ?>
			</td>
			<td>
				<? if($item->sum_arbitration > 0): ?>
					<img  style="width:12px;" src="/images/arbitration.png" />
				<? else: ?>
					<img src="/images/garant_stat<?= $item->garant ?>.png" style="width:12px;" />
				<? endif ?>
			</td>
			<td>
				<?= date_formate_my($item->date_kontract, 'd/m/Y') ?>
			</td>
			<td style="text-align:left;padding-left:10px;">
				<?= $item->name ?> <?= $item->sername ?> <span style="font-size:11px;color:#858585;display:block;margin-top:-8px;"><?= $item->id_user ?>  <?= (($item->valunteer) ? _e('accaunt/partner_expected_income_9') : '') ?></span>
			</td>
			<td>
				<?= $item->summa ?>
			</td>
			<td><?= $item->percent ?>%
			</td>
			<td>
				<?= $item->time ?>
			</td>
			<td>
				<?= date_formate_my($item->out_time, 'd/m/Y') ?>
			</td>
			<td>$ <?= "$item->income" ?> </td>
		</tr>
	<? } ?>
	</tbody>
</table>
</div>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>