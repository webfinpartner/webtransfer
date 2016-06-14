<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
            <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>История  изминений</h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
	   <th>Тип</th>
            <th>ФИО</th>
            <th>Модератор</th>
            <th>Дата изменения</th>
            <th>Изменен на</th>
            <th>IP</th>
            <th>Примечание</th>
	  <th>Причина отказа</th>

            </tr>
            </thead>
            <tbody>


            <?php

view_log('credit', $credits,$this);


		function view_log($type, $items,  $context)
		{
					if(!empty($items))
		{


			foreach($items as $item)
			{
			$user=$context->credit->debit_user($item->id_user);
			$manager =($context->admin_info->manager==1  and !empty($item->id_admin))?"<a href='/opera/admins/$item->id_admin' >$item->family $item->name</a>" : $item->login;

			$typ = ( $context->credit->check_state($item->i_state) )? "<a  href='/opera/credit/$item->id' >".(($item->type==1)?"Кредит":"Вклад")."</a>" :
												"<a  href='/opera/credit/view/$item->id' >".(($item->type==1)?"Кредит":"Вклад")."</a>";
				echo '
			<tr class="gradeA" >
            			<td class=" sorting_1">'.$typ.'</td>
				<td><a href="/opera/users/'.$item->id_user.'">'.$user->sername.' - '.$user->name.' - ' .$user->patronymic.'</a></td>
            			<td>'.$manager.'</td>
            			<td>'.$item->date .'</td>
            			<td>'.check_status($item->state).'</td>
            			<td>'.$item->ip.'</td>
				<td>'.(empty($item->note)? " ": $item->note ).'</td>
				<td>'.(empty($item->cause)? " ": $item->cause ).'</td>
				</tr>';
			}

		}
		}


		?>

           </tbody></table>

           </div>

            </div>

