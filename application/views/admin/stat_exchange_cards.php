<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid black;
  }
  table td,table th{
    border: 1px solid black;
    text-align: center;
    padding: 2px;
  }
</style>

<table>
    <thead>
        <tr>
            <th></th>
            <?foreach( $dates as $date){?>
            <th><?=$date?></th>
            <?}?>
            <th>Итого</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($paysys as $pk=>$p) { ?>
        <tr>
            <td><b><?=$p?></b></td>
            <?foreach( $dates as $date){?>
                <td>$ <?=$results[$pk][$date]?></td>
            <?}?>
            <td>$ <?=$results[$pk]['total']?></td>
        </tr>
        <? } ?>
        <tr>
            <td><b>Итого</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['total'][$date]?></b></td>
            <?}?>
              <td><b>$ <?=$results['total']['total']?></b></td>
        </tr>
        
    </tbody>
</table>