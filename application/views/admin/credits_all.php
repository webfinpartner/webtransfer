<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
 <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6><?=$title?></h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
            <th>ФИО</th>
            <th>Дата контракта</th>
            <th>Контракт</th>
            <th>Срок</th>
            <th>Сумма</th>
            <th>Процент</th>
			<?if($type==2):?>
			<th>Прибыль</th>
			<th>Отчисление</th>
			<? elseif ($type==1):?>
			<th>Начисление</th><?  endif;?>
            <th>Сумма возврата</th>
            <th>Дата возврата</th>
			<?if($type==2):?><th>Гарант</th><?  endif;?>
            <th>Статус</th>

            </tr>
            </thead>
            <tbody>


            <?php

		if(!empty($items))
		{

			foreach($items as $item)
			{
			$user = debit_user($item);?>
			<tr class="gradeA" >
            			<td class=" sorting_1">
            				<a href="/opera/users/<?=$item->id_user?>"><?=$user->sername.' - '.$user->name.' - ' .$user->patronymic?></a>
            			</td>
            			<td><?=date_formate_view($item->date_kontract)?></td>
            			<td><?=$item->kontract?></td>
            			<td><?=$item->time?> дней</td>
            			<td>$ <?=price_format_double($item->summa) ?></td>
            			<td><?=$item->percent?>%</td>
            			<?if($type==2){
							if($item->garant==1)
								$percent = $item->income*(garantPercent($item->time)/100);
							else
								$percent = $item->income*(10/100);
							?>

						<td>$ <?=price_format_double($item->income)?></td>
						<td>$ <?=price_format_double($percent)?></td>
						<td>$ <?=price_format_double($item->out_summ-$percent)?></td>
							<?} else{?>
						<td>$ <?=price_format_double($item->income)?></td>
						<td>$ <?=price_format_double($item->out_summ)?></td>
							<?}?>
            			<td><?=date_formate_view($item->out_time)?></td>
            			<?if($type==2):?><td><?=(($item->garant==1)?'Да':'Нет')?></td><?  endif;?>

						<td class="center"><a title="Редактировать" class="smallButton" href="opera/credit/view/<?=$item->id?>" ><img src="images/icons/018.png"></a><br />
							<?=check_status($item->state);?>
						</td>
			</tr>
			<?}

		}
		?>

           </tbody></table>

           </div>

            </div>

