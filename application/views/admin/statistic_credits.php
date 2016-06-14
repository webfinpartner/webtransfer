<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="widget">
    <div class="title">
        <img src="images/icons/dark/full2.png" alt="" class="titleIcon"/>
        <h6><?= $title ?></h6></div>
    <table cellpadding="0" cellspacing="0" border="0" class="display dTable" data-page-length='30'>
        <thead>
        <tr>
            <th>Процент</th>
            <th>Вклады - Висят</th>
            <th>Вклады Сегодня</th>
            <th>Вклады Вчера</th>
            <th>Арбитр Сегодня</th>
            <th>Арбитр Вчера</th>
        </tr>
        </thead>
        <tbody>

        <?php

        if(!empty($list)) {
            foreach ($list as $item) {
                echo '
                            <tr class="gradeA" >
            			<td>' . $item->percent . '%</td>
            			<td style="text-align:center";>' . $item->credit . '</td>
            			<td style="text-align:center;">' . $item->credit_yesterday . '</td>
                                <td style="text-align:center;">' . $item->credit_today . '</td>
                                <td style="text-align:center;">' . $item->arbitr_yesterday . '</td>
                                <td style="text-align:center;">' . $item->arbitr_today . '</td>
                            </tr>
            		';
            }
        }
        ?>

        </tbody>
    </table>

</div>

</div>

