<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
            <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6><?=$title?></h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
            <th>ФИО Заемщика</th>
			<th>ФИО Вкладчика</th>
            <th>Дата заявки</th>
            <th>Сумма</th>
            <th>Срок</th>
            <th>Сумма возврата</th>
            <th>Статус</th>

            </tr>
            </thead>
            <tbody>


            <?php

		if(!empty($items))
		{

			foreach($items as $item)
			{
			$user=debit_user($item);
			$user2=$this->credit->debit_user($item->id_user2);
			?>
			<tr class="gradeA" >
            			<td><a href="/opera/users/<?=$item->id_user?>"><?=$user->sername.' - '.$user->name.' - ' .$user->patronymic?></a></td>
						<td><a href="/opera/users/<?=$user2->id_user?>"><?=$user2->sername.' - '.$user2->name.' - ' .$user2->patronymic?></a></td>
            			<td  class="sorting_1"><?=$item->date?></td>
            			<td>$ <?=price_format_double($item->summa) ?></td>
            			<td><?=$item->time?></td>
            			<td>$ <?=price_format_double($item->out_summ)?></td>
						<td class="center"><a title="Ответить" class="smallButton" href="opera/credit/offer/<?=$item->id?>" ><img src="images/icons/018.png"></a>
							<a title="Удалить" href="" class="smallButton del"><img src="images/icons/101.png"></a><br />
							<?=check_status($item->state)?>
						</td>
			</tr>
			<?}
		}?>

           </tbody></table>

           </div>

            </div>

