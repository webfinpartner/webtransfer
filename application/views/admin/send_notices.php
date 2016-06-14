<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

            <div style="float: right;">
                <a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/send_notices/create"><span>Новая отправка</span></a>
            </div>

            <div class="widget">
            <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Отправка уведомлений</h6></div>
           
            
            <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Статус</th>
                <th>Количество человек</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <script>$(function(){$(".gradeA").on('click',function()
                {
                	href=$(this).attr("id");
                	window.location="<?=base_url()?>opera/send_notices/"+href;
                });
            $(".gradeA").css('cursor','pointer')});</script>

            <?php

		if(!empty($sends))
		{

			foreach($sends as $item)
			{
				echo '<tr class="gradeA" id="'.$item->id.'"><td class=" sorting_1">'.$item->send_datetime.'</td>';
            			echo '<td>'.$item->status_text.'</td>
                                      <td>'.$item->recipient_count.'</td>
				<td class="center"><a title="Ответить" class="smallButton" href="opera/send_notices/'.$item->id.'" ><img src="images/icons/018.png"></a>
<!--a title="Удалить" href="opera/send_notices/delete/'.$item->id.'" class="smallButton del"><img src="images/icons/101.png"></a-->
            		';
            		echo'</td></tr>';
			}

		}
		?>

           </tbody></table>

           </div>

            </div>

