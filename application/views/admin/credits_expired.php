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
            <th>Начисление</th>
            <th>Сумма возврата</th>
            <th>Дата возврата</th>
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
				echo '
			<tr class="gradeA" >
            			<td class=" sorting_1">
            				<a href="/opera/users/'.$item->id_user.'">'.$user->sername.' - '.$user->name.' - ' .$user->patronymic.'</a>
            			</td>
            			<td>'.date_formate_view($item->date_kontract).'</td>
            			<td>'.$item->kontract.'</td>
            			<td>'.$item->time.' дней</td>
            			<td>$ '.price_format_double($item->summa) .'</td>
            			<td>'.$item->percent.'%</td>
            			<td>$ '.price_format_double($item->income).'</td>
            			<td>$ '.price_format_double($item->out_summ).'</td>
            			<td>'.date_formate_view($item->out_time).'</td>


				<td class="center"><a title="Редактировать" class="smallButton" href="opera/credit/view/'.$item->id.'" ><img src="images/icons/018.png"></a><br />

            		';

            		echo check_status($item->state);

            		echo'</td></tr>';
			}

		}
		?>

           </tbody></table>

           </div>

            </div>

