<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
            <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6><?=$title?></h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
            <th>ФИО</th>
			<th>№ заявки</th>
            <th>Дата заявки</th>
            <th>Тип</th>
            <th>Сумма</th>
            <th>Срок</th>
			 <th>Процент</th>
			<?if($type==2):?>
			<th>Прибыль</th>
			<th>Отчисление</th>
			<? elseif ($type==1):?>
			<th>Начисление</th><?  endif;?>
            <th>Сумма возврата</th>
			<?if($type==2):?><th>Гарант</th><? endif;?>
            <th>Статус</th>
            <th>Действия</th>

            </tr>
            </thead>
            <tbody>


            <?php

		if(!empty($items))
		{

			foreach($items as $item)
			{
			$user=debit_user($item);?>
			<tr class="gradeA" >
            			<td><a href="/opera/users/<?=$item->id_user?>"><?=$user->sername.' - '.$user->name.' - ' .$user->patronymic?></a></td>
						<td  class="sorting_1"><?=$item->id?></td>
            			<td><?=$item->date?></td>
                        <td><?=($item->garant==1?'G':'S')?></td>
						<td>$ <?=  price_format_double($item->summa) ?></td>
            			<td><?=$item->time?></td>
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
						<?if($type==2){?><td><?=(($item->garant==1)?'Да':'Нет')?></td><?}?>
                        <td><?= accaunt_debit_status($item->state) ?></td>
						<td class="center"><a title="Ответить" class="smallButton" href="opera/credit/<?=$item->id?>" ><img src="images/icons/018.png"></a>
							<a title="Удалить" href="opera/credit/delete/<?=$item->id?>" class="smallButton del"><img src="images/icons/101.png"></a>
						</td>
			</tr>
			<?}

		}
		?>

           </tbody></table>

           </div>

            </div>

