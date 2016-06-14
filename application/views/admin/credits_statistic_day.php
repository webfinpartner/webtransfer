<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="stat_day_form">

	Показывать даты от
	<input type="text" class="maskDateTire" name="start_date" value="<?= $start_date ?>">
	- до
	<input type="text" class="maskDateTire" name="end_date" value="<?= $end_date ?>">
	<input type="submit" name="Показать"/>
</div>
<script>
	$("#stat_day_form [type=submit]").click(function () {
		window.location = '/opera/payment/stat_day/' + $("#stat_day_form [name=start_date]").val() + '/' + $("#stat_day_form [name=end_date]").val()
	})
	$(".maskDateTire").mask("99-99-9999");
</script>
<style>
	table.stat_day td {
		text-align: right;
	}
	table.stat_day td:first-child {
		text-align: left;
	}
</style>
<div class="widget">
	<div class="title">
		<img src="images/icons/dark/full2.png" alt="" class="titleIcon"/>
		<h6><?= $title ?> методов пополнения</h6></div>
	<table cellpadding="0" cellspacing="0" border="0" class="display dTable stat_day">
		<thead>
		<tr>
			<th>Метод</th>
			<th>Всего</th>
			<? foreach ($dates as $item) { ?>
				<th><?= $item ?></th>
			<? } ?>
		</tr>
		</thead>
		<tbody>

		<?php

		if(!empty($list_method)) {
			foreach ($list_method as $item) { ?>
				<tr class="gradeA">
					<td><?= $item->method_name ?></td>
					<td>$ <?= price_format_double($item->all) ?></td>

					<?foreach ($dates as $item_date) { ?>
						<td>$ <?= price_format_double($item->$item_date) ?></td>
					<?
					} ?>
				</tr>
			<? }
		}
		?>
		</tbody>
	</table>
</div>
</div>
