<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/filter.css" type="text/css" media="screen" />

<style>
    .pmt {
        cursor: pointer !important;
        float: none !important;
        margin-right: 0px !important;
        padding: 0 !important;
    }

    .zayavka_ind:hover {background-color:#fff;}
</style>

<link rel="stylesheet" href="/css/user/partner.css">

<table colspan="0" border="0" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th>№</th>
            <th><?=_e('new_188')?></th>
            <th><?=_e('new_189')?></th>
            <th><?=_e('new_190')?></th>
            <th><?=_e('new_191')?> <?=(($type == 1)? _e('new_193') : _e('new_194')); ?></th>
            <th><?=_e('new_192')?> %</th>
            <th><?=(($type == 1)? _e('new_195') : _e('new_196')) ?></th>
        </tr>
    </thead>
    <tbody>
        <?
        if (!empty($credits)) {
            foreach ($credits as $item)
            {

                echo "<tr>";
                echo "<td>" . $item->id . "</td>";
                echo "<td><img src=\"/images/garant_stat" . $item->garant . ".png\" style=\"width:12px;\"></td>";
                if (isset($item->date_kontract) && isset($item->out_time))
                {
                    echo "<td>" . $item->date_kontract . "</td>";
                    echo "<td>" . $item->out_time . "</td>";
                }
                else
                {
                    echo "<td colspan=\"2\"><i>" . _e('new_197') . "</i></td>";
                }
                echo "<td>" . $item->summa . "</td>";
                echo "<td>" . $item->percent . "</td>";
                echo "<td>" . $item->income . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
    <thead>
        <tr>
            <th>№</th>
            <th><?=_e('new_188')?></th>
            <th><?=_e('new_189')?></th>
            <th><?=_e('new_190')?></th>
            <th><?=_e('new_191')?> <?=(($type == 1)? _e('new_193') : _e('new_194')); ?></th>
            <th><?=_e('new_192')?> %</th>
            <th><?=(($type == 1)? _e('new_195') : _e('new_196')) ?></th>
        </tr>
    </thead>
</table>
    