<div class="widget">
    <div class="title">
        <img src="images/icons/dark/full2.png" alt="" class="titleIcon"/>
        <h6><?= $title ?> методов пополнения</h6></div>
    <table cellpadding="0" cellspacing="0" border="0" class="display dTable" data-page-length='15'>
        <thead>
        <tr>
            <th>Метод</th>
            <th>Всего</th>
            <th>Сегодня</th>
            <th>Вчера</th>
        </tr>
        </thead>
        <tbody>

        <?php

        if(!empty($list_method)) {
            foreach ($list_method as $item) {
                echo '
					<tr class="gradeA" >
            			<td>' . $item->method_name . '</td>
            			<td>$ ' . price_format_double($item->all) . '</td>
            			<td>$ ' . price_format_double($item->yesterday) . '</td>
            			<td>$ ' . price_format_double($item->today) . '</td>
					</tr>
            		';
            }
        }
        ?>

        </tbody>
    </table>

</div>

</div>