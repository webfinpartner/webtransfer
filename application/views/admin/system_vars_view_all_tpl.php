<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    .dTable tr.link{
        cursor:  pointer;
    }
    #main{
        height:800px !important;
    }
</style>
<a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/<?= $controller ?>/create"><span>Добавить <?= $view_all['one'] ?></span></a>
<div class="widget">
    <div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Управление  <?= $view_all['many'] ?></h6></div>
    <table cellpadding="0" cellspacing="0" border="0" class="display dTable">
        <thead>
            <tr>
                <? foreach($view_all['fields'] as $name=> $field){ if($name=='action')continue; echo "<th>$name</th>";}?>
                <th width="10%">Действия</th>
            </tr>
        </thead>
        <script>$(function () {
                $(".dTable tr.link").on("click", function ()
                {
                    href = $(this).attr("id");
                    window.location = "<?= base_url() ?>opera/<?= $controller ?>/" + href;
                });
            });</script>
        <tbody>
            <?php
            if (!empty($items)) {

                foreach ($items as $item) {
                    echo '<tr class="link" id="' . $item->$id_element . '">';

                    foreach ($view_all['fields'] as $name => $field) {
                        if ($name == 'action')
                            continue;
                        echo "<td>" . tpl_field($field, $item) . "</td>";
                    }
                    echo '<td class="center">'
                    . '<a title="Редактировать" class="smallButton" href="/opera/' . $controller . '/' . $item->$id_element . '" >'
                    . '<img src="images/icons/018.png">'
                    . '</a>'
                    . '<a  title="Удалить" href="/opera/' . $controller . '/delete/' . $item->$id_element . '" class="smallButton del">'
                    . '<img src="images/icons/101.png">'
                    . '</a>'
                    . ((isset($view_all['fields']['action'])) ? tpl_field($view_all['fields']['action'], $item) : "")
                    . '</td>'
                    . '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
    
    <form id="validate" class="form" action="" method="post" style="margin-top: 20px;">
        <fieldset>
            <div class="widget">
                <div class="title">
                    <img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Кусры валют по рынку на сегодня</h6>
                </div>
                <?php foreach( $rates as $r ): ?>
                    <?php foreach( $r->average_data as $val ): ?>
                        <div class="formRow">
                            <label><?= $r->ps_name ?>:</label>
                            <div class="formRight">
                                <input type="text" value="<?= $val['rate'] .' '. $val['currency_name'] ?>" readonly="">
                            </div>
                            <div class="clear"></div>
                        </div>                          
                    <?php endforeach; ?>
                <?php endforeach; ?>
        </fieldset>
    </form>
</div>

</div>

