<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

            <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>База запросов</h6></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
            <th>Тип</th>
            <th>Маил</th>
            <th>Дата</th>
            <th>Статус</th>

            </tr>
            </thead>
            <tbody>
            <script>$(function(){$(".gradeA").on('click',function()
                {
                	href=$(this).attr("id");
                	window.location="<?=base_url()?>opera/feedback/"+href;
                });
            $(".gradeA").css('cursor','pointer')});</script>

            <?php

		if(!empty($feedback))
		{

			foreach($feedback as $item)
			{
				echo '
			<tr class="gradeA" id="'.$item->id.'">
            			<td class=" sorting_1">';
            			echo ($item->state==2)? "Перезвонить":"Вопрос";
            			echo '</td>
            			<td>'.$item->email.'</td>
            			<td>'.$item->date.'</td>
				<td class="center"><a title="Ответить" class="smallButton" href="opera/feedback/'.$item->id.'" ><img src="images/icons/018.png"></a>
<a title="Удалить" href="opera/feedback/delete/'.$item->id.'" class="smallButton del"><img src="images/icons/101.png"></a>
            		';

            		if($item->admin_state==1)echo  '<img src="images/icons/152.png">';
            		elseif($item->admin_state==2) echo '<img src="images/icons/160.png">';
            		else echo  '<img src="images/icons/151.png">';
            		echo'</td></tr>';
			}

		}
		?>

           </tbody></table>

           </div>

            </div>

