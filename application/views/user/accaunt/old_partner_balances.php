<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/partner.css">
<style>
tr td:first-child img[width="35px"]
{
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    border: 1px solid #f5f5f5;
} 

tr:nth-child(even) td:first-child img[width="35px"]
{
    border: 1px solid #cccccc !important;
} 

td[action]
{
    line-height: 16px;
}

td[action] a
{
    text-decoration: none;
}
</style>

<table colspan="0" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?=_e('new_183')?></th>
            <th><?=_e('new_184')?></th>
            <th><?=_e('new_185')?></th>
            <th><?=_e('new_186')?></th>
            <th><?=_e('new_187')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) {
            $name = (empty($item->name) && empty($item->sername)) ? "<i>" . _e('new_182') . "</i>" : $item->name . " " . $item->sername;
            echo "<tr>";
                echo "<td>" . $name . "<br /><span style=\"font-size:10px;display:block;margin-top:-7px;color:#8a8a8a;\">" . $item->id_user . "</td>";
                echo "<td>" . $balance[$item->id_user][0] . "$</td>";
                echo "<td>" . $balance[$item->id_user][1] . "$</td>";
                echo "<td>" . $balance[$item->id_user][2] . "$<br /><span style=\"font-size:10px;display:block;margin-top:-7px;color:#8a8a8a;\">" . $balance[$item->id_user][3] . " " . $balance[$item->id_user][4] . "</td>";
                echo "<td action><a href=\"partner/transactions/$item->id_user\">[Т]</a><br /><a href=\"partner/invests/$item->id_user\">[В]</a><br /><a href=\"partner/credits/$item->id_user\">[К]</a></td>";
            echo "</tr>";
        }?>
    </tbody>
    <thead>
        <tr>
            <th><?=_e('new_183')?></th>
            <th><?=_e('new_184')?></th>
            <th><?=_e('new_185')?></th>
            <th><?=_e('new_186')?></th>
            <th><?=_e('new_187')?></th>
        </tr>
    </thead></table>