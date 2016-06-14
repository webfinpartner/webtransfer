<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
      <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6><?=$title?></h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            		<? if(!empty($items))
		{?>
            <tr>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Дата заявки</th>
            </tr>
            </thead>
            <script>$(function(){$(".gradeA").on('click',function()
                {
                	href=$(this).attr("id");
                	window.location="<?=base_url()?>opera/users/profile/"+href;
                });
            $(".gradeA").css('cursor','pointer')});</script>
            <tbody>
            <?php



			foreach($items as $item)
			{
				echo '
			<tr class="gradeA" id="'.$item->id_user.'">
            			<td class=" sorting_1">'.$item->sername.' - '.$item->name.' - ' .$item->patronymic.'</td>
            			<td>'.$item->phone.'</td>
            			<td>'.$item->date.'</td>
			</tr>';
			}

		}
		else echo"<tr><td style='text-align:center'>Список заявок пуст</td></tr>";
		?>

           </tbody></table>

           </div>



