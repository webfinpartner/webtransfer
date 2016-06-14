<table>
	<tr>
		<th><?=_e('Ставка')?></th>
		<th><?=_e('Сделки')?></th>
		<th><?=_e('Ср. Сумма')?></th>
		<th><?=_e('Ср. Срок')?></th>
		<th><?=_e('Всего')?></th>
		<th><?=_e('Сделки')?></th>
		<th><?=_e('Ср. Сумма')?></th>
		<th><?=_e('Ср. Срок')?></th>
		<th><?=_e('Всего')?></th>
	</tr>
	<? foreach($today as $item):?>
		<tr>
			<td><?= $item->percent ?></td>
			<td><?= $item->total_deals ?></td>
			<td><?= $item->avg_sum ?></td>
			<td><?= $item->avg_time ?></td>
			<td><?= $item->total_sum ?></td>
			<?$yesterday_item = $yesterday[$item->percent]?>

			<td><?= $yesterday_item->total_deals ?></td>
			<td><?= $yesterday_item->avg_sum ?></td>
			<td><?= $yesterday_item->avg_time ?></td>
			<td><?= $yesterday_item->total_sum ?></td>
		</tr>
	<? endforeach;?>
</table>